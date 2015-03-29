
<div class="modal fade" id="modal_change_logical_operator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Warning</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select Logical Operator:</label>
                            </div>
                            <div class="col-md-6">
                                <select name="logical_operator_change_combo" id="logical_operator_change_combo">
                                    <option value="AND">AND</option>
                                    <option value="OR">OR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_change_ok" onclick="change_logical_operator_ok_pressed()" value="Change"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



