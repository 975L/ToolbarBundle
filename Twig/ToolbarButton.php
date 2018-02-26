<?php
/*
 * (c) 2018: 975l <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Twig;

class ToolbarButton extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'toolbar_button',
                array($this, 'button'),
                array('needs_environment' => true)
            ),
        );
    }

    public function button(\Twig_Environment $environment, $route, $label, $dashboard = null)
    {
        //Defines icon and style
        switch ($label) {
            case 'cancel':
                $icon = 'fa-ban';
                $style = 'default';
                break;
            case 'change_password':
                $icon = 'fa-sync-alt';
                $style = 'warning';
                break;
            case 'dashboard':
                $icon = 'fa-hand-point-right';
                $style = 'success';
                break;
            case 'delete':
                $icon = 'fa-trash';
                $style = 'warning';
                break;
            case 'display':
                $icon = 'fa-eye';
                $style = 'default';
                break;
            case 'duplicate':
                $icon = 'fa-copy';
                $style = 'default';
                break;
            case 'help':
                $icon = 'fa-question';
                $style = 'info';
                break;
            case 'modify':
                $icon = 'fa-pencil-alt';
                $style = 'default';
                break;
            case 'new':
                $icon = 'fa-plus';
                $style = 'default';
                break;
            case 'signout':
                $icon = 'fa-sign-out-alt';
                $style = 'info';
                break;
            case 'user':
                $icon = 'fa-user';
                $style = 'default';
                break;
            default:
                $icon = '';
                $style = '';
                break;
        }

        //Defines button
        return $environment->render('@c975LToolbar/fragments/button.html.twig', array(
                'route' => $route,
                'style' => $style,
                'label' => $label,
                'icon' => $icon,
                'dashboard' => $dashboard,
            ));
    }
}