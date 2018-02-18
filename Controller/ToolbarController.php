<?php
/*
 * (c) 2018: 975l <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ToolbarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ToolbarController extends Controller
{
//DISPLAY
    public function displayAction($tools = null, $dashboard = null)
    {
        //Defines installed dashboards
        $dashboardsAvailable = array('email', 'events', 'gift-voucher', 'pageedit', 'payment', 'shop', 'user');
        foreach ($dashboardsAvailable as $dashboardAvailable) {
            if (is_dir($this->container->getParameter('kernel.root_dir') . '/../vendor/c975l/' . $dashboardAvailable . '-bundle')) {
                $dashboards[] = str_replace('-', '', $dashboardAvailable);
            }
        }

        //Defines toolbar
        $toolbar = '';
        if ($this->getUser() !== null) {
            $toolbar  = $this->renderView('@c975LToolbar/toolbar.html.twig', array(
                'tools' => $tools,
                'dashboard' => $dashboard,
                'signoutRoute' => $this->getParameter('c975_l_toolbar.signoutRoute'),
                'dashboards' => $dashboards,
            ));
        }

        return new Response($toolbar);
    }
}