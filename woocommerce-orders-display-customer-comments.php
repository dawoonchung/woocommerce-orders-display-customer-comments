<?php
/*
Plugin Name: WooCommerce Display Customer Comments
Description: A simple plugin that displays order notes input by customers.
Version: 1.0.0
Author: Da-Woon Chung
Author URI: https://dawoonchung.com/
License: MIT
Text Domain: woocommerce-orders-display-customer-comments
 */

function add_customer_comments_column( $columns ) {
	$reordered_columns = array();

	// Inserting columns to a specific location
	foreach ( $columns as $key => $column ) {
		$reordered_columns[ $key ] = $column;
	}

	$reordered_columns[ 'order_notes' ] = esc_html( 'Customer Notes' );

	return $reordered_columns;
}
add_filter( 'manage_edit-shop_order_columns', 'add_customer_comments_column', 20 );

function add_customer_comments_column_content( $column ) {
	global $post, $the_order;
	$order_id = $the_order->id;

	$args = array(
		'order_id' => $order_id,
		'limit'    => 1,
	);


	if ( $column === 'order_notes' ) {
		$note = $the_order->get_customer_note();
		echo esc_html( $note );
	}
}
add_action( 'manage_shop_order_posts_custom_column', 'add_customer_comments_column_content' );

function add_customer_comments_column_style( $hook  ) {
	if ( 'edit.php' !== $hook ) {
		return;
	}

	wp_register_style( 'customer_comments_column', plugins_url( 'style.css', __FILE__ ), false, '1.0.0' );
	wp_enqueue_style( 'customer_comments_column' );
}
add_action( 'admin_enqueue_scripts', 'add_customer_comments_column_style' );
