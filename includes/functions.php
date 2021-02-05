<?php

function mai_get_accordion( $args ) {
	$accordion = new Mai_Accordion( $args );
	return $accordion->get();
}

function mai_get_accordion_item( $args ) {
	$accordion = new Mai_Accordion_Item( $args );
	return $accordion->get();
}

function mai_enqueue_accordion_styles() {
	static $enqueued = false;
	if ( $enqueued ) {
		return;
	}
	wp_enqueue_style( 'mai-accordion', MAI_ACCORDION_PLUGIN_URL . 'assets/mai-accordion.css', [], MAI_ACCORDION_VERSION . '.' . date( 'njYHi', filemtime( MAI_ACCORDION_PLUGIN_DIR . 'assets/mai-accordion.css' ) ) );
	$enqueued = true;
}
