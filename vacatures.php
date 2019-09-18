<?php

/*
Plugin Name: Vacatures
Description: Deze plugin voegt de nodige types en functies toe aan PropertyPeople en MakelaarsMensen.
Version: 1.0
Author: Doede Jaarsma communicatie
Author URI: https://doedejaarsma.nl
License: GPLv3
textdomain: ppmm
*/

defined('ABSPATH') || exit;

if (!defined('PP_VA_FILE')) {
	define('PP_VA_FILE', __FILE__);
}

if (!defined('PP_VA_DIR')) {
	define('PP_VA_DIR', plugin_dir_path(PP_VA_FILE));
}

include_once PP_VA_DIR . 'vendor/autoload.php';

if (!class_exists('PropertyVacatures')) {
	include_once PP_VA_DIR . 'src/PropertyVacatures.php';
}

new \PropertyPeople\PropertyVacatures();
