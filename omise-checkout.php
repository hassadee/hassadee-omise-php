<?php
	require_once dirname(__FILE__).'/omise-php/lib/Omise.php';
	// This example using Omise API version 2014-07-27
	define('OMISE_API_VERSION', '2014-07-27');

	// Your Omise public and secret keys can get from your Omise test/live dashboards.
	define('OMISE_PUBLIC_KEY', '');
	define('OMISE_SECRET_KEY', '');

	$token = $_POST['omise_token'];
	$checkout_amount = $_POST['amount'];
	$checkout_description = $_POST['description'];

	try {
		$charge = OmiseCharge::create(array(
			'livemode' => 'false',
			'amount' => $checkout_amount,
			'currency' => 'thb',
			'card' => $token,
			'description' => $checkout_description	
		));
	} catch (Exception $e) {
		echo $e;
	}
?>
