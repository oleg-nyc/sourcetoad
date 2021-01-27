<?php
session_start();
require_once 'includes/a3.php';

switch( $_POST["_method"] ) {

		case "post":
			$cart = New Cart(false, $_POST);
			break;

		case "put":
			$cart = New Cart($_POST);
			break;

		case "delete":
			$cart = New Cart();
			$cart->deleteItem($_POST['id']);
			break;
		
		default:
			$cart = New Cart;
			break;
	}
?>

<form method="post">
	<input type="hidden" name="_method" value="post" />
	<h3>Customer Info:</h3>
<?php
$cart->printHTML();
?>
	<button type="submit">Apply Changes</button>
</form>
<form method="post">
	<input type="hidden" name="_method" value="put" />
	<h3>New Item:</h3>
<?php
	$cart->Form();
?>
	<button type="submit">Add To Cart</button>
</form>
<h3>Cart:</h3>
<table>
	<tr>
		<th align='center'> ID </th>
		<th align='center'> Name </th>
		<th align='center'> Price, $ </th>
		<th align='center'> Quantity </th>
		<th align='center'>  </th>
	</tr>
	<tbody>
<?php
	$cart->printCart();
?>
	</tbody>
</table>

<h3><a href='./'><< Back</a></h3>
