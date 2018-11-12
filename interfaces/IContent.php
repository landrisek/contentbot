<?php

namespace ContentBot;

/** @author Lubomir Andrisek */
interface IContent {
    
    public function keyword(string $keyword, array $used): array;

    public function keywords(array $wildcards): int;

    public function write(int $id, string $content): void;

}
