<?php

namespace Library\Xml;

use InvalidArgumentException;
use Library\Mapper\XmlMapperInterface;
use RuntimeException;
use XMLReader;

/**
 *
 * @package Library\Services\Xml
 */
class Reader implements ReaderInterface
{

    /**
     * Обьект-маппер, который описывает как извлечь целевой обьект из xml
     * @var XmlMapperInterface | null
     */
    protected $mapper;

    /**
     * Абсолютный путь до файла
     * @var string
     */
    protected $pathToFile;

    /**
     * Обьект XMLReader для чтение документа
     * @var XMLReader
     */
    protected $reader;

    /**
     * Текущее смещение внутри массива
     * @var int
     */
    protected $position = 0;

    /**
     * Флаг, который указывает, что данные были прочитаны в буфер
     * @var bool
     */
    protected $isBufferFull = false;

    /**
     * Массив с буффером, для isValid и current
     * @var array | null
     */
    protected $buffer;

    /**
     * @inheritdoc
     */
    public function setMapper(XmlMapperInterface $mapper): ReaderInterface
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        if (!$this->isBufferFull) {
            $this->isBufferFull = true;
            $this->buffer = $this->getLine();
        }
        return $this->buffer;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        ++$this->position;
        $this->isBufferFull = true;
        $this->buffer = $this->getLine();
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        if (!$this->isBufferFull) {
            $this->isBufferFull = true;
            $this->buffer = $this->getLine();
        }
        return $this->buffer !== null;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->position = 0;
        $this->buffer = [];
        $this->isBufferFull = false;
        $this->seekXmlPath();
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function openFile(string $path): bool
    {
        $realPath = realpath($path);
        if (!$realPath || !file_exists($realPath)) {
            throw new InvalidArgumentException("File {$path} not found");
        }
        $this->pathToFile = $realPath;
        return $this->seekXmlPath();
    }

    /**
     * @inheritdoc
     */
    public function closeFile(): void
    {
        $this->unsetReader();
        $this->pathToFile = '';
    }

    /**
     * Возвращает разобранную в массив строку из файла или null, если разбор
     * файла завершен.
     *
     * @return array|null
     *
     * @throws RuntimeException
     */
    protected function getLine()
    {
        if (!$this->mapper || !$this->reader) {
            throw new RuntimeException('Mapper and reader must be set before reading');
        }
        $return = null;
        $nameFilter = $this->mapper->getXmlPathElement();
        $currentDepth = $this->reader->depth;
        $this->skipUselessXml($nameFilter, $currentDepth);
        //мы можем выйти из цикла, если найдем нужный элемент
        //или попадем на уровень выше - проверяем, что нашли нужный
        if ($nameFilter === $this->reader->name) {
            $return = $this->mapper->extractArrayFromXml($this->reader->readOuterXml());
            //нужно передвинуть указатель, чтобы дважды не прочитать
            //один и тот же элемент
            $this->reader->next();
        }
        return $return;
    }


    /**
     * Пропускает все xml элементы в текущем ридере, у которых имя или вложенность
     * не совпадают с указанным параметром.
     *
     * @param string $nodeName
     * @param int $nodeDepth
     *
     * @return void
     */
    protected function skipUselessXml(string $nodeName, int $nodeDepth)
    {
        while (
            $this->reader
            && $this->reader->depth === $nodeDepth
            && $nodeName !== $this->reader->name
            && $this->reader->next()
        ) ;
    }

    /**
     * Ищет узел заданный в маппере, прежде, чем начать перебор
     * элементов.
     *
     * Если собранный путь лежит в начале строки, которую мы ищем,
     * то продолжаем поиск.
     * Если собранный путь совпадает с тем, что мы ищем,
     * то выходим из цикла.
     * Если путь не совпадает и не лежит в начале строки,
     * то пропускаем данный узел со всеми вложенными деревьями.
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function seekXmlPath(): bool
    {
        $this->resetReader();
        if (!$this->mapper || !$this->reader) {
            throw new RuntimeException('Mapper and reader must be set before reading');
        }
        $path = $this->mapper->getXmlPathRoot() . '/' . $this->mapper->getXmlPathElement();
        $currentPath = [];
        $isCompleted = false;
        $readResult = $this->reader->read();
        while ($readResult) {
            array_push($currentPath, $this->reader->name);
            $currentPathStr = implode('/', $currentPath);
            if ($path === $currentPathStr) {
                $isCompleted = true;
                $readResult = false;
            } elseif (mb_strpos($path, $currentPathStr) !== 0) {
                array_pop($currentPath);
                $readResult = $this->reader->next();
            } else {
                $readResult = $this->reader->read();
            }
        }
        return $isCompleted;
    }


    /**
     * Пересоздает объект для чтения xml.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function resetReader()
    {
        if (empty($this->pathToFile)) {
            throw new RuntimeException('File not open');
        }
        $this->unsetReader();
        $this->reader = new XmlReader;
        if ($this->reader->open($this->pathToFile) === false) {
            throw new RuntimeException(
                "Can't open file {$this->pathToFile} for reading"
            );
        }
    }

    /**
     * Закрывает открытые ресурсы и ресетит все внутренние счетчики.
     *
     * @return void
     */
    protected function unsetReader()
    {
        if ($this->reader) {
            $this->reader->close();
            $this->reader = null;
        }
    }

    /**
     * Деструктор.
     *
     * Закрывает файл, если он все еще открыт.
     */
    public function __destruct()
    {
        $this->closeFile();
    }
}