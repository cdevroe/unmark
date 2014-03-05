<h4 class="suggestion-heading">Here are a few interesting marks to get you started...</h4>
<?php

// Add any notification you want to appear in the sidebar here...

// The following is temporary and will be replaced in future releases of Unmark

	$random_featured_links = array();
		$random_featured_links[] = array('title'=>'Eat: Irish Soda Bread Recipe','url'=>'http://www.simplyrecipes.com/recipes/irish_soda_bread/');
		$random_featured_links[] = array('title'=>'Watch: MOVE on Vimeo','url'=>'http://vimeo.com/27246366');
		$random_featured_links[] = array('title'=>'Buy: The Chork: Chopsticks & Fork','url'=>'http://thechork.com/');
		$random_featured_links[] = array('title'=>'Watch: Pick a movie! IMDb Top 250','url'=>'http://www.imdb.com/chart/top?ref_=nv_ch_250_4');
		$random_featured_links[] = array('title'=>'Read: Bourtange on Wikipedia','url'=>'http://en.wikipedia.org/wiki/Bourtange');
		$random_featured_links[] = array('title'=>'Photo set: NASA\'s Real Gravity','url'=>'http://www.flickr.com/photos/gsfc/sets/72157641720644305/');
		$random_featured_links[] = array('title'=>'Video: Patton Oswald\'s Filibuster, animated','url'=>'http://www.youtube.com/watch?v=j8hlpimFhAY');
		$random_featured_links[] = array('title'=>'Read: Take a breath by Jason Santa Maria','url'=>'http://jasonsantamaria.com/articles/take-a-breath');
		$random_featured_links[] = array('title'=>'Listen: Hello Sky by Yaron Schoen','url'=>'https://soundcloud.com/yarcom/hello-sky-skull-tubes-remix');
		$random_featured_links[] = array('title'=>'Dribbble portfolio of Matt D. Smith','url'=>'http://dribbble.com/mds');
		$random_featured_links[] = array('title'=>'App: The Loop Magazine on the App Store','url'=>'https://itunes.apple.com/us/app/the-loop-magazine/id641340497?mt=8&ign-mpt=uo%3D4');
		$random_featured_links[] = array('title'=>'App: GroupBuzz: Hosted Community Discussions','url'=>'http://groupbuzz.io/');
		$random_featured_links[] = array('title'=>'Keiko Tanabe Fine Art','url'=>'http://ktanabefineart.com/');
		$random_featured_links[] = array('title'=>'Read: The Sweet Setup: iOS app recommendations','url'=>'http://thesweetsetup.com/');
		$random_featured_links[] = array('title'=>'Do: "This is a Unix system, you know this."','url'=>'http://www.jurassicsystems.com/');
		$random_featured_links[] = array('title'=>'Do: Galerie Restauracji','url'=>'http://www.gastronauci.pl/pl/restauracje/galeria');
		$random_featured_links[] = array('title'=>'Read: Tip: Protect iCloud Keychain from the NSA','url'=>'http://tidbits.com/article/14557');
		$random_featured_links[] = array('title'=>'Buy: HUVr: A real hoverboard?','url'=>'http://huvrtech.com/');
		$random_featured_links[] = array('title'=>'App: Slack, Be Less Busy','url'=>'https://slack.com/');
		$random_featured_links[] = array('title'=>'Eat: Breakfast and Brunch recipes','url'=>'http://allrecipes.com/recipes/breakfast-and-brunch/');
		$random_featured_links[] = array('title'=>'Read: BYO: Homebrewing Magazine','url'=>'http://byo.com/');
		$random_featured_links[] = array('title'=>'Buy: Shop at Ugmonk','url'=>'http://shop.ugmonk.com/');
		$random_featured_links[] = array('title'=>'Buy: Vintage Art on Etsy','url'=>'http://www.etsy.com/browse/vintage-category/art?h=5dd7f1c0&lid=159334542&ref=cat_subcat_title_2');
		$random_featured_links[] = array('title'=>'Buy: Projects Ending Soon on Kickstarter','url'=>'https://www.kickstarter.com/discover/ending-soon?ref=ending_soon');
		$random_featured_links[] = array('title'=>'Listen: Trending music on Soundcloud','url'=>'https://soundcloud.com/explore');
		$random_featured_links[] = array('title'=>'Read: Conan O\'Brien on LinkedIn','url'=>'http://www.linkedin.com/in/conanobrien');
		$random_featured_links[] = array('title'=>'Read: The Lost Star Wars movie on Esquire','url'=>'http://www.esquire.com/blogs/culture/star-wars-lost-movie-black-angel??src=rss');
		$random_featured_links[] = array('title'=>'Watch: DIY Engineer Who Built a Nuke','url'=>'http://digg.com/video/the-diy-engineer-who-built-a-nuclear-reactor-in-his-basement');
		$random_featured_links[] = array('title'=>'Drink: Harvey Wallbanger Recipe','url'=>'http://cocktails.about.com/od/atozcocktailrecipes/r/hrvy_wlbngr_ckt.htm');
		$random_featured_links[] = array('title'=>'Eat: Food For Hunters: Deer Stew','url'=>'http://foodforhunters.blogspot.com/2011/10/deer-stew.html');
		$random_featured_links[] = array('title'=>'Eat: Sriracha Deviled Eggs','url'=>'http://www.chow.com/recipes/26662-sriracha-deviled-eggs');
		$random_featured_links[] = array('title'=>'Eat: Korean Style Tacos w Kogi BBQ Sauce','url'=>'http://www.steamykitchen.com/4474-korean-style-tacos-with-kogi-bbq-sauce.html');
		$random_featured_links[] = array('title'=>'Eat: Potato Gratin with Brats, Kale & Gruyère','url'=>'http://www.eattwoplease.com/recipes/potato-gratin-with-brats-kale-gruyere/');
		$random_featured_links[] = array('title'=>'Listen: Discover Funk on Bandcamp','url'=>'http://bandcamp.com/?g=funk&s=top&f=all&w=-1#discover');
		$random_featured_links[] = array('title'=>'Eat: Braised Chicken in White Wine','url'=>'http://tastefoodblog.com/2012/01/01/braised-chicken-in-white-wine-recipe/');
		$random_featured_links[] = array('title'=>'Eat: Slow Cooker Cilantro Lime Chicken','url'=>'http://allrecipes.com/recipe/slow-cooker-cilantro-lime-chicken/');

		shuffle($random_featured_links);
		$number_of_links = count($random_featured_links);
		$number_of_links_to_show = 8;
		$link_counter = 0;

		echo '<div class="sidebar-info-block"><ul class="sidebar-links">';
		foreach($random_featured_links as $link) {
			if ($link_counter >= $number_of_links_to_show) break;
			echo '<li><a href="'.$link['url'].'" target="_blank">'.$link['title'].'</a></li>';
			$link_counter++;
		}
		echo '</ul></div>';
?>
