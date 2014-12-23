<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->library('project/project_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    function index() {
        
    }    
    public function create_program()
    {
        $user_id = $this->session->userdata('user_id');
        $this->data['message'] = "";
        $this->form_validation->set_rules('program_name', 'Program Name', 'required|xss_clean');
        if($this->input->post('submit_create_program'))
        {
            if ($this->form_validation->run() == true) 
            {
                $program_name = $this->input->post('program_name');
                $additional_data = array(
                    'project_name' => $program_name,
                    'project_type_id' => PROJECT_TYPE_ID_PROGRAM,
                    'user_id' => $user_id,
                    'project_content' => PROGRAM_DEFAULT_CONTENT,
                    'project_content_backup' => PROGRAM_DEFAULT_CONTENT
                );
                $project_id = $this->project_library->create_project($additional_data);
                if($project_id !== FALSE)
                {
                    $configuration_file_path = "./xml/program.xml";
                    $project_configuration = "";
                    if (file_exists($configuration_file_path)) {
                        $project_configuration = file_get_contents($configuration_file_path);  
                        $file_path = "./xml/".$project_id.".xml";
                        if ( ! write_file($file_path, $project_configuration))
                        {
                            $this->data['message'] = 'Unable to create configuration file after project creation';
                        }
                        else
                        {
                            redirect("programs/load_program/".$project_id, 'refresh');
                        }
                    }
                    else
                    {
                        $this->data['message'] = 'Unable to load configuration file after project creation';
                    }
                }
                else
                {
                    $this->data['message'] = $this->project_library->errors();
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
        $this->data['program_name'] = array(
            'name' => 'program_name',
            'id' => 'program_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('program_name'),
        );
        $this->data['submit_create_program'] = array(
            'name' => 'submit_create_program',
            'id' => 'submit_create_program',
            'type' => 'submit',
            'value' => 'Create Program'
        );
        $this->template->load(MEMBER_HOME_TEMPLATE, 'project/js/program/create_program', $this->data);
    }
    public function create_script()
    {
        $user_id = $this->session->userdata('user_id');
        $this->data['message'] = "";
        $this->form_validation->set_rules('script_name', 'Script Name', 'required|xss_clean');
        if($this->input->post('submit_create_script'))
        {
            if ($this->form_validation->run() == true) 
            {
                $script_name = $this->input->post('script_name');
                $additional_data = array(
                    'project_name' => $script_name,
                    'project_type_id' => PROJECT_TYPE_ID_SCRIPT,
                    'user_id' => $user_id,
                    'project_content' => SCRIPT_DEFAULT_CONTENT,
                    'project_content_backup' => SCRIPT_DEFAULT_CONTENT
                );
                $project_id = $this->project_library->create_project($additional_data);
                if($project_id !== FALSE)
                {
                    $configuration_file_path = "./xml/script.xml";
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
                            redirect("scripts/load_script/".$project_id, 'refresh');
                        }
                    }
                    else
                    {
                        $this->data['message'] = 'Unable to load configuration file after project creation';
                    }
                }
                else
                {
                    $this->data['message'] = $this->project_library->errors();
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
        $this->data['script_name'] = array(
            'name' => 'script_name',
            'id' => 'script_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('script_name'),
        );
        $this->data['submit_create_script'] = array(
            'name' => 'submit_create_script',
            'id' => 'submit_create_script',
            'type' => 'submit',
            'value' => 'Create Script'
        );
        $this->template->load(MEMBER_HOME_TEMPLATE, 'project/js/script/create_script', $this->data);
    }
    /*
     * This method will show all projects
     */
    public function show_all_projects($user_id = 0)
    {
        if($user_id == 0)
        {
            $user_id = $this->session->userdata('user_id');
        }        
        $this->data['projects'] = $this->project_library->get_all_projects($user_id)->result();
        $this->template->load(MEMBER_HOME_TEMPLATE, "project/projects", $this->data); 
    }
    /*
     * This method will show all programs
     */
    public function show_all_programs($user_id = 0)
    {
        if($user_id == 0)
        {
            $user_id = $this->session->userdata('user_id');
        }        
        $this->data['projects'] = $this->project_library->get_all_programs($user_id)->result();
        $this->template->load(MEMBER_HOME_TEMPLATE, "project/projects", $this->data); 
    }
    /*
     * This method will show all scripts
     */
    public function show_all_scripts($user_id = 0)
    {
        if($user_id == 0)
        {
            $user_id = $this->session->userdata('user_id');
        }        
        $this->data['projects'] = $this->project_library->get_all_scripts($user_id)->result();
        $this->template->load(MEMBER_HOME_TEMPLATE, "project/projects", $this->data); 
    }    
    /*
     * Ajax call to delete a project
     */
    public function delete_project()
    {
        $response = array();
        $project_id = $this->input->post('project_id');
        $user_id = $this->session->userdata('user_id');
        $project_info_array = $this->project_library->get_project_info($project_id)->result_array();
        if(!empty($project_info_array))
        {
            $project_info = $project_info_array[0];
            if($project_info['user_id'] != $user_id)
            {
                $response['message'] = "You don't have permission to delete this project."; 
            }
            else
            {
                if($this->project_library->delete_project($project_id))
                {
                    $response['message'] = "Successfully deleted.";
                }
                else
                {
                    $response['message'] = "Server error. Try again later.";
                }
            }
        }
        else
        {
            $response['message'] = "No such project to delete."; 
        }
        echo json_encode($response);
    }
}