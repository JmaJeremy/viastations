<?php
header('Content-Type: text/xml');
echo(file_get_contents('https://tsimobile.viarail.ca/data/StationSchedule.xml'));
?>
