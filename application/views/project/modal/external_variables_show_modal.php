<div class="modal fade" id="modal_external_variables_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">External variables</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div>
                            <?php
                            foreach ($external_variable_list as $external_variable) {
                                ?>
                                <div class="row form-group">
                                    <div class="col-md-10">
                                        <?php echo $external_variable; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?> 
                        </div>
                        <?php
                        $variable_values_counter = 0;
                        $variable_values = "";
                        foreach ($external_variable_values as $external_variable_value) {
                            if ($variable_values_counter == 0) {
                                $variable_values = $variable_values . $external_variable_value;
                            } else {
                                $variable_values = $variable_values . " , " . $external_variable_value;
                            }
                            $variable_values_counter++;
                        }
                        ?> 
                        <div>
                            <?php echo $variable_values; ?>
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_delete" onclick="external_variables_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->