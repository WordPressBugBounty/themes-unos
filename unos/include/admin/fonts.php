<?php
/**
 * Functions for sending list of fonts available
 * 
 * Also add them to sanitization array (list of allowed options)
 *
 * @package    Unos
 * @subpackage Theme
 */

/**
 * Build URL for loading Google Fonts
 * @credit http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 *
 * @since 1.0
 * @updated 2.9
 * @access public
 * @return void
 */
function unos_google_fonts_enqueue_url() {

	// Backward compatibility for older child themes (Unos Pub < 1.0.8)
	$query_args = apply_filters( 'unos_google_fonts_enqueue_url_args', array(), true );
	if ( is_array( $query_args ) && !empty( $query_args ) && !empty( $query_args['family'] ) ):
		return add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	else:

	$fonts_url = '';
	$fonts = apply_filters( 'unos_google_fonts_preparearray', array() );
	$args = array();

	if ( empty( $fonts ) ) {
		$modsfont = array( hoot_get_mod( 'logo_fontface' ), hoot_get_mod( 'headings_fontface' ) );

		$fonts ['Open Sans'] = array(
				'normal' => array( '300','400','500','600','700','800' ),
				'italic' => array( '400','700' ),
			);
		if ( in_array( 'fontcf', $modsfont ) ) {
			$fonts[ 'Comfortaa' ] = array(
				'normal' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontow', $modsfont ) ) {
			$fonts[ 'Oswald' ] = array(
				'normal' => array( '400' ),
			);
		}
		if ( in_array( 'fontlo', $modsfont ) ) {
			$fonts[ 'Lora' ] = array(
				'normal' => array( '400','700' ),
				'italic' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontsl', $modsfont ) ) {
			$fonts[ 'Slabo 27px' ] = array(
				'normal' => array( '400' ),
			);
		}
	}
	$fonts = apply_filters( 'unos_google_fonts_array', $fonts );

	// Cant use 'add_query_arg()' directly as new google font api url will have multiple key 'family' when adding multiple fonts
	// Hence use 'add_query_arg' on each argument separately and then combine them.
	foreach ( $fonts as $key => $value ) {
		if ( is_array( $value ) && ( !empty( $value['normal'] ) || !empty( $value['italic'] ) ) && ( is_array( $value['normal'] ) || is_array( $value['italic'] ) ) ) {
			$arg = array( 'family' => $key . ':ital,wght@' );
			if ( !empty( $value['normal'] ) && is_array( $value['normal'] ) ) foreach ( $value['normal'] as $wght ) $arg['family'] .= "0,{$wght};";
			if ( !empty( $value['italic'] ) && is_array( $value['italic'] ) ) foreach ( $value['italic'] as $wght ) $arg['family'] .= "1,{$wght};";
			$arg['family'] = substr( $arg['family'], 0, -1 );
			$args[] = substr( add_query_arg( $arg, '' ), 1 );
		}
	}

	if ( !empty( $args ) ) {
		$fonts_url = '//fonts.googleapis.com/css2?' . implode( '&', $args );
		$fonts_url .= ( apply_filters( 'unos_google_fonts_displayswap', false ) ) ? '&display=swap' : '';
	}

	return $fonts_url;

	endif;
}

/**
 * Modify the font (websafe) list
 * Font list should always have the form:
 * {css style} => {font name}
 * 
 * Even though this list isn't currently used in customizer options (no typography options)
 * this is still needed so that sanitization functions recognize the font.
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unos_fonts_list( $fonts ) {
	$fonts['"Open Sans", sans-serif'] = 'Open Sans';
	$fonts['"Comfortaa", sans-serif'] = 'Comfortaa';
	$fonts['"Oswald", sans-serif']    = 'Oswald';
	$fonts['"Lora", serif']           = 'Lora';
	$fonts['"Slabo 27px", serif']     = 'Slabo 27px';
	return $fonts;
}
add_filter( 'hoot_fonts_list', 'unos_fonts_list' );