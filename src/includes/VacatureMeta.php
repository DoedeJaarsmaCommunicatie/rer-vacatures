<?php
namespace PropertyPeople\Includes;

class VacatureMeta
{
	private $current_blog_id;
	private $copy_blog_id;
	
	private $current_post_id;
	private $copy_post_id;
	
	private $current_blog;
	private $copy_blog;
	
	private $sites;
	
	public function __construct() {
		$this->fetchData();
		add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
		add_action('save_post_vacature', [$this, 'copyVacancy']);
	}
	
	public function addMetaBoxes() {
		\add_meta_box('vacature_blogs', 'Vacatures', function(\WP_Post $post) {
			$this->current_blog_id = get_current_blog_id();
			$this->copy_blog_id = \get_post_meta($post->ID, 'vacature_copy_blog_id', true);
			$this->copy_post_id = \get_post_meta($post->ID, 'vacature_copy_vacature_id', true);
			
			$this->current_blog = get_blog_details($this->current_blog_id);
			$this->copy_blog = get_blog_details($this->copy_blog_id);
			
			switch_to_blog($this->copy_blog_id);
			
			$copy_post = get_post($this->copy_post_id);
			$copy_post_permalink = get_permalink($this->copy_post_id);
			
			restore_current_blog();
			
			\wp_nonce_field('vacature_nonce', 'vacature_nonce');
			?>
			<table class="form-table">
				<tr>
					<th>Huidige site:</th>
					<td>
						<?php print $this->current_blog->blogname; ?>
					</td>
				</tr>
				<?php if ($this->copy_blog_id): ?>
					<tr>
						<th> <label for="<?php echo 'vacature_copy_blog_id'; ?>">Your Field</label></th>
						<td>
							<input id="<?php echo 'vacature_copy_blog_id'; ?>"
							       name="<?php echo 'vacature_copy_blog_id'; ?>"
							       type="text"
							       value="<?php echo esc_attr($copy_post_permalink); ?>"
							/>
						</td>
					</tr>
				<?php else: ?>
					<tr>
						<th>
							Vacature ook plaatsen op:
						</th>
						<td>
							<?php foreach ($this->sites as $site):?>
								<input type="checkbox" name="publish_multisite" id="publish_<?php print $site->blogname;?>" value="<?php print $site->blog_id;?>">
								<label for="publish_<?php print $site->blogname;?>"><?php print $site->blogname;?></label>
							<?php endforeach;?>
						</td>
					</tr>
				<?php endif; ?>
			</table>
			<?php
		}, 'vacature');
	}
	
	private function fetchData()
	{
		$sites = get_sites(
			[
				'site__not_in'      => \get_current_blog_id(),
				'number'            => 20
			]
		);
		
		if ($sites) {
			$this->sites = $sites;
		}
	}
	
	public function copyVacancy($post_id)
	{
		remove_action('save_post_vacature', [$this, __FUNCTION__]);
		if (array_key_exists('publish_multisite', $_POST) && $_POST['publish_multisite'] > 0) {
			$post = get_post($post_id, ARRAY_A);
			$blog_id = $_POST['publish_multisite'];
			$current_post_id = $post['ID'];
			$current_blog_id = get_current_blog_id();
			
			$post_terms = wp_get_object_terms($post_id, 'category', array('fields' => 'slugs'));
			
			$data = get_post_custom($post_id);
			
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
			
			unset($_POST['publish_multisite']);
		}
	}
}
