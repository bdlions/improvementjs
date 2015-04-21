<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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

    /*
     * defaut method of this controller
     */
    function index() 
    {

        if (!$this->ion_auth->logged_in()) 
        {
            //redirect to the login page
            redirect('admin/signin', 'refresh');
        } 
        else 
        {
            if ($this->ion_auth->is_admin()) 
            {
                $this->user_render_pagination($this->config->item('pagination_page_range', 'ion_auth'), 0);
            } 
            else 
            {
                redirect('admin/login', 'refresh');
            }
        }
    }

    /*
     * This method will render user list based on pagination settings
     */
    function user_render_pagination($limit, $offset = 0) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $order_by = "id";
        $order = "asc";
        $sort_by_status = false;
        $sort_by_registration_date = false;
        $sort_descending = false;
        if ($this->input->post('submit')) 
        {
            $status = $this->input->post('status');
            $registration_date = $this->input->post('registration_date');
            $descending = $this->input->post('descending');

            $sort_by_status = $status;
            $sort_by_registration_date = $registration_date;
            $sort_descending = $descending;

            if ($sort_descending) 
            {
                $order = "desc";
            } 
            else 
            {
                $order = "asc";
            }
            if ($status == true) 
            {
                $order_by = "active";
                // o is Inactive and 1 is Active. Query sort based on 0/1 but we render Active/Inactive
                if ($sort_descending) 
                {
                    $order = "asc";
                } 
                else 
                {
                    $order = "desc";
                }
            } 
            else if ($registration_date == true) 
            {
                $order_by = "created_date";
            }
        }
        $this->data['sort_by_status'] = $sort_by_status;
        $this->data['sort_by_registration_date'] = $sort_by_registration_date;
        $this->data['sort_descending'] = $sort_descending;

        $total_users = count($this->ion_auth->users()->result());
        //list the users
        if ($limit == 0) {
            $this->data['users'] = $this->ion_auth->order_by($order_by, $order)->users()->result();
            $limit = $this->config->item('pagination_page_range', 'ion_auth');
        } else {
            $this->data['users'] = $this->ion_auth->order_by($order_by, $order)->limit($limit)->offset($offset)->users()->result();
        }
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'admin/user_render_pagination/' . $limit;
        $config['total_rows'] = $total_users;
        $config['uri_segment'] = 4;
        $config['per_page'] = $this->config->item('pagination_page_range', 'ion_auth');

        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();

        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_admin');
        $this->template->load("admin/templates/admin_tmpl", "admin/index", $this->data);
    }

    /*
     * New user will be created
     */

    /*function create_user() 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $this->data['title'] = "Create User";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        //validate form input
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_password_check');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('countries', 'Country', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            $username = $this->input->post('user_name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'country' => $this->input->post('countries'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
            $base = base_url();
            $this->session->set_flashdata('message', "User account successfully created.");
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->data['message'] = "User account successfully created. An email has been sent to you to activate the account.";
            $this->template->load("admin/templates/admin_tmpl", 'admin/create_user_complete', $this->data);
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

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
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", 'admin/create_user', $this->data);
        }
    }*/
    
    //create a new user
    function create_user() 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) 
        {
            redirect('admin/login', 'refresh');
        }
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
            $this->session->set_flashdata('message', "User account successfully created.");
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->data['message'] = "User account successfully created. An email has been sent to you to activate your account.";
            $this->template->load("admin/templates/admin_tmpl", 'admin/create_user_complete', $this->data);
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
            //loading admin menu bar
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", 'admin/create_user', $this->data);
        }
    }

    /*
     * User information will be shown based on user id
     * @param $user_id, user id of the user to be shown
     */

    function show_user($user_id) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $this->session->set_flashdata('message', "");
        $this->data['title'] = "User Information";
        $user_infos = $this->ion_auth->where('users.id', $user_id)->users()->result_array();
        $user_info = null;
        foreach ($user_infos as $u_info) {
            $user_info = $u_info;
            break;
        }
        if ($user_info == null) {
            redirect('admin', 'refresh');
        }
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
        $countries = $this->ion_auth->order_by('printable_name', 'asc')->get_all_countries()->result_array();
        $this->data['countries'] = array();
        foreach ($countries as $key => $country) {
            $this->data['countries'][$country['iso']] = $country['printable_name'];
        }
        $this->data['selected_country'] = $user_info['country'];

        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/form_design.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_admin');
        $this->template->load("admin/templates/admin_tmpl", 'admin/show_user', $this->data);
    }

    /*
     * User information will be edited based on user id
     * @param $user_id, user id of the user to be edited
     */

    function edit_user($user_id) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $this->session->set_flashdata('message', "");
        $this->data['title'] = "Edit User";
        $this->data['user_id'] = $user_id;
        $user_infos = $this->ion_auth->where('users.id', $user_id)->users()->result_array();
        $user_info = null;
        foreach ($user_infos as $u_info) {
            $user_info = $u_info;
            break;
        }
        if ($user_info == null) {
            redirect('admin', 'refresh');
        }
        $groups = $this->ion_auth->groups()->result_array();

        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        //validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('countries', 'Country', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_password_check');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true && $this->input->post('submit')) {
            //user didn't change password field, so we dont need to update password
            if ($this->input->post('password') == $this->config->item('samply_dummy_password', 'ion_auth')) {
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('countries'),
                    'group_id' => $this->input->post('groups'),
                );
            } else {
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('countries'),
                    'group_id' => $this->input->post('groups'),
                    'password' => $this->input->post('password'),
                );
            }
        }

        if ($this->form_validation->run() == true && $this->input->post('submit') && $this->ion_auth->update($user_id, $additional_data)) {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            //loading user update confirmation page
            $this->data['user_id'] = $user_id;
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            ;
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", "admin/edit_user_successful", $this->data);
        } else {
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
            $countries = $this->ion_auth->order_by('printable_name', 'asc')->get_all_countries()->result_array();
            $this->data['countries'] = array();
            foreach ($countries as $key => $country) {
                $this->data['countries'][$country['iso']] = $country['printable_name'];
            }
            $this->data['selected_country'] = $user_info['country'];

            $this->data['groups'] = array();
            foreach ($groups as $key => $group) {
                $this->data['groups'][$group['id']] = $group['name'];
            }
            $user_group = $this->ion_auth->get_users_groups($user_id)->result();
            $this->data['selected_group'] = $user_group[0]->id;

            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", 'admin/edit_user', $this->data);
        }
    }

    /*
     * User will be deleted based on user id
     * @param $user_id, user id of the user to be deleted
     */

    function delete_user($user_id) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        if ($this->input->post('delete_user_yes')) {
            //removing user from database
            $this->ion_auth->delete_user($user_id);

            //loading user deletion successful page
            $this->data['user_id'] = $user_id;
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            ;
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", "admin/delete_user_successful", $this->data);
        } else if ($this->input->post('delete_user_no')) {
            //loading admin dashboard
            redirect("admin", 'refresh');
        } else {
            //loading user deletion confirmation page
            $user_infos = $this->ion_auth->where('users.id', $user_id)->users()->result_array();
            $user_info = null;
            foreach ($user_infos as $u_info) {
                $user_info = $u_info;
                break;
            }
            if ($user_info == null) {
                redirect('admin', 'refresh');
            }
            $this->data['username'] = $user_info['username'];
            $this->data['user_id'] = $user_id;
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            ;
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", "admin/delete_user_confirmation", $this->data);
        }
    }

    /*
     * Use account will be activated
     * @param $user_id, user id of the user to be activated
     */

    function activate($user_id) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $user_infos = $this->ion_auth->where('users.id', $user_id)->users()->result_array();
        $user_info = null;
        foreach ($user_infos as $u_info) {
            $user_info = $u_info;
            break;
        }
        if ($user_info == null) {
            redirect('admin', 'refresh');
        }
        $activation = $this->ion_auth->activate_by_admin($user_id);
        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $this->data['username'] = $user_info['username'];
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            ;
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", "admin/active_successful", $this->data);
        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('admin', 'refresh');
        }
    }

    /*
     * Use account will be inactivated
     * @param $user_id, user id of the user to be inactivated
     */

    function deactivate($user_id) 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $user_infos = $this->ion_auth->where('users.id', $user_id)->users()->result_array();
        $user_info = null;
        foreach ($user_infos as $u_info) {
            $user_info = $u_info;
            break;
        }
        if ($user_info == null) {
            redirect('admin', 'refresh');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($user_id)->row();
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            ;
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", 'admin/deactivate_user', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user_id != $this->input->post('id')) {
                    show_404();
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate_by_admin($user_id);

                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    $this->data['username'] = $user_info['username'];
                    $base = base_url();
                    $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                    ;
                    $this->template->set('css', $css);
                    $this->template->set('menu_bar', 'design/menu_bar_admin');
                    $this->template->load("admin/templates/admin_tmpl", "admin/deactive_successful", $this->data);
                }
            } else {
                redirect('admin', 'refresh');
            }
        }
    }

    /*
     * Users are searched by admin
     */
    function load_search() 
    {
        //only admin has the access to this method
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }
        $this->data['title'] = "Search User";
        $username = true;
        $lastname = false;
        $useremail = false;
        if ($this->input->post('submit')) 
        {
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
        if ($this->form_validation->run() == true) 
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $user_name = $this->input->post('user_name');
            $like_array = array();
            if ($this->input->post('username')) 
            {
                $like_array['username'] = $user_name;
            }
            if ($this->input->post('lastname')) 
            {
                $like_array['last_name'] = $user_name;
            }
            if ($this->input->post('useremail')) 
            {
                $like_array['email'] = $user_name;
            }            
            $this->data['users'] = $this->ion_auth->or_like($like_array)->users()->result();
            foreach ($this->data['users'] as $k => $user) 
            {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $base = base_url();            
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";                
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", "admin/show_searched_user", $this->data);
        } 
        else 
        { 
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['user_name'] = array('name' => 'user_name',
                'id' => 'user_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_name'),
            );
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_admin');
            $this->template->load("admin/templates/admin_tmpl", 'admin/search_user', $this->data);
        }
    }

    /*
     * Sign in method
     */    
    function signin() 
    {
        $this->data['message'] = '';
        
        $this->data['identity'] = array('name' => 'identity',
            'id' => 'identity',
            'type' => 'text'
        );
        $this->data['password'] = array('name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->template->load(LOGIN_TEMPLATE, "admin/admin_login", $this->data);
    }

    /*
     * Admin wants to login
     */
    function login() 
    {
        $this->data['title'] = "Login";
        $this->data['message'] = "";
        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'))) 
            {
                if (!$this->ion_auth->is_admin()) {
                    //User wants to access this page
                    $this->session->set_flashdata('message', 'You are not allowed to access this page.');
                    redirect('admin/login', 'refresh');
                }
                else
                {
                    //Admin login is successful
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect("admin", 'refresh');
                }                
            } 
            else 
            {
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('admin/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } 
        else 
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text'
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password'
            );
            $this->template->load(LOGIN_TEMPLATE, 'admin/admin_login', $this->data);           
        }
        
    }

    /*
     * Admin wants to logout
     */
    function logout() {
        $this->data['title'] = "Logout";
        //log the user out
        $logout = $this->ion_auth->logout();
        //redirect them back to the page they came from
        redirect('admin/login', 'refresh');
    }

    /*
     * Call back function to check password for one char in caps and one number
     * @param $password, password to be checked
     */
    function password_check($password) {
        if (!(preg_match("/[A-Z]/", $password) && preg_match("/[0-9]/", $password))) {
            $this->form_validation->set_message('password_check', 'Password should contain at least 1 char in caps and 1 number');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Call back function to check whether email exists or not
     * @param $email, email to be checked
     */
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

    function _get_csrf_nonce() 
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() 
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
