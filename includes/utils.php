<?php
	$keyMD5 = "huebr";

	function makeMD5($string) {
		return hash_hmac ("md5", $string, $GLOBALS['keyMD5']);
	}

    function getConnection() {
        $dbhost="localhost";
        $dbuser="root";
        $dbpass="";
        $dbname="csc309";
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
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

	function doLogin($email, $password, $app) {
	    $sql = "SELECT * FROM User WHERE email=:email AND password=:password";
	    try {
	        $db = getConnection();
	        $stmt = $db->prepare($sql);
	        $stmt->bindParam("email", $email);
	        $stmt->bindParam("password", makeMD5($password));
	        $stmt->execute();
	        $user = $stmt->fetch(PDO::FETCH_ASSOC);
	        $db = null;
	        if ($user) {
	        	session_start();
	        	$_SESSION["userID"] = $user['idUser'];
	        	$_SESSION["userName"] = $user['name'];
	            $_SESSION["email"] = $email;
	            $_SESSION["gender"] = $user['gender'];
	            $_SESSION["birthday"] = $user['birthdate'];
	            $app->redirect("/csc309hue/");
	        }
	        else
	            $app->render('login.php', array('appName' => $app->getName(), 'error' => 'Invalid Email and/or Password.', 'email' => $email));
	    } catch(PDOException $e) {
	        $app->render('login.php', array('appName' => $app->getName(), 'error' => 'Something went wrong. Try again.', 'email' => $email));
	    }
	}

	function getUserById($idUser)
	{
		$sql = "SELECT * FROM User WHERE idUser=:idUser";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return $user;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function getUserRate($idUser)
	{
		$sql = "SELECT FLOOR(avg(rating)) as r FROM UserRating WHERE idUserRated=:idUserRated";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUserRated", $idUser);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user && $user['r']) {
                return $user['r'];
            }
            return 0;
        } catch(PDOException $e) {
            return 0;
        }
	}

	function canRateUser($idUser, $idUserRated)
	{
		return true;
	}

	function rateUser($idUser, $idUserRated, $rating)
	{
		
        try {
            $db = getConnection();

            $sql = "DELETE FROM UserRating WHERE idUser=:idUser AND idUserRated=:idUserRated";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idUser" => $idUser,
                        ":idUserRated" => $idUserRated));

            $sql = "INSERT INTO UserRating(idUser, idUserRated, rating) VALUES(:idUser, :idUserRated, :rating)";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idUser" => $idUser,
                        ":idUserRated" => $idUserRated,
                        ":rating" => $rating));

            return true;
            
        } catch(PDOException $e) {
            return false;
        }
	}

	//Coworking Space

	function searchSpacesByQuery($query) {
	    $db = getConnection();
	    $sql = "SELECT c.* FROM CoworkingSpace c, User u WHERE u.idUser = c.idOwner and (c.description LIKE :query OR c.name LIKE :query OR u.name LIKE :query)  ORDER BY c.name";
	    $stmt = $db->prepare($sql);
		$stmt->execute(array(":query" => '%'.$query.'%'));
 		return $stmt->fetchAll();
	}

	function getSpacesByMember($idUser) {
	    $db = getConnection();
	    $sql = "SELECT c.* FROM CoworkingSpace c, Tenant t WHERE t.idUser=:idUser and c.idSpace = t.idSpace and t.approved='t' ORDER BY c.name";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idUser", $idUser);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getSpacesByOwner($idOwner) {
		$db = getConnection();
	    $sql = "SELECT * FROM CoworkingSpace WHERE idOwner=:idOwner ORDER BY name";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idOwner", $idOwner);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getLastInsertedSpaceByOwner($idOwner)
	{
		$sql = "SELECT idSpace FROM CoworkingSpace WHERE idOwner=:idOwner ORDER BY idSpace DESC LIMIT 1";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idOwner", $idOwner);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return $user['idSpace'];
            }
            return 0;
        } catch(PDOException $e) {
            return 0;
        }
	}

	function addPhotoToSpace($photo, $idSpace)
	{
		$sql = "INSERT INTO Photo(idSpace, url) VALUES(:idSpace, :url)";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idSpace" => $idSpace,
                        ":url" => $photo));
            return true;
        } catch(PDOException $e) {
            return false;
        }
	}

	function addLeaseToSpace($lease, $idSpace)
	{
		$sql = "UPDATE CoworkingSpace SET leaseAgreement=:lease WHERE idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idSpace" => $idSpace,
                        ":lease" => $lease));
            return true;
        } catch(PDOException $e) {
            return false;
        }
	}
?>
