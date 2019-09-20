<?php
namespace PropertyPeople\Includes;

use PropertyPeople\Includes\Models\Vacature;

class VacatureGetRoute {
    public function __construct() {
        add_action('admin_post_get_vacature', [$this, 'getVacature']);
    }
    
    public function getVacature() {
        isset($_REQUEST['solicitor_id']) || exit(1);
        
        $id = $_REQUEST['solicitor_id'];
        
        $v = new Vacature( (int) $id);
        wp_send_json((array) $v);
    }
}
