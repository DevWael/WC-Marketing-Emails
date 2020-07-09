<?php

namespace WCME;
defined( 'ABSPATH' ) || exit; //prevent direct file access.

class Customers_Mailer {

	protected $mail_template;
	protected $default_subject;
	protected $default_sender;
	protected $mailer;
	protected $content;
	protected $action;
	protected $customer_data;

	public function __construct( $action ) {
		$this->mail_template   = 'mail-template-body.php';
		$this->default_subject = \get_bloginfo( 'name' );
		$this->default_sender  = \get_option( 'admin_email' );
		$this->action          = $action;
		$this->customer_data   = new Customer_data();
	}

	/**
	 * Create email body structure
	 *
	 * @param bool $heading The heading of the email
	 * @param string $content The content of the email
	 *
	 * @return string The email body with styles
	 */
	public function EmailMessageContent( $heading = false, $content = '' ) {
		return \wc_get_template_html(
			$this->mail_template,
			array(
				'order'         => '',
				'email_heading' => $heading,
				'sent_to_admin' => true,
				'plain_text'    => false,
				'mail_content'  => $content,
				'email'         => $this->mailer
			)
		);
	}

	/**
	 * This function processes the task wsd_send_marketing_mail_task to send emails to users with the following parameters
	 *
	 * @param $send_to string mail to send to
	 * @param $subject string subject of the email
	 * @param $content string the email content
	 * @param $headers string email headers
	 */
	public function SendMail( $send_to, $subject, $content, $headers = '' ) {
		$this->mailer = \WC()->mailer();
		$message      = $this->EmailMessageContent( $subject, $content );
		$this->mailer->send( $send_to, $subject, $message, $headers );
	}

	/**
	 * Receive POST requests from site admin and create tasks for sending emails to selected type of customers
	 */
	public function SendMailRequest() {
		if ( ! current_user_can( 'manage_options' ) ) {
			\wp_redirect( admin_url( 'admin.php?page=wsd_admin_welcome' ) ); //not allowed, go to admin home page
			exit();
		}

		if ( isset( $_POST['mailer'] ) && ! empty( $_POST['mailer'] ) ) {
			if ( ! wp_verify_nonce( $_POST['mailer'], 'customer-mailer' ) ) {
				\wp_redirect( admin_url( 'admin.php?page=wsd-customer-mailer&notice=error&code=1' ) ); //nonce is not found or invalid
				exit();
			}
		} else {
			\wp_redirect( admin_url( 'admin.php?page=wsd-customer-mailer&notice=error&code=1' ) ); //nonce is not found or invalid
			exit();
		}

		$sender_mail   = '';
		$mail_subject  = '';
		$mail_content  = '';
		$customer_type = '';
		$allowed_tags  = Helpers::allowed_html_tags();

		if ( isset( $_POST['sender_mail'] ) && \is_email( $_POST['sender_mail'] ) ) {
			$sender_mail = \sanitize_email( $_POST['sender_mail'] );
			$headers     = 'MIME-Version: 1.0' . "\r\n";
			$headers     .= 'Content-type: text/html' . "\r\n";
			$headers     .= 'From: ' . \get_bloginfo( 'name' ) . ' <' . $sender_mail . '>' . "\r\n";
			$headers     .= 'Reply-To: ' . $sender_mail;
		} else {
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html' . "\r\n";
		}

		if ( isset( $_POST['mail_subject'] ) && ! empty( $_POST['mail_subject'] ) ) {
			$mail_subject = \sanitize_text_field( $_POST['mail_subject'] );
		} else {
			\wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=error&code=2' ) ); //subject is required
			exit();
		}

		if ( isset( $_POST['customer_type'] ) ) {
			$customer_type = \sanitize_text_field( $_POST['customer_type'] );
		} else {
			\wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=error&code=3' ) ); //customer type is required
			exit();
		}

		if ( isset( $_POST['mail_content'] ) ) {
			$mail_content = \wp_kses( $_POST['mail_content'], $allowed_tags );
		}

		switch ( $customer_type ) {
			case 'all':
				$customers = $this->customer_data->GetAllCustomers();
				if ( $customers && ! empty( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( isset( $customer['email'] ) ) {
							\wp_schedule_single_event( time(), 'wsd_send_marketing_mail_task', array(
								$customer['email'],
								$mail_subject,
								$mail_content,
								$headers
							) );
						}
					}
				}
				//Success: redirect to the marketing page with success notice
				\wp_redirect( admin_url( 'admin.php?page=wsd-customer-mailer&notice=success&code=1' ) );
				exit();
				break;
			case 'lefted': //customers who have some products in their cart but didn't complete the checkout
				$customers = $this->customer_data->GetCustomersWithCart();
				if ( $customers && ! empty( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( isset( $customer['email'] ) ) {
							\wp_schedule_single_event( time(), 'wsd_send_marketing_mail_task', array(
								$customer['email'],
								$mail_subject,
								$mail_content,
								$headers
							) );
						}
					}
				}
				//Success: redirect to the marketing page with success notice
				\wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=success&code=1' ) );
				exit();
				break;
			case 'bought':
				$customers = $this->customer_data->GetCustomersWithOrders(); //list all customers how have at least one order in their history
				if ( $customers && ! empty( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( isset( $customer['email'] ) ) {
							\wp_schedule_single_event( time(), 'wsd_send_marketing_mail_task', array(
								$customer['email'],
								$mail_subject,
								$mail_content,
								$headers
							) );
						}
					}
				}
				//Success: redirect to the marketing page with success notice
				\wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=success&code=1' ) );
				exit();
				break;
			case 'individual':
				if ( isset( $_POST['customers'] ) && ! empty( $_POST['customers'] ) && is_array( $_POST['customers'] ) ) {
					$customers = $_POST['customers'];
					foreach ( $customers as $customer ) {
						$user = \get_user_by( 'ID', $customer );
						if ( $user ) {
							\wp_schedule_single_event( time(), 'wsd_send_marketing_mail_task', array(
								$user->user_email,
								$mail_subject,
								$mail_content,
								$headers
							) );
						}
					}
				}
				//Success: redirect to the marketing page with success notice
				wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=success&code=1' ) );
				exit();
				break;
		}

		//Error: redirect to the marketing page with error notice "wrong customer type"
		wp_redirect( \admin_url( 'admin.php?page=wsd-customer-mailer&notice=error&code=4' ) );
		exit();
	}

	/**
	 * Display Error and Success Notices
	 */
	public function notices() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'wsd-customer-mailer' ) {
			$code = $notice = '';
			if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {
				$code = $_GET['code'];
			}
			if ( ! $code ) {
				return;
			}
			if ( isset( $_GET['notice'] ) && ! empty( $_GET['notice'] ) ) {
				$notice = $_GET['notice'];
				if ( $notice === 'error' ) {
					switch ( $code ) {
						case 1:
							Notices::error_notice( \esc_html__( 'The URL you are following may be expired, please try again.', 'wsd' ) );
							break;
						case 2:
							Notices::error_notice( \esc_html__( 'Subject is required.', 'wsd' ) );
							break;
						case 3:
							Notices::error_notice( \esc_html__( 'Customer Type is required.', 'wsd' ) );
							break;
						case 4:
							Notices::error_notice( \esc_html__( 'Invalid Customer Type.', 'wsd' ) );
							break;
					}
				} else {
					switch ( $code ) {
						case 1:
							Notices::success_notice( \esc_html__( 'Email Queued for Delivery Successfully.', 'wsd' ) );
							break;
					}
				}
			}
		}
	}
}