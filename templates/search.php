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
       <form class="form-signup" role="form" action="./search" method="GET">
        
        <div class="form-group">
            <?php if ($this->data['search']) {
              echo '<input id="name" type="text" class="form-control input-md" name="name" placeholder="'.$this->data['search'].'"';
             } else { ?>
              <input id="name" type="text" class="form-control" name="name" placeholder="Search Spaces">
             <?php } ?>
        </div>
        <span class="input-group-btn">
            <button type="button" class="btn btn-default btnSearch">
              <span class="glyphicon glyphicon-search"> </span>
            </button>
        </span>
        <div class="col-sm-8">
                <!-- This table is where the data is display. -->
                    <table id="resultTable" class="table table-striped table-hover">
                        <thead>
                            <th>Space Name</th>
                            <th>Owner</th>
                            <th>Price</th>
                            <th>Address</th>
                            <th>Vacancies</th>
                        </thead>
                        <tbody>
                          <?php if ($this->data['rows']) {
                            $rows = $this->data['rows'];
                            foreach ($rows as $row) {
                              echo "<tr>";
                                echo "<td>".$row['space']."</td>";
                                echo "<td>".$row['name']."</td>";
                                echo "<td>".$row['price']."</td>";
                                echo "<td>".$row['address']."</td>";
                                echo "<td>".$row['availableVacancies']."</td>";
                              echo "</tr>";
                            } //end of foreach
                          } //end of if ?>
                        </tbody>
                    </table>
                </div>
       </form>

      
    </div> 

    <div class="container">
      <!-- Example row of columns -->
      <h1>Results</h1>
      
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
      ?>
    </div><!-- /container -->
    <?php include 'includes/jsFiles.php' ?>
    
   
  </body>
</html>