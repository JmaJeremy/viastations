<?php

$db = new SQLite3('stations.db');

$res = $db->query('SELECT * FROM stations order by code');

while($row = $res->fetchArray()) {
	echo "<option value=\"{$row['name']}\">{$row['name']}</option>\n";
}
?>
