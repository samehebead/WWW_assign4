<script src="https://www.gstatic.com/charts/loader.js"></script>
<?php
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error){
    echo "ERROR CONNECTING";
    die($conn->connect_error);
  }
  $query  = "SELECT category,count(*) as cnt FROM classics group by category";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);
  $rows = $result->num_rows;
  //$rows = $row['cnt'];
  echo '<div id="piechart"></div>';
  echo <<<_END
	<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
	  var data = google.visualization.arrayToDataTable([
	  ['Category', 'Percentage'],
_END;
	for ($j = 0 ; $j < $rows ; ++$j){
	    $result->data_seek($j);
	    $row = $result->fetch_array(MYSQLI_NUM);
	    $cnt = $row[1];
	    echo <<<_END
		  ['$row[0]', $cnt],
_END;
		}
	echo <<<_END
	]);
	  var options = {'title':'Publications', 'width':550, 'height':550};
	  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	  chart.draw(data, options);
	}
	</script>
_END;
  $result->close();
  $conn->close();
?>