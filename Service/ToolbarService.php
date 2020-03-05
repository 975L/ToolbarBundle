<?php
/*
 * (c) 2017: 975L <contact@975l.com>
 * (c) 2017: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Service;

use c975L\ToolbarBundle\Service\ToolbarServiceInterface;

/**
 * Main Services related to Payment
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ToolbarService implements ToolbarServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function defineButton(string $button)
    {
        $buttons = array(
            'add_role' => array(
                'icon' => 'fas fa-user-check',
                'style' => 'btn-info',
            ),
            'abuse' => array(
                'icon' => 'fas fa-fire',
                'style' => 'btn-danger',
            ),
            'cancel' => array(
                'icon' => 'fas fa-ban',
                'style' => 'btn-default',
            ),
            'change_password' => array(
                'icon' => 'fas fa-sync-alt',
                'style' => 'btn-warning',
            ),
            'config' => array(
                'icon' => 'fas fa-cog',
                'style' => 'btn-info',
            ),
            'comment' => array(
                'icon' => 'fas fa-comment',
                'style' => 'btn-default',
            ),
            'create' => array(
                'icon' => 'fas fa-plus',
                'style' => 'btn-default',
            ),
            'new' => array(
                'icon' => 'fas fa-plus',
                'style' => 'btn-default',
            ),
            'credits' => array(
                'icon' => 'fas fa-gem',
                'style' => 'btn-default',
            ),
            'dashboard' => array(
                'icon' => 'fas fa-hand-point-right',
                'style' => 'btn-success',
            ),
            'delete' => array(
                'icon' => 'fas fa-trash',
                'style' => 'btn-danger',
            ),
            'delete_role' => array(
                'icon' => 'fas fa-user-times',
                'style' => 'btn-info',
            ),
            'display' => array(
                'icon' => 'fas fa-eye',
                'style' => 'btn-default',
            ),
            'duplicate' => array(
                'icon' => 'fas fa-copy',
                'style' => 'btn-default',
            ),
            'email' => array(
                'icon' => 'fas fa-envelope',
                'style' => 'btn-default',
            ),
            'export_json' => array(
                'icon' => 'fas fa-file-export',
                'style' => 'btn-default',
            ),
            'export_xml' => array(
                'icon' => 'fas fa-file-export',
                'style' => 'btn-default',
            ),
            'forward' => array(
                'icon' => 'fas fa-forward',
                'style' => 'btn-default',
            ),
            'home' => array(
                'icon' => 'fas fa-home',
                'style' => 'btn-default',
            ),
            'heart' => array(
                'icon' => 'fas fa-heart',
                'style' => 'btn-default',
            ),
            'help' => array(
                'icon' => 'fas fa-question',
                'style' => 'btn-info',
            ),
            'info' => array(
                'icon' => 'fas fa-info-circle',
                'style' => 'btn-info',
            ),
            'public_profile' => array(
                'icon' => 'fas fa-user-circle',
                'style' => 'btn-default',
            ),
            'modify' => array(
                'icon' => 'fas fa-pencil-alt',
                'style' => 'btn-default',
            ),
            'qrcode' => array(
                'icon' => 'fas fa-qrcode',
                'style' => 'btn-default',
            ),
            'pdf' => array(
                'icon' => 'far fa-file-pdf',
                'style' => 'btn-default',
            ),
            'renew' => array(
                'icon' => 'fas fa-redo-alt',
                'style' => 'btn-default',
            ),
            'share' => array(
                'icon' => 'fas fa-share',
                'style' => 'btn-default',
            ),
            'signin' => array(
                'icon' => 'fas fa-sign-in-alt',
                'style' => 'btn-info',
            ),
            'signout' => array(
                'icon' => 'fas fa-sign-out-alt',
                'style' => 'btn-info',
            ),
            'send' => array(
                'icon' => 'fas fa-paper-plane',
                'style' => 'btn-default',
            ),
            'spam' => array(
                'icon' => 'fas fa-fire',
                'style' => 'btn-default',
            ),
            'transactions' => array(
                'icon' => 'fas fa-exchange-alt',
                'style' => 'btn-default',
            ),
            'user' => array(
                'icon' => 'fas fa-user',
                'style' => 'btn-default',
            ),
            'users' => array(
                'icon' => 'fas fa-users',
                'style' => 'btn-warning',
            ),
            'validate' => array(
                'icon' => 'fas fa-star',
                'style' => 'btn-default',
            ),
        );

        return isset($buttons[$button]) ? $buttons[$button] : null;
    }
}
