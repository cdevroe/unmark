<div id="helperforms">
    <div id="resetPasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1>Change Password</h1>
            <form id="send_password_change" method="post" action="#" class="ajaxsbmt">
                <input type="password" class="field-input" name="oldpass" id="oldpass" placeholder="Old Password..." autocomplete="off" />
                <input type="password" class="field-input" name="pass1" id="pass1" placeholder="New Password..." autocomplete="off" />
                <input type="password" class="field-input" name="pass2" id="pass2" placeholder="Once More..." autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="changePasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1>Change Email Address</h1>
            <form id="send_email_change" method="post" action="#" class="ajaxsbmt">
                <input type="email" class="field-input" name="emailupdate" id="emailupdate" placeholder="New Email Address..." autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="importExportForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1>Export All Marks</h1>
            <a data-action="export_data" class="exportbtn action" href="#">Export File</a>
            <h1>Import Marks</h1>
            <form id="importForm" method="post" enctype="multipart/form-data" action="/import">
                <input class="importer" type="file" name="upload">
                <a data-action="import_data" class="importbtn action" href="#">Import File</a>
            </form>
            <small>Note: The import needs to be a JSON file.</small>
            <div class="response-message"></div>
        </div>
    </div>
</div>