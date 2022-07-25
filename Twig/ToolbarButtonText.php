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
 * Twig extension to provide the xhtml code for requested button using toolbar_button_text(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'ICON_DISPLAY[true|false](default true)', 'LOCATION[right|bottom|left|top]')
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarButtonText extends AbstractExtension
{
    public function __construct(
        /**
         * Stores the toolbarService
         */
        private readonly ToolbarServiceInterface $toolbarService
    )
    {
    }

    public function getFunctions()
    {
        return [new TwigFunction(
            'toolbar_button_text',
            $this->button(...),
            ['needs_environment' => true, 'is_safe' => ['html']]
        )];
    }

    /**
     * Returns the xhtml code for the button with text
     * @return string
     */
    public function button(Environment $environment, $link, $button, $size = null, $iconDisplay = true, $location = 'right', $label = null, $userStyle = null, $color = null)
    {
        $buttonData = $this->toolbarService->defineButton($button);

        return $environment->render('@c975LToolbar/buttonText.html.twig', ['link' => $link, 'style' => $userStyle ?? $buttonData['style'], 'color' => $color, 'size' => $size ?? 'md', 'button' => $button, 'icon' => $buttonData['icon'], 'label' => $label, 'iconDisplay' => $iconDisplay, 'location' => $location]);
    }
}
