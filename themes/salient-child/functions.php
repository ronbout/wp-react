<?php 
/**
 * Custom functions for the 3sixd site
 * 'td_' will be prefix for names
 * 
 * Ron Boutilier
 * 12/15/2018
 */

add_filter( 'wp_nav_menu_items', 'my_custom_menu_item', 10, 2);
function my_custom_menu_item($items, $args)
{
    if(is_user_logged_in() && $args->theme_location === 'top_nav_pull_right')
    {
				$user=wp_get_current_user();
				
				//$name=$user->display_name; // or user_login , user_firstname, user_lastname
				$user_meta = get_user_meta($user->ID);
				$full_name = strtoupper($user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0]);
				$items .= '<li id="menu-item-6569" class="menu-item-username menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-6569">
				<a href="#" id="menu-username" class="sf-with-ul">
				<img id="menu-profile-icon" src="' . plugins_url('wp-job-manager-resumes/assets/images/candidate.png') . '" alt="Profile">
				 ' . $full_name . '</a>
				<ul class="sub-menu">
					<li id="menu-item-6535" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-6535">
						<a class="account-dropdown" href="/edit-profile/">EDIT PROFILE</a>
					</li>
					<li id="menu-item-6355" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-6355">
						<a class="account-dropdown"  href="/wp-login.php?action=logout">LOGOUT</a>
					</li>
				</ul>
				</li>';
    }
    return $items;
}



 //* Enqueue Jobs Page Stylesheets and Scripts
add_action('wp_enqueue_scripts', 'td_load_resources');

function td_load_resources() {
	// for Candidate resumes and job alerts dashboards, turn off caching by
	// adding time to the js url as the version number
	$js_ver = (is_page('candidate-dashboard') || is_page('job-alerts') || is_page('job-dashboard')) ? time() : false;
	wp_register_script( 'jl-js', get_stylesheet_directory_uri() . '/js/custom.min.js', array( 'jquery', 'wp-resume-manager-resume-submission' ), $js_ver, true);
	//wp_register_script( 'jl-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery', 'wp-resume-manager-resume-submission' ), $js_ver, true);
	wp_enqueue_script('jl-js');
}

// change default login page, which some Job plugins use to our sign in page
if (! is_admin() ) {
	add_filter( 'login_url', 'td_login_page', 10, 2 );
	function td_login_page( $login_url, $redirect ) {
			return home_url( '/sign-in/?redirect_to=' . $redirect );
	}
}

// must turn off admin access to candidate, customer and subscriber users
function td_no_admin_access() {
	global $current_user;
	$redirect = home_url( '/' );
	$user = wp_get_current_user();
	$role = $user->roles[0];
	if ( (strtoupper($role)  === 'CANDIDATE' || strtoupper($role)  === 'SUBSCRIBER' || strtoupper($role)  === 'CUSTOMER')  && ( ! wp_doing_ajax() ) ) {
		exit( wp_redirect( $redirect ) );
	}
}
add_action( 'admin_init', 'td_no_admin_access', 1 );

// hide the admin bar if the user is any of the above
if (is_user_logged_in()) {
	$user = wp_get_current_user();
	$role = $user->roles[0];
	if ( strtoupper($role)  === 'CANDIDATE' || strtoupper($role)  === 'SUBSCRIBER' || strtoupper($role)  === 'CUSTOMER' ) {
		show_admin_bar(false);
	}
}

// must allow Post A Job access to only Admins and Employers
function td_no_post_job_access() {
	global $current_user;
	if (is_page('post-a-job') && is_user_logged_in()) {
		$redirect = home_url( '/' );
		$user = wp_get_current_user();
		$role = $user->roles[0];
		if ( strtoupper($role)  !==  'ADMINISTRATOR' && strtoupper($role)  !== 'EMPLOYER') {
			exit( wp_redirect( $redirect ) );
		}
	}
}
add_action( 'template_redirect', 'td_no_post_job_access', 1 );

// have to add 'action' to public query vars so I can look it 
// up in the next step.  plugin added query w/o registering with wp
function add_query_vars_filter( $vars ) {
  $vars[] = "action";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

// add class to the body so that 
add_filter( 'body_class','add_body_classes' );
function add_body_classes( $classes ) {
	global $post;

		if (is_page(array('resumes', 'jobs'))) {
			$classes[] = 'jobs-resumes-page';
		}
		if (is_page('submit-resume') || (is_page('candidate-dashboard') && get_query_var('action') === 'edit') ) {
			$classes[] = 'resume-form-page';
		}
		/* for better identification, just put page name in body class */
		$classes[] = $post->post_name;

		return $classes;
}


/**
 *  this section modifies the Candidate Registration fields
 */
add_filter( 'submit_resume_form_fields', 'modify_submit_resume_form_fields' );

function modify_submit_resume_form_fields( $fields ) {
	//change label of fields
	$fields['resume_fields']['resume_file']['label'] = "Resume File\n(Required)";
	$fields['resume_fields']['candidate_experience']['fields']['employer']['label'] = "Company";
	
	//change description of fields
	$fields['resume_fields']['resume_file']['description'] = "Upload your resume. Max. file size: 2 MB.";

	// fields to be removed
	unset( $fields['resume_fields']['candidate_photo'] );
	unset( $fields['resume_fields']['candidate_video'] );
	//unset( $fields['resume_fields']['resume_content'] );
	unset( $fields['resume_fields']['links'] );
	unset( $fields['resume_fields']['candidate_education'] );
	unset( $fields['resume_fields']['candidate_experience']['fields']['notes'] );

	// move sub-fields of Candidate Experience so that Title comes before Employer
	// there is no priority field to order like the main fields
	$new_order = array('job_title', 'employer', 'date');
	reorder_candidate_repeated($fields['resume_fields']['candidate_experience']['fields'], $new_order);

	// return the modified fields
	return $fields;
	
}

/***
 * custom code for changing the order of the fields in the form
 */
function reorder_candidate_repeated(&$fields, $new_order) {
	// redo the order of one of he candidate repeated fields!

	$tmp = array();
	foreach($new_order as $v) {
		$tmp[$v] = $fields[$v];
	}
	$fields = $tmp;
	return;
}


?>