<?php
/**
 * Helper Functions
 */

/**
 * Set Theme About Page Tags
 * @access public
 * @return mixed
 */
function unos_abouttag( $index = 'slug' ) {
	static $tags;
	if ( empty( $tags ) ) {
		$child = hoot_data( 'childtheme_name' );
		$is_official_child = false;
		if ( $child ) {
			$checks = apply_filters( 'unos_hootimport_theme_config_childtheme_array', array( 'Unos Publisher', 'Unos Magazine Vu', 'Unos Business', 'Unos Glow',  'Unos Magazine Black', 'Unos Store Bell', 'Unos Minima Store','Unos News', 'Unos BizDeck' ) );
			foreach ( $checks as $check ) {
				if ( stripos( $child, $check ) !== false ) {
					$is_official_child = true;
					break;
				}
			}
		}
		$tags = $is_official_child ? array() : array(
			'slug' => 'unos',
			'name' => __( 'Unos', 'unos' ),
			'label' => __( 'Unos Options', 'unos' ),
			'vers' => hoot_data( 'template_version' ),
			'shot' => ( file_exists( hoot_data()->template_dir . 'screenshot.jpg' ) ) ? hoot_data()->template_uri . 'screenshot.jpg' : (
						( file_exists( hoot_data()->template_dir . 'screenshot.png' ) ) ? hoot_data()->template_uri . 'screenshot.png' : ''
						),
			'fullshot' => ( file_exists( hoot_data()->incdir . 'admin/images/screenshot.jpg' ) ) ? hoot_data()->incuri . 'admin/images/screenshot.jpg' : (
				( file_exists( hoot_data()->incdir . 'admin/images/screenshot.png' ) ) ? hoot_data()->incuri . 'admin/images/screenshot.png' : ''
				)
		);
		$tags = apply_filters( 'unos_abouttags', $tags );
		if ( ! is_array( $tags ) ) $tags = array();
		if ( !empty( $tags['name'] ) ) $tags['name'] = esc_html( $tags['name'] );
		if ( !empty( $tags['slug'] ) ) $tags['slug'] = sanitize_html_class( $tags['slug'] );
		if ( !empty( $tags['vers'] ) ) $tags['vers'] = sanitize_text_field( $tags['vers'] );
		if ( !empty( $tags['shot'] ) ) $tags['shot'] = esc_url( $tags['shot'] );
		if ( !empty( $tags['fullshot'] ) ) $tags['fullshot'] = esc_url( $tags['fullshot'] );
		elseif ( !empty( $tags['shot'] ) ) $tags['fullshot'] = $tags['shot'];
	}
	return ( ( isset( $tags[ $index ] ) ) ? $tags[ $index ] : '' );
}