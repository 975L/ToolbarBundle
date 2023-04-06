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
    public function __construct(
        /**
         * Stores the toolbarService
         */
        private readonly ToolbarServiceInterface $toolbarService
    )
    {
    }

    public function getFunctions(): array
    {
        return [new TwigFunction(
            'toolbar_button',
            $this->button(...),
            ['needs_environment' => true, 'is_safe' => ['html']]
        )];
    }

    /**
     * Returns the xhtml code for the button
     * @return string
     */
    public function button(Environment $environment, $link, $button, $size = null, $label = null, $userStyle = null, $color = null)
    {
        $buttonData = $this->toolbarService->defineButton($button);

        return $environment->render('@c975LToolbar/button.html.twig', ['link' => $link, 'style' => $userStyle ?? $buttonData['style'], 'color' => $color, 'size' => $size ?? 'md', 'button' => $button, 'icon' => $buttonData['icon'], 'label' => $label]);
    }
}
