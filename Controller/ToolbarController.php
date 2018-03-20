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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolbarController extends Controller
{
//DISPLAY
    /*
     * No Route defined as it used as forwarded to
     */
    public function displayAction($tools = null, $dashboard = null)
    {
        if ($this->getUser() !== null) {
            //Defines installed dashboards
            $dashboards = array();
            $dashboardsAvailable = array('email', 'events', 'gift_voucher', 'page_edit', 'payment', 'purchase_credits', 'shop', 'user');
            foreach ($dashboardsAvailable as $dashboardAvailable) {
                //Checks if the bundle is installed
                if (is_dir($this->getParameter('kernel.root_dir') . '/../vendor/c975l/' . str_replace('_', '', $dashboardAvailable) . '-bundle')) {
                    //Checks if roleNeeded is defined
                    if ($this->container->hasParameter('c975_l_' . $dashboardAvailable . '.roleNeeded')) {
                        //User has good roleNeeded for that dashboard
                        if ($this->get('security.authorization_checker')->isGranted($this->getParameter('c975_l_' . str_replace('-', '_', $dashboardAvailable) . '.roleNeeded'))) {
                            $dashboards[] = str_replace('_', '', $dashboardAvailable);
                        }
                    //No roleNeeded defined
                    } else {
                        $dashboards[] = str_replace('-', '', $dashboardAvailable);
                    }
                }
            }

            //Defines toolbar
            $toolbar = '';
            if ($this->getUser() !== null) {
                $toolbar  = $this->renderView('@c975LToolbar/toolbar.html.twig', array(
                    'tools' => $tools,
                    'dashboard' => $dashboard,
                    'dashboards' => $dashboards,
                ));
            }
        //Not defined user
        } else {
            $toolbar = null;
        }

        return new Response($toolbar);
    }
}