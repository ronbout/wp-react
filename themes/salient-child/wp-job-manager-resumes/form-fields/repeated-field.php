<?php
/**
 * Template override of the repeated fields template for the
 * Resume Submission/Registration page.  Our Salient theme
 * requires certain markup that it adds dynamically through js
 * during page load.  But these are added dynamically later 
 * through Add button, so must be set up with the 
 * needed markup.
 *
 *
 * @author      Ron Boutilier
 * @package     WP Job Manager - Resume Manager
 * @category    Template
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) : ?>
	<?php foreach ( $field['value'] as $index => $value ) : ?>
		<div class="resume-manager-data-row">
			<input type="hidden" class="repeated-row-index" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="<?php echo absint( $index ); ?>" />
			<a href="#" class="resume-manager-remove-row"><?php _e( 'Remove', 'wp-job-manager-resumes' ); ?></a>
			<?php foreach ( $field['fields'] as $subkey => $subfield ) : ?>
				<fieldset class="fieldset-<?php esc_attr_e( $subkey ); ?> candidate-repeat">
					<?php /**** here is where we add custom code to work with Salient minimalist forms ****/ ?>
					<div class="minimal-form-input no-text">
						<label for="<?php esc_attr_e( $subkey ); ?>">
							<span class="text">
								<span class="text-inner">
									<?php echo $subfield['label'] . ( $subfield['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager-resumes' ) . '</small>' ); ?>
								</span>
							</span>
						</label>
						<div class="field">
							<?php
								// Get name and value
								$subfield['name']  = $key . '_' . $subkey . '_' . $index;
								$subfield['value'] = $value[ $subkey ];
								$class->get_field_template( $subkey, $subfield );
							?>
						</div>
					</div>
				</fieldset>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<a href="#" class="resume-manager-add-row" data-row="<?php

	ob_start();
	/***  HATE THE REPETITION FROM ABOVE, BUT ORIGINAL PLUGIN CODE AND DON'T WANT TO 
	 * 		ALTER SO MUCH THAT THAT AN UPDATE WOULD BE HARD TO WORK WITH
	 * 		rlb
	 */
	?>
		<div class="resume-manager-data-row">
			<input type="hidden" class="repeated-row-index" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="%%repeated-row-index%%" />
			<a href="#" class="resume-manager-remove-row"><?php _e( 'Remove', 'wp-job-manager-resumes' ); ?></a>
			<?php foreach ( $field['fields'] as $subkey => $subfield ) : ?>
				<fieldset class="fieldset-<?php esc_attr_e( $subkey ); ?> candidate-repeat">					
				<?php /**** here is where we add custom code to work with Salient minimalist forms ****/ ?>
					<div class="minimal-form-input no-text">
						<label for="<?php esc_attr_e( $subkey ); ?>">
							<span class="text">
								<span class="text-inner">
									<?php echo $subfield['label'] . ( $subfield['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager-resumes' ) . '</small>' ); ?>
								</span>
							</span>
						</label>
						<div class="field">
							<?php
								$subfield['name']  = $key . '_' . $subkey . '_%%repeated-row-index%%';
								$class->get_field_template( $subkey, $subfield );
							?>
						</div>
					</div>
				</fieldset>
			<?php endforeach; ?>
		</div>
	<?php
	echo esc_attr( ob_get_clean() );

?>">+ <?php echo esc_html( ! empty( $field['add_row'] ) ? $field['add_row'] : __( 'Add URL', 'wp-job-manager-resumes' ) ); ?></a>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
