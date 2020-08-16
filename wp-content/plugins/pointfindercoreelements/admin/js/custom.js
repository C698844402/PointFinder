(function($) {
  "use strict";

  $(function(){
	

  });

  $(window).load(function() {
  	if ($('body').hasClass('appearance_page_pt-one-click-demo-import')) {
		
		console.log(pointfinderadmcustom.site_mod);

		if (pointfinderadmcustom.site_mod == 'none') {
			console.log('Process Choose Started');

			$('.js-ocdi-gl-import-data').off('click');

			$.each($('.js-ocdi-gl-import-data'), function(index, val) {
				 $(this).text(pointfinderadmcustom.quickinstall_w1);
				 $(this).removeClass('js-ocdi-gl-import-data').removeClass('button-primary').addClass('pointfinder-qi-before');
			});
		}else{
			console.log('Process Import Started');
			$.each($('.js-ocdi-gl-import-data'), function(index, val) {
				if ($(this).val() != pointfinderadmcustom.site_mod) {
					$(this).text(pointfinderadmcustom.quickinstall_w1);
				 	$(this).removeClass('js-ocdi-gl-import-data').removeClass('button-primary').addClass('pointfinder-qi-before');
				 	$(this).off('click');
				}
			});
		}

		$( '.pointfinder-qi-before' ).on( 'click', function () {
			$(this).text(pointfinderadmcustom.buttonwait);
			$('.pointfinder-qi-before').attr("disabled", true);
			$('.js-ocdi-gl-import-data').attr("disabled", true);
			$.ajax({
				beforeSend:function(){},
	            type: 'POST',
	            dataType: 'json',
	            url: pointfinderadmcustom.ajaxurl,
	            data: { 
	                'action': 'pfget_quicksetupprocess',
	                'myval': $(this).val(),
	                'security': pointfinderadmcustom.pfget_quicksetupprocess
	            },
	            success:function(data){
	            	console.log(data);
	            	var obj = [];
					$.each(data, function(index, element) {
						obj[index] = element;
					});

				
					if(obj.process == true){
						$('.pointfinder-qi-before').text(pointfinderadmcustom.buttonwait2);
						setTimeout(function(){location.reload();},500);
					}else{
						$('.pointfinder-qi-before').val(pointfinderadmcustom.buttonerror);
						setTimeout(function(){location.reload();},500);
					}

	            },
	            error: function (request, status, error) {console.log(request);console.log(status);console.log(error);},
	            complete: function(){},
	        });
		});
		
	}
  });

})(jQuery);
