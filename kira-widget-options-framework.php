<?php
/**
 * Plugin Name: Kira Widget Options Framework
 * Plugin URI: https://github.com/sabuz/kira-widget-options-framework
 * Description: A comprehensive framework for creating WordPress widget options with various field types including text, textarea, select, radio, checkbox, and color picker fields.
 * Version: 1.1
 * Author: Nazmul Sabuz
 * Author URI: https://profiles.wordpress.org/nazsabuz/
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kira-widget-options-framework
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

require_once __DIR__ . '/includes/class-kira-widget-options-framework.php';

add_action(
	'admin_init',
	function () {
		$GLOBALS['kira_widget_options_framework'] = new Kira_Widget_Options_Framework();
	}
);
