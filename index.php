<?php
require 'vendor/autoload.php';
require 'includes/utils.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->setName('Synergy Space');

$site_url = "http://localhost/csc309hue/";

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

$app->get('/newPlace', function () use ($app) {
    $app->render('newPlace.php', array('appName' => $app->getName(), "restricted" => true));
});

$app->get('/login', function () use ($app) {
    $app->render('login.php', array('appName' => $app->getName()));
});

$app->get('/search', function () use ($app) {
    $search = $app->request->get('name');
    echo " bla".$search;
   /* if ($search) {
        $db = getConnection();
        echo " bla".$search;
        $data = "%".$search."%";
        $sql = 'SELECT * FROM user WHERE name like ?';
        // we have to tell the PDO that we are going to send values to the query
        $stmt = $conn->prepare($sql);
        // Now we execute the query passing an array toe execute();
        $results = $stmt->execute(array($data));
        // Extract the values from $result
        $rows = $stmt->fetchAll();
        $error = $stmt->errorInfo();
        //echo $error[2];
    
        // If there are no records.
        if(empty($rows)) {
            echo "<tr>";
                echo "<td colspan='4'>There were not records</td>";
            echo "</tr>";
        }
        else {
            foreach ($rows as $row) {
                echo "<tr>";
                    echo "<td>".$row['idUser']."</td>";
                    echo "<td>".$row['name']."</td>";
                    echo "<td>".$row['email']."</td>";
                echo "</tr>";
            }
        }
    } else {*/
        $app->render('search.php', array('appName' => $app->getName(), "restricted" => false));
    //}
});

$app->post('/search', function () use ($app) {
    $search = $app->request->post('typeahead');
    $sql = "SELECT * FROM User WHERE name=:search";
    try {
        $db = getConnection();
        //$stmt = $db->prepare($sql);
        //$stmt->execute();
        foreach($db->query($sql) as $row) {
            echo $row['name'].' '.$row['email']; //etc...
        }

        $db = null;
    } catch(PDOException $e) {
        return false;
    }
});

$app->post('/login', function () use ($app) {
	$email = $app->request->post('email');
    $password = $app->request->post('password');
    doLogin($email, $password, $app);
});

$app->get('/signup', function () use ($app) {
    $app->render('signup.php', array('appName' => $app->getName()));
});

$app->post('/newPlace', function () use ($app) {
    $err = "";
    $name = $app->request->post('name');
    if(empty($name))
    {
        $err = addErrorMessage($err, "Name is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z \d]*$/",$name)) {
      $err = addErrorMessage($err, "Only letters, numbers and white space allowed"); 
    }

    $address = $app->request->post('address');
    if(empty($address))
    {
        $err = addErrorMessage($err, "Address is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z \d]*$/",$address)) {
      $err = addErrorMessage($err, "Only letters, numbers and white space allowed"); 
    }

    $price = $app->request->post('price');
    if(empty($price))
    {
        $err = addErrorMessage($err, "Price is required."); 
    }
    
    $numberSpots = $app->request->post('numberSpots');
    if(empty($numberSpots))
    {
        $err = addErrorMessage($err, "Number of available spots is required."); 
    }

    $description = $app->request->post('description');
    
    $leaseAgreement = $app->request->post('leaseAgreement');

    $photo = $app->request->post('photo');
    if(isset($_FILES['photo']))
    {
        $ext = strtolower(substr($_FILES['photo']['name'],-4)); //Pegando extensão do arquivo
        $new_name = $name . $ext; //Definindo um novo nome para o arquivo
        $dir = 'uploads/'; //Diretório para uploads
     
        move_uploaded_file($_FILES['photo']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
    }

    if(empty($err))
    {
        $sql = "INSERT INTO CoworkingSpace(address, availableVacancies, description, leaseAgreement, name, price) VALUES(:address, :availableVacancies, :description, :leaseAgreement, :name, :price)";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":address" => $address,
                        ":availableVacancies" => $numberSpots,
                        ":description" => $description,
                        ":leaseAgreement" => $leaseAgreement,
                        ":name" => $name,
                        ":price" => $price));
            $app->redirect($site_url);
            
        } catch(PDOException $e) {
            $app->render('newPlace.php', array('appName' => $app->getName(), 
                        'error' => 'Something went wrong. Try again.',
                        ":address" => $address,
                        ":availableVacancies" => $numberSpots,
                        ":description" => $description,
                        ":leaseAgreement" => $leaseAgreement,
                        ":name" => $name));
        }
    }
    else {
        
        $app->render('newPlace.php', array('appName' => $app->getName(), 
                    'error' => $err,
                    ":address" => $address,
                    ":availableVacancies" => $numberSpots,
                    ":description" => $description,
                    ":leaseAgreement" => $leaseAgreement,
                    ":name" => $name));
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

    $password = $app->request->post('password');
    if(empty($password))
    {
        $err = addErrorMessage("", "Password is required."); 
    }
    elseif (!preg_match("/^[a-zA-Z\d]+$/",$password)) {
      $err = addErrorMessage("", "Only letters and numbers allowed.");
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
            doLogin($email, $password, $app);
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

$app->get('/about', function () use ($app) {
    $app->render('about.php', array('appName' => $app->getName()));
});

$app->get('/user/:id', function ($id) use ($app) {
    if(intval($id) <= 0)
    {
        $app->redirect($site_url);
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
                $app->redirect($site_url);
            }
        }
        else
        {
            $app->redirect($site_url);
        }
    }
    
});

$app->post('/user/rate', function () use ($app) {
    session_start();
    $app->response->headers->set('Content-Type', 'application/json');
    if(isset($_SESSION['userID']))
    {
        if(intval($app->request->post('idBox')) > 0 && intval($app->request->post('rate')) >= 0)
        {
            $aResponse['error'] = false;
            $aResponse['message'] = '';

            if($app->request->post('action') != null)
            {
                if(htmlentities($app->request->post('action'), ENT_QUOTES, 'UTF-8') == 'rating')
                {
                    $id = intval($app->request->post('idBox'));
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

$app->get('/logout', function () use ($app) {
    session_start(); 
    session_unset(); 
    session_destroy();
    $app->redirect($site_url);
});

$app->run();



function addErrorMessage($errors, $message)
{
    if(empty($errors))
    {
        return $message;
    }
    else
    {
        return $message . "<br/>" . $message;
    }
}
?>