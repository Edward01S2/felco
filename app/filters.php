<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter('acf/fields/google_map/api', function($api) {
    $api['key'] = 'AIzaSyAyGbaOm6xcPYYf6B6LZiljhkPcen9akwU';
    //DEV KEY
    //$api['key'] = 'AIzaSyAhv4kCi2_Cl7Xqgcxs3tIWd96vOHDvFGI';
    return $api;
});

add_filter( 'gform_field_validation_2_1', __NAMESPACE__ . '\\validate_SN', 10, 4 );
add_filter( 'gform_field_validation_3_2', __NAMESPACE__ . '\\validate_SN', 10, 4 );
add_filter( 'gform_field_validation_4_2', __NAMESPACE__ . '\\validate_SN', 10, 4 );
function validate_SN( $result, $value, $form, $field ) {
    $pattern = "/^\d{2}[a-zA-z]{2}\d{5}/";
    if ( !preg_match( $pattern, $value ) ) {
        $result['is_valid'] = false;
        $result['message']  = 'Please enter a valid serial number';
    }

    return $result;
}