<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Gets an accordion.
 *
 * @since 0.1.0
 *
 * @param array $args The accordion args.
 *
 * @return string
 */
function mai_get_accordion( $args ) {
	$accordion = new Mai_Accordion( $args );
	return $accordion->get();
}

/**
 * Gets an accordion item.
 *
 * @since 0.1.0
 *
 * @param array $args The accordion args.
 *
 * @return string
 */
function mai_get_accordion_item( $args ) {
	$accordion = new Mai_Accordion_Item( $args );
	return $accordion->get();
}

/**
 * Gets FAQ schema.
 * Optionally add new schema to the static variable.
 *
 * @access private
 *
 * @since TBD
 *
 * @param array $faq Array with first item the question and second the answer.
 *
 * @return array
 */
function mai_get_accordion_faq_schema( $faq = [] ) {
	static $cache = [];

	if ( $faq ) {
		$cache[] = $faq;
	}

	return $cache;
}
