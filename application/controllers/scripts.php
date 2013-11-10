<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scripts extends CI_Controller
{
    public $project_types_list = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('project/js/script/script');
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
    
    function create_script()
    {
        $this->data['message'] = '';
        $this->data['title'] = "Create Script";
        $user_id = $this->session->userdata('user_id');
        $this->form_validation->set_rules('script_name', 'Script Name', 'required|xss_clean');
        
        if ($this->form_validation->run() == true)
        {            
            if ( $this->script->is_script_name_exists($this->input->post('script_name')) ) {
                $this->data['message'] = 'Script name already exists. Please use a different name';
            }
            else 
            {
                $additional_data = array(
                    'project_name' => $this->input->post('script_name'),
                    'user_id' => $user_id,
                    'project_type_id' => $this->project_types_list['script_id'],
                    'project_content' => '<li class="ui-widget-content" id="0">Click here to edit block</li>',
                    'project_content_backup' => '<li class="ui-widget-content" id="0">Click here to edit block</li>',
                );
                $project_id = $this->script->create_script($additional_data);
                if( $project_id !== FALSE )
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
                            $redirect_path = "scripts/load_script/".$project_id;
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
                    $this->data['message'] = $this->script->errors();
                }
            }
        }  
        else
        {
            $this->data['message'] = validation_errors();
        }        
        $this->data['script_name'] = array(
            'name' => 'script_name',
            'id' => 'script_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('script_name'),
        );

        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}css/form_design.css' />"."<link rel='stylesheet' href='{$base}css/bluedream.css' />"."<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", 'project/js/script/create_script', $this->data);
    }
    
    function all_scripts()
    {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id,
            'project_type_id' => $this->project_types_list['script_id']
        );
        $this->data['scripts'] = $this->script->where($where)->get_all_scripts()->result();
        $base = base_url();
        $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", "project/js/script/script", $this->data);
    }
    
    function delete_script($project_id) {
        $this->data['submit_button_yes'] = array(
            'name' => 'delete_script_yes',
            'id' => 'delete_script_yes',
            'type' => 'submit',
            'value' => 'Yes'
        );
        $this->data['submit_button_no'] = array(
            'name' => 'delete_script_no',
            'id' => 'delete_script_no',
            'type' => 'submit',
            'value' => 'No'
        );
        if ($this->input->post('delete_script_yes')) 
        {
            $this->script->where('project_id', $project_id)->delete_script();
            //after deleting script delete all script related files---------------------------
            $base = base_url();
            $css = "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" . "<link rel='stylesheet' href='{$base}css/bluedream.css' />";
            $this->template->set('css', $css);
            $this->template->set('menu_bar', 'design/menu_bar_member_demo');
            $this->template->load("default_template", "project/js/script/delete_script_successful");
        } 
        else if ($this->input->post('delete_script_no')) {
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
            $this->template->load("default_template", "project/js/script/delete_script_confirmation", $this->data);
        }
    }
    
    function load_script($project_id)
    {
        
        $this->data['project_type'] = "Script"; 
        
        $this->session->set_userdata('project_id', $project_id);
        $base = base_url();
        $css =
                "<link rel='stylesheet' href='{$base}jstree_resource/design.css' />" .
                "<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />" .
                "<link rel='stylesheet' type='text/css' href='{$base}css/jquery-ui.css'/>" .
                "<link type='text/css' rel='stylesheet' href='{$base}jstree_resource/_docs/syntax/!style.css'/>";

        $js = "<script type=\"text/javascript\" src=\"{$base}js/jquery.min.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/parse_feature_xml.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/feature.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/parameter.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/code_process.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/_lib/jquery.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/_lib/jquery.cookie.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/_lib/jquery.hotkeys.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/jquery.jstree.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}js/jquery-ui.min.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/_docs/syntax/!script.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/custom_script.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/manage_variables.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/common.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/parameter_table.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/manage_action.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}script/variable.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/logical_connector_script.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/arithmetic_operator_script.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}jstree_resource/menu_item_script.js\"></script>" .
                "<script type=\"text/javascript\" src=\"{$base}js/jquery.blockUI.js\" ></script>" .
        

        $this->template->set('css', $css);
        $this->template->set('js', $js);
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
        $selected_project = $this->script->where('project_id',$project_id)->get_all_scripts()->result();
        $this->data['selected_project'] = $selected_project[0];
        
        $user_projects = $this->script->where('user_id',$this->session->userdata('user_id'))->get_all_scripts()->result();
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
        $this->script->where('project_id',$this->session->userdata('project_id'))->update_script($data);
        
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
