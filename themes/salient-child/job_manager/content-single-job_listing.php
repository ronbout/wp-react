<?php
/**
 * Single job listing.
 *
 * Template override of the plugin code
 *  
 *	Ron Boutilier
 *	12/15/2018
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
?>
<div class="single_job_listing">
	<?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
		<div class="job-manager-info"><?php _e( 'This listing has expired.', 'wp-job-manager' ); ?></div>
	<?php else : ?>
		<?php
			/**
			 * single_job_listing_start hook
			 *
			 * @hooked job_listing_meta_display - 20
			 * @hooked job_listing_company_display - 30
			 */

		?>
		<?php
			// put all job detail specific code in separate file for ease of development
			include_once(get_stylesheet_directory() . '/job_manager/single-job-details.php');
			if ( candidates_can_apply() ) {
				get_job_manager_template( 'job-application.php' ); 
			}

			/**
			 * single_job_listing_end hook
			 */
			do_action( 'single_job_listing_end' );
		?>
	<?php endif; ?>
</div>
