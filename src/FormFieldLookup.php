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
class FormFieldLookup {

	public const DATA_KEY = 'fieldMapping';

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
	 * Returns the field ID that corresponds with the supplied key.
	 *
	 * @param string $key Field mapping key.
	 * @return string|null
	 */
	public function get_field_id( string $key ): ?string {
		foreach ( $this->form['fieldMapping'] as $mapping ) {
			if ( $mapping['custom_key'] === $key ) {
				return (string) $mapping['value'];
			}
		}

		return null;
	}
}
