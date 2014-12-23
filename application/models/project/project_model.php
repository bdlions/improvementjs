<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Project Model
 *
 * Requirements: PHP5 or above
 *
 */
class Project_model extends Ion_Auth_Model {
    public function __construct() {
        parent::__construct();        
    }
    /*
     * This method will create a project
     * @param $additional_data, project data to be inserted
     */
    public function create_project($additional_data)
    {
        $data = $this->_filter_data($this->tables['project_info'], $additional_data);
        $this->db->insert($this->tables['project_info'], $data);
        $id = $this->db->insert_id();
        if ( isset($id) ) {
            if(isset($data['project_type_id']) && $data['project_type_id'] == PROJECT_TYPE_ID_PROGRAM)
            {
                $this->set_message('program_creation_successful');
            }
            if(isset($data['project_type_id']) && $data['project_type_id'] == PROJECT_TYPE_ID_SCRIPT)
            {
                $this->set_message('script_creation_successful');
            }
        } 
        else
        {            
            if(isset($data['project_type_id']) && $data['project_type_id'] == PROJECT_TYPE_ID_PROGRAM)
            {
                $this->set_error('program_creation_unsuccessful');
            }
            if(isset($data['project_type_id']) && $data['project_type_id'] == PROJECT_TYPE_ID_SCRIPT)
            {
                $this->set_error('script_creation_unsuccessful');
            }            
        }        
        return (isset($id)) ? $id : FALSE;
    }
    /*
     * This method will return all projects of a user
     * @param $user_id, user id
     */
    public function get_all_projects($user_id)
    {
        $this->db->where($this->tables['project_info'].'.user_id', $user_id);
        return $this->db->select('*')
                ->from($this->tables['project_info'])
                ->join($this->tables['project_types'], $this->tables['project_info'].'.project_type_id='.$this->tables['project_types'].'.id')
                ->get();
    }
    
    /*
     * This method will return all programs of a user
     * @param $user_id, user id
     */
    public function get_all_programs($user_id)
    {
        $this->db->where($this->tables['project_info'].'.user_id', $user_id);
        $this->db->where($this->tables['project_info'].'.project_type_id', PROJECT_TYPE_ID_PROGRAM);
        return $this->db->select('*')
                ->from($this->tables['project_info'])
                ->join($this->tables['project_types'], $this->tables['project_info'].'.project_type_id='.$this->tables['project_types'].'.id')
                ->get();
    }
    
    /*
     * This method will return all scripts of a user
     * @param $user_id, user id
     */
    public function get_all_scripts($user_id)
    {
        $this->db->where($this->tables['project_info'].'.user_id', $user_id);
        $this->db->where($this->tables['project_info'].'.project_type_id', PROJECT_TYPE_ID_SCRIPT);
        return $this->db->select('*')
                ->from($this->tables['project_info'])
                ->join($this->tables['project_types'], $this->tables['project_info'].'.project_type_id='.$this->tables['project_types'].'.id')
                ->get();
    }
    
    /*
     * This method will return project info
     * @param $project_id, project id
     */
    public function get_project_info($project_id)
    {
        $this->db->where($this->tables['project_info'].'.project_id', $project_id);
        return $this->db->select('*')
                ->from($this->tables['project_info'])
                ->join($this->tables['project_types'], $this->tables['project_info'].'.project_type_id='.$this->tables['project_types'].'.id')
                ->get();
    }
    
    /*
     * This method will delete a project
     * @param $project_id, project id
     */
    public function delete_project($project_id)
    {
        if(!isset($project_id) || $project_id <= 0)
        {
            return FALSE;
        }
        $this->db->where('project_id', $project_id);
        $this->db->delete($this->tables['project_info']);
        
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function is_project_exists($name)
    {
        if (empty($name))
        {
            return FALSE;
        }
        $query = $this->db->select('*')
                                ->where('project_name', $name)
                                ->limit(1)
                                ->get($this->tables['project_info']);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }
        else
        {
            $project_info = $query->row();
            if( $project_info->project_type_id === $this->project_types_list['program_id'] )
            {
                $this->set_error('program_name_exist');
            }
            else if( $project_info->project_type_id === $this->project_types_list['script_id'] )
            {
                $this->set_error('script_name_exist');
            }
        }
        return TRUE;
    }
}
