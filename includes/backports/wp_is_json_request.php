<?php

/**
 * Checks whether a string is a valid JSON Media Type.
 *
 * @since 5.6.0
 *
 * @param string $media_type A Media Type string to check.
 * @return bool True if string is a valid JSON Media Type.
 */

if ( ! function_exists( 'wp_is_json_media_type' ) ) :
function wp_is_json_media_type( $media_type ) {
	static $cache = array();

	if ( ! isset( $cache[ $media_type ] ) ) {
		$cache[ $media_type ] = (bool) preg_match( '/(^|\s|,)application\/([\w!#\$&-\^\.\+]+\+)?json(\+oembed)?($|\s|;|,)/i', $media_type );
	}

	return $cache[ $media_type ];
}
endif;

/**
 * Checks whether current request is a JSON request, or is expecting a JSON response.
 *
 * @since 5.0.0
 *
 * @return bool True if `Accepts` or `Content-Type` headers contain `application/json`.
 *              False otherwise.
 */
if ( ! function_exists( 'wp_is_json_request' ) ) :
	function wp_is_json_request() {

		if ( isset( $_SERVER['HTTP_ACCEPT'] ) && wp_is_json_media_type( $_SERVER['HTTP_ACCEPT'] ) ) {
			return true;
		}

		if ( isset( $_SERVER['CONTENT_TYPE'] ) && wp_is_json_media_type( $_SERVER['CONTENT_TYPE'] ) ) {
			return true;
		}

		return false;

	}
endif;
