<?php
/*
Plugin Name: Vacatures
Description: Deze plugin voegt de nodige types en functies toe aan RealEstateRecruiters.
Version: 1.1
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

defined('PP_VA_URL') || define('PP_VA_URL', plugin_dir_url(PP_VA_FILE));

include_once PP_VA_DIR . 'vendor/autoload.php';

$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/DoedeJaarsmaCommunicatie/rer-vacatures',
    __FILE__,
    'vacatures'
);

if (!class_exists('PropertyVacatures')) {
	include_once PP_VA_DIR . 'src/PropertyVacatures.php';
}

new \PropertyPeople\PropertyVacatures();
