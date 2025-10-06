<h4 class="nav-heading">
  <?php echo sprintf('<span id="user-email">' . ((isset($user)) ? $user['email'] : '') . '</span>'); ?>
  <span class="account-type"><?php echo unmark_phrase('Local Account'); ?></span>
</h4>
<ul class="nav-list">
    <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XSYNN4MGM826N"><?php echo unmark_phrase('Support Unmark')?></a></li>
    <li><a href="#" class="action" data-action="change_password"><?php echo unmark_phrase('Change Password'); ?></a></li>
    <li><a href="#" class="action" data-action="change_email"><?php echo unmark_phrase('Change Email Address'); ?></a></li>
    <li><a href="#" class="action" data-action="delete_user"><?php echo unmark_phrase('Delete Account'); ?></a></li>
    <li><a href="#" class="action" data-action="import_export"><?php echo unmark_phrase('Import or Export Marks'); ?></a></li>
    <li><a href="mailto:?subject=Checkout Unmark&amp;body=You should really check out Unmark. http://unmark.it"><?php echo unmark_phrase('Invite Others'); ?></a></li>
</ul>
