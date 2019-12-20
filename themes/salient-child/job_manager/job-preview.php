<?php
/**
 *	Job listing preview when submitting job listings.
 *
 *	This is an override of the WP Job Manager Job Preview as
 *	we use our own code for the Single Job page
 *
 *	Ron Boutilier
 *	12/15/2018
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
	<div class="job_listing_preview_title">
		<input type="submit" name="continue" id="job_preview_submit_button" class="button job-manager-button-submit-listing" value="<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Submit Listing', 'wp-job-manager' ) ) ); ?>" />
		<input type="submit" name="edit_job" class="button job-manager-button-edit-listing" value="<?php esc_attr_e( 'Edit listing', 'wp-job-manager' ); ?>" />
		<h2><?php esc_html_e( 'Preview', 'wp-job-manager' ); ?></h2>
	</div>
	<div class="job_listing_preview single_job_listing">
		<h1 class="entry-title"><?php wpjm_the_job_title(); ?></h1>
		<?php 
			$post = get_post( NULL );
			include_once(get_stylesheet_directory() . '/job_manager/single-job-details.php');
		?>

		<input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
		<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form->get_form_name() ); ?>" />
	</div>
</form>