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
          <a class="btn btn-success" href="./space/<?php echo $this->data['space']['idSpace'] ?>" role="button">See space details</a>
          
          <?php echo "<h3>Owner: <a href='./user/".$this->data['space']['idOwner']."'>".getUserById($this->data['space']['idOwner'])['name']."</a></h3>";?>
          <div class="rating" data-average="<?php echo getSpaceRate($this->data['space']['idSpace']); ?>" data-id="<?php echo $this->data['space']['idSpace'];?>"></div> <?php echo getSpaceRate($this->data['space']['idSpace']); ?>/20
          <?php echo userIsMemberOfSpace($_SESSION['userID'], $this->data['space']['idSpace'])? ((intval($this->data['space']['idOwner']) == intval($_SESSION['userID']))? "(*You cannot rate your own coworking space)" : "") :"(*You have to be a member to rate this coworking space)"; ?>
          <p><?php echo $this->data['space']['description']; ?> </p>
      </div> 
    </div>
	
	
    <div class="container">
      
      <form class="form-horizontal form-validation newspacepost" action="" method="POST">
        <fieldset>
        <div class="form-group">
          <label class="col-md-4 control-label" for="description">New post</label>
          <div class="col-md-4">                     
            <textarea class="form-control message" name="description"></textarea>
          </div>
        </div>
        <!-- Button (Double) -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="send"></label>
          <div class="col-md-8">
            <button id="send" name="send" class="btn btn-success" type="submit">Submit</button>
            <button id="cancel" name="cancel" class="btn btn-danger" type="reset">Cancel</button>
          </div>
        </div>
        </fieldset>
      </form>
      <h3>Posts</h3>
      <ul id="spacePosts">
        <?php 
        	$posts = $this->data['posts'];
          foreach ($posts as $post) {
            if($post['idTenant'])
            {
              $u = getUserByIdTenant($post['idTenant']);
            }
            else
            {
              $u = getSpaceOwner($this->data['space']['idSpace']);
            }
            echo "<li><a href='./user/".$u['idUser']."'>".$u["name"]."</a>: ".$post["message"]."</li>"; ?>
            <li>
              <ul id="commentsPost<?php echo $post['idSpacePost'] ?>">
                <?php 
                  $comments = $post['comments'];
                  foreach ($comments as $comment) {
                    if($comment['idTenant'])
                    {
                      $us = getUserByIdTenant($comment['idTenant']);
                    }
                    else
                    {
                      $us = getSpaceOwner($this->data['space']['idSpace']);
                    }
                    echo "<li><a href='./user/".$us['idUser']."'>".$us["name"]."</a>: ".$comment["message"]."</li>";
                  }
                ?>
                <li><a href="#" class="replyPost" post-id="<?php echo $post['idSpacePost'] ?>">Reply</a></li>
              </ul>
            </li>
        <?php	} 
        ?>
      </ul>
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

        $(document).on("submit", ".newspacepost", function(event) {
          $replyTo = $(this).attr('reply-to');
          if(!$replyTo) 
          {
            $replyTo = 0;
          }
          $(this).find('.message').prop("disabled", true);
          $(this).find('[type="submit"]').prop("disabled", true);
          $(this).find('[type="submit"]').val('Sending...');
          $message = $.trim($(this).find('.message').val()).replace(/\n/g, "<br />");
          $this = $(this);
          $.ajax({
            type: "POST",
            url: "./space/<?php echo $this->data['space']['idSpace'] ?>/post",
            data: {
              message: $message,
              replyTo: $replyTo
            },
            success: function(data, textStatus, jqXHR) {
              $this.find('.message').prop("disabled", false);
              $(this).find('[type="submit"]').val('Submit');
              $this.find('[type="submit"]').prop("disabled", false);
              if(data.error)
              {
                alert(data.message);
              }
              else
              {
                $this.find('.message').val("");
                if($replyTo == 0)
                {
                  $post = "<li><a href='./user/<?php echo $_SESSION['userID'] ?>'><?php echo $_SESSION['userName'] ?></a>: " + $message + "</li>";
                  $post = $post + "<li>"
                  $post = $post + "<ul id='commentsPost" + data.idPost + "'>"
                  $post = $post + "<li><a href='#' class='replyPost' post-id='" + data.idPost + "'>Reply</a></li>"
                  $post = $post + "</ul>";
                  $post = $post + "</li>";
                  $('#spacePosts').prepend($post);
                }
                else
                {
                  $post = "<li><a href='./user/<?php echo $_SESSION['userID'] ?>'><?php echo $_SESSION['userName'] ?></a>: " + $message + "</li>";
                  $formComment = $('#commentsPost' + $replyTo+ " li").last();
                  $($post).insertBefore($formComment);
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              alert(textStatus);
            }
          });
          event.preventDefault();
        });
        
        $(document).on("click", '.replyPost', function(e){
          e.preventDefault();
          $postId = $(this).attr('post-id');
          $liLink = $(this).parent();
          console.log($liLink);

          $commentForm = "<li>"
          $commentForm = $commentForm + "<form class='form-horizontal form-validation newspacepost' action='' reply-to='" + $postId + "' method='POST'>";
          $commentForm = $commentForm + "<fieldset>";
          $commentForm = $commentForm + "<div class='form-group'>";
          $commentForm = $commentForm + "<label class='col-md-4 control-label' for='description'>Comment</label>";
          $commentForm = $commentForm + "<div class='col-md-4'>";                    
          $commentForm = $commentForm + "<textarea class='form-control message' name='description'></textarea>";
          $commentForm = $commentForm + "</div>";
          $commentForm = $commentForm + "</div>";
          $commentForm = $commentForm + "<div class='form-group'>";
          $commentForm = $commentForm + "<label class='col-md-4 control-label' for='send'></label>";
          $commentForm = $commentForm + "<div class='col-md-8'>";
          $commentForm = $commentForm + "<button name='send' class='btn btn-success' type='submit'>Submit</button>";
          $commentForm = $commentForm + "<button name='cancel' class='btn btn-danger' type='reset'>Cancel</button>";
          $commentForm = $commentForm + "</div>";
          $commentForm = $commentForm + "</div>";
          $commentForm = $commentForm + "</fieldset>";
          $commentForm = $commentForm + "</form>";
          $commentForm = $commentForm + "</li>";
          
          $($commentForm).insertAfter($liLink);
          $liLink.remove();
        });
      });
    </script>
  </body>
</html>