--------------------
dddx
--------------------
Version: 1.0.0
Author: Bruno Perner <b.perner@gmx.de>
--------------------

dynamic_dropdown-TV is a custom-tv-input-type for selecting related values
each dropdown can have on or more dynamic_dropdown-children, which are updated on selection 
 
Installation: by package-management

Example 1 (uses the default-processor):

Create 3 dynamic_dropdown-TVs:

name: dddx0
Parent Dropdown:
dddx Group: mygroup
where: {"parent":"0"}

name: dddx1
Parent Dropdown: dddx0
dddx Group: mygroup  
where: {"parent":"[[+dddx0]]"}

name: dddx2
Parent Dropdown: dddx1
dddx Group: mygroup
where: {"parent":"[[+dddx1]]"}  

 
Example 2 (with individual processors):
 
Create 3 dynamic_dropdown-TVs:

name: resource_level0
Parent Dropdown:
dddx Group: resource_levels 

name: resource_level1
Parent Dropdown: resource_level0
dddx Group: resource_levels     

name: resource_level2
Parent Dropdown: resource_level1
dddx Group: resource_levels  

Note: create all dropdown-TVs from up to down!

To work correctly, each dropdown needs to know all its parents and childs.
There is running a plugin (onTvFormSave), which collects all parents- and childs-dynamic_dropdowns of the same dddx-group and stores them in input-properties for each TV after saving the TV.  

This will add three dropdown-selects, where you can choose resources.
By selecting a resource of the first level. The next selectbox will open with childs of this resource.
By selecting one of the second level, the third one will open with its childs.


Build own sets:
choose your own group-name and create a new folder under 
/core/components/dddx/processors/mgr/your own group name

and create processor-files for each dropdown with names like:

getoptions.your-dynamic_dropdown-TV-name.php
all childs will send a requestParam with selected value of its parents and the name of its parents dynamic_dropdown-TV
see the files under /resource_levels for examples.

you can do live-searching in options
with 
Enable Type-Ahead: true
and
Force Selection to List: true


