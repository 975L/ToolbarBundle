<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Twig;

use c975L\ToolbarBundle\Service\ToolbarServiceInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension to provide the xhtml code for requested button using: `toolbar_button(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'USE_ANOTHER_LABEL', 'USE_ANOTHER_STYLE')
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarButton extends AbstractExtension
{
    /**
     * Stores the toolbarService
     * @var ToolbarServiceInterface
     */
    private $toolbarService;

    public function __construct(ToolbarServiceInterface $toolbarService)
    {
        $this->toolbarService = $toolbarService;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'toolbar_button',
                array($this, 'button'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    /**
     * Returns the xhtml code for the button
     * @return string
     */
    public function button(Environment $environment, $link, $button, $size = 'md', $label = null, $userStyle = null)
    {
        //Defines $icon and $style
        extract($this->toolbarService->defineButton($button));

        //Gets defined style
        if (null !== $userStyle) {
            $style = $userStyle;
        }

        //Defines button
        return $environment->render('@c975LToolbar/button.html.twig', array(
            'link' => $link,
            'style' => $style,
            'size' => $size,
            'button' => $button,
            'icon' => $icon,
            'label' => $label,
        ));
    }
}
