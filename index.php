<?php
require 'vendor/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->setName('SynergySpace');

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
        $stmt->bindParam("password", $password);
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