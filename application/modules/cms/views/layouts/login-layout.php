<?php
use LFramework\Helpers\Html as Html;
use Application\AssetBundles\CmsAsset as CmsAsset;

/* @var $this LFramework\Base\View */
/* @var $cmsAsset IaiAsset */
$cmsAsset = new CmsAsset();
CmsAsset::register($this);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= LFramework::$application->_language; ?>">
<head>
    <meta charset="<?= LFramework::$application->_charset; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= Html::encode($this->_title); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <?php $this->head(); ?>
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= $cmsAsset->getBaseUrl(); ?>/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
    <?php $this->beginBody(); ?>
    
    <!-- CONTENT -->
    <?= $content; ?>
    <!-- END CONTENT -->

<?php $this->endBody(); ?>
<!-- iCheck -->
<script src="<?= $cmsAsset->getBaseUrl(); ?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
<?php $this->endPage(); ?>


