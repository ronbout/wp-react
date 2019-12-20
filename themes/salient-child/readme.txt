www.3sixD.com Website

This project is a major upgrade to the 3sixD website, adding the Salient Theme 
and using the WP Job Manager plugins to create a Job Board.

# CODE ORGANIZATION

All of the customized code (PHP, JS, CSS) is under the salient-child theme.  Style.css and functions.php
are under the main child directory.  The .js files are under <js>.  In addition, some Job Manager plugin
overrides are in subdirectories.  There are a very few changes to plugin code when changes were required
in plugin files that did not have an override capability.  They are listed first in the FILES section.


#  FILES  

** a few core plugin code (PHP) files were changed **

plugins/wp-job-manager/includes/class-wp-job-manager-shortcodes.php
	- bug with esc_html causing a <span> tag to appear in user messages

plugins/wp-jog-manager-resumes/includes/forms/class-wp-resume-manager-form-submit-resume.php
	- change "Submit Resume" to "Save Resume" on save button

plugins/profile-builder/front-end/class-formbuilder.php
	- Added message to user to check spam folder for confirmation email

** Javascript

/js/custom.js
	- this is the working file for development.  Includes client-side form validation, and some
		styling (adding classes, changing css properties) that cannot be done with css or php.

/js/custom-es5.js
	- this is custom.js after it has been transpiled to es5 for IE 11 (yes, annoying!)

/js/custom.min.js
	- minified version for production  (remember in development to change functions.php to point to custom.js)

** PHP

functions.php
	- Typical functions file.  Load resources.  Set up security on pages with redirects when needed.  Add custom 
		Account menu item with submenu.  Add useful classes to the body tag.  Modify Candidate Resume fields that 
		cannot be done through the admin screen.

/job_manager/*.php
	- various template overrides for Single Job display, Job submit, and Job preview.  Each is self-documented.

/wp-job-manager-alerts/*.php
	- two template overrides for the Job Alerts dashboard and Job Alert form.  Each is self-documented.

/wp-job-manager-resumes/*.php
	- various template overrides for Candiate Resume Dashboard, Resume display, and the Resume form.  Each is self-documented.

/wp-job-manager-resumes/form-fields/repeated.field.php
	- template override for the repeated fields (Candidate Experience) on the Resume form.  This was critical to getting the
		form to work with the Salient theme as the fields are added dynamically and had to include the Salient required markup.


# MISC

The Salient theme, especially the minimal form setting, created a number of conflicts with the Job Manager shortcode
pages.  A lot of css and php-generated html had to written to overcome that.  

The Job Alert form was done differently.  I used the javascript to turn off the minimal form and the fancy rcs (radio button, 
checkbox, select).  Two data attributes just have to be changed in the body element.  Since the salient js,which runs after 
the custom.js, does not see those data attributes, it does not change the html and does not create as much conflict with the Job Manager
plugins, which expect to see the HTML that they created, not a vastly modified version that Salient creates.

This is a technique that should probably be applied to all Job Manager forms to reduce the unneeded complexity of the code.
