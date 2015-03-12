<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Space || <?php echo $this->data['appName']; ?></title>

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


    <div class="container">

      
    <h2 class="form-signin-heading">Space 
	
	<?php 
	$idSpace = 6;
	$sql = "SELECT name FROM coworkingspace WHERE idSpace = :idSpace";
	try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idSpace", $idSpace);
		$stmt->execute();
        $space = $stmt->fetch();
        $db = null;
		echo $space["name"]; 
	} catch(PDOException $e) {
		echo "Ops...";
		//echo $e;
    }	
	?>
	
	</h2>
       

    </div>

<div> 
	<?php 	
	$idSpace = 6;
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
	
</div ><!-- final do carrosel -->





 <h3> Members </h3> 
<ol>
<li> Maria </li>
<li> Jose </li>
<li> Joao </li>
</ol>


	
    </div> <!-- /container -->


   <?php include 'includes/jsFiles.php' ?>
  </body>
</html>