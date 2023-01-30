# Gravity Forms Field Mapping Field

This plugin adds the ability to map form fields to user defined keys on the form settings screen.

![GIF](https://github.com/timothyjensen/gravityformsfieldmapping/blob/master/gravityformsfieldmapping.png)

The advantage is being able to programmatically refer to fields by key instead of ID:

```php
add_action( 'gform_after_submission', function ( $entry, $form ) {
    $name_field_id    = '1';
    $email_field_id   = '2';
    $message_field_id = '3';
    
    $name    = rgar( $entry, $name_field_id );
    $email   = rgar( $entry, $email_field_id );
    $message = rgar( $entry, $message_field_id );
    
    // Once the fields have been mapped on the form settings screen, the above can be rewritten as:
    $name_field_key    = 'name';
    $email_field_key   = 'email';
    $message_field_key = 'message';
    
    $name    = \GravityFormsFieldMapping\get_mapped_field_value( $form['id'], $entry, $name_field_key );
    $email   = \GravityFormsFieldMapping\get_mapped_field_value( $form['id'], $entry, $email_field_key );
    $message = \GravityFormsFieldMapping\get_mapped_field_value( $form['id'], $entry, $message_field_key );
    
    // Do more stuff...
}, 10, 2 );
```

There are three helper functions to simplify retrieving form input data:
```php

echo \GravityFormsFieldMapping\get_mapped_field_id( $form_id, 'email' ); // Output: 2

echo \GravityFormsFieldMapping\get_mapped_field_value( $form_id, $entry, 'email' ); // Output: name@domain.com

var_export( \GravityFormsFieldMapping\get_mapped_field_values( $form_id, $entry ) ); // Output: [ 'name' => 'First Last', 'email' => name@domain.com, 'message' => 'Test message' ]
```

