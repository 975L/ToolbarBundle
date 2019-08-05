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
        switch ($button) {
            case 'add_role':
                $icon = 'fas fa-user-check';
                $style = 'btn-info';
                break;
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
            case 'config':
                $icon = 'fas fa-cog';
                $style = 'btn-info';
                break;
            case 'create':
            case 'new':
                $icon = 'fas fa-plus';
                $style = 'btn-default';
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
                $style = 'btn-danger';
                break;
            case 'delete_role':
                $icon = 'fas fa-user-times';
                $style = 'btn-info';
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
            case 'export_json':
            case 'export_xml':
                $icon = 'fas fa-file-export';
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
            case 'public_profile':
                $icon = 'fas fa-user-circle';
                $style = 'btn-default';
                break;
            case 'modify':
                $icon = 'fas fa-pencil-alt';
                $style = 'btn-default';
                break;
            case 'qrcode':
                $icon = 'fas fa-qrcode';
                $style = 'btn-default';
                break;
            case 'pdf':
                $icon = 'far fa-file-pdf';
                $style = 'btn-default';
                break;
            case 'renew':
                $icon = 'fas fa-sync-alt';
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
            case 'users':
                $icon = 'fas fa-users';
                $style = 'btn-warning';
                break;
            default:
                $icon = null;
                $style = null;
                break;
        }

        return compact('icon', 'style');
    }
}
