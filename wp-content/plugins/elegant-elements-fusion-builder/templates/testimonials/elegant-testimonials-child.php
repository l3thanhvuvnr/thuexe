<?php
$this->testimonials[ $this->testimonials_counter ][] = array(
	'title'           => isset( $args['title'] ) ? $args['title'] : '',
	'title_color'     => isset( $args['title_color'] ) && '' !== $args['title_color'] ? $args['title_color'] : '',
	'sub_title'       => isset( $args['sub_title'] ) ? $args['sub_title'] : '',
	'sub_title_color' => isset( $args['sub_title_color'] ) && '' !== $args['sub_title_color'] ? $args['sub_title_color'] : '',
	'content'         => do_shortcode( $content ),
	'image_url'       => isset( $args['image_url'] ) ? $args['image_url'] : '',
);
