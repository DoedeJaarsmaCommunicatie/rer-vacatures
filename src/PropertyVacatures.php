<?php

namespace PropertyPeople;

use PropertyPeople\Includes\MultiSync;
use PropertyPeople\Includes\VacatureMeta;
use PropertyPeople\Includes\VacaturePostRoute;
use PropertyPeople\Vendor\Taxonomies\Branche;
use PropertyPeople\Vendor\Taxonomies\Vakgebied;
use PropertyPeople\Vendor\Taxonomies\Functie;
use PropertyPeople\Vendor\Taxonomies\Opdrachtgevers;
use PropertyPeople\Vendor\Taxonomies\Regio;
use PropertyPeople\Vendor\Taxonomies\Organisatie;
use PropertyPeople\Vendor\Types\Contactpersoon;
use PropertyPeople\Vendor\Types\Vacature;

class PropertyVacatures
{
	public $version = '1.0.0';
	
	protected static $_instance = null;
	
	public static function instance() {
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}
	
	/**
	 * When WP has finished loading, fire this hook.
	 *
	 * @return void
	 */
	public function on_plugins_loaded() {
		do_action( 'pp_vacatures/loaded' );
	}
	
	public function init_hooks() {
		add_action('init', [$this, 'init'], 0, 0);
	}
	
	private function define_constants() {
		$upload_dir = wp_upload_dir(null, false);
		
		$this->define('PP_VA_ABSPATH', trailingslashit(dirname(PP_VA_FILE)));
		$this->define('PP_VA_SRCPATH', trailingslashit(dirname(PP_VA_FILE)));
		$this->define('PP_VA_BASENAME', plugin_basename(PP_VA_FILE));
		$this->define('PP_VA_VERSION', $this->version);
		$this->define('PP_VACATURES_VERSION', $this->version);
		$this->define('MM_VACATURES_VERSION', $this->version);
		$this->define('PP_VA_DELIMITER', '|');
		$this->define('PP_VA_LOG_DIR', $upload_dir['basedir'] . '/ppva-logs/');
	}
	
	public function init()
	{
		$this->init_database();
		$this->init_vacature();
		$this->init_contact();
		// $this->init_sync();
	}
	
	/**
	 * Include files.
	 *
	 * @return void
	 */
	private function includes()
	{
	}
	
	private function init_database() {
		new PropertyDatabase();
	}
	
	private function init_vacature()
	{
		new Vacature();
		new Regio();
		new Vakgebied();
		new Functie();
		new Organisatie();
		new Branche();
		
		new VacaturePostRoute();
		
		if (is_admin()) {
			new PropertyAdminPage();
		}
	}
	
	private function init_contact() {
		new Contactpersoon();
		new Opdrachtgevers();
	}
	
	private function init_sync() {
		new MultiSync();
		new VacatureMeta();
	}
	
	/**
	 * Set a definition if not already set.
	 *
	 * @param String $name  The definition name.s
	 * @param String $value The value to be defined
	 */
	protected function define($name, $value) {
		if (!defined($name)) {
			define($name, $value);
		}
	}
}
