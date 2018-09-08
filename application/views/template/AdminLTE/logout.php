<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dacha Usermanager | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $_tpath; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $_tpath; ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $_tpath; ?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $_tpath; ?>/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $_tpath; ?>/plugins/iCheck/square/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
  <!-- jQuery 3 -->
<script src="<?php echo $_tpath; ?>/bower_components/jquery/dist/jquery.min.js"></script>
<?php
  if (isset($tes)) {
    echo "<pre>";
    print_r($tes);
    echo "</pre>";
  }
  ?>
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Dacha</b>Usermanager</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Please wait, you are being logged out...</p>
    <div class="progress progress-sm active">
      <div id="progressBar" class="progress-bar progress-bar-blue progress-bar-striped" style="width: 0%"></div>
    </div>
    <script type="text/javascript">
      $(document).ready(function() {
        var progress=1;
        var time=5;//second
        progressBar=$('#progressBar');
        function updProgress() {
          if (progress==time) {
            progressBar.css('width', '100%');
            $('.login-box-msg').text('You are logged out');
            document.location='<?php echo base_url('logout/logout'); ?>';
          }
          else{
            progressBar.css('width', ((100/time)*progress)+'%');
            progress++;
          }
          setTimeout(updProgress, 1000);
        }
        updProgress();
      });
    </script>
    
<!--
    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
     /.social-auth-links-->
     <!--
    <a href="#">I forgot my password</a><br>
    <a href="#" class="text-center">Register a new membership</a>
  -->
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo $_tpath; ?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $_tpath; ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo $_tpath; ?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
