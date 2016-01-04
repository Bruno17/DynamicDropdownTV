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
 * @subpackage processor
 *
 * DynamicDropdownTV example gallery albums processor for albums0 tv
 */
$query = $modx->getOption('query', $scriptProperties, '');
$line_id = $modx->getOption('productlines',$scriptProperties,0);

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');

$modx->lexicon->load('tv_widget', 'dynamicdropdowntv:inputoptions');
$lang = $modx->lexicon->fetch('dynamicdropdowntv.', TRUE);

$firstText = $modx->getOption('firstText', $inputProperties, $lang['firstText_default'], TRUE);

$packageName = 'rhythmcatalog';
$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$modx->addPackage($packageName, $modelpath);

$classname = 'productGroup';
$c = $modx->newQuery($classname);

$options = array();
$count = 1;

if (!empty($query)) {
	$c->where(array('name:LIKE' => $query . '%'));
} else {
	$options[] = array('id' => '', 'name' => $firstText);
}

$c->where(array('line_id' => $line_id));

//$c->prepare();echo $c->toSql();
if ($collection = $modx->getCollection($classname, $c)) {
	$count += $modx->getCount($classname);
	foreach ($collection as $object) {
		$option['id'] = $object->get('id');
		$option['name'] = $object->get('name');
		$rows[strtolower($option['name'])] = $option;
	}
	ksort($rows);

	foreach ($rows as $option) {
		$options[] = $option;
	}
}

return $this->outputArray($options, $count);
