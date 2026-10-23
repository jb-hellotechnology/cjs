$('a.menu').click(function(){
    $('header nav > ul').toggle();
  })
  
  $(window).resize(function(){
	  if($(window).width()>'767'){
		  $('nav > ul').show();
	  }else{
		  $('nav > ul').hide();
	  }
  })
  
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
