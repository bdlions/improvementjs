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
    
    public function load_project_list_project_panel()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        if(isset($_POST['button_pre_load_project_ok']) && isset($_POST['pre_load_project_left_panel_content']) && $_POST['pre_load_project_left_panel_content'] != "")
        {            
            $project_left_panel_content = $_POST['pre_load_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content,
                'project_content' => $project_left_panel_content
            );
            $project_type_id = $this->session->userdata('current_project_type_id');
            $project_id = $this->session->userdata('project_id');
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
            }            
        }
        redirect("auth", 'refresh');
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
     * User wants to upload a project
     */
    function upload_project()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        $project_id = $this->session->userdata('project_id');
        if(isset($_POST['upload_project_project_left_panel_content']) && $_POST['upload_project_project_left_panel_content'] != "")
        {
            $project_left_panel_content = $_POST['upload_project_project_left_panel_content'];
            $data = array(
                'project_content_backup' => $project_left_panel_content
            );
            $project_type_id = $this->session->userdata('current_project_type_id');
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
            }
        }
        
        $this->data['project_id'] = $project_id;
        $base = base_url();        
        $css ="<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", "upload/upload_project", $this->data);    
    }
    /*
     * Processing uploaded file content of a project
     */
    function post_upload_project()
    {
        
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        $redirected_path = "";
        $project_type_id = $this->session->userdata('current_project_type_id');
        $project_id = $this->session->userdata('project_id'); 
        if($this->input->post('upload'))
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
                $css ="<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                $this->template->load("default_template", "upload/upload_project", $this->data); 
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
                    $css ="<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                    $this->template->set('css', $css);
                    $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                    $this->template->load("default_template", "upload/upload_project", $this->data);
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
                        $this->variable_library->create_variable($additional_variable_data, $additional_project_data);
                    endforeach;
                    $properties = $project_xml_object->properties;
                    if( $properties->project_type_id != null && $properties->project_type_id != "")
                    {
                        $project_type_id = $properties->project_type_id;
                    }               
                }                
            }
        }
        if( $project_type_id == $this->project_types_list['program_id'])
        {
            $redirected_path = "programs/load_program/".$project_id;
        }
        else if( $project_type_id == $this->project_types_list['script_id'])
        {
            $redirected_path = "scripts/load_script/".$project_id;
        }
        redirect($redirected_path, 'refresh');
    }
    function upload_external_variable()
    {
        if(isset($_POST['external_variable_upload_project_left_panel_content']) && $_POST['external_variable_upload_project_left_panel_content'] != "")
        {
            $natural_language_panel_anchor_id = "";
            if(isset($_POST['ev_anchor_id']))
            {
                $natural_language_panel_anchor_id = $_POST['ev_anchor_id'];            
            }            
            $this->session->set_userdata('selected_anchor_id', $natural_language_panel_anchor_id);
            $project_left_panel_content = $_POST['external_variable_upload_project_left_panel_content'];
            
            $data = array(
                'project_content_backup' => $project_left_panel_content
            );
            $project_type_id = $this->session->userdata('current_project_type_id');
            $project_id = $this->session->userdata('project_id');
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
            }
        }
        $base = base_url();        
        $css ="<link rel='stylesheet' href='{$base}css/bluedream.css' />";
        $this->template->set('css', $css);
        $this->template->set('menu_bar', 'design/menu_bar_member_demo');
        $this->template->load("default_template", "upload/upload_external_variables");
    }
    function upload_external_variables_post_processing()
    {
        $project_id = $this->session->userdata('project_id'); 
        if($this->input->post('upload'))
        {
            $config['upload_path'] = './external_variables/';
            $config['allowed_types'] = 'txt';
            $config['max_size'] = '5000';
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
                $this->data['error'] = $error;
                $base = base_url();        
                $css ="<link rel='stylesheet' href='{$base}css/bluedream.css' />";
                $this->template->set('css', $css);
                $this->template->set('menu_bar', 'design/menu_bar_member_demo');
                $this->template->load("default_template", 'upload/upload_external_variables', $this->data);
            } else {
                $data = $this->upload->data();
                $uploaded_file_name = $data['raw_name'];
                $uploaded_file_path = "./external_variables/".$uploaded_file_name.".txt";
                $external_file_content = "";
                if (file_exists($uploaded_file_path)) {
                    $external_file_content = file_get_contents($uploaded_file_path);                
                }
                $external_variable_list = array();
                $variable_name = array();
                $external_variable_values = array();
                $external_variable_values[0] = "\"".$uploaded_file_name."\"";
                $file_content_array = explode("\n", $external_file_content);
                $total_lines = count($file_content_array);
                $variable_counter = 0;
                for($counter = 0 ; $counter < $total_lines ; $counter++)
                {
                    $each_line_content = $file_content_array[$counter];
                    $each_line_content = trim($each_line_content);
                    $first_word = substr($each_line_content, 0, 8);
                    //Step 1 finding all lines that start with external
                    if($first_word == "external")
                    {
                        $external_variable_list[$variable_counter] = $each_line_content;
                        //Step 2 removing stuff after ;
                        $temp_string  =  explode(";", $each_line_content);
                        
                        if(count($temp_string) > 1)
                        {
                            $comment_string = $temp_string[1];
                            $comment_string = trim($comment_string);
                            $comment_first_word = substr($comment_string, 0, 8);
                            if($comment_first_word == "external")
                            {
                                $this->session->set_userdata('external_file_content_error', "true");
                                $redirect_path = "welcome/load_project/".$project_id;
                                redirect($redirect_path, 'refresh');
                                return;
                            }
                        }
                        
                        $each_line_content = $temp_string[0];
                        $temp_string  =  explode("=", $each_line_content);
                        //storing variable value
                        $external_variable_values[$variable_counter+1] = " ".trim($temp_string[1]);
                        $temp_string = explode(" ", trim($temp_string[0]));
                        //Step 3 getting the variable names
                        $variable_name[$variable_counter] = $temp_string[count($temp_string)-1];
                        //print_r("variable->".$variable_name[$variable_counter].":".$external_variable_values[$variable_counter]."--");
                        $variable_counter++;
                    }                
                }
                $this->session->set_userdata('external_variable_list', $external_variable_list);
                $this->session->set_userdata('external_variable_values', $external_variable_values);
            }
        }
        else if($this->input->post('cancel'))
        {
            $this->session->set_userdata('is_cancel_pressed_external_variable_upload', "true");
        }
        $redirected_path = "";
        $project_type_id = $this->session->userdata('current_project_type_id');
        if( $project_type_id == $this->project_types_list['program_id'])
        {
            $redirected_path = "programs/load_program/".$project_id;
        }
        else if( $project_type_id == $this->project_types_list['script_id'])
        {
            $redirected_path = "scripts/load_script/".$project_id;
        }
        redirect($redirected_path, 'refresh');
        
    }
    function download_project_code()
    {
        $project_id = $this->session->userdata('project_id');
        $file_path = "./code/".$project_id.".txt";
        if (file_exists($file_path)) {
            $content = "";
            $lines = file($file_path); // gets file in array using new lines character
            foreach($lines as $line)
            {
                $content = $content.$line."\r\n";
            }
            header("Content-Type:text/plain");
            //header("Content-Length: " . filesize($file_path));
            header("Content-Disposition: 'attachment'; filename=code.txt");
            echo $content;
        }
    } 
    //--------------------------------- Ajax calling related methods-----------------------------
    /*
     * This method will update project left panel content
     */
    function update_project() 
    {
        if ( !$this->ion_auth->logged_in() ) 
        {
            redirect('auth/login', 'refresh');
        } 
        if( isset($_POST['project_content']) && $_POST['project_content'] != "" )
        {
            $project_content = $_POST['project_content'];
            $data = array(
                'project_content' => $project_content,
                'project_content_backup' => $project_content
            );
            $project_type_id = $this->session->userdata('current_project_type_id');
            $project_id = $this->session->userdata('project_id');
            if( $project_type_id == $this->project_types_list['program_id'])
            {
                $this->program->where('project_id',$project_id)->update_program($data);
            }
            else if( $project_type_id == $this->project_types_list['script_id'])
            {
                $this->script->where('project_id',$project_id)->update_script($data);
            }
            echo "Project is saved successfully.";
        }
        else
        {
            echo "Error while saving project. Please try again later.";
        }
    }
    
    /*
     * Before downloading project info we are generating xml file with project content and variables in server
     */
    function save_project_left_panel_and_variables()
    {
        if ( !$this->ion_auth->logged_in() ) 
        {
            redirect('auth/login', 'refresh');
        } 
        $status = "false";
        $project_type_id = $this->session->userdata('current_project_type_id');
        $project_code_variables = "<project><code>";
        $project_id = $this->session->userdata('project_id');
        if( isset($_POST['code']) && $_POST['code'] != "" )
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
            $project_code_variables = $project_code_variables."<properties>";
            $project_code_variables = $project_code_variables."<project_type_id>".$project_type_id."</project_type_id>";
            $project_code_variables = $project_code_variables."</properties>";
            $project_code_variables = $project_code_variables."</project>";
            $file_path = "./project/".$project_id.".xml";
            if ( write_file($file_path, $project_code_variables))
            {
                $status = "true";
            }
        }
        echo $status;
    }
    
    function save_project_code()
    {
        $status = "false";
        $project_id = $this->session->userdata('project_id');
        if(isset($_POST['code']))
        {
            $project_code = $_POST['code'];
            $file_path = "./code/".$project_id.".txt";
            if ( write_file($file_path, $project_code))
            {
                $status = "true";
            }
        }
        echo $status;
    }
}
?>
