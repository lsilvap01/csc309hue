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
          <h2>Search</h2>
          <form class="form-signup searchForm" role="search">
            <div class="form-group">
                 <div class="input-group">
                  <input type="text" value="<?php echo isset($this->data['query'])? $this->data['query'] : ""; ?>" class="form-control searchField" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default btnSearch">
                      <span class="glyphicon glyphicon-search"> </span>
                    </button>
                  </span>
                </div>
            </div>
          </form>
      </div> 
    </div>
    

    <div class="container">
      <?php if(isset($this->data['results']))
      { ?>
      <h1>Results for "<?php echo $this->data['query']; ?>"</h1>
      
        <?php $results = $this->data['results'];
              $count = 0; 
              foreach ($results as $space) { 
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
                echo '<h3>No results.</h3>';
              }
        }
      ?>

    </div> <!-- /container -->


    <?php include 'includes/jsFiles.php'; ?>
  </body>
</html>