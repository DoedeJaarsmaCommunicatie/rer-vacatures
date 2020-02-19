<?php
use PropertyPeople\Includes\Models\OpenVacature;

if (!isset($_REQUEST['solicitor_id'])) {
	return;
}

try {
	$vacature = new OpenVacature((int) $_REQUEST['solicitor_id']);
} catch (\Exception $exception) {
	print $exception->getMessage();
	return;
}

if (
	isset( $_GET[ 'action' ] )
	&& $_GET[ 'action' ] === 'toggle_status'
	&& wp_verify_nonce( $_GET[ '_wpnonce' ], 'toggle_solicitation_status' )
) {
	if ($vacature->status === 'opgepakt') {
		$vacature->toggleStatus('nieuw');
	} else {
		$vacature->toggleStatus();
	}

	?>
	<script>
		(function () {
			var qp = new URLSearchParams(window.location.search)

			qp.delete('action');
			qp.delete('_wpnonce');

			window.location.search = qp.toString();
		})()
	</script>
	<?php
}

$delete_args = [
	'page' => 'sollicitor-overview',
	'action' => 'delete_solicitation',
	'solicitor_id' => $vacature->id,
	'_wpnonce' => wp_create_nonce('delete_solicitation')
];

$delete_link = add_query_arg($delete_args, admin_url('admin.php'));

$update_args = [
	'page'          => $_REQUEST['page'],
	'action'        => 'toggle_status',
	'solicitor_id'  => $vacature->id,
	'_wpnonce'      => wp_create_nonce('toggle_solicitation_status'),
];

$update_link = add_query_arg($update_args, admin_url('admin.php'));
?>
<style>
	@media screen and (min-width: 414px) {
		.postbox-container {
			max-width: 45vw;
		}
	}
</style>
<div class="wrap">
	<h2><?php _e('Sollicitatie', 'ppmm'); ?></h2>

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
					Beoogde functie
				</span>
			</h2>
			<div class="inside">
				<?php print $vacature->function ?>
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

