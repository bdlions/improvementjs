<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language_process_model extends CI_Model
{
    public $tables = array();
    
    public function __construct()
    {
        parent::__construct();

        $this->load->config('language_process_db_config', TRUE);
        $this->tables  = $this->config->item('dbtable', 'language_process_db_config');
        $this->load->database();
        $this->load->library('session');
    }

    public function add_variable($variable_name, $variable_type, $variable_value, $project_id)
    {
        
        //variable list table
        $data = array(
            'variable_name'   => $variable_name,
            'variable_type'   => $variable_type,
            'variable_value'   => $variable_value
        );

        $this->db->insert($this->tables['VARIABLE_LIST_TABLE'], $data);

        $id = $this->db->insert_id();
        
        $project_variable_date = array(
            'variable_id'   => $id,
            'project_id'   => $project_id
        );
        $this->db->insert($this->tables['VARIABLES_PROJECTS'], $project_variable_date);
        
        return $id;
    }
    public function variable_name_exist($variable_name)
    {

        $this->db->select('variable_name');
        $this->db->where('variable_name', $variable_name);
        //$query = $this->db->get($this->tables['VARIABLE_LIST_TABLE']);
        $query = $this->db->select('*')->from($this->tables['VARIABLE_LIST_TABLE'])->join($this->tables['VARIABLES_PROJECTS'], 'variable_list.variable_id = variables_projects.variable_id')->get();
        if(sizeof($query->result())>0)
        {
            return TRUE;
        }
        return FALSE;
    }

    public function get_all_custom_variables()
    {
        $this->db->select('variable_id');
        $this->db->select('variable_name');
        $this->db->select('variable_type');
        $this->db->select('variable_value');
        $query = $this->db->get($this->tables['VARIABLE_LIST_TABLE']);
        if(sizeof($query->result())>0)
        {
            return $query->result();
        }
        return FALSE;
    }

}

?>
