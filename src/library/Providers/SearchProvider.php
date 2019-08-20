<?php

namespace Library\Providers;

use Elasticsearch\ClientBuilder;
use Library\Search\Connection;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

use function Library\Core\envValue;

class SearchProvider implements ServiceProviderInterface
{

    public function register(DiInterface $container)
    {
        $container->setShared(
            'search',
            function () {

                $host = envValue('DATA_API_ELASTICSEARCH_HOST', 'elasticsearch:9200');

                $batchLimit = envValue('DATA_API_ELASTICSEARCH_BATCH_LIMIT', 50);

                $clientBuilder = ClientBuilder::create();
                $clientBuilder->setHosts([$host]);
                $client = $clientBuilder->build();

                $connection = new Connection($client, $batchLimit);

                return $connection;
            });
    }
}

