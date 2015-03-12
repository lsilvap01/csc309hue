<?php include 'includes/sessionConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'includes/headers.php'; ?>

    <title>Home || <?php echo $this->data['appName']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jRating.jquery.css" type="text/css" />
    <!-- Custom styles for this template 
    <link href="jumbotron.css" rel="stylesheet">-->

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<<<<<<< HEAD

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
              echo '<a class="btn btn-primary navbar-right" href="./logout">Logout</a>';
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

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><?php echo $this->data['appName']; ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          
          
          <ul class="nav navbar-nav navbar-right">
            <li><a>Welcome <?php echo explode(" ", $this->data['user']['name'])[0]; ?></a></li>
            <li class="active"><a href="./">Home</a></li>
            <li><a href="#">Messages</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">My Profile</a></li>
                <li><a href="#">My Workspaces</a></li>
                <li><a href="#">My Groups</a></li>
                <li class="divider"></li>
                <li><a href="./logout">Log Out</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right" role="search" action="./search">
            <div class="form-group">
              <div class="col-sm-10">
              <input type="text" class="form-control" placeholder="Search Workspaces" name="name"></div>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
=======
    <?php include 'includes/userMenu.php'; ?>
>>>>>>> origin/master

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
          <h2><?php echo $this->data['user']['name']; ?></h2>
          <div class="rating" data-average="<?php echo getUserRate($this->data['user']['idUser']); ?>" data-id="<?php echo $this->data['user']['idUser'];?>"></div> <?php echo getUserRate($this->data['user']['idUser']); ?>/20
          <p><?php echo $this->data['user']['selfDescription']; ?> </p>
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
        <div class="col-md-4">
          <h2>Heading</h2>
          <p> </p>
          <p><a class="btn btn-default" href="" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p></p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>

    </div> <!-- /container -->


    <?php include 'includes/jsFiles.php'; ?>
    <script type="text/javascript" src="js/jRating.jquery.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        <?php if($_SESSION['userID'] == $this->data['user']['idUser']) { ?>
          $('.rating').jRating({
            isDisabled : true
          });
        <?php } else { ?>  
          $('.rating').jRating({
            canRateAgain : true,
            onSuccess : function(){
              alert('Success : your rate has been saved :)');
            },
            onError : function(){
              alert('Error : please retry');
            }
          });
        <?php } ?>
      });
    </script>
  </body>
</html>