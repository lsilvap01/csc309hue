
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login || <?php echo $this->data['appName']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="css/style.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">

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
      <form class="form-signup" action="./signup" method="POST">
        <h2 class="form-signin-heading">Sign up</h2>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" value="<?php echo $this->data['email']; ?>" name="email" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <div class="form-group">
          <label for="confirmpassword">Confirm password</label>
          <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm password">
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" value="<?php echo $this->data['name']; ?>" name="name" placeholder="Name">
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
        <div class="control-group">
          <label class="control-label" for="button1"></label>
          <div class="controls">
            <button id="button1" name="button1" class="btn btn-success">Submit</button>
            <button id="button2" name="button2" class="btn btn-danger">Cancel</button>
          </div>
        </div>
        <?php if ($this->data['error']) { ?> <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $this->data['error']; ?></div> <?php } ?>
      </form>
     <!--  <form class="form-horizontal">
        <fieldset>
      Form Name 
        <h2 class="form-signin-heading">SingUp Form</h2>
        
          <label class="control-label" for="email">Email</label>
          <input id="email" name="email" placeholder="" class="input-large" required="" type="text">

        
        
          <label class="control-label" for="password">Password</label>
            <input id="password" name="password" placeholder="" class="input-xlarge" required="" type="password">

       
          <label class="control-label" for="passwordconf">Confirm password</label>
            <input id="passwordconf" name="passwordconf" placeholder="" class="input-xlarge" required="" type="password">

        
          <label class="control-label" for="fname">First Name</label>
            <input id="fname" name="fname" placeholder="" class="input-large" required="" type="text">
            

        
          <label class="control-label" for="fname">Last Name</label>
            <input id="fname" name="fname" placeholder="" class="input-large" required="" type="text">

        
          <label class="control-label" for="select1">Gender</label>
            <select id="select1" name="select1" class="input-large">
              <option>Female</option>
              <option>Male</option>
            </select>

        
        <div class="control-group">
          <label class="control-label" for="birthday">Birthday</label>
          <div class="controls">
            <input id="birthday" name="birthday" placeholder="" class="input-large" help="MM/DD/YYYY" required="" type="text">
            
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="prof">Profession</label>
          <div class="controls">
            <input id="prof" name="prof" placeholder="" class="input-large" required="" type="text">
            
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="descript">Self-description</label>
          <div class="controls">                     
            <textarea id="descript" name="descript"></textarea>
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="profexp">Profissional Experience</label>
          <div class="controls">                     
            <textarea id="profexp" name="profexp"></textarea>
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="profskills">Profissional Skills</label>
          <div class="controls">                     
            <textarea id="profskills" name="profskills"></textarea>
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="inter">Fields of Interest</label>
          <div class="controls">                     
            <textarea id="inter" name="inter"></textarea>
          </div>
        </div>

       
        <div class="control-group">
          <label class="control-label" for="address">Address</label>
          <div class="controls">                     
            <textarea id="address" name="address"></textarea>
          </div>
        </div>

        
        <div class="control-group">
          <label class="control-label" for="button1"></label>
          <div class="controls">
            <button id="button1" name="button1" class="btn btn-success">Send</button>
            <button id="button2" name="button2" class="btn btn-danger">Cancel</button>
          </div>
        </div>-->
        
      

    </div> <!-- /container -->


    <?php include 'includes/jsFiles.php' ?>
    <script src="js/validation/dist/jquery.validate.js"></script>
    <script>

      $().ready(function() {

        $.validator.addMethod(
          "regex",
          function(value, element, regexp) {
              var re = new RegExp(regexp);
              return this.optional(element) || re.test(value);
          },
          "Please check your input."
        );

        // validate signup form on keyup and submit
        $(".form-signup").validate({
          rules: {
            name: {
              required: true,
              regex: "^[a-zA-Z ]*$"
            },
            password: {
              required: true,
              minlength: 5,
              regex: "^[a-z0-9]+$"
            },
            confirmpassword: {
              required: true,
              minlength: 5,
              regex: "^[a-z0-9]+$",
              equalTo: "#password"
            },
            email: {
              required: true,
              email: true
            },
            birthday: {
              required: true,
              date: true
            },
            gender: "required"
          },
          messages: {
            name: {
              required: "Please enter your firstname",
              regex: "Only letters and white space allowed"
            },
            password: {
              required: "Please provide a password",
              minlength: "Your password must be at least 5 characters long",
              regex: "Only letters and numbers allowed"
            },
            confirmpassword: {
              required: "Please provide a password",
              minlength: "Your password must be at least 5 characters long",
              regex: "Only letters and numbers allowed",
              equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address",
            birthday: {
              required: "Please provide your birthday",
              date: "Please enter a valid date"
            },
            gender: "Please select a gender"
          }
        });
      });
    </script>
  </body>
</html>
