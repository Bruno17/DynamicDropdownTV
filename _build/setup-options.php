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
 * DynamicDropdownTV — install setup-options screen.
 *
 * Renders an HTML page on install/upgrade with environment + dependency checks.
 * This is purely informational; it does NOT block install (we want the user to
 * see issues but still be able to proceed when running in atypical environments).
 *
 * @var array $options
 * @var modX  $modx
 */

if (!isset($options[xPDOTransport::PACKAGE_ACTION])) {
    return '';
}
$action = $options[xPDOTransport::PACKAGE_ACTION];
if (!in_array($action, [xPDOTransport::ACTION_INSTALL, xPDOTransport::ACTION_UPGRADE], true)) {
    return '';
}

$rows = [];

/* PHP version */
$phpOk  = version_compare(PHP_VERSION, '7.4', '>=');
$rows[] = [
    'PHP version', PHP_VERSION,
    $phpOk ? 'ok' : 'warn',
    $phpOk
        ? 'Meets the recommended minimum (>= 7.4). Tested on 8.x.'
        : 'Detected PHP version is older than 7.4. The extra may still run but is unsupported.',
];

/* MODX version */
$modxVer = '?';
if (defined('MODX_VERSION')) {
    $modxVer = MODX_VERSION;
} elseif (isset($modx->version) && is_array($modx->version) && !empty($modx->version['full_version'])) {
    $modxVer = $modx->version['full_version'];
} else {
    try {
        $sv = (string)$modx->getOption('settings_version', null, '');
        if ($sv !== '') {
            $modxVer = $sv;
        }
    } catch (\Throwable $e) { /* ignore */ }
    if ($modxVer === '?') {
        $modxVer = is_object($modx) && strpos(get_class($modx), 'MODX\\Revolution') === 0 ? '3.x' : '?';
    }
}
$modxNumeric = ($modxVer !== '?') ? preg_replace('/[^\d.].*$/', '', (string)$modxVer) : '';
$modxOk = $modxNumeric !== '' ? version_compare($modxNumeric, '2.6', '>=') : true;
$rows[] = [
    'MODX version', $modxVer,
    $modxOk ? 'ok' : 'warn',
    $modxOk
        ? 'Compatible with MODX Revolution >= 2.6 and MODX 3.x.'
        : 'Detected MODX version is older than 2.6. Some features may not work.',
];

/* MIGX dependency */
$migxInstalled = false;
try {
    $migxPath = $modx->getOption('core_path') . 'components/migx/';
    $migxInstalled = is_dir($migxPath);
    if (!$migxInstalled) {
        // Also try via namespace
        $ns = $modx->getObject('modNamespace', ['name' => 'migx']);
        $migxInstalled = !empty($ns);
    }
} catch (\Throwable $e) { /* ignore */ }
$rows[] = [
    'MIGX extra', $migxInstalled ? 'installed' : 'NOT FOUND',
    $migxInstalled ? 'ok' : 'err',
    $migxInstalled
        ? 'MIGX is installed. Required dependency for DynamicDropdownTV configurations.'
        : 'MIGX is a required dependency! Please install MIGX from the MODX package manager before using DynamicDropdownTV.',
];

/* PHP extension: json */
$jsonOk = extension_loaded('json');
$rows[] = [
    'ext-json', $jsonOk ? 'loaded' : 'missing',
    $jsonOk ? 'ok' : 'err',
    $jsonOk
        ? 'Required for processor JSON responses.'
        : 'The json extension is required for processor JSON responses. Please enable it in php.ini.',
];

/* PHP extension: pdo_mysql */
$pdoOk = extension_loaded('pdo_mysql');
$rows[] = [
    'ext-pdo_mysql', $pdoOk ? 'loaded' : 'missing',
    $pdoOk ? 'ok' : 'err',
    $pdoOk
        ? 'Required for xPDO database queries.'
        : 'The pdo_mysql extension is required for xPDO queries. Please enable it in php.ini.',
];

/* ─── Render ─────────────────────────────────────────────────────────── */

$html = <<<'CSS'
<style>
.ddtv-wrap{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:1.45;color:#333;max-width:640px}
.ddtv-wrap h2{margin:0 0 4px;font-size:15px;font-weight:600}
.ddtv-wrap p.ddtv-lead{margin:0 0 10px;color:#555;font-size:12px}
.ddtv-wrap table{width:100%;border-collapse:collapse;margin-bottom:6px}
.ddtv-wrap th,.ddtv-wrap td{text-align:left;padding:4px 6px;border-bottom:1px solid #e4e4e4;font-size:12px}
.ddtv-wrap th{background:#f7f7f7;font-weight:600}
.ddtv-pill{display:inline-block;padding:1px 7px;border-radius:9px;font-size:11px;font-weight:600;line-height:1.6}
.ddtv-pill-ok{background:#d4edda;color:#155724}
.ddtv-pill-warn{background:#fff3cd;color:#856404}
.ddtv-pill-err{background:#f8d7da;color:#721c24}
.ddtv-pill-info{background:#d1ecf1;color:#0c5460}
.ddtv-note{font-size:11px;color:#666;margin-top:2px}
</style>
CSS;

$html .= '<div class="ddtv-wrap">';
$html .= '<h2>DynamicDropdownTV &mdash; environment check</h2>';
$html .= '<p class="ddtv-lead">This is an informational pre-install check for DynamicDropdownTV. It verifies that your environment meets the recommended requirements and that required dependencies are present. Installation will proceed regardless of the results below.</p>';
$html .= '<table><thead><tr><th>Check</th><th>Value</th><th>Status</th><th>Notes</th></tr></thead><tbody>';

foreach ($rows as $r) {
    [$label, $value, $level, $note] = $r;
    $pillClass = 'ddtv-pill ddtv-pill-' . $level;
    $statusLabel = [
        'ok'   => 'OK',
        'warn' => 'WARN',
        'err'  => 'ERROR',
        'info' => 'INFO',
    ][$level] ?? strtoupper($level);

    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($label) . '</td>';
    $html .= '<td><code>' . htmlspecialchars($value) . '</code></td>';
    $html .= '<td><span class="' . $pillClass . '">' . $statusLabel . '</span></td>';
    $html .= '<td>' . htmlspecialchars($note) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';
$html .= '<p class="ddtv-note">Checks are informational only and do not block installation.</p>';
$html .= '</div>';

return $html;
