<?php

/**
 * Determine the current locale desired for the request.
 *
 * @since 5.0.0
 *
 * @global string $pagenow
 *
 * @return string The determined locale.
 */
if ( ! function_exists( 'determine_locale' ) ) :
	function determine_locale() {
		/**
		 * Filters the locale for the current request prior to the default determination process.
		 *
		 * Using this filter allows to override the default logic, effectively short-circuiting the function.
		 *
		 * @since 5.0.0
		 *
		 * @param string|null The locale to return and short-circuit, or null as default.
		 */
		$determined_locale = apply_filters( 'pre_determine_locale', null );
		if ( ! empty( $determined_locale ) && is_string( $determined_locale ) ) {
			return $determined_locale;
		}

		$determined_locale = get_locale();

		if ( function_exists( 'get_user_locale' ) && is_admin() ) {
			$determined_locale = get_user_locale();
		}

		if ( function_exists( 'get_user_locale' ) && isset( $_GET['_locale'] ) && 'user' === $_GET['_locale'] ) {
			$determined_locale = get_user_locale();
		}

		if ( ! empty( $_GET['wp_lang'] ) && ! empty( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
			$determined_locale = sanitize_text_field( $_GET['wp_lang'] );
		}

		/**
		 * Filters the locale for the current request.
		 *
		 * @since 5.0.0
		 *
		 * @param string $locale The locale.
		 */
		return apply_filters( 'determine_locale', $determined_locale );
	}
endif;
