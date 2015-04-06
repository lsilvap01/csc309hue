<?php
require 'vendor/autoload.php';
require 'includes/utils.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->setName('Synergy Space');

$site_url = "http://localhost/csc309hue/";//"http://huecsc309.byethost22.com/";
$upload_directory = "uploads/";


$app->config(array(
    'debug' => true,
    'templates.path' => './templates'
));



$app->get('/', function () use ($app) {
    session_start();
    
    if(isset($_SESSION['userID']))
    {
        $user = getUserById($_SESSION['userID']);
        $app->render('userHome.php', array('appName' => $app->getName(), "user" => $user));
    }
    else
    {
        $app->render('home.php', array('appName' => $app->getName()));
    }
});

$app->get('/space/add', function () use ($app) {
    $app->render('newPlace.php', array('appName' => $app->getName(), "restricted" => true));
});

$app->get('/space/:idSpace', function ($idSpace) use ($app){
    $space = getSpaceById($idSpace);
    if($space)
    {
        $app->render('space.php', array('appName' => $app->getName(), 'space' => $space));
    }
	else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->get('/space/:idSpace/posts', function ($idSpace) use ($app){
    session_start();
    if(isset($_SESSION['userID']) && userIsMemberOfSpace($_SESSION['userID'], $idSpace))
    {
	    $space = getSpaceById($idSpace);
	    $posts = getPostsBySpace($idSpace);
	    if($space)
	    {
	        $app->render('spacePosts.php', array('appName' => $app->getName(), 'space' => $space, 'posts' => $posts));
	    }
		else
	    {
	        $app->redirect($GLOBALS['site_url']);
	    }
	}
	else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->post('/space/:idSpace/post', function ($idSpace) use ($app){
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        if(intval($idSpace) > 0 && intval($app->request->post('replyTo')) >= 0)
        {
            $aResponse['error'] = false;
            $aResponse['message'] = '';

            
            $id = intval($idSpace);
            $replyTo = intval($app->request->post('replyTo'));
            $message = $app->request->post('message');
            $space = getSpaceById($id);

            if($space)
            {
                if(userIsMemberOfSpace($_SESSION['userID'], $id))
                {
                    if($message)
                    {
                    	$tenant = getTenantByUserAndSpace($_SESSION['userID'], $id);
                    	$idPost = addPostToSpace($id, $tenant['idTenant'], $message, $replyTo);
                        if($idPost > 0)
                        {
                            $aResponse['message'] = 'Your post has been successfuly inserted.';
                            $aResponse['idPost'] = $idPost;
                        }
                        else
                        {
                            $aResponse['error'] = true;
                            $aResponse['message'] = 'An error occured during the request. Please retry';
                        }
                    }
                    else
                    {
                        $aResponse['error'] = true;
                        $aResponse['message'] = 'The field "message" cannot be empty.';
                    }
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = 'You cannot post in this space';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'Space does not exist';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in to rate a space';
    }
    echo json_encode($aResponse);
});

$app->get('/space/:idSpace/teams/new', function ($idSpace) use ($app){
    session_start();
    if(isset($_SESSION['userID']) && userIsMemberOfSpace($_SESSION['userID'], $idSpace))
    {
	    $space = getSpaceById($idSpace);
	    if($space)
	    {
	        $app->render('newTeam.php', array('appName' => $app->getName(), 'space' => $space, 'members' => array()));
	    }
		else
	    {
	        $app->redirect($GLOBALS['site_url']);
	    }
	}
	else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->post('/space/:idSpace/teams/new', function ($idSpace) use ($app){
    session_start();
    if(isset($_SESSION['userID']) && userIsMemberOfSpace($_SESSION['userID'], $idSpace))
    {
	    $space = getSpaceById($idSpace);
	    if($space)
	    {
	    	$members = $app->request->post('members');
	    	if(!$members || !is_array($members))
	    	{
	    		$members = array();
	    	}
	    	
	    	$err = "";
	        $name = $app->request->post('name');
	        if(empty($name))
	        {
	            $err = addErrorMessage($err, "Name is required."); 
	        }
	        elseif (!preg_match("/^[a-zA-Z0-9 \d]*$/",$name)) {
	          $err = addErrorMessage($err, "Only letters, numbers and white space allowed"); 
	        }
	        elseif (strlen($name) > 50) {
	          $err = addErrorMessage($err, "The name must be at most 50 caracters long"); 
	        }

	        if(empty($err))
	        {
	            $sql = "INSERT INTO Team(idSpace, name) VALUES(:idSpace, :name)";
	            try {
	                $db = getConnection();
	                $stmt = $db->prepare($sql);
	                $stmt->execute(array(":idSpace" => $idSpace,
	                            ":name" => $name));
	                

	                $idTeam = getLastInsertedTeamBySpaceId($idSpace);
	                if($idTeam > 0)
	                {
	                	addMemberToTeam($_SESSION['userID'], $idTeam, 'y');
	                	foreach ($members as $member) {
	                		addMemberToTeam($member, $idTeam, 'y');
	                	}
	                	$app->redirect($GLOBALS['site_url']."space/".$idSpace."/team/".$idTeam);
	                }      
	            } catch(PDOException $e) {
	                $app->render('newPlace.php', array('appName' => $app->getName(), 
	                            'error' => 'Something went wrong. Try again.',
	                            "name" => $name,
	                            "members" => $members));
	            }
	        }
	        else {
	            
	            $app->render('newTeam.php', array('appName' => $app->getName(), 
	                        'error' => $err,
	                        "name" => $name,
	                        "members" => $members));
	        }
	    }
		else
	    {
	        $app->redirect($GLOBALS['site_url']);
	    }
	}
	else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->get('/space/:idSpace/team/:idTeam', function ($idSpace, $idTeam) use ($app){
    $space = getSpaceById($idSpace);
    $team = getTeamById($idTeam);
    if($space && $team && intval($team['idSpace']) == intval($idSpace))
    {
        $app->render('team.php', array('appName' => $app->getName(), 'space' => $space, 'team' => $team));
    }
	else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->post('/space/:idSpace/rate', function ($idSpace) use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        if(intval($idSpace) > 0 && intval($app->request->post('rate')) >= 0)
        {
            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if($app->request->post('action') != null)
            {
                if(htmlentities($app->request->post('action'), ENT_QUOTES, 'UTF-8') == 'rating')
                {
                    $id = intval($idSpace);
                    $rate = intval($app->request->post('rate'));
                    
                    $space = getSpaceById($id);
                    if($space)
                    {
                        if(userIsMemberOfSpace($_SESSION['userID'], $id))
                        {
                            if(intval($space['idOwner']) != intval($_SESSION['userID']))
                            {
                                if(rateSpace($_SESSION['userID'], $id, $rate))
                                {
                                    $aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate.';
                                }
                                else
                                {
                                    $aResponse['error'] = true;
                                    $aResponse['message'] = 'An error occured during the request. Please retry';
                                }
                            }
                            else
                            {
                                $aResponse['error'] = true;
                                $aResponse['message'] = 'You cannot rate your own space';
                            }
                        }
                        else
                        {
                            $aResponse['error'] = true;
                            $aResponse['message'] = 'You cannot rate this space';
                        }
                    }
                    else
                    {
                        $aResponse['error'] = true;
                        $aResponse['message'] = 'Space does not exist';
                    }
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = '"action" post data not equal to \'rating\'';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'Something went wrong';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in to rate a space';
    }
    echo json_encode($aResponse);
});

$app->get('/login', function () use ($app) {
    $app->render('login.php', array('appName' => $app->getName()));
});

$app->get('/userProfile', function () use ($app) {
    $app->render('userProfile.php', array('appName' => $app->getName()));
});

$app->get('/search(/(:query))', function ($query = "") use ($app) {
    if(!empty($query))
    {
        $results = searchSpacesByQuery($query);
        $app->render('search.php', array('appName' => $app->getName(), 'restricted' => true, 'query' => $query, 'results' => $results));
    }
    else {
        $app->render('search.php', array('appName' => $app->getName(), 'restricted' => true));
    }
});

$app->post('/login', function () use ($app) {
	$email = $app->request->post('email');
    $password = $app->request->post('password');
    doLogin($email, $password);
});

$app->get('/signup', function () use ($app) {
    $app->render('signup.php', array('appName' => $app->getName()));
});

$app->post('/space/add', function () use ($app) {
    session_start();
    if(isset($_SESSION['userID']))
    {
        $err = "";
        $name = $app->request->post('name');
        if(empty($name))
        {
            $err = addErrorMessage($err, "Name is required."); 
        }
        elseif (!preg_match("/^[a-zA-Z0-9 \d]*$/",$name)) {
          $err = addErrorMessage($err, "Only letters, numbers and white space allowed"); 
        }
        elseif (strlen($name) > 50) {
          $err = addErrorMessage($err, "The name must be at most 50 caracters long"); 
        }

        $address = $app->request->post('address');
        if(empty($address))
        {
            $err = addErrorMessage($err, "Address is required."); 
        }
        elseif (!preg_match("/^[a-zA-Z \d]*$/",$address)) {
          $err = addErrorMessage($err, "Only letters, numbers and white space allowed"); 
        }
        elseif (strlen($address) > 150) {
          $err = addErrorMessage($err, "The address must be at most 150 caracters long"); 
        }

        $price = $app->request->post('price');
        if(empty($price))
        {
            $err = addErrorMessage($err, "Price is required."); 
        }
        elseif(!is_numeric($price))
        {
            $err = addErrorMessage($err, "Price must be a number."); 
        }
        elseif(floatval($price) < 0)
        {
            $err = addErrorMessage($err, "Price must be a positive number."); 
        }
        
        $numberSpots = $app->request->post('numberSpots');
        if(empty($numberSpots))
        {
            $err = addErrorMessage($err, "Number of available spots is required."); 
        }
        elseif(!is_numeric($numberSpots))
        {
            $err = addErrorMessage($err, "The number of available spots must be a number."); 
        }
        elseif(intval($numberSpots) < 0)
        {
            $err = addErrorMessage($err, "The number of available spots must be a positive number."); 
        }

        $description = $app->request->post('description');

        if(!empty($_FILES['photo']['name']))
        {
            //$ext = strtolower(substr($_FILES['photo']['name'],-4)); 
            $allowed =  array('jpeg','png' ,'jpg');
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION); //Pegando extensão do arquivo
            if(!in_array($ext,$allowed) ) {
                $err = addErrorMessage($err, "Invalid photo format (Allowed extensions: .jpg|.jpeg|.png)"); 
            }
        }

        if(!empty($_FILES['lease']['name']))
        {
            //$ext = strtolower(substr($_FILES['photo']['name'],-4)); 
            $allowed =  array('pdf','doc' ,'docx');
            $ext = pathinfo($_FILES['lease']['name'], PATHINFO_EXTENSION); //Pegando extensão do arquivo
            if(!in_array($ext,$allowed) ) {
                $err = addErrorMessage($err, "Invalid lease format (Allowed extensions: .pdf|.doc|.docx)"); 
            }
        }

        if(empty($err))
        {
            $sql = "INSERT INTO CoworkingSpace(idOwner, address, availableVacancies, description, name, price) VALUES(:idOwner, :address, :availableVacancies, :description,  :name, :price)";
            try {
                $db = getConnection();
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":idOwner" => $_SESSION['userID'],
                            ":address" => $address,
                            ":availableVacancies" => $numberSpots,
                            ":description" => $description,
                            ":name" => $name,
                            ":price" => $price));
                

                $idSpace = getLastInsertedSpaceByOwner($_SESSION['userID']);
                if($idSpace > 0)
                {
                    if(!empty($_FILES['photo']['name']))
                    {
                        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION); //Pegando extensão do arquivo
                        $new_name = "space" . $idSpace. "Photo." . $ext; //Definindo um novo nome para o arquivo
                        move_uploaded_file($_FILES['photo']['tmp_name'], $GLOBALS['upload_directory'].$new_name); //Fazer upload do arquivo
                        addPhotoToSpace($new_name, $idSpace);
                    }

                    if(!empty($_FILES['lease']['name']))
                    {
                        $ext = pathinfo($_FILES['lease']['name'], PATHINFO_EXTENSION); //Pegando extensão do arquivo
                        $new_name = "space" . $idSpace . "Lease." . $ext; //Definindo um novo nome para o arquivo
                        move_uploaded_file($_FILES['lease']['tmp_name'], $GLOBALS['upload_directory'].$new_name); //Fazer upload do arquivo
                        addLeaseToSpace($new_name, $idSpace);
                    }
                }

                $app->redirect($GLOBALS['site_url']);
                
            } catch(PDOException $e) {
                $app->render('newPlace.php', array('appName' => $app->getName(), 
                            'error' => 'Something went wrong. Try again.',
                            "address" => $address,
                            "numberSpots" => $numberSpots,
                            "description" => $description,
                            "name" => $name,
                            "price" => $price));
            }
        }
        else {
            
            $app->render('newPlace.php', array('appName' => $app->getName(), 
                        'error' => $err,
                        "address" => $address,
                        "numberSpots" => $numberSpots,
                        "description" => $description,
                        "name" => $name,
                        "price" => $price));
        }
    }
    else
    {
        $app->redirect($GLOBALS['site_url']);
    }
});

$app->post('/signup', function () use ($app) {
    $email = $app->request->post('email');
    $err = "";
    if(empty($email))
    {
        $err = addErrorMessage($err, "Email is required."); 
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $err = addErrorMessage($err, "Email is required.");
    }
    elseif (emailExists($email)) {
        $err = addErrorMessage($err, "This email is already being used."); 
    }
    elseif (strlen($email) > 100) {
      $err = addErrorMessage($err, "The email must be at most 100 caracters long"); 
    }

    $password = $app->request->post('password');
    if(empty($password))
    {
        $err = addErrorMessage("", "Password is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z\d]+$/",$password)) {
      $err = addErrorMessage("", "Only letters and numbers allowed.");
    }
    elseif (strlen($password) < 5 || strlen($password) > 20) {
      $err = addErrorMessage($err, "The password must be at least 5 and at most 20 caracters long"); 
    }

    $confirmpassword = $app->request->post('confirmpassword');
    if ($password != $confirmpassword) {
        $err = addErrorMessage($err, "Passwords are not equal.");
    }

    $name = $app->request->post('name');
    if(empty($name))
    {
        $err = addErrorMessage($err, "Name is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $err = addErrorMessage($err, "Only letters and white space allowed"); 
    }
    elseif (strlen($name) > 50) {
      $err = addErrorMessage($err, "The name must be at most 50 caracters long"); 
    }

    $birthday = $app->request->post('birthday');
    if (DateTime::createFromFormat('Y-m-d', $birthday) !== FALSE) {
        $birthdayD = DateTime::createFromFormat("Y-m-d", $birthday);//strtotime($birthday);
        //$birthday = date('Y/m/d',$time);
        $year = $birthdayD->format("Y");
        $month = $birthdayD->format("m");
        $day = $birthdayD->format("d");
        if(!checkdate($month, $day , $year))
        {
            $err = addErrorMessage($err, "Invalid birthday."); 
        }
        elseif(strtotime($birthday) > strtotime(date('Y/m/d')))
        {
            $err = addErrorMessage($err, "Birthday cannot be higher than the current date.");
        }
    }
    else {
        $err = addErrorMessage($err, "Invalid birthday."); 
    }

    $gender = $app->request->post('gender');
    if(empty($gender))
    {
        $err = addErrorMessage($err, "Gender is required."); 
    }

    if(empty($err))
    {
        $sql = "INSERT INTO User(name, email, password, gender, birthdate) VALUES(:name, :email, :password, :gender, :birthday)";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":name" => $name,
                        ":email" => $email,
                        ":password" => makeMD5($password),
                        ":gender" => $gender,
                        ":birthday" => $birthday));
            doLogin($email, $password);
            //$app->redirect("/csc309hue/");
            
        } catch(PDOException $e) {
            $app->render('signup.php', array('appName' => $app->getName(), 
                        'error' => 'Something went wrong. Try again.',
                        "name" => $name,
                        "email" => $email,
                        "gender" => $gender,
                        "birthday" => $birthday));
        }
    }
    else {
        $app->render('signup.php', array('appName' => $app->getName(), 
                    'error' => $err,
                    "name" => $name,
                    "email" => $email,
                    "gender" => $gender,
                    "birthday" => $birthday));
    }
    //$app->render('login.php', array('appName' => $app->getName()));
});



$app->post('/userProfile', function () use ($app) {
    $email = $app->request->post('email');
    $err = "";
    if(empty($email))
    {
        $err = addErrorMessage($err, "Email is required."); 
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $err = addErrorMessage($err, "Email is required.");
    }
    elseif (emailExists($email)) {
        $err = addErrorMessage($err, "This email is already being used."); 
    }
    elseif (strlen($email) > 100) {
      $err = addErrorMessage($err, "The email must be at most 100 caracters long"); 
    }

    $name = $app->request->post('name');
    if(empty($name))
    {
        $err = addErrorMessage($err, "Name is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $err = addErrorMessage($err, "Only letters and white space allowed"); 
    }
    elseif (strlen($name) > 50) {
      $err = addErrorMessage($err, "The name must be at most 50 caracters long"); 
    }

    $birthdate = $app->request->post('birthday');
    if (DateTime::createFromFormat('Y-m-d', $birthdate) !== FALSE) {
        $birthdayD = DateTime::createFromFormat("Y-m-d", $birthdate);//strtotime($birthday);
        //$birthday = date('Y/m/d',$time);
        $year = $birthdayD->format("Y");
        $month = $birthdayD->format("m");
        $day = $birthdayD->format("d");
        if(!checkdate($month, $day , $year))
        {
            $err = addErrorMessage($err, "Invalid birthday."); 
        }
        elseif(strtotime($birthdate) > strtotime(date('Y/m/d')))
        {
            $err = addErrorMessage($err, "Birthday cannot be higher than the current date.");
        }
    }
    else {
        $err = addErrorMessage($err, "Invalid birthday."); 
    }

    $gender = $app->request->post('gender');
    if(empty($gender))
    {
        $err = addErrorMessage($err, "Gender is required."); 
    }

    $profession = $app->request->post('profession');
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
          $err = addErrorMessage($err, "Only letters and white space allowed"); 
        }
        elseif (strlen($name) > 50) {
          $err = addErrorMessage($err, "The profession must be at most 50 caracters long"); 
    }

    $address = $app->request->post('address');

    $selfDescription = $app->request->post('selfDescription');

    $professionalExperience = $app->request->post('professionalExperience');

    $professionalSkills = $app->request->post('professionalSkills');

    $fieldsOfInterest = $app->request->post('fieldsOfInterest');

    $err = "";

    if(empty($err))
    {
        session_start();
        $userId = $_SESSION["userID"];
        $sql = "UPDATE User SET name=:name, email=:email, gender=:gender, birthdate=:birthdate, profession=:profession, address=:address, selfDescription=:selfDescription, professionalExperience=:professionalExperience, professionalSkills=:professionalSkills, fieldsOfInterest=:fieldsOfInterest WHERE idUser=:userId";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":userId", $userId);
            $stmt->execute(array(":name" => $name,
                        ":email" => $email,
                        ":gender" => $gender,
                        ":birthdate" => $birthdate,
                        ":profession" => $profession,
                        ":address" => $address,
                        "selfDescription" => $selfDescription,
                        ":professionalExperience" => $professionalExperience,
                        ":professionalSkills" => $professionalSkills,
                        ":fieldsOfInterest" => $fieldsOfInterest,
                        ":userId" => $userId));
            $app->redirect($GLOBALS['site_url']);            
            
        } catch(PDOException $e) {
            $app->render('userProfile.php', array('appName' => $app->getName(), 
                        'error' => 'Something went wrong. Try again.',
                        "name" => $name,
                        "email" => $email,
                        "gender" => $gender,
                        "birthdate" => $birthdate,
                        ":profession" => $profession,
                        ":address" => $address,
                        "selfDescription" => $selfDescription,
                        ":professionalExperience" => $professionalExperience,
                        ":professionalSkills" => $professionalSkills,
                        ":fieldsOfInterest" => $fieldsOfInterest));

        }
    }
    else {
        $app->render('userProfile.php', array('appName' => $app->getName(), 
                    'error' => $err,
                    "name" => $name,
                    "email" => $email,
                    "gender" => $gender,
                    "birthdate" => $birthdate,
                   ":profession" => $profession,
                    ":address" => $address,
                    "selfDescription" => $selfDescription,
                    ":professionalExperience" => $professionalExperience,
                    ":professionalSkills" => $professionalSkills,
                    ":fieldsOfInterest" => $fieldsOfInterest));
    }
    //$app->render('login.php', array('appName' => $app->getName()));
});



$app->get('/about', function () use ($app) {
    $app->render('about.php', array('appName' => $app->getName()));
});

$app->get('/user/:id', function ($id) use ($app) {
    if(intval($id) <= 0)
    {
        $app->redirect($GLOBALS['site_url']);
        //echo json_encode(array('success' => false, 'error' => 'Ivalid user id'));
    }
    else
    {
        session_start();
    
        if(isset($_SESSION['userID']))
        {
            $user = getUserById($id);
            if($user)
            {
               $app->render('userHome.php', array('appName' => $app->getName(), "user" => $user)); 
            }
            else
            {
                $app->redirect($GLOBALS['site_url']);
            }
        }
        else
        {
            $app->redirect($GLOBALS['site_url']);
        }
    }
    
});

$app->post('/user/:idUser/rate', function ($idUser) use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        if(intval($idUser) > 0 && intval($app->request->post('rate')) >= 0)
        {
            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if($app->request->post('action') != null)
            {
                if(htmlentities($app->request->post('action'), ENT_QUOTES, 'UTF-8') == 'rating')
                {
                    $id = intval($idUser);
                    $rate = intval($app->request->post('rate'));
                    
                    $user = getUserById($id);
                    if($user)
                    {
                        if(canRateUser($_SESSION['userID'], $id))
                        {
                            if(rateUser($_SESSION['userID'], $id, $rate))
                            {
                                $aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate.';
                            }
                            else
                            {
                                $aResponse['error'] = true;
                                $aResponse['message'] = 'An error occured during the request. Please retry';
                            }
                        }
                        else
                        {
                            $aResponse['error'] = true;
                            $aResponse['message'] = 'You cannot rate this user';
                        }
                    }
                    else
                    {
                        $aResponse['error'] = true;
                        $aResponse['message'] = 'User does not exist';
                    }
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = '"action" post data not equal to \'rating\'';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'Something went wrong';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in to rate a user';
    }
    echo json_encode($aResponse);
});

$app->get('/me/spaces', function () use ($app) {
    $app->render('mySpaces.php', array('appName' => $app->getName())); 
});

$app->get('/space/:idSpace/requestmembership', function ($idSpace) use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        $idUser = $_SESSION['userID'];
        if(intval($idSpace) > 0)
        {

            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if(!userIsMemberOfSpace($idUser, $idSpace))
            {
                if(!userHasSentRequestToSpace($idUser, $idSpace))
                {
                    if(sentRequestToSpace($idUser, $idSpace))
                    {
                        $aResponse['message'] = 'Your membership request has been successfuly sent.';
                    }
                    else
                    {
                        $aResponse['error'] = true;
                        $aResponse['message'] = 'Something went wrong.';
                    }
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = 'You have already sent a membership request.';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'You are already a member.';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters.';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in.';
    }
    echo json_encode($aResponse);
});

$app->get('/space/:idSpace/requestmembership/:idUser/accept', function ($idSpace, $idUser) use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        $idOwner = $_SESSION['userID'];
        if(intval($idSpace) > 0 && intval($idUser) > 0)
        {

            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if(isOwnerOfSpace($idOwner, $idSpace))
            {
                if(aproveRequestToSpace($idUser, $idSpace))
                {
                    $aResponse['message'] = 'You have successfuly accepted the request.';
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = 'Something went wrong.';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'You do not have permission to perform this operation.';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters.';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in.';
    }
    echo json_encode($aResponse);
});

$app->delete('/space/:idSpace/requestmembership/:idUser', function ($idSpace, $idUser) use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        $idOwner = $_SESSION['userID'];
        if(intval($idSpace) > 0)
        {

            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if(isOwnerOfSpace($idOwner, $idSpace))
            {
                if(rejectRequestToSpace($idUser, $idSpace))
                {
                    $aResponse['message'] = 'You have successfuly rejected the request.';
                }
                else
                {
                    $aResponse['error'] = true;
                    $aResponse['message'] = 'Something went wrong.';
                }
            }
            else
            {
                $aResponse['error'] = true;
                $aResponse['message'] = 'You do not have permission to perform this operation.';
            }
        }
        else
        {
            $aResponse['error'] = true;
            $aResponse['message'] = 'Invalid parameters.';
        }
    }
    else
    {
        $aResponse['error'] = true;
        $aResponse['message'] = 'You must be logged in.';
    }
    echo json_encode($aResponse);
});

$app->get('/logout', function () use ($app) {
    session_start(); 
    session_unset(); 
    session_destroy();
    $app->redirect($GLOBALS['site_url']);
});

$app->run();
?>