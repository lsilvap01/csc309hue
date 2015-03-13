<?php include 'includes/sessionConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home || <?php echo $this->data['appName']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

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
        <div id="navbar" class="navbar-collapse collapse">
          <?php 
            if($logado) {
              echo '<a class="btn btn-primary navbar-right" role="button" href="./logout">Logout</a>';
              echo '<div class="navbar-brand navbar-right navbar-header"> Hello, <a  href="./">'.$_SESSION["userName"].'</a></div>' ;
              
            }
            else {
            ?>
          <form class="navbar-form navbar-right" action="./login" method="post">
            <div class="form-group">
              <input type="email" placeholder="Email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Log in</button>
            <a class="btn btn-success" href="./signup" role="button">Sign up</a>
          </form>
          <?php } ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Synergy Space</h1>
        <p>Here you find a place for your Job wherever you are.</p>
        <p> Sign up to have the opportunity to join or create a coworking team  or just explore the available spaces.</p>
        <p><a class="btn btn-primary btn-lg" href="./login" role="button">Log in</a>
          <a class="btn btn-primary btn-lg" href="./signup" role="button">Sign up</a></p>
          <a class="btn btn-primary btn-lg" href="./search" role="button">Explore</a></p> 
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Coworking Space</h2>
          <p>Synergy Space is a coworking website for you to find the best place to work.  </p>
          <p><a class="btn btn-default" href="./about" role="button">View details &raquo;</a></p>
        </div>
       

    </div> <!-- /container -->


    <?php include 'includes/jsFiles.php'; ?>
  </body>
</html>