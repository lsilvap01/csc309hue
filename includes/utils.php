<?php
	$keyMD5 = "huebr";

	function makeMD5($string) {
		return hash_hmac ("md5", $string, $GLOBALS['keyMD5']);
	}

	function emailExists($email)
	{
		$sql = "SELECT * FROM User WHERE email=:email";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->execute();
            $user = $stmt->fetchObject();
            $db = null;
            if ($user) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}
?>
