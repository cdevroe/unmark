<div class="timeline_wrapper">
    <ul class="timeline">
        <li class="timeline-dot">
            <a href="/marks">All Links</a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/today">Today<span><?php print determinePlurality($stats['marks']['today'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/yesterday">Yesterday<span><?php print determinePlurality($stats['marks']['yesterday'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-week">Last Week<span><?php print determinePlurality($stats['marks']['last week'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-month">Last Month<span><?php print determinePlurality($stats['marks']['last month'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-three-months">Last 3 Months<span><?php print determinePlurality($stats['marks']['last 3 months'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-six-months">Last 6 Months<span><?php print determinePlurality($stats['marks']['last 6 months'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot">
            <a href="/marks/last-year">Last Year<span><?php print determinePlurality($stats['marks']['last year'], 'link'); ?></span></a>
        </li>
        <li class="timeline-dot"></li>
    </ul>
</div>