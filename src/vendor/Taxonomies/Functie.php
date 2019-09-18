<?php
namespace PropertyPeople\Vendor\Taxonomies;

class Functie extends Taxonomy
{
	public function slug()
	{
		return 'functie';
	}
	
	public function name()
	{
		return 'Functie';
	}
	
	public function namePlural()
	{
		return 'Functies';
	}
	
	public function supports()
	{
		return [
			'vacature'
		];
	}
	
	public function textDomain()
	{
		return 'ppmm';
	}
	
}
