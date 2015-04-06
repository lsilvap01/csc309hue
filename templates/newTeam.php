<?php include 'includes/sessionConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'includes/headers.php'; ?>

    <title>New Coworking Space || <? echo $this->data['appName']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

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

        <form class="form-horizontal form-validation" id="newteam" action="./space/<?php echo $this->data['space']['idSpace'] ?>/teams/new" method="POST">
          <fieldset>

          <!-- Form Name -->
          <h2><?php echo $this->data['space']['name']; ?> > New team</h2>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="name">Name</label>  
            <div class="col-md-4">
              <input id="name" maxlength="50" name="name" type="text" value="<?php echo $this->data['name']; ?>" placeholder="" class="form-control input-md" required />
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-4 control-label" for="members">Members</label>  
            <div class="col-md-4">
              <select id="members" name="members[]" multiple="multiple">
                <?php echo "<option value='".$this->data['space']['idOwner']."'".(in_array($this->data['space']['idOwner'], $this->data['members'])?" selected='selected'":"").">".getUserById($this->data['space']['idOwner'])['name']."</option>";?>
                <?php 
                  $myMembers = getSpaceMembers($this->data['space']['idSpace']);
                  foreach ($myMembers as $member) {
                    if(intval($member['idUser']) != intval($_SESSION['userID']))
                    {
                      echo "<option value='".$member['idUser']."'".(in_array($member['idUser'], $this->data['members'])?" selected='selected'":"").">".$member["name"]."</option>";
                    }
                  }
                ?>
              </select>
            </div>
          </div>

          <!-- Button (Double) -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="send"></label>
            <div class="col-md-8">
              <button id="send" name="send" class="btn btn-success" type="submit">Submit</button>
              <button id="cancel" name="cancel" class="btn btn-danger">Cancel</button>
            </div>
          </div>
          <?php if ($this->data['error']) { ?> <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $this->data['error']; ?></div> <?php } ?>
          </fieldset>

        </form>
      </div>
    </div>    


    <?php include 'includes/jsFiles.php'; ?>
    <script src="js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <script src="js/validation/dist/jquery.validate.js"></script>
    <script src="js/validation/dist/additional-methods.min.js"></script>
    <script>

      $().ready(function() {
        $('#members').multiselect({
            enableFiltering: true
        });

        $.validator.addMethod(
          "regex",
          function(value, element, regexp) {
              var re = new RegExp(regexp);
              return this.optional(element) || re.test(value);
          },
          "Please check your input."
        );

        $.validator.addMethod(
          "currency", 
          function (value, element) {
            return this.optional(element) || /^\$?[0-9][0-9\,]*(\.\d{1,2})?$|^\$?[\.]([\d][\d]?)$/.test(value);
          }, 
          "Please specify a valid amount");

        // validate signup form on keyup and submit
        $("#newteam").validate({
          rules: {
            name: {
              required: true,
              maxlength:50,
              regex: "^[a-zA-Z0-9 ]*$"
            }
          },
          messages: {
            name: {
              required: "Please enter a name",
              maxlength: "The name must be at most 50 characters long",
              regex: "Only letters, numbers and white space allowed"
            }
          }
        });
      });
    </script>
  </body>
</html>
