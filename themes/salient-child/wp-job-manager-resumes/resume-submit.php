<?php
/**
 * Template override of the Submit Resume/Register Page
 *
 *
 * @author      Ron Boutilier
 * @package     WP Job Manager - Resume Manager
 * @category    Template
 * @version     1.0
 */

  /* to keep it simple, I will clear the resume dashboard cache anytime
		the user comes to this page.  If site volume reaches a point that this 
		is not sufficient, we will add more specific code.
		This is not as important as the job alerts caching as it takes much
		longer to enter a resume and the cache is often cleared already*/
		pantheon_wp_clear_edge_paths( array('/candidate-dashboard') );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* var_dump($resume_fields);
die(); */
wp_enqueue_script( 'wp-resume-manager-resume-submission' );
?>
<form action="<?php echo $action; ?>" method="post" id="submit-resume-form" class="job-manager-form" enctype="multipart/form-data">
	<div id="resume-form-field-container">

		<?php do_action( 'submit_resume_form_start' ); ?>

		<?php if ( apply_filters( 'submit_resume_form_show_signin', true ) ) : ?>

			<?php get_job_manager_template( 'account-signin.php', array( 'class' => $class ), 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' ); ?>

		<?php endif; ?>

		<?php if ( resume_manager_user_can_post_resume() ) : ?>

			<?php if ( get_option( 'resume_manager_linkedin_import' ) ) : ?>

				<?php get_job_manager_template( 'linkedin-import.php', '', 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' ); ?>

			<?php endif; ?>

			<!-- Resume Fields -->
			<?php do_action( 'submit_resume_form_resume_fields_start' ); ?>

			<?php foreach ( $resume_fields as $key => $field ) : ?>
				<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">
					<label for="<?php esc_attr_e( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_resume_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager-resumes' ) . '</small>', $field ); ?></label>
					<div class="field">
						<?php $class->get_field_template( $key, $field ); ?>
					</div> 
				</fieldset>
			<?php endforeach; ?>

			<?php do_action( 'submit_resume_form_resume_fields_end' ); ?>

			<p>
				<?php wp_nonce_field( 'submit_form_posted' ); ?>
				<input type="hidden" name="resume_manager_form" value="<?php echo $form; ?>" />
				<input type="hidden" name="resume_id" value="<?php echo esc_attr( $resume_id ); ?>" />
				<input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
				<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
			</p>

		<?php else : ?>

			<?php do_action( 'submit_resume_form_disabled' ); ?>

		<?php endif; ?>

		<?php do_action( 'submit_resume_form_end' ); ?>
	</div>
	<?php 
		/* moved the submit button outside the form fields div to make alignment easier */ 
		
		if ( resume_manager_user_can_post_resume() ) : ?>
			<p>
			<input type="submit" name="submit_resume" class="button" value="<?php esc_attr_e( $submit_button_text ); ?>" />
			</p>
	<?php endif; ?>
</form>
