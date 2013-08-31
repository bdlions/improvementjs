<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Project extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->helper('file');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('array');
        $this->load->helper('url');
    }

    function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
    }
    
    /*
     * User wants to upload a project
     */
    function upload_project()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        
        if(isset($_POST['upload_project_project_left_panel_content']) && $_POST['upload_project_project_left_panel_content'] != "")
        {
            $project_left_panel_content = $_POST['upload_project_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content
            );
            $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->update_project($data);
        }
        
        $this->data['project_id'] = $this->session->userdata('project_id');
        $base = base_url();        
        $css ="<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />"."<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", "program/upload_project", $this->data);    
    }
    
    /*
     * Processing uploaded file content of a project
     */
    function post_upload_project()
    {
        
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        $project_id = $this->session->userdata('project_id'); 
        if($this->input->post('cancel'))
        {
            $redirect_path = "welcome/load_project/".$project_id;
            redirect($redirect_path, 'refresh');
        }
        else
        {
            $config['upload_path'] = './project/';
            $config['allowed_types'] = 'xml';
            $config['max_size'] = '5000';
            $config['file_name'] = $project_id. ".xml";
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) 
            {
                $error = array('error' => $this->upload->display_errors());
                $this->data['error'] = $error;
                $this->data['project_id'] = $this->session->userdata('project_id');
                $base = base_url();        
                $css ="<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />"."<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                $this->template->load("default_template", "program/upload_project", $this->data); 
            } 
            else 
            {
                $this->load->library('projectxmlparser');
                $project_xml_object = $this->projectxmlparser->readXML();
                //user uploads a wrong project/file
                if($project_xml_object == null)
                {
                    $error = array('error' => 'Please upload correct project.');
                    $this->data['error'] = $error;
                    $this->data['project_id'] = $this->session->userdata('project_id');
                    $base = base_url();        
                    $css ="<link rel='stylesheet' href='{$base}jstree_resource/menu_style.css' />"."<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                    $this->template->set('css', $css);
                    $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                    $this->template->load("default_template", "program/upload_project", $this->data);
                }
                else
                {
                    $project_content = $project_xml_object->code;
                    if($project_content != false){
                        $data = array(
                            'project_content' => $project_content,
                            'project_content_backup' => $project_content
                        );
                        $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->update_project($data);
                    }
                    //deleting current variables of this project
                    $current_variable_ids = array();
                    $variable_counter = 0;
                    $custom_variables = $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->get_project_variables()->result();
                    foreach ($custom_variables as $custom_variable):
                        $current_variable_ids[$variable_counter++] = $custom_variable->variable_id;
                    endforeach;
                    if($variable_counter > 0)
                    {
                        $this->ion_auth->where_in('variable_id',$current_variable_ids)->delete_project_variables();
                        $this->ion_auth->where_in('variable_id',$current_variable_ids)->delete_variables();
                    }

                    //adding variables of uploaded file into this project
                    $custom_variables = $project_xml_object->variables;
                    foreach ($custom_variables as $custom_variable):
                        $additional_variable_data = array(
                            'variable_name' => $custom_variable->name,
                            'variable_type' => $custom_variable->type,
                            'variable_value' => $custom_variable->value,
                        );
                        $additional_project_data = array(
                            'project_id' => $this->session->userdata('project_id'),
                        );
                        $this->ion_auth->create_variable($additional_variable_data, $additional_project_data);
                    endforeach;

                    $redirect_path = "welcome/load_project/".$project_id;
                    redirect($redirect_path, 'refresh');
                }                
            }
        }
    }
    
    /*
     * Before downloading project info we are generating xml file with project content and variables in server
     */
    function save_project_left_panel_and_variables()
    {
        $status = "false";
        $project_code_variables = "<project><code>";
        $project_id = $this->session->userdata('project_id');
        if(isset($_POST['code']))
        {
            $custom_variables = $this->ion_auth->where('project_id',$project_id)->get_project_variables()->result();

            $project_code_variables = $project_code_variables.htmlentities($_POST['code']);
            $project_code_variables = $project_code_variables."</code>";
            $project_code_variables = $project_code_variables."<variables>";
            foreach ($custom_variables as $cv)
            {
                $project_code_variables = $project_code_variables."<variable>";
                $project_code_variables = $project_code_variables."<id>".$cv->variable_id."</id>";
                $project_code_variables = $project_code_variables."<name>".$cv->variable_name."</name>";
                $project_code_variables = $project_code_variables."<type>".$cv->variable_type."</type>";
                $project_code_variables = $project_code_variables."<value>".$cv->variable_value."</value>";
                $project_code_variables = $project_code_variables."</variable>";
            }
            $project_code_variables = $project_code_variables."</variables>";
            $project_code_variables = $project_code_variables."</project>";
            $file_path = "./project/".$project_id.".xml";
            if ( write_file($file_path, $project_code_variables))
            {
                $status = "true";
            }
        }
        echo $status;
    }
    
    /*
     * Downloading project content and variables stored in xml file in server
     */
    function download_project()
    {
        $project_id = $this->session->userdata('project_id');
        $file_path = "./project/".$project_id.".xml";
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            if($content == "")
            {
                return;
            }
            $file_name = "project_content.xml";
            if(isset($_POST['project_content_file_name']))
            {
                if(strlen($_POST['project_content_file_name']) > 0 ){
                    $file_name = $_POST['project_content_file_name'].".xml";
                    $_POST['project_content_file_name'] = "";
                }
            }
            header("Content-Type:text/plain");
            header("Content-Length: " . filesize($file_path));
            header("Content-Disposition: 'attachment'; filename=".$file_name);
            echo $content;
        }
    }
    
    /*
     * This method deletes a variable from the database
     */
    function delete_variable()
    {
        //updating current project left panel content as a backup and this will be used while loading the project again
        if(isset($_POST['delete_variable_project_left_panel_content']) && $_POST['delete_variable_project_left_panel_content'] != "")
        {
            $project_left_panel_content = $_POST['delete_variable_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content
            );
            $this->ion_auth->where('project_id',$this->session->userdata('project_id'))->update_project($data);
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
        $redirect_path = "welcome/load_project/".$this->session->userdata('project_id');
        redirect($redirect_path, 'refresh');
    }
}
?>
