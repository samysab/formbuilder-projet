<?php


defined('ABSPATH') or die('Accès refusé');



echo "<h1> Bienvenue sur le plugin de contact </h1>";

$c = new plgFormBuilder();

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

<table id="myTable" class="display">
	<thead>
		<tr>
			<th>field_1</th>
			<th>field_2</th>
			<th>field_3</th>
			<th>field_4</th>
			<th>field_5</th>
		</tr>
	</thead>
	<tbody>
		<?= $c->display_fields(); ?>
	</tbody>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready( function () {
    	$('#myTable').DataTable();
	});
</script>