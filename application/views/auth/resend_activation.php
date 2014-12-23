<div class='box'>  
    <div class="form-group">
        <div class="col-md-12">
            Account Inactive. Did you activate your account? We sent you an email with an activation link. Please check your email. Thank you.
        </div>
    </div>
    <?php echo form_open('auth/send_email_activation/'.$id); ?>       
        <div class="form-group" style="padding-top:40px;">
            <div class="pull-left col-md-2">
                <?php echo form_input(array('type'=> 'submit','value'=>'Resend Activation email', 'class'=>'form-control btn-success'));?>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

