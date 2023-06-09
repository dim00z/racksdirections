<?php

/**
 * -------------------------------------------------------------------------
 * RacksDirections plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of the RacksDirections plugin for GLPI.
 *
 * RacksDirections is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * RacksDirections is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with RacksDirections. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2023 by Dimitri Mestdagh.
 * @license   GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * @link      https://github.com/dim00z/racksdirections
 * -------------------------------------------------------------------------
 */

use Glpi\Plugin\Hooks;

define('PLUGIN_RACKSDIRECTIONS_VERSION', '0.0.2');

// Minimal GLPI version, inclusive
define('PLUGIN_RACKSDIRECTIONS_MIN_GLPI', '10.0.0');

// Maximum GLPI version, exclusive
define('PLUGIN_RACKSDIRECTIONS_MAX_GLPI', '10.0.99');

if (!defined("PLUGIN_RACKSDIRECTIONS_DIR")) {
    define("PLUGIN_RACKSDIRECTIONS_DIR", Plugin::getPhpDir("racksdirections"));
}
if (!defined("PLUGIN_RACKSDIRECTIONS_WEB_DIR")) {
    define("PLUGIN_RACKSDIRECTIONS_WEB_DIR", Plugin::getWebDir("racksdirections"));
}

if (!defined("PLUGIN_RACKSDIRECTIONS_DOC_DIR")) {
    define("PLUGIN_RACKSDIRECTIONS_DOC_DIR", GLPI_PLUGIN_DOC_DIR . "/racksdirections");
}
if (!file_exists(PLUGIN_RACKSDIRECTIONS_DOC_DIR)) {
    mkdir(PLUGIN_RACKSDIRECTIONS_DOC_DIR);
}

if (!defined("PLUGIN_RACKSDIRECTIONS_CLASS_PATH")) {
    define("PLUGIN_RACKSDIRECTIONS_CLASS_PATH", PLUGIN_RACKSDIRECTIONS_DIR . "/inc");
}
if (!file_exists(PLUGIN_RACKSDIRECTIONS_CLASS_PATH)) {
    mkdir(PLUGIN_RACKSDIRECTIONS_CLASS_PATH);
}

if (!defined("PLUGIN_RACKSDIRECTIONS_FRONT_PATH")) {
    define("PLUGIN_RACKSDIRECTIONS_FRONT_PATH", PLUGIN_RACKSDIRECTIONS_DIR . "/front");
}
if (!file_exists(PLUGIN_RACKSDIRECTIONS_FRONT_PATH)) {
    mkdir(PLUGIN_RACKSDIRECTIONS_FRONT_PATH);
}

/**
 * Get the name and the version of the plugin - REQUIRED
 */
function plugin_version_racksdirections() {
	return [
		'name'           => __("Racks Directions", "racksdirections"),
		'version'        => PLUGIN_RACKSDIRECTIONS_VERSION,
		'author'         => 'Dimitri Mestdagh',
		'license'        => 'GPLv3.0',
		'homepage'       => 'https://github.com/dim00z/racksdirections',
		'requirements'   => [
			'glpi' => [
				'min' => PLUGIN_RACKSDIRECTIONS_MIN_GLPI,
				'max' => PLUGIN_RACKSDIRECTIONS_MAX_GLPI,
			]
		]
	];
}

/**
 *  Check if the config is ok - REQUIRED
 */
function plugin_racksdirections_check_config() {
    return true;
}

/**
 * Check if the prerequisites of the plugin are satisfied - REQUIRED
 */
function plugin_racksdirections_check_prerequisites() {
 
    // Check that the GLPI version is compatible:
    if (version_compare(GLPI_VERSION, '10.0.0', 'lt') || version_compare(GLPI_VERSION, '10.0.7', 'gt')) {
        echo __('This plugin requires GLPI >= 10.0.0 and GLPI < 10.0.8', 'racksdirections');
        return false;
    }
 
    return true;
}


/**
 * Init hooks of the plugin - REQUIRED
 *
 * @return void
 */
function plugin_init_racksdirections() {
   
	global $PLUGIN_HOOKS,$CFG_GLPI;
	
	$PLUGIN_HOOKS[Hooks::CSRF_COMPLIANT]['racksdirections'] = true;
	
    // Load plugin custom class:
	include_once(PLUGIN_RACKSDIRECTIONS_DIR . "/inc/racksdirections.class.php");
	
	// Add tab on rack admin page:
	Plugin::registerClass('PluginRacksDirections', array('addtabon' => array('Rack')));
	
	// Add plugin config page:
	if (Session::haveRight('config', UPDATE)) {
		$PLUGIN_HOOKS['config_page']['racksdirections'] = 'front/config.form.php';
	}

	return;
   
}
