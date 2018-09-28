
<?php
if(!isset($_COOKIE['chunk'])) {
    echo "Cookie named '" . 'chunk' . "' is not set!";
} else {
	$arr = [$_COOKIE['chunk'], $_COOKIE['chunks_lenght']];
	print_r(json_encode($arr));
}
?>