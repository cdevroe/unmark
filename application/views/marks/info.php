<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo unmark_phrase('Unmark : Mark Added'); ?></title>
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/unmark.css?v=<?php echo $this->config->item('unmark_version'); ?>" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <script src="/assets/js/plugins/modernizr-2.7.1.min.js"></script>
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>
<body class="unmark-solo greybg">

<div class="mark-added" data-label="<?php print $mark->label_id; ?>" data-label-name="<?php print $mark->label_name; ?>">

    <div class="mark-added-block">
        <?php echo unmark_phrase('This mark has been added to your stream.'); ?>
        <i class="icon-check"></i>
    </div>
    <div class="mark-added-info">
        <h1 class="hideoutline"><?php print $mark->title; ?></h1>
        <span><?php print niceUrl($mark->url); ?></span>
    </div>

    <div class="mark-added-meta">
      <div class="mark-added-label mark-added-settings label-<?php print $mark->label_id; ?>">
          <h4>Label</h4>
          <span id="label-chosen"></span>
          <a id="currLabel" class="action" data-action="marks_addLabel" href="#"><?php echo unmark_phrase('Unlabeled'); ?></a>
          <section data-id="<?php print $mark->mark_id; ?>">
              <ul data-id="<?php print $mark->mark_id; ?>" class="label-choices"></ul>
          </section>
      </div>
      <div class="mark-added-tags mark-added-settings">
          <h4>Tags</h4>
          <?php if ( !empty($mark->tags) ) :
              $tag_csv = '';
              foreach ($mark->tags as $tag=>$tag_array) :
                  $tag_csv.=$tag.',';
              endforeach;
              $tag_csv = trim($tag_csv,',');

          endif; ?>
          <section id="tags-<?php print $mark->mark_id; ?>" data-id="<?php print $mark->mark_id; ?>">
            <input data-mark-id="<?php print $mark->mark_id; ?>" type="text" id="input-tags" class="mark-added-tags-area selectize" placeholder="e.g. work, technology, interview" value="<?php if ( !empty($mark->tags)) { print $tag_csv; } ?>">
          </section>
          <?php
          $tagcount = 6; // Number of tags to show
          
          if ( !empty($tags['popular']) ) : ?>
            <p>Most-used:
            <?php   $i = 0;
                    foreach ($tags['popular'] as $tag) :
                        if ($i == $tagcount) continue;
                        echo '<a href="#" class="quick-tag">#'.$tag->name.'</a>';
                        $i++;
                    endforeach; ?>
            </p>
          <?php endif; // Tags popular ?>
          <?php if ( !empty($tags['recent']) ) : ?>
            <p>Recently-used:
            <?php
                    $i=0;
                    foreach ($tags['recent'] as $tag) :
                        if ($i == $tagcount) continue;
                        echo '<a href="#" class="quick-tag">#'.$tag->name.'</a>';
                        $i++;
                    endforeach; ?>
            </p>
        <?php endif; // Tags recent ?>
      </div>
      <div class="mark-added-note mark-added-settings">
          <h4>Notes</h4>
          <div class="mark-added-note-box">
            <?php if (empty($mark->notes)) : ?>
                <!-- <a class="action" data-action="marks_addNotes" href=""><?php echo unmark_phrase('Add a Note or Edit Title'); ?></a>-->
                <textarea class="mark-added-notes-area" data-id="<?php print $mark->mark_id; ?>" placeholder="<?php echo unmark_phrase('Type note text here...'); ?>"></textarea>
            <?php else : ?>
                <textarea class="mark-added-notes-area" data-id="<?php print $mark->mark_id; ?>" placeholder="<?php echo unmark_phrase('Type note text here...'); ?>"><?php print $mark->notes; ?></textarea>
            <?php endif; ?>
          </div>
      </div>
    </div>

    <div class="mark-added-actions">
    <?php if ( $bookmarklet == 'true' ) { ?>
        <div class="delete-button">
            <button class="delete" data-action="delete_mark" data-view="bookmarklet" data-id="<?php print $mark->mark_id; ?>"><?php echo unmark_phrase('Delete Link'); ?></button>
        </div>
    <?php } ?>
        <div class="update-button">
            <?php if ( $bookmarklet == 'true' ) { ?>
                <button data-action="close_window"><?php echo unmark_phrase('Update &amp; Close'); ?></button>
            <?php } else { ?>
                <button><a href="/"><?php echo unmark_phrase('Update &amp; Return'); ?></a></button>
            <?php } ?>
        </div>
    </div>

</div>

<?php $this->load->view('layouts/jsvars'); ?>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.plugins.js?v=<?php echo $this->config->item('unmark_version'); ?>"></script>
<script src="/assets/js/production/unmark.bookmarklet.js?v=<?php echo $this->config->item('unmark_version'); ?>"></script>

</body>
</html>
