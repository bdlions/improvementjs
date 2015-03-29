<div class="modal fade" id="modal_delete_block_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmation Dialog</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <label id="label_delete_block_confirmation_div_modal" name="label_delete_block_confirmation_div_modal">Are you sure you want to delete this?</label>  
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_delete" onclick="delete_ok_pressed()" value="Delete"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->