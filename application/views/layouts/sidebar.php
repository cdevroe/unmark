<div class="sidebar-default">
    <div class="sidebar-block" id="sidebar-graph">
        <div class="sidebar-inner">
            <?php if (isset($stats)) : ?>
            <canvas id="unmark-graph" class="graph" width="300" height="150"></canvas>
            <p>
                You saved <span class="ns-today"><?php print $stats['saved']['today']; ?></span>
                links today and archived <span class="na-today"><?php print $stats['archived']['today']; ?></span>.
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($stats) && $stats['marks']['ages ago'] > 0) : ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p>You have <span class="ns-year"><?php print $stats['marks']['ages ago']; ?></span> links that are over 1 year old. Want to tidy up a bit?</p>
            <a href="/marks/ages-ago" class="btn">View Links</a>
            <button data-action="dismiss_this">Do Nothing</button>
            <button data-action="archive_all">Archive All</button>
        </div>
    </div>
    <?php endif; ?>
    <?php if (isset($total) && $total < 3) : ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <a href="javascript:(function()%7Bf%3D%27<?php print getFullUrl(); ?>/mark/add%3Furl%3D%27%2BencodeURIComponent(window.location.href)%2B%27%26title%3D%27%2BencodeURIComponent(document.title)%2B%27%26v%3D6%26%27%3Ba%3Dfunction()%7Bif(!window.open(f%2B%27noui%3D1%26jump%3Ddoclose%27,%27nilaiv2%27,%27location%3D1,links%3D0,scrollbars%3D0,toolbar%3D0,width%3D594,height%3D485%27))location.href%3Df%2B%27jump%3Dyes%27%7D%3Bif(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()" class="btn">Unmark+</a>
            <p class="clear sidenote">Drag this button into your bookmark toolbar.  <a href="http://help.unmark.it/bookmarklet" target="_blank">Learn how</a>.</p>
        </div>
    </div>
    <?php endif; ?>
    <?php $this->load->view('layouts/sidebar/sidebar_notices'); ?>

    <!-- The following is temporary and will be replaced in future releases of Unmark -->
    <?php 
        $random_featured_links = array();
            $random_featured_links[] = array('title'=>'Irish Soda Bread Recipe','url'=>'http://www.simplyrecipes.com/recipes/irish_soda_bread/');
            $random_featured_links[] = array('title'=>'MOVE on Vimeo','url'=>'http://vimeo.com/27246366');
            $random_featured_links[] = array('title'=>'The Chork: Chopsticks & Fork','url'=>'http://thechork.com/');
            $random_featured_links[] = array('title'=>'Pick a movie! IMDb Top 250','url'=>'http://www.imdb.com/chart/top?ref_=nv_ch_250_4');
            $random_featured_links[] = array('title'=>'Bourtange on Wikipedia','url'=>'http://en.wikipedia.org/wiki/Bourtange');
            $random_featured_links[] = array('title'=>'Photo set: NASA\'s Real Gravity','url'=>'http://www.flickr.com/photos/gsfc/sets/72157641720644305/');
            $random_featured_links[] = array('title'=>'Video: Patton Oswald\'s Filibuster, animated','url'=>'http://www.youtube.com/watch?v=j8hlpimFhAY');
            $random_featured_links[] = array('title'=>'Take a breath by Jason Santa Maria','url'=>'http://jasonsantamaria.com/articles/take-a-breath');
            $random_featured_links[] = array('title'=>'Listen: Hello Sky by Yaron Schoen','url'=>'https://soundcloud.com/yarcom/hello-sky-skull-tubes-remix');
            $random_featured_links[] = array('title'=>'Dribbble portfolio of Matt D. Smith','url'=>'http://dribbble.com/mds');
            $random_featured_links[] = array('title'=>'App: The Loop Magazine on the App Store','url'=>'https://itunes.apple.com/us/app/the-loop-magazine/id641340497?mt=8&ign-mpt=uo%3D4');
            $random_featured_links[] = array('title'=>'GroupBuzz: Hosted Community Discussions','url'=>'http://groupbuzz.io/');
            $random_featured_links[] = array('title'=>'Keiko Tanabe Fine Art','url'=>'http://ktanabefineart.com/');
            $random_featured_links[] = array('title'=>'The Sweet Setup: iOS app recommendations','url'=>'http://thesweetsetup.com/');
            $random_featured_links[] = array('title'=>'"This is a Unix system, you know this."','url'=>'http://www.jurassicsystems.com/');
            $random_featured_links[] = array('title'=>'Galerie Restauracji','url'=>'http://www.gastronauci.pl/pl/restauracje/galeria');
            $random_featured_links[] = array('title'=>'Tip: Protect iCloud Keychain from the NSA','url'=>'http://tidbits.com/article/14557');
            $random_featured_links[] = array('title'=>'HUVr: A real hoverboard?','url'=>'http://huvrtech.com/');
            $random_featured_links[] = array('title'=>'App: Slack, Be Less Busy','url'=>'https://slack.com/');
            $random_featured_links[] = array('title'=>'Breakfast and Brunch recipes','url'=>'http://allrecipes.com/recipes/breakfast-and-brunch/');
            $random_featured_links[] = array('title'=>'BYO: Homebrewing Magazine','url'=>'http://byo.com/');
            $random_featured_links[] = array('title'=>'Shop at Ugmonk','url'=>'http://shop.ugmonk.com/');
            $random_featured_links[] = array('title'=>'Vintage Art on Etsy','url'=>'http://www.etsy.com/browse/vintage-category/art?h=5dd7f1c0&lid=159334542&ref=cat_subcat_title_2');
            $random_featured_links[] = array('title'=>'Projects Ending Soon on Kickstarter','url'=>'https://www.kickstarter.com/discover/ending-soon?ref=ending_soon');
            $random_featured_links[] = array('title'=>'Listen: Trending music on Soundcloud','url'=>'https://soundcloud.com/explore');
            $random_featured_links[] = array('title'=>'Conan O\'Brien on LinkedIn','url'=>'http://www.linkedin.com/in/conanobrien');
            $random_featured_links[] = array('title'=>'The Lost Star Wars movie on Esquire','url'=>'http://www.esquire.com/blogs/culture/star-wars-lost-movie-black-angel??src=rss');
            $random_featured_links[] = array('title'=>'Video: DIY Engineer Who Built a Nuke','url'=>'http://digg.com/video/the-diy-engineer-who-built-a-nuclear-reactor-in-his-basement');
            $random_featured_links[] = array('title'=>'Harvey Wallbanger Recipe','url'=>'http://cocktails.about.com/od/atozcocktailrecipes/r/hrvy_wlbngr_ckt.htm');

            shuffle($random_featured_links);
            $number_of_links = count($random_featured_links);
            $number_of_links_to_show = 8;
            $link_counter = 0;

            echo '<ul>';
            foreach($random_featured_links as $link) {
                if ($link_counter >= $number_of_links_to_show) break;
                echo '<li><a style="color: black;" href="'.$link['url'].'">'.$link['title'].'</a></li>';
                $link_counter++;
            }
            echo '</ul>';
    ?>


</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>
