<?php

/** Exit if accessed directly */
if ( !defined( 'ABSPATH' ) ) exit;

/** Print section description */
if ( isset( $args[ 'description' ] ) ) {
    echo '<p>' . $args[ 'description' ] . '</p>';
}