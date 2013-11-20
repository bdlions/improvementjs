<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Programs extends CI_Controller {

    public $project_types_list = array();

    function __construct() {
        parent::__construct();
        $this->load->library('project/js/program/program');
        $this->load->library('ion_auth');
        $this->project_types_list = $this->config->item('project_types', 'ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('file');
        if (!$this->ion_auth->logged_in()) {
            //redirect('auth/login', 'refresh');
        }
    }

    function index() {
        
    }

    function create_program() {
        $this->data['message'] = '';
        $this->data['title'] = "Create Program";
        $user_id = $this->session->userdata('user_id');
        $this->form_validation->set_rules('program_name', 'Program Name', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            if ( $this->program->is_program_name_exists($this->input->post('program_name')) ) {
                $this->data['message'] = 'Program name already exists. Please use a different name';
            } 
            else 
            {
                $additional_data = array(
                    'project_name' => $this->input->post('program_name'),
                    'user_id' => $user_id,
                    'project_type_id' => $this->project_types_list['program_id'],
                    'project_content' => '<li class="ui-widget-content" id="0">Click here to edit block</li>',
                    'project_content_backup' => '<li class="ui-widget-content" id="0">Click here to edit block</li>',
                );
                $project_id = $this->program->create_program($additional_data);
                if( $project_id !== FALSE )
                {
                    $configuration_file_path = "./xml/program.xml";
                    $project_configuration = "";
                    if (file_exists($configuration_file_path)) {
                        $project_configuration = file_get_contents($configuration_file_path);  
                        //storing feature xml file for this project
                        $file_path = "./xml/".$project_id.".xml";
                        if ( ! write_file($file_path, $project_configuration))
                        {
                            $this->data['message'] = 'Unable to create configuration file after project creation';
                        }
                        else
                        {
                            $redirect_path = "programs/load_program/".$project_id;
                            redirect($redirect_path, 'refresh');
                        }
                    }
                    else
                    {
                        $this->data['message'] = 'Unable to load configuration file after project creation';
                    }
                }
                else
                {
                    $this->data['message'] = $this->program->errors();
                }
            }
        } 
        else 
        {
            $this->data['message'] = validation_errors();
        }
        $this->data['program_name'] = array(
            'name' => 'program_name',
            'id' => 'program_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('program_name'),
        );

        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}css/form_design.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />" . "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", 'project/js/program/create_program', $this->data);
    }

    function all_programs() {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id,
            'project_type_id' => $this->project_types_list['program_id']
        );
        $this->data['programs'] = $this->program->where($where)->get_all_programs()->result();
        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", "project/js/program/program", $this->data);
    }
    
    function delete_program($project_id) {
        $this->data['submit_button_yes'] = array(
            'name' => 'delete_program_yes',
            'id' => 'delete_program_yes',
            'type' => 'submit',
            'value' => 'Yes'
        );
        $this->data['submit_button_no'] = array(
            'name' => 'delete_program_no',
            'id' => 'delete_program_no',
            'type' => 'submit',
            'value' => 'No'
        );
        if ($this->input->post('delete_program_yes')) 
        {
            $this->program->where('project_id', $project_id)->delete_program();
            //after deleting program delete all program related files---------------------------
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            $this->template->load("default_template", "project/js/program/delete_program_successful");
        } 
        else if ($this->input->post('delete_program_no')) {
            //loading admin dashboard
            redirect("auth", 'refresh');
        } 
        else {
            //loading user deletion confirmation page
            $this->data['project_id'] = $project_id;
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            $this->template->load("default_template", "project/js/program/delete_program_confirmation", $this->data);
        }
    }
    
    function load_program($project_id)
    {
        
        $session_data = array(
            'current_project_type_id' => $this->project_types_list['program_id']
        );
        $this->session->set_userdata($session_data);
            
        $this->data['project_type'] = "Program"; 
        
        $this->session->set_userdata('project_id', $project_id);
        $base = base_url();
        $this->template->set('title', 'Welcome');
        //$this->template->set('main_content', 'test');
        $this->template->set('main_content', 'welcome_message');
        $this->template->set('menu_bar', 'design/menu_bar');
        $this->template->set('left_side_bar', 'design/code_process/left_side_bar');

        $this->load->library('xmlperser');
        $fObjectArray = $this->xmlperser->readXML();
        
        //$this->load->model('language_process_model');
        $custom_variables = $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->get_project_variables()->result();

        $this->template->set('custom_variables', $custom_variables);

        $this->template->set('fObjectArray', $fObjectArray);
        
        //$selected_project = $this->ion_auth->where('project_info.project_id',$project_id)->projects()->result();
        $selected_project = $this->program->where('project_id',$project_id)->get_all_programs()->result();
        $this->data['selected_project'] = $selected_project[0];
        
        $user_projects = $this->program->where('user_id',$this->session->userdata('user_id'))->get_all_programs()->result();
        $user_project_name_list = "";
        $user_project_id_list = "";
        foreach ($user_projects as $user_project):
           $user_project_name_list = $user_project_name_list.$user_project->project_name.",";
            $user_project_id_list = $user_project_id_list.$user_project->project_id.",";
        endforeach;
        $this->data['user_project_name_list'] = $user_project_name_list;
        $this->data['user_project_id_list'] = $user_project_id_list;
        
        $this->data['base_url'] = base_url();
        
        //replacing project_content_backup by project_content
        $data = array(
                'project_content_backup' => $selected_project[0]->project_content
        );
        $this->program->where('project_id',$this->session->userdata('project_id'))->update_program($data);
        
        $external_variable_list = array();
        $external_variable_values = array();
        //if ($this->session->userdata('external_variable_list') !== FALSE) {
           //$external_variable_list= $this->session->userdata('external_variable_list');
        //}   
        $this->data['selected_anchor_id'] = "";
        $this->data['has_external_variables'] = "false";
        $this->data['is_cancel_pressed_external_variable_upload'] = "false";
        $this->data['external_file_content_error'] = "false";
        if ($this->session->userdata('external_variable_values') !== FALSE && count($this->session->userdata('external_variable_values')) > 0) {
           $this->data['selected_anchor_id'] = $this->session->userdata('selected_anchor_id');
           $this->session->set_userdata('selected_anchor_id', "");
           $this->data['has_external_variables'] = "true";
           $external_variables= $this->session->userdata('external_variable_list');
           $external_variable_values = $this->session->userdata('external_variable_values');
           foreach($external_variables as $external_variable)
           {
               $external_variable_list[] = $external_variable;
           }
           //print_r(count($external_variable_list));
           //print_r($external_variable_list);
           $reset_array = array();
           $this->session->set_userdata('external_variable_list', $reset_array);
           $this->session->set_userdata('external_variable_values', $reset_array);
           //$this->session->unset_userdata('external_variable_list');
        }
        else if($this->session->userdata('is_cancel_pressed_external_variable_upload') !== FALSE && $this->session->userdata('is_cancel_pressed_external_variable_upload') == "true")
        //else
        {
            $this->data['selected_anchor_id'] = $this->session->userdata('selected_anchor_id');
            $this->session->set_userdata('selected_anchor_id', "");
            $this->data['is_cancel_pressed_external_variable_upload'] = $this->session->userdata('is_cancel_pressed_external_variable_upload');
            $this->session->set_userdata('is_cancel_pressed_external_variable_upload', "false");
        }
        else if($this->session->userdata('external_file_content_error') !== FALSE && $this->session->userdata('external_file_content_error') == "true")
        //else
        {
            $this->data['selected_anchor_id'] = $this->session->userdata('selected_anchor_id');
            $this->session->set_userdata('selected_anchor_id', "");
            $this->data['external_file_content_error'] = $this->session->userdata('external_file_content_error');
            $this->session->set_userdata('external_file_content_error', "false");
        }
        
        $user_infos = $this->ion_auth->where('users.id',$this->session->userdata('user_id'))->users()->result_array();
        if(count($user_infos) <= 0)
        {
            $user_info['username'] = 'Guest';
        }
        $user_info = $user_infos[0];
        $this->data['user_info'] = $user_info;
        $this->data['external_variable_list'] = $external_variable_list;
        $this->data['external_variable_values'] = $external_variable_values;         
        $this->template->load("default_template", 'welcome_message', $this->data);
    }

}

?>
