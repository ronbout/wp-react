<?php
/**
 * Template override of the Account Sign in Section of the Registration Page
 * Needed some customization in styling and removal of Sign Out button
 *
 * @author      Ron Boutilier
 * @package     WP Job Manager - Resume Manager
 * @category    Template
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() ) : ?>

	<fieldset class="fieldset-account-signed-in">
		<label><?php /* _e( 'Your account', 'wp-job-manager-resumes' );*/ ?></label>
		<div class="field account-signed-in">
			<?php
				$user = wp_get_current_user();

				// rlb - change Logged in User msg, retrieving the full name from user_meta
				$user_meta = get_user_meta($user->ID);
				$full_name = $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0];
				printf( __( '<span>Welcome back, </span><span id="display-name"><strong>%s</strong></span>.', 'wp-job-manager-resumes' ), $full_name );
			?>

<?php // rlb - remove the sign out button from the form ?>
		</div>
	</fieldset>

<?php else :

	$account_required             = resume_manager_user_requires_account();
	$registration_enabled         = resume_manager_enable_registration();
	$generate_username_from_email = resume_manager_generate_username_from_email();
	?>
	<fieldset class="fieldset-account-sign-in">
		<label id="sign-in-label"><?php _e( 'Have an account?', 'wp-job-manager-resumes' ); ?>
		<a class="button" href="<?php echo apply_filters( 'submit_resume_form_login_url', wp_login_url( add_query_arg( array( 'job_id' => $class->get_job_id() ), get_permalink() ) ) ); ?>"><?php _e( 'Sign in', 'wp-job-manager-resumes' ); ?></a>
	</label>
		<div class="field account-sign-in">
			

			<?php if ( $registration_enabled ) : ?>

				<?php _e( 'If you don&rsquo;t have an account you can create one below by entering your email address. Your account details will be confirmed via email.', 'wp-job-manager-resumes' ); ?>

			<?php elseif ( $account_required ) : ?>

				<?php echo apply_filters( 'submit_resume_form_login_required_message',  __( 'You must sign in to submit a resume.', 'wp-job-manager-resumes' ) ); ?>

			<?php endif; ?>
		</div>
	</fieldset>
	<?php if ( $registration_enabled ) : ?>
		<?php if ( ! $generate_username_from_email ) : ?>
			<fieldset class="fieldset-username">
				<label><?php _e( 'Username', 'wp-job-manager-resumes' ); ?> <?php echo apply_filters( 'submit_resume_form_required_label', ( ! $account_required ) ? ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>' : '' ); ?></label>
				<div class="field">
					<input type="text" class="input-text" name="create_account_username" id="account_username" value="<?php if ( ! empty( $_POST['create_account_username'] ) ) echo sanitize_text_field( stripslashes( $_POST['create_account_username'] ) ); ?>" />
				</div>
			</fieldset>
		<?php endif; ?>
		<?php do_action( 'resume_manager_register_form' ); ?>
	<?php endif; ?>

<?php endif; ?>
