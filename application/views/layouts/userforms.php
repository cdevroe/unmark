<div id="helperforms">
    <div id="resetPasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo unmark_phrase('Change Password') ?></h1>
            <form id="send_password_change" method="post" action="#" class="ajaxsbmt">
                <input type="password" class="field-input" name="oldpass" id="oldpass" placeholder="<?php echo unmark_phrase('Old Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass1" id="pass1" placeholder="<?php echo unmark_phrase('New Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass2" id="pass2" placeholder="<?php echo unmark_phrase('Once More...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="changePasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo unmark_phrase('Change Email Address') ?></h1>
            <form id="send_email_change" method="post" action="#" class="ajaxsbmt">
                <input type="email" class="field-input" name="emailupdate" id="emailupdate" placeholder="<?php echo unmark_phrase('New Email Address...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
    <div id="importExportForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1><?php echo unmark_phrase('Export All Marks') ?></h1>
            <a data-action="export_data" class="exportbtn action" href="#"><?php echo unmark_phrase('Export File') ?></a>
            <h1><?php echo unmark_phrase('Import Marks') ?></h1>
            <form id="importForm" method="post" enctype="multipart/form-data" action="/import">
                <input id="importerUnmark" class="importer" type="file" name="upload">
                <a data-action="import_data" class="importbtn action" href="#"><?php echo unmark_phrase('Import Unmark File') ?></a>
            </form>
            <form id="importFormReadability" method="post" enctype="multipart/form-data" action="/import">
                <input id="importerReadability" class="importer" type="file" name="uploadReadability">
                <a data-action="import_data_readability" class="importbtn action" href="#"><?php echo unmark_phrase('Import Readability File') ?></a>
            </form>
            <form id="importFormHTML" method="post" enctype="multipart/form-data" action="/import">
                <input id="importerHTML" class="importer" type="file" name="uploadHTML">
                <a data-action="import_data_html" class="importbtn action" href="#"><?php echo _('Import HTML File') ?></a>
            </form>
            <small><?php echo unmark_phrase('Note: HTML import supports Delicious, Pinboard, and others.'); ?></small>
            <div class="response-message"></div>
        </div>
    </div>
</div>
