<?php include 'includes/sessionConfig.php'; ?>
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
          <a class="navbar-brand" href="./"><?php echo $this->data['appName']; ?></a>
        </div>

      </div>
    </nav>


    <div class="container">
       <form class="form-signup" roel="form" method="GET">
        <h2 class="form-signup-heading">Search</h2>
        <div class="form-group">
            
            <input id="name" type="text" class="form-control input-md" name="name" placeholder="Search">
        </div>
        <span class="input-group-btn">
            <button type="button" class="btn btn-default btnSearch">
              <span class="glyphicon glyphicon-search"> Search</span>
            </button>
        </span>
        <div class="col-sm-8">
                <!-- This table is where the data is display. -->
                    <table id="resultTable" class="table table-striped table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
       </form>

      
    </div> <!-- /container -->
    <?php include 'includes/jsFiles.php' ?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('.btnSearch').click(function(){
          makeAjaxRequest();
        });

            $('form').submit(function(e){
                e.preventDefault();
                makeAjaxRequest();
                return false;
            });

            function makeAjaxRequest() {
                $.ajax({
                    url: 'searchConn.php',
                    type: 'get',
                    data: {name: $('input#search').val()},
                    success: function(response) {
                        $('table#resultTable tbody').html(response);
                    }
                });
            }
      });
    </script>
   
  </body>
</html>