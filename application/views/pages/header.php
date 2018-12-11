<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<title>Welcome to Questionnaire</title>
<meta name="description" content="">
<meta name="author" content="">

<!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo config_item('asset_url'); ?>js/menu_jquery.js'></script>

<!----Font------>
<link href='http://fonts.googleapis.com/css?family=Tangerine:400,700|Crimson+Text:400,400italic,600,600italic,700,700italic|Playball' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Calligraffitti|Raleway:400,100,200,300,500,600,700,800,900|Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

<!-- Bootstrap Stylesheets -->
<link href="<?php echo config_item('asset_url'); ?>bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">

<!-- Main Stylesheets -->
<link href="<?php echo config_item('asset_url'); ?>css/custom.css" rel="stylesheet" type="text/css">
<?php if($key == 'booklettemplate'){ ?>

<!-- Booklet Stylesheets -->

<link href="<?php echo config_item('asset_url'); ?>css/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo config_item('asset_url'); ?>css/jquery.Jcrop.css" rel="stylesheet" type="text/css">
<?php } ?>

<!-- Favicon Icon -->
<link rel="shortcut icon" href="<?php echo config_item('media_url'); ?>images/icons/favicon.ico">

<!-- Google web font ---->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Marcellus+SC' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Inconsolata:400,700' rel='stylesheet' type='text/css'>
<script src="<?php echo config_item('asset_url'); ?>js/jquery-1.11.3.min.js"></script>
<script src="<?php echo config_item('asset_url'); ?>bootstrap/js/bootstrap.min.js"></script>
<?php if($key == 'booklettemplate'){ ?>
<script src="<?php echo config_item('asset_url'); ?>js/tinymce/tinymce.min.js"></script>
<script src="<?php echo config_item('asset_url'); ?>js/booklet.js"></script>
<script src="<?php echo config_item('asset_url'); ?>js/jquery.Jcrop.js"></script>
<script>tinymce.init({selector:'textarea'});</script>
<?php } ?>
</head>

<body>

<!--<div class="padtop-15"></div>--> 

<!---------Top Bar------->

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3">
      <div class="line-pad"></div>
      <div class="logo"> <img src="<?php echo config_item('media_url'); ?>images/icon/logo.png" class="img-responsive"> </div>
    </div>
    
    <!---------->
    
    <div class="col-xs-12 col-sm-4 col-md-4"></div>
    <div class="col-xs-12 col-sm-4 col-md-5">
      <?php
	  $userid = get_sessionData('user_id');
	  $firstname = get_sessionData('firstname');
	  $lastname = get_sessionData('lastname');
	  ?>
      <div class="register">
        <?php 
		if(isset($userid) && !empty($userid)){
			echo 'Welcome ' . $firstname . ' ' . $lastname;
		  }
		?>
        <ul class="list-inline top">
          <?php if(isset($userid) && !empty($userid)){  ?>
          <li> <a href="<?php echo config_item('base_url'). 'login/logout' ?>">SIGN OUT</a></li>
          <?php } else {?>
          <li><a href="<?php echo config_item('base_url'). 'login' ?>">SIGN IN</a></li>
          <li class="line-pad"><a href="#"><img src="<?php echo config_item('media_url'); ?>images/icon/right-line.png" class="img-responsive" /></a></li>
          <li><a href="<?php echo config_item('base_url'). 'login/register' ?>">REGISTER</a></li>
          <?php }?>
          <li class="line-pad"><a href="#"><img src="<?php echo config_item('media_url'); ?>images/icon/right-line.png" class="img-responsive" /></a></li>
          <?php $cartitems = $this->cart->total_items();
		  if ($cartitems > 0) { ?>
          <li><a href="<?php echo config_item('base_url') ?>cart/showcart">CART(<?php echo $cartitems ?>)</a></li>
          <?php } else { ?>
          <li>CART(0)</li>
          <?php } ?>
          <li class="line-pad"><a href="#"><img src="<?php echo config_item('media_url'); ?>images/icon/right-line.png" class="img-responsive" /></a></li>
          <li><a href="#">HELP</a></li>
        </ul>
      </div>
      
      <!---------->
      
      <div class="row">
        <div class="col-md-12">
          <div class="contact-top"> <i class="fa fa-phone br-green"></i>&nbsp;&nbsp;<span> 01768899063</span> </div>
          <div id="custom-search-input" class="search-bar">
            <div class="input-group col-md-12"> <span class="input-group-btn">
              <button class="btn btn-danger" type="button"><i class="fa fa-search"></i> </button>
              </span>
              <input type="text" class="  search-query form-control" placeholder="Search" />
            </div>
          </div>
        </div>
      </div>
      
      <!-----------> 
      
    </div>
  </div>
</div>
<div class="line-pad"></div>

<!----------------First Container End----->

<div class="nav-menu">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div id='cssmenu'>
          <ul>
            <?php $baseurl = config_item('base_url'); ?>
            <?php /*?><li><a href='<?php echo $baseurl; ?>'><span>Home</span></a></li>
            <li><a href='<?php echo $baseurl; ?>content/page/sample'><span>Samples</span></a></li>
            <li class='has-sub'><a href='javascript:void()'><span>Categories</span></a>
              <ul>
                <li><a href='<?php echo $baseurl; ?>product/category/1'><span>Funeral</span></a></li>
                <li><a href='<?php echo $baseurl; ?>product/category/2'><span>Other</span></a></li>
              </ul>
            </li>
            <li><a href='<?php echo $baseurl; ?>content/page/contact-us'><span>Contact Us</span></a></li>
            <li class='has-sub'><a href='javascript:void()'><span>Policy</span></a>
              <ul>
                <li><a href="<?php echo $baseurl; ?>content/page/terms-of-use">Terms Of Use</a></li>
                <li><a href="<?php echo $baseurl; ?>content/page/privacy-policy">Privacy Policy</a></li>
                <li class='last'><a href="<?php echo $baseurl; ?>content/page/cookie-policy">Cookie Policy</a></li>
              </ul>
            </li>
            <li><a href='<?php echo $baseurl; ?>product/allproducts'><span>Create Online</span></a></li>
          </ul><?php */?>
          <ul>
          <?php
		  if (!empty($menulist) && count($menulist) > 0){
			  $menuurl = '';
			  foreach($menulist as $Mkey => $Mval){
				  $menuurl = $baseurl . $Mval['0']['url'];
				  if (count($Mval) > 1){
					  echo "<li class='has-sub'><a href='" . $menuurl. "'><span>" . $Mval['0']['name'] . "</span></a>";
					  echo '<ul>';
					  foreach($Mval as $SKey => $Sval){
						  if ($SKey == '0') continue;
						  $menuurl = $baseurl . $Sval['url'];
						  echo "<li><a href='" . $menuurl. "'><span>" . $Sval['name'] . "</span></a></li>";  
					  }
  					  echo '</li></ul>';
				  } else {
					  echo "<li><a href='" . $menuurl . "'><span>" . $Mval['0']['name'] . "</span></a></li>";
				  }
			  }
		  }
		  ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="color-divider"></div>
<div class="clearfix"></div>
