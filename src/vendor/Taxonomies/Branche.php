<?php
namespace PropertyPeople\Vendor\Taxonomies;


class Branche extends Taxonomy
{
	public function namePlural()
	{
		return 'Branches';
	}

	public function textDomain()
	{
		return 'ppmm';
	}

	public function name()
	{
		return 'Branche';
	}

	public function slug()
	{
		return 'branche';
	}

	public function supports()
	{
		return [
			'vacature'
		];
	}
}
