<?php

/** Exit if accessed directly */
if ( !defined( 'ABSPATH' ) ) exit;

/** Set field data */
$field_classes           = [];
$field_additional_attrs  = [];
$field_placeholder       = $args[ 'placeholder' ] ?? null;
$field_value             = ymfo_get_option( $args[ 'page_slug_tale' ], $args[ 'field_slug_tale' ] );

$field_print_mask = '<input type="%1$s" name="%2$s" id="%3$s" class="%4$s" %5$s placeholder="%6$s" value="%7$s" %8$s>';

/** Change field data by type */
switch ( $args[ 'field_type' ] ) {
	/** Text */
	case 'text':
		array_push( $field_classes, 'regular-text' );

		break;
	/** Textarea */
	case 'textarea':
		$field_additional_attrs[ 'rows' ] = $args[ 'rows' ] ?? 2;

		$field_print_mask = '<textarea name="%2$s" id="%3$s" class="%4$s" %5$s placeholder="%6$s" %8$s>%7$s</textarea>';

		array_push( $field_classes, 'regular-text' );

		break;
	/** Number */
	case 'number':
		if ( isset( $args[ 'min' ] ) ) {
			$field_additional_attrs[ 'min' ] = $args[ 'min' ];
		}
		if ( isset( $args[ 'max' ] ) ) {
			$field_additional_attrs[ 'max' ] = $args[ 'max' ];
		}
		if ( isset( $args[ 'step' ] ) ) {
			$field_additional_attrs[ 'step' ] = $args[ 'step' ];
		}

		break;
	/** Select */
	case 'select':
		$field_print_mask = '<select name="%2$s" id="%3$s" %5$s %8$s>';

		foreach ( $args[ 'options' ] as $option ) {
			$value = $option[ 'value' ];
			$label = $option[ 'label' ];

			$field_print_mask .= sprintf( '<option value="%s" %s>%s</option>',
		$value,
				selected( $field_value, $value, false ),
				$label,
			);
		}

		$field_print_mask .= '</select>';

		break;
	/** Checkbox */
	case 'checkbox':
		if ( checked( $field_value, true, false ) ) {
			$field_additional_attrs[ 'checked' ] = 'checked';
		}

		$field_print_mask = '<label for="%3$s">';
		$field_print_mask .= '<input type="%1$s" name="%2$s" id="%3$s" %5$s value="1" %8$s> ' . $args[ 'description' ] ?? '';
		$field_print_mask .= '</label>';

		break;
	/** Radio */
	case 'radio':
		$field_print_mask = '<fieldset>';
		$field_print_mask .= '<legend class="screen-reader-text"><span>' . $args[ 'field_title' ] . '</span></legend>';

		foreach ( $args[ 'options' ] as $option ) {
			$value = $option[ 'value' ];
			$label = $option[ 'label' ];

			$field_print_mask .= '<label><input type="%1$s" name="%2$s" value="' . $value . '" ' . checked( $field_value, $value, false ) . '> <span>' . $label . '</span></label><br>';
		}

		$field_print_mask .= '</fieldset>';

		break;
	/** Tel */
	case 'tel':
		array_push( $field_classes, 'regular-text' );

		break;
	/** Email */
	case 'email':
		array_push( $field_classes, 'regular-text' );

		break;
	/** URL */
	case 'url':
		array_push( $field_classes, 'regular-text' );
		array_push( $field_classes, 'code' );

		break;
}

/** Format additional arguments */
$field_additional_attrs_output = [];
foreach ( $field_additional_attrs as $attr => $value ) {
	$field_additional_attrs_output[] = $attr . ( !is_null( $value ) ? '=' . esc_attr( $value ) : '' );
}

/** Print field */
printf( $field_print_mask,
	esc_attr( $args[ 'field_type' ] ),								// 1. Type
	esc_attr( $args[ 'field_name' ] ),										// 2. Name
	esc_attr( $args[ 'field_id' ] ),										// 3. ID
	esc_attr( implode( ' ', $field_classes ) ),			// 4. Class
	implode( ' ', $field_additional_attrs_output ),		// 5. Additional attributes
	esc_attr( $field_placeholder ),											// 6. Placeholder
	esc_attr( $field_value ),												// 7. Value
	$args[ 'is_field_required' ] ? 'required' : '',							// 8. Required
);

/** Print description */
$print_description = true;

if ( !isset( $args[ 'description' ] ) ) {
	$print_description = false;
}
if ( $args[ 'field_type' ] == 'checkbox' ) {
	$print_description = false;
}

printf( '<p><code class="ymfo-copyable">%s</code>%s</p>',
	esc_html( $args[ 'field_slug_tale' ] ),
	$print_description ? " – {$args[ 'description' ]}" : '',
);