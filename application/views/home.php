<?php $this->load->view('header'); ?>

<div class="row-fluid">
    <div class="hero-unit span5">
    <h1>Save Your Bookmarks.</h1>
    <a href="/sirius" title="Sign up" class="btn btn-large btn-primary">Create An Account</a>
    </div>
    <div class="span5">
      <div id="myCarousel" class="carousel">
        <!-- Carousel items -->
        <div class="carousel-inner">
          <div class="active item">
            <img src="<?=site_url();?>assets/images/imac.jpeg" alt="imac" />
            <div class="carousel-caption">
              <h4>You're bombarded with links all day</h4>
              <p>From coworkers, friends, family via Twitter, IM, or email.</p>
            </div>
          </div>
          <div class="item">
          <img src="<?=site_url();?>assets/images/imac2.jpeg" alt="imac" />
            <div class="carousel-caption">
              <h4>Use Nilai to save them for later</h4>
              <p>Nilai helps you do something with all of those links.</p>
            </div>
          </div>
          <div class="item">
          <img src="<?=site_url();?>assets/images/iphone.jpeg" alt="iPhone" />
            <div class="carousel-caption">
              <h4>Catch up at your computer or on the go</h4>
              <p>Nilai works on your phone and tablet computer!</p>
            </div>
          </div>
        </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
      </div>
    </div>
</div>

<div class="row-fluid">
  <div class="span4">
    <!--<p><strong>Just $1/month.</strong><br />
		<small>Or $10/year if you'd prefer.</small></p> -->
		<p>Nilai isn't just for reading. Nilai makes it easy to catch up on all of the links you come across throughout the day. Read articles, watch videos, share recipes, or listen to podcasts.</p>
		
  </div>
  
  <div class="span3">
  <h4>News</h4>
    <p><small>30 April 2012: <a href="http://cdevroe.com/notes/nilai-groups/" target="_blank">Introducing Groups</a></small><br /><small>30 March 2012: <a href="http://cdevroe.com/notes/nilai-previews/" target="_blank">Introducing Previews</a></small><br /><small>20 March 2012: <a href="http://cdevroe.com/notes/nilai-smartlabels/" target="_blank">Introducing Smart Labels</a></small><br /><small>18 March 2012: <a href="http://cdevroe.com/notes/why-nilai/" target="_blank">Why I'm building Nilai</a></small></p>
    <p><small>Follow <a href="http://twitter.com/nilaico/" target="_blank">@nilaico</a> for updates.</small></p>
  </div>
  
  <div class="span3">
    <blockquote>"I am surprised at how much I like the responsiveness of Nilai. I didn't realize Delicious was 'slow' until now." <cite><a href="https://twitter.com/canadaduane/status/184278091248582656">Duane Johnson</a></cite></blockquote>
    
    <blockquote>"Smart Labels in Nilai just made it for me." <cite><a href="https://twitter.com/alexknowshtml/status/182273556598636544">Alex Hillman</a></cite></blockquote>
    
    <blockquote>"I use Nilai to keep all of my recipes." <cite>Eliza Devroe</cite></blockquote>
  </div>
</div>

<?php $this->load->view('footer'); ?>