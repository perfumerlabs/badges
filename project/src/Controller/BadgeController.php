<?php

namespace Badges\Controller;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;

class BadgeController extends LayoutController
{
    public function post()
    {
        $collection = (string) $this->f('collection');
        $user = (string) $this->f('user');
        $name = (string) $this->f('name');
        $payload = (array) $this->f('payload');

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

        if (!$name) {
            $this->forward('error', 'badRequest', ['"name" parameter must be set']);
        }

        if (!is_string($name)) {
            $this->forward('error', 'badRequest', ['"name" parameter is invalid']);
        }

        $keys = [$name];
        $pos_offset = 0;

        do {
            $pos = strpos($name, '/', $pos_offset);

            if ($pos !== false) {
                $keys[] = substr($name, 0, $pos);
                $pos_offset = $pos + 1;
            }
        } while ($pos !== false);

        try {
            /** @var Database $mongo_db */
            $mongo_db = $this->s('mongo.db');
            $collection = $mongo_db->selectCollection($collection);
            $collection->insertOne([
                'name' => $name,
                'user' => $user,
                'payload' => $payload,
                'keys' => $keys,
                'created_at' => new UTCDateTime(1000 * time()),
            ]);

            $this->getExternalResponse()->setStatusCode(201);

            error_log('Inserted ' . $name);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
