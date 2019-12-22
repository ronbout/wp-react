<?php

// Template Name: React Page
?>
<?php get_header(); ?>

<?php
// pass data to the react component
    $cand_id = get_query_var('candid', 22);
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
