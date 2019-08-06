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
use c975L\ConfigBundle\Service\ConfigServiceInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension to provide the xhtml code for available 975L dashboards using `toolbar_dashboards('SIZE[lg|md|sm|xs](default md)')`
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarDashboards extends AbstractExtension
{
    /**
     * Stores ConfigServiceInterface
     * @var ConfigServiceInterface
     */
    private $configService;

    /**
     * Stores ContainerInterface
     * @var ContainerInterface
     */
    private $container;

    /**
     * Stores TokenStorageInterface
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        ConfigServiceInterface $configService,
        ContainerInterface $container,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->configService = $configService;
        $this->container = $container;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction(
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
    public function dashboards(Environment $environment, $size)
    {
        //Checks user's rights
        $dashboards = null;
        if ($this->tokenStorage->getToken()->getUser() !== null) {
            //Defines installed dashboards
            $dashboardsAvailable = array('ContactForm', 'Email', 'Events', 'ExceptionChecker', 'GiftVoucher', 'PageEdit', 'Payment', 'PurchaseCredits', 'Site', 'User');
            foreach ($dashboardsAvailable as $dashboardAvailable) {
                //Checks if the bundle is installed
                if (is_dir($this->configService->getContainerParameter('kernel.root_dir') . '/../vendor/c975l/' . strtolower($dashboardAvailable) . '-bundle') &&
                    //Checks if roleNeeded for that dashboard is defined
                    $this->configService->hasParameter('c975L' . $dashboardAvailable . '.roleNeeded') &&
                    //Checks if User has good roleNeeded for that dashboard
                    $this->container->get('security.authorization_checker')->isGranted($this->configService->getParameter('c975L' . $dashboardAvailable . '.roleNeeded'))
                )
                {
                    $dashboards[] = strtolower($dashboardAvailable);
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
