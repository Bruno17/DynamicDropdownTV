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
 * @subpackage plugin
 *
 * Plugins for DynamicDropdownTV
 */
if (!function_exists('ddSetParentChilds')) {

	function ddSetParentChilds($tvName, $parentTvName, $ddTvArr) {
		if (!empty($parentTvName) && array_key_exists($parentTvName, $ddTvArr)) {
			$ddTvArr[$parentTvName]['childs'][$tvName] = $tvName;
			if (!empty($ddTvArr[$parentTvName]['properties']['parent'])) {
				$ddTvArr = ddSetParentChilds($tvName, $ddTvArr[$parentTvName]['properties']['parent'], $ddTvArr);
			}
		}
		return $ddTvArr;
	}

}

$corePath = $modx->getOption('dynamicdropdowntv.core_path', null, $modx->getOption('core_path') . 'components/dynamicdropdowntv/');

switch ($modx->event->name) {
	case 'OnTVInputRenderList':
		$modx->event->output($corePath . 'tv/input/');
		break;
	case 'OnTVOutputRenderList':
		$modx->event->output($corePath . 'tv/output/');
		break;
	case 'OnTVInputPropertiesList':
		$modx->event->output($corePath . 'tv/inputoptions/');
		break;
	case 'OnTVOutputRenderPropertiesList':
		$modx->event->output($corePath . 'tv/properties/');
		break;
	case 'OnManagerPageBeforeRender':
		break;
	case 'OnTVFormSave':
		$tv = &$scriptProperties['tv'];
		$tvName = $tv->get('name');
		$tvType = $tv->get('type');
		if ($tvType == 'dynamicdropdown' || $tvType == 'dynamicdropdown_multiple') {
			$tvInputProperties = $tv->get('input_properties');
			// get all DynamicDropdownTVs
			$c = $modx->newQuery('modTemplateVar');
			$c->where(array('type:LIKE' => 'dynamicdropdown%'));
			$ddTvs = $modx->getCollection('modTemplateVar', $c);

			$ddTvArr = array();
			foreach ($ddTvs as $key => $ddTv) {
				$ddTvInputProperties = $ddTv->get('input_properties');
				// work only with them in the same dd-group
				if ($ddTvInputProperties['group'] == $tvInputProperties['group']) {
					$ddTvArr[$ddTv->get('name')]['properties'] = $ddTv->get('input_properties');
				} else {
					unset($ddTvs[$key]);
				}
			}
			$ddTvResult = $ddTvArr;
			foreach ($ddTvArr as $tvName => $ddTv) {
				$ddTvInputProperties = $ddTv['properties'];
				$ddTvResult = ddSetParentChilds($tvName, $ddTvInputProperties['parent'], $ddTvResult);
			}
			$ddTvArr = $ddTvResult;
			foreach ($ddTvArr as $tvName => $ddTv) {
				$ddTvInputProperties = $ddTv['properties'];
				if (count($ddTv['childs'] > 0)) {
					foreach ($ddTv['childs'] as $child) {
						$ddTvResult[$child]['parents'][$tvName] = $tvName;
					}
				}
			}
			foreach ($ddTvs as $key => $ddTv) {
				$ddTvInputProperties = $ddTv->get('input_properties');
				$ddTvInputProperties['childs'] = isset($ddTvResult[$ddTv->get('name')]['childs']) ? implode(',', $ddTvResult[$ddTv->get('name')]['childs']) : '';
				$ddTvInputProperties['parents'] = isset($ddTvResult[$ddTv->get('name')]['parents']) ? implode(',', $ddTvResult[$ddTv->get('name')]['parents']) : '';
				$ddTv->set('input_properties', $ddTvInputProperties);
				$ddTv->save();
			}
		}
		break;
}
return;
