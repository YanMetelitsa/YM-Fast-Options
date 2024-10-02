<?php

// Exits if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Prints section description.
if ( isset( $args[ 'description' ] ) ) {
	printf( '<p>%s</p>', wp_kses_post( $args[ 'description' ] ) );
}