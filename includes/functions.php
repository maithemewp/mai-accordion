<?php

function mai_get_accordion( $args ) {
	$accordion = new Mai_Accordion( $args );
	return $accordion->get();
}

function mai_get_accordion_item( $args ) {
	$accordion = new Mai_Accordion_Item( $args );
	return $accordion->get();
}

function mai_enqueue_accordion_styles( $preview = false ) {
	static $enqueued = false;
	if ( $enqueued ) {
		return;
	}
	$suffix = mai_get_suffix();
	wp_enqueue_style( 'mai-accordion', MAI_ACCORDION_PLUGIN_URL . sprintf( 'assets/mai-accordion%s.css', $suffix ), [], MAI_ACCORDION_VERSION . '.' . date( 'njYHi', filemtime( MAI_ACCORDION_PLUGIN_DIR . sprintf( 'assets/mai-accordion%s.css', $suffix ) ) ) );
	if ( $preview ) {
		wp_enqueue_style( 'mai-accordion-editor', MAI_ACCORDION_PLUGIN_URL . sprintf( 'assets/mai-accordion-editor%s.css', $suffix ), [], MAI_ACCORDION_VERSION . '.' . date( 'njYHi', filemtime( MAI_ACCORDION_PLUGIN_DIR . sprintf( 'assets/mai-accordion-editor%s.css', $suffix ) ) ) );
	}
	$enqueued = true;
}

/**
 * Gets the script/style `.min` suffix for minified files.
 *
 * @return string
 */
function mai_get_suffix() {
	static $suffix = null;
	if ( ! is_null( $suffix ) ) {
		return $suffix;
	}
	$debug  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	$suffix = $debug ? '' : '.min';
	return $suffix;
}
