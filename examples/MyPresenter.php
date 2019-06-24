<?php

namespace ContentBot\Examples;

use ContentBot\IContent;
use ContentBot\IContentBotFactory;
use Nette\Application\UI\Presenter;

/** @author Lubomir Andrisek */
final class MyPresenter extends Presenter {

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

    public function actionDefault(int $id): void {
        $this->id = $id;
        $this->write = $this->writeModel->getWrite($id);
    }

    protected function createComponentContentBot(): IContentBotFactory {
        return $this->contentBotFactory->create()
                    ->setId($this->id)
                    ->setWrite($this->write);
    }

}
