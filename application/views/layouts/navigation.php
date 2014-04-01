<div class="navigation-content">
    <?php $this->load->view('layouts/navigation/nav_icons'); ?>
</div>
<div class="navigation-pane">
    <ul class="navigation-pane-links">
        <?php $this->load->view('layouts/navigation/nav_helper'); ?>
        <li class="panel-settings"><a rel="350" href="#panel-settings"><?php _('Settings')?></a></li>
    </ul>
    <div class="navigation-panel-wrapper">
        <?php $this->load->view('layouts/navigation/nav_panels'); ?>
    </div>
</div>
