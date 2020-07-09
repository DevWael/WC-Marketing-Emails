<?php


namespace WCME;


class Customer_data {

	/**
	 * List All Customers
	 * @return array|bool
	 */
	public function GetAllCustomers() {
		$args       = array(
			'number' => - 1,
		);
		$user_data  = [];
		$user_query = new \WP_User_Query( $args );
		if ( ! empty( $user_query->get_results() ) ) {
			foreach ( $user_query->get_results() as $user ) {
				$user_data[] = [
					'id'    => $user->ID,
					'email' => $user->user_email,
				];
			}

			return $user_data;
		}

		return false;
	}

	/**
	 * List customers who have some products in their cart but didn't complete the checkout
	 * @return array|bool
	 */
	public function GetCustomersWithCart() {
		$args       = array(
			'number'   => - 1,
			'meta_key' => '_woocommerce_persistent_cart_' . get_current_blog_id()
		);
		$user_data  = [];
		$user_query = new \WP_User_Query( $args );
		if ( ! empty( $user_query->get_results() ) ) {
			foreach ( $user_query->get_results() as $user ) {
				$user_data[] = [
					'id'    => $user->ID,
					'email' => $user->user_email,
				];
			}

			return $user_data;
		}

		return false;
	}


	/**
	 * List all customers IDs who have ordered at least one order before
	 * @return array
	 */
	public function GetCustomersIDsWithOrders() {
		global $wpdb;
		$customer_ids = $wpdb->get_col( "SELECT DISTINCT meta_value  FROM $wpdb->postmeta
        WHERE meta_key = '_customer_user' 
        AND meta_value > 0" );

		return $customer_ids;
	}


	/**
	 * List all customers ID and Email who have ordered at least one order before
	 * @return array|bool
	 */
	public function GetCustomersWithOrders() {
		$users_data    = [];
		$customers_ids = $this->GetCustomersIDsWithOrders();
		if ( $customers_ids ) {
			foreach ( $customers_ids as $customers_id ) {
				$user         = get_user_by( 'ID', $customers_id );
				$users_data[] = [
					'id'    => $user->ID,
					'email' => $user->user_email,
				];
			}

			return $users_data;
		}

		return false;
	}

}