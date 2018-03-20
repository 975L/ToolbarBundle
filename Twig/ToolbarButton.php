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
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    public function button(\Twig_Environment $environment, $route, $label, $dashboard = null)
    {
        //Defines icon and style
        switch ($label) {
            case 'cancel':
                $icon = 'fas fa-ban';
                $style = 'default';
                break;
            case 'change_password':
                $icon = 'fas fa-sync-alt';
                $style = 'warning';
                break;
            case 'credits':
                $icon = 'fas fa-shopping-cart ';
                $style = 'default';
                break;
            case 'dashboard':
                $icon = 'fas fa-hand-point-right';
                $style = 'success';
                break;
            case 'delete':
                $icon = 'fas fa-trash';
                $style = 'warning';
                break;
            case 'display':
                $icon = 'fas fa-eye';
                $style = 'default';
                break;
            case 'duplicate':
                $icon = 'fas fa-copy';
                $style = 'default';
                break;
            case 'help':
                $icon = 'fas fa-question';
                $style = 'info';
                break;
            case 'modify':
                $icon = 'fas fa-pencil-alt';
                $style = 'default';
                break;
            case 'new':
                $icon = 'fas fa-plus';
                $style = 'default';
                break;
            case 'signout':
                $icon = 'fas fa-sign-out-alt';
                $style = 'info';
                break;
            case 'transactions':
                $icon = 'fas fa-exchange-alt';
                $style = 'default';
                break;
            case 'user':
                $icon = 'fas fa-user';
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