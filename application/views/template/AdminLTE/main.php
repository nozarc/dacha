<!DOCTYPE html>
<html>
<head>
	<?php echo $_htmlhead;?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php echo $_jscript; ?>
<?php
	if (isset($debug)) {
		echo "<pre>";
		print_r($debug);
		echo "</pre>";
	}
?>
	<div class="wrapper">

		<header class="main-header">
			<?php echo $_mainheader; ?>
		</header>

		<!-- Left side column. contains the logo and sidebar -->
  		<aside class="main-sidebar">
  			<?php echo $_mainsidebar; ?>
  		</aside>

  		<!-- Content Wrapper. Contains page content -->
  		<div class="content-wrapper">
  			<?php echo $_content; ?>
  		</div>
  		<!-- /.content-wrapper -->

  		<footer class="main-footer">
  			<?php echo $_mainfooter; ?>
  		</footer>

  		<!-- Control Sidebar -->
  		<aside class="control-sidebar control-sidebar-dark">
  			<?php echo $_ctrlsidebar; ?>
  		</aside>
  		<!-- /.control-sidebar -->
		<!-- Add the sidebar's background. This div must be placed
	    immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>
	</div>
</body>
</html>