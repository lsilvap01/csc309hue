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
            <li><a href="./space/new">New Space</a></li>
            <li><a href="#">My Groups</a></li>
            <li class="divider"></li>
            <li><a href="./logout">Log Out</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-right searchForm" role="search">
        <div class="form-group">
          <div class="col-sm-10">
          <input type="text" class="form-control searchField" placeholder="Search Workspaces" name="name"></div>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>