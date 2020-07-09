<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/DevWael
 * @since      1.0.0
 *
 * @package    Wcme
 * @subpackage Wcme/admin/partials
 */
defined( 'ABSPATH' ) || exit; //prevent direct file access.
global $action;
?>

<h2><?php esc_html_e( 'Marketing Mails', 'wsd' ); ?></h2>

<section class="wsd-mailer-section">
    <form action="<?php echo admin_url( 'admin-post.php' ) ?>" method="post">
		<?php wp_nonce_field( 'customer-mailer', 'mailer' ); ?>
        <input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
        <table class="form-table">
            <tr>
                <th>
                    <label for="sender_mail">
                        <strong>
							<?php esc_html_e( 'Sender Mail', 'wsd' ); ?>
                        </strong>
                    </label>
                </th>
                <td>
                    <input type="email" name="sender_mail" id="sender_mail"
                           value="<?php echo esc_attr( get_option( 'admin_email' ) ) ?>"
                           placeholder="<?php esc_attr_e( 'Add Sender Mail', 'wsd' ); ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mail_subject">
                        <strong>
							<?php esc_html_e( 'Subject', 'wsd' ); ?>
                        </strong>
                    </label>
                </th>
                <td>
                    <input type="text" name="mail_subject" id="mail_subject"
                           placeholder="<?php esc_attr_e( 'Add Email Subject', 'wsd' ); ?>" required>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="customer_type">
                        <strong>
							<?php esc_html_e( 'Customers Type', 'wsd' ); ?>
                        </strong>
                    </label>
                </th>
                <td>
                    <select name="customer_type" id="customer_type" class="wsd_customer_type" required>
                        <option value=""><?php esc_html_e( 'Select...', 'wsd' ); ?></option>
                        <option value="all"><?php esc_html_e( 'All', 'wsd' ); ?></option>
                        <option value="lefted"><?php esc_html_e( 'Lefted Cart', 'wsd' ); ?></option>
                        <option value="bought"><?php esc_html_e( 'Bought A product before', 'wsd' ); ?></option>
                        <option value="individual"><?php esc_html_e( 'Select Customers', 'wsd' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="customers_select">
                        <strong>
							<?php esc_html_e( 'Select Customers', 'wsd' ); ?>
                        </strong>
                    </label>
                </th>
                <td>
                    <div class="customers-select">
                        <select name="customers[]" class="wsd-customers-select" id="customers_select" multiple></select>
                        <div class="overlay"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mail_content">
                        <strong>
							<?php esc_html_e( 'Email Content', 'wsd' ); ?>
                        </strong>
                    </label>
                </th>
                <td>
					<?php
					wp_editor( '', 'mail_content', array(
						'media_buttons' => true,
						'editor_height' => 300, // In pixels, takes precedence and has no default value
						'textarea_rows' => 20,  // Has no visible effect if editor_height is set, default is 20
					) );
					?>
                </td>
            </tr>
        </table>
        <button type="submit" class="wsd-button-field">
			<?php esc_html_e( 'Send', 'wsd' ); ?>
        </button>
    </form>
</section>
