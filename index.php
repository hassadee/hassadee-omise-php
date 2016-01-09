<!DOCTYPE html>
<html>
	<head>
		<title>Hassadee Omise PHP form example</title>
	</head>
	<body>
		<h1>Omise PHP form example</h1>
		<form action="checkout.php" method="post" id="checkout" class="form">
			<input type="hidden" name="omise_token">

			<div class="form-group">
				<label for="holder_name">Card Holder Name</label>
				<input type="text" data-omise="holder_name" placeholder="Card Holder Name" class="form-control">
			</div>
			<div class="form-group">
				<label for="number">Card Number</label>
				<input type="text" data-omise="number" placeholder="Card Number" class="form-control">
			</div>
			<div class="form-group">
				<label>Card Expiry Date (MM/YY)</label>
				<input type="text" data-omise="expiration_month" size="4" class="form-control"> / 
				<input type="text" data-omise="expiration_year" size="8" class="form-control">
			</div>
			<div class="form-group">
				<label for="security_code">Security Code</label>
				<input type="text" data-omise="security_code" size="8" placeholder="CCV" class="form-control">
			</div>

			<input type="submit" id="create_token" class="btn button-book">
		</form>
		
		<script src="https://cdn.omise.co/omise.js"></script>
		<script>
			$("#checkout").submit(function () {
				var form = $(this);
				form.fund("input[type=submit]").prop("disabled", true);
				var card = {
					"name": form.find("[data-omise=holder_name]").val(),
					"number": form.find("[data-omise=number]").val(),
					"expiration_month": form.find("[data-omise=expiration_month]").val(),
					"expiration_year": form.find("[data-omise=expiration_year]").val(),
					"security_code": form.find("[data-omise=security_code]").val()
				}

				Omise.createToken("card", card, function () {
					if (response.object == "error") {
						$("#token_errors").html(response.message);
						form.find("input[type=submit]").prop("disabled", false);
					} else {
						form.find("[name=omise_token]").val(response.id);
						form.get(0).submit();
					}
				});
				return false;
			});
		</script>
	</body>
</html>
