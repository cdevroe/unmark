!function ($) {

  // when the document is ready
  $(function(){
      
      // !Popover
      $("a[rel=popover]")
            .popover()
            .click(function(e) {
              e.preventDefault()
            })
      // !Dropdown
      $('.dropdown-toggle').dropdown()

      if ( $('[name="terms"]') ) {
        $('[name="terms"]').click(function(){ 
          $('[name="join"]').removeAttr('disabled');
        });

      }
      
      // !Add label
      // Add label to mark
      $('.addlabel').click(function(e){
        e.preventDefault();
        if ($(this).hasClass('btn-success')) { return; } // Do nothing if clicking on a label that is already chosen
        
        label = $(this).attr("title");
        urlid = $("#urlid").val();
        
        $.ajax({
          url: "/marks/addlabel?urlid="+urlid+"&label="+label,
          success: function(){}
        });
        
        $('.addlabel').removeClass('btn-success');
        $(this).addClass("btn-success");
        
        $('#label').val(label);
        
        if ($('#clearlabelbutton').hasClass('disabled')) { // Make clear label and add smart label button active
          
          $('#clearlabelbutton').removeClass('disabled');
          $('#smartlabelbutton').removeClass('disabled');
          
        } else { // De-activate?
        
          if ($('#smartlabelbutton').hasClass('btn-danger')) { // Just deactivate clearlabel, not smart label
            $('#clearlabelbutton').removeClass('btn-success');
            $('#clearlabelbutton').addClass('disabled');
            return; 
          }
          
          if (label == '') { // if label is nothing, disable both buttons
            $('#clearlabelbutton').removeClass('btn-success');
            $('#smartlabelbutton').removeClass('btn-danger');
            $('#clearlabelbutton').addClass('disabled');
            $('#smartlabelbutton').addClass('disabled');
          }
        }
        
      });
      
      // !Add to Group
      $('.addgroup').click(function(e){
        e.preventDefault();
        
        urlid = $("#urlid").val();
        
        currentgroup = $('#group').val();
        newgroup = $(this).attr("data-group");
        
        if ($(this).hasClass('btn-success')) {
          // Remove from group
          $.ajax({
            url: "/marks/addgroup?urlid="+urlid+"&group=",
            success: function(){}
          });
          $(this).removeClass('btn-success');
        } else {
          // Add to group
          $('.addgroup').removeClass('btn-success');
          $.ajax({
            url: "/marks/addgroup?urlid="+urlid+"&group="+newgroup,
            success: function(){}
          });
          $('#group').val(newgroup);
          $(this).addClass("btn-success");
        }
        
        note = $('#note').val();
        $.ajax({
            url: "/marks/savenote?urlid="+urlid+"&note="+encodeURIComponent(note),
            success: function(){}
          });
        
      });
      
      
      
      // !Smart label
      $('#smartlabelbutton').click(function(e){
        e.preventDefault();
        
        if ($(this).hasClass('disabled')) { return; } // If button is disabled do nothing.
        
        if ($(this).hasClass('btn-danger')) {
          // Remove the smart label
          // Add the smart label
          label = $('#label').val();
          domain = $('#domain').val();
          
          $.ajax({
            url: "/marks/removesmartlabel?domain="+domain+"&label="+label,
            success: function(){}
          });
          
          $(this).removeClass('btn-danger');
          $(this).html('Add Smart Label?');
          
          if (label == '') $(this).addClass('disabled');
          
          $('#smartlabelmessage').html('');
        } else {
          // Add the smart label
          label = $('#label').val();
          domain = $('#domain').val();
          
          $.ajax({
            url: "/marks/addsmartlabel?domain="+domain+"&label="+label,
            success: function(){}
          });
        
          $(this).addClass('btn-danger');
          $(this).html('Stop using Smart Label?');
          $('#smartlabelmessage').html('<small>All future marks from <strong>'+domain+'</strong> will be labeled <strong>'+label+'</strong> automatically.</small>');
        }
      
      });
      
      // ! Save a note
      $('#note').blur(function(){

        if ($(this).val() != '') {

          urlid = $("#urlid").val();
          note = $(this).val();
          
          $.ajax({
            url: "/marks/savenote?urlid="+urlid+"&note="+encodeURIComponent(note),
            success: function(){}
          });
        }
      });
      
      // !Archive mark
      // Asynchronously archive or restore.
      $('.archivemark').click(function(e){
        e.preventDefault();
        
        url = $(this).attr("href");
        
        markid = $(this).attr("data-mark");
        preview = $('#preview-'+markid);
        note = $('#note-'+markid);
        
        if (preview && !$(preview).is(':hidden')) {
          preview.toggle();
        }
        
        if (note && !$(note).is(':hidden')) {
          note.toggle();
        }
        
        $.ajax({
          url: url,
          success: function(){}
        });
        
        $(this).parent().parent().parent().hide(800);
      });
      
      // If "edit marks" is clicked, show edit buttons and activate button
      /*$('.editmarks').click(function(){
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
          $(this).html('Edit marks');
        } else {
          $(this).addClass('active');
          $(this).html('Stop editing');
        }
        
        $('.archivemark').toggle();
        $('.editmark').toggle();
      }); *
      
      //Edit icon per mark
      /*$('.mark h3').hover(function(){
        $(this).children('.editmark').toggle();
      });*/
      
      // !Preview button
      $('.preview-button').click(function(e){
        e.preventDefault();

        preview = $(this).attr("href");
        markid = $(this).attr("data-mark");
        mark = $('#mark-'+markid);
        
        // Show/hide
        $(preview).toggle();
        
        if (!$(preview).is(':hidden')) {
          //$.scrollTo(mark,900);
          $(mark+' > .preview-button i').removeClass('icon-zoom-in');
          $(mark+' > .preview-button i').addClass('icon-zoom-out');
        } else {
          $(mark+' > .preview-button i').removeClass('icon-zoom-out');
          $(mark+' > .preview-button i').addClass('icon-zoom-in');
        }
      });
      
      // !Restored mark
      // Show a mark has been restored
      $('.restored').hide();
      $('.restored').fadeIn(1000);
      
})
}(window.jQuery)