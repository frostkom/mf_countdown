<?php
/*
Plugin Name: MF Countdown
Plugin URI: https://github.com/frostkom/mf_countdown
Description:
Version: 1.1.8
Licence: GPLv2 or later
Author: Martin Fors
Author URI: https://martinfors.se
Text Domain: lang_countdown
Domain Path: /lang
*/

if(!function_exists('is_plugin_active') || function_exists('is_plugin_active') && is_plugin_active("mf_base/index.php"))
{
	include_once("include/classes.php");

	$obj_countdown = new mf_countdown();

	add_action('enqueue_block_editor_assets', array($obj_countdown, 'enqueue_block_editor_assets'));
	add_action('init', array($obj_countdown, 'init'));

	//add_filter('get_loading_animation', array($obj_countdown, 'get_loading_animation'), 9, 2);

	add_action('wp_ajax_api_countdown_validate', array($obj_countdown, 'api_countdown_validate'));
	add_action('wp_ajax_nopriv_api_countdown_validate', array($obj_countdown, 'api_countdown_validate'));
}