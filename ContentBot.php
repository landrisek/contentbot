<?php

namespace ContentBot;

use Nette\Application\UI\Control,
    Nette\Application\IPresenter,
    Nette\Application\Responses\JsonResponse,
    Nette\ComponentModel\IComponent,
    Nette\Http\IRequest,
    Nette\Localization\ITranslator;

/** @author Lubomir Andrisek */
final class ContentBot extends Control implements IContentBotFactory {

    /** @var IContent */
    private $contentFacade;

    /** @var int */
    private $id;

    /** @var string */
    private $js;

    /** @var IPresenter */
    private $presenter;

    /** @var IRequest */
    private $request;

    /** @var array */
    private $write;

    /** @var ITranslator */
    private $translatorRepository;

    /** @var array */
    private $used = [];

    public function __construct(string $js, IContent $contentFacade, IRequest $request, ITranslator $translatorRepository) {
        $this->contentFacade = $contentFacade;
        $this->js = $js;
        $this->request = $request;
        $this->translatorRepository = $translatorRepository;
        $this->monitor(IPresenter::class, [$this, 'attached']);
    }

    public function attached(IComponent $presenter): void {
        if($presenter instanceof IPresenter) {
            $this->presenter = $presenter;
        }
    }

    public function create(): ContentBot {
        return $this;
    }

    public function handleSubmit(): void {
        $post = json_decode(file_get_contents('php://input'), true);
        $this->contentFacade->write($this->id, $post['content']);
        $this->presenter->sendPayload();
    }

    public function handlePreview(): void {
        $preview = '';
        $statistics = [];
        $post = json_decode(file_get_contents('php://input'), true);
        foreach ($post['content'] as $optionId => $options) {
            if(is_array($options)) {
                $max = 0;
                /** solved by name */
                foreach($options as $option) {
                    $summary = substr_count($post['name'], $option);
                    if($summary >= $max) {
                        $max = $summary;
                        $selected = $option;
                    }
                    if(!empty($max)) {
                        $statistics[$option] = $max;
                    }
                }
                if(empty($max)) {
                    /** solved by facade */
                    foreach($options as $option) {
                        $summary = $this->wildcard($option);
                        if($summary >= $max) {
                            $max = $summary;
                            $selected = $option;
                        }
                        if(!empty($max)) {
                            $statistics[$option] = $max;
                        }
                    }
                }
                $preview .= ' ' . $selected;
            } else {
                $preview .= ' ' . $options;
            }
        }
        $this->presenter->sendResponse(new JsonResponse(['statistics' => $statistics, 'write' => $preview]));
    }

    public function render(...$args): void {
        $this->template->component = $this->getName();
        $this->template->data =  json_encode(['content' => json_decode(trim($this->write['content'])),
                                            'current' => 0,
                                            'labels' => ['plain' => ucfirst($this->translatorRepository->translate('plain')),
                                                       'preview' => ucfirst($this->translatorRepository->translate('preview')),
                                                       'select' => ucfirst($this->translatorRepository->translate('select')),
                                                       'submit' => ucfirst($this->translatorRepository->translate('save')),
                                                       'statistics' => ucfirst($this->translatorRepository->translate('statistics')),
                                                       'write' => ucfirst($this->translatorRepository->translate('output'))],
                                            'name' => '']);
        $this->template->links =  json_encode(['preview' => $this->link('preview'), 'submit' => $this->link('submit')]);
        $this->template->js = $this->getPresenter()->template->basePath . '/' . $this->js;
        $this->template->setFile(__DIR__ . '/templates/contentBot.latte');
        $this->template->render();
    }

    public function setId(int $id): IContentBotFactory {
        $this->id = $id;
        return $this;
    }

    public function setWrite(array $write): IContentBotFactory {
        $this->write = $write;
        return $this;
    }

    private function wildcard(string $option): int {
        $wildcards = [];
        foreach (explode(' ', trim(preg_replace('/\s+|\.|\,/', ' ', $option))) as $wildcard) {
            $row = $this->contentFacade->keyword($wildcard, $this->used);
            if(!empty($row) && !empty($wildcard) && strlen($wildcard) > 2) {
                foreach(json_decode($row['content']) as $content) {
                    $wildcards[$wildcard][] = '% ' . $content . ' %';
                    $wildcards[$wildcard][] = '% ' . $content . '. %';
                    $wildcards[$wildcard][] = '% ' . $content . ', %';
                    $wildcards[$wildcard][] = '% ' . mb_convert_case($content, MB_CASE_LOWER, 'UTF-8') . ' %';
                    $wildcards[$wildcard][] = '% ' . mb_convert_case($content, MB_CASE_TITLE, 'UTF-8') . ' %';
                }
            }
       }
        if(!empty($wildcards)) {
           return $this->contentFacade->keywords($wildcards);
       }
       return 0;
    }

}

interface IContentBotFactory {

    public function create(): ContentBot;
}
