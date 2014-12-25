<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        if( !$this->ion_auth->valid_session() )
        {
            redirect('auth/login','refresh');
        }
    }

    function index()
    {        
       redirect('user/show_user', 'refresh');
    }
    
    /*
     * This method will update user information
     */
    function edit_user()
    {
        $user_id = $this->session->userdata('user_id');        
        //validate form input
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('countries', 'Country', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_password_check');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        if($this->input->post('submit_update_user'))
        {
            if ($this->form_validation->run() == true) 
            {
               $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('countries'),
                );            
                //user changes password field, so we need to update password
                if($this->input->post('password') != $this->config->item('samply_dummy_password', 'ion_auth'))
                {
                    $additional_data['password'] = $this->input->post('password');
                }
                if($this->ion_auth->update_user($user_id, $additional_data))
                {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect('user/edit_user', 'refresh');
                }
                else
                {
                    $this->data['message'] = $this->ion_auth->errors();
                }
            }
            else
            {
                $this->data['message'] = (validation_errors() ? validation_errors() : '');
            }
        }
        else
        {
            $this->data['message'] = $this->session->flashdata('message'); 
        }
        $user_info = array();
        $user_info_array = $this->ion_auth->where('users.id',$user_id)->users()->result_array();
        if(empty($user_info_array))
        {
            redirect('auth', 'refresh');
        }   
        else
        {
            $user_info = $user_info_array[0];
        }        
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
        foreach ($countries as $country)
        {
            $this->data['countries'][$country['iso']] = $country['printable_name'];
        }
        $this->data['selected_country'] = $user_info['country'];
        $this->data['submit_update_user'] = array(
            'name' => 'submit_update_user',
            'id' => 'submit_update_user',
            'type' => 'submit',
            'value' => 'Update User'
        );
        $this->template->load(MEMBER_HOME_TEMPLATE, "auth/edit_user", $this->data);
    }
    
    /*
     * This method will show user info
     */
    function show_user()
    {
        $this->data['message'] = "";
        $user_info = array();
        $user_info_array = $this->ion_auth->where('users.id',$this->session->userdata('user_id'))->users()->result_array();
        if(empty($user_info_array))
        {
            redirect('auth', 'refresh');
        }
        else
        {
            $user_info = $user_info_array[0];
        }
        $countries = $this->ion_auth->order_by('printable_name','asc')->get_all_countries()->result_array();
        foreach ($countries as $country)
        {
            if($user_info['country'] == $country['iso'])
            {
                $user_info['country'] = $country['printable_name'];
                break;
            }
        }
        $this->data['user_info'] = $user_info;
        $this->template->load(MEMBER_HOME_TEMPLATE, "auth/show_user", $this->data);    
    }
}
