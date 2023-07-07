<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<h2>Investeerimiskalkulaator</h2>
	<form method="POST" action="">
		<label for="name">Aktsia nimi:</label>
                <input type="text" name="name" id="name" required><br>

		<label for="price">Hind:</label>
		<input type="number" step="0.01" name="price" id="price" required><br>

		<label for="amount">Kogus:</label>
                <input type="number" name="amount" id="amount" required><br>

		<label for="yield">Tootlus (%):</label>
		<input type="number" step="0.01" name="yield" id="yield" required><br>

		<label for="years">Periood:</label>
		<input type="number" name="years" id="years" required><br>

		<input type="submit" name="submit" value="Calculate">
	</form>

	<?php
	if(isset($_POST['submit'])){
		$name = $_POST['name'];
		$price = $_POST['price'];
		$amount = $_POST['amount'];
		$yield = $_POST['yield'] / 100;
		$years = $_POST['years'];

		echo "<h2>'$name' aktsia potensiaalne vaartus</h2>";
		echo "<table border='3'>";
		echo "<tr><th>Periood</th><th>Vaartus</th><th>Muutus (vorreldes eelmise aastaga)</th><th>Muutus (vorreldes esimese aastaga)</th></tr>";

		$value_def = $price * $amount;
		$value = $price * $amount;
		$value_prev = $value;
		$change_prev = 0;
		$change_first = 0;
		$year1 = "0";
		echo "<tr><td>".$year1."</td><td>".number_format($value, 2)."€</td><td>".number_format($change_prev, 2)."€</td><td>".number_format($change_first, 2)."€</td></tr>";
		for ($i=1; $i<=$years; $i++) {
			$value += $value * $yield;
			$change_prev = $value - $value_prev;
			$change_first = abs($value - $value_def);
			$value_prev = $value;
			echo "<tr><td>".$i."</td><td>".number_format($value, 2)."€</td><td>".number_format($change_prev, 2)."€</td><td>".number_format($change_first, 2)."€</td></tr>";
		}

		echo "</table>";
	}
	?>

	<div id="curve_chart" style="width: 900px; height: 500px"></div>
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                                ['Description', 'Value'],
                                ['Sissemakse', <?php echo number_format($price * $amount, 2); ?>],
                                ['Tootlus', <?php echo number_format($value - $price * $amount, 2); ?>]
                        ]);

                        var options = {
                                title: '<?php echo $name; ?> aktsia potentsiaalne väärtus',
                                curveType: 'funcion',
                                legend: { position: 'bottom'}
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                        chart.draw(data, options);
                }
        </script>


</body>
</html>


