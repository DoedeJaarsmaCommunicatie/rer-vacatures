<?php /** @noinspection ALL */

namespace PropertyPeople\Vendor;

abstract class CPT implements Type
{
    public function __construct()
    {
        $this->action();
    }
    
    public final function action() : void
    {
        \add_action('init', [ $this, 'register' ], 10);
    }
    
    public final function register() : void
    {
        \register_post_type($this->slug(), array_merge($this->defaultArgs(), $this->args()));
    }
    
    public final function defaultArgs() : array
    {
        return [
            'label'                 => $this->label(),
            'description'           => $this->description(),
            'labels'                => array_merge($this->defaultLabels(), $this->labels()),
            'supports'              => $this->supports(),
            'taxonomies'            => $this->taxonomies(),
            'hierarchical'          => $this->hierarchical(),
            'public'                => $this->public(),
            'show_ui'               => $this->showInUi(),
            'show_in_menu'          => $this->showInMenu(),
            'menu_position'         => $this->menuPosition(),
            'show_in_admin_bar'     => $this->showInAdmin(),
            'show_in_nav_menus'     => $this->showInMenu(),
            'can_export'            => $this->export(),
            'has_archive'           => $this->archive(),
            'exclude_from_search'   => $this->exclude(),
            'publicly_queryable'    => $this->publicQuery(),
            'capability_type'       => $this->capabilityType(),
        ];
    }
    
    public final function defaultLabels(): array
    {
        return [
            'name'                  => _x($this->name(), $this->name() . ' General Name', $this->textDomain()),
            'singular_name'         => _x($this->name(), $this->name() . ' Singular Name', $this->textDomain()),
            'menu_name'             => __($this->namePlural(), $this->textDomain()),
            'name_admin_bar'        => __($this->name(), $this->textDomain()),
            'archives'              => __($this->name() . ' Archives', $this->textDomain()),
            'attributes'            => __($this->name() . ' Attributes', $this->textDomain()),
            'parent_item_colon'     => __('Parent Item:', $this->textDomain()),
            'all_items'             => __('All Items', $this->textDomain()),
            'add_new_item'          => __('Add New Item', $this->textDomain()),
            'add_new'               => __('Add New', $this->textDomain()),
            'new_item'              => __('New Item', $this->textDomain()),
            'edit_item'             => __('Edit Item', $this->textDomain()),
            'update_item'           => __('Update Item', $this->textDomain()),
            'view_item'             => __('View Item', $this->textDomain()),
            'view_items'            => __('View Items', $this->textDomain()),
            'search_items'          => __('Search Item', $this->textDomain()),
            'not_found'             => __('Not found', $this->textDomain()),
            'not_found_in_trash'    => __('Not found in Trash', $this->textDomain()),
            'featured_image'        => __('Featured Image', $this->textDomain()),
            'set_featured_image'    => __('Set featured image', $this->textDomain()),
            'remove_featured_image' => __('Remove featured image', $this->textDomain()),
            'use_featured_image'    => __('Use as featured image', $this->textDomain()),
            'insert_into_item'      => __( 'Insert into item', $this->textDomain()),
            'uploaded_to_this_item' => __('Uploaded to this item', $this->textDomain()),
            'items_list'            => __('Items list', $this->textDomain()),
            'items_list_navigation' => __('Items list navigation', $this->textDomain()),
            'filter_items_list'     => __('Filter items list', $this->textDomain()),
        ];
    }
    
    public function capabilityType(): string
    {
    	return 'page';
    }
    
    public function publicQuery(): bool
    {
    	return true;
    }
    
    public function exclude(): bool
    {
    	return false;
    }
	
	public function archive()
	{
		return true;
    }
    
    public function export(): bool
    {
    	return true;
    }
	
	public function showInMenu(): bool
	{
		return true;
    }
	
	public function showInAdmin(): bool {
		return true;
    }
	
	public function menuPosition(): int {
		return 5;
    }
    
	public function addToNav() : bool
    {
    	return true;
    }
	
	public function showInUi(): bool {
		return true;
    }
	
	public function hierarchical(): bool {
		return false;
    }
	
	public function taxonomies(): array {
		return [
			'category',
			'post_tag',
		];
    }
    
    public function public() : bool {
    	return true;
    }
	
	public function supports() {
		return false;
    }
}
