<?php
namespace PropertyPeople\Vendor\Types;

use PropertyPeople\Vendor\CPT;

class Contactpersoon extends CPT
{
	public function labels() : array
	{
		return [];
	}
	
	public function args() : array
	{
		return [
			'menu_icon'     => 'dashicons-admin-users'
		];
	}
	
	public function slug() : string
	{
		return 'contactpersoon';
	}
	
	public function namePlural() : string
	{
		return 'Contactpersonen';
	}
	
	public function name() : string
	{
		return 'Contactpersoon';
	}
	
	public function description() : string
	{
		return __('Contactpersonen opdrachtgevers', 'ppmm');
	}
	
	public function label() : string
	{
		return __('Contactpersonen', 'ppmm');
	}
	
	public function textDomain() : string
	{
		return 'ppmm';
	}
	
	public function supports()
	{
		return [
			'title',
			'thumbnail',
			'editor'
		];
	}
	
	public function taxonomies() : array
	{
		return [];
	}
}
