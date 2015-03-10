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

$app->get('/login', function () use ($app) {
    $app->render('login.php', array('appName' => $app->getName()));
});

$app->post('/login', function () use ($app) {
	$email = $app->request->post('email');
	$password = $app->request->post('password');
	$sql = "SELECT * FROM User WHERE email=:email AND password=:password";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", makeMD5($password));
        $stmt->execute();
        $user = $stmt->fetchObject();
        $db = null;
        if ($user) {
        	$app->redirect("/csc309hue/");
        }
        else
        	$app->render('login.php', array('appName' => $app->getName(), 'error' => 'Invalid Email and/or Password.', 'email' => $email));
    } catch(PDOException $e) {
    	$app->render('login.php', array('appName' => $app->getName(), 'error' => 'Something went wrong. Try again.', 'email' => $email));
       }
    //$app->render('login.php', array('appName' => $app->getName()));
});

$app->get('/signup', function () use ($app) {
    $app->render('signup.php', array('appName' => $app->getName()));
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
            $app->redirect("/csc309hue/");
            
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

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
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