<div class="timeline_wrapper">
    <ul class="timeline">
    <?php if (isset($stats)) : ?>
        <li class="timeline-dot">
            <a href="/marks">All Links</a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/today">Today<span><?php print determinePlurality($stats['marks']['today'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/yesterday">Yesterday<span><?php print determinePlurality($stats['marks']['yesterday'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-week">Last Week<span><?php print determinePlurality($stats['marks']['last week'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-month">Last Month<span><?php print determinePlurality($stats['marks']['last month'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-three-months">Last 3 Months<span><?php print determinePlurality($stats['marks']['last 3 months'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-six-months">Last 6 Months<span><?php print determinePlurality($stats['marks']['last 6 months'], 'mark'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-year">Last Year<span><?php print determinePlurality($stats['marks']['last year'], 'mark'); ?></span></a>
        </li>
    <?php endif; ?>
    </ul>
</div>
