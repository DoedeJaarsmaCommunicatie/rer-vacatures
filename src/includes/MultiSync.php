<?php
namespace PropertyPeople\Includes;

class MultiSync
{
	public function add_to_bulk($bulk_array)
	{
		$sites = get_sites(
			[
				'site__not_in'      => \get_current_blog_id(),
				'number'            => 20
			]
		);
		
		if ($sites) {
			foreach ($sites as $site) {
				$bulk_array["copy_to_$site->blog_id"] = __("Copy to $site->blogname", 'ppmm');
			}
		}
		
		return $bulk_array;
	}
	
	public function bulk_copy_handler($redirect, $doaction, $object_ids)
	{
		if (!strpos($doaction, 'copy_to_') === 0)
			return;
		
		$redirect = remove_query_arg(
			[
				'ppmm_posts_moved',
				'ppmm_blog_id'
			],
			$redirect
		);
		
		$blog_id = str_replace( 'copy_to_', '', $doaction );
		
		foreach ($object_ids as $id) {
			$post = get_post($id, ARRAY_A);
			
			$current_post_id = $post['ID'];
			$current_blog_id = get_current_blog_id();
			
			$post_terms = wp_get_object_terms($id, 'category', array('fields' => 'slugs'));
			
			$data = get_post_custom($id);
			
			$post['ID'] = '';
			
			switch_to_blog($blog_id);
			
			$inserted_post_id = wp_insert_post($post);
			
			wp_set_object_terms($inserted_post_id, $post_terms, 'category', false);
			
			foreach ($data as $key => $values) {
				if ($key == '_wp_old_slug') {
					continue;
				}
				
				foreach ( $values as $value ) {
					add_post_meta($inserted_post_id, $key, $value);
				}
			}
			update_post_meta($inserted_post_id, 'vacature_copy_blog_id', $current_blog_id);
			update_post_meta($inserted_post_id, 'vacature_copy_vacature_id', $current_post_id);
			restore_current_blog();
			
			update_post_meta($current_post_id, 'vacature_copy_blog_id', $blog_id);
			update_post_meta($current_post_id, 'vacature_copy_vacature_id', $inserted_post_id);
		}
		$redirect = add_query_arg(
			[
				'ppmm_posts_moved'  => count($object_ids),
				'ppmm_blog_id'      => $blog_id
			],
			$redirect
		);
		
		return $redirect;
	}
	
	public function syncNotifications()
	{
		if (empty($_REQUEST['ppmm_posts_moved']))
			return;
			
		$blog = get_blog_details($_REQUEST['ppmm_blog_id']);
		
		printf('<div id="message" class="updated notice is-dismissible"><p>' .
		       _n('%d post has been moved to "%s"', '$d posts have been moved to "%s"', (int) $_REQUEST['ppmm_posts_moved'] )
		       .'</p></div>', (int) $_REQUEST['ppmm_posts_moved'], $blog->blogname);
	}
	
	public function __construct() {
		$this->init();
	}
	
	public function init()
	{
		$this->add_filters();
	}
	
	final private function add_filters()
	{
		$this->filter('bulk_actions-edit-vacature', [ $this, 'add_to_bulk']);
		$this->filter('handle_bulk_actions-edit-vacature', [$this, 'bulk_copy_handler'], 10, 3);
	}
	
	final private function add_actions()
	{
		$this->action('admin_notices', [$this, 'syncNotifications']);
	}
	
	final private function filter($name, callable $hook, $priority = 10, $accepted_args = 1)
	{
		\add_filter($name, $hook, $priority, $accepted_args);
	}
	
	final private function action($name, callable $hook, $priority = 10, $accepted_args = 1)
	{
		\add_action($name, $hook, $priority, $accepted_args);
	}
}
