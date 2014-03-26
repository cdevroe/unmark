<div id="helperforms">
    <div id="resetPasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo _('Change Password') ?></h1>
            <form id="send_password_change" method="post" action="#" class="ajaxsbmt">
                <input type="password" class="field-input" name="oldpass" id="oldpass" placeholder="<?php echo _('Old Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass1" id="pass1" placeholder="<?php echo _('New Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass2" id="pass2" placeholder="<?php echo _('Once More...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="changePasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo _('Change Email Address') ?></h1>
            <form id="send_email_change" method="post" action="#" class="ajaxsbmt">
                <input type="email" class="field-input" name="emailupdate" id="emailupdate" placeholder="<?php echo _('New Email Address...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="importExportForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo _('Export All Marks') ?></h1>
            <a data-action="export_data" class="exportbtn action" href="#"><?php echo _('Export File') ?></a>
            <h1><?php echo _('Import Marks') ?></h1>
            <form id="importForm" method="post" enctype="multipart/form-data" action="/import">
                <input class="importer" type="file" name="upload">
                <a data-action="import_data" class="importbtn action" href="#"><?php echo _('Import File') ?></a>
            </form>
            <small><?php echo _('Note: The import needs to be a JSON file.') ?></small>
            <div class="response-message"></div>
        </div>
    </div>
</div>