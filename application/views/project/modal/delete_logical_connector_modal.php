<div class="modal fade" id="modal_delete_logical_conector_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Select Condition to Delete</h4>
            </div>
            <div class="modal-body">
                <ol id="logical_connector_removing_condition_selected_item" style="font-size:12pt;">       
                </ol>

                <input type = "hidden" value="" name="logical_connector_removing_condition_selected_operator_anchor_id" id="logical_connector_removing_condition_selected_operator_anchor_id" />
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="button_logical_connector_removing_condition_delete" onclick="button_logical_connector_removing_condition_delete_pressed()" type="button">Delete</button>
                <button class="btn btn-default" id="button_logical_connector_removing_condition_cancel" onclick="button_logical_connector_removing_condition_cancel_pressed()" type="button">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->