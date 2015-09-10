<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( isset( $on_hold_message ) ) { 
  // EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
  $hold_minutes = strval( (int) config_item('seconds_on_hold') / 60 );
?>
  
<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
    <h1>Excessive Login Attempts</h1>
  </div>
  <div>
    <p>You have exceeded the maximum number of failed login attempts that this website will allow.</p>
    <p>Your access to login and account recovery has been blocked for <?php echo $hold_minutes; ?> minutes.</p>
    <p>Please use <a href="<?php echo secure_site_url('auth/recover'); ?>">Account Recovery</a> after 
    <?php echo $hold_minutes; ?> minutes has passed, or contact us if you require assistance gaining access to your account.</p>
  </div>
  </div>
</section>

<?php } elseif ( $this->input->get('logout') ) { ?>

<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
    <h1>Logged Out</h1>
  </div>
  <div>
  <p>You have successfully logged out.</p>
  <p><a href="<?php echo secure_site_url('login'); ?>">Log In</a><br>
  <a href="<?php echo site_url('welcome'); ?>">Home</a></p>
  </div>
  </div>
</section>

<?php 
} else {
?>

<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
      <h1>Log In with Google</h1>
    </div>
      <?php echo form_open( $login_url ); ?>
        <div><input type="submit" id="submit_button" name="submit" value="Google" /></div>
      </form>
  </div>
</section>

<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
      <h1>Log In with Email and Password</h1>
    </div>
  <?php if ( isset( $login_error_mesg ) ) { ?>
    <div><p>Login Error: Invalid email address or password. Passwords are case sensitive.</p></div>
  <?php } ?>
    <div class="mdl-card__supporting-text">
      <?php echo form_open( $login_url ); ?>
        <div><label for="login_string">Email address</label><br>
          <input type="text" id="login_string" name="login_string" size="60" /></div>
        <div><label for="login_pass">Password</label><br>
          <input type="password" id="login_pass" name="login_pass" maxlength="<?php echo config_item('max_chars_for_password'); ?>" /></div>
        <?php if( config_item('allow_remember_me') ) { ?>
        <div></div><label for="remember_me" class="form_label">Remember Me</label><br>
          <input type="checkbox" id="remember_me" name="remember_me" value="yes" /></div>
        <?php } ?>
        <div><input type="submit" id="submit_button" name="submit" value="Login" /></div>
      </form>
      <p><a href="<?php echo secure_site_url('auth/recover'); ?>">Can't access your account?</a><br>
      <a href="<?php echo site_url('welcome'); ?>">Home</a></p>
    </div>
  </div>
</section>

<?php } 
