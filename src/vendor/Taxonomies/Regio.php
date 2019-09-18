<?php
namespace PropertyPeople\Vendor\Taxonomies;

class Regio extends Taxonomy
{
	public function slug()
	{
		return 'regio';
	}
	
	public function name()
	{
		return 'Regio';
	}
	
	public function namePlural()
	{
		return 'Regio\'s';
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
