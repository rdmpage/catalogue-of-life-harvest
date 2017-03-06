<?php

// proxy for Ajax calls where server dieson't support CORS or JSONP
require_once (dirname(__FILE__) . '/lib.php');

$url = $_GET['url'];

$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
}

$json = get($url);

if ($callback != '')
{
	$json = $callback . '(' . $json . ')';
}

echo $json;


?>