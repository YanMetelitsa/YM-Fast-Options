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

<!-- Content -->
<div class="ymfo-page-content">
	<div class="wrap">
		<form action="options.php" method="POST" class="ymfo-page-content__options-form">
			<?php
				settings_fields( $this->page_slug );
				do_settings_sections( $this->page_slug );
				
				submit_button();
			?>
		</form>
	</div>
</div>