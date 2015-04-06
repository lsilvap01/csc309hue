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
        // $dbhost="sql206.byethost22.com";
        // $dbuser="b22_15920833";
        // $dbpass="huebrcsf";
        // $dbname="b22_15920833_csc309";
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }

    function addErrorMessage($errors, $message)
	{
	    if(empty($errors))
	    {
	        return $message;
	    }
	    else
	    {
	        return $errors . "<br/>" . $message;
	    }
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

	function doLogin($email, $password) {
	    $sql = "SELECT * FROM User WHERE email=:email AND password=:password";
	    try {
	        $db = getConnection();
	        $stmt = $db->prepare($sql);
	        $stmt->bindParam("email", $email);
	        $hashPass = makeMD5($password);
	        $stmt->bindParam("password", $hashPass);
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
	            $GLOBALS['app']->redirect($GLOBALS['site_url']);
	        }
	        else
	            $GLOBALS['app']->render('login.php', array('appName' => $GLOBALS['app']->getName(), 'error' => 'Invalid Email and/or Password.', 'email' => $email));
	    } catch(PDOException $e) {
	        $GLOBALS['app']->render('login.php', array('appName' => $GLOBALS['app']->getName(), 'error' => 'Something went wrong. Try again.', 'email' => $email));
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

	function getUserByIdTenant($idTenant)
	{
		$sql = "SELECT idUser FROM Tenant WHERE idTenant=:idTenant";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idTenant", $idTenant);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return getUserById($user['idUser']);
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function getTenantByUserAndSpace($idUser, $idSpace)
	{
		$sql = "SELECT * FROM Tenant WHERE idUser=:idUser AND idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($tenant) {
                return $tenant;
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

	function getSpaceById($idSpace){
		$sql = "SELECT * FROM CoworkingSpace WHERE idSpace = :idSpace";

		try {
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":idSpace", $idSpace);
			$stmt->execute();
			$space = $stmt->fetch();
			$db = null;
			if ($space) {
                return $space;
            }
        	return false;
		} catch(PDOException $e) {
			return false;
		}	
	}
	
	function getSpaceRate($idSpace){
		$sql = "SELECT FLOOR(avg(spaceRating)) as r FROM Tenant WHERE idSpace=:idSpace AND approved='y'";

		try {
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":idSpace", $idSpace);
			$stmt->execute();
			$space = $stmt->fetch();
			$db = null;
			if ($space && $space['r']) {
                return $space['r'];
            }
			return 0;
		} catch(PDOException $e) {
			return 0;
		}	
	}

	function rateSpace($idUser, $idSpace, $rating)
	{
        try {
            $db = getConnection();

            $sql = "UPDATE Tenant SET spaceRating=:rating WHERE idUser=:idUser AND idSpace=:idSpace";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idUser" => $idUser,
                        ":idSpace" => $idSpace,
                        ":rating" => $rating));
            return true;
            
        } catch(PDOException $e) {
            return false;
        }
	}

	function getActiveUserConnections($idUser)
	{
		$db = getConnection();
		$sql = "SELECT * FROM User WHERE idUser in (select distinct(idUser) from tenant where idSpace in (select distinct(idSpace) from tenant where idUser = :idUser and approved = 'y' and endDate IS NULL UNION select distinct(idSpace) from coworkingspace where idOwner = :idUser) and idUser != :idUser and approved = 'y' and endDate is NULL" .
			   " UNION select idOwner from coworkingspace where idSpace in (select distinct(idSpace) from tenant where idUser = :idUser and approved = 'y' and endDate IS NULL))";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idUser", $idUser);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getOldUserConnections($idUser)
	{
		$db = getConnection();
		$sql = "SELECT * FROM User WHERE idUser in" .
			   " (select DISTINCT(r.idUser) FROM (select DISTINCT(t.idUser) as idUser from tenant t, tenant t2 where t2.idUser = 2 and t2.approved = 'y' " . 
			   "and t2.endDate IS NOT NULL and t.idUser != :idUser and t.idSpace = t2.idSpace and t.approved = 'y' and (t.endDate IS NULL OR t.endDate BETWEEN t2.startDate " .
			   "AND t2.endDate) UNION select DISTINCT(t.idUser) as idUser from tenant t WHERE t.idSpace in (select idSpace from coworkingspace where idOwner = :idUser) and t.endDate IS NOT NULL) r " .
               "WHERE r.idUser not in (select distinct(idUser) from tenant where idSpace in (select distinct(idSpace) from tenant where idUser = :idUser and approved = 'y' and endDate IS NULL" .
               " UNION select distinct(idSpace) from coworkingspace where idOwner = :idUser) and idUser != :idUser and approved = 'y' and endDate is NULL)" . 
			   " UNION select idOwner from coworkingspace where idSpace in (select distinct(idSpace) from tenant where idUser = :idUser and approved = 'y' and endDate IS NOT NULL))";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idUser", $idUser);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getAllUserConnections($idUser)
	{
		$old = getOldUserConnections($idUser);
		$active = getActiveUserConnections($idUser);
		if(!is_array($old))
		{
			$old = [];
		}
		
		if(!is_array($active))
		{
			$active = [];
		}
		return array_merge($old, $active);
	}

	function canRateUser($idUser, $idUserRated)
	{
		$connections = getAllUserConnections($idUser);
		$return = false;
		foreach ($connections as $user) {
			$return = intval($user['idUser']) == intval($idUserRated);
		}
		return $return;
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
	    $sql = "SELECT c.* FROM CoworkingSpace c, Tenant t WHERE t.idUser=:idUser and c.idSpace = t.idSpace and t.approved='y' ORDER BY c.name";
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
	
	function getSpaceMembers($idSpace) {
		$db = getConnection();
	    $sql = "SELECT u.* FROM Tenant t, User u WHERE u.idUser=t.idUser AND t.idSpace = :idSpace and t.approved = 'y'";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpace", $idSpace);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getSpaceTeams($idSpace) {
		$db = getConnection();
	    $sql = "SELECT * FROM Team WHERE idSpace = :idSpace";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpace", $idSpace);
		$stmt->execute();
 		return $stmt->fetchAll();
	}

	function getSpaceOwner($idSpace) {
		$db = getConnection();
	    $sql = "SELECT u.* FROM User u, CoworkingSpace c WHERE u.idUser=c.idOwner AND c.idSpace = :idSpace";
	    try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idSpace", $idSpace);
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

	function getSpaceRequests($idSpace) {
		$db = getConnection();
	    $sql = "SELECT u.* FROM Tenant t, User u WHERE u.idUser=t.idUser AND t.idSpace = :idSpace and t.approved = 'n'";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpace", $idSpace);
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

	function userIsMemberOfSpace($idUser, $idSpace)
	{
		$sql = "SELECT * FROM Tenant WHERE idUser=:idUser AND idSpace=:idSpace AND approved='y' LIMIT 1";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($tenant) {
                return true;
            }
            return isOwnerOfSpace($idUser, $idSpace);
        } catch(PDOException $e) {
            return isOwnerOfSpace($idUser, $idSpace);
        }
	}

	function isTenantOfSpace($idTenant, $idSpace)
	{
		$user = getUserByIdTenant($idTenant);
		return userIsMemberOfSpace($user['idUser'], $idSpace);
	}

	function userHasSentRequestToSpace($idUser, $idSpace)
	{
		$sql = "SELECT * FROM Tenant WHERE idUser=:idUser AND idSpace=:idSpace AND approved='n' LIMIT 1";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($tenant) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function isOwnerOfSpace($idUser, $idSpace)
	{
		$sql = "SELECT * FROM CoworkingSpace WHERE idOwner=:idUser AND idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $space = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($space) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function sentRequestToSpace($idUser, $idSpace)
	{
		if(!userHasSentRequestToSpace($idUser, $idSpace) && !userIsMemberOfSpace($idUser, $idSpace))
		{
			$sql = "INSERT INTO Tenant(idUser, idSpace) VALUES(:idUser, :idSpace)";
	        try {
	            $db = getConnection();
	            $stmt = $db->prepare($sql);
	            $stmt->execute(array(":idUser" => $idUser, ":idSpace" => $idSpace));
	            return true;
	        } catch(PDOException $e) {
	            return false;
	        }
	    }
	    return false;
	}

	function aproveRequestToSpace($idUser, $idSpace)
	{
		$sql = "UPDATE Tenant SET approved='y', startDate=now() WHERE idUser=:idUser AND idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idUser" => $idUser, ":idSpace" => $idSpace));
            return true;
        } catch(PDOException $e) {
            return false;
        }
	}

	function rejectRequestToSpace($idUser, $idSpace)
	{
		$sql = "DELETE FROM Tenant WHERE idUser=:idUser AND idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":idUser" => $idUser, ":idSpace" => $idSpace));
            return true;
        } catch(PDOException $e) {
            return false;
        }
	}

	function getPostsBySpace($idSpace) {
	    $db = getConnection();
	    $sql = "SELECT * FROM CWSpacePost WHERE idSpace=:idSpace AND idReplyTo IS NULL ORDER BY idSpacePost DESC";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpace", $idSpace);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$posts = array();
		foreach ($results as $post) {
			$p = array();
			foreach ($post as $key => $value) {
				$p[$key] = $value;
			}
			$p['comments'] = getCommentsBySpacePost($post['idSpacePost']);
			array_push($posts, $p);
		}
 		return $posts;
	}

	function getCommentsBySpacePost($idSpacePost) {
	    $db = getConnection();
	    $sql = "SELECT * FROM CWSpacePost WHERE idReplyTo=:idSpacePost ORDER BY idSpacePost";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpacePost", $idSpacePost);
		$stmt->execute();
		$comments = $stmt->fetchAll();
		if(!$comments)
		{
			$comments = array();
		}
 		return $comments;
	}

	function addPostToSpace($idSpace, $idTenant, $message, $idReplyTo=0) {
		if($idTenant == null || isTenantOfSpace($idTenant, $idSpace))
		{
			$db = getConnection();
			if($idReplyTo > 0)
			{
				$sql = "INSERT INTO CWSpacePost(idSpace, idTenant, message, idReplyTo) VALUES(:idSpace, :idTenant, :message, :idReplyTo)";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":idSpace" => $idSpace, ":idTenant" => $idTenant, ":message" => $message, ":idReplyTo" => $idReplyTo));
			}
		    else 
		    {
		    	$sql = "INSERT INTO CWSpacePost(idSpace, idTenant, message) VALUES(:idSpace, :idTenant, :message)";
		    	$stmt = $db->prepare($sql);
				$stmt->execute(array(":idSpace" => $idSpace, ":idTenant" => $idTenant, ":message" => $message));
		    }
		    
	 		return getLastInsertedPostSpaceByTenant($idTenant, $idSpace);
		}
	    else
	    {
	    	return 0;
	    }
	}

	function getLastInsertedPostSpaceByTenant($idTenant, $idSpace)
	{
		if($idTenant)
		{
			$sql = "SELECT idSpacePost FROM CWSpacePost WHERE idTenant=:idTenant AND idSpace=:idSpace ORDER BY idSpace DESC LIMIT 1";
		}
		else
		{
			$sql = "SELECT idSpacePost FROM CWSpacePost WHERE idTenant IS NULL AND idSpace=:idSpace ORDER BY idSpace DESC LIMIT 1";
		}

        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            if($idTenant)
			{
           		$stmt->bindParam("idTenant", $idTenant);
           	}
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return $user['idSpacePost'];
            }
            return 0;
        } catch(PDOException $e) {
            return 0;
        }
	}

	function removeSpacePost($idPost, $idTenant) {
		if(isOwnerOfSpacePost($idPost, $idTenant))
		{
			$db = getConnection();
			$sql = "DELETE FROM CWSpacePost WHERE idSpacePost=:idPost";
		    $stmt = $db->prepare($sql);
			$stmt->execute(array(":idPost" => $idPost));
	 		return true;
		}
	    else
	    {
	    	return false;
	    }
	}

	function isOwnerOfSpacePost($idPost, $idTenant)
	{
		$sql = "SELECT * FROM CWSpacePost WHERE idTenant=:idTenant AND idSpacePost=:idPost";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idTenant", $idTenant);
            $stmt->bindParam("idPost", $idPost);
            $stmt->execute();
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($post) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function getLastInsertedTeamBySpaceId($idSpace)
	{
		$sql = "SELECT idTeam FROM Team WHERE idSpace=:idSpace ORDER BY idTeam DESC LIMIT 1";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return $user['idTeam'];
            }
            return 0;
        } catch(PDOException $e) {
            return 0;
        }
	}

	function getTeamById($idTeam){
		$sql = "SELECT * FROM Team WHERE idTeam = :idTeam";

		try {
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":idTeam", $idTeam);
			$stmt->execute();
			$team = $stmt->fetch();
			$db = null;
			if ($team) {
                return $team;
            }
        	return false;
		} catch(PDOException $e) {
			return false;
		}	
	}

	function addMemberToTeam($idUser, $idTeam, $approved = 'n') {
		$team = getTeamById($idTeam);
		if($team)
		{
			if(isOwnerOfSpace($idUser, $team['idSpace']))
			{
				$db = getConnection();
				$sql = "INSERT INTO TeamMember(idTeam, approved) VALUES(:idTeam, :approved)";
			    $stmt = $db->prepare($sql);
				$stmt->execute(array(":idTeam" => $idTeam, ":approved" => $approved));
		 		return true;
			}
			else
		    {
		    	$db = getConnection();
				$sql = "INSERT INTO TeamMember(idTeam, idTenant, approved) VALUES(:idTeam, :idTenant, :approved)";
			    $stmt = $db->prepare($sql);
			    $tenant = getTenantByUserAndSpace($idUser, $team['idSpace']);
				$stmt->execute(array(":idTeam" => $idTeam, ":idTenant" => $tenant['idTenant'], ":approved" => $approved));
		 		return true;
		    }
		}
	    else
	    {
	    	return false;
	    }
	}


	function userIsMemberOfTeam($idUser, $idTeam)
	{
		$team = getTeamById($idTeam);
		if($team)
		{
			$isOwner = isOwnerOfSpace($idUser, $team['idSpace']);
			if($isOwner)
			{
				$sql = "SELECT * FROM TeamMember WHERE idTeam=:idTeam AND idTenant IS NULL AND approved='y' LIMIT 1";
			}
			else
			{
				$sql = "SELECT * FROM TeamMember tm, Team t, Tenant te WHERE tm.idTeam=t.idTeam AND t.idTeam=:idTeam AND tm.idTenant=te.idTenant AND te.idUser=:idUser AND te.idSpace=t.idSpace AND approved='y' LIMIT 1";
			}
	        try {
	            $db = getConnection();
	            $stmt = $db->prepare($sql);
	            if (!$isOwner) {
	            	$stmt->bindParam("idUser", $idUser);
	            }
	            $stmt->bindParam("idTeam", $idTeam);
	            $stmt->execute();
	            $teammember = $stmt->fetch(PDO::FETCH_ASSOC);
	            $db = null;
	            if ($teammember) {
	                return true;
	            }
	            return false;
	        } catch(PDOException $e) {
	            return false;
	        }
	    }
	    return false;
	}

	function isTenantOfTeam($idTenant, $idSpace)
	{
		$user = getUserByIdTenant($idTenant);
		return userIsMemberOfSpace($user['idUser'], $idSpace);
	}

	function userHasSentRequestToTeam($idUser, $idTeam)
	{
		$team = getTeamById($idTeam);
		if($team)
		{
			$isOwner = isOwnerOfSpace($idUser, $team['idSpace']);
			if($isOwner)
			{
				$sql = "SELECT * FROM TeamMember WHERE idTeam=:idTeam AND idTenant IS NULL AND approved='n' LIMIT 1";
			}
			else
			{
				$sql = "SELECT t.* FROM TeamMember tm, Team t, Tenant te WHERE tm.idTeam=t.idTeam AND t.idTeam=:idTeam AND tm.idTenant=te.idTenant AND te.idUser=:idUser AND te.idSpace=t.idSpace AND approved='n' LIMIT 1";
			}
	        try {
	            $db = getConnection();
	            $stmt = $db->prepare($sql);
	            if (!$isOwner) {
	            	$stmt->bindParam("idUser", $idUser);
	            }
	            $stmt->bindParam("idTeam", $idTeam);
	            $stmt->execute();
	            $teammember = $stmt->fetch(PDO::FETCH_ASSOC);
	            $db = null;
	            if ($teammember) {
	                return true;
	            }
	            return false;
	        } catch(PDOException $e) {
	            return false;
	        }
	    }
	    return false;
	}

	function isOwnerOfTeam($idUser, $idTeam)
	{
		$sql = "SELECT * FROM CoworkingSpace WHERE idOwner=:idUser AND idSpace=:idSpace";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUser", $idUser);
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $space = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($space) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
	}

	function sentRequestToTeam($idUser, $idTeam)
	{
		if(!userHasSentRequestToTeam($idUser, $idTeam) && !userIsMemberOfTeam($idUser, $idTeam))
		{
			return addMemberToTeam($idUser, $idTeam);
	    }
	    return false;
	}

	function aproveRequestToTeam($idUser, $idTeam)
	{
		$team = getTeamById($idTeam);
		if($team)
		{
			$isOwner = isOwnerOfSpace($idUser, $team['idSpace']);
			if($isOwner)
			{
				$sql = "UPDATE TeamMember SET approved='y' WHERE idTenant IS NULL AND idTeam=:idTeam";
			}
			else
			{
				$sql = "UPDATE TeamMember SET approved='y' WHERE idTenant=(SELECT idTenant FROM Tenant WHERE idUser:idUser and idSpace=:idSpace) AND idTeam=:idTeam";
			}
	        try {
	            $db = getConnection();
	            $stmt = $db->prepare($sql);
	            if($isOwner)
	            {
	            	$stmt->execute(array(":idTeam" => $idTeam));
	            }
	            else
	            {
	            	$stmt->execute(array(":idUser" => $idUser, ":idSpace" => $team['idSpace'], ":idTeam" => $idTeam));
	            }
	            return true;
	        } catch(PDOException $e) {
	            return false;
	        }
	    }
	    return false;
	}

	function rejectRequestToTeam($idUser, $idTeam)
	{
		$team = getTeamById($idTeam);
		if($team)
		{
			$isOwner = isOwnerOfSpace($idUser, $team['idSpace']);
			if($isOwner)
			{
				$sql = "DELETE FROM TeamMember WHERE idTenant IS NULL AND idTeam=:idTeam";
			}
			else
			{
				$sql = "DELETE FROM TeamMember WHERE idTenant=(SELECT idTenant FROM Tenant WHERE idUser:idUser and idSpace=:idSpace) AND idTeam=:idTeam";
			}
	        try {
	            $db = getConnection();
	            $stmt = $db->prepare($sql);
	            if($isOwner)
	            {
	            	$stmt->execute(array(":idTeam" => $idTeam));
	            }
	            else
	            {
	            	$stmt->execute(array(":idUser" => $idUser, ":idSpace" => $team['idSpace'], ":idTeam" => $idTeam));
	            }
	            return true;
	        } catch(PDOException $e) {
	            return false;
	        }
	    }
	    return false;
	}

	function getPostsByTeam($idTeam) {
	    $db = getConnection();
	    $sql = "SELECT * FROM TeamPost WHERE idSpace=:idSpace AND idReplyTo IS NULL ORDER BY idSpacePost DESC";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpace", $idSpace);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$posts = array();
		foreach ($results as $post) {
			$p = array();
			foreach ($post as $key => $value) {
				$p[$key] = $value;
			}
			$p['comments'] = getCommentsBySpacePost($post['idSpacePost']);
			array_push($posts, $p);
		}
 		return $posts;
	}

	function getCommentsByTeamPost($idSpacePost) {
	    $db = getConnection();
	    $sql = "SELECT * FROM TeamPost WHERE idReplyTo=:idSpacePost ORDER BY idSpacePost";
	    $stmt = $db->prepare($sql);
	    $stmt->bindParam("idSpacePost", $idSpacePost);
		$stmt->execute();
		$comments = $stmt->fetchAll();
		if(!$comments)
		{
			$comments = array();
		}
 		return $comments;
	}

	function addPostToTeam($idSpace, $idTenant, $message, $idReplyTo=0) {
		if($idTenant == null || isTenantOfSpace($idTenant, $idSpace))
		{
			$db = getConnection();
			if($idReplyTo > 0)
			{
				$sql = "INSERT INTO TeamPost(idSpace, idTenant, message, idReplyTo) VALUES(:idSpace, :idTenant, :message, :idReplyTo)";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":idSpace" => $idSpace, ":idTenant" => $idTenant, ":message" => $message, ":idReplyTo" => $idReplyTo));
			}
		    else 
		    {
		    	$sql = "INSERT INTO TeamPost(idSpace, idTenant, message) VALUES(:idSpace, :idTenant, :message)";
		    	$stmt = $db->prepare($sql);
				$stmt->execute(array(":idSpace" => $idSpace, ":idTenant" => $idTenant, ":message" => $message));
		    }
		    
	 		return getLastInsertedPostSpaceByTenant($idTenant, $idSpace);
		}
	    else
	    {
	    	return 0;
	    }
	}

	function getLastInsertedPostTeamByTenant($idTenant, $idSpace)
	{
		if($idTenant)
		{
			$sql = "SELECT idSpacePost FROM TeamPost WHERE idTenant=:idTenant AND idSpace=:idSpace ORDER BY idSpace DESC LIMIT 1";
		}
		else
		{
			$sql = "SELECT idSpacePost FROM TeamPost WHERE idTenant IS NULL AND idSpace=:idSpace ORDER BY idSpace DESC LIMIT 1";
		}

        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            if($idTenant)
			{
           		$stmt->bindParam("idTenant", $idTenant);
           	}
            $stmt->bindParam("idSpace", $idSpace);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
            if ($user) {
                return $user['idSpacePost'];
            }
            return 0;
        } catch(PDOException $e) {
            return 0;
        }
	}
?>
