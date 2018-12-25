<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Twig;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Twig extension to provide the xhtml code for the toolbar using `{{ toolbar_display('TOOLS_TEMPLATE', 'TYPE', 'SIZE[lg|md|sm|xs]', OBJECT_IF_NEEDED) }}`
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarDisplay extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'toolbar_display',
                array($this, 'display'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    /**
     * Returns the xhtml code for the toolbar
     * @return string
     */
    public function display(\Twig_Environment $environment, $template, $type = null, $size = 'md', $object = null)
    {
        //Defines tools
        $tools = $environment->render($template, array(
                'type' => $type,
                'object' => $object,
            ));

        //Defines toolbar
        return $environment->render('@c975LToolbar/toolbar.html.twig', array(
                'tools' => $tools,
                'size' => $size,
            ));
    }
}
