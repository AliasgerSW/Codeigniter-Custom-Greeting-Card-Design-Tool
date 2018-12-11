<?php    $this->load->view('admin/include/head');
//$this->input->cookie('test_cookie', TRUE);
?>
        <!-- Login Content -->
        <div class="content overflow-hidden">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <!-- Login Block -->
                    <div class="block block-themed animated fadeIn">
                        <div class="block-header bg-primary">
                            <ul class="block-options">
                                <li>
                                    <a href="<?php echo base_url('login/forget_password'); ?>">Forgot Password?</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('login/register'); ?>" data-toggle="tooltip" data-placement="left" title="New Account"><i class="si si-plus"></i></a>
                                </li>
                            </ul>
                            <h3 class="block-title">Login</h3>
                        </div>
                        <div class="block-content block-content-full block-content-narrow">
                            <!-- Login Title -->
                            <h1 class="h2 font-w600 push-30-t push-5">Booklet</h1>
                            <p>Welcome, please login.</p>
                            <!-- END Login Title -->
                            <p class="error_msg"><?php if(!empty($msg)){echo $msg;}?></p>

                            <!-- Login Form -->
                            <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
                            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                            <form class="js-validation-login form-horizontal push-30-t push-50" action="<?php echo base_url('login/auth'); ?>" method="post">
							
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-primary floating">
                                            <input class="form-control" type="text" id="login_username" name="login_username">
                                            <label for="login-username">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-primary floating">
                                            <input class="form-control" type="password" id="login_password" name="login_password">
                                            <label for="login-password">Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <!--<label class="css-input switch switch-sm switch-primary">
                                            <input type="checkbox" id="login-remember-me" name="login-remember-me"><span></span> Remember Me?
                                        </label>-->
										<label class="css-input switch switch-sm switch-primary">
                                            <input type="checkbox" id="login-remember-me" name="remember_me"><span></span> Remember Me?
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <button class="btn btn-block btn-primary" type="submit"><i class="si si-login pull-right"></i> Log in</button>
                                    </div>
                                </div>
                            </form>
                            <!-- END Login Form -->
                        </div>
                    </div>
                    <!-- END Login Block -->
                </div>
            </div>
        </div>

       <?php  $this->load->view('admin/include/footer_script'); ?>
       
        <!-- Page JS Plugins -->
        <script src="<?php echo base_url();?>assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>

        <!-- Page JS Code -->
        <script src="<?php echo base_url();?>assets/js/pages/base_pages_login.js"></script>
    </body>
</html>