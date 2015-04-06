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
          <h2>Space <?php echo $this->data['space']['name']; ?> > <?php echo $this->data['team']['name']; ?></h2>
          <a class="btn btn-success" href="./space/<?php echo $this->data['space']['idSpace'] ?>" role="button">See space details</a>
      </div> 
    </div>
	
	
    <div class="container">
     
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