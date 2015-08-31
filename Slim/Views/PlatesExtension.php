<?php

namespace Slim\Views;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Slim\Slim;

/**
 * Class PlatesExtension
 *
 * @package Slim\Views
 */
class PlatesExtension implements ExtensionInterface
{
    /** @var  Engine */
    protected $engine;

    /**
     * @param Engine $engine
     */
    public function register(Engine $engine)
    {
        foreach ($this->getFunctions() as $func => $method) {
            $engine->registerFunction($func, array($this, $method));
        }
    }

    /**
     * @return string[]
     */
    public function getFunctions()
    {
        return array(
            'app' => 'app',
            'uriFor' => 'uriFor',
            'urlFor' => 'urlFor',
            'baseUrl' => 'base',
            'siteUrl' => 'site',
        );
    }

    /**
     * @return Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param $engine
     *
     * @return $this
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @param       $name
     * @param array $params
     * @param null  $appName
     *
     * @return string
     */
    public function uriFor($name, $params = array(), $appName = null)
    {
        return $this->app($appName)->router()->urlFor($name, $params);
    }

    /**
     * @param null $appName
     *
     * @return null|Slim
     */
    public function app($appName = null)
    {
        if ($appName) {
            return Slim::getInstance($appName);
        } else {
            return Slim::getInstance();
        }
    }

    /**
     * @param       $name
     * @param array $params
     * @param null  $appName
     *
     * @return string
     */
    public function urlFor($name, $params = array(), $appName = null)
    {
        return $this->app($appName)->urlFor($name, $params);
    }

    /**
     * @param           $url
     * @param bool|true $withUri
     * @param null      $appName
     *
     * @return string
     */
    public function site($url, $withUri = true, $appName = null)
    {
        return $this->base($withUri, $appName) . '/' . ltrim($url, '/');
    }

    /**
     * @param bool|true $withUri
     * @param null      $appName
     *
     * @return string
     */
    public function base($withUri = true, $appName = null)
    {
        $req = $this->app($appName)->request();
        $uri = $req->getUrl();

        if ($withUri) {
            $uri .= $req->getRootUri();
        }
        return $uri;
    }
}
