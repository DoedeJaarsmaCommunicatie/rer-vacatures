<?php
use PropertyPeople\Includes\Models\Vacature;

if(!isset($_REQUEST['solicitor_id'])) {
    return;
}

try {
	$vacature = new Vacature((int) $_REQUEST['solicitor_id']);
} catch (\Exception $exception) {
	print $exception->getMessage();
}

if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] === 'toggle_status' && wp_verify_nonce( $_GET[ '_wpnonce' ], 'toggle_solicitation_status' ) ) {
    if ( $vacature->status === 'opgepakt' ) {
        $vacature->toggleStatus( 'nieuw' );
    } else {
        $vacature->toggleStatus();
    }
    
    ?>
    <script>
        (function ($) {
            var qp = new URLSearchParams(window.location.search)
            
            qp.delete('action');
            qp.delete('_wpnonce');
            
            window.location.search = qp.toString();
        })(jQuery)
    </script>
    <?php
}

$delete_args = [
    'page'          => 'vacancy-overview',
    'action'        => 'delete_solicitation',
    'solicitor_id'  => $vacature->id,
    '_wpnonce'      => wp_create_nonce('delete_solicitation')
];

$delete_link = esc_url(add_query_arg($delete_args, admin_url('admin.php?page=' . $_REQUEST['page'])));

$update_args = [
    'page'          => 'single_solicitor',
    'action'        => 'toggle_status',
    'solicitor_id'  => $vacature->id,
    '_wpnonce'      => wp_create_nonce('toggle_solicitation_status'),
];

$update_link = esc_url(add_query_arg($update_args, admin_url('admin.php')));
?>
	<div class="wrap">
		<h2><?php _e('Sollicitatie', 'ppmm'); ?></h2>
        <style>
            @media screen and (min-width: 414px) {
                .postbox-container {
                    max-width: 45vw;
                }
            }
        </style>
		<div class="postbox-container" id="poststuff" style="float: left;">
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle"><span>Naam</span></h2>
				<div class="inside">
					<?php print $vacature->getName(); ?>
				</div>
			</div>
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle"><span>E-mail</span></h2>
				<div class="inside">
					<a href="mailto:<?php print $vacature->email; ?>">
						<?php print $vacature->email; ?>
					</a>
				</div>
			</div>
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle"><span>Mobiele nummer</span></h2>
				<div class="inside">
					<a href="tel:<?php print $vacature->phone; ?>">
						<?php print $vacature->phone; ?>
					</a>
				</div>
			</div>
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle"><span>Motivatie</span></h2>
				<div class="inside">
					<?php print $vacature->motivation; ?>
				</div>
			</div>
		</div>
		<div class="postbox-container" id="poststuff" style="float: right;">
			<div class="postbox submitbox">
				<h2 class="hndle">
					<span>
						Acties
					</span>
				</h2>
				<div class="inside">
					<a href="<?php print $delete_link; ?>" class="submitdelete deletion">
						<?php _e('Delete') ?>
					</a>
				</div>
			</div>
            <div class="postbox">
                <h2 class="hndle">
                    <span>
                        Status: <?= $vacature->status ?>
                    </span>
                </h2>
                <div class="inside">
                    <a href="<?= $update_link ?>">
                        <?= __('Status wisselen') ?>
                    </a>
                </div>
            </div>
			<div class="postbox">
				<h2 class="hndle">
					<span>
						Voor vacature
					</span>
				</h2>
				<div class="inside">
					<a href="<?php print get_permalink($vacature->post->ID); ?>">
						<?php print $vacature->post->post_title ?>
					</a>
				</div>
			</div>
			<div class="postbox">
				<h2 class="hndle">
					<span>
						CV
					</span>
				</h2>
				<div class="inside">
            <?php if (!empty($vacature->file)): ?>
					<a href="<?php print $vacature->file; ?>" download>
						Download
					</a>
			<?php else: ?>
				<strong>Er is geen CV geupload</strong>
            <?php endif; ?>
				</div>
			</div>

		</div>
	</div>
<?php
