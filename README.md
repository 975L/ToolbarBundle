ToolbarBundle
=============

ToolbarBundle does the following:

- Displays a toolbar,
- Includes generic tools,
- Integrates with your web design.

[ToolbarBundle dedicated web page](https://975l.com/en/pages/toolbar-bundle).

[ToolbarBundle API documentation](https://975l.com/apidoc/c975L/ToolbarBundle.html).

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

Create button - `toolbar_button()`
----------------------------------
You can create a button in a Twig template by calling the following code:

```twig
{{ toolbar_button(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'USE_ANOTHER_LABEL', 'USE_ANOTHER_STYLE') }}
````

Create button with text - `toolbar_button_text()`
-------------------------------------------------
You can create a button with text in a Twig template by calling the following code (data between [] are optional):

```twig
{{ toolbar_button_text(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'ICON_DISPLAY[true|false](default true)', 'LOCATION[right|bottom|left|top]', 'USE_ANOTHER_LABEL', 'USE_ANOTHER_STYLE') }}
````

Create a toolbar - `toolbar_display`
------------------------------------
To create a toolbar, you need to create a template where the tools are defined. Inside this template you can use the Twig Extension `toolbar_button()` or `toolbar_button_text()` to define buttons, like in the following:

```twig
    {# You can add some test and use the object sent #}
    {% if type === 'YOUR_TYPE' %}
        {# You can pass an object and use it there, with the name 'object' #}
        {{ toolbar_button(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'USE_ANOTHER_LABEL', 'USE_ANOTHER_STYLE') }}
        {{ toolbar_button_text(path('ROUTE', { 'VARIABLE': object.PROPERTY }), 'BUTTON_NAME', 'SIZE[lg|md|sm|xs](default md)', 'ICON_DISPLAY[true|false](default true)', 'LOCATION[right|bottom|left|top]') }}
    {% endif %}
```
Then in your templates simply call the Twig extension `{{ toolbar_display('TOOLS_TEMPLATE', 'TYPE', 'SIZE[lg|md|sm|xs]', OBJECT_IF_NEEDED, ALIGNMENT[left|center|right](default center)) }}`.

You can also specify a css style in your stylesheet for the toolbar:
```css
.toolbar {
    margin-bottom: 2em;
}
```

**Note** that the Twig extension `ToolbarDashboards` is specific to 975L developed products, as it will display a dropdown menu link to other products.

Call from Controller
--------------------
If you need to call it from a controller, you can do it with the following code:
```php
<?php
//...
    $tools = $this->renderView('LOCATION_OF_YOUR_TEMPLATE_DEFINED_ABOVE', array(
        'type' => 'YOUR_TYPE',
        'object' => YOUR_OBJECT_IF_NEEDED,
    ));
    $toolbar = $this->renderView('@c975LToolbar/toolbar.html.twig', array(
        'tools' => $tools,
        'size' => 'YOUR_SIZE',
    ));
```

**If this project help you to reduce time to develop, you can [buy me a coffee](https://www.buymeacoffee.com/LaurentMarquet) :)**