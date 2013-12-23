<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span4">
		<p><a href="http://cdevroe.com/notes/why-nilai/" target="_blank">My mission is simple</a>. I'm tired of my favorite services being bought out only to see them go away. Nilai is a simple bookmarking service that has no intention of ever selling. Save your bookmarks, forever.</p>
		<p><strong>Just $1/month.</strong><br />
		<small>Or $10/year if you'd prefer.</small></p>
    <hr />
		<p>Use Nilai on your desktop, mobile phone, and tablet!</p>
    <hr />
    <h4>News</h4>
    <p><small>27 March 2012: <a href="http://cdevroe.com/notes/nilai-previews/" target="_blank">Introducing Previews</a></small><br /><small>20 March 2012: <a href="http://cdevroe.com/notes/nilai-smartlabels/" target="_blank">Introducing Smart Labels</a></small><br /><small>18 March 2012: <a href="http://cdevroe.com/notes/why-nilai/" target="_blank">Why I'm building Nilai</a></small></p>
    <p><small>Follow <a href="http://twitter.com/nilaico/" target="_blank">@nilaico</a> for updates.</small></p>
  </div>
  
  <div class="well span5">
  <form method="post" action="users/login" class="form-inline">
      <?=form_input('emailaddress','Email','class="input-small"');?>
      <?=form_password('password','xxxxxxx','class="input-small"');?>
      <input type="submit" value="Log in" name="login" id="login" class="btn btn-primary" />
    </form>
    <p><small>Want to help out? <strong><a href="/sirius" title="Sign up!">Sign up</a></strong> to become a member.</small></p>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>