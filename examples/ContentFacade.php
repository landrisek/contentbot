<?php

namespace ContentBot\Examples;

use ContentBot\IContent;

/** @author Lubomir Andrisek */
final class ContentFacade implements IContent {

    /** @var ContentRepository */
    private $contentRepository;

    /** @var KeywordsRepository */
    private $keywordsRepository;

    /** @var WriteRepository */
    private $writeRepository;

    public function __construct(ContentRepository $contentRepository, KeywordsRepository $keywordsRepository, WriteRepository $writeRepository) {
        $this->contentRepository = $contentRepository;
        $this->keywordsRepository = $keywordsRepository;
        $this->writeRepository = $writeRepository;
    }

    public function keyword(string $keyword, array $used): array {
        return $this->keywordsRepository->getKeyword($keyword, $used);
    }

    public function keywords(array $wildcards): int {
        return $this->contentRepository->getKeywords($wildcards);
    }
    
    public function write(int $id, string $content): void {
        $this->writeRepository->updateWrite($id, ['content' => $content]);
    }

}