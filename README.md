ToolbarBundle
=============

ToolbarBundle does the following:

- Displays a toolbar for products developped by 975L.com,
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

Step 2: Enable the Bundle
-------------------------
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

How to use
----------
You need to create a template where the tools are defined. Inside this template you can use the Twig Extension `toolbar_button()` to define buttons, like in the following:

```twig
    {# You can add some test and use the object sent #}
    {% if type == 'YOUR_TYPE' %}
        {# You can pass an object and use it there with the name 'object' #}
        {{ toolbar_button(path('YOUR_LINK', { 'YOUR_VARIABLE__NAME': object.YOUR_OBJECT_PROPERTY_NAME }), 'NAME_OF_BUTTON', 'YOU_CAN_SPECIFY_ANOTHER_LABEL', 'YOU_CAN_SPECIFY_ANOTHER_STYLE') }}
    {% endif %}
```
Then in your templates simply call the Twig extension `{{ toolbar_display('LOCATION_OF_YOUR_TEMPLATE_DEFINED_ABOVE', 'YOUR_TYPE', 'YOUR_SIZE', YOUR_OBJECT_IF_NEEDED) }}`.

You can also specify a css style in your stylesheet for the toolbar:
```css
.toolbar {
    margin-bottom: 2em;
}
```