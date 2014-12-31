<?php
/**
 * Plugin Name: Moonlight
 * Plugin URI:  https://eamann.com
 * Description: Auto-link names to Twitter handles.
 * Version:     0.1.0
 * Author:      Eric Mann
 * Author URI:  https://eamann.com
 * License:     GPLv2+
 * Text Domain: moonlight
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 Eric Mann (email : eric@eamann.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'MOONLIGHT_VERSION', '0.1.0' );
define( 'MOONLIGHT_URL',     plugin_dir_url( __FILE__ ) );
define( 'MOONLIGHT_PATH',    dirname( __FILE__ ) . '/' );

// Include necessary files
include __DIR__ . '/includes/functions/core.php';

// Bootstrap
Moonlight\Core\setup();

// WordPress activation
register_activation_hook(   __FILE__, 'Moonlight\Core\activate'   );
register_deactivation_hook( __FILE__, 'Moonlight\Core\deactivate' );