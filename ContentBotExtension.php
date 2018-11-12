<?php

namespace ContentBot;

use Exception,
    Nette\DI\CompilerExtension,
    Nette\PhpGenerator\ClassType;

/** @author Lubomir Andrisek */
final class ContentBotExtension extends CompilerExtension {

    private $defaults = ['js' => 'assets/components/contentbot/js'];

    public function afterCompile(ClassType $class) { }

    public function beforeCompile() {
        if(!class_exists('Nette\Application\Application')) {
            throw new MissingDependencyException('Please install and enable https://github.com/nette/nette.');
        }
        parent::beforeCompile();
    }

    public function getConfiguration(array $parameters) {
        foreach($this->defaults as $key => $parameter) {
            if(!isset($parameters['contentBot'][$key])) {
                $parameters['contentBot'][$key] = $parameter;
            }
        }
        return $parameters;
    }
    
    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $parameters = $this->getConfiguration($builder->parameters);
        $manifest = (array) json_decode(file_get_contents($parameters['wwwDir'] . '/' . $parameters['contentBot']['js'] . '/manifest.json'));
        $builder->addDefinition($this->prefix('ContentBot'))
                ->setFactory('ContentBot\ContentBot', [$manifest['ContentBot.js']]);
        $builder->addDefinition($this->prefix('contentBotExtension'))
                ->setFactory('ContentBot\ContentBotExtension', []);
    }

}

class MissingDependencyException extends Exception { }
