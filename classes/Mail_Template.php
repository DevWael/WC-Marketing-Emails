<?php

namespace WCME;

defined( 'ABSPATH' ) || exit; //prevent direct file access.

class Mail_Template {

	/**
	 * Hooked into woocommerce_locate_template, this function will load in the archive-product.php template included
	 * with this plugin. If the theme has its own archive-product.php, that will be loaded.
	 *
	 * @param string $template
	 * @param string $template_name
	 * @param string $template_path
	 *
	 * @return void
	 */
	public function marketing_mail_template( $template, $template_name, $template_path ) {
		if ( 'mail-template-body.php' !== $template_name || WC_TEMPLATE_DEBUG_MODE ) {
			return $template;
		}

		if ( ! $template_path ) {
			$template_path = WC()->template_path();
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get our template
		if ( ! $template ) {
			$template = WCME_DIR . 'admin/partials/' . $template_name;
		}

		return $template;
	}
}