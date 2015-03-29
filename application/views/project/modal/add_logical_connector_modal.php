<div class="modal fade" id="modal_add_logical_connector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Current Conditions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Insert</label>
                                <select name="logicalConnectorSelectionCombo" id="logicalConnectorSelectionCombo">
                                    <option value="AND" selected = "selected">AND</option>
                                    <option value="OR">OR</option>
                                </select>
                                user can adds a new condtion or a boolean variable 
                                <select name="logicalConnectorItemType" id="logicalConnectorItemType">
                                    <option value="Condition" selected = "selected">Condition</option>
                                    <option value="BooleanVariable">Boolean Variable</option>
                                </select>
                                <label>after</label>
                                <ol id="logical_connector_selected_item" style="font-size:8pt;">       
                                </ol>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-1"></div>
                </div>
            </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" id="button_add_logical_connector_ok" onclick="add_logical_connector_ok_pressed()" value="Save"/>  
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->