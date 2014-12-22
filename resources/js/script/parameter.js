/* 
 * This class contains parameter information of a feature
 * 
 */

function Parameter()
{
    //name of parameter in xml file
    var name;
    //type of parameter in xml file
    var type;
    //default value of parameter in xml file
    var default_value;
    //allowed values of a parameter in xml file
    var allowed_values;
    //interval of a parameter in xml file
    var interval;
    //variables to be allowed or not for this parameter
    var allow_var;
    Parameter.prototype.setName = function(nm)
    {
        this.name = nm;        
    }
    Parameter.prototype.getName = function()
    {
        return this.name;        
    }
    Parameter.prototype.setType = function(tp)
    {
        this.type = tp;        
    }
    Parameter.prototype.getType = function()
    {
        return this.type;        
    }  
    Parameter.prototype.setDefaultValue = function(dv)
    {
        this.default_value = dv;        
    }
    Parameter.prototype.getDefaultValue = function()
    {
        return this.default_value;        
    }
    Parameter.prototype.setAllowedValues = function(av)
    {
        this.allowed_values = av;        
    }
    Parameter.prototype.getAllowedValues = function()
    {
        return this.allowed_values;        
    }
    Parameter.prototype.setInterval = function(ival)
    {
        this.interval = ival;        
    }
    Parameter.prototype.getInterval = function()
    {
        return this.interval;        
    }
    Parameter.prototype.setAllowVar = function(avar)
    {
        this.allow_var = avar;        
    }
    Parameter.prototype.getAllowVar = function()
    {
        return this.allow_var;        
    }
}

