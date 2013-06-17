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
 * Adds events to DynamicDropdownTV plugin
 */
$events = array();

$events['OnTVInputRenderList'] = $modx->newObject('modPluginEvent');
$events['OnTVInputRenderList']->fromArray(array(
	'event' => 'OnTVInputRenderList',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);
$events['OnTVOutputRenderList'] = $modx->newObject('modPluginEvent');
$events['OnTVOutputRenderList']->fromArray(array(
	'event' => 'OnTVOutputRenderList',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);
$events['OnTVInputPropertiesList'] = $modx->newObject('modPluginEvent');
$events['OnTVInputPropertiesList']->fromArray(array(
	'event' => 'OnTVInputPropertiesList',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);
$events['OnTVOutputRenderPropertiesList'] = $modx->newObject('modPluginEvent');
$events['OnTVOutputRenderPropertiesList']->fromArray(array(
	'event' => 'OnTVOutputRenderPropertiesList',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);
$events['OnManagerPageBeforeRender'] = $modx->newObject('modPluginEvent');
$events['OnManagerPageBeforeRender']->fromArray(array(
	'event' => 'OnManagerPageBeforeRender',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);
$events['OnTVFormSave'] = $modx->newObject('modPluginEvent');
$events['OnTVFormSave']->fromArray(array(
	'event' => 'OnTVFormSave',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);

return $events;

