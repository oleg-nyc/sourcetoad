<?php
require_once 'includes/a1.php';
require_once 'includes/a2.php';
require_once 'includes/vars.php';
?>

<table>
	<tr>
		<th align='left'>Key</th>
		<th align='left'>Value</th>
	</tr>
	<tbody>
<?php
	printArr( sortingArr($var, ['last_name','guest_id']) );
?>
	</tbody>
</table>

<h3><a href='./'><< Back</a></h3>
