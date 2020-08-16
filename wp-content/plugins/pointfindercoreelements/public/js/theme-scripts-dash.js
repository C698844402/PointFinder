(function($) {
	"use strict";

	/***************************************************************************************************************
	*
	*
	* USER DASHBOARD ACTIONS
	*
	*
	***************************************************************************************************************/
	/* Account Removal System */
		$('.accountremovebutton').on('click', function(event) {

			if (confirm($(this).data("remes"))) {
				
				$.ajax({
					url: theme_scriptspf.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'pfget_accountremoval',
						lang: theme_scriptspfm.pfcurlang,
						security: $(this).data("nonce")
					}
				}).success(function(obj) {
					if (obj.success) {
						$('.accountremovebuttonres').html('<div class="notification success" id="pfuaprofileform-notify"><p>'+obj.data+'</p></div>')
					} else {
						$('.accountremovebuttonres').html('<div class="notification error" id="pfuaprofileform-notify"><p>'+obj.data+'</p></div>')
					}
				});
			} 
		});

	/* Post Tag System STARTED */
		$('body').on('click touchstart','.pf-item-posttag a',function(){
			var selectedtag = $(this);
			var selectedtagicon = $(this).children('i');

			$.ajax({
		    	beforeSend:function(){
		    		selectedtagicon.switchClass('pfadmicon-glyph-644','pfadmicon-glyph-647');
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_posttag',
					id: $(this).data('pid'),
					pid: $(this).data('pid2'),
					lang: theme_scriptspfm.pfcurlang,
					security: theme_scriptspfm.pfget_posttag
				}
			}).success(function(obj) {
				if (obj == 1) {
					selectedtag.closest('.pf-item-posttag').remove();
				}

			}).complete(function(){

				selectedtagicon.switchClass('pfadmicon-glyph-647','pfadmicon-glyph-644');

			});
		});
	/* Post Tag System END */


	/* Map function STARTED */

		$.pf_submit_page_map = function(){
			
			var mapcontainer = $('#pfupload_map');
			var pf_istatus = mapcontainer.data('pf-istatus');
		 	var lat = mapcontainer.data('lat');
	        var lng = mapcontainer.data('lng');
	        var marker;

			if (!pf_istatus) {
				$.pointfinderuploadmapsys = $.pointfinderbuildmap('pfupload_map');
				
				$.pointfinderuploadmarker = marker = L.marker([parseFloat(lat),parseFloat(lng)])
		      	.on('click',function(e) {

		      		//mapobj.setView(this.getLatLng());
		      		
		      	})
		      	.on('dragend',function(e) {

		      		$('#pfupload_lat_coordinate').val(marker.getLatLng().lat);
		    		$('#pfupload_lng_coordinate').val(marker.getLatLng().lng);

		    		if ($('#pfitempagestreetviewMap').length > 0) {
		    			$('#pfitempagestreetviewMap').data('pfcoordinateslat',marker.getLatLng().lat);
						$('#pfitempagestreetviewMap').data('pfcoordinateslng',marker.getLatLng().lng);
						$.pfstmapregenerate(marker.getLatLng());
		    		}
		      		
		      	}).addTo($.pointfinderuploadmapsys);

		      	marker.dragging.enable();

				$('#pfupload_map').data("pf-istatus","true");
			}

		}

		$.pfstmapregenerate = function(latLng){
			$.pfstpano.setPosition(latLng);
		}
		$.pfstmapgenerate = function(){
			var current_heading = parseFloat($("#webbupointfinder_item_streetview-heading").val());
			var current_pitch = parseFloat($("#webbupointfinder_item_streetview-pitch").val());
			var current_zoom = parseInt($("#webbupointfinder_item_streetview-zoom").val());

			var pfitemcoordinatesLat = parseFloat($("#pfitempagestreetviewMap").data('pfcoordinateslat'));
			var pfitemcoordinatesLng = parseFloat($("#pfitempagestreetviewMap").data('pfcoordinateslng'));
			var pfitemzoom = parseInt($("#pfitempagestreetviewMap").data('pfzoom'));

			var pfitemcoordinates_output = L.latLng(pfitemcoordinatesLat,pfitemcoordinatesLng);

			if ($('#pfupload_map').data('lng') == 'undefied') {
				var defaultlocl = L.latLng(40.71275, -74.00597);
			}else{
				var defaultlocl = L.latLng($('#pfupload_map').data( 'lat'), $('#pfupload_map').data('lng'));
			}

			var curlatLng = (typeof pfitemcoordinatesLat != 'undefined')? pfitemcoordinates_output:defaultlocl;

			if (current_heading != 0 && current_pitch != 0) {
				var pfpanoramaOptions = {
		          position: curlatLng,
		          pov: {
		            heading: current_heading,
		            pitch: current_pitch
		          },
		          zoom: current_zoom
		        };
		        $.pfstpano = new google.maps.StreetViewPanorama(
		            document.getElementById('pfitempagestreetviewMap'),
		            pfpanoramaOptions);
		        $.pfstpano.setVisible(true);
		        setTimeout(function(){
		        	$.pfstpano.setPosition(curlatLng);
		        	$.pfstpano.setPov({heading: current_heading,pitch: current_pitch});
		        },1000)
			}else{
				var pfpanoramaOptions = {
		          position: curlatLng
		        };
		        $.pfstpano = new google.maps.StreetViewPanorama(
		            document.getElementById('pfitempagestreetviewMap'),
		            pfpanoramaOptions);
		        $.pfstpano.setVisible(true);
			}

			$.pfstpano.addListener('pov_changed', function() {
              $("#webbupointfinder_item_streetview-heading").val($.pfstpano.getPov().heading);
              $("#webbupointfinder_item_streetview-pitch").val($.pfstpano.getPov().pitch)
              $("#webbupointfinder_item_streetview-zoom").val($.pfstpano.getPov().zoom)
            });

            $.pfstpano.addListener('position_changed', function() {
              $("#webbupointfinder_item_streetview-heading").val($.pfstpano.getPov().heading);
              $("#webbupointfinder_item_streetview-pitch").val($.pfstpano.getPov().pitch)
              $("#webbupointfinder_item_streetview-zoom").val($.pfstpano.getPov().zoom)
            });
		}

	/* Map Function END */


	/* MEMBERSHIP PACKAGES STARTED */
		$('.pf-membership-splan-button a').on('click', function() {
			var packageid = $(this).data('id');
			var ptype = $(this).data('ptype');
			$.pfmembershipgetp(packageid,ptype);
		});

		$.pfmembershipgetp = function(packageid,ptype){

			$('.pfsubmit-inner-membership').hide( "fade");
			$('.pfsubmit-inner-membership-payment').show("fade");
			$('input[name="selectedpackageid"]').val(packageid)

			$.ajax({
				beforeSend:function(){
					$("#pf-ajax-s-button").attr("disabled", true);
					$('.pfm-payment-plans').pfLoadingOverlay({action:'show',message: theme_scriptspfm.buttonwait});
				},
	            type: 'POST',
	            dataType: 'html',
	            url: theme_scriptspf.ajaxurl,
	            data: {
	                'action': 'pfget_membershippaymentsystem',
	                'ptype':ptype,
	                'pid': packageid,
	                'security': theme_scriptspfm.pfget_membershipsystem,
	                'lang': theme_scriptspfm.pfcurlang
	            },
	            success:function(data){
	            	$('.pfm-payment-plans').html(data);
	            },
	            error: function (request, status, error) {
	            	console.log(error);
	            },
	            complete: function(){
	            	$("#pf-ajax-purchasepack-button").attr("disabled", false);
	            	$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex2);
	            	$('.pfm-payment-plans').pfLoadingOverlay({action:'hide'});
	            },
	        });
			return false;
		};

		$('.pfsubmit-title-membershippack').on('click', function() {
			$('.pfsubmit-inner-membership').show("fade");
			$('.pfsubmit-inner-membership-payment').hide("fade");
		});

		$('.pfsubmit-title-membershippack-payment').on('click', function() {
			$('.pfsubmit-inner-membership').hide("fade");
			$('.pfsubmit-inner-membership-payment').show("fade");
		});

		// AJAX MEMBERSHIP PAYMENT PROCESS
		$("#pf-ajax-purchasepack-button").on("click touchstart",function(){

			var form = $("#pfuaprofileform");
			form.validate();

			if(!form.valid()){
				$.pfscrolltotop();
			}else{
				$("#pf-ajax-purchasepack-button").val(theme_scriptspfm.buttonwait);
				$.pfOpenMembershipModal('open','purchasepackage',form.serialize());
				return false;
			};
		});

		// AJAX MEMBERSHIP CANCEL RECURRING
		$('body').on('click', '.pf-dash-cancelrecurring', function() {
			$.pfOpenMembershipModal('open','cancelrecurring','');
			return false;
		});

		$.pfOpenMembershipModal = function(status,modalname,formdata) {

			$.pfdialogstatus = '';

		    if(status == 'open'){

		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	if (modalname == 'purchasepackage' || modalname == 'cancelrecurring') {
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});
		    	};

	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};

	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_membershipsystem',
		                'formtype': modalname,
		                'dt': formdata,
		                'security': theme_scriptspfm.pfget_membershipsystem,
		                'lang': theme_scriptspfm.pfcurlang
		            },
		            success:function(data){

		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});



						if(obj.process == true){
							if (obj.processname == 'paypal'|| obj.processname == 'paypal2' ) {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
								window.location = obj.returnurl;
							}else if (obj.processname == 'pags') {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.generalredirect});
								window.location = obj.returnurl;
							}else if (obj.processname == 'ideal') {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.generalredirect});
								window.location = obj.returnurl;
							}else if (obj.processname == 'payu') {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.payumail);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								var payuForm = document.forms.payuForm;
							    payuForm.submit();

							}else if (obj.processname == 'robo' || obj.processname == 't2co' || obj.processname == 'payf') {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								var roboForm = document.forms.roboForm;
							    roboForm.submit();

							}else if(obj.processname == 'iyzico'){

								if (obj.iyzico_status == 'success') {
									$('.pointfinder-dialog').remove();
									$('.pf-membersystem-overlay').remove();
									$("#iyzipay-checkout-form").html(obj.iyzico_content);
								}else{
									setTimeout(function(){window.location = obj.returnurl;},1000);
								}

								$('body.pfdashboardpagenewedit').on('click','.iyzi-closeButton',function(){
									window.location = obj.returnurl;
								});
								$('body.pfdashboardpage').on('click','.iyzi-closeButton',function(){
									window.location = obj.returnurl;
								});
								$('.pf-overlay-close').on('click',function(){
									window.location = obj.returnurl;
								});

							}else if(obj.processname == 'stripe'){
								var handler = StripeCheckout.configure({
									key: obj.key,
									token: function(token) {
										$.pfOpenMembershipModal('open','stripepay',token);
									}
								});


								handler.open({
								  name: obj.name,
								  description: obj.description,
								  amount: obj.amount,
								  email: obj.email,
								  currency: obj.currency,
								  allowRememberMe: false,
								  opened:function(){
								  	$.pfOpenMembershipModal('close');
								  }
								});


								$(window).on('popstate', function() {
									handler.close();
								});
							}else if(obj.processname == 'stripepay'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								window.location = theme_scriptspfm.dashurl;

							}else if(obj.processname == 'free' || obj.processname == 'trial'){

								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									window.location = theme_scriptspfm.dashurl;
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							}else if(obj.processname == 'bank'){

								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									window.location = obj.returnurl;
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							}else if(obj.processname == 'cancelrecurring'){
								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									setTimeout(function(){
										window.location = theme_scriptspfm.dashurl;
									},2000);
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);

							if(modalname == 'payu'){setTimeout(function(){window.location = obj.returnurl;},1000);}
						}

						$('.pf-overlay-close').on('click',function(){
							$.pfOpenMembershipModal('close');
						});


		            },
		            error: function (request, status, error) {

	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);

		            },
		            complete: function(){
	            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            	$('.pointfinder-dialog').center(true);
		            },
		        });

	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			    	closeOnEscape: false,
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				        $('.ui-widget-overlay').on('click',function(e) {
						    e.preventDefault();
						    return false;
						});
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* MEMBERSHIP PACKAGES END */


	/* AJAX PAYMENT MODAL STARTED */
		$('body').on('click','.pfbuttonpaymentb',function(){

			var selectedval = $(this).parent().parent().prev().find('select option:selected').val();
			var itemnum = $(this).data('pfitemnum');

			if(selectedval == 'creditcard'){

				$.pfOpenPaymentModal('open','creditcardstripe',itemnum,'');

			}else if(selectedval == 'paypal'){

				$.pfOpenPaymentModal('open','paypalrequest',itemnum,'');

			}else if(selectedval == 'pags'){

				$.pfOpenPaymentModal('open','pags',itemnum,'');

			}else if(selectedval == 'ideal'){

				$.pfOpenPaymentModal('open','ideal',itemnum,'');


			}else if(selectedval == 'payu'){

				$.pfOpenPaymentModal('open','payu',itemnum,'');

			}else if(selectedval == 'robo'){

				$.pfOpenPaymentModal('open','robo',itemnum,'');

			}else if(selectedval == 't2co'){

				$.pfOpenPaymentModal('open','t2co',itemnum,'');

			}else if(selectedval == 'payf'){

				$.pfOpenPaymentModal('open','payf',itemnum,'');

			}else if(selectedval == 'iyzico'){

				$.pfOpenPaymentModal('open','iyzico',itemnum,'');

			}else{

				window.location = selectedval;

			};


			return false;
		});

		$.pfOpenPaymentModal = function(status,modalname,itemid,token,otype) {


			$.pfdialogstatus = '';

		    if(status == 'open'){
		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	if (modalname == 'creditcardstripe') {
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});
		    	}else if(modalname == 'paypalrequest'){
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
		    	}else if(modalname == 'pags' || modalname == 'payu' || modalname == 'robo' || modalname == 't2co' || modalname == 'payf'){
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.generalredirect});
		    	}else if(modalname == 'stripepayment' || modalname == 'iyzico'){
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect3});
		    	};

	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};

	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_paymentsystem',
		                'formtype': modalname,
		                'itemid': itemid,
		                'otype':otype,
		                'token': token,
		                'security': theme_scriptspfm.pfget_paymentsystem
		            },
		            success:function(data){

		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});



						if(obj.process == true){
							if (modalname == 'paypalrequest' || modalname == 'pags' || modalname == 'ideal') {
								window.location = obj.returnurl;
							}else if(modalname == 'payu'){

								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.payumail);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								var payuForm = document.forms.payuForm;
							    payuForm.submit();
							}else if(modalname == 'iyzico'){
								if (obj.iyzico_status == 'success') {
									$('.pointfinder-dialog').remove();
									$('.pf-membersystem-overlay').remove();
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
									$("#iyzipay-checkout-form").html(obj.iyzico_content);
								}else{
									setTimeout(function(){window.location = obj.returnurl;},1000);
								}

								$('body.pfdashboardpagenewedit').on('click','.iyzi-closeButton',function(){
									window.location = obj.returnurl;
								});
								$('body.pfdashboardpage').on('click','.iyzi-closeButton',function(){
									window.location = obj.returnurl;
								});
								$('.pf-overlay-close').on('click',function(){
									window.location = obj.returnurl;
								});

								$('body').on('click', '.iyzi-closeButton', function(event) {
									window.location = obj.returnurl;
								});

							}else if(modalname == 'robo' || modalname == 't2co' || modalname == 'payf'){

								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								var roboForm = document.forms.roboForm;
							    roboForm.submit();


							}else if(modalname == 'creditcardstripe'){
								var handler = StripeCheckout.configure({
									key: obj.key,
									token: function(token) {
										$.pfOpenPaymentModal('open','stripepayment',itemid,token,obj.otype);
									}
								});


								handler.open({
								  name: obj.name,
								  description: obj.description,
								  amount: obj.amount,
								  email: obj.email,
								  currency: obj.currency,
								  allowRememberMe: false,
								  opened:function(){
								  	$.pfOpenPaymentModal('close');
								  },
								  closed:function(){
								  	if ($('#pfupload_type').val() == 1) {
								  		setTimeout(function(){window.location = theme_scriptspfm.dashurl2;},2000);
								  	};
								  }
								});


								$(window).on('popstate', function() {
									handler.close();
								});
							}else if(modalname == 'stripepayment'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								setTimeout(function(){window.location = obj.returnurl;},2000);
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);

							if(modalname == 'payu'){setTimeout(function(){window.location = obj.returnurl;},1000);}
						}

						$('.pf-overlay-close').on('click',function(){
							$.pfOpenPaymentModal('close');
						});


		            },
		            error: function (request, status, error) {

	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);

		            },
		            complete: function(){
		            	if (modalname != 'iyzico') {
		            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            		$('.pointfinder-dialog').center(true);
		            	}

		            },
		        });

	        	if(modalname != '' && modalname != 'iyzico'){
				    $("#pf-membersystem-dialog").dialog({
				    	closeOnEscape: false,
				        resizable: false,
				        modal: true,
				        minWidth: minwidthofdialog,
				        show: { effect: "fade", duration: 100 },
				        dialogClass: 'pointfinder-dialog',
				        open: function() {
					        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
					        $('.ui-widget-overlay').on('click',function(e) {
							    e.preventDefault();
							    return false;
							});
					    },
					    close: function() {
					        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
					    },
					    position:{my: "center", at: "center",collision:"fit"}
				    });
				    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* AJAX PAYMENT MODAL END */

	/* LISTING PACK PAYMENTS STARTED */
		$('.pfpackselector').change(function(){
			$.pf_get_priceoutput(1);
		});

		$('#featureditembox').on('change',function(){
			$.pf_get_priceoutput();
		});

		$.pf_get_priceoutput = function(pcs){
			if ($('#pfupload_type').val() == 1) {

				var listing_category = $('input.pflistingtypeselector:checked').val();
				var listing_pack = $('input.pfpackselector:checked').val();
				var listing_featured = ($('#featureditembox').attr('checked'))? 1:0;

				var status_c = $('#pfupload_c').val();
				var status_f = $('#pfupload_f').val();
				var status_p = $('#pfupload_p').val();
				var status_o = $('#pfupload_o').val();
				var status_px = $('#pfupload_px').val();

				if (status_c == 1) {listing_category = '';};
				if (status_f == 1) {listing_featured = '';};
				if (listing_pack == status_p) {listing_pack = '';};

				$.ajax({
			    	beforeSend:function(){
			    		if (pcs == 1) {$('.pflistingtype-selector-main-top').pfLoadingOverlay({action:'show'})};
			    		$('.pfsubmit-inner-payment .pfsubmit-inner-sub').pfLoadingOverlay({action:'show'});
			    		$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwait);
						$("#pf-ajax-uploaditem-button").attr("disabled", true);
			    	},
					url: theme_scriptspf.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'pfget_listingpaymentsystem',
						c:listing_category,
						p:listing_pack,
						f:listing_featured,
						o:status_o,
						px:status_px,
						lang: theme_scriptspfm.pfcurlang,
						security: theme_scriptspfm.pfget_lprice
					},
				}).success(function(obj) {

					if (obj) {
						if (obj.totalpr != 0) {
							$('.pfsubmit-inner-totalcost-output').html(obj.html);
							$('.pfsubmit-inner-payment').show();
						}else{
							$('.pfsubmit-inner-totalcost-output').html(obj.html);
							$('.pfsubmit-inner-payment').hide();
						}

					};

				}).complete(function(){
					$("#pf-ajax-uploaditem-button").attr("disabled", false);
					$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex2);
					$('.pfsubmit-inner-payment .pfsubmit-inner-sub').pfLoadingOverlay({action:'hide'});
					if (pcs == 1) {$('.pflistingtype-selector-main-top').pfLoadingOverlay({action:'hide'})};
				});
			};
		}
	/* LISTING PACK PAYMENTS END */


	/* PROFILE UPDATE FUNCTION STARTED */
		$('#pf-ajax-profileupdate-button').on('click touchstart',function(){

			var form = $('#pfuaprofileform');
			var pfsearchformerrors = form.find(".pfsearchformerrors");
			if ($.isEmptyObject($.pfAjaxUserSystemVars4)) {

				$.pfAjaxUserSystemVars4 = {};
				$.pfAjaxUserSystemVars4.email_err = 'Please write an email';
				$.pfAjaxUserSystemVars4.email_err2 = 'Your email address must be in the format of name@domain.com';
				$.pfAjaxUserSystemVars4.nickname_err = 'Please write nickname';
				$.pfAjaxUserSystemVars4.nickname_err2 = 'Please enter at least 3 characters for nickname.';
				$.pfAjaxUserSystemVars4.passwd_err = $.validator.format("Enter at least {0} characters");
				$.pfAjaxUserSystemVars4.passwd_err2 = "Enter the same password as above";
			}

			form.validate({
				  debug:false,
				  onfocus: false,
				  onfocusout: false,
				  onkeyup: false,
				  rules:{
				    nickname:{
				      required: true,
				      minlength: 3
				    },
				    password: {
						minlength: 7
					},
					password2: {
						minlength: 7,
						equalTo: "#password"
					},
				  	email:{
				  		required:true,
				  		email:true
				  	}
				  },
				  messages:{
				  	nickname:{
					  	required:$.pfAjaxUserSystemVars4.nickname_err,
					  	minlength:$.pfAjaxUserSystemVars4.nickname_err2
				  	},
				  	password: {
						rangelength: $.pfAjaxUserSystemVars4.passwd_err
					},
					password2: {
						minlength: $.pfAjaxUserSystemVars4.passwd_err,
						equalTo: $.pfAjaxUserSystemVars4.passwd_err2
					},
				  	email: {
					    required: $.pfAjaxUserSystemVars4.email_err,
					    email: $.pfAjaxUserSystemVars4.email_err2
				    }
				  },
				  validClass: "pfvalid",
				  errorClass: "pfnotvalid pfaddnotvalidicon",
				  errorElement: "li",
				  errorContainer: pfsearchformerrors,
				  errorLabelContainer: $("ul", pfsearchformerrors),
				  invalidHandler: function(event, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						$.pfscrolltotop();
						pfsearchformerrors.show("slide",{direction : "up"},100);
						form.find(".pfsearch-err-button").on('click',function(){
							pfsearchformerrors.hide("slide",{direction : "up"},100);
							return false;
						});
					}else{
						pfsearchformerrors.hide("fade",300);
					}
				  }
			});


			if(!form.valid()){
				$.pfscrolltotop();
				return false;
			};
		});
	/* PROFILE UPDATE FUNCTION END */


	



	/* FEATURES  FUNCTION STARTED  */
		$.pf_get_modules_now = function(itemid){
			var postid = $('#pfupload_listingpid').val();

			$.ajax({
		    	beforeSend:function(){
		    		$('.pfsubmit-inner-features').pfLoadingOverlay({action:'show'});
		    		$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'pfget_featuresystem',
					id: itemid,
					postid:postid,
					lang: theme_scriptspfm.pfcurlang,
					security: theme_scriptspfm.pfget_featuresystem
				},
			}).done(function(obj) {

				

				if (obj.features == null || obj.features == '' || typeof obj.features == 'undefined') {
					$('.pfsubmit-inner-features').hide();
					$('.pfsubmit-inner-features-title').hide();
					$('#pfupload_itemtypes').rules('remove');
				} else {
					$('.pfsubmit-inner-features').html(obj.features);
					$('.pfsubmit-inner-features').show();
					$('.pfsubmit-inner-features-title').show();

					if ($('input[name="pointfinderfeaturecount"]').val() == 0) {
						$('.pfsubmit-inner-features').hide();
						$('.pfsubmit-inner-features-title').hide();
					}else{
						$('.pfsubmit-inner-features').show();
						$('.pfsubmit-inner-features-title').show();
					}

					$('.pfitemdetailcheckall').on('click',function(event) {
						$.each($('[name="pffeature[]"]'), function(index, val) {
							 $(this).attr('checked', true);
						});
					});

					$('.pfitemdetailuncheckall').on('click',function(event) {
						$.each($('[name="pffeature[]"]'), function(index, val) {
							 $(this).attr('checked', false);
						});
					});

					$.each($('[name="pffeature[]"]'),function(index, el) {
						var parent_element = $(this)
						var parent_val = parent_element.data('parent');
						var status_of_attr = parent_element.attr('checked');
						if (parent_val == "yes") {
							if (status_of_attr == 'checked') {
								var parent_id_val = parent_element.data('pid');
								var childs_of_this = $('.pffeature'+parent_id_val);
								$.each(childs_of_this, function(index, val) {
									$(this).prop('disabled', false);
								});
							}
						}
					});

					$('[name="pffeature[]"]').on('click', function(event) {

						var parent_element = $(this)
						var parent_val = parent_element.data('parent');
						var status_of_attr = parent_element.attr('checked');

						if (parent_val == "yes") {

							var child_val = parent_element.data('child');
							var childof_val = parent_element.data('childof');
							var parent_id_val = parent_element.data('pid');

							var childs_of_this = $('.pffeature'+parent_id_val);

							if (status_of_attr == 'checked') {
								$.each(childs_of_this, function(index, val) {
									$(this).prop('disabled', false);
									$(this).attr('checked', true);
								});
							}else{
								$.each(childs_of_this, function(index, val) {
									$(this).prop('disabled', true);
									$(this).attr('checked', false);
								});
							}

						}

					});
				}



				if (obj.itemtypes == null || obj.itemtypes == '' || typeof obj.itemtypes == 'undefined') {
					$('.pfsubmit-inner-sub-itype').hide();
				}else{
					$('.pfsubmit-inner.pfsubmit-inner-sub-itype').html(obj.itemtypes);
					$('.pfsubmit-inner-sub-itype').show();
				}



				if (obj.conditions == null || obj.conditions == '' || typeof obj.conditions == 'undefined') {
					$('.pfsubmit-inner-sub-conditions').hide();
				}else{
					$('.pfsubmit-inner.pfsubmit-inner-sub-conditions').html(obj.conditions);
					$('.pfsubmit-inner-sub-conditions').show();
				}

				if (obj.customfields == null || obj.customfields == '' || typeof obj.customfields == 'undefined') {
					$('.pfsubmit-inner-customfields').hide();
					$('.pfsubmit-inner-customfields-title').hide();
				}else{
					$('.pfsubmit-inner-customfields').html(obj.customfields);
					$('.pfsubmit-inner-customfields').show();
					$('.pfsubmit-inner-customfields-title').show();
				}

				if (obj.eventdetails == null || obj.eventdetails == '' || typeof obj.eventdetails == 'undefined') {
					$('.eventdetails-output-container').hide();
				}else{
					$('.eventdetails-output-container').html(obj.eventdetails);
					$('.eventdetails-output-container').show();
				}


				if (obj.customtabs == null || obj.customtabs == '' || typeof obj.customtabs == 'undefined') {
					$('.customtab-output-container').hide();
				}else{
					$('.customtab-output-container').html(obj.customtabs);
					$('.customtab-output-container').show();


					setTimeout(function(){
						if($('textarea[name="webbupointfinder_item_custombox1"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox1' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox1' );
							}
							$('textarea[name="webbupointfinder_item_custombox1"]').hide();

						}

						if($('textarea[name="webbupointfinder_item_custombox2"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox2' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox2' );
							}
							$('textarea[name="webbupointfinder_item_custombox2"]').hide();
						}

						if($('textarea[name="webbupointfinder_item_custombox3"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox3' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox3' );
							}
							$('textarea[name="webbupointfinder_item_custombox3"]').hide();
						}

						if($('textarea[name="webbupointfinder_item_custombox4"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox4' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox4' );
							}
							$('textarea[name="webbupointfinder_item_custombox4"]').hide();
						}

						if($('textarea[name="webbupointfinder_item_custombox5"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox5' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox5' );
							}
							$('textarea[name="webbupointfinder_item_custombox5"]').hide();
						}

						if($('textarea[name="webbupointfinder_item_custombox6"]').hasClass('textareaadv')){
							if ( typeof( tinyMCE) != "undefined" ) {
								tinyMCE.execCommand( 'mceRemoveEditor', true, 'webbupointfinder_item_custombox6' );
								tinyMCE.execCommand( 'mceAddEditor', true, 'webbupointfinder_item_custombox6' );
							}
							$('textarea[name="webbupointfinder_item_custombox6"]').hide();
						}
					},0);
				}



				if (obj.video == null || obj.video == '' || typeof obj.video == 'undefined') {
					$('.pfvideotab-output-container').hide();
				}else{
					$('.pfvideotab-output-container').html(obj.video);
					$('.pfvideotab-output-container').show();
				}



				if (obj.ohours == null || obj.ohours == '' || typeof obj.ohours == 'undefined') {
					$('.openinghourstab-output-container').hide();
				}else{
					$('.openinghourstab-output-container').html(obj.ohours);
					$('.openinghourstab-output-container').show();
				}


				$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'hide'});
				$('.pfsubmit-inner-features').pfLoadingOverlay({action:'hide'});

				if ($.pf_tablet_check() == false) {
					$('.pf-special-selectbox').each(function(index, el) {
						var pfplc = $(this).data('pf-plc');
						if(typeof pfplc == 'undefined'){pfplc = theme_scriptspf.pfselectboxtex;}
						if((
							!$('option:selected',this).attr('value') ||
							$('option:selected',this).attr('value') == '' 
							|| typeof $('option:selected',this).attr('value') == 'undefined' 
							|| $('option:selected',this).attr('value') == null
							) 
							&& (!$(this).attr('multiple') || typeof $(this).attr('multiple') == 'undefined' || $(this).attr('multiple') == false || $(this).attr('multiple') == 'false')
						){
							
							$(this).children('option:first').replaceWith("<option value='' selected='selected'>"+pfplc+"</option>");
						}else{
							$(this).children('option:first').replaceWith('<option value="">'+pfplc+'</option>');
						}
					});
				};

			}).complete(function(obj) {
			}).error(function(jqXHR,textStatus,errorThrown){
				console.log(errorThrown);
			});
		}
	/* FEATURES FUNCTION END  */


	/* ITEM ADD/UPDATE FUNCTION STARTED  */
		$("#pf-ajax-uploaditem-button").on("click touchstart",function(){

			var form = $("#pfuaprofileform");

			/*if($('#item_desc').hasClass('textarea')){
				tinyMCE.triggerSave();
			}*/
			if($('textarea[name="item_desc"]').hasClass('textarea')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="item_desc"]').html( tinyMCE.get('item_desc').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox1"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox1"]').html( tinyMCE.get('webbupointfinder_item_custombox1').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox2"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox2"]').html( tinyMCE.get('webbupointfinder_item_custombox2').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox3"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox3"]').html( tinyMCE.get('webbupointfinder_item_custombox3').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox4"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox4"]').html( tinyMCE.get('webbupointfinder_item_custombox4').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox5"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox5"]').html( tinyMCE.get('webbupointfinder_item_custombox5').getContent() );
				}
			}

			if($('textarea[name="webbupointfinder_item_custombox6"]').hasClass('textareaadv')){
				if ( typeof( tinyMCE) != "undefined" ) {
					$('textarea[name="webbupointfinder_item_custombox6"]').html( tinyMCE.get('webbupointfinder_item_custombox6').getContent() );
				}
			}

			form.validate();

			if(!form.valid()){
				/*Extra classes for image and listing type*/
					if ($('#pfupload_listingtypes').hasClass('pfnotvalid')) {
						$('.pfsubmit-inner-listingtype').addClass('pfnotvalid');
					}else{
						$('.pfsubmit-inner-listingtype').removeClass('pfnotvalid');
					};
					if ($('#pfupload_locations').hasClass('pfnotvalid')) {
						$('.pfsubmit-location-errc').addClass('pfnotvalid');
					}else{
						$('.pfsubmit-location-errc').removeClass('pfnotvalid');
					};
					if ($('.pfuploadimagesrc').hasClass('pfnotvalid')) {
						$('.pfitemimgcontainer').addClass('pfnotvalid');
					}else{
						$('.pfitemimgcontainer').removeClass('pfnotvalid');
					};
					if ($('#pfuploadfilesrc').hasClass('pfnotvalid')) {
						$('.pfitemfilecontainer').addClass('pfnotvalid');
					}else{
						$('.pfitemfilecontainer').removeClass('pfnotvalid');
					};

					if ($('#pfupload_sublistingtypes').hasClass('pfnotvalid')) {
						$('#s2id_pfupload_sublistingtypes input').addClass('pfnotvalid');
					}else{
						$('#s2id_pfupload_sublistingtypes input').removeClass('pfnotvalid');
					};

					if ($('#pfupload_subsublistingtypes').hasClass('pfnotvalid')) {
						$('#s2id_pfupload_subsublistingtypes input').addClass('pfnotvalid');
					}else{
						$('#s2id_pfupload_subsublistingtypes input').removeClass('pfnotvalid');
					};

					if ($('#pfupload_address').hasClass('pfnotvalid') || $('#pfupload_lat_coordinate').hasClass('pfnotvalid') || $('#pfupload_lng_coordinate').hasClass('pfnotvalid')) {
						$('.pfsubmit-address-errc').addClass('pfnotvalid');
						$('#pfupload_address').removeClass('pfnotvalid');
						$('#pfupload_lat_coordinate').removeClass('pfnotvalid');
						$('#pfupload_lng_coordinate').removeClass('pfnotvalid');
					}else{
						$('.pfsubmit-address-errc').removeClass('pfnotvalid');
					};


					if ($('#item_desc').hasClass('pfnotvalid')) {
						if ( typeof( tinyMCE) != "undefined" ) {
							tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#F0D7D7';
						}
					}else{
						if ( typeof( tinyMCE) != "undefined" ) {
							if($('#item_desc').hasClass('textarea')){
								tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#ffffff'
							}
						}
					};


				$.pfscrolltotop();
				return false;
			}else{
				$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwait);
				$("#pf-ajax-uploaditem-button").attr("disabled", true);
				//form.submit();
				if ($("#pf-ajax-uploaditem-button").data('edit') > 0) {
					$.pfOpenItemUpEditModal('open','edit',form.serialize());
				}else{
					$.pfOpenItemUpEditModal('open','upload',form.serialize());
				};

				return false;
			};
		});

		/*Delete Item*/
		$(".pf-itemdelete-link").on("click touchstart",function(){
			if (confirm(theme_scriptspfm.delmsg)) {
				$.pfOpenItemUpEditModal('open','delete',$(this).data('pid'));
			};
		});


		$.pfOpenItemUpEditModal = function(status,modalname,formdata) {
			$.pfdialogstatus = '';

		    if(status == 'open'){

		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});

	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};

	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_itemsystem',
		                'formtype': modalname,
		                'dt': formdata,
		                'lang': theme_scriptspfm.pfcurlang,
		                'security': theme_scriptspfm.pfget_itemsystem
		            },
		            success:function(data){

		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});

						if(obj.process == true){

							if(obj.processname == 'upload' || obj.processname == 'edit'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								if (obj.returnval.ppps != '') {

									if (obj.processname == 'edit' && obj.returnval.pppso == 1) {
										var otype = 1;
									}else{
										var otype = 0;
									}

									if (obj.returnval.ppps == 'paypal') {
										$.pfOpenPaymentModal('open','paypalrequest',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'pags'){
										$.pfOpenPaymentModal('open','pags',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'iyzico'){
										$.pfOpenPaymentModal('open','iyzico',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'ideal'){
										$.pfOpenPaymentModal('open','ideal',obj.returnval.post_id,obj.returnval.issuer,otype);
									}else if(obj.returnval.ppps == 'payu'){
										$.pfOpenPaymentModal('open','payu',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'robo'){
										$.pfOpenPaymentModal('open','robo',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 't2co'){
										$.pfOpenPaymentModal('open','t2co',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'payf'){
										$.pfOpenPaymentModal('open','payf',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'stripe'){
										$.pfOpenPaymentModal('open','creditcardstripe',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'bank'){
											setTimeout(function(){window.location = obj.returnval.pppsru;},2000);
									}else if(obj.returnval.ppps == 'free'){
										if (obj.processname == 'edit') {
											setTimeout(function(){window.location = obj.returnurl;},3500);
										}else{
											setTimeout(function(){window.location = obj.returnurl;},2000);
										};
									};
								}else{
									if (obj.processname == 'edit') {
										setTimeout(function(){window.location = obj.returnurl;},3500);
									}else{
										setTimeout(function(){window.location = obj.returnurl;},2000);
									};
								};
							}else if(obj.processname == 'delete'){

								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								setTimeout(function(){window.location = obj.returnurl;},2000);
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);

							$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex);
							$("#pf-ajax-uploaditem-button").attr("disabled", false);
						}

						$('.pf-overlay-close').on('click',function(){
							$.pfOpenMembershipModal('close');
						});


		            },
		            error: function (request, status, error) {

	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);

		            },
		            complete: function(){
	            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            	$('.pointfinder-dialog').center(true);
		            },
		        });

	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			    	closeOnEscape: false,
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				        $('.ui-widget-overlay').on('click',function(e) {
						    e.preventDefault();
						    return false;
						});
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* ITEM ADD/UPDATE FUNCTION END  */

	/* IMAGE AND FILE UPLOAD STARTED */

		/* Delete Photo */
		$('body').on("click", ".pf-delete-standartimg", function(){

			var deleting_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			if ($(this).data("pffeatured") == 'yes') {
				return alert(theme_scriptspfm.dashtext1);
			}else{
				if(confirm(theme_scriptspfm.dashtext2)){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_imagesystem',
							iid: deleting_item,
							id: post_id,
							process: 'd',
							security: theme_scriptspfm.pfget_imagesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listimages(post_id);

						$.drzoneuploadlimit = $.drzoneuploadlimit +1;
						$(".pfuploaddrzonenum").text($.drzoneuploadlimit);

						var myDropzone = Dropzone.forElement("div#pfdropzoneupload");
						myDropzone.options.maxFiles = $.drzoneuploadlimit;

					})

				}
			}


		    return false;
		});


		/* Change Cover Photo */
		$('body').on("click", ".pf-change-standartimg", function(){

			var changing_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			/*Send ajax*/
		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages(post_id);
			})

		    return false;
		});

		$.pfitemdetail_listimages = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					id: id,
					process: 'l',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedimages').html(obj);
			})
		};

		/* OLD Upload system */

		/* Delete Photo OLD */
		$('body').on("click", ".pf-delete-standartimg-old", function(){

			var deleting_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			if ($(this).data("pffeatured") == 'yes') {
				return alert("This is your cover photo and can not remove. Please change your cover photo first.");
			}else{
				if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_imagesystem',
							iid: deleting_item,
							id: post_id,
							oldup:1,
							process: 'd',
							security: theme_scriptspfm.pfget_imagesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listimages_old(post_id);

						$.pfuploadimagelimit = $.pfuploadimagelimit +1;
						$('.pfmaxtext').text($.pfuploadimagelimit);
						$('.pfuploadfeaturedimgupl-container').attr("data-imagelimit",$.pfuploadimagelimit);
						if ($.pfuploadimagelimit > 0) {
							$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
						}

					})

				}
			}


		    return false;
		});

		/* Delete Cover Image */
		$('body').on("click", ".pf-delete-coverimg-old", function(){

			var deleting_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");


			if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
				/*Send ajax*/
				$.ajax({
					beforeSend:function(){
			    		$('.pfuploadedcoverimages').pfLoadingOverlay({action:'show'});
			    	},
					url: theme_scriptspf.ajaxurl,
					type: 'POST',
					dataType: 'html',
					data: {
						action: 'pfget_imagesystem',
						iid: deleting_item,
						id: post_id,
						oldup:1,
						process: 'd2',
						security: theme_scriptspfm.pfget_imagesystem
					},
				})
				.done(function(obj) {
					$.pfitemdetail_listcoverimages_old(post_id);

					$.pfuploadcoverimagelimit = $.pfuploadcoverimagelimit +1;

					if ($.pfuploadcoverimagelimit > 0) {
						$('.pfuploadcoverimgupl-container').css('display','inline-block');
					}

				})

			}
		    return false;
		});

		/*List images - OLD*/
		$.pfitemdetail_listimages_old = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					id: id,
					oldup:1,
					process: 'l',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedimages').html(obj);
			})
		};

		/*List cover images - OLD*/
		$.pfitemdetail_listcoverimages_old = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedcoverimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					id: id,
					oldup:1,
					process: 'l2',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedcoverimages').html(obj);
			})
		};


		/* Change Cover Photo - OLD */
		$('body').on("click", ".pf-change-standartimg-old", function(){

			var changing_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			/*Send ajax*/
		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					oldup:1,
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages_old(post_id);
			})

		    return false;
		});


		/* FILE Upload system */

		/* Delete File */
		$('body').on("click", ".pf-delete-standartfile", function(){

			var deleting_item = $(this).data("pffileno");
			var post_id = $(this).data("pfpid");

			if ($(this).data("pffeatured") == 'yes') {
				return alert("This is your cover photo and can not remove. Please change your cover photo first.");
			}else{
				if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_filesystem',
							iid: deleting_item,
							id: post_id,
							process: 'd',
							security: theme_scriptspfm.pfget_filesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listfiles(post_id);

						$.pfuploadfilelimit = $.pfuploadfilelimit +1;
						$('.pfmaxtext2').text($.pfuploadfilelimit);
						$('.pfuploadfeaturedfileupl-container').attr("data-filesnewlimit",$.pfuploadfilelimit);
						if ($.pfuploadfilelimit > 0) {
							$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
						}

					})

				}
			}


		    return false;
		});

		/*List images */
		$.pfitemdetail_listfiles = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_filesystem',
					id: id,

					process: 'l',
					security: theme_scriptspfm.pfget_filesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedfiles').html(obj);
			})
		};


		/* Change Cover Photo  */
		$('body').on("click", ".pf-change-standartfile", function(){

			var changing_item = $(this).data("pffileno");
			var post_id = $(this).data("pfpid");

		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_filesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					security: theme_scriptspfm.pfget_filesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages_old(post_id);
			})

		    return false;
		});
	/* IMAGE AND FILE UPLOAD END */


	/* ON-OFF SYSTEM STARTED */
		$("body").on('click touchstart',".pfstatusbuttonaction",function(event) {
			var pid = $(this).data('pfid');
			var atext = $(this).data('pf-active');
			var dtext = $(this).data('pf-deactive');
			var thisitem = $(this);

			$.ajax({
			beforeSend:function(){
				$('.pfmu-itemlisting-inner'+pid).pfLoadingOverlay({action:'show'});
			},
	        type: 'POST',
	        dataType: 'json',
	        url: theme_scriptspf.ajaxurl,
	        data: {
	            'action': 'pfget_onoffsystem',
	            'itemid': pid,
	            'lang': theme_scriptspfm.pfcurlang,
	            'security': theme_scriptspfm.pfget_onoffsystem
	        }
	    	})
			.done(function(obj) {

				if (obj == 1) {
					thisitem.switchClass('pfstatusbuttonactive','pfstatusbuttondeactive');
					thisitem.attr('title', dtext);
					$('.pfmu-itemlisting-inner-overlay'+pid).css('display','block');
				}else{
					thisitem.switchClass('pfstatusbuttondeactive','pfstatusbuttonactive');
					thisitem.attr('title', atext);
					$('.pfmu-itemlisting-inner-overlay'+pid).css('display','none');
				};



				$('.pfmu-itemlisting-inner'+pid).pfLoadingOverlay({action:'hide'});
			})
		});
	/* ON-OFF SYSTEM STARTED */


	$(function(){
		
		if ($('#pfitempagestreetviewMap').length > 0) {
			$.pfstmapgenerate();
		}

		$("#pf_search_geolocateme").on("click",function(){
			$(".pf-search-locatemebut").hide("fast");
			$(".pf-search-locatemebutloading").show("fast");
			$.pfgeolocation_findme('pfupload_address',$('#pfupload_map').data('geoctype'));
		});

		$.each($('.pf-listing-item-inner-addpinfo'), function(index, val) {
			 if ($(this).html() == '') {
			 	$(this).hide();
			 }else{
			 	$(this).fadeIn();
			 }
		});

		if($.pf_tablet2_check() && theme_scriptspf.ttstatus == 1){
  			$('.pf-dash-pinfo-col .pf-dash-packageinfo-tableex').tooltip(
  				{
				  position: { 
				  	at: 'right-10 center-20',
				  	collision: "none",
				  	using: function( position, feedback ) {
						$( this ).css( position );
						$( this.firstChild )
						.addClass( "pointfinderarrow_box" )
						.addClass( "wpfquick-tooltip" );

						if (feedback.important == 'horizontal') {
							$( this.firstChild )
							.addClass( feedback.horizontal );
						} else {
							$( this.firstChild )
							.addClass( feedback.vertical );
						}
			        }
				  },
				  show: {effect: "blind", duration: 800},
				  hide: {effect: "blind"}
				}
  			);
  		}


		$('.pf-dash-usernamef').css('left', (($('.pf-dash-userprof').width()/2)-(($('.pf-dash-usernamef').outerWidth()+10)/2)));
		
		var nonhoverbg = $('.pf-dash-userprof').css('background-color');

		$('.pf-dash-userprof2.pf-dash-bank-info').one('mouseenter mouseleave', function(event) {
			$(this).css('background-color',nonhoverbg);
		});
		$('.pf-dash-userprof2.pf-dash-notempty').one('mouseenter mouseleave', function(event) {
			$(this).css('background-color',nonhoverbg);
		});
		$('.pf-dash-userprof2.pf-dash-userlimits').one('mouseenter mouseleave', function(event) {
			$(this).css('background-color',nonhoverbg);
		});
		$('body').on('click', '#mceu_12', function(event) {wpLink.open();return false;});
	
		
		/* MOBILEDROPDOWNS  FUNCTION STARTED  */
		if (theme_scriptspf.mobiledropdowns == 1 && !$.pf_tablet_check()) {
			$("#pfupload_itemtypes").select2("destroy");
			$("#pfupload_sublistingtypes").select2("destroy");
			$("#pfupload_subsublistingtypes").select2("destroy");
			$("#pflocationselector").select2("destroy");
			$("#pfupload_locations").select2("destroy");
		};
		/* MOBILEDROPDOWNS  FUNCTION END  */

	    /* OLD IMAGE UPLOAD FUNCTION STARTED  */
		    if ($('.pfuploadfeaturedimgupl-container').length > 0) {
		    	$.pfuploadimagelimit = parseInt($('.pfuploadfeaturedimgupl-container').data("imagelimit"));

		    	if ($('.pfuploadfeaturedimgupl-container').data("formtype") == 'edititem') {

		    		$.pfitemdetail_listimages_old($('.pfuploadfeaturedimgupl-container').data("editid"));

		    		if ($.pfuploadimagelimit <= 0) {
						$('.pfuploadfeaturedimg-container').css('display','none');
						$('.pfuploadfeaturedimgupl-container').css('display','none');
					}

					if ($.pfuploadimagelimit <= parseInt($('.pfuploadfeaturedimgupl-container').data("pfimageuploadimit"))){
						$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
					}

					if ($.pfuploadimagelimit == 0) {
						$('.pfuploadfeaturedimgupl-container').css('display','none');
					}
		    	}


		    	/*Image upload featured image AJAX */
				var FeaturedfileInput = new mOxie.FileInput({
		            browse_button: document.getElementById('pffeaturedimageuploadfilepicker'),
		            accept: [{title: 'Image files', extensions: 'jpg,gif,png'}],
		            multiple: true
		        });


		        $.pfuploadedfilecount = 0;

		        var pfuploadoldimages = function(){
		  			console.log('Upload File Limit:'+$.pfuploadimagelimit);
		  			console.log('Upload File Count:'+$.pfuploadedfilecount);
					if ($.pfuploadimagelimit > 0 && $.pfuploadimagelimit >= $.pfuploadedfilecount) {
						

						FeaturedfileInput.files.forEach(function(item, index){

							$('.pfitemimgcontainer').pfLoadingOverlay({action:'show',message: $(".pfuploadfeaturedimgupl-container").data("mes1")+(index+1)});

				            var formData = new mOxie.FormData();
				            formData.append('action','pfget_imageupload');
						    formData.append('security',$('.pfuploadfeaturedimgupl-container').data("nonceimgup"));
						    formData.append('oldup',1);
						    formData.append('pfuploadfeaturedimg', FeaturedfileInput.files[index]);
						
						    var featured_xhr = new mOxie.XMLHttpRequest();
						    featured_xhr.open('POST', theme_scriptspf.ajaxurl, true);
						    featured_xhr.responseType = 'text';
						    featured_xhr.send(formData);
						

							var clearfeaturedinterval = function(){
						    	clearInterval(featureimgint);

						    	$.pfuploadedfilecount = $.pfuploadedfilecount + 1;
								$.pfuploadimagelimit = $.pfuploadimagelimit - 1;

						    	if (FeaturedfileInput.files.length > 0) {
							    	if ($.pfuploadimagelimit > 0) {
							    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
							    	}else{
							    		$('.pfuploadfeaturedimgupl-container').css('display','none');
							    	}
									$('.pfuploadfeaturedimg-container').css('display','inline-block');
									$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
							    }

							    if ($.pfuploadimagelimit > 0) {
						    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
						    	}else{
						    		$('.pfuploadfeaturedimgupl-container').css('display','none');
						    	}

							    $('.pfmaxtext').text($.pfuploadimagelimit);
						    }

						    var featureimgint = setInterval(function(){
						    	if (featured_xhr.readyState == 4) {
						    		var obj = featured_xhr.response;
						    		obj = $.parseJSON(obj)

						    		if (obj.process == 'up') {
						    	
										var uploadedimages = $('#pfuploadimagesrc').val();
										if (uploadedimages.length > 0) {
											uploadedimages = uploadedimages+','+obj.id;
											$('#pfuploadimagesrc').val(uploadedimages);
										}else{
											$('#pfuploadimagesrc').val(obj.id);
										}
									}
									clearfeaturedinterval();
						    	}
						    }, 1000);

					    });
					}else{
						if ($.pfuploadimagelimit > 0) {
				    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
				    	}else{
				    		$('.pfuploadfeaturedimgupl-container').css('display','none');
				    	}
						$('.pfuploadfeaturedimg-container').css('display','inline-block');
						$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
						$.pfuploadedfilecount = 0;
					};
		        };

		        FeaturedfileInput.onchange = function(e) {

		        	if (FeaturedfileInput.files.length) {
		        		
		        		FeaturedfileInput.files.forEach(function(item, index){
		        			if(item.size > (1000000*parseInt($('.pfuploadfeaturedimgupl-container').data("imagesizelimit")))){
		        				FeaturedfileInput.files.splice(index,1);
		        			}
	        			});
		        		if (FeaturedfileInput.files.length) {
		        			pfuploadoldimages();
		        		}
		       		}
		        };


		        FeaturedfileInput.init();

			/* Remove Featured Image Ajax */
				$('body').on('click touchstart', '#pfuploadfeaturedimg_remove', function(){

					$('.pfitemimgcontainer').pfLoadingOverlay({action:'show',message: $('.pfuploadfeaturedimgupl-container').data("mes2")});

				    var formData = new mOxie.FormData();
		            formData.append('action','pfget_imageupload');
				    formData.append('security',$('.pfuploadfeaturedimgupl-container').data("nonceimgup"));
				    formData.append('oldup',1);
				    formData.append('exid', $('#pfuploadimagesrc').val());

				    var remove_xhr = new mOxie.XMLHttpRequest();
				    remove_xhr.open('POST', theme_scriptspf.ajaxurl, true);
				    remove_xhr.responseType = 'text';
				    remove_xhr.send(formData);
				    var clearfeaturedinterval = function(){
				    	clearInterval(removefeaturedimg);
				    }
				    var removefeaturedimg = setInterval(function(){
				    	if (remove_xhr.readyState == 4) {
				    		var obj = remove_xhr.response;
				    		obj = $.parseJSON(obj)

				    		if (obj.process == 'del') {
								$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
								$('.pfuploadfeaturedimg-container').css('display','none');
								$.pfuploadimagelimit = $('.pfuploadfeaturedimgupl-container').data("imagelimit");
								$('.pfmaxtext').text($.pfuploadimagelimit);
							}
							$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
							clearfeaturedinterval();
				    	}
				    	$('#pfuploadimagesrc').val('');
				    	$.pfuploadedfilecount = 0;

				    }, 1000);
				});

		    }
	    /* OLD IMAGE UPLOAD FUNCTION END  */



	    /* Attachment UPLOAD FUNCTION STARTED  */
	    	if ($('.pfuploadfeaturedfileupl-container').length > 0) {
	    		$.pfuploadfilelimit = parseInt($('.pfuploadfeaturedfileupl-container').data("filesnewlimit"));

	    		if ($('.pfuploadfeaturedfileupl-container').data("formtype") == 'edititem') {

	    			$.pfitemdetail_listfiles($('.pfuploadfeaturedfileupl-container').data("editid"));

	    			if ($.pfuploadfilelimit <= 0) {
						$('.pfuploadfeaturedfile-container').css('display','none');
						$('.pfuploadfeaturedfileupl-container').css('display','none');
					}

					if ($.pfuploadfilelimit <= parseInt($('.pfuploadfeaturedfileupl-container').data("pffileuploadlimit"))){
						$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
					}

					if ($.pfuploadfilelimit == 0) {
						$('.pfuploadfeaturedfileupl-container').css('display','none');
					}
	    		}


	    		/*File upload featured image AJAX */
				var PFfileInput = new mOxie.FileInput({
		            browse_button: document.getElementById('pffeaturedfileuploadfilepicker'),
		            accept: [{ title: 'Documents', extensions: ''+$('.pfuploadfeaturedfileupl-container').data("allowed")+'' }],
		            multiple: true
		        });




		        $.pfuploadedfilecount = 0;

		        var pfuploadoldfiles = function(){
		        	
					if ($.pfuploadfilelimit > 0 && $.pfuploadfilelimit >= $.pfuploadedfilecount) {
						
						PFfileInput.files.forEach(function(item, index){
							$('.pfitemfilecontainer').pfLoadingOverlay({action:'show',message: $('.pfuploadfeaturedfileupl-container').data("mes1")+(index+1)});
				            var fileformData = new mOxie.FormData();
				            fileformData.append('action','pfget_fileupload');
						    fileformData.append('security',$('.pfuploadfeaturedfileupl-container').data("nonceimgup"));
						    fileformData.append('oldup',1);
						    fileformData.append('pfuploadfeaturedfile', PFfileInput.files[index]);

						    var featuredfile_xhr = new mOxie.XMLHttpRequest();
						    featuredfile_xhr.open('POST', theme_scriptspf.ajaxurl, true);
						    featuredfile_xhr.responseType = 'text';
						    featuredfile_xhr.send(fileformData);

						    var clearfeaturedfileinterval = function(){
						    	
						    	clearInterval(featuredfileint);

						    	$.pfuploadedfilecount = $.pfuploadedfilecount + 1;
								$.pfuploadfilelimit = $.pfuploadfilelimit - 1;

						    	if (PFfileInput.files.length > 0) {
							    	if ($.pfuploadfilelimit > 0) {
							    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
							    	}else{
							    		$('.pfuploadfeaturedfileupl-container').css('display','none');
							    	}
									$('.pfuploadfeaturedfile-container').css('display','inline-block');
									$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
							    }

							    if ($.pfuploadfilelimit > 0) {
						    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
						    	}else{
						    		$('.pfuploadfeaturedfileupl-container').css('display','none');
						    	}

							    $('.pfmaxtext2').text($.pfuploadfilelimit);
						    }

						    var featuredfileint = setInterval(function(){
						    	
						    	if (featuredfile_xhr.readyState == 4) {
						    		var obj = featuredfile_xhr.response;
						    		obj = $.parseJSON(obj)
						    			
							    		if (obj.process == 'up') {

											var uploadedfiles = $('#pfuploadfilesrc').val();
											if (uploadedfiles.length > 0) {
												uploadedfiles = uploadedfiles+','+obj.id;
												$('#pfuploadfilesrc').val(uploadedfiles);
											}else{
												$('#pfuploadfilesrc').val(obj.id);
											}
										}
									clearfeaturedfileinterval();
						    	}
						    }, 1000);
					 	});    
					}else{
						if ($.pfuploadfilelimit > 0) {
				    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
				    	}else{
				    		$('.pfuploadfeaturedfileupl-container').css('display','none');
				    	}
						$('.pfuploadfeaturedfile-container').css('display','inline-block');
						$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
						$.pfuploadedfilecount = 0;
					};
		        };

		        PFfileInput.onchange = function(e) {
		       		if (PFfileInput.files.length) {
		        		PFfileInput.files.forEach(function(item, index){
		        			if(item.size > (1000000*parseInt($('.pfuploadfeaturedfileupl-container').data("filesizelimit")))){
		        				PFfileInput.files.splice(index,1);
		        			}
	        			});
	        			if (PFfileInput.files.length) {
		        			pfuploadoldfiles();
		        		}
		       		}
		        };


		        PFfileInput.init();

			/* Remove Featured Files Ajax */
				$('body').on('click touchstart', '#pfuploadfeaturedfile_remove', function(){

					$('.pfitemfilecontainer').pfLoadingOverlay({action:'show',message: $('.pfuploadfeaturedfileupl-container').data("mes2")});

				    var formData = new mOxie.FormData();
		            formData.append('action','pfget_fileupload');
				    formData.append('security',$('.pfuploadfeaturedfileupl-container').data("nonceimgup"));
				    formData.append('oldup',1);
				    formData.append('exid', $('#pfuploadfilesrc').val());

				    var remove_xhr = new mOxie.XMLHttpRequest();
				    remove_xhr.open('POST', theme_scriptspf.ajaxurl, true);
				    remove_xhr.responseType = 'text';
				    remove_xhr.send(formData);
				    var clearfeaturedfileinterval = function(){
				    	clearInterval(removefileimg);
				    }
				    var removefileimg = setInterval(function(){
				    	if (remove_xhr.readyState == 4) {
				    		var obj = remove_xhr.response;
				    		obj = $.parseJSON(obj)

				    		if (obj.process == 'del') {
								$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
								$('.pfuploadfeaturedfile-container').css('display','none');
								$.pfuploadfilelimit = $('.pfuploadfeaturedfileupl-container').data("filesnewlimit");
								$('.pfmaxtext2').text($.pfuploadfilelimit);
							}
							$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
							clearfeaturedfileinterval();
				    	}
				    	$('#pfuploadfilesrc').val('');
				    	$.pfuploadedfilecount = 0;

				    }, 1000);
				});

	    	}
	    /* Attachment UPLOAD FUNCTION END  */


	    /* Header Image UPLOAD FUNCTION STARTED  */

		    if ($('.pfuploadcoverimgupl-container').length > 0) {

		    	$.pfuploadcoverimagelimit = parseInt($('.pfuploadcoverimgupl-container').data("imagesnewlimit"));

		    	if ($('.pfuploadcoverimgupl-container').data("formtype") == 'edititem') {

		    		$.pfitemdetail_listcoverimages_old(parseInt($('.pfuploadcoverimgupl-container').data("editid")));
		    		if ($.pfuploadcoverimagelimit <= 0) {
						$('.pfuploadcoverimg-container').css('display','none');
						$('.pfuploadcoverimgupl-container').css('display','none');
						$('.pfsubmit-inner-sub > small').css('display','none');
					}

					if ($.pfuploadcoverimagelimit <= parseInt($('.pfuploadcoverimgupl-container').data("imagesnewlimit"))){
						$('.pfuploadcoverimgupl-container').css('display','inline-block');
					}

					if ($.pfuploadcoverimagelimit == 0) {
						$('.pfuploadcoverimgupl-container').css('display','none');
					}
		    	}

		    	/*Image upload featured image AJAX */
				var CoverfileInput = new mOxie.FileInput({
		            browse_button: document.getElementById('pfcoverimageuploadfilepicker'),
		            accept: [{title: 'Image files', extensions: 'jpg,gif,png'}],
		            multiple: false
		        });

		        $.pfuploadedcoverfilecount = 0;
				
		        var pfuploadcoverimages = function(){

					if ($.pfuploadcoverimagelimit > 0 && $.pfuploadedcoverfilecount < CoverfileInput.files.length) {

						CoverfileInput.files.forEach(function(item, index){
							$('.pfitemcoverimgcontainer').pfLoadingOverlay({action:'show',message: $('.pfuploadcoverimgupl-container').data("mes1")+(index+1)});
				            var formDataCoverimg = new mOxie.FormData();
				            formDataCoverimg.append('action','pfget_imageupload');
						    formDataCoverimg.append('security',$('.pfuploadcoverimgupl-container').data("nonceimgup"));
						    formDataCoverimg.append('oldup',1);
						    formDataCoverimg.append('cover',1);
						    formDataCoverimg.append('pfuploadcoverimg', CoverfileInput.files[index]);

						    var featured_xhr_cover = new mOxie.XMLHttpRequest();
						    featured_xhr_cover.open('POST', theme_scriptspf.ajaxurl, true);
						    featured_xhr_cover.responseType = 'text';
						    featured_xhr_cover.send(formDataCoverimg);

						    var clearcoversinterval = function(){

						    	clearInterval(coverfimgint);

						    	$.pfuploadedcoverfilecount = $.pfuploadedcoverfilecount + 1;
								$.pfuploadcoverimagelimit = $.pfuploadcoverimagelimit - 1;

						    	if ($.pfuploadedcoverfilecount == CoverfileInput.files.length) {
							    	if ($.pfuploadcoverimagelimit > 0) {
							    		$('.pfuploadcoverimgupl-container').css('display','inline-block');
							    	}else{
							    		$('.pfuploadcoverimgupl-container').css('display','none');
							    	}
									$('.pfuploadcoverimg-container').css('display','inline-block');
									$('.pfitemcoverimgcontainer').pfLoadingOverlay({action:'hide'});
							    }

							    if ($.pfuploadcoverimagelimit > 0) {
						    		$('.pfuploadcoverimgupl-container').css('display','inline-block');
						    	}else{
						    		$('.pfuploadcoverimgupl-container').css('display','none');
						    	}

							    $('.pfmaxtext').text($.pfuploadcoverimagelimit);
						    }

						    var coverfimgint = setInterval(function(){
						    	if (featured_xhr_cover.readyState == 4) {
						    		var obj = featured_xhr_cover.response;
						    		obj = $.parseJSON(obj)

							    		if (obj.process == 'up') {

											var uploadedimages = $('#pfuploadcovimagesrc').val();
											if (uploadedimages.length > 0) {
												uploadedimages = uploadedimages+','+obj.id;
												$('#pfuploadcovimagesrc').val(uploadedimages);
											}else{
												$('#pfuploadcovimagesrc').val(obj.id);
											}
										}
									clearcoversinterval();
						    	}
						    }, 1000);
						});
					}else{
						if ($.pfuploadcoverimagelimit > 0) {
				    		$('.pfuploadcoverimgupl-container').css('display','inline-block');
				    	}else{
				    		$('.pfuploadcoverimgupl-container').css('display','none');
				    	}
						$('.pfuploadcoverimg-container').css('display','inline-block');
						$('.pfitemcoverimgcontainer').pfLoadingOverlay({action:'hide'});
						$.pfuploadedcoverfilecount = 0;
					};
		        };

		        CoverfileInput.onchange = function(e) {

		       		if (CoverfileInput.files.length) {
		        		
		        		CoverfileInput.files.forEach(function(item, index){
		        			if(item.size > (1000000*parseInt($('.pfuploadcoverimgupl-container').data("imagesizelimit")))){
		        				CoverfileInput.files.splice(index,1);
		        			}
	        			});
	        			if (CoverfileInput.files.length) {
		        			pfuploadcoverimages();
		        		}
		       		}
		        };


		        CoverfileInput.init();

			/* Remove Featured Image Ajax */
				$('body').on('click touchstart', '#pfuploadcoverimg_remove', function(){

					$('.pfitemcoverimgcontainer').pfLoadingOverlay({action:'show',message: $('.pfuploadcoverimgupl-container').data("mes2")});

				    var formDataCover = new mOxie.FormData();
		            formDataCover.append('action','pfget_imageupload');
				    formDataCover.append('security',$('.pfuploadcoverimgupl-container').data("nonceimgup"));
				    formDataCover.append('oldup',1);
				    formDataCover.append('exid', $('#pfuploadcovimagesrc').val());

				    var remove_xhr_cover = new mOxie.XMLHttpRequest();
				    remove_xhr_cover.open('POST', theme_scriptspf.ajaxurl, true);
				    remove_xhr_cover.responseType = 'text';
				    remove_xhr_cover.send(formDataCover);
				    var clearcoversinterval = function(){
				    	clearInterval(removecoverimg);
				    }
				    var removecoverimg = setInterval(function(){
				    	if (remove_xhr_cover.readyState == 4) {

				    		var obj = remove_xhr_cover.response;
				    		obj = $.parseJSON(obj)

				    		if (obj.process == 'del') {
								$('.pfuploadcoverimgupl-container').css('display','inline-block');
								$('.pfuploadcoverimg-container').css('display','none');
								$.pfuploadcoverimagelimit = ".$images_newlimit.";
							}
							$('.pfitemcoverimgcontainer').pfLoadingOverlay({action:'hide'});
							clearcoversinterval();
				    	}
				    	$('#pfuploadcovimagesrc').val('');
				    	$.pfuploadedcoverfilecount = 0;

				    }, 1000);
				});
		    }

	    /* Header Image UPLOAD FUNCTION END  */


	    $.typeahead({
		    input: "#pfupload_address",
		    minLength: 3,
		    accent: true,
		    dynamic:true,
		    compression: false,
		    cache: false,
			ttl: 86400000,
			hint: false,
			loadingAnimation: true,
			cancelButton: true,
			debug: true,
			searchOnFocus: false,
			delay: 1000,
			group: false,
			filter: false,
			maxItem: 10,
			maxItemPerGroup: 10,
			emptyTemplate: $('.we-change-addr-input-upl').data('text1'),
			template: "{{address}}",
			templateValue: "{{address}}",
			selector: {
		        cancelButton: "typeahead__cancel-button2"
		    },
		    source: {
		        "found": {
		          ajax: {
		          	type: "GET",
		              url: theme_scriptspf.ajaxurl,
		              dataType: "json",
		              path: "data.found",
		              data: {
		              	action: "pfget_geocoding",
		              	security: theme_scriptspf.pfget_geocoding,
		              	q: "{{query}}",
		              	option: "geocode",
		              	ctype: $('#pfupload_map').data('geoctype')
		              }
		          }
		        }
		    },
		    callback: {
		    	onLayoutBuiltAfter:function(){
					$(".we-change-addr-input-upl").find(".typeahead__list").css("width",$("#pfupload_address").outerWidth());
					$(".we-change-addr-input-upl").find(".typeahead__result").css("width",$("#pfupload_address").outerWidth());
					$(".we-change-addr-input-upl ul.typeahead__list").css("min-width",$(".we-change-addr-input-upl .typeahead__field").outerWidth());
		    	},
		    	onClickBefore: function(){
		    		
					$(".typeahead__container.we-change-addr-input-upl .typeahead__field input").css("padding-right","66px");
	    		},
				onClickAfter: function(node, a, item, event){
					event.preventDefault();
					
					$("#pfupload_address").val(item.address);

					if($('#pfupload_map').data('geoctype') == "google"){
													
						var sessiontoken = item.lng;
						var place_id = item.lat
						$.ajax({
					    	url: theme_scriptspf.ajaxurl,
					    	type: "GET",
					    	dataType: "JSON",
					    	data: {action: "pfget_geocodingx",security: theme_scriptspf.pfget_geocoding,sessiontoken:sessiontoken,place_id:place_id},
					    })
					    .done(function(data) {
					    	
					    	$('#pfupload_lat_coordinate').val(data.result.geometry.location.lat);
			    			$('#pfupload_lng_coordinate').val(data.result.geometry.location.lng);

			    			$.pointfinderuploadmarker.setLatLng(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng))
			    			$.pointfinderuploadmapsys.panTo(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng));

							if ($('#pfitempagestreetviewMap').length > 0) {
				    			$('#pfitempagestreetviewMap').data('pfcoordinateslat',data.result.geometry.location.lat);
								$('#pfitempagestreetviewMap').data('pfcoordinateslng',data.result.geometry.location.lng);
								$.pfstmapregenerate(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng));
				    		}
					    });
						

					}else{
						$('#pfupload_lat_coordinate').val(item.lat);
		    			$('#pfupload_lng_coordinate').val(item.lng);

		    			$.pointfinderuploadmarker.setLatLng(L.latLng(item.lat, item.lng))
		    			$.pointfinderuploadmapsys.panTo(L.latLng(item.lat, item.lng));

						if ($('#pfitempagestreetviewMap').length > 0) {
			    			$('#pfitempagestreetviewMap').data('pfcoordinateslat',item.lat);
							$('#pfitempagestreetviewMap').data('pfcoordinateslng',item.lng);
							$.pfstmapregenerate(L.latLng(item.lat, item.lng));
		    			}
					}

					

					$(".typeahead__cancel-button2").css("visibility","visible");
				},
				onCancel: function(node,event){
					$(".typeahead__cancel-button2").css("visibility","hidden");
					$("#pfupload_address").val("");
	        	}
		    }
		});

     })

})(jQuery);
