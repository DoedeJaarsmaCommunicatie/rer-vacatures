<?php
namespace PropertyPeople\VacancyList;

use PropertyPeople\Includes\Models\OpenVacature;
use PropertyPeople\PropertyDatabase;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class SollicitorTableList
 *
 * Toont de open sollicitaties.
 *
 * @package PropertyPeople\VacancyList
 */
class SollicitorTableList extends \WP_List_Table
{
    public function prepare_items()
    {
        $seach_key = isset($_REQUEST['s']) ? wp_unslash($_REQUEST['s']) : '';
        $this->handle_table_actions();
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        
        $table_data = $this->table_data();
        
        $this->_column_headers = [$columns, $hidden, $sortable];
        
        $sollicitaties_per_page = $this->get_items_per_page('sollicitaties_per_page');
        $table_page = $this->get_pagenum();
        
        $this->items = array_slice($table_data, ($table_page -1) * $sollicitaties_per_page, $sollicitaties_per_page);
        
        $total_sollicitaties = count($table_data);
        $this->set_pagination_args(
            [
                'total_items'   => $total_sollicitaties,
                'per_page'      => $sollicitaties_per_page,
                'total_pages'   => ceil($total_sollicitaties/$sollicitaties_per_page)
            ]
        );
    }
    
    public function get_columns(): array
    {
        return [
            'cb'            => '<input type="checkbox />',
            'id'            => __('id', 'ppmm'),
            'naam'          => 'Naam',
            'email'         => 'Email',
            'mobiel'        => 'Mobiel',
            'cv'            => 'CV',
            'functie'       => 'Functie',
            'motivatie'     => 'Motivatie',
            'created_at'    => 'Datum',
            'status'        => 'Status',
        ];
    }
    
    public function get_hidden_columns(): array
    {
        return [];
    }
    
    public function get_sortable_columns(): array
    {
        return [
            'created_at'    => ['created_at', false]
        ];
    }
    
    private function table_data()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . PropertyDatabase::OPEN_TABLE_NAME;
        $orderby = isset($_GET['orderby']) ? esc_sql($_GET['orderby']) : 'created_at';
        $order = isset($_GET['order']) ? esc_sql($_GET['order']) : 'DESC';
        
        $sql = "
        SELECT id
        FROM {$table_name}
        ORDER BY {$orderby} {$order}
        ";
        
        $rows = $wpdb->get_results($sql, ARRAY_A);
        
        $sollicitaties = [];
        
        foreach ($rows as $row) {
            $sollicitaties []= new OpenVacature((int) $row['id']);
        }
        
        return $sollicitaties;
    }
    
    protected function column_default( $item, $column_name )
    {
        switch ($column_name) {
            case 'id':
                return $item->id;
                break;
            case 'naam':
                return $item->getName();
                break;
            case 'mobiel':
                return $item->phone;
                break;
            case 'cv':
                if (empty($item->file)) {
                    return __('Niet geupload', 'ppmm');
                }
                return "<a href='{$item->file}' download>Download</a>";
                break;
            case 'motivatie':
                return $item->motivation;
                break;
            case 'functie':
                return $item->function;
                break;
            default:
                return ((array) $item)[$column_name];
                break;
        }
    }
    
    protected function column_cb( $item )
    {
        return sprintf(
            '<label class="screen-reader-text" for="solicitor_' . $item->id . '">' . sprintf( __( 'Select %s' ), $item->getName() ) . '</label>'
            . "<input type='checkbox' name='solicitations[]' id='solicitor_{$item->id}' value='{$item->id}' />"
        );
    }
    
    public function no_items()
    {
        return _e('Geen sollicitaties gevonden', 'ppmm');
    }
    
    public function get_bulk_actions()
    {
        return [
            'bulk-delete'   => __('Delete'),
            'bulk-toggle-status' => __('Toggle Status')
        ];
    }
    
    public function handle_table_actions()
    {
        $this->handle_bulk_delete();
        $this->handle_bulk_toggle();
    }
    
    private function handle_bulk_toggle()
    {
        if(
            ( isset($_REQUEST['action']) && $_REQUEST['action'] === 'bulk-toggle-status' ) ||
            ( isset($_REQUEST['action2']) && $_REQUEST['action2'] === 'bulk-toggle-status')
        ) {
            $nonce = wp_unslash($_REQUEST['_wpnonce']);
            
            if (!wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural'])) {
                $this->invalid_nonce_redirect();
                return;
            }
            
            
            foreach ( $_REQUEST['solicitations'] as $id ) {
                $v = new OpenVacature((int) $id);
                switch ($v->status) {
                    case 'opgepakt':
                        $v->toggleStatus('nieuw');
                        break;
                    default:
                        $v->toggleStatus();
                        break;
                }
            }
            
        }
    }
    
    private function handle_bulk_delete()
    {
        if (
            ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST['action'] === 'bulk-delete' ) ||
            ( isset( $_REQUEST[ 'action2' ] ) && $_REQUEST[ 'action2' ] === 'bulk-delete' )
        ) {
            $nonce = wp_unslash($_REQUEST['_wpnonce']);
            
            if (!wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural'])) {
                $this->invalid_nonce_redirect();
                return;
            }
            
            foreach ($_REQUEST['solicitations'] as $id) {
                $v = new OpenVacature((int) $id);
                $v->delete();
            }
            
            $this->graceful_exit();
        }
    }
    
    final protected function invalid_nonce_redirect()
    {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Er is iets fout gegaan, probeer het opnieuw', 'ppmm' ); ?></p>
        </div>
        <?php
    }
    
    final protected function graceful_exit()
    {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Sollicitatie(s) verwijderd', 'ppmm' ); ?></p>
        </div>
        <?php
    }
}
