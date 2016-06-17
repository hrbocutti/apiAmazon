<?php

$feedType = $_POST['feedType'];


define("FEED_TYPE", $feedType);

$feed = $_POST['xml'];

echo "Feed Type -> " . FEED_TYPE ."<br>";

echo "Feed XML -> <br>" . $feed . "<br> <- FIM";


?>
