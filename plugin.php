<?php
/**
 * Plugin Name: Klarity Vimeo wrapper block
 * Plugin URI: https://github.com/Klarityorg/wp-plugin-vimeo-wrapper
 * Description: Klarity Vimeo wrapper block
 * Author: Klarity
 * Author URI: https://github.com/Klarityorg
 * Version: 1.1.5
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Klarity
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
