<?php
namespace PropertyPeople;

class PropertyDatabase
{
	/**
	 * @var \wpdb
	 */
	private $wpdb;
	
	public $table_name;
	
	public const TABLE_NAME = 'vacancy';
	
	public const OPEN_TABLE_NAME = 'open_soll';
	
	public const VERSION = '1.2.1';
	
	public function __construct()
	{
		$this->wpdb = $GLOBALS['wpdb'];
		$this->table_name = $this->wpdb->prefix . self::TABLE_NAME;
		
		if (!get_option('vacancy_DB_VERSION')) {
			$this->createInitialTable();
			$this->createOpenTable();
			$this->addEmailColumnOpenTable();
		} else {
			$this->runUpdateTable();
		}
	}
	
	public function createInitialTable(): void
	{
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($this->initialDBSQL());
		
		add_option('vacancy_DB_VERSION', '1.0.0');
	}
	
	public function runUpdateTable(): void
	{
		if (version_compare(self::VERSION, get_option('vacancy_DB_VERSION'), '>')) {
			$this->createOpenTable();
		}
		if (version_compare(self::VERSION, get_option('vacancy_DB_VERSION'), '>')) {
			$this->addEmailColumnOpenTable();
		}
		if (version_compare(self::VERSION, get_option('vacancy_DB_VERSION'), '>')) {
		    $this->addStatusColumn();
        }
	}
	
	private function createOpenTable(): void
	{
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($this->initialOpenDBSQL());
		
		update_option('vacancy_DB_VERSION', '1.1.0');
	}
	
	private function addEmailColumnOpenTable(): void
	{
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($this->addEmailToOpenDBSQL());
		
		update_option('vacancy_DB_VERSION', '1.1.1');
	}
	
	private function addStatusColumn(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($this->addStatusColumnSQL());
        dbDelta($this->addStatusOpencolumnSQL());
        
        update_option('vacancy_DB_VERSION', '1.2.1');
    }
	
    private function addStatusColumnSQL(): string
    {
        $charset = $this->wpdb->get_charset_collate();
        $table_name = $this->wpdb->prefix . self::TABLE_NAME;
        
        $sql = "CREATE TABLE ${table_name} (
    	id mediumint(9) NOT NULL AUTO_INCREMENT,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        voornaam tinytext NOT NULL,
        naam tinytext NOT NULL,
        email tinytext NOT NULL,
        mobiel tinytext NOT NULL,
        cv tinytext NULL,
        motivatie text NULL,
        origin mediumint(9) UNSIGNED DEFAULT 1 NOT NULL,
        vacancy bigint(20) UNSIGNED NOT NULL,
        status varchar(45) DEFAULT 'nieuw' NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (vacancy) REFERENCES {$this->wpdb->posts} (ID)
) $charset";
        
        return $sql;
    }
    
    private function addStatusOpencolumnSQL(): string
    {
        $charset = $this->wpdb->get_charset_collate();
        $table_name = $this->wpdb->prefix . self::OPEN_TABLE_NAME;
    
        $sql = "CREATE TABLE ${table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    voornaam tinytext NOT NULL,
    naam tinytext NOT NULL,
    email tinytext NOT NULL,
    mobiel tinytext NOT NULL,
    functie tinytext NULL,
    cv tinytext NULL,
    motivatie text NULL,
    status varchar(45) DEFAULT 'nieuw' NULL,
    origin mediumint(9) UNSIGNED DEFAULT 1 NOT NULL,
    PRIMARY KEY (id)
) $charset";
    
        return $sql;
    }
    
	private function addEmailToOpenDBSQL(): string
	{
		$charset = $this->wpdb->get_charset_collate();
		$table_name = $this->wpdb->prefix .  self::OPEN_TABLE_NAME;
        
        $sql = "CREATE TABLE ${table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    voornaam tinytext NOT NULL,
    naam tinytext NOT NULL,
    email tinytext NOT NULL,
    mobiel tinytext NOT NULL,
    functie tinytext NULL,
    cv tinytext NULL,
    motivatie text NULL,
    origin mediumint(9) UNSIGNED DEFAULT 1 NOT NULL,
    PRIMARY KEY (id)
) $charset";
		
		return $sql;
	}
	
	private function initialOpenDBSQL(): string
	{
		$charset = $this->wpdb->get_charset_collate();
		$table_name = $this->wpdb->prefix .  self::OPEN_TABLE_NAME;
		
		$sql = "CREATE TABLE ${table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    voornaam tinytext NOT NULL,
    naam tinytext NOT NULL,
    mobiel tinytext NOT NULL,
    functie tinytext NULL,
    cv tinytext NULL,
    motivatie text NULL,
    origin mediumint(9) UNSIGNED DEFAULT 1 NOT NULL,
    PRIMARY KEY (id)
) $charset";
		
		return $sql;
	}
	
	private function initialDBSQL(): string
	{
		$charset = $this->wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE {$this->table_name} (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	voornaam tinytext NOT NULL,
	naam tinytext NOT NULL,
	email tinytext NOT NULL,
	mobiel tinytext NOT NULL,
	cv tinytext NULL,
	motivatie text NULL,
	origin mediumint(9) UNSIGNED DEFAULT 1 NOT NULL,
	vacancy bigint(20) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (vacancy) REFERENCES {$this->wpdb->posts} (ID)
) $charset";
		
		return $sql;
	}
}
