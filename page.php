<?php 
	// Exit if accessed directly
	if ( !defined( 'ABSPATH' ) ) exit;
?>

<!-- Header -->
<div class="ymfo-page-header">
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	</div>
</div>

<div class="wrap">
	<?php
		if ( get_current_screen()->parent_base !== 'options-general' ) {
			settings_errors();
		}
	?>
</div>

<!-- Content -->
<div class="ymfo-page-content">
	<div class="wrap">
		<form action="<?php echo $this->page_args[ 'in_network' ] ? "edit.php?action={$this->page_slug}" : 'options.php'; ?>" method="POST" class="ymfo-page-content__options-form">
			<?php
				if ( $this->page_args[ 'in_network' ] ) {
					wp_nonce_field( 'ymfo_network_nonce' );
				} else {
					settings_fields( $this->page_slug );
				}

				do_settings_sections( $this->page_slug );
				
				submit_button();
			?>
		</form>
	</div>
</div>