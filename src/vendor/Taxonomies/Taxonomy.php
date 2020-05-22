<?php
namespace PropertyPeople\Vendor\Taxonomies;

abstract class Taxonomy implements Tax
{
	public function __construct()
	{
		$this->action();
	}

	public function action()
	{
		\add_action('init', [ $this, 'register' ], 10);
	}

	public function register()
	{
		\register_taxonomy($this->slug(), $this->supports(), $this->args());
	}

	public function labels(): array
	{
		return [
			'name'                       => _x($this->namePlural(), $this->name() . ' General Name', $this->textDomain()),
			'singular_name'              => _x($this->name(), $this->name() . ' Singular Name', $this->textDomain()),
			'menu_name'                  => __($this->name(), $this->textDomain()),
			'all_items'                  => __('All Items', $this->textDomain()),
			'parent_item'                => __('Parent Item', $this->textDomain()),
			'parent_item_colon'          => __('Parent Item:', $this->textDomain()),
			'new_item_name'              => __('New Item Name', $this->textDomain()),
			'add_new_item'               => __('Add New Item', $this->textDomain()),
			'edit_item'                  => __('Edit Item', $this->textDomain()),
			'update_item'                => __('Update Item', $this->textDomain()),
			'view_item'                  => __('View Item', $this->textDomain()),
			'separate_items_with_commas' => __('Separate items with commas', $this->textDomain()),
			'add_or_remove_items'        => __('Add or remove items', $this->textDomain()),
			'choose_from_most_used'      => __('Choose from the most used', $this->textDomain()),
			'popular_items'              => __('Popular Items', $this->textDomain()),
			'search_items'               => __('Search Items', $this->textDomain()),
			'not_found'                  => __('Not Found', $this->textDomain()),
			'no_terms'                   => __('No items', $this->textDomain()),
			'items_list'                 => __('Items list', $this->textDomain()),
			'items_list_navigation'      => __('Items list navigation', $this->textDomain()),
		];
	}

	final public function args(): array
	{
		return [
			'labels'            => $this->labels(),
			'hierarchical'      => $this->hierarchical(),
			'public'            => $this->public(),
			'show_ui'           => $this->showUi(),
			'show_admin_column' => $this->showAdminColumn(),
			'show_in_nav_menus' => $this->showInNavMenus(),
			'show_tagcloud'     => $this->showTagcloud(),
			'show_in_rest'      => $this->showInRest(),
		];
	}

	public function public()
	{
		return true;
	}

	public function hierarchical()
	{
		return true;
	}

	public function showUi()
	{
		return true;
	}

	public function showAdminColumn()
	{
		return true;
	}

	public function showInNavMenus()
	{
		return true;
	}

	public function showTagcloud()
	{
		return true;
	}

	public function showInRest()
	{
		return true;
	}
}
