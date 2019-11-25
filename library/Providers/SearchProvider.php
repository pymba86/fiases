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

                $host = envValue('ES_HOST', 'elasticsearch');
                $port = envValue('ES_PORT', '9200');
                
                $clientHost = $host . ":" . $port;

                $batchLimit = envValue('ES_BATCH_LIMIT', 10000);

                $clientBuilder = ClientBuilder::create();
                $clientBuilder->setHosts([$clientHost]);
                $client = $clientBuilder->build();

                $connection = new Connection($client, $batchLimit);

                return $connection;
            });
    }
}

