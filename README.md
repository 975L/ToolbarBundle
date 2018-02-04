ToolbarBundle
=============

ToolbarBundle does the following:

- Displays a common toolbar for products developped by 975L.com,
- include specific tools provided by products,
- Integrates with your web design.

[Toolbar Bundle dedicated web page](https://975l.com/en/pages/toolbar-bundle).

Bundle installation
===================

Step 1: Download the Bundle
---------------------------
Use [Composer](https://getcomposer.org) to install the library
```bash
    composer require c975l/toolbar-bundle
```

Step 2: Enable the Bundles
--------------------------
Then, enable the bundles by adding them to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new c975L\ToolbarBundle\c975LToolbarBundle(),
        ];
    }
}
```

Step 3: Configure the Bundle
----------------------------
Then, in the `app/config.yml` file of your project, define `roleNeeded` (the user's role needed to enable access to admin tasks)

```yml
c975_l_toolbar:
    #(Optional) Your signout Route if you want to allow sign out from Events toolbar
    signoutRoute: 'name_of_your_signout_route' #default null
    #(Optional) Your main dashboard route if you want to allow it from Events toolbar
    dashboardRoute: 'your_dashboard_route' #default null
    #(Optional) The list of used products in order of appeareance
    products: ['pageedit', 'giftvoucher', 'userfiles']
```

How to use
----------
Simply define the tools to be displayed against the context of the page in a Twig template

```html
    {# @c975LGiftVoucher/tools.html.twig #}

    {% trans_default_domain 'toolbar' %}

    {# Set any conditions #}
    {% if type == 'edit' or type == 'delete' %}
        <a href="{{ path('giftvoucher_display', {'number': giftVoucher.number}) }}" class="btn btn-default" title="{{ 'label.number'|trans({}, 'giftVoucher') }}" role="button">
        <span class="glyphicon glyphicon-eye-open"></span></a>
    {% endif %}
    {# Add any needed buttons following the same scheme #}
    {# ... #}
```
Then in your Controller call the above template and `c975L\ToolbarBundle\Controller\ToolbarController::displayAction`
```php
<?php
    public function displayAction()
    {
        //...

        //Defines toolbar
        $tools  = $this->renderView('@c975LGiftVoucher/tools.html.twig', array(
            //Needed data
            'type' => 'display',
            'giftVoucher' => $giftVoucher,
        ));
        $toolbar = $this->forward('c975L\ToolbarBundle\Controller\ToolbarController::displayAction', array(
            'tools'  => $tools,
            'product'  => 'giftvoucher',
        ))->getContent();

        return $this->render('@c975LGiftVoucher/pages/display.html.twig', array(
            'toolbar' => $toolbar,
        ));
    }
```