<?php

namespace WCME;

class Helpers {
	public static function allowed_html_tags() {
		$allowed_tags = array(
			'a'          => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'abbr'       => array(
				'title' => array(),
			),
			'b'          => array(),
			'blockquote' => array(
				'cite' => array(),
			),
			'cite'       => array(
				'title' => array(),
			),
			'code'       => array(),
			'del'        => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'         => array(),
			'div'        => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'         => array(),
			'dt'         => array(),
			'em'         => array(),
			'h1'         => array(),
			'h2'         => array(),
			'h3'         => array(),
			'h4'         => array(),
			'h5'         => array(),
			'h6'         => array(),
			'i'          => array(),
			'img'        => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li'         => array(
				'class' => array(),
			),
			'ol'         => array(
				'class' => array(),
			),
			'p'          => array(
				'class' => array(),
			),
			'q'          => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'       => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike'     => array(),
			'strong'     => array(),
			'ul'         => array(
				'class' => array(),
			),
		);

		return $allowed_tags;
	}
}