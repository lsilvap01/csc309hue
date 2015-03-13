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
    <?php include 'includes/userMenu.php'; ?>

    <div class="jumbotron">
      <div class="container">
          
      </div> 
    </div>        

    <div class="container">
      <!-- Example row of columns -->
      <h1>My Coworking Spaces</h1>
      <a class="btn btn-success" href="./space/add" role="button">New Coworking Space</a>
        <?php $mySpaces = getSpacesByOwner($_SESSION["userID"]);
              $count = 0; 
              foreach ($mySpaces as $space) { 
                $count++; ?>
                <?php if($count%3 == 1) echo '<div class="row">'; ?>
                <div class="col-md-4">
                  <h2><?php echo $space['name']; ?></h2>
                  <p><?php echo substr($space['description'], 0, 250).(strlen($space['description'])>250? '...':''); ?></p>
                  <p><a class="btn btn-default" href="./space/<?php echo $space['idSpace']; ?>" role="button">View details &raquo;</a></p>
                </div>
                <?php if($count%3 == 0) echo '</div>'; ?>
      <?php   } 
              if($count>0 && $count%3 != 0) 
              {
                echo '</div>';
              }
              elseif($count == 0)
              {
                echo '<h3>You do not own any space.</h3>';
              }
      ?>

      <h1>Coworking Spaces where I work</h1>
      
        <?php $myMemberSpaces = getSpacesByMember($_SESSION["userID"]);
              $count = 0; 
              foreach ($myMemberSpaces as $space) { 
                $count++; ?>
                <?php if($count%3 == 1) echo '<div class="row">'; ?>
                <div class="col-md-4">
                  <h2><?php echo $space['name']; ?></h2>
                  <p><?php echo substr($space['description'], 0, 250).(strlen($space['description'])>250? '...':''); ?></p>
                  <p><a class="btn btn-default" href="./space/<?php echo $space['idSpace']; ?>" role="button">View details &raquo;</a></p>
                </div>
                <?php if($count%3 == 0) echo '</div>'; ?>
      <?php   } 
              if($count>0 && $count%3 != 0) 
              {
                echo '</div>';
              }
              elseif($count == 0)
              {
                echo '<h3>You are not member of any space.</h3>';
              }
      ?>
        <!-- <div class="col-md-4">
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
      </div> -->

    </div> <!-- /container -->


    <?php include 'includes/jsFiles.php'; ?>
    <script type="text/javascript" src="js/jRating.jquery.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
          // $('.rating').jRating({
          //   isDisabled : true
          // });
      });
    </script>
  </body>
</html>