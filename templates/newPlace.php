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

        <form class="form-horizontal form-validation" id="newspace" action="./space/new" method="POST" enctype="multipart/form-data">
          <fieldset>

          <!-- Form Name -->
          <h2>Registration of workspace</h2>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="name">Name</label>  
            <div class="col-md-4">
            <input id="name" maxlength="50" name="name" type="text"value="<?php echo $this->data['name']; ?>" placeholder="" class="form-control input-md" required />
              
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="address">Address</label>  
            <div class="col-md-6">
            <input id="address" maxlength="150" name="address" type="text"value="<?php echo $this->data['address']; ?>" placeholder="" class="form-control input-md" required />
              
            </div>
          </div>

          <!-- Prepended text-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="price">Price</label>
            <div class="col-md-4">
                <input id="price" name="price" class="form-control" placeholder="$ 00.00" type="text"value="<?php echo $this->data['price']; ?>" required />
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="numberSpots">Number of available spots</label>  
            <div class="col-md-2">
            <input id="numberSpots" name="numberSpots" type="text"value="<?php echo $this->data['numberSpots']; ?>" placeholder="" class="form-control input-md" required />
              
            </div>
          </div>

          <!-- Textarea -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="description">Description</label>
            <div class="col-md-4">                     
              <textarea class="form-control" id="description" name="description"><?php echo $this->data['description']; ?></textarea>
            </div>
          </div>

          <!-- File Button -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="lease">Lease Agreement</label>
            <div class="col-md-4">                     
              <input id="lease" name="lease" class="input-file" type="file">
            </div>
          </div>

          <!-- File Button --> 
          <div class="form-group">
            <label class="col-md-4 control-label" for="photo">Photo</label>
            <div class="col-md-4">
              <input id="photo" name="photo" class="input-file" type="file">
            </div>
          </div>

          <!-- Button (Double) -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="send"></label>
            <div class="col-md-8">
              <button id="send" name="send" class="btn btn-success" type="submit">Send</button>
              <button id="cancel" name="cancel" class="btn btn-danger">Cancel</button>
            </div>
          </div>
          <?php if ($this->data['error']) { ?> <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $this->data['error']; ?></div> <?php } ?>
          </fieldset>

        </form>
      </div>
    </div>    


    <?php include 'includes/jsFiles.php'; ?>
    <script src="js/validation/dist/jquery.validate.js"></script>
    <script src="js/validation/dist/additional-methods.min.js"></script>
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

        $.validator.addMethod(
          "currency", 
          function (value, element) {
            return this.optional(element) || /^\$?[0-9][0-9\,]*(\.\d{1,2})?$|^\$?[\.]([\d][\d]?)$/.test(value);
          }, 
          "Please specify a valid amount");

        // validate signup form on keyup and submit
        $("#newspace").validate({
          rules: {
            name: {
              required: true,
              maxlength:50,
              regex: "^[a-zA-Z0-9 ]*$"
            },
            address: {
              required: true,
              maxlength: 150
            },
            price: {
              required: true,
              currency: true
            },
            numberSpots: {
              required: true,
              digits: true,
              min: 0
            },
            lease: {
              extension: "pdf|doc|docx"
            },
            photo: {
              extension: "jpg|jpeg|png"
            }
          },
          messages: {
            name: {
              required: "Please enter a name",
              maxlength: "The name must be at most 50 characters long",
              regex: "Only letters, numbers and white space allowed"
            },
            address: {
              required: "Please provide an address",
              maxlength: "The address must be at most 50 characters long"
            },
            price: {
              required: "Please provide a price"
            },
            numberSpots: {
              required: "Please provide the number of available spots"
            },
            lease: {
              extension: "Only the following extensions are allowed: .pdf|.doc|.docx"
            },
            photo: {
              extension: "Only the following extensions are allowed: .jpg|.jpeg|.png"
            } 
          }
        });
      });
    </script>
  </body>
</html>
