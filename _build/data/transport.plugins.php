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
 * Dual-compat (MODX 2.x / 3.x) build maintained in this fork by JNogueira.
 *
 *
 * DynamicDropdownTV — transport.plugins.php
 *
 * Builds the DynamicDropdownTV plugin object (dual-compat MODX 2.x / 3.x).
 * The plugin source is read from core/components/dynamicdropdowntv/elements/plugins/.
 *
 * @package dynamicdropdowntv
 * @subpackage build
 */
$plugins = array();

$tmp = [
    'DynamicDropdownTV' => [
        'file' => 'dynamicdropdowntv',
        'description' => 'Dispatches DynamicDropdownTV input/inputoption render lists and computes parent/child topology on TV save.',
        'events' => [
            'OnTVInputRenderList' => 0,
            'OnTVOutputRenderList' => 0,
            'OnTVInputPropertiesList' => 0,
            'OnTVOutputRenderPropertiesList' => 0,
            'OnTVFormSave' => 0,
        ],
    ],
];

foreach ($tmp as $k => $v) {
    /** @var modPlugin $plugin */
    $plugin = $modx->newObject('modPlugin');
    /** @noinspection PhpUndefinedVariableInspection */
    $plugin->fromArray([
        'name' => $k,
        'description' => @$v['description'],
        'plugincode' => getPhpFileContent($sources['source_core'] . '/elements/plugins/' . $v['file'] . '.plugin.php'),
        'static' => BUILD_PLUGIN_STATIC,
        'source' => 1,
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/' . $v['file'] . '.plugin.php',
    ], '', true, true);

    $events = [];
    if (!empty($v['events']) && is_array($v['events'])) {
        foreach ($v['events'] as $name => $priority) {
            /** @var modPluginEvent $event */
            $event = $modx->newObject('modPluginEvent');
            $event->fromArray([
                'event' => $name,
                'priority' => $priority,
                'propertyset' => 0,
            ], '', true, true);
            $events[] = $event;
        }
        unset($v['events']);
    }

    if (!empty($events)) {
        $plugin->addMany($events);
    }

    $plugins[] = $plugin;
}

return $plugins;
