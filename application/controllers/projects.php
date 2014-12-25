<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->library('project/project_library');
        if (!$this->ion_auth->valid_session()) {
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
     * This method will upload project
     */
    public function upload_project($project_id = 0)
    {
        $this->data['message'] = "";
        $this->data['project_id'] = $project_id;
        $user_id = $this->session->userdata('user_id');
        $project_info = array();
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
            $this->data['message'] = "No such project to upload."; 
            $this->template->load(MEMBER_HOME_TEMPLATE, 'auth/show_messages', $this->data);
            return;
        }
        $project_type_id = $project_info['project_type_id'];
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
                $this->data['message'] = $this->upload->display_errors(); 
                $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_project", $this->data); 
                return;
            } 
            else 
            {
                $this->load->library('projectxmlparser');
                $project_xml_object = $this->projectxmlparser->readXML();
                //user uploads a wrong project/file
                if($project_xml_object == null)
                {
                    $this->data['message'] = "Invalid file content. Please upload correct project."; 
                    $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_project", $this->data); 
                    return;
                }
                else
                {
                    $properties = $project_xml_object->properties;
                    if($project_type_id != $properties->project_type_id)
                    {                        
                        if( $project_type_id == PROJECT_TYPE_ID_PROGRAM)
                        {
                            $this->data['message'] = "Please upload a program."; 
                        }
                        else if( $project_type_id == PROJECT_TYPE_ID_SCRIPT)
                        {
                            $this->data['message'] = "Please upload a script."; 
                        }
                        $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_project", $this->data); 
                        return;
                    }
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
                    foreach ($custom_variables as $custom_variable){
                        $additional_variable_data = array(
                            'variable_name' => $custom_variable->name,
                            'variable_type' => $custom_variable->type,
                            'variable_value' => $custom_variable->value,
                        );
                        $additional_project_data = array(
                            'project_id' => $this->session->userdata('project_id'),
                        );
                        $this->variable_library->create_variable($additional_variable_data, $additional_project_data);
                    }
                    
                    if( $project_type_id == PROJECT_TYPE_ID_PROGRAM)
                    {
                        redirect("programs/load_program/".$project_id, 'refresh');
                    }
                    else if( $project_type_id == PROJECT_TYPE_ID_SCRIPT)
                    {
                        redirect("scripts/load_script/".$project_id, 'refresh');
                    }
                    else
                    {
                        redirect('auth', 'refresh');     
                    }                              
                }                
            }
        }
        $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_project", $this->data); 
    }
    
    /*
     * This method will upload external variables
     */
    function upload_external_variables($project_id)
    {
        $this->data['project_id'] = $project_id;
        $user_id = $this->session->userdata('user_id');
        $project_info = array();
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
            $this->data['message'] = "No such project to upload."; 
            $this->template->load(MEMBER_HOME_TEMPLATE, 'auth/show_messages', $this->data);
            return;
        }
        $project_type_id = $project_info['project_type_id'];  
        if($this->input->post())
        {
            if($this->input->post('upload'))
            {
                $config['upload_path'] = './external_variables/';
                $config['allowed_types'] = 'txt';
                $config['max_size'] = '5000';
                $config['overwrite'] = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload()) {
                    $this->data['message'] = $this->upload->display_errors(); 
                    $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_external_variables", $this->data); 
                    return;                    
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
            if( $project_type_id == PROJECT_TYPE_ID_PROGRAM)
            {
                $redirected_path = "programs/load_program/".$project_id;
            }
            else if( $project_type_id == PROJECT_TYPE_ID_SCRIPT)
            {
                $redirected_path = "scripts/load_script/".$project_id;
            }
            redirect($redirected_path, 'refresh');
        }
        $this->template->load(MEMBER_HOME_TEMPLATE, "project/upload_external_variables", $this->data);        
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
    
    /*
     * Ajax call to update project left panel
     */
    public function update_project_left_panel()
    {
        $response = array();
        $project_id = $this->input->post('project_id');
        $left_panel_content = $this->input->post('left_panel_content');
        if($project_id > 0)
        {
            $additional_data = array(
                'project_content_backup' => $left_panel_content,
                'project_content' => $left_panel_content
            );
            if($this->project_library->update_project($project_id, $additional_data))
            {
                $response['status'] = 1;
                $response['message'] = $this->project_library->messages_alert();
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = $this->project_library->errors_alert();
            }
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = "Invalid project to update.";
        }
        echo json_encode($response);
    }
    
    /*
     * Ajax call to update project left panel backup
     */
    public function update_project_left_panel_backup()
    {
        $response = array();
        $project_id = $this->input->post('project_id');
        $left_panel_content = $this->input->post('left_panel_content');
        //verify that current user is owner of this project id
        if($project_id > 0)
        {
            $this->session->set_userdata('selected_anchor_id', $this->input->post('selected_anchor_id'));
            $additional_data = array(
                'project_content_backup' => $left_panel_content
            );
            if($this->project_library->update_project($project_id, $additional_data))
            {
                $response['status'] = 1;
                $response['message'] = $this->project_library->messages_alert();
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = $this->project_library->errors_alert();
            }
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = "Invalid project to update.";
        }
        echo json_encode($response);
    }
}