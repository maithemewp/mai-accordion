<?php

class Mai_Accordion_Item {
	protected $args;
	protected $block;

	function __construct( $args, $block = false ) {
		$args        = wp_parse_args( $args, $this->get_defaults() );
		$this->args  = $this->get_sanitized_args( $args );
		$this->block = $block;
	}

	function get_defaults() {
		return [
			'title'   => '',
			'content' => '',
			'class'   => '',
		];
	}

	function get_sanitized_args( $args ) {
		$args['title']   = esc_html( $args['title'] );
		$args['content'] = $args['content'];
		$args['class']   = esc_html( $args['class'] );
		return $args;
	}

	function get() {
		if ( ! function_exists( 'mai_get_engine_theme' ) ) {
			return;
		}

		$attributes = [
			'class' => 'mai-accordion-item',
		];

		if ( $this->args['class'] ) {
			$attributes['class'] = mai_add_classes( $this->args['class'], $attributes['class'] );
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
				'atts'    => [
					'class' => 'mai-accordion-summary',
				],
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
				'atts'    => $attributes,
			]
		);
	}
}
