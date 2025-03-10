<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion UserRegistration will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/user-registration/template-structure/
 * @author  WPEverest
 * @package UserRegistration/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="ur-frontend-form registration-form" id="ur-frontend-form">
        <?php apply_filters('user_registration_login_form_before_notice', ur_print_notices()); ?>
	<form method="post" class="user-registration-ResetPassword lost_reset_password">
		<div class="ur-form-row">
			<div class="ur-form-grid">
				<p><?php echo apply_filters( 'user_registration_lost_password_message', __( 'Forgot password? Please enter your business e-mail. You will receive a link to create a new password via e-mail.', 'user-registration' ) ); ?></p>

				<p class="user-registration-form-row user-registration-form-row--first form-row form-row-first">
					<label for="user_login"><?php _e( 'Business E-mail', 'user-registration' ); ?></label>
					<input class="user-registration-Input user-registration-Input--text input-text" type="text" name="user_login" id="user_login" />
				</p>

				<div class="clear"></div>

				<?php do_action( 'user_registration_lostpassword_form' ); ?>

				<p class="user-registration-form-row form-row">
					<input type="hidden" name="ur_reset_password" value="true" />
					<input type="submit" class="user-registration-Button button btn-primary" style="width: 100%;" value="<?php esc_attr_e( 'Reset password', 'user-registration' ); ?>" />
				</p>

				<?php wp_nonce_field( 'lost_password' ); ?>
			</div>
		</div>
	</form>
</div>
