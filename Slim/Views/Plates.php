<?php

namespace Slim\Views;

use Closure;
use League\Plates\Engine;
use LogicException;
use Slim\View;

/**
 * Class Plates
 * @package Slim\Views
 */
class Plates extends View
{
    /**
     * @var Engine $_engine
     */
    static $_engine;

    private $_construct = array();

    /**
     * {@inheritdoc}
     *
     * @param null|Closure|Array $init
     */
    public function __construct($init = null)
    {
        parent::__construct();
        if ($init) {
            $this->addConstruct($init);
        }
    }

    /**
     * @param Closure|array $construct
     * @throws LogicException
     */
    public function addConstruct($construct)
    {
        if ($construct && is_callable($construct)) {
            $this->_construct[] = $construct;
        } else {
            throw new \LogicException('Not a callable parameter: ' . var_export($construct, true));
        }
    }

    /**
     * @param array $constructs
     */
    public function addConstructs(array $constructs)
    {
        foreach ($constructs as $construct) {
            $this->addConstruct($construct);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $templateFile
     * @param null $data
     * @return string
     */
    public function render($templateFile, $data = null)
    {
        if (!isset(static::$_engine)) {
            $engine = new Engine($this->getTemplatesDirectory());

            $funcs = $this->_construct;
            foreach ($funcs as $func) {
                //if (is_callable($func)) {
                call_user_func($func, $engine);
                //}
            }
            static::$_engine = $engine;
        }

        $template = static::$_engine->makeTemplate();
        $template->data($this->all());
        return $template->render($templateFile, $data);
    }
}
