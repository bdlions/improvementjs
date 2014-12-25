<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scripts extends CI_Controller
{
    public $project_types_list = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('project/js/script/script');
        $this->load->library('project/project_library');
        $this->load->library('ion_auth');
        $this->project_types_list = $this->config->item('project_types', 'ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('file');
        if( !$this->ion_auth->valid_session() )
        {
            redirect('auth/login','refresh');
        }
    }

    function index()
    {        
       
    }
    
    function load_script($project_id)
    {
        
        $user_id = $this->session->userdata('user_id');
        $this->data['message'] = "";
        $project_info_array = $this->project_library->get_project_info($project_id)->result_array();
        if(!empty($project_info_array))
        {
            $project_info = $project_info_array[0];
            if($project_info['user_id'] != $user_id)
            {
                $this->data['message'] = "You don't have permission to view this project."; 
                $this->template->load(MEMBER_HOME_TEMPLATE, 'auth/show_messages', $this->data);
                return;
            }
        }
        else
        {
            $this->data['message'] = "No such project to view."; 
            $this->template->load(MEMBER_HOME_TEMPLATE, 'auth/show_messages', $this->data);
            return;
        }
        $this->data['project_id'] = $project_id;
        $session_data = array(
            'current_project_type_id' => $this->project_types_list['script_id']
        );
        $this->session->set_userdata($session_data);
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
