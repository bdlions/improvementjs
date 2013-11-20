<?php
/**
 * Description of projectxmlparser
 *
 * @author Nazmul
 */
class projectxmlparser
{
    protected $ci;
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('session'); 
        $this->ci->load->library('project/parser/project_object'); 
        $this->ci->load->library('project/parser/variable'); 
        $this->ci->load->library('project/parser/properties');
    }
    public function readXML()
    {   
        $xml_path = 'project/'.$this->ci->session->userdata('project_id').'.xml';
        $doc = new DOMDocument();    
        $doc->load($xml_path);

        $projects = $doc->getElementsByTagName("project");

        foreach ($projects as $project)
        {
            $pObject = new Project_object();
            //$pObject = &get_instance()->project;

            $options = $project->getElementsByTagName("code");
            $options = $options->item(0)->nodeValue;
            $pObject->code = $options;
            
            $variables = $project->getElementsByTagName("variable");

            $pObject->variables = array();
            foreach ($variables as $variable)
            {
                $variableObject = new Variable();
                $variableObject = &get_instance()->variable;
                
                $ids = $variable->getElementsByTagName("id");
                $id = $ids->item(0)->nodeValue;
                $variableObject->id = $id;
                
                $names = $variable->getElementsByTagName("name");
                $name = $names->item(0)->nodeValue;
                $variableObject->name = $name;
                
                $types = $variable->getElementsByTagName("type");
                $type = $types->item(0)->nodeValue;
                $variableObject->type = $type;
                
                $values = $variable->getElementsByTagName("value");
                $value = $values->item(0)->nodeValue;
                $variableObject->value = $value;
                
                array_push($pObject->variables, $variableObject);               
            }
            $project_properties = $project->getElementsByTagName("properties");
            foreach ($project_properties as $project_property)
            {
                $project_type_id = $project_property->getElementsByTagName("project_type_id");
                $project_type_id = $project_type_id->item(0)->nodeValue;
                $properties = new Properties();
                $properties->project_type_id = $project_type_id;
                $pObject->properties = $properties;
            }
        }
        return $pObject;
    }
}
?>
