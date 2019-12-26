<?php
/**
 * user-fields.php
 * adds candId to user fields
 * and creates a new candidate
 * through the post api when a
 * user registers.
 *
 * */
class UserFields
{
    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/

    /** Refers to a single instance of this class. */
    private static $instance = null;

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    private function __construct()
    {
        $this->modify_user_fields();
    }

    // end constructor

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Creates or returns an instance of this class.
     *
     * @return Foo A single instance of this class.
     */
    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // end get_instance;

    /*--------------------------------------------*
     *  Public Functions
     *--------------------------------------------*/

    public function tsd_registration_form()
    {
        $first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : ''; ?>
<p>
	<label for="first_name"><?php esc_html_e('First Name ', 'tsd'); ?><br />
		<input type="text" id="first_name" name="first_name" size="20"
			value="<?php echo esc_attr($first_name); ?>"
			class="input" />
	</label>
</p>
<p>
	<label for="last_name"><?php esc_html_e('Last Name', 'tsd'); ?><br />
		<input type="text" id="last_name" name="last_name" size="20"
			value="<?php echo esc_attr($last_name); ?>"
			class="input" />
	</label>
</p>
<?php
    }

    public function tsd_registration_errors($errors, $sanitized_user_login, $user_email)
    {
        if (empty($_POST['first_name'])) {
            $errors->add('first_name_error', __('<strong>ERROR</strong>: Please enter your First Name.', 'tsd'));
        }

        if (empty($_POST['last_name'])) {
            $errors->add('last_name_error', __('<strong>ERROR</strong>: Please enter your Last Name.', 'tsd'));
        }

        return $errors;
    }

    public function tsd_add_extra_user_column($columns)
    {
        $columns['candidate_id'] = 'Candidate Id';

        return $columns;
    }

    public function tsd_show_user_id_column_content($value, $column_name, $user_id)
    {
        $candid = get_user_meta($user_id, 'candidate_id', true);
        if ('candidate_id' == $column_name) {
            return $candid ? $candid : '';
        }

        return $value;
    }

    public function tsd_user_register($user_id)
    {
        if (!empty($_POST['candidate_id'])) {
            update_user_meta($user_id, 'candidate_id', intval($_POST['candidate_id']));
        }
    }

    public function tsd_user_register_new($user_id)
    {
        $new_cand_id = $this->create_candidate($_POST);
        if ($new_cand_id) {
            update_user_meta($user_id, 'candidate_id', $new_cand_id);
        }
        if (!empty($_POST['first_name'])) {
            update_user_meta($user_id, 'first_name', $_POST['first_name']);
        }
        if (!empty($_POST['last_name'])) {
            update_user_meta($user_id, 'last_name', $_POST['last_name']);
        }
    }

    /**
     * Back end registration.
     *
     * @param mixed $operation
     */
    public function tsd_admin_registration_form($operation)
    {
        if ('add-new-user' !== $operation) {
            // $operation may also be 'add-existing-user'
            return;
        }
        $candid = !empty($_POST['candidate_id']) ? intval($_POST['candidate_id']) : ''; ?>
<h3><?php esc_html_e('Candidate Information', 'tsd'); ?>
</h3>

<table class="form-table">
	<tr>
		<th><label for="candidate_id"><?php esc_html_e('Candidate xxId', 'tsd'); ?></label>
		</th>
		<td>
			<input type="number" min="1" max="99999" step="1" id="candidate_id" name="candidate_id"
				value="<?php echo esc_attr($candid); ?>"
				class="regular-text" />
		</td>
	</tr>
</table>
<?php
    }

    public function tsd_user_profile_update_errors($errors, $update, $user)
    {
        if ($update) {
            return;
        }
    }

    public function tsd_show_extra_profile_fields($user)
    {
        $candid = get_user_meta($user->ID, 'candidate_id', true); ?>
<h3><?php esc_html_e('Candidate Information', 'tsd'); ?>
</h3>

<table class="form-table">
	<tr>
		<th><label for="candidate_id"><?php esc_html_e('Candidateyy Id', 'tsd'); ?></label>
		</th>
		<td> <input type="number" min="1" max="99999" step="1" id="candidate_id" name="candidate_id"
				value="<?php echo esc_attr($candid); ?>"
				class="regular-text" />
			<?php
        // echo esc_html(get_user_meta('candidate_id', $user->ID));
        ?>
		</td>
	</tr>
</table>
<?php
    }

    /*--------------------------------------------*
     * Private Functions
     *--------------------------------------------*/

    private function modify_user_fields()
    {
        add_filter('manage_users_columns', [$this, 'tsd_add_extra_user_column']);
        add_action('manage_users_custom_column', [$this, 'tsd_show_user_id_column_content'], 10, 3);

        add_action('user_new_form', [$this, 'tsd_admin_registration_form']);
        add_action('user_profile_update_errors', [$this, 'tsd_user_profile_update_errors'], 10, 3);
        add_action('edit_user_created_user', [$this, 'tsd_user_register']);
        add_action('personal_options_update', [$this, 'tsd_user_register']);
        add_action('edit_user_profile_update', [$this, 'tsd_user_register']);
        add_action('user_register', [$this, 'tsd_user_register_new']);
        add_action('show_user_profile', [$this, 'tsd_show_extra_profile_fields']);
        add_action('edit_user_profile', [$this, 'tsd_show_extra_profile_fields']);
        add_action('register_form', [$this, 'tsd_registration_form']);
        add_filter('registration_errors', [$this, 'tsd_registration_errors'], 10, 3);
    }

    private function create_candidate($user)
    {
        $person_info = [
            'givenName' => $user['first_name'],
            'familyName' => $user['last_name'],
            'email1' => $user['user_email'],
        ];

        $person = $this->create_person_post($person_info);
        if ($person->error) {
            show_message($person->message);

            return false;
        }

        $cand_resp = $this->create_candidate_post($person->id);
        if ($cand_resp->error) {
            show_message($cand_resp->message);
        } else {
            return $cand_resp->id;
        }
    }

    private function create_person_post($person_info)
    {
        return fetch_post('persons', '', $person_info);
    }

    private function create_candidate_post($person_id)
    {
        $candidate_info = ['personId' => $person_id];

        return fetch_post('candidates', '', $candidate_info);
    }

    // end class
}
