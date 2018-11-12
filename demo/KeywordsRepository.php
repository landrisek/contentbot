<?php

namespace ContentBot\Demo;

use Nette\Database\Context;

/** @author Lubomir Andrisek */
final class KeywordsRepository {

    /** @var Context */
    protected $database;

    public function __construct(Context $database) {
        $this->database = $database;
    }

    public function getKeyword(string $keyword, array $used): array {
        $resource = $this->database->table($this->source)
                        ->where('content LIKE',  '%' . strtolower($keyword) . '%');
        foreach($used as $usage) {
            $resource->where('content NOT LIKE', '%' . strtolower($usage) . '%');
        }
        if(null == $row = $resource->fetch()) {
            return [];
        }
        return $row->toArray();
    }

}