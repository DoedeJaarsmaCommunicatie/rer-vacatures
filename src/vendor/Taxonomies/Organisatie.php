<?php
namespace PropertyPeople\Vendor\Taxonomies;

class Organisatie extends Taxonomy
{
	public function slug(  )
	{
		return 'organisatie';
	}
	
	public function name()
	{
		return 'Organisatie';
	}
	
	public function namePlural()
	{
		return 'Organisaties';
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
