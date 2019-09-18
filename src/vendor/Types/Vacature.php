<?php
namespace PropertyPeople\Vendor\Types;

use PropertyPeople\Vendor\CPT;

class Vacature extends CPT
{
	public function labels() : array
	{
		return [];
	}
	
	public function args() : array
	{
		return [
			'menu_icon'     => 'dashicons-portfolio'
		];
	}
	
	public function slug() : string
	{
		return 'vacature';
	}
	
	public function namePlural() : string
	{
		return 'Vacatures';
	}
	
	public function name() : string
	{
		return 'Vacature';
	}
	
	public function description() : string
	{
		return __('Vacatures', 'ppmm');
	}
	
	public function label() : string
	{
		return __('Vacatures', 'ppmm');
	}
	
	public function textDomain() : string
	{
		return 'ppmm';
	
	}

	public function archive() {
		return 'vacatures';
	}
	
	public function supports()
	{
		return [
			'title',
			'editor',
			'thumbnail'
		];
	}
	
	
	public function taxonomies() : array
	{
		return [
			'regio',
			'functie',
			'organisatie',
			'vakgebied',
			'branche'
		];
	}
}
