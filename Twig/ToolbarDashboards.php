<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Twig extension to provide the xhtml code for available 975L dashboards using `toolbar_dashboards`
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarDashboards extends \Twig_Extension
{
    /**
     * Stores Container
     * @var ContainerInterface
     */
    private $container;

    /**
     * Stores TokenStorage
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        ContainerInterface $container,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->container = $container;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'toolbar_dashboards',
                array($this, 'dashboards'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    /**
     * Returns the xhtml code for the available 975L dashboards
     * @return string
     */
    public function dashboards(\Twig_Environment $environment, $size)
    {
        //Checks user's rights
        $dashboards = null;
        if ($this->tokenStorage->getToken()->getUser() !== null) {
            //Defines installed dashboards
            $dashboardsAvailable = array('email', 'events', 'exception_checker', 'gift_voucher', 'page_edit', 'payment', 'purchase_credits', 'user');
            foreach ($dashboardsAvailable as $dashboardAvailable) {
                //Checks if the bundle is installed
                if (is_dir($this->container->getParameter('kernel.root_dir') . '/../vendor/c975l/' . str_replace('_', '', $dashboardAvailable) . '-bundle')) {
                    //Checks if roleNeeded is defined
                    if ($this->container->hasParameter('c975_l_' . $dashboardAvailable . '.roleNeeded')) {
                        //User has good roleNeeded for that dashboard
                        if ($this->container->get('security.authorization_checker')->isGranted($this->container->getParameter('c975_l_' . str_replace('-', '_', $dashboardAvailable) . '.roleNeeded'))) {
                            $dashboards[] = str_replace('_', '', $dashboardAvailable);
                        }
                    //No roleNeeded defined
                    } else {
                        $dashboards[] = str_replace('-', '', $dashboardAvailable);
                    }
                }
            }
        }

        //Defines toolbar
        return $environment->render('@c975LToolbar/dashboards.html.twig', array(
                'dashboards' => $dashboards,
                'size' => $size,
            ));
    }
}
