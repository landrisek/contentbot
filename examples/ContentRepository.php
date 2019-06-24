<?php

namespace ContentBot\Examples;

use Nette\Database\Context;

/** @author Lubomir Andrisek */
final class ContentRepository extends BaseRepository {

    /** @var Context */
    protected $database;

    public function __construct(Context $database) {
        $this->database = $database;
    }

    public function getKeywords(array $wildcards): int {
        $query = 'SELECT COUNT(*) AS summary FROM ' . $this->source . ' WHERE ';
        foreach ($wildcards as $wildcard) {
            $query .= ' (';
            foreach ($wildcard as $like) {
                $query .= 'text_cs LIKE BINARY "' . $like . '" OR ';
            }
            $query = rtrim($query, ' OR') . ') AND ';
        }
        return $this->database->query(rtrim($query, ' AND'))->fetch()->summary;
    }

}