<?php

namespace Library\Search;

use Exception;
use Elasticsearch\Client;
use Library\Mapper\Field\Date;
use Library\Mapper\Field\IntNumber;
use Library\Mapper\Field\Line;
use Library\Mapper\SqlMapperInterface;
use stdClass;

/**
 * Обьект для взаимодействия с контейнером данным ограниченного размера
 * @package Library\Search
 */
class Connection implements ConnectionInterface
{
    /**
     * Клиент
     * @var Client
     */
    protected $client;

    /**
     * Лимит на единоразовую вставку данных
     * @var int
     */
    protected $batchLimit = 50;

    /**
     * Временный массив данных (контейнер данных)
     * @var mixed[]
     */
    protected $insertQueue = [];

    /**
     * Задает обьект поискового запроса для общения с поисковым движком
     * ElasticConnection constructor.
     * @param Client $client
     * @param int $batchLimit
     */
    public function __construct(Client $client, int $batchLimit = 50)
    {
        $this->batchLimit = $batchLimit;
        $this->client = $client;
        $this->insertQueue = [];
    }

    /**
     * Отправляет очередь запросов.
     *
     * @param array $params
     * @return void
     */
    protected function flush(array $params)
    {
        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function save(SqlMapperInterface $mapper, array $item)
    {
        $body = $mapper->convertToStrings($mapper->mapArray($item), true);

        if ($this->batchLimit > 0) {

            $this->insertQueue['body'][] = [
                'index' => [
                    '_index' => strtolower($mapper->getIndexName()),
                    '_type' => strtolower($mapper->getTypeName()),
                    '_id' => $item[$mapper->getPrimary()]
                ]
            ];

            $this->insertQueue['body'][] = $body;

            if (count($this->insertQueue['body']) === $this->batchLimit) {
                $this->flush($this->insertQueue);
                $this->insertQueue = [];
            }

        } else {

            $params = [
                'index' => strtolower($mapper->getIndexName()),
                'type' => strtolower($mapper->getTypeName()),
                'id' => $item[$mapper->getPrimary()],
                'body' => $body,
            ];

            $this->client->index($params);
        }
    }

    /**
     * $query['bool']['must'][]['term']['username'] = strtolower('abcd');
     * $sort['create_at'] ='desc';
     * $sort = ['create_at'=>'desc', 'username'=>'asc', 'user'];
     * $fields = ['username','email']; //return few fields instead of all
     * $r = Users::find([
     *              'index'      => $index,
     *              'type'       => $type,
     *              'query'      => $query,
     *              'sort'       => $sort,
     *              'fields'     => $fields,
     *                      ]);
     *
     * @param array $opt
     * @return stdClass
     */
    public function find(array $opt): stdClass
    {
        $maxTotal = isset($opt['max_total']) ? intval($opt['max_total']) : 5000;
        $page = isset($opt['page']) ? intval($opt['page']) : 1;
        $limit = isset($opt['limit']) ? intval($opt['limit']) : 30;

        $query = isset($opt['query']) ? $opt['query'] : array();
        $sort = isset($opt['sort']) ? $opt['sort'] : array();
        $fields = isset($opt['fields']) ? $opt['fields'] : array();
        $groupby = isset($opt['aggs']) ? $opt['aggs'] : array();
        $scroll = isset($opt['scroll']) ? $opt['scroll'] : "";

        $index =  isset($opt['index']) ? $opt['index'] : "";
        $type =  isset($opt['type']) ? $opt['type'] : "";

        if (!$query) {
            $query = array("match_all" => new stdClass());
        }

        $offset = ($page - 1) * $limit;

        $params['index'] = $index;
        $params['type'] = $type;
        $params['size'] = $limit;
        $params['body']['query'] = $query;

        if ($limit > 0) {
            $params['from'] = $offset;
        }

        if ($sort) {
            if (!is_array($sort)) {
                $sort[] = $sort;
            }

            foreach ($sort as $k => $s) {
                $params['sort'][] = $k . ':' . $s;
            }
        }

        if ($fields) {
            if (!is_array($fields)) {
                $fields[] = $fields;
            }
            $params['_source'] = $fields;
        }

        if ($groupby) {
            $params['body']['aggs'] = $groupby;
        }

        if ($scroll) {
            $params['scroll'] = $scroll;
        }

        $response = $this->client->search($params);

        $total = isset($response['hits']['total']) ? intval($response['hits']['total']) : 0;
        $hits = isset($response['hits']['hits']) ? $response['hits']['hits'] : [];
        $aggs = isset($response['aggregations']) ? $response['aggregations'] : [];
        $scrollId = isset($response['_scroll_id']) ? $response['_scroll_id'] : "";

        $items = [];
        if ($total > 0 and $hits) {
            foreach ($hits as $hit) {
                $row = $hit['_source'];
                $items[] = $row;
            }
        }

        if ($maxTotal < $total) {
            $total = $maxTotal;
        }

        $result = $this->paginate($items, $total, $page, $offset, $limit);

        if ($aggs) {
            $result->aggs = $aggs;
        }

        if ($scrollId) {
            $result->scroll_id = $scrollId;
        }

        return $result;


    }

    /**
     * Формируем ответ с пагинацией
     * @param array $items
     * @param int $total
     * @param int $page
     * @param int $offset
     * @param int $limit
     * @return stdClass
     */
    protected function paginate(array $items, int $total, int $page, int $offset, int $limit): stdClass
    {

        $totalPage = ($limit === 0) ? 0 : ceil($total / $limit);
        $current = ($page > 0) ? $page : 1;
        $before = ($current > 1) ? $current - 1 : 1;
        $next = ($current < $totalPage) ? $current + 1 : $totalPage;

        $pagination = new stdClass();

        $pagination->total_items = $total;
        $pagination->total_pages = $totalPage;
        $pagination->first = 1;
        $pagination->before = $before;
        $pagination->current = $current;
        $pagination->next = $next;
        $pagination->last = $totalPage;
        $pagination->from = $offset + 1;
        $pagination->to = $offset + $limit;
        $pagination->items = $items;

        return $pagination;
    }

    /**
     * @inheritdoc
     */
    public function create(SqlMapperInterface $mapper)
    {
        $index = $mapper->getIndexName();
        $type = $mapper->getTypeName();
        $columns = $this->getFieldsTypes($mapper->getMap(true));

        // Define the initial parameters that will be sent to Elasticsearch.
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'address' => [
                                'type' => 'custom',
                                'tokenizer' => 'whitespace',
                                'trim' => ['trim', 'lowercase']
                            ],
                        ]
                    ],
                ],
                'mappings' => [
                    $type => [
                        'properties' => [],
                    ],
                ],
            ],
        ];


        foreach ($columns as $column => $typeColumn) {
            if (is_array($typeColumn)) {
                // Remember we used an array to define the types for dates. This is the only case for now.
                $params['body']['mappings'][$type]['properties'][$column] = [
                    'type' => $typeColumn[0],
                    'format' => $typeColumn[1],
                ];
            } else {
                $params['body']['mappings'][$type]['properties'][$column] = ['type' => $typeColumn];

                if ($typeColumn == 'text') {
                    $params['body']['mappings'][$type]['properties'][$column]['analyzer'] = 'address';
                }
            }
        }

        return $this->client->indices()->create($params);
    }

    /**
     * @inheritdoc
     */
    public function drop(SqlMapperInterface $mapper)
    {
        $index = $mapper->getIndexName();

        if ($this->client->indices()->exists(['index' => $index])) {
            return $this->client->indices()->delete(['index' => $index]);
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function delete(SqlMapperInterface $mapper, array $item)
    {
        $params = [
            'index' => strtolower($mapper->getIndexName()),
            'type' => strtolower($mapper->getTypeName()),
            'id' => $item[$mapper->getPrimary()],
        ];

       $this->client->delete($params);
    }

    /**
     * Указывает, что данный потребитель закончил работу с движком и нужно
     * дописать все оставшиеся запросы и сбросить все временные данные.
     *
     * @return void
     */
    public function complete()
    {
        $this->flush($this->insertQueue);
        $this->insertQueue = [];
    }

    /**
     * Получить описание полей
     * @param array $fields
     * @return array
     */
    private function getFieldsTypes(array $fields):array
    {
        $typeFields = [];

        foreach ($fields as $name => $field) {

            if ($field instanceof Line) {
                $typeFields[$name] = 'text';
            }

            if ($field instanceof IntNumber) {
                $typeFields[$name] = 'long';
            }

        }
        return $typeFields;
    }

    public function __destruct()
    {
        $this->flush($this->insertQueue);
    }
}