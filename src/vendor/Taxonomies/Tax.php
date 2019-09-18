<?php
namespace PropertyPeople\Vendor\Taxonomies;

interface Tax
{
    public function args();
    public function labels();
    
    public function slug();
    public function name();
    public function namePlural();
    
    public function supports();
    public function hierarchical();
    public function public();
    public function showUi();
    
    public function showAdminColumn();
    public function showInNavMenus();
    public function showTagcloud();
    public function showInRest();
    
    public function textDomain();
}
