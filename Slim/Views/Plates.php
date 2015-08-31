<?php

namespace Slim\Views;

use InvalidArgumentException;
use League\Plates\Engine;
use RuntimeException;
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
    protected $_engine;

    /**
     * @var callable[]
     */
    private $_construct = array();

    /**
     * {@inheritdoc}
     *
     * @param null|callable|callable[] $init
     */
    public function __construct($init = null)
    {
        parent::__construct();
        if (is_array($init)) {
            $this->addConstructs($init);
        } elseif ($init) {
            $this->addConstruct($init);
        }
    }

    /**
     * @param callable[] $constructs
     * @param bool|true  $lazy
     */
    public function addConstructs(array $constructs, $lazy = true)
    {
        foreach ($constructs as $construct) {
            $this->addConstruct($construct, $lazy);
        }
    }

    /**
     * @param callable  $construct
     * @param bool|true $lazy
     */
    public function addConstruct($construct, $lazy = false)
    {
        if ($construct && is_callable($construct)) {
            if ($this->hasEngine()) {
                if ($lazy) {
                    throw new RuntimeException ('Cannot add callback to plates lazy load queue: already instantiated.');
                } else {
                    call_user_func($construct, $this->getEngine());
                }
            } else {
                $this->_construct[] = $construct;
            }
        } else {
            throw new InvalidArgumentException ('Not a callable parameter: ' . var_export($construct, true));
        }
    }

    /**
     * @return bool
     */
    public function hasEngine()
    {
        return isset($this->_engine);
    }

    /**
     * @return Engine
     */
    public function getEngine()
    {
        if (!$this->hasEngine()) {
            $engine = new Engine($this->getTemplatesDirectory());

            $funcs = $this->_construct;
            /** @var callable $func */
            foreach ($funcs as $func) {
                call_user_func($func, $engine);
            }
            $this->setEngine($engine);
        }

        return $this->_engine;
    }

    /**
     * @param Engine $engine
     *
     * @return $this
     */
    public function setEngine(Engine $engine)
    {
        $this->_engine = $engine;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render($templateFile, $data = null)
    {
        $template = $this->getEngine()->make($templateFile);
        $template->data($this->all());

        return $template->render((array)$data);
    }
}
