<?php
namespace PropertyPeople\VacancyList;

use PropertyPeople\Includes\Models\Vacature;
use PropertyPeople\PropertyDatabase;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class VacancyTableList
 * @package PropertyPeople\VacancyList
 *
 * FIXME: Add actions to remove solicitors
 * @see https://premium.wpmudev.org/blog/wordpress-admin-tables/
 */
class VacancyTableList extends \WP_List_Table {

    public function prepare_items()
    {
        $search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

        $this->handle_table_actions();

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $table_data = $this->table_data();

        $this->_column_headers = [$columns, $hidden, $sortable];

        $vacatures_per_page = $this->get_items_per_page( 'vacatures_per_page' );
        $table_page = $this->get_pagenum();

        if ($search_key) {
            $table_data = $this->filter_table_data( $this, $search_key);
        }

        $this->items = array_slice($table_data, ( $table_page - 1) * $vacatures_per_page, $vacatures_per_page );

        $total_vacatures = count($table_data);
        $this->set_pagination_args(
            [
                'total_items'   => $total_vacatures,
                'per_page'      => $vacatures_per_page,
                'total_pages'   => ceil($total_vacatures/$vacatures_per_page)
            ]
        );
    }

    public function get_columns(): array
    {
        return [
            'cb'            => '<input type="checkbox" />',
            'id'            => __('id', 'ppmm'),
            'naam'          => 'Naam',
            'email'         => 'Email',
            'mobiel'        => 'Mobiel',
            'cv'            => 'CV',
            'vacancy'       => 'Vacature',
            'status'        => 'Status',
            'created_at'    => 'Datum',
        ];
    }

    public function get_hidden_columns(): array
    {
        return [];
    }

    public function get_sortable_columns(): array
    {
        return [
            'vacancy'       => ['vacancy', false],
            'created_at'    => ['created_at', false]
        ];
    }

    private function table_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . PropertyDatabase::TABLE_NAME;
        $orderby = isset($_GET['orderby']) ? esc_sql($_GET['orderby']) : 'created_at';
        $order = isset($_GET['order']) ? esc_sql($_GET['order']) : 'DESC';

        $sql = "
		SELECT id
		FROM {$table_name}
		ORDER BY {$orderby} {$order}
		";

        $rows = $wpdb->get_results($sql, ARRAY_A);

        $vacatures = [];

        foreach ($rows as $row) {
            $vacatures []= new Vacature((int) $row['id']);
        }

        return $vacatures;
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
            case 'vacancy':
                $link = get_permalink($item->post->ID);
                return "<a href='$link'>{$item->post->post_title}</a>";
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
            'bulk-delete'   => __('Delete')
        ];
    }

    public function handle_table_actions()
    {
        $this->handle_bulk_delete();
        $this->handle_single_delete();
    }

    protected function column_naam($item)
    {
        $actions['show_solicitor']   = $this->show_single_solicitor($item);
        $actions['delete_solicitor'] = $this->delete_single_solicitor($item);

        $row_value = '<strong>' . $item->getName() . '</strong>';
        return $row_value . $this->row_actions($actions);
    }

    private function delete_single_solicitor($item)
    {
        $current_page_url = admin_url('admin.php?page=vacancy-overview');

        $query_args_delete = [
            'page'          => wp_unslash($_REQUEST['page']),
            'action'        => 'delete_solicitation',
            'solicitor_id'  => $item->id,
            '_wpnonce'      => wp_create_nonce('delete_solicitation')
        ];

        $delete_solicitation_link = esc_url(add_query_arg($query_args_delete, $current_page_url));

        return sprintf(
            '<a href="%s">%s</a>',
            $delete_solicitation_link,
            __('Delete')
        );
    }

    private function show_single_solicitor($item)
    {
        $current_page_url = admin_url('admin.php?page=vacancy-overview');

        $query_args = [
            'page'          => wp_unslash('single_solicitor'),
            'action'        => 'show_solicitation',
            'solicitor_id'  => $item->id,
        ];

        $show_solicitation_link = esc_url(add_query_arg($query_args, $current_page_url));

        return sprintf(
            '<a href="%s">%s</a>',
            $show_solicitation_link,
            __('Show')
        );
    }

    private function handle_single_delete(): void
    {
        if (isset($_REQUEST['action'], $_REQUEST['solicitor_id']) && $_REQUEST[ 'action' ] === 'delete_solicitation') {
            $nonce = wp_unslash( $_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'delete_solicitation')) {
                $this->invalid_nonce_redirect();
            }

            $vacature = new Vacature((int) $_REQUEST['solicitor_id']);
            if ($vacature->delete()) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'Sollicitatie verwijderd', 'ppmm' ); ?></p>
                </div>
                <?php
            }
        }
    }

    /*
     * FIXME: handle bulk deletes
     */
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
                $v = new Vacature((int) $id);
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

    /*
     * FIXME: To work with model object.
     */
    public function filter_table_data($table_data, $search_key)
    {
        $filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
            foreach( $row as $row_val ) {
                if( stripos( $row_val, $search_key ) !== false ) {
                    return true;
                }
            }
        } ) );
        return $filtered_table_data;
    }

}
