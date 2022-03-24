<?php

/**
 * Determine the current locale desired for the request.
 *
 * @return string The determined locale.
 * @global string $pagenow
 *
 * @since 5.0.0
 *
 */
if ( ! function_exists( 'determine_locale' ) ) :
	function determine_locale() {
		/**
		 * Filters the locale for the current request prior to the default determination process.
		 *
		 * Using this filter allows to override the default logic, effectively short-circuiting the function.
		 *
		 * @param string|null $locale The locale to return and short-circuit. Default null.
		 *
		 * @since 5.0.0
		 *
		 */
		$determined_locale = apply_filters( 'pre_determine_locale', null );

		if ( ! empty( $determined_locale ) && is_string( $determined_locale ) ) {
			return $determined_locale;
		}

		$determined_locale = get_locale();

		if ( is_admin() ) {
			$determined_locale = get_user_locale();
		}

		if ( isset( $_GET['_locale'] ) && 'user' === $_GET['_locale'] && wp_is_json_request() ) {
			$determined_locale = get_user_locale();
		}

		$wp_lang = '';

		if ( ! empty( $_GET['wp_lang'] ) ) {
			$wp_lang = sanitize_text_field( $_GET['wp_lang'] );
		} elseif ( ! empty( $_COOKIE['wp_lang'] ) ) {
			$wp_lang = sanitize_text_field( $_COOKIE['wp_lang'] );
		}

		if ( ! empty( $wp_lang ) && ! empty( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
			$determined_locale = $wp_lang;
		}

		/**
		 * Filters the locale for the current request.
		 *
		 * @param string $locale The locale.
		 *
		 * @since 5.0.0
		 *
		 */
		return apply_filters( 'determine_locale', $determined_locale );
	}

endif;
