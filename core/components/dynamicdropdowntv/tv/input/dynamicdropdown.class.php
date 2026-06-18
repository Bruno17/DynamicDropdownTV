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
 * Input Render for DynamicDropdownTV
 */
if (!class_exists('DynamicDropdownInputRender')) {

	class DynamicDropdownInputRender extends modTemplateVarInputRender {

		public function getTemplate() {
			$corePath = $this->modx->getOption('dynamicdropdowntv.core_path', null, $this->modx->getOption('core_path') . 'components/dynamicdropdowntv/');
			return $corePath . 'tv/input/tpl/dynamicdropdown.tpl';
		}

		/* MODX 3 + MigX compatibility: $modx->controller is not always
		 * populated when the TV input renderer is invoked from inside
		 * MigX's "fields" form-builder pipeline (MigX still references
		 * the MODX 2 `modManagerControllerDeprecated` path which does
		 * not exist on MODX 3). The base modTemplateVarInputRender
		 * delegates both setPlaceholder() and fetchTemplate() through
		 * $this->modx->controller, which then fatals with
		 * "Call to … on null" and MODX falls back to a plain text input
		 * (showing the raw numeric ID). We route both through Smarty
		 * when no controller is available so the dropdown renders
		 * correctly. This is harmless on MODX 2 where the controller
		 * is always set.
		 */
		public function setPlaceholder($k, $v) {
			if (isset($this->modx->controller) && is_object($this->modx->controller)) {
				$this->modx->controller->setPlaceholder($k, $v);
				return;
			}
			if (!isset($this->modx->smarty) || !is_object($this->modx->smarty)) {
				$this->modx->getService('smarty', 'smarty.modSmarty');
			}
			if (isset($this->modx->smarty) && is_object($this->modx->smarty)) {
				$this->modx->smarty->assign($k, $v);
			}
		}

		public function render($value, array $params = array()) {
			if (isset($this->modx->controller) && is_object($this->modx->controller)) {
				return parent::render($value, $params);
			}
			$this->setPlaceholder('tv', $this->tv);
			$this->setPlaceholder('id', $this->tv->get('id'));
			$this->setPlaceholder('ctx', isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');
			$this->setPlaceholder('params', $params);
			$this->_loadLexiconTopics();
			$this->process($value, $params);
			$tpl = $this->getTemplate();
			if (empty($tpl) || !isset($this->modx->smarty)) {
				return '';
			}
			return $this->modx->smarty->fetch($tpl);
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

			/* PHP 8: defensively normalize optional params before use. */
			$params = array_merge(array(
				'group' => '', 'childs' => '', 'parents' => '',
				'title' => '', 'listWidth' => '', 'maxHeight' => '',
				'typeAhead' => '', 'typeAheadDelay' => '',
				'listEmptyText' => '', 'forceSelection' => '',
				'allowBlank' => '',
			), $params);
			$this->setPlaceholder('params', $params);

			$resource = is_object($this->modx->resource) ? $this->modx->resource->toArray() : array();

			$ddId = $this->tv->get('name');
			$params['group'] = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $params['group']);
			$groupPath = 'mgr/' . $params['group'];
			$defaultPath = 'mgr/default';
			$actionPath = ($params['group'] !== '' && file_exists($corePath . 'processors/' . $groupPath)) ? $groupPath . '/' : $defaultPath . '/';

			if ($this->tv->get('elements') == '') {
				$default_processor = 'getoptions.default';
				$ddProcessor = 'getoptions.' . $ddId;
			} else {
				$default_processor = 'getelements.default';
				$ddProcessor = 'getelements.' . $ddId;
			}

			/* MODX 3 only loads class-style processors (.class.php).
			 * MODX 2 prefers legacy (.php) but also accepts .class.php.
			 * Prefer the class file when present, fall back to legacy. */
			$override = $corePath . 'processors/' . $actionPath . $ddProcessor;
			if (file_exists($override . '.class.php') || file_exists($override . '.php')) {
				$action = $actionPath . $ddProcessor;
			} else {
				$action = $actionPath . $default_processor;
			}

			$this->setPlaceholder('connector_path', "'" . $this->modx->getOption('dynamicdropdowntv.assets_path', NULL, "' + MODx.config.assets_url + 'components/dynamicdropdowntv/") . "connector.php'");
			$this->setPlaceholder('resource', $resource);
			$this->setPlaceholder('object_id', (int) $this->modx->getOption('object_id', $_REQUEST, 0));
			$this->setPlaceholder('params', $params);
			$this->setPlaceholder('action', $action);
			$this->setPlaceholder('children', json_encode(array_values(array_filter(explode(',', (string)$params['childs']))), JSON_UNESCAPED_SLASHES));
			$this->setPlaceholder('parents',  json_encode(array_values(array_filter(explode(',', (string)$params['parents']))), JSON_UNESCAPED_SLASHES));
			$this->setPlaceholder('ddId', $ddId);
		}

	}

}
return 'DynamicDropdownInputRender';