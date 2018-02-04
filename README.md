ToolbarBundle
=============

ToolbarBundle does the following:

- Displays a common toolbar for projects developped by 975L.com,
- include specific tools provided by projects
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
            new c975L\ToolbarBundle\c975LtoolbarBundle(),
        ];
    }
}
```

Step 3: Configure the Bundle
----------------------------
Then, in the `app/config.yml` file of your project, define `roleNeeded` (the user's role needed to enable access to admin tasks)

```yml
c975_l_toolbar:
    #User's role needed to enable access to defined admin tasks
    roleNeeded: 'ROLE_ADMIN'
    #(Optional) Your signout Route if you want to allow sign out from Events toolbar
    signoutRoute: 'name_of_your_signout_route' #default null
    #(Optional) Your main dashboard route if you want to allow it from Events toolbar
    dashboardRoute: 'your_dashboard_route' #default null
```

How to use
----------
