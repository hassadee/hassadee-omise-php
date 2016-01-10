<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Hassadee Omise PHP form example</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, nofollow">
        <link rel="icon" href="./assets/img/ico/fav.ico" type="image/x-icon">
        <link rel="shortcut icon" href="./assets/img/ico/fav.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="./assets/css/main.css">
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	</head>
	<body>
		<div class="container">
			<article class="payment-form-container">
				<header class="payment-form-header">
					<section class="payment-form-headline-container">
						<h1 class="payment-form-headline-text">
							Payment<br>
							Details
						</h1>
					</section>
					<section class="payment-form-logo-container">
						<img src="./assets/img/omise-logo.png" class="payment-form-logo-image">
					</section>
				</header>
				<section class="payment-form">
					<form action="checkout.php" method="post" id="checkout" class="form">
						<!-- Error response from Omise, using Bootstrap style. -->
						<div id="token_errors"></div>

						<input type="hidden" name="omise_token">
						<input type="hidden" name="amount" value="2000">
						<input type="hidden" name="description" value="System Testing">

						<div class="payment-form-group">
							<label for="holder_name" class="payment-form-label">
								Cardholder Name
							</label>
							<div class="payment-form-input-addon">
								<img src="./assets/img/glyphicons-user.png" class="payment-form-input-addon-icon">
							</div>
							<input type="text" data-omise="holder_name" placeholder="Cardholder Name" class="form-control payment-form-input" required>
						</div>

						<div class="payment-form-group">
							<label for="number" class="payment-form-label">Card Number</label>
							<div class="payment-form-input-addon">
								<img src="./assets/img/glyphicons-credit-card.png" class="payment-form-input-addon-icon">
							</div>
							<input type="text" data-omise="number" placeholder="Card Number" class="form-control payment-form-input" required>
						</div>

						<div class="payment-form-group">
							<div class="payment-form-expiry">
								<label class="payment-form-label">Expiry Date</label>
								<div class="payment-form-input-addon">
									<img src="./assets/img/glyphicons-calendar.png" class="payment-form-input-addon-icon">
								</div>
								<input type="text" data-omise="expiration_month" size="4" class="form-control payment-form-input payment-form-input-expiry-month" placeholder="mm" required>
								<input type="text" data-omise="expiration_year" size="8" class="form-control payment-form-input payment-form-input-expiry-year" placeholder="yy" required>
							</div>

							<div class="payment-form-security-code">
								<label for="security_code" class="payment-form-label">
									Security Code
								</label>
								<div class="payment-form-input-addon">
									<img src="./assets/img/glyphicons-lock.png" class="payment-form-input-addon-icon">
								</div>
								<input type="password" data-omise="security_code" size="8" placeholder="CCV" class="form-control payment-form-input" required>
							</div>
						</div>

						<!-- <button type="submit" id="create_token" class="btn payment-form-button">Pay ฿20.00</button> -->

						<input type="submit" id="create_token" class="btn payment-form-button" value="Pay ฿20.00">
					</form>
				</section>
			</article>

			<div class="container">
				<div id="response"></div>
			</div>

			<div class="container">
				<div class="remark">
					<ul class="remark-list">
						<li class="remark-list-item">
							Omise icon is trademark of Omise and the use of this trademark does not indicate endorsement of the trademark holder by Omise, nor vice versa.
						</li>
						<li class="remark-list-item">
							Glyphicons icons are free license under the Creative Commons Attribution 3.0 Unported (CC BY 3.0). 
						</li>
						<li class="remark-list-item">
							&copy; <a href="http://hassadee.com" target="_blank">Hassadee Pimsuwan</a> under MIT license / <a href="http://twitter.com/hapztron" target="_blank">@hapztron</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="./assets/js/bootstrap.min.js" charset="utf-8"></script>
		
		<script src="https://cdn.omise.co/omise.js"></script>
		<script>
			Omise.setPublicKey("");

			$("#checkout").submit(function () {
				var form = $(this);
				form.find("input[type=submit]").prop("disabled", true);
				
				var card = {
					"name": form.find("[data-omise=holder_name]").val(),
					"number": form.find("[data-omise=number]").val(),
					"expiration_month": form.find("[data-omise=expiration_month]").val(),
					"expiration_year": form.find("[data-omise=expiration_year]").val(),
					"security_code": form.find("[data-omise=security_code]").val()
				};

				Omise.createToken("card", card, function (statusCode, response) {
					if (response.object == "token") {
						// $("#response").html("Response ID: "+response.id+"<br>Security Code Check: "+response.card['security_code_check']+"Livemode: "+response.livemode);
						if (response.card['security_code_check'] == false) {
							$("#token_errors").html("<div class=\"alert alert-danger\" role=\"alert\">Invalid Security Code (CCV).</div>");
							form.find("input[type=submit]").prop("disabled", false);
						}
					} else {
						// $("#response").html(response.code+": "+response.message);
						form.find("input[type=submit]").prop("disabled", false);
					};

					if (response.object == "error") {
						$("#token_errors").html("<div class=\"alert alert-danger\" role=\"alert\">" + response.message + "</div>");
						form.find("input[type=submit]").prop("disabled", false);
					} else {
						if (response.card['security_code_check'] == true) {
							form.find("[name=omise_token]").val(response.id);
							// Remove card number from form before submiting to server.
							form.find("[data-omise=number]").val("");
							form.find("[data-omise=security_code]").val("");
							form.get(0).submit();
						}
					};
				});
				return false;
			});
		</script>
	</body>
</html>
