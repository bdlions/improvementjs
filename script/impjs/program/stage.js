function Stage()
{
    var sprites;
    Stage.prototype.setSprites = function(value)
    {
        this.sprites = value;
    }
    Stage.prototype.getSprites = function()
    {
        return this.sprites;
    }
}

