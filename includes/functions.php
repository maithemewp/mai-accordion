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
 * Gets schema questions and answers.
 * Optionally add new schema to the static variable.
 *
 * @access private
 *
 * @since TBD
 *
 * @param array $qa Array with first item the question and second the answer.
 *
 * @return array
 */
function mai_get_accordion_schema_qa( $qa = [] ) {
	static $cache = [];

	if ( $qa ) {
		$cache[] = $qa;
	}

	return $cache;
}
