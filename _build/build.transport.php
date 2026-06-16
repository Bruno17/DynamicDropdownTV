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
 * DynamicDropdownTV build script (dual-compat MODX 2.x / 3.x).
 *
 * Source elements, processors, TV input types, lexicons and docs are read from
 * core/components/dynamicdropdowntv/ and assets/components/dynamicdropdowntv/.
 * This _build/ folder contains build scripts only — no project source code.
 *
 * Run from CLI:
 *   cd <modx>/_build/dynamicdropdowntv/_build && php build.transport.php
 *
 * @package dynamicdropdowntv
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

require_once 'build.config.php';

/* define sources */
$root = dirname(dirname(__FILE__)) . '/';
$sources = [
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'resolvers' => $root . '_build/resolvers/',
    'docs' => $root . 'core/components/' . PKG_NAME_LOWER . '/docs/',
    'plugins' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/',
    'source_core' => $root . 'core/components/' . PKG_NAME_LOWER,
    'source_assets' => $root . 'assets/components/' . PKG_NAME_LOWER,
];
unset($root);

require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once $sources['build'] . 'includes/functions.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
$modx->getService('error', 'error.modError');

echo '<pre>';

/* Dual compat: MODX 2 needs explicit loadClass, MODX 3 autoloads via alias. */
if (!class_exists('modPackageBuilder')) {
    if (method_exists($modx, 'loadClass')) {
        $modx->loadClass('transport.modPackageBuilder', '', false, true);
    }
    if (!class_exists('modPackageBuilder') && class_exists('\\MODX\\Revolution\\Transport\\modPackageBuilder')) {
        class_alias('\\MODX\\Revolution\\Transport\\modPackageBuilder', 'modPackageBuilder');
    }
}

$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

/* create category */
$category = $modx->newObject('modCategory');
$category->set('id', 1);
$category->set('category', PKG_NAME);

/* create category vehicle attributes */
$attr = [
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
];

/* add plugins */
if (defined('BUILD_PLUGIN_UPDATE')) {
    $attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['Plugins'] = [
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => BUILD_PLUGIN_UPDATE,
        xPDOTransport::UNIQUE_KEY => 'name',
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
            'PluginEvents' => [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY => ['pluginid', 'event'],
            ],
        ],
    ];
    $plugins = include $sources['data'] . 'transport.plugins.php';
    if (!is_array($plugins)) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in plugins.');
    } else {
        $category->addMany($plugins, 'Plugins');
        $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($plugins) . ' plugins.');
    }
}

$vehicle = $builder->createVehicle($category, $attr);

/* file resolvers — deploy core and assets folders */
$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers ...');
$vehicle->resolve('file', [
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
]);
$vehicle->resolve('file', [
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
]);

/* php resolvers — package rename + MIGX dependency check */
foreach ($BUILD_RESOLVERS as $resolver) {
    if ($vehicle->resolve('php', ['source' => $sources['resolvers'] . 'resolve.' . $resolver . '.php'])) {
        $modx->log(modX::LOG_LEVEL_INFO, 'Added resolver "' . $resolver . '" to category.');
    } else {
        $modx->log(modX::LOG_LEVEL_INFO, 'Could not add resolver "' . $resolver . '" to category.');
    }
}

flush();
$builder->putVehicle($vehicle);

/* package attributes — docs, setup options and dependency requirements */
$builder->setPackageAttributes([
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => [
        'source' => $sources['build'] . 'setup-options.php',
    ],
    'requires' => [
        'php' => '>=7.4.0',
        'migx' => '>=2.0.0',
    ],
]);
$modx->log(modX::LOG_LEVEL_INFO, 'Added package attributes and setup options.');

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO, 'Packing up transport package zip...');
$builder->pack();

$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = sprintf('%2.4f s', $tend - $tstart);

$modx->log(modX::LOG_LEVEL_INFO, "\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

echo '</pre>';
