<div class="navigation-content">
    <div class="menu-activator menu-item"><a rel="200" href="#panel-menu"><i class="icon-menu_open"></i></a></div>
    <div class="menu-marks menu-item"><a href="/marks"><i class="icon-links"></i></a></div>
    <div data-menu="panel-label" class="menu-labels menu-item"><a rel="200" href="#panel-label"><i class="icon-label"></i></a></div>
    <div data-menu="panel-timeline" class="menu-timeline menu-item"><a rel="250" href="#panel-timeline"><i class="icon-time"></i></a></div>
    <div data-menu="panel-search" class="menu-search menu-item"><a rel="350" href="#panel-search"><i class="icon-search"></i></a></div>
    <div data-menu="panel-archives" class="menu-search menu-item"><a href="/marks/archive"><i class="icon-archive"></i></a></div>
    <div data-menu="panel-settings" class="menu-settings menu-item"><a rel="350" href="#panel-settings"><i class="icon-settings"></i></a></div>
</div>

<div class="navigation-pane">
    <ul class="navigation-pane-links">
        <li class="panel-marks"><a href="/marks">All Links</a></li>
        <li class="panel-label"><a rel="200" href="#panel-label">Labels</a></li>
        <li class="panel-timeline"><a rel="250" href="#panel-timeline">Timeline</a></li>
        <li class="panel-search"><a rel="350" href="#panel-search">Search</a></li>
        <li class="panel-search"><a href="/marks/archive">Archives</a></li>
        <li class="panel-settings"><a rel="350" href="#panel-settings">Settings</a></li>
    </ul>
    <div class="navigation-panel-wrapper">
        <div id="panel-label" class="nav-panel">
            <ul class="label-list">
                <?php 
                
                // If the first label is "Unlabeled", move to end of array
                if ( $labels[0]->name == 'Unlabeled' ) {
                    $labels[] = $labels[0];
                    array_shift($labels);
                }

                foreach ($labels as $label) : ?>
                    <li class="label-<?php print $label->label_id ?>">
                        <a href="/marks/label/<?php print $label->slug; ?>"><?php print $label->name; ?></a>
                        <span><?php print determinePlurality($label->total_active_marks, 'link'); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="panel-timeline" class="nav-panel">
            <?php $this->load->view('layouts/timeline'); ?>
        </div>
        <div id="panel-search" class="nav-panel">
            <form method="get" action="/marks/search" id="search-form">
                <input type="text" name="q" id="search-input" placeholder="SEARCH...">
                <i class="icon-go"></i>
            </form>
            
            <h4 class="nav-heading">Most-Used Tags</h4>
            <ul class="tag-list">
                <?php if (isset($tags) && count($tags['popular'] > 0)) : ?>
                    <?php foreach ($tags['popular'] as $pop_tag) : ?>
                        <li><a href="/marks/tag/<?php print $pop_tag->slug; ?>">#<?php print $pop_tag->name; ?></a></li>
                    <?php endforeach; ?>
                <?php else : ?>
                <li>No Tags Found</li>
                <?php endif; ?>
            </ul>
        </div>
        <div id="panel-settings" class="nav-panel">
            <button id="logout-btn" class="danger" data-action="logout">Sign Out</button>
            <h4 class="nav-heading">Settings</h4>
            <ul class="nav-list">
                <li><a href="#" class="action" data-action="awesome">Turn On Awesome</a></li>
            </ul>

            <h4 class="nav-heading">Account <span>[ <?php print $user['email']; ?> ]</span></h4>
            <ul class="nav-list">
                <li><a href="#" class="action" data-action="change_password">Change Password</a></li>
                <li><a href="#" class="action" data-action="change_email">Change Email Address</a></li>
                <li><a href="mailto:?subject=Checkout Nilai&amp;body=You should really check out Nilai. http://nilai.co">Invite Others</a></li>
            </ul>

            <h4 class="nav-heading">Help</h4>
            <ul class="nav-list">
                <li><a href="/help/how.php">How to Use Nilai</a></li>
                <li><a href="/help/faq">FAQ</a></li>
                <li><a href="/help/bookmarklet">Get the Bookmarklet</a></li>
                <li><a href="#">Get the Chrome Extension</a></li>
            </ul>
        </div>
    </div>
</div>