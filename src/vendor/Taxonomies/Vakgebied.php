<?php
namespace PropertyPeople\Vendor\Taxonomies;

class Vakgebied extends Taxonomy
{
	public function namePlural()
	{
		return 'Vakgebieden';
	}
	
	public function textDomain()
	{
		return 'ppmm';
	}
	
	public function name()
	{
		return 'Vakgebied';
	}
	
	public function slug()
	{
		return 'vakgebied';
	}
	
	public function supports()
	{
		return [
			'vacature'
		];
	}
}
