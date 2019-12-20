/**
 * Custom.js
 * custom javascript for the 3sixd site
 * when necessary, use 'td' as prefix
 *
 * Ron Boutilier
 * 12/15/2018
 */

/* start of polyfills for IE...ugh  */
if (!String.prototype.startsWith) {
  Object.defineProperty(String.prototype, "startsWith", {
    value: function(search, pos) {
      return (
        this.substring(!pos || pos < 0 ? 0 : +pos, search.length) === search
      );
    }
  });
}
if (!String.prototype.endsWith) {
  String.prototype.endsWith = function(search, this_len) {
    if (this_len === undefined || this_len > this.length) {
      this_len = this.length;
    }
    return this.substring(this_len - search.length, this_len) === search;
  };
}
/* end of polyfills */

jQuery(document).ready(function() {
  // in order to allow for drop down menus, must override
  // the preference of Salient to only allow drop downs on
  // the left most menus
  jQuery("div.right-aligned-menu-items ul.buttons")
    .addClass("sf-menu")
    .removeClass("buttons");

  // turn off the minimal form style for the job alerts form
  // this will probably be used on most of the Job Manager forms
  // also turn off the fancy form rcs (radio button, checkbox, select)
  // as Job Manager uses jQuery Chosen for its select
  jQuery("body.job-alerts")
    .attr("data-form-style", "classic")
    .attr("data-fancy-form-rcs", false);

  // set up resize code for the footer which is a pain to keep on the
  // bottom without being overwritten
  const $window = jQuery(window);
  const $contWrap = jQuery("div.container-wrap");
  const $footer = jQuery("div#footer-outer");

  function setContWrapPadding() {
    //console.log($contWrap.css("padding-bottom"));
    const footHeight = $footer.height() + "px";
    //console.log("foot: ***", footHeight, "%%%");
    $contWrap[0].style.setProperty("padding-bottom", footHeight, "important");
  }

  // run once to set initial size and then again on any resize
  setTimeout(setContWrapPadding, 500);
  $window.resize(() => setContWrapPadding());

  // set up email and required tests for Job Application form
  if (
    jQuery("body").hasClass("single-job_listing") &&
    jQuery("form.job-manager-application-form").length
  ) {
    if (jQuery("#email-address").length) {
      setupRequiredValidation(
        ".job-manager-application-form",
        "#email-address"
      );
      setupEmailValidation(".job-manager-application-form", "#email-address");
    }
    jQuery("#full-name").length &&
      setupRequiredValidation(".job-manager-application-form", "#full-name");
    jQuery("#resume-file").length &&
      setupRequiredValidation(".job-manager-application-form", "#resume-file");
    jQuery("#portfolio-or-website-url").length &&
      setupRequiredValidation(
        ".job-manager-application-form",
        "#portfolio-or-website-url"
      );
  }

  // set up email test for home page Job Alert email
  if (jQuery("body").hasClass("home") && jQuery("#job-alerts-email").length) {
    setupRequiredValidation(".wpcf7-form", "#job-alerts-email");
    setupEmailValidation(".wpcf7-form", "#job-alerts-email");
  }

  // set up form validation for Contact Us page
  if (jQuery("body").hasClass("page-id-25")) {
    if (jQuery("#contact-email").length) {
      setupRequiredValidation(".wpcf7-form", "#contact-email");
      setupEmailValidation(".wpcf7-form", "#contact-email");
    }
    jQuery("#contact-name").length &&
      setupRequiredValidation(".wpcf7-form", "#contact-name");
  }

  // set up form validation for Sign in page
  if (jQuery("body").hasClass("sign-in")) {
    jQuery("#user_login").length &&
      setupRequiredValidation("#loginform", "#user_login");
    jQuery("#user_pass").length &&
      setupRequiredValidation("#loginform", "#user_pass");
  }

  // set up form validation for Resume page (resume-form-page)
  if (jQuery("body").hasClass("resume-form-page")) {
    // set up all required fields
    // file upload does not have a required attr so must add individually
    // however, may already have uploaded file for test for that as
    // part of the valid condition
    jQuery("#resume_file").length &&
      setupRequiredValidation("#submit-resume-form", "#resume_file", function(
        val
      ) {
        return val.trim() !== "" || jQuery(".job-manager-uploaded-file").length;
      });
    jQuery("#submit-resume-form").length &&
      setupRequiredValidation("#submit-resume-form", ":input[required]");
    if (jQuery("#candidate_email").length) {
      setupEmailValidation("#submit-resume-form", "#candidate_email");
    }
    const $fileInput = jQuery(
      "#submit-resume-form .fieldset-resume_file input[type='file']"
    );
    $fileInput.length &&
      $fileInput.change(function() {
        $fileInput.val() &&
          jQuery(".fieldset-resume_file small").addClass("hide-mobile");
      }),
      jQuery("#submit-resume-form").on(
        "click",
        ".job-manager-remove-uploaded-file",
        function() {
          jQuery(".fieldset-resume_file small").removeClass("hide-mobile");
        }
      );
  }

  // set up form validation for Registration page
  if (jQuery("body").hasClass("sign-up")) {
    // set up all required fields
    jQuery("#wppb-register-user").length &&
      setupRequiredValidation("#wppb-register-user", ":input[required]");
    jQuery("#email").length &&
      setupEmailValidation("#wppb-register-user", "#email");
  }

  // set up form validation for Edit Profile page
  if (jQuery("body").hasClass("edit-profile")) {
    // set up all required fields
    jQuery("#wppb-edit-user").length &&
      setupRequiredValidation("#wppb-edit-user", ":input[required]");
    jQuery("#email").length &&
      setupEmailValidation("#wppb-edit-user", "#email");
  }

  // set up form validation for Job Alert form
  if (
    jQuery("body").hasClass("job-alerts") &&
    jQuery("#job-alert-form").length
  ) {
    setupRequiredValidation("#job-alert-form", "#alert_name");
  }

  // set up css for company logo on Post a Job page
  if (jQuery("body").hasClass("post-a-job")) {
    const $fileInput = jQuery(
      "#submit-job-form .fieldset-company_logo input[type='file']"
    );
    $fileInput.length &&
      $fileInput.change(function() {
        $fileInput.val() &&
          jQuery(".fieldset-company_logo small").addClass("hide-mobile");
      }),
      jQuery("#submit-job-form").on(
        "click",
        ".job-manager-remove-uploaded-file",
        function() {
          jQuery(".fieldset-company_logo small").removeClass("hide-mobile");
        }
      );
  }
});

function testEmailField(val) {
  // no error if field is empty
  return val.trim() === "" || isEmail(val);
}

function testRequiredField(val) {
  return val.trim() !== "";
}

function setupEmailValidation(formName, emailField) {
  setupValidation(
    formName,
    emailField,
    testEmailField,
    "email",
    "Invalid Email"
  );
}

function setupRequiredValidation(
  formName,
  fieldName,
  testFn = testRequiredField
) {
  setupValidation(formName, fieldName, testFn);
}

function setupValidation(
  formName,
  fieldName,
  validTest,
  typeTest = "required",
  errMsg = "Required Field"
) {
  const $form = jQuery(`form${formName}`);

  // need to remove the form validation so that we have consistent errors
  typeTest === "required" && $form.attr("novalidate", true);

  $form.submit(ev => {
    const $fields = jQuery(fieldName);
    let validFlag = true;
    $fields.each((i, el) => {
      const $field = jQuery(el);
      const fieldVal = $field.val().trim();
      // strip off the leading character and any spaces
      // some selectors are of the '.class1 .class2' variety
      const errorId = `td-${fieldName.substr(1)}-${typeTest}-error-${i}`
        .replace(/\s+/g, "")
        .replace(/[\[\]]/g, "")
        .replace(/:/g, "");
      if (!validTest(fieldVal)) {
        if (!jQuery(`#${errorId}`).length) {
          const $e = jQuery(
            `<div class="td-error" id="${errorId}"></div>`
          ).text(errMsg);
          $field
            .parent()
            .parent()
            .after($e);
          // need to check if the user retypes the email correctly and remove error msg if present
          $field.on("input", { field: $field }, event => {
            $field = event.data.field;
            let fieldVal = $field.val();
            if (validTest(fieldVal)) {
              const $tdError = jQuery(`#${errorId}`);
              if ($tdError.length) {
                $tdError.remove();
              }
            }
          });
        }
        $field.focus();
        validFlag = false;
      }
    });

    validFlag || ev.preventDefault();
    return validFlag;
  });
}

function isEmail(email) {
  // returns true or false if email is correctly formatted

  // test length
  if (email.length < 6) return false;

  // test for @ character
  if (email.indexOf("@") < 1) return false;

  // split at the @, making sure it is only 2
  // then test each part
  let emailArray = email.split("@");
  if (emailArray.length !== 2) return false;
  let local = emailArray[0];
  let domain = emailArray[1];

  // test for invalid characters
  let regex = /^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/;
  if (!local.match(regex)) return false;

  // test for periods in a row in domain
  regex = /\.{2,}/;
  if (domain.match(regex)) return false;

  // test for leading and trailing whitespace and periods
  if (
    domain.trim() !== domain ||
    domain.startsWith(".") ||
    domain.endsWith(".")
  )
    return false;

  // split the domain at the periods, making sure at least 2
  let domainSubs = domain.split(".");
  if (domainSubs.length < 2) return false;
  // make sure both

  // loop through each sub and perform test for invalid chars
  // and leading/trailing whitespace and hyphens
  domainSubs.forEach(sub => {
    if (sub.trim() !== sub || sub.startsWith("-") || sub.endsWith("-"))
      return false;
    let regex = /^[a-z0-9-]+$/i;
    if (!sub.match(regex)) return false;
  });

  // Email passed!
  return true;
}
