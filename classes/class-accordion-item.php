<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Mai_Accordion_Item {
	protected $args;
	protected $block;

	/**
	 * Gets it started.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function __construct( $args, $block = false ) {
		$args        = wp_parse_args( $args, $this->get_defaults() );
		$this->args  = $this->get_sanitized_args( $args );
		$this->block = $block;
	}

	/**
	 * Gets defaults.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_defaults() {
		return [
			'preview' => false,
			'open'    => false,
			'title'   => '',
			'content' => '',
			'class'   => '',
		];
	}

	/**
	 * Gets sanitized args.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_sanitized_args( $args ) {
		$args['preview'] = rest_sanitize_boolean( $args['preview'] );
		$args['open']    = rest_sanitize_boolean( $args['open'] );
		$args['title']   = do_shortcode( wp_kses_post( $args['title'] ) );
		$args['content'] = $args['content'];
		$args['class']   = esc_html( $args['class'] );

		return $args;
	}

	/**
	 * Gets the accordion item.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get() {
		if ( ! class_exists( 'Mai_Engine' ) ) {
			return;
		}

		$details_atts = [
			'class' => 'mai-accordion-item',
		];

		$summary_atts = [
			'class' => 'mai-accordion-summary',
		];

		if ( $this->args['open'] ) {
			$details_atts['open'] = true;
		}

		if ( $this->args['class'] ) {
			$summary_atts['class'] = mai_add_classes( $this->args['class'], $summary_atts['class'] );
		}

		if ( ! $this->args['title'] ) {
			$this->args['title'] = $this->args['preview'] ? sprintf( '<span style="color:var(--body-color);font-family:var(--body-font-family);font-weight:var(--body-font-weight);font-size:var(--body-font-size);opacity:0.62;">%s</span>', __( 'Click here to enter title in block sidebar', 'mai-engine' ) ) : '';
		}

		if ( ! $this->block ) {
			$this->args['content'] = mai_get_processed_content( $this->args['content'] );
		}

		$summary = genesis_markup(
			[
				'open'    => '<summary %s>',
				'close'   => '</summary>',
				'context' => 'mai-accordion-item-summary',
				'content' => sprintf( '<span class="mai-accordion-title">%s</span>', $this->args['title'] ),
				'echo'    => false,
				'atts'    => $summary_atts,
			]
		);

		$content = genesis_markup(
			[
				'open'    => '<div %s>',
				'close'   => '</div>',
				'context' => 'mai-accordion-item-content',
				'content' => $this->args['content'],
				'echo'    => false,
				'atts'    => [
					'class' => 'mai-accordion-content',
				]
			]
		);

		return genesis_markup(
			[
				'open'    => '<details %s>',
				'close'   => '</details>',
				'context' => 'mai-accordion-item',
				'content' => $summary . $content,
				'echo'    => false,
				'atts'    => $details_atts,
			]
		);
	}
}
