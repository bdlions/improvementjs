<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');
    }

    //redirect if needed, otherwise display the user list
    function index() {

        if (!$this->ion_auth->logged_in()) {
            //redirect to the login page
            redirect('auth/login', 'refresh');
        } else {
            if ($this->ion_auth->is_member()) {
                $base = base_url();
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                $this->data['projects'] = $this->ion_auth->where('users.id', $this->session->userdata('user_id'))->projects()->result();
                $this->template->load("default_template", "auth/project", $this->data);
            } else if ($this->ion_auth->is_demo()) {
                $base = base_url();
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                $this->data['projects'] = $this->ion_auth->where('users.id', $this->session->userdata('user_id'))->projects()->result();
                $this->template->load("default_template", "auth/project", $this->data);
            } else {
                redirect('auth/login', 'refresh');
            }
        }
    }

    //log the user out
    function logout() {
        $this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them back to the page they came from
        redirect('auth/login', 'refresh');
    }

    //change password
    function change_password() {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) { //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            //$this->load->view('auth/change_password', $this->data);
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}css/form_design.css' />";
            $this->template->set('css', $css);
            $this->template->set('main_content', "auth/change_password");
            $this->template->load("default_template", 'auth/change_password', $this->data);
        } else {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) { //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }    

    //call back function to check whether email exists or not
    function is_email_exists($email) {
        $email_exists = false;
        $total_users = count($this->ion_auth->where('users.email', $email)->users()->result());
        if ($total_users > 0) {
            $email_exists = true;
        }
        if (!$email_exists) {
            $this->form_validation->set_message('is_email_exists', 'Error - There is no account associated to that email');
            return false;
        } else {
            return true;
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code) {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset) {  //if the reset worked then send them to the login page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/login", 'refresh');
        } else { //if the reset didnt work then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //activate the user
    function activate($id, $code = false) 
    {
        if ($code !== false)
        {
            //checking whether this user is already active or not        
            $user_infos = $this->ion_auth->where('users.id',$id)->users()->result_array();
            if(count($user_infos) > 0)
            {
                $user_info = $user_infos[0];
                if($user_info['active'] == 1)
                {
                    $this->data['message'] = "Account is already active.";
                }
                else
                {
                    $activation = $this->ion_auth->activate($id, $code);
                    if ($activation)
                    {
                    $this->data['message'] = "Account is now active.";
                    }
                    else
                    {
                        $this->data['message'] = "Incorrect activation code.";
                    }
                }
            }
            else
            {
                $this->data['message'] = "You are not allowed to activate this user.";            
            }            
        }            
        else
        {
            $this->data['message'] = "You are not allowed to activate this user.";            
        }
        
        $base = base_url(); 
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_external_user');
        $this->template->load("default_template", 'auth/activation_message_user', $this->data);
    }

    //deactivate the user
    function deactivate($id = NULL) {
        // no funny business, force to integer
        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->template->set('main_content', 'auth/deactivate_user');
            $this->template->load("default_template", 'auth/deactivate_user', $this->data);

            //$this->load->view('auth/deactivate_user', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_404();
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    //call back function to check password for one char in caps and one number
    function password_check($password) {
        if (!(preg_match("/[A-Z]/", $password) && preg_match("/[0-9]/", $password))) {
            $this->form_validation->set_message('password_check', 'Password should contain at least 1 char in caps and 1 number');
            return false;
        } else {
            return true;
        }
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function upload_configuration_file() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        $this->data['project_id'] = $this->session->userdata('project_id');
        $this->template->set('menu_bar', 'design/configuration_menubar');
        $this->template->load("default_template", "program/upload_configuration_file", $this->data);
    }

    function delete_project($project_id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        if ($this->input->post('delete_project_yes')) {
            $this->ion_auth->where('project_id', $project_id)->delete_project();
            $this->ion_auth->where('project_id', $project_id)->delete_user_project();
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            //loading user deletion confirmation page
            $this->template->load("default_template", "auth/delete_project_successful");
        } else if ($this->input->post('delete_project_no')) {
            //loading admin dashboard
            redirect("auth", 'refresh');
        } else {
            //loading user deletion confirmation page
            $this->data['project_id'] = $project_id;
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            $this->template->load("default_template", "auth/delete_project_confirmation", $this->data);
        }
    }

    function update_project() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
            echo "Your session is expired";
        } else {
            $project_content = $_POST['project_content'];
            $data = array(
                'project_content' => $project_content,
                'project_content_backup' => $project_content
            );
            $this->ion_auth->where('project_id', $this->session->userdata('project_id'))->update_project($data);
            echo "Project successfully saved.";
        }
    }

    function send_email_activation($id) 
    {
        $user_infos = $this->ion_auth->where('users.id',$id)->users()->result_array();
        $user_info = $user_infos[0];
        $email = $user_info['email'];
        $this->ion_auth->resend_activation_email($id, $email);
        
        $this->data['user_email'] = $email;                
        $base = base_url(); 
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
        $this->template->set('css', $css);
        $this->template->set('base', $base);
        $this->template->set('menu_bar', 'design/menu_bar_external_user');
        $this->template->load("default_template", 'auth/resend_activation_successful', $this->data);
    }

    function load_search() {
        $this->data['title'] = "Search User";
        //only admin can edit an user within same session
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $username = true;
        $lastname = false;
        $useremail = false;
        if ($this->input->post('submit')) {
            $username = $this->input->post('username');
            $lastname = $this->input->post('lastname');
            $useremail = $this->input->post('useremail');
        }
        $this->data['username'] = $username;
        $this->data['lastname'] = $lastname;
        $this->data['useremail'] = $useremail;


        //validate form input
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        //if ($this->input->post('submit'))
        if ($this->form_validation->run() == true) {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $user_name = $this->input->post('user_name');
            $like_array = array();
            if ($this->input->post('username')) {
                $like_array['username'] = $user_name;
            }
            if ($this->input->post('lastname')) {
                $like_array['last_name'] = $user_name;
            }
            if ($this->input->post('useremail')) {
                $like_array['email'] = $user_name;
            }
            /* $like_array = array('username' => $user_name,
              'last_name' => $user_name,
              'email' => $user_name,
              ); */
            $this->data['users'] = $this->ion_auth->or_like($like_array)->users()->result();
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $base = base_url();
            if ($this->ion_auth->is_admin()) {
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                ;
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_admin');
            }
            $this->template->set('main_content', "auth/show_searched_user");
            $this->template->load("default_template", "auth/show_searched_user", $this->data);
        } else { //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['user_name'] = array('name' => 'user_name',
                'id' => 'user_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_name'),
            );
            $base = base_url();
            if ($this->ion_auth->is_admin()) {
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_admin');
            }
            $this->template->set('main_content', "auth/search_user");
            $this->template->load("default_template", 'auth/search_user', $this->data);
        }
    }

    //log the user in
    function login() 
    {
        $this->data['title'] = "Login";

        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) 
            {
                //if the login is successful
                //redirect to the main page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/index", 'refresh');
            } 
            else 
            {
                if ($this->ion_auth->get_global_user_info() != null && $this->ion_auth->get_global_user_info()->active == 0) 
                {
                    if ($this->ion_auth->get_global_user_info()->is_inactivated_by_admin == 1) 
                    {
                        $this->data['id'] = $this->ion_auth->get_global_user_info()->id;
                        $base = base_url(); 
                        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
                        $this->template->set('css', $css);
                        $this->template->set('base', $base);
                        $this->template->set('menu_bar', 'design/menu_bar_external_user');
                        $this->template->load("default_template", 'auth/user_inactive_by_admin', $this->data);
                    } 
                    else 
                    {
                        $this->data['id'] = $this->ion_auth->get_global_user_info()->id;
                        $base = base_url(); 
                        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
                        $this->template->set('css', $css);
                        $this->template->set('base', $base);
                        $this->template->set('menu_bar', 'design/menu_bar_external_user');        
                        $this->template->load("default_template", 'auth/resend_activation', $this->data);
                    }
                } 
                else 
                {
                    //if the login was un-successful
                    //redirect them back to the login page
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
                }
            }
        } 
        else {
            $identity = "";
            $password = "";
            $remember = false;
            if (get_cookie('identity')) {
                $identity = get_cookie('identity');
                //delete_cookie('identity');
            }
            if (get_cookie('u_p')) {
                $password = get_cookie('u_p');
            }
            if (get_cookie('is_remember')) {
                $remember = true;
            }
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->session->set_flashdata('message', $this->ion_auth_model->errors());
            //$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['message'] = $this->session->flashdata('message');
            //$this->ion_auth_model->clear_errors();
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $identity,
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $password,
            );
            $this->data['remember'] = $remember;
            $this->template->set('main_content', 'auth/login');
            $this->template->load("default_template", 'auth/login', $this->data);
            //$this->load->view('auth/login', $this->data);
        }
    }
    
    function signin() 
    {
        $this->data['message'] = '';
        $identity = "";
        $password = "";
        $remember = false;
        if (get_cookie('identity')) {
            $identity = get_cookie('identity');
        }
        if (get_cookie('u_p')) {
            $password = get_cookie('u_p');
        }
        if (get_cookie('is_remember')) {
            $remember = true;
        }
        $this->data['identity'] = array('name' => 'identity',
            'id' => 'identity',
            'type' => 'text',
            'value' => $identity,
        );
        $this->data['password'] = array('name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'value' => $password,
        );
        $this->data['remember'] = $remember;        
        $this->template->load("default_template", 'auth/login', $this->data);
    }
    
    /*
     * New user will be created
     */
    function create_user() 
    {
        $this->data['title'] = "Create User";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');

        //validate form input
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|matches[email_confirm]');
        $this->form_validation->set_rules('email_confirm', 'Email Address Confirmation', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_password_check');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('countries', 'Country', 'required|xss_clean');

        if ($this->form_validation->run() == true) 
        {
            $username = $this->input->post('user_name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'country' => $this->input->post('countries'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) 
        {
            $base = base_url();
            $this->session->set_flashdata('message', "");
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_external_user');
            $this->data['message'] = "User account successfully created. An email has been sent to you to activate your account.";
            $this->template->set('main_content', "auth/create_user_complete");
            $this->template->load("default_template", 'auth/create_user_complete', $this->data);
        } 
        else 
        { //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : '');

            $this->data['user_name'] = array('name' => 'user_name',
                'id' => 'user_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_name'),
            );
            $this->data['first_name'] = array('name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array('name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['email_confirm'] = array('name' => 'email_confirm',
                'id' => 'email_confirm',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email_confirm'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array('name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $countries = $this->ion_auth->order_by('printable_name', 'asc')->get_all_countries()->result_array();
            $this->data['countries'] = array();
            foreach ($countries as $key => $country) {
                $this->data['countries'][$country['iso']] = $country['printable_name'];
            }

            $base = base_url();
            //loading menu bar            
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_external_user');
            $this->template->set('main_content', "auth/create_user");
            $this->template->load("default_template", 'auth/create_user', $this->data);
        }
    }
    
    /*
     * This method sends a link to the user to change his password based on user given email
     */
    function forgot_password() 
    {
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|callback_is_email_exists');        
        if ($this->form_validation->run() == false)
        {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );            
            $this->data['message'] = (validation_errors()) ? validation_errors() : '';
            $base = base_url(); 
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);                
            $this->template->set('menu_bar', 'design/menu_bar_external_user');
            $this->template->load("default_template", 'auth/forgot_password', $this->data);
        }
        else
        {
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));
            //if there were no errors
            if ($forgotten)
            { 
                //we should display a confirmation page here instead of the login page
                $user_infos = $this->ion_auth->where('users.email',$this->input->post('email'))->users()->result_array();
                $user_info = $user_infos[0];
                
                $this->session->set_flashdata('message', "");
                $this->data['user_email'] = $user_info['email'];

                $base = base_url(); 
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
                $this->template->set('css', $css);                
                $this->template->set('menu_bar', 'design/menu_bar_external_user');
                $this->template->load("default_template", 'auth/forgot_password_successful', $this->data);
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }
    
    /*
     * This method send user name to user email
     */
    function forgot_user_name() {
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|callback_is_email_exists');
        
        if ($this->form_validation->run() == true)
        {
            $user_infos = $this->ion_auth->where('users.email',$this->input->post('email'))->users()->result_array();
            $user_info = $user_infos[0];
            $forgotten = $this->ion_auth->forgotten_user_name($user_info);
            if ($forgotten)
            { 
                //if there were no errors
                $this->session->set_flashdata('message', "");
                $this->data['user_email'] = $user_info['email'];
                
                $base = base_url(); 
                $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
                $this->template->set('css', $css); 
                $this->template->set('menu_bar', 'design/menu_bar_external_user');
                $this->template->load("default_template", 'auth/forgot_user_name_successful', $this->data);
            
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgot_user_name", 'refresh');
            }
        }
        else
        {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            
            $this->data['message'] = (validation_errors()) ? validation_errors() : '';
            $base = base_url(); 
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_external_user');
            $this->template->load("default_template", 'auth/forgot_user_name', $this->data);
        }
    }
    
    //load user info to show user
    function show_user()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }
        
        $this->session->set_flashdata('message', "");
        $this->data['title'] = "User profile";
        $this->data['user_id'] = $this->session->userdata('user_id');
        
        $user_infos = $this->ion_auth->where('users.id',$this->session->userdata('user_id'))->users()->result_array();
        if(count($user_infos) <= 0)
        {
            redirect('auth', 'refresh');
        }
        $user_info = $user_infos[0];
        
        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['user_name'] = array('name' => 'user_name',
            'id' => 'user_name',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['username'],
        );
        $this->data['first_name'] = array('name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['first_name'],
        );
        $this->data['last_name'] = array('name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['last_name'],
        );
        $this->data['email'] = array('name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['email'],
        );
        $this->data['created_date'] = array('name' => 'created_date',
            'id' => 'created_date',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['created_date'],
        );
        $this->data['ip_address'] = array('name' => 'ip_address',
            'id' => 'ip_address',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['ip_address'],
        );
        $this->data['browser'] = array('name' => 'browser',
            'id' => 'browser',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $user_info['browser'],
        );
        $countries = $this->ion_auth->order_by('printable_name','asc')->get_all_countries()->result_array();
        $this->data['countries'] = array();
        foreach ($countries as $key => $country)
        {
            $this->data['countries'][$country['iso']] = $country['printable_name'];
        }
        $this->data['selected_country'] = $user_info['country'];
        
        if ($this->ion_auth->is_member()) 
        {
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        } 
        else if ($this->ion_auth->is_demo()) 
        {
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        } 
        else 
        {
            redirect('auth/login', 'refresh');
        }
        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
        $this->template->set('css', $css);
        $this->template->load("default_template", "auth/show_user", $this->data);    
    }
    
    //edit user information
    function edit_user()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }
        
        $user_id = $this->session->userdata('user_id');
        
        $this->session->set_flashdata('message', "");
        $this->data['title'] = "Edit User";
        $this->data['user_id'] = $user_id;
        
        $user_infos = $this->ion_auth->where('users.id',$user_id)->users()->result_array();
        if(count($user_infos) <= 0)
        {
            redirect('auth', 'refresh');
        }        
        $user_info = $user_infos[0];
        $groups = $this->ion_auth->groups()->result_array();        

        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        
        //validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('countries', 'Country', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_password_check');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        
        
        if ($this->form_validation->run() == true && $this->input->post('submit'))
        {
            //user didn't change password field, so we dont need to update password
            if($this->input->post('password') == $this->config->item('samply_dummy_password', 'ion_auth'))
            {
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('countries'),
                );
            }
            else
            {
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('countries'),
                    'password' => $this->input->post('password'),
                );
            }
            
        }
        
        if ($this->form_validation->run() == true && $this->input->post('submit') && $this->ion_auth->update_user($user_id, $additional_data))
        { 
            //$this->session->set_flashdata('message', "User account successfully updated.");
            
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            
            //redirect('auth', 'refresh');
            //loading user update confirmation page
            $this->data['user_id'] = $user_id;            
            
            if ($this->ion_auth->is_member()) 
            {
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            } 
            else if ($this->ion_auth->is_demo()) 
            {
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            } 
            else 
            {
                redirect('auth/login', 'refresh');
            }
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
            $this->template->set('css', $css);
            $this->template->load("default_template", "auth/edit_user_successful", $this->data);
        }
        else
        { //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array('name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $user_info['first_name'],
            );
            $this->data['last_name'] = array('name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $user_info['last_name'],
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->config->item('samply_dummy_password', 'ion_auth'),
            );
            $this->data['password_confirm'] = array('name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->config->item('samply_dummy_password', 'ion_auth'),
            );
            $countries = $this->ion_auth->order_by('printable_name','asc')->get_all_countries()->result_array();
            $this->data['countries'] = array();
            foreach ($countries as $key => $country)
            {
                $this->data['countries'][$country['iso']] = $country['printable_name'];
            }
            $this->data['selected_country'] = $user_info['country'];
            
            if ($this->ion_auth->is_member()) 
            {
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            } 
            else if ($this->ion_auth->is_demo()) 
            {
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            } 
            else 
            {
                redirect('auth/login', 'refresh');
            }
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
            $this->template->set('css', $css);
            $this->template->load("default_template", "auth/edit_user", $this->data);
        }
    }

}
