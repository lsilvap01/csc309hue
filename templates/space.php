<?php include 'includes/sessionConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include 'includes/headers.php'; ?>

    <title>Space <?php echo getSpaceName($idSpace); ?> || <?php echo $this->data['appName']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">

    <!-- Custom styles for this template 
    <link href="jumbotron.css" rel="stylesheet">-->

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><?php echo $this->data['appName']; ?></a>
        </div>

      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <    <div class="jumbotron">
      <div class="container">
          <h2>Space <?php echo getSpaceName($idSpace); ?></h2>
          <div class="rating" data-average="<?php echo getSpaceRate($this->data['idSpace']); ?>" data-id="<?php echo $this->data['idSpace'];?>"></div> <?php echo getSpaceRate($this->data['idSpace']); ?>/20
          <p><?php echo getSpaceDescription($this->data['idSpace']); ?> </p>
      </div> 
    </div>
	
	
    <div class="container">

<div> 
	<?php 	
	$idSpace = $this->data['idSpace'];
	$sql = "SELECT * FROM photo WHERE idSpace = :idSpace";
	try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idSpace", $idSpace);
		$stmt->execute();
        $space = $stmt->fetch();
        $db = null; 
		echo '<img src="uploads/'.$space["url"].'" alt="'.$space["url"].'"width="500">';
	} catch(PDOException $e) {
		echo "Ops...";
		//echo $e;
    }	
	?>
</div >

 <h3> Members </h3> 
<ol>
<?php echo "<li> ".getSpaceOwner($this->data['idSpace'])."</li>";?>
<?php 
	$myMembers = getSpacesMembers($this->data['idSpace']);
    foreach ($myMembers as $member) { 
	$user = getUserById($member["idUser"]);
    echo "<li> ".$user["name"]."</li>";
	}
?>
</ol>


	
    </div> <!-- /container -->


   <?php include 'includes/jsFiles.php' ?>
  </body>
</html>