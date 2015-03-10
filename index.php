<?php
require 'vendor/autoload.php';
require 'utils.php';

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
        $stmt->bindParam("password", makekMD5($password));
        $stmt->execute();
        $user = $stmt->fetchObject();
        $db = null;
        if ($user) {
        	echo json_encode($user);
        }
        else
        	$app->render('login.php', array('appName' => $app->getName(), 'error' => 'Invalid Email and/or Password.'));
    } catch(PDOException $e) {
    	$app->render('login.php', array('appName' => $app->getName(), 'error' => 'Something went wrong. Try again.'));
       }
    //$app->render('login.php', array('appName' => $app->getName()));
});

$app->get('/signup', function () use ($app) {
    $app->render('signup.php', array('appName' => $app->getName()));
});

$app->get('/about', function () use ($app) {
    $app->render('about.php', array('appName' => $app->getName()));
});

$app->post('/signup', function () use ($app) {
    $email = $app->request->post('email');
	$password = $app->request->post('password');
	$passwordconf = $app->request->post('passwordconf');
	$name = $app->request->post('fname') . $app->request->post('lname');
	$gender = $app->request->post('select1');
	$birthdate = $app->request->post('birthday');
	$profession = $app->request->post('prof');
	$professionalExperience = $app->request->post('profexp');
	$professionalSkills = $app->request->post('profskills');
	$selfDescription = $app->request->post('descript');
	$fieldsOfInterest = $app->request->post('inter');
	$address = $app->request->post('address');
	$sql = "INSERT INTO `user`(`name`, `email`, `password`, `gender`, `birthdate`, `profession`, `professionalExperience`, `professionalSkills`, `selfDescription`, `fieldsOfInterest`, `address`) 
	VALUES (name, email, password, gender, birthdate, profession, professionalExperience, professionalSkills, selfDescription, fieldsOfInterest, address)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", $password);
		$stmt->bindParam("name", $name);
		$stmt->bindParam("gender", $gender);
		$stmt->bindParam("birthdate", $birthdate);
		$stmt->bindParam("profession", $profession);
		$stmt->bindParam("professionalExperience", $professionalExperience);
		$stmt->bindParam("professionalSkills", $professionalSkills);
		$stmt->bindParam("selfDescription", $selfDescription);
		$stmt->bindParam("fieldsOfInterest", $fieldsOfInterest);
		$stmt->bindParam("address", $address);
        $stmt->execute();
        $user = $stmt->fetchObject();
        $db = null;
        if ($user) {
        	echo json_encode($user);
        }
        else
        	echo 'erro';
    } catch(PDOException $e) {
    	echo $e;
       }
    //$app->render('login.php', array('appName' => $app->getName()));
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
?>