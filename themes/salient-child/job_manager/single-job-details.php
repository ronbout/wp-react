<?php
/*  pulled out the code from single-job_listing.php that only deals
		with display the job detail page.  The rest is standard WP boilerplate.
		Ron Boutilier
		12/12/2018
*/

// use an array of fields to display containing field name and display
$display_fields = array(
	array('business_unit', 'Business Unit + Center'),
	array('contract_duration', 'Contract  Duration'),
	array('typical_day', 'Typical  Day'),
	array('edu_requirments', 'Education  Requirements'),
	array('techinical_skills', 'Technical  Skills'),
	array('pay_range', 'Pay  Range'),
	array('other_req', 'Other  Requirements'),
);

?>
<div class="single_job_listing">
		<?php if (get_option('job_manager_hide_expired_content', 1) && 'expired' === $post->post_status) : ?>
				<div class="job-manager-info"><?php _e('This listing has expired.', 'wp-job-manager'); ?></div>
		<?php endif; ?>
				<?php
				/**
				 * single_job_listing_start hook
				 *
				 * @hooked job_listing_meta_display - 20
				 * @hooked job_listing_company_display - 30
				 */
				do_action('single_job_listing_start');
				?>
				<div class="job_description">
						<?php wpjm_the_job_description(); ?>
				</div>
				<div class="additionalinfo">
						<div class="cls-addinfo-outer">
							<div class="mk_cls_left_content">
							<?php // loop through all left-side fields and display if data exists
							foreach($display_fields as $field) {
								display_field_div($field[0], $field[1]);
							}
							?>
						</div>
						<div class="mk_cls_right_content">
						<?php
							// right side is always Job Responsibilities
							display_field_div('job_responsibility', 'Job Responsibilities');
						?>
						</div>
					</div>
				</div>
</div>


<?php 

function display_field_div ( $field, $desc ) {
	$output = do_shortcode('[job_field key="' . $field . '"]');
	if ($output) : ?>
	<div class="cls-info-inner">
		<div class="cls-info-title"><?php echo $desc; ?></div>
		<div class="cls-info-description">
			<?php echo htmlspecialchars_decode($output); ?> 
		</div>
	</div>
<?php endif;

}
