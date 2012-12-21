<h1>TEST Sandbox</h1>

<?php

	$date = DateTime::createFromFormat('Y-m-d H:i', '2012-12-21 17:40');
	echo $date->format('d/m/Y H:i');

	
?>

{{D($date)}}

{{substr('2012-12-21 12:00:00', 0, -3)}}