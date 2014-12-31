<?php
namespace Moonlight\Core;

/**
 * WordPress hook initialization
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'init' ) );

	add_filter( 'the_content', $n( 'twitterify' ) );
}

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'moonlight' );
	load_textdomain( 'moonlight', WP_LANG_DIR . '/moonlight/moonlight-' . $locale . '.mo' );
	load_plugin_textdomain( 'moonlight', false, MOONLIGHT_PATH . '/languages/' );
}

/**
 * Activate the plugin
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();

	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function deactivate() {

}

/**
 * Get a list of WordPressers, cached, from a GitHub file.
 *
 * @return array
 */
function get_wordpressers() {
	$wordpressers = get_transient( 'moonlight' );

	if ( false === $wordpressers ) {
		$request = wp_remote_get( '' );

		if ( is_wp_error( $request ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $request );

		if ( is_wp_error( $body ) ) {
			return array();
		}

		$wordpressers = json_decode( $body, true );

		if ( null == $wordpressers ) {
			return array();
		}

		set_transient( 'moonshine', $wordpressers, 60 * 60 ); // Cache for an hour.
	}

	return $wordpressers;
}

/**
 * Get a list of the appropriate WordPressers in regex-parsing format
 */
function get_wordpresser_regex() {
	$wordpressers = get_wordpressers();
	$names = array_keys( $wordpressers );
	$name_regexp = join( '|', array_map( 'preg_quote', $names ) );

	return "($name_regexp)";
}

/**
 * Automatically link the first mention of a WordPresser's first name to their Twitter handle.
 *
 * @param string $content
 *
 * @return string
 */
function twitterify( $content ) {
	$pattern = get_wordpresser_regex();

	return preg_replace_callback( "/$pattern/si", __NAMESPACE__ . '\do_twitter', $content );
}

/**
 * Regular Expression callable for WordPresser Twitterification mechanisme.
 *
 * @param array $match
 *
 * @return mixed False on failure
 */
function do_twitter( $match ) {
	$wordpressers = get_wordpressers();
	$keys = array_keys( $wordpressers );
	$keys = array_map( 'strtolower', $keys );
	$wordpressers = array_combine( $keys, array_values( $wordpressers ) );

	$key = strtolower( $match[1] );

	if ( ! isset( $wordpressers[ $key ] ) ) {
		return $match[1];
	}

	$handle = $wordpressers[ $key ];

	return sprintf( '<a href="https://twitter.com/%1$s" title="@%1$s">%2$s</a>', $handle, $match[1] );
}