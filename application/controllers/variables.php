<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Variables extends CI_Controller
{
    public $project_types_list = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('project/js/variable/variable_library');
        $this->load->library('ion_auth');
        $this->project_types_list = $this->config->item('project_types', 'ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('file');
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
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        
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
        
        if(isset($_POST['project_left_panel_content_backup']) && $_POST['project_left_panel_content_backup'] != "")
        {
            $project_left_panel_content_backup = $_POST['project_left_panel_content_backup'];
            $data = array(
                'project_content_backup' => $project_left_panel_content_backup
            );
            $this->ion_auth->where('project_id',$project_id)->update_project($data);

        }
        $project_infos = $this->ion_auth->where('project_info.project_id',$project_id)->projects()->result();
        $project_info = $project_infos[0];
        $redirect_path = "";
        if( $project_info->project_type_id == $this->project_types_list['program_id'])
        {
            $redirect_path = "programs/load_program/".$project_id;
        }
        else if($project_info->project_type_id == $this->project_types_list['script_id'])
        {
            $redirect_path = "scripts/load_script/".$project_id;
        }  
        //loading the project
        redirect($redirect_path, 'refresh');
    }
}
?>
