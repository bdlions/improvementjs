function Project()
{
    var stage;
    Project.prototype.setStage = function(value)
    {
        this.stage = value;
    }
    Project.prototype.getStage = function()
    {
        return this.stage;
    }
}

