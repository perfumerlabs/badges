<?php

namespace Badges\Controller;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;

class BadgesController extends LayoutController
{
    public function get()
    {
        $collection = (string) $this->f('collection');
        $name = (string) $this->f('name');
        $user = (string) $this->f('user');
        $badges = [];

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

        try {
            /** @var Database $mongo_db */
            $mongo_db = $this->s('mongo.db');
            $mongo_collection = $mongo_db->selectCollection($collection);

            $cursor_filters = [
                'user' => $user,
            ];

            if ($name) {
                $cursor_filters['keys'] = $name;
            }

            $cursor = $mongo_collection->find($cursor_filters, [
                'sort' => ['created_at' => -1]
            ]);

            foreach ($cursor as $item) {
                /** @var UTCDateTime $created_at */
                $created_at = $item['created_at'];

                $badge = [
                    'name' => $item['name'],
                    'user' => $item['user'],
                    'created_at' => $created_at->toDateTime()->format('Y-m-d H:i:s'),
                ];

                $payload = $item['payload'] ?? null;

                if (count($payload) > 0) {
                    $badge['payload'] = $payload;
                }

                $badges[] = $badge;
            }
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }

        $this->setContent(['badges' => $badges]);
    }

    public function delete()
    {
        $collection = (string) $this->f('collection');
        $name = (string) $this->f('name');
        $user = (string) $this->f('user');

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

        try {
            /** @var Database $mongo_db */
            $mongo_db = $this->s('mongo.db');
            $mongo_collection = $mongo_db->selectCollection($collection);

            $cursor_filters = [
                'user' => $user,
            ];

            if ($name) {
                $cursor_filters['keys'] = $name;
            }

            $mongo_collection->deleteMany($cursor_filters);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
