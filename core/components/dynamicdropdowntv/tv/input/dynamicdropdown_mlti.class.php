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
 * @subpackage input render
 *
 * Input Render for DynamicDropdownTV Multiple
 */
if (!class_exists('DynamicDropdownMultipleInputRender')) {

	class DynamicDropdownMultipleInputRender extends modTemplateVarInputRender {

		public function getTemplate() {
			$corePath = $this->modx->getOption('dynamicdropdowntv.core_path', null, $this->modx->getOption('core_path') . 'components/dynamicdropdowntv/');
			return $corePath . 'tv/input/tpl/dynamicdropdown_mlti.tpl';
		}

		public function process($value, array $params = array()) {
			$corePath = $this->modx->getOption('dynamicdropdowntv.core_path', null, $this->modx->getOption('core_path') . 'components/dynamicdropdowntv/');

			// fetch only the tv lexicon
			$this->modx->lexicon->load('tv_widget');
			$this->modx->lexicon->load('dynamicdropdowntv:inputoptions');
			$lang = $this->modx->lexicon->fetch();
			foreach ($lang as $k => $v) {
				if (strpos($k, 'dynamicdropdowntv.') !== false) {
					$k = str_replace('dynamicdropdowntv.', '', $k);
					$k = str_replace('.', '_', $k);
				}
				$this->setPlaceholder('lang_' . $k, $v);
			}
			$this->setPlaceholder('params', $params);

			$resource = is_object($this->modx->resource) ? $this->modx->resource->toArray() : array();

			$ddId = $this->tv->get('name');
			$groupPath = 'mgr/' . $params['group'];
			$defaultPath = 'mgr/default';
			$actionPath = file_exists($corePath . 'processors/' . $groupPath) ? $groupPath . '/' : $defaultPath . '/';

			if ($this->tv->get('elements') == '') {
				$default_processor = 'getoptions.default';
				$ddProcessor = 'getoptions.' . $ddId;
			} else {
				$default_processor = 'getelements.default';
				$ddProcessor = 'getelements.' . $ddId;
			}

			$action = file_exists($corePath . 'processors/' . $actionPath . $ddProcessor . '.php') ? $actionPath . $ddProcessor : $actionPath . $default_processor;

			$this->setPlaceholder('connector_path', "'" . $this->modx->getOption('dynamicdropdowntv.assets_path', NULL, "' + MODx.config.assets_url + 'components/dynamicdropdowntv/") . "connector.php'");
			$this->setPlaceholder('resource', $resource);
			$this->setPlaceholder('object_id', $this->modx->getOption('object_id', $_REQUEST, ''));
			$this->setPlaceholder('params', $params);
			$this->setPlaceholder('action', $action);
			$this->setPlaceholder('children', $this->modx->toJson(explode(',', $params['childs'])));
			$this->setPlaceholder('parents', $this->modx->toJson(explode(',', $params['parents'])));
			$this->setPlaceholder('ddId', $ddId);
		}

	}

}
return 'DynamicDropdownMultipleInputRender';