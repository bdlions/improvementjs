<style type="text/css">
    .bracket-condition{
        margin-bottom: 5px;
    }
    .bracket-condition label{
        min-width: 300px;
    }
    .bracket-condition .btn{
        text-align: left;
    }
    .bracket-condition .btn.active{
        background-color: green;
    }
    .bracket-condition label a, .bracket-condition label a:hover{
        color: #ffffff;
        text-decoration: none;
    }
</style>

<div class="modal fade" id="modal_bracket_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Bracket (Click the two positions for the new bracket)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="btn-group" data-toggle="buttons"  id="div_add_bracket_in_condition">

                            </div>
                        </div> 
                    </div>
                </div>
                               
            </div>
            <div class="modal-footer">
                <div class ="col-md-offset-8 col-md-2 col-xs-offset-4 col-xs-4">
                    <button id="button_add_bracket" name="button_add_bracket" type="button" class="btn btn-success">Add</button>
                </div>
                <div class ="col-md-2 col-xs-4">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(function(){
       $("#button_add_bracket").click(function(){
           button_add_bracket_in_condition_ok_pressed();
       });
    });
    function bracketValidation(){
        $("#div_add_bracket_in_condition div label").each(function(){
            $(this).on("click", function(){
                if($(this).is(".active")){
                    //allowing to uncheck selected row
                    return true;
                }
                else if($("#div_add_bracket_in_condition div label.active").size() > 1){
                    return false;
                }
            });
        });
    }
</script>
