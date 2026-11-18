/* Mobile menu toggle.

   The button is <button id="menu"> in .masthead, and the CSS opens the menu
   when the main nav list carries .show (see the max-width:660px block in
   layout.css). This used to bind to "a.menu" and toggle "header nav > ul"
   with inline styles, which matched the old markup only: the button is no
   longer a link, the nav is a sibling of <header> on most layouts, and an
   inline display would beat the stylesheet anyway.

   Vanilla, and first in this file, because navigation has to keep working
   even if the jQuery CDN fails - everything below this point is
   jQuery-dependent and dies with it. */
(function () {
  var button = document.getElementById('menu');
  if (!button) return;

  /* The home layout carries both nav.mobile and nav.desktop; every other
     layout has a single nav. Toggle whichever one the mobile CSS shows. */
  var nav = document.querySelector('nav.mobile') || document.querySelector('nav');
  if (!nav) return;

  /* ul.inline.sub is the second-level list, kept hidden at mobile widths. */
  var menu = nav.querySelector('ul.inline:not(.sub)');
  if (!menu) return;

  button.setAttribute('aria-expanded', 'false');

  button.addEventListener('click', function () {
    var open = menu.classList.toggle('show');
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  /* Dragged back up to desktop widths: drop the class so the nav is not
     left holding an open-menu state it no longer uses. */
  var mobile = window.matchMedia('(max-width: 660px)');
  var reset = function () {
    if (mobile.matches) return;
    menu.classList.remove('show');
    button.setAttribute('aria-expanded', 'false');
  };

  if (mobile.addEventListener) {
    mobile.addEventListener('change', reset);
  } else if (mobile.addListener) {
    mobile.addListener(reset);
  }
})();

  
  function showReset(){
    $('#resetform').toggle();
  }
  
  $('.form-content').hide();
  $('#form2_type').change(function(){
    $('.form-content').show();
  });
  $('#choice').change(function(){
    var option = $('#choice').val();
    console.log(option);
    $('.form-content').hide();
    $('.form-content.'+option).show();
  })
  
  $('.success').each(function(){
    $(this).parent().show();
  })
  
  $('#form2_type').change(function(){
	    var type = $('#form2_type').val();
	    if(type == 'Provider'){
		    $('.input.provider').hide();
	    }else{
		    $('.input.provider').show();
	    }
	    
    });
  
  function get_tags(){
	$('.blog-tags li').hide();
	var searchTerm = $('#q').val();
	console.log(searchTerm);
	$('.blog-tags li:contains('+searchTerm+')').show();
  }	
  
  function get_employers(){
	$('.tags li').hide();
	var searchTerm = $('#q').val();
	var lower = searchTerm.toLowerCase();
	var upper = searchTerm.toUpperCase();
	$('.tags li:contains('+lower+')').show();
  }
