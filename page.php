<?php  if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="ymfo-page-header">
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	</div>
</div>

<div class="ymfo-page-info">
	<div class="wrap">
		<p>
            <?php printf(
                __( 'To get option value use <code>ymfo_get_option( \'%s\', \'label\' )</code>. You can find <code>label</code> value under each field.', 'ym-fast-options' ),
                esc_html( $this->page_slug_tale ),
            ); ?>
        </p>
	</div>
</div>

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