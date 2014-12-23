<div class="page-header">
    <h1>Projects</h1>
</div>
<?php if (isset($message) && ($message != NULL)){ ?>
    <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <table class="table-responsive table">
            <caption>Below is a list of projects</caption>
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Type</th>
                    <th>Open</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project){ ?>
                    <tr>
                        <td><?php echo $project->project_name; ?></td>
                        <td><?php echo $project->description; ?></td>
                        <td><?php
                            if ($project->project_type_id == PROJECT_TYPE_ID_PROGRAM) {
                                echo anchor("programs/load_program/" . $project->project_id, 'Open');
                            } else if ($project->description == "Script") {
                                echo anchor("scripts/load_script/" . $project->project_id, 'Open');
                            }
                            ?>
                        </td>
                        <td style="text-align:left">
                            <a onclick="modal_delete_project_confirmation('<?php echo $project->project_id; ?>')" >
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->load->view('project/modal_delete_project_confirmation');?>