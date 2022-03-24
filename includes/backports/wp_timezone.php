<?php

/**
 * Retrieves the timezone of the site as a string.
 *
 * Uses the `timezone_string` option to get a proper timezone name if available,
 * otherwise falls back to a manual UTC ± offset.
 *
 * Example return values:
 *
 *  - 'Europe/Rome'
 *  - 'America/North_Dakota/New_Salem'
 *  - 'UTC'
 *  - '-06:30'
 *  - '+00:00'
 *  - '+08:45'
 *
 * @since 5.3.0
 *
 * @return string PHP timezone name or a ±HH:MM offset.
 */
if ( ! function_exists( 'wp_timezone_string' ) ) :
function wp_timezone_string() {
	$timezone_string = get_option( 'timezone_string' );

	if ( $timezone_string ) {
		return $timezone_string;
	}

	$offset  = (float) get_option( 'gmt_offset' );
	$hours   = (int) $offset;
	$minutes = ( $offset - $hours );

	$sign      = ( $offset < 0 ) ? '-' : '+';
	$abs_hour  = abs( $hours );
	$abs_mins  = abs( $minutes * 60 );
	$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

	return $tz_offset;
}
endif;

/**
 * Retrieves the timezone of the site as a `DateTimeZone` object.
 *
 * Timezone can be based on a PHP timezone string or a ±HH:MM offset.
 *
 * @since 5.3.0
 *
 * @return DateTimeZone Timezone object.
 */
if ( ! function_exists( 'wp_timezone' ) ) :
function wp_timezone() {
	return new DateTimeZone( wp_timezone_string() );
}
endif;
