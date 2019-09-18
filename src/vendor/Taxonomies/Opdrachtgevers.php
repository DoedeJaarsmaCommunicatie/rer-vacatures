<?php
namespace PropertyPeople\Vendor\Taxonomies;

class Opdrachtgevers extends Taxonomy
{
	public function slug()
	{
		return 'opdrachtgever';
	}
	
	public function name()
	{
		return 'Opdrachtgever';
	}
	
	public function namePlural()
	{
		return 'Opdrachtgevers';
	}
	
	public function supports()
	{
		return [
		];
	}
	
	public function textDomain()
	{
		return 'ppmm';
	}
	
}
