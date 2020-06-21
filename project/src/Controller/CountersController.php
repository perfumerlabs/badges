<?php

namespace Badges\Controller;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;

class CountersController extends LayoutController
{
    public function get()
    {
        $collection = (string) $this->f('collection');
        $names = $this->f('names');
        $user = (string) $this->f('user');
        $counters = [];

        if (!$collection) {
            $this->forward('error', 'badRequest', ['"collection" parameter must be set']);
        }

        if (!is_string($collection)) {
            $this->forward('error', 'badRequest', ['"collection" parameter is invalid']);
        }

        if (!$user) {
            $this->forward('error', 'badRequest', ['"user" parameter must be set']);
        }

        if (!is_string($user)) {
            $this->forward('error', 'badRequest', ['"user" parameter is invalid']);
        }

        if (!$names) {
            $this->forward('error', 'badRequest', ['"names" parameter must be set']);
        }

        if (!is_string($names) && !is_array($names)) {
            $this->forward('error', 'badRequest', ['"names" parameter is invalid']);
        }

        if (is_string($names)) {
            $names = explode(',', $names);
        }

        try {
            /** @var Database $mongo_db */
            $mongo_db = $this->s('mongo.db');
            $mongo_collection = $mongo_db->selectCollection($collection);

            foreach ($names as $name) {
                $filters = [
                    'user' => $user,
                ];

                if ($name !== '_all') {
                    $filters['keys'] = $name;
                }

                $count = $mongo_collection->countDocuments($filters);

                $counters[$name] = $count;
            }
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }

        $this->setContent(['counters' => $counters]);
    }
}
