<?php
/*
 * (c) 2018: 975L <contact@975l.com>
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

    public function button(\Twig_Environment $environment, $link, $button, $label = null, $userStyle = null)
    {
        //Defines icon and style
        switch ($button) {
            case 'abuse':
                $icon = 'fas fa-fire';
                $style = 'btn-danger';
                break;
            case 'cancel':
                $icon = 'fas fa-ban';
                $style = 'btn-default';
                break;
            case 'change_password':
                $icon = 'fas fa-sync-alt';
                $style = 'btn-warning';
                break;
            case 'credits':
                $icon = 'fas fa-gem';
                $style = 'btn-default';
                break;
            case 'dashboard':
                $icon = 'fas fa-hand-point-right';
                $style = 'btn-success';
                break;
            case 'delete':
                $icon = 'fas fa-trash';
                $style = 'btn-warning';
                break;
            case 'display':
                $icon = 'fas fa-eye';
                $style = 'btn-default';
                break;
            case 'duplicate':
                $icon = 'fas fa-copy';
                $style = 'btn-default';
                break;
            case 'email':
                $icon = 'fas fa-envelope';
                $style = 'btn-default';
                break;
            case 'forward':
                $icon = 'fas fa-forward';
                $style = 'btn-default';
                break;
            case 'heart':
                $icon = 'fas fa-heart';
                $style = 'btn-default';
                break;
            case 'help':
                $icon = 'fas fa-question';
                $style = 'btn-info';
                break;
            case 'info':
                $icon = 'fas fa-info-circle';
                $style = 'btn-info';
                break;
            case 'modify':
                $icon = 'fas fa-pencil-alt';
                $style = 'btn-default';
                break;
            case 'qrcode':
                $icon = 'fas fa-qrcode';
                $style = 'btn-default';
                break;
            case 'new':
                $icon = 'fas fa-plus';
                $style = 'btn-default';
                break;
            case 'signout':
                $icon = 'fas fa-sign-out-alt';
                $style = 'btn-info';
                break;
            case 'send':
                $icon = 'fas fa-paper-plane';
                $style = 'btn-default';
                break;
            case 'transactions':
                $icon = 'fas fa-exchange-alt';
                $style = 'btn-default';
                break;
            case 'user':
                $icon = 'fas fa-user';
                $style = 'btn-default';
                break;
            default:
                $icon = '';
                $style = '';
                break;
        }

        //Gets defined style
        if ($userStyle !== null) {
            $style = $userStyle;
        }

        //Defines button
        return $environment->render('@c975LToolbar/button.html.twig', array(
                'link' => $link,
                'style' => $style,
                'button' => $button,
                'icon' => $icon,
                'label' => $label,
            ));
    }
}