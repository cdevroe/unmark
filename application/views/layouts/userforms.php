<div id="helperforms">
    <div id="resetPasswordForm" class="resetWrapper hiddenform">
        <div class="loginInner">
            <h1>Change Password</h1>
            <form id="passwordUpdate" method="post" action="#">
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
            <form id="emailUpdate" method="post" action="#">
                <input type="email" class="field-input" name="emailupdate" id="emailupdate" placeholder="New Email Address..." autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
        </div>
    </div>
</div>