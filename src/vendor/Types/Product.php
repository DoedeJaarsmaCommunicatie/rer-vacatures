<?php
namespace PropertyPeople\Vendor\Types;

use PropertyPeople\Vendor\CPT;

class Product extends CPT
{
    public function labels() : array
    {
        return [];
    }
    
    public function args() : array
    {
        return [
            'menu_icon'     => 'dashicons-awards'
        ];
    }
    
    public function slug() : string
    {
        return 'product';
    }
    
    public function namePlural() : string
    {
        return 'Producten';
    }
    
    public function name() : string
    {
        return 'Product';
    }
    
    public function description() : string
    {
        return __('Herkenbaar producten', 'herkenbaar');
    }
    
    public function label() : string
    {
        return __('Producten', 'herkenbaar');
    }
    
    public function textDomain() : string
    {
        return 'herkenbaar';
    }
}
