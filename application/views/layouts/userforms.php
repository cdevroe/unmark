<div id="helperforms">
    <figure id="resetPasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <header>
              <h1><?php echo unmark_phrase('Change Password') ?></h1>
            </header>
            <form id="send_password_change" method="post" action="#" class="ajaxsbmt">
                <input type="password" class="field-input" name="oldpass" id="oldpass" placeholder="<?php echo unmark_phrase('Old Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass1" id="pass1" placeholder="<?php echo unmark_phrase('New Password...') ?>" autocomplete="off" />
                <input type="password" class="field-input" name="pass2" id="pass2" placeholder="<?php echo unmark_phrase('New Password Again...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit">Update</button>
            </form>
            <div class="response-message"></div>
        </div>
        <a href="#" id="unmarkModalClose"><i class="icon-big_close"></i></a>
    </figure>
    <figure id="changePasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <header>
              <h1><?php echo unmark_phrase('Change Email Address') ?></h1>
            </header>
            <form id="send_email_change" method="post" action="#" class="ajaxsbmt">
                <input type="email" class="field-input" name="emailupdate" id="emailupdate" placeholder="<?php echo unmark_phrase('New Email Address...') ?>" autocomplete="off" />
                <button class="login-submit" type="submit">Update</button>
            </form>
            <div class="response-message"></div>
        </div>
        <a href="#" id="unmarkModalClose"><i class="icon-big_close"></i></a>
    </figure>
    <figure id="importExportForm" class="resetWrapper hiddenform">
        <div class="loginInner">
          <div class="export">
            <header>
              <h1><?php echo unmark_phrase('Export All Marks') ?></h1>
            </header>
            <a data-action="export_data" class="exportbtn action" href="#"><?php echo unmark_phrase('Export File') ?></a>
          </div>
          <div class="import">
            <header>
              <h1><?php echo unmark_phrase('Import Marks') ?></h1>
            </header>
              <div class="from-unmark">
                <form id="importForm" method="post" enctype="multipart/form-data" action="/import">
                    <input id="importerUnmark" class="importer" type="file" name="upload">
                    <a data-action="import_data" class="importbtn action" href="#"><?php echo unmark_phrase('Import Unmark File') ?></a>
                </form>
              </div>
              <div class="from-other">
                <form id="importFormReadability" method="post" enctype="multipart/form-data" action="/import">
                    <input id="importerReadability" class="importer" type="file" name="uploadReadability">
                    <a data-action="import_data_readability" class="importbtn action" href="#"><?php echo unmark_phrase('Import Readability File') ?></a>
                </form>
                <form id="importFormHTML" method="post" enctype="multipart/form-data" action="/import">
                    <input id="importerHTML" class="importer" type="file" name="uploadHTML">
                    <a data-action="import_data_html" class="importbtn action" href="#"><?php echo unmark_phrase('Import HTML File') ?></a>
                </form>
                <p class="note"><em><?php echo unmark_phrase('Note: HTML import supports Pocket, Delicious, Pinboard, and others.'); ?></em></p>
              </div>
          </div>
          <div class="response-message"></div>
        </div>
        <a href="#" id="unmarkModalClose"><i class="icon-big_close"></i></a>
    </figure>
</div>
