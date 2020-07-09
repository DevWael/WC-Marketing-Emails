<?php


namespace WCME;


class Notices {
	public static function error_notice( $message ) {
		?>
        <div id="message" class="error notice-error is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
		<?php
	}

	public static function success_notice( $message ) {
		?>
        <div id="message" class="updated notice-success is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
		<?php
	}
}