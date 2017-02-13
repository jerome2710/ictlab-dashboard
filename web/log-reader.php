<?php

if (!isset($_GET['OTMKrA539aIcDc2h9jn5kY3rl802ywnw'])) {
	return;
}

$file = file(__DIR__ . '/../app/logs/' . $_GET['env'] . '.log');
for ($i = max(0, count($file)-$_GET['lines']); $i < count($file); $i++) {
	echo $file[$i] . "<br />";
}