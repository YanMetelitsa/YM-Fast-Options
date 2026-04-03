<?php

// Exits if accessed directly.
defined( 'ABSPATH' ) || exit;

// Sets field data.
$ymfo_field_classes           = [];
$ymfo_field_additional_attrs  = [];
$ymfo_field_placeholder       = $args[ 'placeholder' ] ?? null;
$ymfo_field_value             = ymfo_get_option( $args[ 'page_slug_tale' ], $args[ 'field_slug_tale' ] );

$ymfo_field_print_mask = '<input type="%1$s" name="%2$s" id="%3$s" class="%4$s" %5$s placeholder="%6$s" value="%7$s" %8$s>';

// Changes field data by type.
switch ( $args[ 'field_type' ] ) {
	// Text.
	case 'text':
		array_push( $ymfo_field_classes, 'regular-text' );

		break;
	// Textarea.
	case 'textarea':
		$ymfo_field_additional_attrs[ 'rows' ] = $args[ 'rows' ] ?? 2;

		$ymfo_field_print_mask = '<textarea name="%2$s" id="%3$s" class="%4$s" %5$s placeholder="%6$s" %8$s>%7$s</textarea>';

		array_push( $ymfo_field_classes, 'regular-text' );

		break;
	// Number.
	case 'number':
		if ( isset( $args[ 'min' ] ) ) {
			$ymfo_field_additional_attrs[ 'min' ] = $args[ 'min' ];
		}
		if ( isset( $args[ 'max' ] ) ) {
			$ymfo_field_additional_attrs[ 'max' ] = $args[ 'max' ];
		}
		if ( isset( $args[ 'step' ] ) ) {
			$ymfo_field_additional_attrs[ 'step' ] = $args[ 'step' ];
		}

		break;
	// Select.
	case 'select':
		$ymfo_field_print_mask = '<select name="%2$s" id="%3$s" %5$s %8$s>';

		foreach ( $args[ 'options' ] as $ymfo_option ) {
			$ymfo_value = $ymfo_option[ 'value' ];
			$ymfo_label = $ymfo_option[ 'label' ];

			$ymfo_field_print_mask .= sprintf( '<option value="%s" %s>%s</option>',
				esc_attr( $ymfo_value ),
				selected( $ymfo_field_value, $ymfo_value, false ),
				esc_html( $ymfo_label ),
			);
		}

		$ymfo_field_print_mask .= '</select>';

		break;
	// Checkbox.
	case 'checkbox':
		if ( checked( $ymfo_field_value, true, false ) ) {
			$ymfo_field_additional_attrs[ 'checked' ] = 'checked';
		}

		$ymfo_field_print_mask  = '<label for="%3$s">';
		$ymfo_field_print_mask .= '<input type="%1$s" name="%2$s" id="%3$s" %5$s value="1" %8$s> ' . $args[ 'description' ] ?? '';
		$ymfo_field_print_mask .= '</label>';

		break;
	// Radio.
	case 'radio':
		$ymfo_field_print_mask  = '<fieldset>';
		$ymfo_field_print_mask .= '<legend class="screen-reader-text"><span>' . $args[ 'field_title' ] . '</span></legend>';

		foreach ( $args[ 'options' ] as $ymfo_option ) {
			$ymfo_value = $ymfo_option[ 'value' ];
			$ymfo_label = $ymfo_option[ 'label' ];

			$ymfo_field_print_mask .= "<label><input type=\"%1$s\" name=\"%2$s\" value=\"$ymfo_value\"" . checked( $ymfo_field_value, $ymfo_value, false ) . "><span>$ymfo_label</span></label><br>";
		}

		$ymfo_field_print_mask .= '</fieldset>';

		break;
	// Tel.
	case 'tel':
		array_push( $ymfo_field_classes, 'regular-text' );

		break;
	// Email.
	case 'email':
		array_push( $ymfo_field_classes, 'regular-text' );

		break;
	// URL.
	case 'url':
		array_push( $ymfo_field_classes, 'regular-text' );
		array_push( $ymfo_field_classes, 'code' );

		break;
	// Image.
	case 'image':
		$ymfo_field_print_mask = '';

		include 'parts/field-image.php';

		break;
}

// Formats additional arguments.
$ymfo_field_additional_attrs_output = [];
foreach ( $ymfo_field_additional_attrs as $ymfo_attr => $ymfo_value ) {
	$ymfo_field_additional_attrs_output[] = $ymfo_attr . ( ! is_null( $ymfo_value ) ? esc_attr( "=$ymfo_value" ) : '' );
}

/** Print field */
// phpcs:ignore
printf( $ymfo_field_print_mask,
	esc_attr( $args[ 'field_type' ] ),								// 1. Type.
	esc_attr( $args[ 'field_name' ] ),								// 2. Name.
	esc_attr( $args[ 'field_id' ] ),								// 3. ID.
	esc_attr( implode( ' ', $ymfo_field_classes ) ),				// 4. Class.
	// phpcs:ignore
	implode( ' ', $ymfo_field_additional_attrs_output ),			// 5. Additional attributes.
	esc_attr( $ymfo_field_placeholder ),							// 6. Placeholder.
	esc_attr( $ymfo_field_value ),									// 7. Value.
	esc_attr( $args[ 'is_field_required' ] ? 'required' : '' ),		// 8. Required.
);

// Prints description.
$ymfo_print_description = true;

if ( ! isset( $args[ 'description' ] ) ) {
	$ymfo_print_description = false;
}
if ( $args[ 'field_type' ] == 'checkbox' ) {
	$ymfo_print_description = false;
}

// Prints field.
printf( '<p class="description"><code class="ymfo-copyable">%s</code>%s</p>',
	esc_html( $args[ 'field_slug_tale' ] ),
	wp_kses_post( $ymfo_print_description ? " – {$args[ 'description' ]}" : '' ),
);