<html>
<head>
	<title>Cheaterlist</title>
</head>
<body>
<?php
$files = glob("*.html");

foreach($files as $fn){
	$title = str_replace('.html', '', $fn);
	echo "<a href='#{$title}'>{$title}</a><br>";
}

foreach($files as $fn){
	$title = str_replace('.html', '', $fn);
	echo "<h2><a name='{$title}'>{$title}</a></h2>";
	echo "<div id='{$title}'>";
	echo file_get_contents($fn);
	echo "</div>";
}
?>
</body></html>
