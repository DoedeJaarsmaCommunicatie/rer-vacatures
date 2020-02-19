<?php
namespace PropertyPeople;

use PropertyPeople\Includes\Models\OpenVacature;
use PropertyPeople\Includes\Models\Vacature;
use PropertyPeople\VacancyList\SollicitorTableList;
use PropertyPeople\VacancyList\VacancyTableList;

class PropertyAdminPage
{
	public function __construct()
	{
		add_action('admin_menu', [$this, 'vacancy_overview']);
	}

	public function vacancy_overview()
	{
		$page_hook = add_menu_page(
			__('Sollicitanten', 'ppmm'),
			__('Sollicitanten', 'ppmm'),
			'manage_options',
			'vacancy-overview',
			[$this, 'vacancy_list']
		);

		add_submenu_page(
			'vacancy-overview',
			__('Single Solicitor', 'ppmm'),
			'',
			'manage_options',
			'single_solicitor',
			[$this, 'single_vacancy']
		);

		add_submenu_page(
			'vacancy-overview',
			__('Open sollicitaties', 'ppmm'),
			__('Open sollicitaties', 'ppmm'),
			'manage_options',
			'sollicitor-overview',
			[$this, 'open_soll_list']
		);

		add_action("load-{$page_hook}", [$this, 'add_page_options']);
	}

	public function add_page_options()
	{
		$args = [
			'label'     => __('Vacatures per pagina', 'ppmm'),
			'default'   => 15,
			'options'   => 'vacatures_per_page'
		];

		add_screen_option('per_page', $args);
	}

	public function vacancy_list()
	{
		$vacancyListTable = new VacancyTableList();
		$vacancyListTable->prepare_items();
		?>
		<div class="wrap">
			<h2><?php _e('Vacature Overzicht', 'ppmm'); ?></h2>
			<form method="get" id="ppmm-vacancy-body">
				<input type="hidden" name="page" value="<?php print $_REQUEST['page']; ?>" />
				<?php
				//                    $vacancyListTable->search_box( __('Zoeken', 'ppmm'), 'ppmm-vacancy-find');
				$vacancyListTable->display();
				?>
			</form>
		</div>
		<?php
	}

	public function single_vacancy(): void
	{

	    wp_enqueue_Script('single-vacature-app', PP_VA_URL . '/assets/dist/app.react.js', [], false, true);
		include_once PP_VA_DIR . '/src/views/single-sollicitatie.php';
	}

	public function open_soll_list()
	{
		$sollListTable = new SollicitorTableList();
		$sollListTable->prepare_items();
		?>
		<div class="wrap">
			<h2><?php _e('Sollicitatie Overzicht', 'ppmm'); ?></h2>
			<form method="get" id="ppmm-vacancy-body">
				<input type="hidden" name="page" value="<?php print $_REQUEST['page']; ?>" />
				<?php
				$sollListTable->display();
				?>
			</form>
		</div>
		<?php
	}
}


