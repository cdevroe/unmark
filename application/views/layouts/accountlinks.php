<h4 class="nav-heading"><?php echo sprintf(_('Account %s'), '<span id="user-email">[ ' . ((isset($user)) ? $user['email'] : '').' ]</span>'); ?></h4>
<ul class="nav-list">
    <li><a href="#" class="action" data-action="change_password"><?php echo _('Change Password'); ?></a></li>
    <li><a href="#" class="action" data-action="change_email"><?php echo _('Change Email Address'); ?></a></li>
    <li><a href="#" class="action" data-action="import_export"><?php echo _('Import or Export Marks'); ?></a></li>
    <li><a href="mailto:?subject=Checkout Unmark&amp;body=You should really check out Unmark. http://unmark.it"><?php echo _('Invite Others'); ?></a></li>
</ul>
