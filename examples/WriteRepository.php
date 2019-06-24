<?php

namespace ContentBot\Demo;

use Nette\Database\Context;

/** @author Lubomir Andrisek */
final class WriteRepository {

    /** @var Context */
    protected $database;

    public function __construct(Context $database) {
        $this->database = $database;
    }

    public function getWrite(int $id): array {
        return $this->database->table($this->source)
                    ->where('id', $id)
                    ->fetch()
                    ->toArray();
    }

    public function updateWrite(int $id, array $data): int {
        return $this->database->table($this->source)
                        ->where('id', $id)
                        ->update($data);
    }

}