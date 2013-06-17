<?php
/**
 * DynamicDropdownTV
 *
 * Copyright 2012-2013 by Bruno Perner <b.perner@gmx.de>
 *
 * DynamicDropdownTV is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * DynamicDropdownTV is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * DynamicDropdownTV; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package dynamicdropdowntv
 * @subpackage build
 *
 * Package in plugins.
 */
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id', 1);
$plugins[0]->set('name', 'DynamicDropdownTV');
$plugins[0]->set('description', 'Tell MODX to check these directories for DynamicDropdownTV files and save parents and childrens of DynamicDropdownTVs to the TV options.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'dynamicdropdown.plugin.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'] . 'dynamicdropdowntv.events.php';
if (is_array($events) && !empty($events)) {
	$plugins[0]->addMany($events);
	$modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in ' . count($events) . ' Plugin events for DynamicDropdownTV.');
	flush();
} else {
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events for DynamicDropdownTV!');
}
unset($events);

return $plugins;
