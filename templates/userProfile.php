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

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
          <h2><?php echo $this->data['user']['name']; ?></h2>
          <div class="rating" data-average="<?php echo getUserRate($this->data['user']['idUser']); ?>" data-id="<?php echo $this->data['user']['idUser'];?>"></div> <?php echo getUserRate($this->data['user']['idUser']); ?>/20
          <p><?php echo $this->data['user']['selfDescription']; ?> </p>
      </div> 
    </div>

    <div class="container">
      <form class="form-signup" action="./signup" method="POST">
        <h2 class="form-signin-heading">Sign up</h2>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" maxlength="100" value="<?php echo $this->data['email']; ?>" name="email" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" maxlength="20" name="password" placeholder="Password">
        </div>
        <div class="form-group">
          <label for="confirmpassword">Confirm password</label>
          <input type="password" class="form-control" id="confirmpassword" maxlength="20" name="confirmpassword" placeholder="Confirm password">
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" maxlength="50" value="<?php echo $this->data['name']; ?>" name="name" placeholder="Name">
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" class="form-control">
            <option value="">Select a gender</option>
            <option value="f"  <?php if($this->data['gender'] == "f") echo "selected" ?>>Female</option>
            <option value="m" <?php if($this->data['gender'] == "m") echo "selected" ?>>Male</option>
          </select>
        </div>
        <div class="form-group">
          <label for="birthday">Birthday</label>
          <input type="date" class="form-control" id="birthday" value="<?php echo $this->data['birthday']; ?>" name="birthday" placeholder="MM/DD/YYY">
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="profession">Profession</label>  
          <div class="col-md-6">
          <input id="profession" name="profession" type="text" placeholder="" class="form-control input-md">
            
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="address">Address</label>  
          <div class="col-md-6">
          <input id="address" name="address" type="text" placeholder="" class="form-control input-md">
            
          </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="selfDescription">Self Description</label>
          <div class="col-md-4">                     
            <textarea class="form-control" id="selfDescription" name="selfDescription"></textarea>
          </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="professionalExperience">Professional Experience</label>
          <div class="col-md-4">                     
            <textarea class="form-control" id="professionalExperience" name="professionalExperience"></textarea>
          </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="professinalSkills">Professional Skills</label>
          <div class="col-md-4">                     
            <textarea class="form-control" id="professinalSkills" name="professinalSkills"></textarea>
          </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="fieldInterest">Fields of interest</label>
          <div class="col-md-4">                     
            <textarea class="form-control" id="fieldInterest" name="fieldInterest"></textarea>
          </div>
        </div>
        
        <div class="control-group">
          <label class="control-label" for="button1"></label>
          <div class="controls">
            <button id="button1" name="button1" class="btn btn-success">Submit</button>
            <button id="button2" name="button2" class="btn btn-danger">Cancel</button>
          </div>
        </div>
        <?php if ($this->data['error']) { ?> <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $this->data['error']; ?></div> <?php } ?>
      </form>


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