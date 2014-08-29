<?php

namespace Slim\Views;

use League\Plates\Extension\ExtensionInterface;
use Slim\Slim;

class PlatesExtension implements ExtensionInterface
{
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

    public function app($appName = 'default')
    {
        return Slim::getInstance($appName);
    }

    public function uriFor($name, $params = array(), $appName = 'default')
    {
        return $this->app($appName)->router()->urlFor($name, $params);
    }

    public function urlFor($name, $params = array(), $appName = 'default')
    {
        return $this->app($appName)->urlFor($name, $params);
    }

    public function site($url, $withUri = true, $appName = 'default')
    {
        return $this->base($withUri, $appName) . '/' . ltrim($url, '/');
    }

    public function base($withUri = true, $appName = 'default')
    {
        $req = $this->app($appName)->request();
        $uri = $req->getUrl();

        if ($withUri) {
            $uri .= $req->getRootUri();
        }
        return $uri;
    }
}
