
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
          <a class="navbar-brand" href="#"><?php echo $this->data['appName']; ?></a>
        </div>

      </div>
    </nav>


    <div class="container">

      <form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<h2>SingUp Form</h2>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="email">Email</label>
  <div class="controls">
    <input id="email" name="email" placeholder="" class="input-large" required="" type="text">
    
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="password">Password</label>
  <div class="controls">
    <input id="password" name="password" placeholder="" class="input-xlarge" required="" type="password">
    
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="passwordconf">Password</label>
  <div class="controls">
    <input id="passwordconf" name="passwordconf" placeholder="" class="input-xlarge" required="" type="password">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="fname">First Name</label>
  <div class="controls">
    <input id="fname" name="fname" placeholder="" class="input-large" required="" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="fname">Last Name</label>
  <div class="controls">
    <input id="fname" name="fname" placeholder="" class="input-large" required="" type="text">
    
  </div>
</div>

<!-- Select Basic -->
<div class="control-group">
  <label class="control-label" for="select1">Gender</label>
  <div class="controls">
    <select id="select1" name="select1" class="input-large">
      <option>Female</option>
      <option>Male</option>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="birthday">Birthday</label>
  <div class="controls">
    <input id="birthday" name="birthday" placeholder="" class="input-large" help="MM/DD/YYYY" required="" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="prof">Profession</label>
  <div class="controls">
    <input id="prof" name="prof" placeholder="" class="input-large" required="" type="text">
    
  </div>
</div>

<!-- Textarea -->
<div class="control-group">
  <label class="control-label" for="descript">Self-description</label>
  <div class="controls">                     
    <textarea id="descript" name="descript"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="control-group">
  <label class="control-label" for="profexp">Profissional Experience</label>
  <div class="controls">                     
    <textarea id="profexp" name="profexp"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="control-group">
  <label class="control-label" for="profskills">Profissional Skills</label>
  <div class="controls">                     
    <textarea id="profskills" name="profskills"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="control-group">
  <label class="control-label" for="inter">Fields of Interest</label>
  <div class="controls">                     
    <textarea id="inter" name="inter"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="control-group">
  <label class="control-label" for="address">Address</label>
  <div class="controls">                     
    <textarea id="address" name="address"></textarea>
  </div>
</div>

<!-- Button (Double) -->
<div class="control-group">
  <label class="control-label" for="button1"></label>
  <div class="controls">
    <button id="button1" name="button1" type="submit" class="btn btn-success">Send</button>
  </div>
</div>

</fieldset>
</form>
</div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
