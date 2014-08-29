<?php

namespace Slim\Views;

use Closure;
use League\Plates\Engine;
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
     * @param null|Closure|array $init
     */
    public function __construct($init = null)
    {
        parent::__construct();
        if ($init && is_callable($init)) {
            $this->_construct = array($init);
        } elseif (is_array($init)) {
            $this->_construct = $init;
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
                if (is_callable($func)) {
                    call_user_func($func, $engine);
                }
            }
            static::$_engine = $engine;
        }

        $template = static::$_engine->makeTemplate();
        $template->data($this->all());
        return $template->render($templateFile, $data);
    }
}
