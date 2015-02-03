<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class General_process extends CI_Controller
{
    public $project_types_list = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');        
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('file');
        $this->project_types_list = $this->config->item('project_types', 'ion_auth');
        $this->load->library('project/js/program/program');
        $this->load->library('project/js/script/script');
        $this->load->library('project/js/variable/variable_library');
        if( !$this->ion_auth->logged_in() )
        {
            redirect('auth/login','refresh');
        }
    }

    function index()
    {        
       
    }
    
    /*
     * This method will create a new program/script based on current selected program/script
     */
    function save_as_project()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $project_type_id = $this->session->userdata('current_project_type_id');
        $additional_data = array(
            'project_name' => $this->input->post('save_as_project_project_name'),
            'user_id' => $user_id,
            'project_type_id' => $project_type_id,
            'project_content' => $_POST['save_as_project_left_panel_content'],
            'project_content_backup' => $_POST['save_as_project_left_panel_content'],
        );
        $new_project_id = 0;
        $redirected_path = "";
        if( $project_type_id == $this->project_types_list['program_id'])
        {
            $new_project_id = $this->program->create_program($additional_data);
            $redirected_path = "programs/load_program/".$new_project_id;
        }
        else if( $project_type_id == $this->project_types_list['script_id'])
        {
            $new_project_id = $this->script->create_script($additional_data);
            $redirected_path = "scripts/load_script/".$new_project_id;
        }
        
        //creating feature xml file for this newly created project
        $project_id = $this->session->userdata('project_id');
        $file_path = "./xml/".$project_id.".xml";
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            $new_file_path = "./xml/".$new_project_id.".xml";
            if ( ! write_file($new_file_path, $content))
            {
                echo 'Unable to write the file';
            }
        }    
        //create variable list for this newly created project
        $custom_variables = $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->get_project_variables()->result();
        foreach ($custom_variables as $custom_variable):
            $additional_variable_data = array(
                'variable_name' => $custom_variable->variable_name,
                'variable_type' => $custom_variable->variable_type,
                'variable_value' => $custom_variable->variable_value,
            );
            $additional_project_data = array(
                'project_id' => $new_project_id,
            );
            $this->variable_library->create_variable($additional_variable_data, $additional_project_data);
        endforeach;
        redirect($redirected_path, 'refresh');
    }
    /*
     * This method will overwrite an existing program/script based on current selected program/script
     */
    function save_as_replace_project()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        //$user_id = $this->session->userdata('user_id');
        $project_type_id = $this->session->userdata('current_project_type_id');
        //$replaced_project_name = $_POST['save_as_replace_project_name'];
        $replaced_project_id = $_POST['save_as_replace_project_id'];
        //updating project(to be replaced) left panel content
        if(isset($_POST['save_as_replace_project_left_panel_content']) && $_POST['save_as_replace_project_left_panel_content'] != "")
        {
            $project_left_panel_content_backup = $_POST['save_as_replace_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content_backup,
                'project_content' => $project_left_panel_content_backup
            );
            $redirected_path = "";
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$replaced_project_id)->update_program($data);
                $redirected_path = "programs/load_program/".$replaced_project_id;
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$replaced_project_id)->update_script($data);
                $redirected_path = "scripts/load_script/".$replaced_project_id;
            }
            
            //updating project(to be replaced) feature xml
            $project_id = $this->session->userdata('project_id');
            $file_path = "./xml/".$project_id.".xml";
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $new_file_path = "./xml/".$replaced_project_id.".xml";
                if ( ! write_file($new_file_path, $content))
                {
                    echo 'Unable to write the file';
                }
            }
            
            //updating project(to be replaced) variable list
            $this->ion_auth->where('project_id',$replaced_project_id)->delete_project_variable_map();
            $custom_variables = $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->get_project_variables()->result();
            foreach ($custom_variables as $custom_variable):
                $additional_variable_data = array(
                    'variable_name' => $custom_variable->variable_name,
                    'variable_type' => $custom_variable->variable_type,
                    'variable_value' => $custom_variable->variable_value,
                );
                $additional_project_data = array(
                    'project_id' => $replaced_project_id,
                );
                if( $project_type_id == $this->project_types_list['program_id'])
                {
                    $this->program->clone_program_variable($additional_variable_data, $additional_project_data);
                }
                else if( $project_type_id == $this->project_types_list['script_id'])
                {
                    $this->script->clone_script_variable($additional_variable_data, $additional_project_data);
                }
            endforeach;
            redirect($redirected_path, 'refresh');
        }
        else
        {
            echo "Internal Server Error. Try later.";
        }
        
    }
    /*
     * Ajax Call
     * This method will keep current session alive for the current logged in user
     */
    function keep_server_alive()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
    }
    
    /*
     * Ajax Call
     * This method will logout user 
     */
    function logout() {
        $this->ion_auth->logout();
    }
}
?>
