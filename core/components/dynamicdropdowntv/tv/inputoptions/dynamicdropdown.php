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
 * @subpackage input render properties
 *
 * Input Render for DynamicDropdownTV Properties
 */
$modx->lexicon->load('tv_widget', 'dynamicdropdowntv:inputoptions');
$lang = $modx->lexicon->fetch('dynamicdropdowntv.', true);

// get list of dynamic dropdown template variables to select parent dropdown
$c = $modx->newQuery('modTemplateVar');
$c->where(array('type:LIKE' => 'dynamicdropdown%'));
$currentTv = isset($_REQUEST['tv']) ? (int)$_REQUEST['tv'] : 0;
$list = array();
$list[] = array('', $lang['noParent']);
if ($collection = $modx->getCollection('modTemplateVar', $c)) {
	foreach ($collection as $object) {
		if ($currentTv != (int)$object->get('id')) {
			$list[] = array($object->get('name'), $object->get('name'));
		}
	}
}
$list = json_encode($list);

/* PHP 8: guard against $modx->smarty being null (e.g. CLI or headless context) */
if (!isset($modx->smarty) || !$modx->smarty) {
	return '';
}

$modx->smarty->assign('tvlist', $list);
$modx->smarty->assign('dynamicdropdowntv', $lang);

$corePath = $modx->getOption('dynamicdropdowntv.core_path', null, $modx->getOption('core_path') . 'components/dynamicdropdowntv/');
return $modx->smarty->fetch($corePath . 'tv/inputoptions/tpl/dynamicdropdown.tpl');
