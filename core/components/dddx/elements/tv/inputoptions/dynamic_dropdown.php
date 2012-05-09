<?php

/**
 * dddx
 *
 * Copyright 2012 by Bruno Perner <b.perner@gmx.de>
 *
 * dddx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * dddx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * migx; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package dddx
 */
/**
 * Input TV render for dddx
 *
 * @package dddx
 * @subpackage tv
 */
$modx->lexicon->load('tv_widget', 'dddx:tvprops');
$modx->smarty->assign('base_url', $modx->getOption('base_url'));

$path = 'components/dddx/';

$corePath = $modx->getOption('dddx.core_path', null, $modx->getOption('core_path') . $path);

/* get TV input properties specific language strings */
$lang = $modx->lexicon->fetch('dddx.', true);

$c = $modx->newQuery('modTemplateVar');
$c->where(array('type' => 'dynamic_dropdown'));

$list[] = "['','---']";

if ($collection = $modx->getCollection('modTemplateVar', $c)) {
    foreach ($collection as $object) {
        if ($_REQUEST['tv'] != $object->get('id')){
            $list[] = "['" . $object->get('name') . "','" . $object->get('name') . "']";
        }
    }
}

$list = "[" . implode(',', $list) . "]";

$modx->smarty->assign('tvlist', $list);
$modx->smarty->assign('dddx', $lang);

return $modx->smarty->fetch($corePath . 'elements/tv/dddx.inputproperties.tpl');
