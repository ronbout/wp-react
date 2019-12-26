<?php

// Template Name: React Page
?>
<?php get_header(); ?>

<?php
// pass data to the react component
$cand_id = get_candid();
echo "
	<script>
		 window.tsdData = { candId: {$cand_id} }
	</script>
";
?>

<?php nectar_page_header($post->ID); ?>

<div class="container-wrap">

	<div class="container main-content">

		<div class="row">

			<?php

            //breadcrumbs
            if (function_exists('yoast_breadcrumb') && !is_home() && !is_front_page()) {
                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
            }

            ?>
			<div id='tsd-react-root'>
				<h1>Loading...</h1>
			</div>


		</div>
		<!--/row-->

	</div>
	<!--/container-->

</div>
<?php get_footer();

function get_candid()
{
    // check if the user has a candidate id.  if so, use that.
    // otherwise, check the query vars.
    $candid = 0;
    $user = wp_get_current_user();
    if ($user) {
        $candid = get_user_meta($user->ID, 'candidate_id', true);
    }
    if (!$candid) {
        // see if anything was present in the query string
        $candid = get_query_var('candid', 0);
    }

    return $candid;
}
