function Block()
{
    var block;
    var script;
    var s;
    var l;	
    Block.prototype.setBlock = function(value)
    {
        this.block = value;
    }
    Block.prototype.getBlock = function()
    {
        return this.block;
    }
    Block.prototype.setScript = function(value)
    {
        this.script = value;
    }
    Block.prototype.getScript = function()
    {
        return this.script;
    }
    Block.prototype.setS = function(value)
    {
        this.s = value;
    }
    Block.prototype.getS = function()
    {
        return this.s;
    }
    Block.prototype.setL = function(value)
    {
        this.l = value;
    }
    Block.prototype.getL = function()
    {
        return this.l;
    }	
}

