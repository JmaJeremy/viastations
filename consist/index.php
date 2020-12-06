<?php
require 'vendor/autoload.php';

function get_station_from_abbr($station) {
	$db = new SQLite3('../stations.db');
	$stm = $db->prepare('SELECT name FROM stations WHERE code = :stn');
	$stm->bindvalue(':stn', $station);
	$res = $stm->execute();

	$row = $res->fetchArray();
	return $row[0];
}
?>
<html>
<head>
<!--
<?php
$secret = trim(file_get_contents('/var/www/elasticapi.secret'));
$defaultHandler = Elasticsearch\ClientBuilder::defaultHandler();
$client = Elasticsearch\ClientBuilder::create()
		->setHandler($defaultHandler)
		->setElasticCloudId('viarail-data:dXMtZWFzdC0xLmF3cy5mb3VuZC5pbyQ4MjJhY2YxNDk0ODc0ZGQwODM0Nzg0ZTIzNmE5MDFhOSQ3MDk3ODYzNjdhZDI0ZjU2YjZlMmM5ZWM0ZDcxYjdkMw==')
		->setApiKey('v8NGoXUBPF3H5Has5s7B', $secret)
		->build();

$params = [
	'index' => 'via-consist-*',
	'body' => [
		'query' => [
			'range' => [
				'sequence_time' => [
					'gte' => 'now-30m'
				]
			]
		],
		'size' => 0,
		'aggs' => [
			'trainNums' => [
				'terms' => [ 
					'field' => 'Train', 
					'size' => 100,
					'order' => [ '_key' => 'asc' ]
				],
				'aggs' => [
					'carNums' => [
						'top_hits' => [
							'sort' => [ [ 'SeqNum' => [ 'order' => 'asc' ] ] ],
							'_source' => [ 'includes' => [ 'CarNum', 'SeqNum', 'Date', 'From', 'FromTime', 'To', 'ToTime', 'sequence_time' ] ],
							'size' => 100
						]
					]
				]
			]
		]
	]
];

$countParams['index'] = 'via-consist-*';
$results = $client->count($countParams);

print_r($results);

$results = $client->search($params);
print_r($results);

?>

-->
 <link rel="stylesheet" href="styles.css">
	<title>VIA Rail Consists</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<meta property="og:url"		content="https://viarail.live/consist" />
	<meta property="og:type"		content="website" />
	<meta property="og:title"		content="VIA Rail Consists" />
	<meta property="og:description"		content="Live locomotive and passenger car consist details for VIA Rail trains in Canada" />
	<meta property="og:image"		content="https://viastation-assets.s3.amazonaws.com/depboard.jpg" />
	<meta property="og:image:width"		content="600" />
	<meta property="og:image:height"		content="450" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
</head>
<body>
<h1>VIA Rail Consists</h1>
<h2>Trains which have reported within last 6 hours</h2>
<h3>All times displayed in local time of departure/destination station respectively</h3>
<h3><a href="/">Click here</a> to view arrival/departure times at any station</h3>
<h3><a href="/consist/historic.php">Click here</a> to view <b class="historiclink">historic</b> data</h3>
<table>
<tr>
<th>Train #</th><th>Total cars</th><th>Dep. Stn.</th><th>Dep. Date</th><th>Dep. Time</th><th>Dest. Stn.</th><th>Dest. Time</th><th>Consist</th>
</tr>
<?php
$trains = $results['aggregations']['trainNums']['buckets'];
$today = date('Y/m/d', strtotime('-5 days'));
//echo $today;
foreach($trains as $train) {
	if($train['carNums']['hits']['hits'][0]['_source']['Date'] < $today) {
		continue;
	}
	echo "<tr>";
	echo "<td>" . $train['key'] . "</td>";
	echo "<td>" . $train['doc_count'] . "</td>";
	echo "<td><abbr title=\"" . get_station_from_abbr($train['carNums']['hits']['hits'][0]['_source']['From']) . "\">" . $train['carNums']['hits']['hits'][0]['_source']['From'] . "</abbr></td>";
	echo "<td>" . $train['carNums']['hits']['hits'][0]['_source']['Date'] . "</td>";
	echo "<td>" . $train['carNums']['hits']['hits'][0]['_source']['FromTime'] . "</td>";
	echo "<td><abbr title=\"" . get_station_from_abbr($train['carNums']['hits']['hits'][0]['_source']['To']) . "\">" . $train['carNums']['hits']['hits'][0]['_source']['To'] . "</abbr></td>";
	echo "<td>" . $train['carNums']['hits']['hits'][0]['_source']['ToTime'] . "</td><td>";
	$last_key = end(array_keys($train['carNums']['hits']['hits']));
	foreach($train['carNums']['hits']['hits'] as $key=>$car) {
		if($car['_source']['Date'] == $train['carNums']['hits']['hits'][0]['_source']['Date']) {
			echo $car['_source']['CarNum'];
			if($key != $last_key) {
				echo ", ";
			}
		}
	}
	echo "</td></tr>";
}

$date = new DateTimeImmutable($results['aggregations']['trainNums']['buckets'][0]['carNums']['hits']['hits'][1]['_source']['sequence_time']);
$mutable = DateTime::createFromImmutable($date);

?>
</table>

	<p><b>Latest update:</b> <?php echo $mutable->format('Y-m-d, H:i:s')  ?> UTC</p>

</body>
</html>
