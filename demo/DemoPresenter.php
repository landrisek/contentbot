<?php

namespace ContentBot\Demo;

use ContentBot\IContent,
    ContentBot\IContentBotFactory,
    Nette\Application\UI\Presenter;

/** @author Lubomir Andrisek */
class DemoPresenter extends Presenter {

    /** @var IContentBotFactory @inject */
    public $contentBotFactory;

    /** @var IContent @inject */
    public $contentFacade;

    /** @var int */
    private $id;

    /** @var array */
    private $write;

    /** @var WriteRepository @inject */
    private $writeRepository;

    public function actionDemo(int $id): void {
        $this->id = $id;
        $this->write = $this->writeModel->getWrite($id);
    }

    protected function createComponentContentBot(): IContentBotFactory {
        return $this->contentBotFactory->create()
                    ->setId($this->id)
                    ->setWrite($this->write);
    }

}
