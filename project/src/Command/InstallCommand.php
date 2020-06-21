<?php

namespace Badges\Command;

use MongoDB\Database;
use Perfumer\Framework\Controller\PlainController;

class InstallCommand extends PlainController
{
    public function action()
    {
        $collection_names = $this->getContainer()->getParam('mongo/collections');
        $badges_lifetime = $this->getContainer()->getParam('badges/lifetime');

        if ($collection_names) {
            $collection_names = explode(',', $collection_names);
        }

        if ($collection_names) {
            try {
                /** @var Database $mongo_db */
                $mongo_db = $this->s('mongo.db');

                foreach ($collection_names as $collection_name) {
                    $collection = $mongo_db->selectCollection($collection_name);
                    $collection->createIndex(['user' => 1]);
                    error_log("Index for \"user\" field of collection $collection_name is created");
                    $collection->createIndex(['name' => 1]);
                    error_log("Index for \"name\" field of collection $collection_name is created");
                    $collection->createIndex(['keys' => 1]);
                    error_log("Index for \"keys\" field of collection $collection_name is created");
                    $collection->createIndex(['created_at' => -1], ['expireAfterSeconds' => (int) $badges_lifetime]);
                    error_log("Index for \"created_at\" field of collection $collection_name is created");
                }
            } catch (\Throwable $e) {
                error_log($e->getMessage());
            }
        }
    }
}
