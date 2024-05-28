<?php

if ( !defined( 'ABSPATH' ) ) exit;

// Print section description
if ( isset( $args[ 'description' ] ) ) {
    echo '<p>' . esc_html( $args[ 'description' ] ) . '</p>';
}