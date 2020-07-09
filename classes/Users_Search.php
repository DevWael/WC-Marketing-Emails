<?php
namespace WCME;

class Users_Search {

	/**
	 * Search for customers (for autocomplete search)
	 */
	public function SearchUsers() {
		if ( isset( $_GET['search'] ) ) {
			$args       = array(
				'search'         => '*' . \esc_attr( $_GET['search'] ) . '*',
				'search_columns' => array(
					'ID',
					'user_login',
					'user_email',
					'user_url',
					'user_nicename'
				)
			);
			$user_data  = [];
			$user_query = new \WP_User_Query( $args );
			if ( ! empty( $user_query->get_results() ) ) {
				foreach ( $user_query->get_results() as $user ) {
					$user_data['results'][] = [
						'id'   => $user->ID,
						'text' => $user->user_email,
					];
				}
			}

			wp_send_json( $user_data );
		}
	}

}