<style type="text/css">
    .form-group{
        margin-bottom: 5px;
    }
    label{
        min-width: 300px;
    }
    .btn{
        text-align: left;
    }
    .btn.active{
        background-color: green;
    }
</style>
<script type="text/javascript">
    $(function(){
        $("#bracketAdder div label").each(function(){
            $(this).on("click", function(){
                if($("#bracketAdder div label.active").size() > 1){
                    return false;
                }
            });
        });
    });
</script>

<div class="btn-group" data-toggle="buttons" id="bracketAdder">
    <div class="form-group">
        <label class="btn btn-primary">
            First option
        </label>
    </div>
    <div class="form-group">
        <label class="btn btn-primary">
            Second option
        </label>
    </div>    
    <div class="form-group">
        <label class="btn btn-primary">
            Third option
        </label>
    </div>
</div>