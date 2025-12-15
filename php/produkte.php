<?php
	require 'include/connectcon.php';
	$stmt = $conPDO->query('SELECT * FROM artikel');
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<table cellpadding="2" cellspacing="2" border="0">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Beschreibung</th>
		<th>Größe</th>
		<th>Preis</th>
	<?php foreach ($result as $product) { ?>
		<tr>
			<td><?php echo $product->id; ?></td>
			<td><?php echo $product->name; ?></td>
			<td><?php echo $product->beschreibung; ?></td>
			<td><?php echo $product->groesse; ?></td>
			<td><?php echo $product->preis; ?></td>
			<td><a href="cart.php?id=<?php echo $product->id; ?>">In den Warenkorb</a></td>
		</tr>
	<?php } ?>
</table>