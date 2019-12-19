<?php
$db = new SQLite3('stations.db');
$station = $_GET['stn'];
$stm = $db->prepare('SELECT * FROM stations WHERE name = :stn');
$stm->bindValue(':stn', $station);
$res = $stm->execute();

$row = $res->fetchArray(SQLITE3_NUM);
echo $row[3];
?>
