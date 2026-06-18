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
 * DynamicDropdownTV — package resolver.
 *
 * Polishes the modTransportPackage row after install:
 *   - Renames package_name from "dynamicdropdowntv" to "DynamicDropdownTV"
 *   - Sets a short metadata description
 *   - Checks MIGX dependency and logs a warning if missing
 *
 * @var xPDOObject    $object
 * @var xPDOTransport $transport (not used directly here)
 * @var array         $options
 */

if (!function_exists('dynamicdropdowntv_package_resolver')) {
    function dynamicdropdowntv_package_resolver($object, $options)
    {
        /** @var modX $modx */
        $modx = $object->xpdo;
        if (!$modx instanceof modX) return true;

        $action = $options[xPDOTransport::PACKAGE_ACTION] ?? null;

        // On uninstall, nothing to do
        if ($action === xPDOTransport::ACTION_UNINSTALL) {
            return true;
        }

        // Detect MODX 2 vs 3
        $versionData = $modx->getVersionData();
        $isModx2 = version_compare($versionData['full_version'] ?? '2.0.0', '3.0.0', '<');

        // --- Rename package and set metadata ---
        try {
            $pkgs = $modx->getCollection('transport.modTransportPackage', ['package_name' => 'dynamicdropdowntv']);
            $oneLiner = 'Dynamic dropdown custom TV for MODX Revolution with parent-child cascading relationships. Requires MIGX.';
            $metadata = $isModx2
                ? [['name' => 'description', 'text' => $oneLiner]]
                : ['description' => $oneLiner];

            foreach ($pkgs as $pkg) {
                $pkg->set('package_name', 'DynamicDropdownTV');
                $pkg->set('metadata', $metadata);
                $pkg->save();
            }
        } catch (\Throwable $e) {
            $modx->log(modX::LOG_LEVEL_WARN, '[DynamicDropdownTV package.resolver pkg-polish] ' . $e->getMessage());
        }

        // --- Check MIGX dependency ---
        try {
            $migxPath = $modx->getOption('core_path') . 'components/migx/';
            $migxInstalled = is_dir($migxPath);
            if (!$migxInstalled) {
                $modx->log(
                    modX::LOG_LEVEL_WARN,
                    '[DynamicDropdownTV] MIGX package not found. DynamicDropdownTV requires MIGX to function properly. Please install MIGX from the MODX Extras repository.'
                );
            }
        } catch (\Throwable $e) {
            $modx->log(modX::LOG_LEVEL_WARN, '[DynamicDropdownTV package.resolver migx-check] ' . $e->getMessage());
        }

        return true;
    }
}

return dynamicdropdowntv_package_resolver($object, $options);
