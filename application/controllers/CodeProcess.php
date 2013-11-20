<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CodeProcess
 *
 * @author Alamgir
 */
class CodeProcess extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('ion_auth');
        $this->load->helper('file');
    }

    /*
     *  This controller is called when variable validation id performed
     *  This method calls ion_auth model to check the existance of a variable name
     */
    function check_variable()
    {
        $variable_name = $_POST['variable_name'];
        $project_id = $this->session->userdata('project_id');
        
        $array = array(
            "variable_name" => $variable_name,
            "project_id" => $project_id,
        );
        $var_name_exist = $this->ion_auth->where($array)->check_variable();
        
        echo $var_name_exist;
    }
    
    
}
?>
