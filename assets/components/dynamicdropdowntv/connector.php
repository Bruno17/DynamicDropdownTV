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
 * @subpackage connector
 *
 * DynamicDropdownTV connector
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

/* Defense-in-depth: refuse anything that is not an authenticated manager. */
if (!isset($modx) || !$modx->user || !$modx->user->isAuthenticated($modx->getOption('manager_context_key', null, 'mgr'))) {
    if (!headers_sent()) { http_response_code(401); }
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    exit;
}

/* handle request */
$corePath = $modx->getOption('dynamicdropdowntv.core_path',null,$modx->getOption('core_path').'components/dynamicdropdowntv/');
$modx->request->handleRequest(array(
    'processors_path' => $corePath.'processors/',
    'location' => '',
));