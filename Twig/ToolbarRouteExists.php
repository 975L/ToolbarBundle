<?php
/*
 * (c) 2018: 975l <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Twig;

class ToolbarRouteExists extends \Twig_Extension
{
    private $container;

    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('route_exists', array($this, 'routeExists')),
        );
    }

    public function routeExists($route)
    {
        return ($this->container->get('router')->getRouteCollection()->get($route));
    }
}