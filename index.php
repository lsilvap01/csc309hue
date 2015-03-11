<?php
require 'vendor/autoload.php';
require 'includes/utils.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->setName('Synergy Space');

$app->config(array(
    'debug' => true,
    'templates.path' => './templates'
));



$app->get('/', function () use ($app) {
    $app->render('home.php', array('appName' => $app->getName()));
});

$app->get('/newPlace', function () use ($app) {
    $app->render('newPlace.php', array('appName' => $app->getName(), "restricted" => true));
});

$app->get('/login', function () use ($app) {
    $app->render('login.php', array('appName' => $app->getName()));
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
            $app->redirect("/csc309hue/");
            
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

$app->get('/logout', function () use ($app) {
    session_start(); 
    session_unset(); 
    session_destroy();
    $app->redirect("/csc309hue/");
});

$app->run();

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="csc309";
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
        return $message . "<br/>" . $message;
    }
}
?>