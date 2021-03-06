<?php include 'includes/sessionConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include 'includes/headers.php'; ?>

    <title>Space <?php echo $this->data['space']['name']; ?> || <?php echo $this->data['appName']; ?></title>

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
          <h2>Space <?php echo $this->data['space']['name']; ?></h2>
          <?php if(!userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace'])) { 
                  if(userHasSentRequestToSpace($_SESSION['userID'], $this->data['space']['idSpace'])) { ?>
                    <a class="btn btn-success" role="button">Request sent</a>
            <?php } 
                  else 
                  { ?>
                    <a class="btn btn-success sendMembershipRequest" href="#" role="button">Become a member</a>
          <?php 
                  }
                }
                else 
                { ?>
                  <a class="btn btn-success" href="./space/<?php echo $this->data['space']['idSpace'] ?>/posts" role="button">See posts</a>
          <?php } ?>      
          <?php echo "<h3>Owner: <a href='./user/".$this->data['space']['idOwner']."'>".getUserById($this->data['space']['idOwner'])['name']."</a></h3>";?>
          <div class="rating" data-average="<?php echo getSpaceRate($this->data['space']['idSpace']); ?>" data-id="<?php echo $this->data['space']['idSpace'];?>"></div> <?php echo getSpaceRate($this->data['space']['idSpace']); ?>/20
          <?php echo userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace'])? ((intval($this->data['space']['idOwner']) == intval($_SESSION['userID']))? "(*You cannot rate your own coworking space)" : "") :"(*You have to be a member to rate this coworking space)"; ?>
          <p><?php echo $this->data['space']['description']; ?> </p>
      </div> 
    </div>
	
	
    <div class="container">
      <div> 
      	<?php 	
        	$idSpace = $this->data['space']['idSpace'];
        	$sql = "SELECT * FROM photo WHERE idSpace = :idSpace LIMIT 1";
        	try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":idSpace", $idSpace);
            $stmt->execute();
            $photo = $stmt->fetch();
            $db = null; 
            if($photo) echo '<img src="uploads/'.$photo["url"].'" alt="'.$photo["url"].'"width="500">';
        	}
          catch(PDOException $e) {
          }	
      	?>
      </div >
      <?php if(intval($this->data['space']['idOwner']) == intval($_SESSION['userID'])) { ?>
        <h3> Membership Requests </h3> 
        <ol>
          <?php 
            $myMembers = getSpaceRequests($this->data['space']['idSpace']);
            $count = 0;
            foreach ($myMembers as $member) {
              $count++;
              echo "<li id='request".$member['idUser']."'> <a href='./user/".$member['idUser']."' id='userName".$member['idUser']."'>".$member["name"]."</a> <a href='space/".$this->data['space']['idSpace']."/requestmembership/".$member['idUser']."/accept' user-id='".$member['idUser']."' class='acceptRequest'>Accept</a> | <a href='space/".$this->data['space']['idSpace']."/requestmembership/".$member['idUser']."' user-id='".$member['idUser']."' class='rejectRequest'>Reject</a>  </li>";
            }
            if($count==0) echo "<li>There is no request</li>";
          ?>
        </ol>
      <?php } ?>

      <h3> Members </h3> 
      <ol id="spaceMembers">
        <?php echo "<li><a href='./user/".$this->data['space']['idOwner']."'>".getUserById($this->data['space']['idOwner'])['name']."</a></li>";?>
        <?php 
        	$myMembers = getSpaceMembers($this->data['space']['idSpace']);
          foreach ($myMembers as $member) {
            echo "<li> <a href='./user/".$member['idUser']."'>".$member["name"]."</a></li>";
        	}
        ?>
      </ol>

      <?php if(userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace'])) { ?>
      <h3> Teams </h3> 
      <ol id="spaceTeams">
        <?php 
          $teams = getSpaceTeams($this->data['space']['idSpace']);
          foreach ($teams as $team) {
            echo "<li> <a href='./space/".$this->data['space']['idSpace']."/team/".$team['idTeam']."'>".$team["name"]."</a></li>";
          }
        ?>
        <li><a href="./space/<?php echo $this->data['space']['idSpace'] ?>/teams/new">New team</a></li>
      </ol>
      <?php } ?>
    </div> <!-- /container -->


   <?php include 'includes/jsFiles.php' ?>
   <script type="text/javascript" src="js/jRatingSpace.jquery.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        <?php if(!userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace']) || intval($this->data['space']['idOwner']) == intval($_SESSION['userID'])) { ?>
        <?php if(!userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace']) && !userHasSentRequestToSpace($_SESSION['userID'], $this->data['space']['idSpace'])) { ?>  
          $('.sendMembershipRequest').click(function(e){
            e.preventDefault();
            if(!$(this).hasClass("sent"))
            {
              $this = $(this);
              $(this).text("Sending...");
              $.ajax({
                url: "space/<?php echo $this->data['space']['idSpace']; ?>/requestmembership",
                success: function(data, textStatus, jqXHR) {
                  if(data.error)
                  {
                    alert(data.message);
                    $this.text("Become a member");
                  }
                  else
                  {
                    $this.text("Request sent");
                    $this.addClass('sent');
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  alert(textStatus);
                }
              });
            }
          });
        <?php } ?> 
        <?php if(intval($this->data['space']['idOwner']) == intval($_SESSION['userID'])) { ?>  
          $('.acceptRequest').click(function(e){
            e.preventDefault();
            $userId = $(this).attr('user-id');
            $userName = $('#userName' + $userId).text();
            $this = $(this);
            $.ajax({
              url: $this.attr('href'),
              success: function(data, textStatus, jqXHR) {
                alert(data.message);
                if(!data.error)
                {
                  $('#request'+$userId).remove();
                  $('#spaceMembers').append("<li> <a href='./user/"+$userId+"'>"+$userName+"</a></li>");
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
              }
            });
            
          });

          $('.rejectRequest').click(function(e){
            e.preventDefault();
            $userId = $(this).attr('user-id');
            $userName = $('#userName' + $userId).text();
            $this = $(this);
            $.ajax({
              url: $this.attr('href'),
              type: "delete",
              success: function(data, textStatus, jqXHR) {
                alert(data.message);
                if(!data.error)
                {
                  $('#request'+$userId).remove();
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
              }
            });
          });
        <?php } ?>  
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