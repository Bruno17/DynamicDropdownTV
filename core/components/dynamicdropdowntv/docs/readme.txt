DynamicDropdownTV — Custom dual-compat build by João Nogueira from original release 1.3.0 by Bruno Perner
==========================================================================================================

This is a maintenance fork of the original DynamicDropdownTV extra by
Bruno Perner. It refreshes the codebase for modern PHP and MODX:

- PHP 8.x compatibility — every undefined-array-key access path was
  guarded, the inverted count() parenthesis bug in OnTVFormSave was
  fixed, and the $scriptProperty[$key] mutation bug in the default
  getoptions processor was rewritten to write to $scriptProperties
  instead.

- MODX 3 compatibility — replaced the removed $modx->toJson() /
  $modx->fromJson() calls with json_encode() / json_decode() and
  swapped the deprecated Ext.util.JSON.decode for Ext.decode in the
  ExtJS templates.

- Defense in depth — the asset connector now requires an authenticated
  manager session before dispatching the request, matching the policy
  used by the bundled cttupdater extra.

Original copyright (c) 2012-2013 Bruno Perner, GPL-2.0+.
Custom dual-compat patches (c) 2026 João Nogueira.

Installation
------------
Install from Manager > Extras > Installer > Add New Package > Upload
Package. Pick the dynamicdropdowntv-x.y.z-pl.transport.zip file.
The extra registers a plugin (DynamicDropdownTV) bound to the TV
input/inputoption render-list events and to OnTVFormSave.

Compatibility
-------------
- MODX Revolution 2.6+ (manager UI assumes ExtJS-based MODX manager).
- MODX 3.x.
- PHP >= 7.4 (tested on PHP 8.5).

Dependencies
------------
- MIGX (any current version) must be installed.


Original DynamicDropdownTV Documentation by Bruno Perner
========================================================

DynamicDropdownTV
================================================================================

Dynamic dropdown custom template variable for MODX Revolution.

Features
--------------------------------------------------------------------------------
With this MODX Revolution custom template variable related dropdown template
variables could be used. Each dynamic dropdown template variable could have one
or more child dynamic dropdown template variables which are updated on selection
of the parent dynamic dropdown template variable. 

Installation
--------------------------------------------------------------------------------
MODX Package Management

Examples
--------------------------------------------------------------------------------

Example 1: Default processor usage (XPDO Package)
················································································

Create three Dynamic Dropdown template variables:

First

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | dynamic0
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Parent Dropdown        |
Input Options       | Dynamic Dropdown Group | dynamicGroup
Input Options       | Where                  | {"parent":"0"}

Second

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | dynamic1
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Parent Dropdown        | dynamic0
Input Options       | Dynamic Dropdown Group | dynamicGroup
Input Options       | Where                  | {"parent":"[[+dynamic0:default=`999999999999999`]]"}

Third

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | dynamic2
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Parent Dropdown        | dynamic1
Input Options       | Dynamic Dropdown Group | dynamicGroup
Input Options       | Where                  | {"parent":"[[+dynamic1:default=`999999999999999`]]"}

The names dynamicX and dynamicGroup are just examples and could be changed.

This example will add three dropdown select TVs, where you can choose MODX
resources. After selecting a resource in the first TV dynamic0, the dropdown
select of the second TV dynamic1 will show the childs of this resource. After
selecting a resource in the second TV dynamic1, the dropdown select of the third
TV dynamic2 will show the childs of this resource. If you select again a
resource in the first TV dynamic0 all children of this Dynamic Dropdown TV
(dynamic1 and  dynamic2) will be resetted.

Example 2: Default processor usage (Input Option Values)
················································································

Create two Dynamic Dropdown template variables:

First

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | dynamic0
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Dynamic Dropdown Group | dynamicGroup
Input Options       | Input Option Values    | TEST1==1||TEST2==2

Second

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name | dynamic1
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Dynamic Dropdown Group | dynamicGroup
Input Options       | Input Option Values    | 1::TEST1a==1a||TEST1a==1a##2::TEST2b==2b||TEST2b==2b

The names dynamicX and dynamicGroup are just examples and could be changed.

This example will add two dropdown select TVs, where you can different values. After selecting a value in the first TV dynamic0, the dropdown select of the second TV dynamic1 will show related values. If you select again a value in the first TV dynamic0 all children of this Dynamic Dropdown TV (dynamic1) will be reseted.

The format for the input option values is:
Parentvalue::Key==Value||…||Key==Value##Parentvalue::Key==Value||…||Key==Value

Creating a new line after the ## is allowed.

Example 3: Individual processor usage
················································································

Create three Dynamic Dropdown template variables:

First

Tab                 | Option                 | Value
------------------- | ---------------------- | ---------------
General Information | Name                   | resource_level0
Input Options       | Parent Dropdown        |
Input Options       | Dynamic Dropdown Group | resource_levels

Second

Tab                 | Option                 | Value
------------------- | ---------------------- | ---------------
General Information | Name                   | resource_level1
Input Options       | Parent Dropdown        | resource_level0
Input Options       | Dynamic Dropdown Group | resource_levels

Third

Tab                 | Option                 | Value
------------------- | ---------------------- | ---------------
General Information | Name                   | resource_level2
Input Options       | Parent Dropdown        | resource_level1
Input Options       | Dynamic Dropdown Group | resource_levels

This example will also add three dropdown select TVs, where you can choose MODX
resources. After selecting a resource in the first TV resource_level0, the
dropdown select of the second TV resource_level1 will show the childs of this
resource. After selecting a resource in the second TV resource_level1, the
dropdown select of the third TV resource_level2 will show the childs of this
resource. If you select again a resource in the first TV resource_level0 all
children of this Dynamic Dropdown TV (resource_level1 and resource_level2) will
be resetted.

The above example uses the example processors in
/core/components/dynamicdropdowntv/processors/mgr/resource_levels.

Create the individual processors
················································································

If you want to create your own processors, choose a group name and create a new
folder in /core/components/dynamicdropdowntv/processors/mgr/your-own-group-name.
Then create processor files for each dynamic dropdown template variable with
names like getoptions.your-dynamic-dropdown-tv-name.php.

All child Dynamic Dropdown TVs will send the name and the selected value of the
parent Dynamic Dropdown TV as request parameter pair to the processor.

If no folder with the group name exists in
/core/components/dynamicdropdowntv/processors/mgr
the default processors are used (as in example 1).

Example 3: Gallery album processor usage:
················································································

Create two Dynamic Dropdown template variables

First

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | albums0
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Parent Dropdown        |
Input Options       | Dynamic Dropdown Group | gallery_albums

Second

Tab                 | Option                 | Value
------------------- | ---------------------- | ----------------
General Information | Name                   | albums1
Input Options       | Input Type             | Dynamic Dropdown
Input Options       | Parent Dropdown        | albums0
Input Options       | Dynamic Dropdown Group | gallery_albums

This example will also add two dropdown select TVs, where you can choose MODX
gallery albums. After selecting a gallery album in the first TV albums0, the
dropdown select of the second TV albums1 will show the child albums of this
album. If you select again a album in the first TV albums0 the child of this
Dynamic Dropdown TV (albums1) will be resetted.

Notes
--------------------------------------------------------------------------------

1. Create all Dynamic Dropdown from up to down!
2. To work correctly, each Dynamic Dropdown TV needs to know all its parents and
   childs. There is a plugin in onTvFormSave, which collects all parents and
   children Dynamic Dropdown TVs of the same Dynamic Dropdown group and stores
   them in input-properties for each TV after saving the TV.
3. You can live search in dropdown select options if you set Enable Type-Ahead
   to Yes and set Force Selection to List to Yes.
