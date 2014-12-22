function Variable()
{
    var variable_id;
    var variable_name;
    var variable_type;
    var variable_value;
    
    Variable.prototype.setVariableId = function(var_id)
    {
        this.variable_id = var_id;        
    }
    Variable.prototype.getVariableId = function()
    {
        return this.variable_id;        
    }
    
    Variable.prototype.setVariableName = function(var_name)
    {
        this.variable_name = var_name;        
    }
    Variable.prototype.getVariableName = function()
    {
        return this.variable_name;        
    }
    
    Variable.prototype.setVariableType = function(var_type)
    {
        this.variable_type = var_type;        
    }
    Variable.prototype.getVariableType = function()
    {
        return this.variable_type;        
    }
    
    Variable.prototype.setVariableValue = function(var_value)
    {
        this.variable_value = var_value;        
    }
    Variable.prototype.getVariableValue = function()
    {
        return this.variable_value;        
    }
}