<div class="navigation-content">
    <div class="menu-activator menu-item"><a href="#show-menu"><i class="barley-icon-reorder"></i></a></div>
    <div class="menu-labels menu-item"><a href="#panel-label"><i class="barley-icon-bookmark"></i></a></div>
    <div class="menu-timeline menu-item"><a href="#panel-timeline"><i class="barley-icon-time"></i></a></div>
    <div class="menu-search menu-item"><a href="#panel-search"><i class="barley-icon-search"></i></a></div>
    <div class="menu-marks menu-item"><a href="/"><i class="barley-icon-link"></i></a></div>
    <div class="menu-settings menu-item"><a href="#panel-settings"><i class="barley-icon-cog"></i></a></div>
</div>

<div class="navigation-pane">
    <ul class="navigation-pane-links">
        <li class="panel-label"><a href="#panel-label">Labels</a></li>
        <li class="panel-timeline"><a href="#panel-timeline">Timeline</a></li>
        <li class="panel-search"><a href="#panel-search">Search</a></li>
        <li class="panel-marks"><a href="/">All Links</a></li>
        <li class="panel-settings"><a href="#panel-settings">Settings</a></li>
    </ul>
    <div class="navigation-panel-wrapper">
        <div id="panel-label" class="nav-panel">
            <ul class="label-list">
                <?php foreach ($labels as $label) : ?>
                    <li class="label-<?php print $label->label_id ?>">
                        <a href="/marks/label/<?php print $label->slug; ?>"><?php print $label->name; ?></a>
                        <span><?php print $label->total_marks; ?> links</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="panel-timeline" class="nav-panel">Timeline</div>
        <div id="panel-search" class="nav-panel">Search</div>
        <div id="panel-settings" class="nav-panel">Settings</div>
    </div>
</div>