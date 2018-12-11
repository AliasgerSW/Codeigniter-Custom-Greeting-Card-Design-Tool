<?php  $this->load->view('admin/include/head'); ?>
        <!-- Register Content -->
        <div class="content overflow-hidden">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <!-- Register Block -->
                    <div class="block block-themed animated fadeIn">
                        <div class="block-header bg-success">
                            <ul class="block-options">
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#modal-terms">View Terms</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('login'); ?>" data-toggle="tooltip" data-placement="left" title="Log In"><i class="si si-login"></i></a>
                                </li>
                            </ul>
                            <h3 class="block-title">Register</h3>
                            <?php 
                            $username = $email = $user_address = $user_contact = $user_city = '';
								if(!empty($result))
								{
								 $username     = $result['username'];
								 $email        = $result['email'];
								 $user_address = $result['user_address'];
								 $user_contact = $result['user_contact'];
								 $user_city    = $result['user_city'];
								}
                             ?>
                           </div>
                        <div class="block-content block-content-full block-content-narrow">
                            <!-- Register Title -->
                            <h1 class="h2 font-w600 push-30-t push-5">Booklet</h1>
                            <p>Please fill the following details to create a new account.</p>
                            <!-- END Register Title -->
							<p class="error_msg"><?php if(!empty($msg)){echo $msg;}?></p>
                            <!-- Register Form -->
                            <!-- jQuery Validation (.js-validation-register class is initialized in js/pages/base_pages_register.js) -->
                            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
      <form class="js-validation-register form-horizontal push-50-t push-50" action="<?php echo base_url('login/register');?>" method="post">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="text" id="register_username" name="register_username" placeholder="Please enter a username" value="<?php if(!empty($username)){echo $username;}?>">
                                            <label for="register-username">Username</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="email" id="register_email" name="register_email" placeholder="Please provide your email" value="<?php if(!empty($email)){echo $email;}?>" >
                                            <label for="register-email">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="password" id="register_password" name="register_password" placeholder="Choose a strong password..">
                                            <label for="register-password">Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="password" id="register_password2" name="register_password2" placeholder="..and confirm it">
                                            <label for="register-password2">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="text" id="contact" name="contact" placeholder="Please enter a contact number" value="<?php if(!empty($user_contact)){echo $user_contact;}?>" >
                                            <label for="register-username">Contact</label>
                                        </div>
                                    </div>
                                </div>
								<div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material">
<textarea placeholder="Please add a comment" maxlength="140" rows="3" name="address" id="address" class="form-control"><?php if(!empty($user_address)){echo $user_address;}?></textarea>
                                         <label for="material-textarea-small">Contact Addres</label>
                                                </div>
                                            </div>
                                        </div>
                               
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-success">
                                            <input class="form-control" type="text" id="city" name="city" placeholder="Please enter city" value="<?php if(!empty($user_city)){echo $user_city;}?>" >
                                            <label for="city">City</label>
                                        </div>
                                    </div>
                                </div>
								
								<!-- test code -->
							  
								<div class="form-group">
                                    <div class="col-xs-12">
                                        <label class="css-input switch switch-sm switch-success">
                                            <input type="checkbox" id="register_terms" name="register_terms"><span></span> I agree with terms &amp; conditions
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                        <button class="btn btn-block btn-success" type="submit"><i class="fa fa-plus pull-right"></i> Sign Up</button>
                                    </div>
                                </div>
                            </form>
                            <!-- END Register Form -->
                        </div>
                    </div>
                    <!-- END Register Block -->
                </div>
            </div>
        </div>
        <!-- END Register Content -->

        <!-- Register Footer -->
        <div class="push-10-t text-center animated fadeInUp">
            <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; Booklet 1.0</small>
        </div>
        <!-- END Register Footer -->

        <!-- Terms Modal -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Terms &amp; Conditions</h3>
                        </div>
                        <div class="block-content">
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> I agree</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Terms Modal -->

        <?php  $this->load->view('admin/include/footer_script'); ?>

        <!-- Page JS Plugins -->
        <script src="<?php echo config_item('asset_url'); ?>js/plugins/jquery-validation/jquery.validate.min.js"></script>

        <!-- Page JS Code -->
        <script src="<?php echo config_item('asset_url'); ?>js/pages/base_pages_register.js"></script>
    </body>
</html>
