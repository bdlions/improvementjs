function Sprite()
{
    var scripts;
    Sprite.prototype.setScripts = function(value)
    {
        this.scripts = value;
    }
    Sprite.prototype.getScripts = function()
    {
        return this.scripts;
    }
}

