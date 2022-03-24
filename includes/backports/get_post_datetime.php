<?php

/**
 * Retrieve post published or modified time as a `DateTimeImmutable` object instance.
 *
 * The object will be set to the timezone from WordPress settings.
 *
 * For legacy reasons, this function allows to choose to instantiate from local or UTC time in database.
 * Normally this should make no difference to the result. However, the values might get out of sync in database,
 * typically because of timezone setting changes. The parameter ensures the ability to reproduce backwards
 * compatible behaviors in such cases.
 *
 * @since 5.3.0
 *
 * @param int|WP_Post $post   Optional. WP_Post object or ID. Default is global `$post` object.
 * @param string      $field  Optional. Published or modified time to use from database. Accepts 'date' or 'modified'.
 *                            Default 'date'.
 * @param string      $source Optional. Local or UTC time to use from database. Accepts 'local' or 'gmt'.
 *                            Default 'local'.
 * @return DateTimeImmutable|false Time object on success, false on failure.
 */
if ( ! function_exists( 'get_post_datetime' ) ) :
function get_post_datetime( $post = null, $field = 'date', $source = 'local' ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$wp_timezone = wp_timezone();

	if ( 'gmt' === $source ) {
		$time     = ( 'modified' === $field ) ? $post->post_modified_gmt : $post->post_date_gmt;
		$timezone = new DateTimeZone( 'UTC' );
	} else {
		$time     = ( 'modified' === $field ) ? $post->post_modified : $post->post_date;
		$timezone = $wp_timezone;
	}

	if ( empty( $time ) || '0000-00-00 00:00:00' === $time ) {
		return false;
	}

	$datetime = date_create_immutable_from_format( 'Y-m-d H:i:s', $time, $timezone );

	if ( false === $datetime ) {
		return false;
	}

	return $datetime->setTimezone( $wp_timezone );
}
endif;
