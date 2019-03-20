<section class="buttons">
    <a class="search-button" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M18,8a6,6,0,1,0,6,6A6,6,0,0,0,18,8Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,18,18Z"/><rect x="10.54" y="16.46" width="2" height="8" transform="translate(17.85 -2.16) rotate(45)"/></svg>
    </a>
    <a class="add-mark-button" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><polygon points="24,15 17,15 17,8 15,8 15,15 8,15 8,17 15,17 15,24 17,24 17,17 24,17 "/></svg>
    </a>
</section>
<section class="search-bar">
    <i><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M18,8a6,6,0,1,0,6,6A6,6,0,0,0,18,8Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,18,18Z"/><rect x="10.54" y="16.46" width="2" height="8" transform="translate(17.85 -2.16) rotate(45)"/></svg></i>
    <form method="get" action="/marks/search" id="search-form">
      <input autofocus type="text" name="q" id="search-input" placeholder="<?php echo unmark_phrase('Search...') ?>" autocapitalize="off">
      <button type="submit"><?php echo unmark_phrase('Go') ?></button><!-- **Add to phrases** -->
    </form>
    <a class="close-button" href="#"></a>
</section>
<section class="add-mark-bar">
    <i><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 32 32"><polygon points="22,26 16,22 10,26 10,6 22,6 "/></svg></i>
    <form method="post" action="/marks/add" id="add-mark-form">
        <input type="hidden" name="add_from_url" value="1">
        <input type="text" name="url" id="add-mark-input" placeholder="<?php echo unmark_phrase('Enter URL...') ?>" autocapitalize="off">
        <button type="submit"><?php echo unmark_phrase('Add<span> Mark</span>') ?></button><!-- **Add to phrases** -->
    </form>
    <a class="close-button" href="#"></a>
</section>
