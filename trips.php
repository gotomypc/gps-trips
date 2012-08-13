<?php
date_default_timezone_set('America/Los_Angeles');

require('/home/alan/coding/projects/dbFacile/src/dbFacile_sqlite3.php');

$db = new dbFacile_sqlite3();
$db->open('20120813.sqlite3');


$trips = array();

$rows = $db->fetchAll('select * from locations order by milliseconds');
$current = 0;
$trip = array();
foreach ($rows as $row) {
	$ts = ($row['milliseconds']/1000);
	if ($ts > $current + 180) { // if it's been 3 minutes, start a new trip
		if ($trip) $trips[] = $trip;
		$trip = array();
	}

	$trip[] = array(
		'longitude' => $row['longitude'],
		'latitude' => $row['latitude'],
		'accuracy' => $row['accuracy'],
		'title' => date('Y-m-d H:i:s', $ts)
	);

	$current = $ts;
}

$trips[] = $trip;

//var_dump($out);
file_put_contents('out.json', json_encode($trips));
