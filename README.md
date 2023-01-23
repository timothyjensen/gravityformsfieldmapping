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
    
    $name    = rgar( $entry, \GravityFormsFieldMapping\get_field_id( $form['id'], $name_field_key ) );
    $email   = rgar( $entry, \GravityFormsFieldMapping\get_field_id( $form['id'], $email_field_key ) );
    $message = rgar( $entry, \GravityFormsFieldMapping\get_field_id( $form['id'], $message_field_key ) );
    
    // Do more stuff...
}, 10, 2 );
```

