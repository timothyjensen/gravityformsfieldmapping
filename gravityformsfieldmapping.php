<?php

/**
 * Gravity Forms Field Mapping
 *
 * @package     GravityFormsFieldMapping
 * @author      Tim Jensen <tim@timjensen.us>
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 *
 * Plugin Name: Gravity Forms Field Mapping
 * Plugin URI:  https://github.com/timothyjensen/gravityformsfieldmapping
 * Description: Adds field mapping settings so that fields may be identified by key instead of ID.
 * Version:     0.2.2
 * Requires 	PHP: 7.4
 * Author:      Tim Jensen
 * Author URI:  https://www.timjensen.us
 * Text Domain: gravityformsfieldmapping
 * License:     GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace GravityFormsFieldMapping;

if ( ! \defined( 'ABSPATH' ) ) {
	die;
}

if ( version_compare( PHP_VERSION, '7.4.0', '<' ) ) {
	return;
}

\define( 'GF_FIELD_MAPPING_FILE', __FILE__ );

require_once __DIR__ . '/init.php';
