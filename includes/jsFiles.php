<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
		  $(".searchForm").submit(function(event) {
		    event.preventDefault();
		    window.location.replace("search/" + encodeURIComponent($(this).find('.searchField').val()));
		  });
		});
	</script>