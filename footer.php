  	 <div id="footer">Copyright &copy; <?php echo date("Y");?>, DARUSO CoICT <i>prepared by Ryoba.</i> </div>
	  </body>
</html>
	<?php
	global $connectparam;
	  if (isset($connectparam)){
	  mysqli_close($connectparam);
	  }
	?>