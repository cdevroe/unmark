<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span6">
    <h2>Changelog</h2>
		<p>A quick list of what's new. Posted immediately upon update.</p>

		<h4 id="033"><a href="#032">0.3.3 - 6 January 2014</a></h4>
		  <ul>
		    <li>Updated: Terms and homepage.</li>
		  </ul>

		<h4 id="032"><a href="#032">0.3.2 - 2 January 2014</a></h4>
		  <ul>
		    <li>Update: Added basic archive/saved stats in sidebar.</li>
		    <li>Update: Remove superfluous JavaScript for input value replacement. Using "placeholder" instead.</li>
		    <li>Fix: Group invitations can now be rejected.</li>
		    <li>Update: Added version number to index.php to keep track</li>
		    <li>Update: Switched logging on production to be far more minimal.</li>
		  </ul>

		<h4 id="031"><a href="#031">0.3.1 - 2 January 2014</a></h4>
		  <ul>
		    <li><a href="https://github.com/cdevroe/nilai/issues/13">Update</a>: Added version in sidebar. Easier to keep track.</li>
		    <li></li>
		  </ul>

		<h4 id="03"><a href="#03">0.3 - 2 January 2014</a></h4>
		  <ul>
		    <li>Fix: The $name variable for creating a group wasn't being set.</li>
		  </ul>
		
		<h4 id="02"><a href="#02">0.2 - 31 December 2013</a></h4>
		  <ul>
		    <li>New: Delete marks from the edit mark screen.</li>
		  </ul>
		
		<h4 id="01"><a href="#01">0.1 - 31 December 2013</a></h4>
		  <ul>
		    <li>Initial official beta release.</li>
		  </ul>
		
  </div>
  
  <div class="well span3">
    <a href="/home">Back to your marks</a>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>