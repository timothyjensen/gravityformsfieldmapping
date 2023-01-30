<?php

namespace GravityFormsFieldMapping;

\spl_autoload_register( 'GravityFormsFieldMapping\autoload_classes' );
/**
 * Class autoloader.
 *
 * @param string $class
 * @return void
 */
function autoload_classes( $class ) {
	$namespace = __NAMESPACE__;

	$should_autoload = \preg_match( "/^$namespace\\\\(?P<class_name>.*)$/", $class, $matches );

	if ( ! $should_autoload ) {
		return;
	}

	$class_name = \str_replace( '\\', '/', $matches['class_name'] );

	include_once __DIR__ . "/src/$class_name.php";
}

add_filter( 'gform_form_settings_fields', 'GravityFormsFieldMapping\add_field_mapping_settings_field' );
/**
 * Adds the field mapping form setting.
 *
 * @param array $fields
 * @return array
 */
function add_field_mapping_settings_field( array $fields ): array {
	$fieldMappingField = [
		'label'       => esc_html__( 'Key:Value Pairs', 'gravityformsfieldmapping' ),
		'name'        => FormFieldLookup::DATA_KEY,
		'type'        => 'generic_map',
		'required'    => false,
		'merge_tags'  => true,
		'tooltip'     => sprintf(
			'<h6>%s</h6>%s',
			esc_html__( 'Field Mapping', 'gravityformsfieldmapping' ),
			esc_html__( 'Setup key names to be used when processing the form and entry data.', 'gravityformsfieldmapping' )
		),
		'value_field' => [
			'choices'      => 'form_fields',
			'custom_value' => true,
		],
	];

	$fields['field_mapping'] = [
		'title'  => esc_html__( 'Form Field Mapping', 'gravityformsfieldmapping' ),
		'fields' => [ $fieldMappingField ],
	];

	return $fields;
}

add_filter( 'gform_form_settings_initial_values', 'GravityFormsFieldMapping\load_field_mapping_settings_initial_values', 10, 2 );
/**
 * Loads the saved fieldMapping values on the form settings page.
 *
 * @param array $initial_values
 * @param array $form
 * @return array
 */
function load_field_mapping_settings_initial_values( $initial_values, $form ) {
	if ( isset( $form['fieldMapping'] ) ) {
		$initial_values['fieldMapping'] = $form['fieldMapping'];
	}

	return $initial_values;
}

add_filter( 'gform_form_post_get_meta', 'GravityFormsFieldMapping\initialize_form_field_lookup', 15 );
/**
 * Initializes the form field lookup functionality.
 *
 * @param array $form
 * @return void
 */
function initialize_form_field_lookup( $form ) {
	if ( isset( $form[ FormFieldLookup::DATA_KEY ] ) ) {
		FormFieldLookup::make( (int) $form['id'], $form );
	}

	return $form;
}

/**
 * Helper function for retrieving the field ID.
 *
 * @param $form_id
 * @param $key
 * @return string|null
 */
function get_mapped_field_id( $form_id, $key ): ?string {
	if ( ! class_exists( \GFAPI::class ) ) {
		return null;
	}

	return FormFieldLookup::make( (int) $form_id )->get_field_id( (string) $key );
}

/**
 * Helper function for retrieving the field ID.
 *
 * @param $form_id
 * @param $entry
 * @param $key
 * @return string|null
 */
function get_mapped_field_value( $form_id, $entry, $key ): ?string {
	if ( ! class_exists( \GFAPI::class ) ) {
		return null;
	}

	return FormFieldLookup::make( (int) $form_id )->field_value( $entry, $key );
}

/**
 * Helper function for retrieving the field ID.
 *
 * @param $form_id
 * @param $entry
 * @return array|null
 */
function get_mapped_field_values( $form_id, $entry ): ?array {
	if ( ! class_exists( \GFAPI::class ) ) {
		return null;
	}

	return FormFieldLookup::make( (int) $form_id )->field_values( $entry );
}
