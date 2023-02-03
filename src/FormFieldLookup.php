<?php
/**
 * FormFieldLookup class.
 *
 * @package     GravityFormsFieldMapping
 * @author      Tim Jensen <tim@timjensen.us>
 * @license     GNU General Public License 2.0+
 * @link        https://www.timjensen.us
 */

namespace GravityFormsFieldMapping;

/**
 * FormFieldLookup class.
 */
class FormFieldLookup extends \GFAddOn {

	public const DATA_KEY = 'fieldMapping';

	/**
	 * AddOn slug.
	 *
	 * @var string
	 */
	protected $_slug = 'gravityformsfieldmapping';

	/**
	 * Form ID.
	 *
	 * @var int
	 */
	private int $form_id;

	/**
	 * Form data.
	 *
	 * @var array
	 */
	private array $form;

	/**
	 * FormFieldLookup constructor.
	 *
	 * @param int   $form_id
	 * @param array $form
	 */
	public function __construct( int $form_id, array $form = [] ) {
		$this->form_id = $form_id;
		$this->form    = $form;
	}

	/**
	 * Returns a single instance of the class.
	 *
	 * @param int   $form_id
	 * @param array $form_data
	 * @return FormFieldLookup
	 */
	public static function make( int $form_id, array $form_data = [] ): FormFieldLookup {
		static $instance = null;

		if ( ! isset( $instance[ $form_id ] ) ) {
			$form_data = $form_data ?: \GFAPI::get_form( $form_id );

			$instance[ $form_id ] = new self( $form_id, $form_data );
		}

		return $instance[ $form_id ];
	}

	/**
	 * Returns the field value.
	 *
	 * @param array $form
	 * @param array $entry
	 * @param string $field_id
	 * @return mixed
	 */
	public function get_field_value( $form, $entry, $field_id ) {
		$field_value = parent::get_field_value( $form, $entry, $field_id );

		$field = \GFFormsModel::get_field( $form, $field_id );

		if ( is_a( $field, \GF_Field::class ) && $field->is_value_submission_array() && is_string( $field_value ) ) {
			try {
				$field_value = json_decode( $field_value, true, 512, JSON_THROW_ON_ERROR );
			} catch ( \Exception $e ) {
				$field_value = explode( ', ', $field_value );
			}
		}

		return $field_value;
	}

	/**
	 * Returns the field ID that corresponds with the supplied key.
	 *
	 * @param string $key Field mapping key.
	 * @return string|null
	 */
	public function get_field_id( string $key ): ?string {
		static $field_mapping = null;

		if ( null === $field_mapping ) {
			$field_mapping = self::get_dynamic_field_map_fields( $this->feed_config(), 'fieldMapping' );
		}

		return $field_mapping[ $key ] ?? null;
	}

	/**
	 * Returns the mapped field values.
	 *
	 * @param array $entry
	 * @return array
	 */
	public function field_values( array $entry ): array {
		static $field_values = null;

		$hash = md5( json_encode( $entry ) );

		if ( ! isset( $field_values[ $hash ] ) ) {
			$field_values[ $hash ] = $this->get_generic_map_fields( $this->feed_config(), 'fieldMapping', $this->form, $entry );
		}

		return (array) $field_values[ $hash ];
	}

	/**
	 * Returns the mapped field value.
	 *
	 * @param array $entry
	 * @param string $field_name
	 * @return mixed
	 */
	public function field_value( array $entry, string $field_name ) {
		return $this->field_values( $entry )[ $field_name ] ?? '';
	}

	/**
	 * Returns the feed config.
	 *
	 * @return array
	 */
	private function feed_config(): array {
		$field_mapping = $this->form['fieldMapping'] ?? [];

		foreach ( $field_mapping as &$mapping ) {
			if ( $mapping['value'] ?? '' === 'gf_custom' ) {
				$mapping['value'] = $mapping['custom_value'] ?? '';
			}
		}

		return [
			'meta' => [
				'fieldMapping' => $field_mapping,
			],
		];
	}
}
