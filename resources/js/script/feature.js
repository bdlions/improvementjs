/* 
 * This class contains one feature information
 * 
 */
function Feature()
{
    var options;
    var options_type;
    var natural;
    var code;
    var help;
    var parameter_list;
    
    Feature.prototype.setOptions = function(opt)
    {
        this.options = opt;        
    }
    Feature.prototype.getOptions = function()
    {
        return this.options;        
    }
    Feature.prototype.setOptionsType = function(opt_type)
    {
        this.options_type = opt_type;        
    }
    Feature.prototype.getOptionsType = function()
    {
        return this.options_type;        
    }
    Feature.prototype.setNatural = function(nat)
    {
        this.natural = nat;        
    }
    Feature.prototype.getNatural = function()
    {
        return this.natural;        
    }
    Feature.prototype.setCode = function(cd)
    {
        this.code = cd;        
    }
    Feature.prototype.getCode = function()
    {
        return this.code;        
    }
    Feature.prototype.setHelp = function(hp)
    {
        this.help = hp;        
    }
    Feature.prototype.getHelp = function()
    {
        return this.help;        
    }
    Feature.prototype.setParameterList = function(pl)
    {
        this.parameter_list = pl;        
    }
    Feature.prototype.getParameterList = function()
    {
        return this.parameter_list;        
    }
}

