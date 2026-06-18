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
 * DynamicDropdownTV getoptions default processor
 */
$query = $modx->getOption('query', $scriptProperties, '');

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname'] ?? ''));
if (!$tv) {
	return $this->outputArray(array(), 0);
}
$inputProperties = $tv->get('input_properties');
if (!is_array($inputProperties)) { $inputProperties = array(); }

$modx->lexicon->load('tv_widget', 'dynamicdropdowntv:inputoptions');
$lang = $modx->lexicon->fetch('dynamicdropdowntv.', TRUE);

$firstText = $modx->getOption('firstText', $inputProperties, isset($lang['firstText_default']) ? $lang['firstText_default'] : '', TRUE);
$where = $modx->getOption('where', $inputProperties, '');

/* PHP 8: normalize empty scriptProperties to a sentinel so chunk-processed
 * `where` clauses still resolve. The original code did `$scriptProperty[$key]`
 * which silently mutated a string index — replaced with proper array write. */
foreach ($scriptProperties as $key => $scriptProperty) {
	if ($scriptProperty === '' || $scriptProperty === null) {
		$scriptProperties[$key] = '999999999999999';
	}
}

if (!empty($where)) {
	$chunk = $modx->newObject('modChunk');
	$chunk->setCacheable(false);
	$chunk->setContent($where);
	$where = $chunk->process($scriptProperties);
}

$classname = $modx->getOption('classname', $inputProperties, 'modResource', TRUE);
$idfield = $modx->getOption('idfield', $inputProperties, 'id', TRUE);
$namefield = $modx->getOption('namefield', $inputProperties, 'pagetitle', TRUE);
$packageName = $modx->getOption('packagename', $inputProperties, '');
$prefix = $modx->getOption('prefix', $inputProperties, '');
$prefix = empty($prefix) ? null : $prefix;

if (!empty($packageName)) {
	$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
	$modelpath = $packagepath . 'model/';
	$modx->addPackage($packageName, $modelpath, $prefix);
}

$c = $modx->newQuery($classname);

if (!empty($where)) {
	$decoded = json_decode($where, true);
	if (is_array($decoded)) {
		$c->where($decoded);
	}
}

$options = array();
$rows = array();
$count = 1;

if (!empty($query)) {
	$c->where(array($namefield . ':LIKE' => $query . '%'));
} else {
	$options[] = array('id' => '', 'name' => $firstText);
}

if ($collection = $modx->getCollection($classname, $c)) {
	$count += $modx->getCount($classname);
	foreach ($collection as $object) {
		$option = array(
			'id'   => $object->get($idfield),
			'name' => $object->get($namefield),
		);
		$rows[strtolower((string)$option['name'])] = $option;
	}
	ksort($rows);

	foreach ($rows as $option) {
		$options[] = $option;
	}
}

return $this->outputArray($options, $count);
