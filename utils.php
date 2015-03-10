<?php
	$keyMD5 = "huebr";
	function makeMD5($string) {
		return hash_hmac ("md5", $string, $keyMD5);
	}
?>
