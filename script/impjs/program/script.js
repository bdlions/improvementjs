function Script()
{
    var block;
    Script.prototype.setBlock = function(value)
    {
        this.block = value;
    }
    Script.prototype.getBlock = function()
    {
        return this.block;
    }
}
