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
    }
    public function readXML()
    {   
        $xml_path = 'project/'.$this->ci->session->userdata('project_id').'.xml';
        $doc = new DOMDocument();    
        $doc->load($xml_path);

        $pObject = null;            
        $projects = $doc->getElementsByTagName("project");

        foreach ($projects as $project)
        {
            $pObject = &get_instance()->project;

            $options = $project->getElementsByTagName("code");
            $options = $options->item(0)->nodeValue;
            $pObject->code = $options;

            $variables = $project->getElementsByTagName("variable");

            $pObject->variables = array();
            foreach ($variables as $variable)
            {
                $variableObject = null;
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
        }
        return $pObject;
    }
}
?>
