# Changelog

- Added info to `README.md` (29/10/2018)

v1.8.10
-------
- Added buttons (29/10/2018)

v1.8.9.1
--------
- Added missing labels (28/10/2018)

v1.8.9
------
- Added `add_role` and `delete_role` buttons (28/10/2018)

v1.8.8
------
- Made delete button as 'danger' instead of 'warning' (03/09/2018)

v1.8.7
------
- Updated composer.json (01/09/2018)
- Added dashboard for c975L/SiteBundle (02/09/2018)

v1.8.6
------
- Changed `ToolbarDashboards` to use c975L/ConfigBundle (31/08/2018)
- Removed un-needed declaration in Configuration class (31/08/2018)

v1.8.5
------
- Removed 'parameters' data and replaced by 'config' (28/08/2018)
- Added 'contactform' as available dashboard (30/08/2018)

v1.8.4.2
--------
- Added 'config' button (27/08/2018)

v1.8.4.1
--------
- Removed css margin for `toolbar_button_text()` (25/08/2018)

v1.8.4
------
- Created ToolbarService + Interface (25/08/2018)
- Added Twig extension ToolbarButtonText (25/08/2018)
- Improved `README.md` (25/08/2018)
- Added size to `toolbar_button()` [BC-Break for button defining specific label and/or style] (25/08/2018)
- Added 'copy_code' and 'parameters' buttons (25/08/2018)
- Updated documentation (25/08/2018)

v1.8.3.2
--------
- Added link to BuyMeCoffee (22/08/2018)
- Added link to apidoc (22/08/2018)
- Added documentation (22/08/2018)
- Removed FQCN (22/08/2018)

v1.8.3.1
--------
- Added label.create (02/08/2018)

v1.8.3
------
- Added `create` case for button (02/08/2018)

v1.8.2.3
--------
- Corrected cancel button to be more in "best practices" (26/05/2018)

v1.8.2.2
--------
- Removed required in composer.json (22/05/2018)

v1.8.2.1
--------
- Removed sop from available dashboards as not available yet (19/05/2018)

v1.8.2
------
- Added information in `README.md` about calling from controller (13/05/2018)
- Added pdf button (13/05/2018)

v1.8.1
------
- Modified icon for credits to be the same as for c975LPurchaseCredits (13/05/2018)

v1.8
----
- Added possibility to create toolbars for anything wanted, not only for bundles developped by 975L.com :-) (13/05/2018)
- Removed inline style for toolbar and added css class `toolbar` (13/05/2018)
- Moved `button.html.twig` to `views` folder (13/05/2018)
- Renamed `label` to `button` in Twig extension `ToolbarButton` (13/05/2018)
- Added `label` in Twig extension `ToolbarButton` (so it's use is not the same as before, see line above) to allow specifying another label for button (13/05/2018)
- Renamed `route` to `link` in Twig extension `ToolbarButton` (13/05/2018)
- Added multiples buttons (13/05/2018)
- Removed `Controller` as not used anymore (13/05/2018)
- Removed `ToolbarRouteExists` as not used anymore (13/05/2018)

v1.7.2
------
- Added c975LExceptionCheckerBundle (15/04/2018)

v1.7.1
------
- Added c975LPurchaseCreditsBundle (20/03/2018)
- Modified `button.html.twig` to receive the full name of the icon (20/03/2018)

v1.7
----
- Added 'is_safe' to Twig extension `ToolbarButton` to remove "|raw" on each call (01/03/2018)

v1.6.2
------
- Added margin-bottom to toolbar (27/02/2018)

v1.6.1
------
- Changed logo for current dashboard (26/02/2018)

v1.6
----
- Abandoned Glyphicon and replaced by fontawesome (22/02/2018)
- Renamed Twig function name from 'route_exists' to 'toolbar_route_exists' (22/02/2018)
- Added Twig extension to display button easily (22/02/2018)
- Removed help form default toolbar as it was displayed even on the help page without possibilities to test current route in Twig (22/02/2018)
- Added test to check if other dashboards are available to user, in order to not display dashboards button if not (22/02/2018)

v1.5
----
- Added check against `roleNeeded` config value for each dashboard before adding it to the toolbar (20/02/2018)
- Removed signoutRoute as managed via `c975L\UserBundle` (20/02/2018)

v1.4.3
------
- Added `label.validate` to translations (19/02/2018)

v1.4.2
------
- Corrected translation for Gift-Voucher dashboard (18/02/2018)

v1.4.1
------
- Added events to toolbar dashboards available (18/02/2018)
- Added suppression of '-' in dashboards names (18/02/2018)

v1.4
----
- Removed `dashboards` parameter to get the data directly from the vendor folder (17/02/2018)

v1.3.1
------
- Add of translation for c975L/EmailBundle (05/02/2018)
- Modified label for userfiles (05/02/2018)

v1.3
----
- Add a Twig function to check if a Route exists (05/02/2018)
- Add a test to check if help Route exists (05/02/2018)

v1.2.3
------
- Update `README.md` (04/02/2018)

v1.2.2
------
- Rename products to dashboards (04/02/2018)

v1.2.1
------
- Remove of mention of `dashboardRoute` in `README.md` (04/02/2018)

v1.2
----
- Move the list of products at he end of the toolbar (04/02/2018)
- Remove of the button dashboard as set in the products (04/02/2018)
- Add the link to the current product dashboard at the beginning of the toolbar (04/02/2018)

v1.1.1
------
- Add support in `composer.json`+ use of ^ for versions request (04/02/2018)

v1.1
----
- Add of system files (04/02/2018)

v1.0
----
- Creation of bundle (04/02/2018)
