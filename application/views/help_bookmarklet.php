<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span6">
    <h2>Help with bookmarklets</h2>
		<p>Installing bookmarklets isn't easy. Especially on mobile devices. Here is how you do it, step-by-step:</p>

		<h3>On your computer</h3>
		<p>In Safari, Chrome, Firefox, or Internet Explorer the installation process is nearly the same.</p>
		<ol>
		  <li>Click and Drag the link in the sidebar into your browser's bookmarks bar</li>
		  <li>That's it! Now, when you're on a page that you want to bookmark, click the bookmarklet.</li>
		</ol>

		<h3>On iPad, iPhone or iPod touch</h3>
		<textarea cols="10" rows="10">javascript:(function(){f='<?php echo site_url();?>marks/add?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title)+'&v=6&';a=function(){if(!window.open(f+'noui=1&jump=doclose','nilaiv1','location=1,links=0,scrollbars=0,toolbar=0,width=710,height=660'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()</textarea>


		<ol><li>Bookmark this page</li><li>Copy the text in the box above</li><li>Go into your bookmarks on your iOS device</li><li>Edit the bookmark</li><li>Paste the text as the URL</li><li>Save it.</li><li>You're done!</li></ol>
		<p>Still confused? I understand. It is confusing. I don't know why it isn't easier to do. If you'd like personal help installing the bookmarklet you can send me an email and I'll try to help.</p>

		<h3>Android devices</h3>
		<p>I have no idea. If you do, please drop me a line.</p>
  </div>

  <div class="well span3">
    <p><a class="btn" href="javascript:(function(){f='<?php echo site_url();?>marks/add?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title)+'&v=6&';a=function(){if(!window.open(f+'noui=1&jump=doclose','nilaiv1','location=1,links=0,scrollbars=0,toolbar=0,width=710,height=660'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()" title="Install bookmarklet">Nilai+</a> <strong>&lt;-- DRAG TO BOOKMARKS</strong></p>
    <p><a href="/home">Back to your marks</a></p>
  </div>

</div>

<?php $this->load->view('footer'); ?>