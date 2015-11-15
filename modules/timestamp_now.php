<?php 
/*Getting unix timestamp*/
	$date = date_create();
	echo date_format($date, 'U = Y-m-d H:i:s')."--------------------------";
	date_timestamp_set($date, date_timestamp_get($date));
	echo date_format($date, 'U = Y-m-d H:i:s');
?>