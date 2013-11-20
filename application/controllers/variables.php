<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Variables extends CI_Controller
{
    public $project_types_list = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->project_types_list = $this->config->item('project_types', 'ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->library('project/js/variable/variable_library');
        $this->load->library('project/js/program/program');
        $this->load->library('project/js/script/script');
        if( !$this->ion_auth->logged_in() )
        {
            redirect('auth/login','refresh');
        }
    }

    function index()
    {        
       
    }
    
    /*
     * This method will create a new variable
     */
    function create_variable()
    {
        $project_id = $this->session->userdata('project_id');        
        $variable_name = $_POST['add_variable_name'];
        $variable_type = $_POST['add_variable_type_selection_combo'];
        $variable_value = "";
        if($variable_type == 'BOOLEAN')
        {
            $variable_value = $_POST['add_variable_value_selection_combo'];
        }
        else if($variable_type == 'NUMBER')
        {
            $variable_value = $_POST['add_variable_value'];
        }        
        $additional_variable_data = array(
            'variable_name' => $variable_name,
            'variable_type' => $variable_type,
            'variable_value' => $variable_value,
        );
        $additional_project_data = array(
            'project_id' => $project_id,
        );
        $this->variable_library->create_variable($additional_variable_data, $additional_project_data);
        $project_type_id = $this->session->userdata('current_project_type_id');
        $redirected_path = "";
        if(isset($_POST['project_left_panel_content_backup']) && $_POST['project_left_panel_content_backup'] != "")
        {
            $project_left_panel_content_backup = $_POST['project_left_panel_content_backup'];
            $data = array(
                'project_content_backup' => $project_left_panel_content_backup
            );
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
                $redirected_path = "programs/load_program/".$project_id;
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
                $redirected_path = "scripts/load_script/".$project_id;
            }
        }
        redirect($redirected_path, 'refresh');
    }
    
    /*
     * This method deletes a variable from the database
     */
    function delete_variable()
    {
        $project_id = $this->session->userdata('project_id');    
        $project_type_id = $this->session->userdata('current_project_type_id');
        $redirected_path = "";
        //updating current project left panel content as a backup and this will be used while loading the project again
        if(isset($_POST['delete_variable_project_left_panel_content']) && $_POST['delete_variable_project_left_panel_content'] != "")
        {
            $project_left_panel_content = $_POST['delete_variable_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content
            );
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
                $redirected_path = "programs/load_program/".$project_id;
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
                $redirected_path = "scripts/load_script/".$project_id;
            }
        }
        //deleting variable
        if(isset($_POST['delete_variable_variable_id']))
        {
            $variable_id = $_POST['delete_variable_variable_id'];
            if($variable_id > 0){
                $this->ion_auth->where('variable_id',$variable_id)->delete_project_variable();
                $this->ion_auth->where('variable_id',$variable_id)->delete_variable();
            }            
        }
        redirect($redirected_path, 'refresh');
    }
}
?>
