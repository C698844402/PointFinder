function number_format(e,t,n,r){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var i=!isFinite(+e)?0:+e,s=!isFinite(+t)?0:Math.abs(t),o=typeof r==="undefined"?",":r,u=typeof n==="undefined"?".":n,a="",f=function(e,t){var n=Math.pow(10,t);return""+(Math.round(e*n)/n).toFixed(t)};a=(s?f(i,s):""+Math.round(i)).split(".");if(a[0].length>3){a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)}if((a[1]||"").length<s){a[1]=a[1]||"";a[1]+=(new Array(s-a[1].length+1)).join("0")}return a.join(u)}
jQuery.fn.center=function(absolute){return this.each(function(){var t=jQuery(this);t.css({position:absolute?'absolute':'fixed',left:'50%',top:'50%',zIndex:'99'}).css({marginLeft:'-'+(t.outerWidth()/2)+'px',marginTop:'-'+(t.outerHeight()/2)+'px'});if(absolute){t.css({marginTop:parseInt(t.css('marginTop'),10)+jQuery(window).scrollTop(),marginLeft:parseInt(t.css('marginLeft'),10)+jQuery(window).scrollLeft()})}})};
function pointfinder_numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;
else if ((("0123456789").indexOf(keychar) > -1))
   return true;
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
      return typeof args[number] != "undefined"
        ? args[number]
        : match
      ;
    });
  };
}

(function($) {
  "use strict";
  	

  	$('body').on('click', '.pfquickview', function(event) {
  		event.preventDefault();
  		$.pfOpenModal('open','quickpreview','','',$(this).attr('data-pfitemid'));
  	});
  	$('body').on('click', '.pfquickclose', function(event) {
  		event.preventDefault();
  		$.pfOpenModal('close');
  	});


  	$('body').on('click', '.pfclicktoshowphone', function(event) {
  		event.preventDefault();
  		var link = $(this).parent().find('a[rel="nofollow"]');
  		var content2 = link.data('mx');
  		var content1 = link.data('mxe');
  		var lcontent = link.data('mxt');
  		var icontent = link.data('mxi');
  		$(this).remove();
  		link.attr('href', lcontent+'://'+content1+content2);
  		link.html('<i class="'+icontent+'"></i> '+content1+content2);

  	});

  	SVGInject(document.querySelector("img.injectable"));
  	
  	$.fn.reverse = [].reverse;

  	if ($("#commentform #submit").length > 0) {
  		$("#commentform #submit").addClass('button');
  	}
  	
	$('#pfpostitemlink a').on('click', function(event) {
		if (theme_scriptspf.userlog != 1) {
			$.pfOpenLogin('open','login','','',2);
			return false;
		};

	});

	
	if (/Edge\/12./i.test(navigator.userAgent)){
	   $('body').addClass('edge-browser')
	}

	$(function(){

		if ($(".pointfinder-terms-archive").length>0) {

			$(".pointfinder-terms-archive").isotope({
				  	layoutMode: "fitRows",
				    itemSelector: ".pf-grid-item",
				    percentPosition: false
				  });

		}
		$('body').on('click','.pfButtons a',function() {
			if($(this).attr('data-pf-link')){
				$.magnificPopup.open({
				  items: {
				    src: ''+$(this).attr('data-pf-link')+''
				  },
					  type: ''+$(this).attr('data-pf-type')+''
				});
			}
		});
		$(".pftermshortc2").magnificPopup({
			type: "ajax",
			overflowY: "scroll"
		});

		$(".pftermshortc3").magnificPopup({
			type: "ajax",
			overflowY: "scroll"
		});
		
	})



/***************************************************************************************************************
*
*
* DIRECTORY LIST FUNCTIONS
*
*
***************************************************************************************************************/
	$('.pf-main-term a').mouseover(function(event) {
		$(this).css({
			color: $(this).data('hovercolor'),
		});
	});

	$('.pf-main-term a').mouseleave(function(event) {
		$(this).css({
			color: $(this).data('standartc'),
		});
	});

	$('.pf-child-term a').mouseover(function(event) {
		$(this).css({
			color: $(this).data('hovercolor'),
		});
	});

	$('.pf-child-term a').mouseleave(function(event) {
		$(this).css({
			color: $(this).data('standartc'),
		});
	});


/***************************************************************************************************************
*
*
* HORIZONTAL SEARCH WINDOW FUNCTIONS
*
*
***************************************************************************************************************/

	$('body').on('click touchstart', '.pfsopenclose', function(event) {
		$( "#pfsearch-draggable" ).toggle( "fade",{direction:"up",mode:"hide"},function(){
			$('.pfsopenclose').fadeToggle("fast");
			$('.pfsopenclose2').fadeToggle("fast");
		});
	});
	$('body').on('click touchstart', '.pfsopenclose2', function(event) {
		$('.pfsopenclose2').fadeToggle("fast");
		$( "#pfsearch-draggable" ).toggle( "fade",{direction:"up",mode:"show"},function(){
			$('.pfsopenclose').fadeToggle("fast");
		});
	});



/***************************************************************************************************************
*
*
* PF GET SUB ITEMS FOR SEARC
*
*
***************************************************************************************************************/
	$.PFGetSubItems = function(pfcat,pformvalues,widgetpf,horpf){
		if(pfcat != null && pfcat != 'undefined' && pfcat != ''){
			if (pfcat.length !== 0) {
				$.ajax({
					beforeSend: function(){
						$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
					},
		            type: 'POST',
		            dataType: 'html',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_searchitems',
		                'pfcat': pfcat,
		                'formvals': pformvalues,
		                'widget':widgetpf,
		                'hor':horpf,
		                'cl':theme_scriptspf.pfcurlang,
		                'security': theme_scriptspf.pfget_searchitems
		            },
		            success:function(data){
						$('#pfsearchsubvalues').html(data);
						$.pf_data_pfpcl_apply();

						if ($('.pfsearchresults-container').length > 0) {
							if ($('.pfsearchresults-container').data('sdata') != '') {
								$.each($('.pfsearchresults-container').data('sdata'), function(index, val) {
									 if (val != '') {
									 	if ($("[name=\""+index+"\"]").length > 0) {
									 		if ($("input[name=\""+index+"\"]").attr('type') == 'text') {
									 			$("input[name=\""+index+"\"]").val(val);
									 		}else if($("label[for=\""+index+"\"]").hasClass('select')){
									 			$("[name=\""+index+"\"] option[value=\""+val+"\"]").prop('selected', true);
									 			if ($("[name=\""+index+"\"]").is(":hidden")) {
									 				$("[name=\""+index+"\"]").select2("val",val);
									 			}
									 		}else if ($("input[name=\""+index+"\"]").attr('type') == 'hidden' && $("input[name=\""+index+"\"]").hasClass('pfignorevalidation') ) {
									 			if ($("#"+index+"").hasClass('pfrangeorj')) {
									 				var exp_val = val.split(",");
									 				$("#"+index+"").slider("values",[parseInt(exp_val[0]),parseInt(exp_val[1])]);
									 				$("#"+index+"-view2").val([parseInt(exp_val[0]),parseInt(exp_val[1])]);
									 				$("#"+index+"-view").val([parseInt(exp_val[0]),parseInt(exp_val[1])]);
									 				$("[name=\""+index+"\"]").val([parseInt(exp_val[0]),parseInt(exp_val[1])]);
									 			}else{
									 				$("#"+index+"").slider("value",val);
										 			$("#"+index+"-view").val(val);
										 			$("[name=\""+index+"\"]").val(val);
									 			}
									 		}
									 	}
									 }
								});
							}
						}
		            },
		            complete: function(){
		            	$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
		            },
		        });
			};
		}else{
			$('#pfsearchsubvalues').html('');
		};
	};


/***************************************************************************************************************
*
*
* PF RENEW FEATURES WHEN LISTING TYPE SELECTED
*
*
***************************************************************************************************************/
	$.PFRenewFeatures = function(pfcat){
		if(pfcat != null && pfcat != 'undefined' && pfcat != ''){
			if (pfcat.length !== 0) {


				var rq = [];
				var fieldids_features = $.pffieldsids.features;
				var fieldids_itemtypes = $.pffieldsids.itemtypes;
				var fieldids_conditions = $.pffieldsids.conditions;
				var fieldids_mit = $.pffieldsids.mit;
				var fieldids_mfe = $.pffieldsids.mfe;
				var fieldids_mco = $.pffieldsids.mco;


				if (fieldids_features != '' && fieldids_features != 'undefined' && fieldids_features != null) {
					rq.push("features");
				}

				if (fieldids_itemtypes != '' && fieldids_itemtypes != 'undefined' && fieldids_itemtypes != null) {
					rq.push("itypes");
				}

				if (fieldids_conditions != '' && fieldids_conditions != 'undefined' && fieldids_conditions != null) {
					rq.push("conditions");
				}

				$.ajax({
					beforeSend: function(){
						$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
					},
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_featuresfilter',
		                'pfcat': pfcat,
		                'rq': rq,
		                'cl':theme_scriptspf.pfcurlang,
		                'security': theme_scriptspf.pfget_searchitems
		            },
		            success:function(data){

		            	if (fieldids_features != '' && fieldids_features != 'undefined' && fieldids_features != null) {
		            		if (fieldids_mfe != '1') {
		            			var features_append = '<option></option>';
		            		}else{
		            			var features_append = '';
		            		}
		            		$('#'+fieldids_features)
						    .find('option')
						    .remove()
						    .end()
						    .append(features_append+data['features']);
						    $('#'+fieldids_features).val('').trigger("change");
		            	}


		            	if (fieldids_itemtypes != '' && fieldids_itemtypes != 'undefined' && fieldids_itemtypes != null) {
		            		if (fieldids_mit != '1') {
		            			var itemtype_append = '<option></option>';
		            		}else{
		            			var itemtype_append = '';
		            		}
		            		$('#'+fieldids_itemtypes)
						    .find('option')
						    .remove()
						    .end()
						    .append(itemtype_append+data['itypes']);
						    $('#'+fieldids_itemtypes).val('').trigger("change");


						    if (data['itypes'] == null) {
						    	$('#'+fieldids_itemtypes+'_main').hide();
						    }else{
						    	$('#'+fieldids_itemtypes+'_main').show();
						    }
		            	}

		            	if (fieldids_conditions != '' && fieldids_conditions != 'undefined' && fieldids_conditions != null) {
		            		if (fieldids_mco != '1') {
		            			var conditions_append = '<option></option>';
		            		}else{
		            			var conditions_append = '';
		            		}
		            		$('#'+fieldids_conditions)
						    .find('option')
						    .remove()
						    .end()
						    .append(conditions_append+data['conditions']);
						    $('#'+fieldids_conditions).val('').trigger("change");

						    if (data['conditions'] == null) {
						    	$('#'+fieldids_conditions+'_main').hide();
						    }else{
						    	$('#'+fieldids_conditions+'_main').show();
						    }
		            	}

		            },
		            complete: function(){
		            	$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
		            },
		        });
			};
		};
	};


/***************************************************************************************************************
*
*
* PF GENERAL FUNCTIONS & ACTIONS
*
*
***************************************************************************************************************/

	$.pfscrolltotop = function(){$.smoothScroll();};


	$('body').on('click', '.ui-tabs label', function(event) {
		$.smoothScroll({scrollTarget: $(this),offset: -110});
	});



	/*Fix for sharebar*/
	$('body').on('click', '.pf-sharebar-icons li a', function(){
	  	return false;
	});

	$.pf_mobile_check = function(){
		if ($(window).width() > 568) {return true;} else{return false;};
	}

	$.pf_tablet_check = function(){
		if ($(window).width() > 992) {return true;} else{return false;};
	}

	$.pf_tablet2_check = function(){
		if ($(window).width() > 1024) {return true;} else{return false;};
	}

	$.pf_data_pfpcl_apply = function(){
		if ($.pf_tablet_check() == false) {
			$(".pf-special-selectbox").each(function(index, el) {
				var dataplc = $(this).data('pf-plc');
				if (dataplc) {
					if((
						!$('option:selected',this).attr('value') ||
						$('option:selected',this).attr('value') == '' 
						|| typeof $('option:selected',this).attr('value') == 'undefined' 
						|| $('option:selected',this).attr('value') == null
						) 
						&& (!$(this).attr('multiple') || typeof $(this).attr('multiple') == 'undefined' || $(this).attr('multiple') == false || $(this).attr('multiple') == 'false')
					){
						$(this).children('option:first').replaceWith('<option value="" selected="selected">'+dataplc+'</option>');
					}else{
						$(this).children('option:first').replaceWith('<option value="">'+dataplc+'</option>');
					}
					
				}else{
					if(!$('option:selected',this).attr('value')){
						$(this).children('option:first').replaceWith('<option value="" selected="selected">'+theme_scriptspf.pfselectboxtex+'</option>');
					}else{
						$(this).children('option:first').replaceWith('<option value="">'+theme_scriptspf.pfselectboxtex+'</option>');
					}
					
				};
			});
		};
	}


	$(function() {
		

		/*Please select fix for mobile*/
			setTimeout(function(){
				$.pf_data_pfpcl_apply();
			},1000);

		$('.pfnot-err-button').on('click',function(){$.pftogglewnotificationclear();});


		$('body').on('click','#pfuaprofileform .pf-favorites-link',function(){
			if($(this).attr('data-pf-active') == 'true'){
				$(this).closest('.pfmu-itemlisting-inner').remove();
			};
		});

	});




/***************************************************************************************************************
*
*
* PF FAVORITES SYSTEM FUNCTIONS & ACTIONS
*
*
***************************************************************************************************************/
	$(function(){

		$('body').on('click','.pf-favorites-link',function(){
			$.maindivfav = $(this);
			$.ajax({
				beforeSend: function(){
					$.maindivfav.children('i').switchClass('fa-heart','fa-spinner fa-spin');
				},
	            type: 'POST',
	            dataType: 'json',
	            url: theme_scriptspf.ajaxurl,
	            data: {
	                'action': 'pfget_favorites',
	                'item': $.maindivfav.attr('data-pf-num'),
	                'active':$.maindivfav.attr('data-pf-active'),
	                'security': theme_scriptspf.pfget_favorites
	            },
	            success:function(data){
					var obj = [];
					$.each(data, function(index, element) {
						obj[index] = element;
					});

					if (!$.isEmptyObject(obj)) {

						if (obj.user == 0) {
							$.pfOpenLogin('open','login');
						}else{
							if (obj.active == 'true') {
								var datatextfv = 'true';
							}else{
								var datatextfv = 'false';
							};
							$.maindivfav.attr('data-pf-active',datatextfv);
							$.maindivfav.attr('title',obj.favtext);
							
							if ($.maindivfav.data('pf-item') == true) {
								$.maindivfav.children('#itempage-pffav-text').html(obj.favtext);
							};
						};
					};

	            },
	            complete:function(){
	            	$.maindivfav.children('i').removeClass('fa-spinner fa-spin').addClass('fa-heart');
	            }
	        });
		});

	});


/***************************************************************************************************************
*
*
* PF ITEM REPORT SYSTEM
*
*
***************************************************************************************************************/
	$(function(){

		$('body').on('click','.pf-report-link',function(){
			$.maindivfav = $(this);
			$.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: theme_scriptspf.ajaxurl,
	            data: {
	                'action': 'pfget_reportitem',
	                'item': $.maindivfav.attr('data-pf-num'),
	                'security': theme_scriptspf.pfget_reportitem
	            },
	            success:function(data){
					var obj = [];
					$.each(data, function(index, element) {
						obj[index] = element;
					});

					if (!$.isEmptyObject(obj)) {

						if (obj.user == 0 && obj.rs == "1") {
							$.pfOpenLogin('open','login');
						}else if(obj.user == 0 && obj.rs == "0"){
							$.pfOpenModal('open','reportform','','',obj.item);
						}else if(obj.user != 0 && obj.rs == "1"){
							$.pfOpenModal('open','reportform','','',obj.item);
						}else if(obj.user != 0 && obj.rs == "0"){
							$.pfOpenModal('open','reportform','','',obj.item);
						};

					};

	            }
	        });
		});

	});


/***************************************************************************************************************
*
*
* PF ITEM CLAIM SYSTEM
*
*
***************************************************************************************************************/
	$(function(){

		$('body').on('click touchstart','#pfclaimitem',function(){
			$.maindivfav = $(this);
			$.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: theme_scriptspf.ajaxurl,
	            data: {
	                'action': 'pfget_claimitem',
	                'item': $.maindivfav.attr('data-pf-num'),
	                'security': theme_scriptspf.pfget_claimitem
	            },
	            success:function(data){
					var obj = [];
					$.each(data, function(index, element) {
						obj[index] = element;
					});

					if (!$.isEmptyObject(obj)) {

						if (obj.user == 0 && obj.rs == "1") {
							$.pfOpenLogin('open','login');
						}else if(obj.user == 0 && obj.rs == "0"){
							$.pfOpenModal('open','claimform','','',obj.item);
						}else if(obj.user != 0 && obj.rs == "1"){
							$.pfOpenModal('open','claimform','','',obj.item);
						}else if(obj.user != 0 && obj.rs == "0"){
							$.pfOpenModal('open','claimform','','',obj.item);
						};

					};

	            }
	        });
		});

	});



/***************************************************************************************************************
*
*
* PF REVIEW SYSTEM FUNCTIONS & ACTIONS
*
*
***************************************************************************************************************/


	$.pfReviewwithAjax = function(vars){

		$.ajax({
			beforeSend: function(){
				$('#pftrwcontainer').pfLoadingOverlay({action:'show'});
			},
            type: 'POST',
            dataType: 'json',
            url: theme_scriptspf.ajaxurl,
            data: {
                'action': 'pfget_modalsystemhandler',
                'formtype': 'reviewform',
                'vars': vars,
                'security': theme_scriptspf.pfget_modalsystemhandler,
                'lang': theme_scriptspf.pfcurlang
            },
            success:function(data){
				var obj = [];
				$.each(data, function(index, element) {
					obj[index] = element;
				});

				// Review Form works ---------------------------------------------------
				var form = $('#pf-review-form');
				var pfreviewoverlay = $("#pftrwcontainer-overlay");

				if (typeof grecaptcha !== 'undefined') {
					$.reCAPTCHA_execute(form.selector.replace("#",""));
				};
				
				pfreviewoverlay.on('click',function(){
					pfreviewoverlay.hide("slide",{direction : "up"},100);
					pfreviewoverlay.find('.pf-overlay-close').remove();
					pfreviewoverlay.find('.pfrevoverlaytext').remove();
					if(obj.process == true){
						
						form.find("textarea").val("");
						form.find(':input').removeAttr('checked');
					}
				});
				if(obj.process == true){
					pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
					pfreviewoverlay.show("slide",{direction : "up"},100);
				}else{
					pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
					pfreviewoverlay.show("slide",{direction : "up"},100);
				}

				$('.pf-overlay-close').on('click',function(){
					pfreviewoverlay.hide("slide",{direction : "up"},100);
					pfreviewoverlay.find('.pf-overlay-close').remove();
					pfreviewoverlay.find('.pfrevoverlaytext').remove();
					if(obj.process == true){

						form.find("textarea").val("");
						form.find(':input').removeAttr('checked');
					}
				});
				// Review Form works ---------------------------------------------------


            },
            error: function (request, status, error) {
                $("#pftrwcontainer-overlay").append("<span class='pfrevoverlaytext'>Error:"+request.responseText+"</span>");
            },
            complete: function(){
            	$('#pftrwcontainer').pfLoadingOverlay({action:'hide'});
            },
        });

	};


	$('.pf-show-review-details').on( "mouseenter mouseleave",function(){

		if ($(this).find('.pf-itemrevtextdetails').is( ':visible' )) {
			$(this).find('.pf-itemrevtextdetails').hide();
			$(this).find('.pf-itemrevtextdetails').removeClass('animated bounceIn');
			$(this).find('.pf-itemrevtextdetails').addClass('animated bounceOut');
		}else{
			$(this).find('.pf-itemrevtextdetails').show();
			$(this).find('.pf-itemrevtextdetails').removeClass('animated bounceOut');
			$(this).find('.pf-itemrevtextdetails').addClass('animated bounceIn');
		};

	});


	$('body').on('click', '.review-flag-link', function(){
		$.maindivfav = $(this);
		$.ajax({
            type: 'POST',
            dataType: 'json',
            url: theme_scriptspf.ajaxurl,
            data: {
                'action': 'pfget_flagreview',
                'item': $.maindivfav.attr('data-pf-revid'),
                'security': theme_scriptspf.pfget_flagreview
            },
            success:function(data){
				var obj = [];
				$.each(data, function(index, element) {
					obj[index] = element;
				});

				if (!$.isEmptyObject(obj)){

					if (obj.user == 0 ) {
						$.pfOpenLogin('open','login');
					}else{
						$.pfOpenModal('open','flagreview','','',obj.item);

					};

				};

            }
        });
	});







/***************************************************************************************************************
*
*
* USER LOGIN SYSTEM ACTIONS
*
*
***************************************************************************************************************/



	$.pfLoginwithAjax = function(vars,formtype){
		$.ajax({
			beforeSend: function(){
				$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
			},
            type: 'POST',
            dataType: 'json',
            url: theme_scriptspf.ajaxurl,
            data: {
                'action': 'pfget_usersystemhandler',
                'formtype': formtype,
                'vars': vars,
                'lang': theme_scriptspf.pfcurlang,
                'security': theme_scriptspf.pfget_usersystemhandler
            },
            success:function(data){
				var obj = [];
				$.each(data, function(index, element) {
					obj[index] = element;
				});


				// Social Login Form works ---------------------------------------------------
				if(formtype == 'createsocial'){
					
					var pfreviewoverlay = $("#pflgcontainer-overlay");

					if(obj.status == 0){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						if (obj.auto == 1) {
							setTimeout(function() {
								$.pfOpenLogin('close');
							}, 4000);
							setTimeout(function() {
								window.location = obj.redirect;
							}, 4000);
						}
						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();

							if(obj.status == 0 && obj.auto == 1){
								$.pfOpenLogin('close');
								window.location = obj.redirect;
							}

						});


					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();
						});
					}

					$('.pf-overlay-close').on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();
						if(obj.status == 0){
							setTimeout(function() {
								$.pfOpenLogin('close');
							}, 4000);
							setTimeout(function() {
								window.location = theme_scriptspf.homeurl;
							}, 4000);
						}
					});
				}

				if(formtype == 'connectsocial'){

					var pfreviewoverlay = $("#pflgcontainer-overlay");

					if(obj.login == true){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);
						setTimeout(function() {
							$.pfOpenLogin('close');
						}, 4000);
						setTimeout(function() {
							window.location = obj.redirectpage;
						}, 4000);
					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();

							if (typeof grecaptcha !== 'undefined') {
								$.reCAPTCHA_execute(form.selector.replace("#",""));
							};
							form.find("textarea").val("");

						});
					}

					$('.pf-overlay-close').on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();

						if (typeof grecaptcha !== 'undefined') {
							$.reCAPTCHA_execute(form.selector.replace("#",""));
						};
						form.find("textarea").val("");

					});
				}
				// Social Login Form works ---------------------------------------------------


				// Login Form works ---------------------------------------------------
				if(formtype == 'login'){
					var form = $('#pf-ajax-login-form');
					var pfreviewoverlay = $("#pflgcontainer-overlay");

					if(obj.login == true){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);
						setTimeout(function() {
							window.location = obj.redirectpage;
						}, 120);
					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						pfreviewoverlay.on('click',function(){
							$.pfOpenLogin('open','login');
						});
					}

					$('.pf-overlay-close').on('click',function(){
						$.pfOpenLogin('open','login');
					});

				}
				// Login Form works ---------------------------------------------------



				// Register Form works ---------------------------------------------------
				if(formtype == 'register'){

					var form = $('#pf-ajax-register-form');
					var pfreviewoverlay = $("#pflgcontainer-overlay");

					

					if(obj.status == 0){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						if (obj.auto == 1) {
							setTimeout(function() {
								$.pfOpenLogin('close');
							}, 4000);
							setTimeout(function() {
								window.location = obj.redirectpage;
							}, 4000);
						}
						if (obj.regsysx == 0) {
							pfreviewoverlay.on('click',function(){
								pfreviewoverlay.hide("slide",{direction : "up"},100);
								pfreviewoverlay.find('.pf-overlay-close').remove();
								pfreviewoverlay.find('.pfrevoverlaytext').remove();

								if(obj.status == 0 && obj.auto == 1){
									$.pfOpenLogin('close');
									window.location = obj.redirectpage;
								}

							});
						}else{
							pfreviewoverlay.on('click',function(){
								$.pfOpenLogin('open','login');
							});
						}


					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						if (obj.regsysx == 0) {
							pfreviewoverlay.on('click',function(){
								$.pfOpenLogin('open','register');
							});
						}else{
							pfreviewoverlay.on('click',function(){
								$.pfOpenLogin('open','register');
							});
						}
					}

					$('.pf-overlay-close').on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();
						if(obj.status == 0){
							setTimeout(function() {
								$.pfOpenLogin('close');
							}, 4000);
							if (obj.regsysx == 0) {
								setTimeout(function() {
									window.location = obj.redirectpage;
								}, 4000);
							}
						}else{
							$.pfOpenLogin('open','register');
						}
					});


				}
				// Register Form works ---------------------------------------------------



				// Lost Password Form works ---------------------------------------------------
				if(formtype == 'lp'){
					var form = $('#pf-ajax-lp-form');

					var pfreviewoverlay = $("#pflgcontainer-overlay");

					if(obj.status == 0){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);
						setTimeout(function() {
							$.pfOpenLogin('close');
						}, 10000);

						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();
							if(obj.status == 0){
								$.pfOpenLogin('close');
							}
						});

					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						pfreviewoverlay.on('click',function(){
							$.pfOpenLogin('open','lp');
						});
					}

					$('.pf-overlay-close').on('click',function(){
						$.pfOpenLogin('close');
					});


				}
				// Lost Password Form works ---------------------------------------------------


				// Reset Form works ---------------------------------------------------
				if(formtype == 'reset'){
					var form = $('#pf-ajax-lpr-form');
					var pfreviewoverlay = $("#pflgcontainer-overlay");

					if(obj.reset == true){
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);
						
						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('form').remove();
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();
							$.pfOpenLogin('close');
							setTimeout(function(){$.pfOpenLogin('open','login');},300)
						});
					}else{
						pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
						pfreviewoverlay.show("slide",{direction : "up"},100);

						pfreviewoverlay.on('click',function(){
							pfreviewoverlay.hide("slide",{direction : "up"},100);
							pfreviewoverlay.find('.pf-overlay-close').remove();
							pfreviewoverlay.find('.pfrevoverlaytext').remove();
						});
					}

					$('.pf-overlay-close').on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();
					});

				}
				// Reset Form works ---------------------------------------------------

            },
            error: function (request, status, error) {
                $("#pf-membersystem-dialog").html('Error:'+request.responseText);
            },
            complete: function(){
            	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
            },
        });
	};


	$.pfOpenLogin = function(status,modalname,errortext,errortype,redirectpage) {
		$.pfdialogstatus = '';
		if (modalname == 'terms') {var pid = $('.pftermshortc').data('pid');}else{var pid = '';}
		if (modalname != 'error' && modalname != 'scontent' && modalname != 'confirmaction' && modalname != 'lpr') {
			var errortext = '';
			var errortype = 0;
		};

		if (modalname == 'scontent') {
			var scontenttype = errortype;
			var scontenttext = errortext;
			var errortext = '';
			var errortype = 0;
		};

		if (modalname == 'confirmaction') {
			var scontenttext = errortext;
		}

		if(errortype != 2){
		    if(status == 'open'){

		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}
		    	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});

	    		var minwidthofdialog = 380;
	    		if (modalname == 'terms') {minwidthofdialog = 650;}
	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};

	    		$.ajax({
		            type: 'POST',
		            dataType: 'html',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_usersystem',
		                'formtype': modalname,
		                'security': theme_scriptspf.pfget_usersystem,
		                'errortype': errortype,
		                'scontenttype': scontenttype,
		                'scontenttext': scontenttext,
		                'lang': theme_scriptspf.pfcurlang,
		                'redirectpage': redirectpage,
		                'pid':pid
		            },
		            success:function(data){

		            	

						$("#pf-membersystem-dialog").html(data);
						$('#pf-login-trigger-button-inner').on('click',function(){$.pfOpenLogin('open','login')});
						$('#pf-register-trigger-button-inner').on('click',function(){$.pfOpenLogin('open','register')});
						$('#pf-lp-trigger-button-inner').on('click',function(){$.pfOpenLogin('open','lp')});

						$('.pftermshortc').on('click', function(event) {
							setTimeout(function(){
								$.pfOpenLogin('open','terms');
							},0);
							return false;
						});
						
						$('#pf-ajax-terms-button').on('click', function(event) {
							$.pfOpenLogin('open','register');return false;
						});


						if (modalname == 'error') {
							$('#pf-ajax-cl-details').html(errortext);
						};

						if (modalname == 'lpr') {
							$('.pf-additional-detailsforlpr').html(errortext);
						};


						$('#pf-ajax-loginfacebook').on('click',function(){
							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
						});

						$('#pf-ajax-logintwitter').on('click',function(){
							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
						});

						$('#pf-ajax-logingoogle').on('click',function(){
							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
						});

						// SOCIAL CONNECT FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pfsocialconnectbutton').on('click',function(){
							var form = $('#pf-ajax-login-form');

							var pfsearchformerrors = form.find(".pfsearchformerrors");
							if ($.isEmptyObject($.pfAjaxUserSystemVars)) {
								$.pfAjaxUserSystemVars = {};
								$.pfAjaxUserSystemVars.username_err = 'Please write username';
								$.pfAjaxUserSystemVars.username_err2 = 'Please enter at least 3 characters for Username.';
								$.pfAjaxUserSystemVars.password_err = 'Please write password';
							}
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	username:{
								      required: true,
								      minlength: 3
								    },
								  	password:"required"
								  },
								  messages:{
								  	username:{
								  		required:$.pfAjaxUserSystemVars.username_err,
								  		minlength:$.pfAjaxUserSystemVars.username_err2
								  	},
								  	password:$.pfAjaxUserSystemVars.password_err
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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

							if(form.valid()){
								$.pfLoginwithAjax(form.serialize(),'connectsocial');
							};
							return false;
						});
						// SOCIAL CONNECT FUNCTION FINISHED --------------------------------------------------------------------------------------------

						// SOCIAL CREATE FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pfsocialnewaccountbutton').on('click',function(){
							var form = $('#pf-ajax-login-form');
							if (scontenttype == 2 || scontenttype == 4) {
								var pfsearchformerrors = form.find(".pfsearchformerrors");
								
								form.validate({
									  debug:false,
									  onfocus: false,
									  onfocusout: false,
									  onkeyup: false,
									  rules:{
									  	email_n:{
									      required: true,
									      email: true
									    }
									  },
									  messages:{
									  	email_n:{
									  		required:theme_scriptspf.email_err_social,
									  		email:theme_scriptspf.email_err_social2
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

								if(form.valid()){
									$.pfLoginwithAjax(form.serialize(),'createsocial');
								};
							} else {
								$.pfLoginwithAjax(form.serialize(),'createsocial');
							}
							return false;
						});
						// SOCIAL CREATE FUNCTION FINISHED --------------------------------------------------------------------------------------------



						// LOGIN FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-login-button').on('click',function(){
							var form = $('#pf-ajax-login-form');

							var recaptchanum = form.find('#recaptcha_div_us .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};


							var pfsearchformerrors = form.find(".pfsearchformerrors");

							if ($.isEmptyObject($.pfAjaxUserSystemVars)) {
								$.pfAjaxUserSystemVars = {};
								$.pfAjaxUserSystemVars.username_err = 'Please write username';
								$.pfAjaxUserSystemVars.username_err2 = 'Please enter at least 3 characters for Username.';
								$.pfAjaxUserSystemVars.password_err = 'Please write password';
							}
					
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	username:{
								      required: true,
								      minlength: 3
								    },
								  	password:"required"
								  },
								  messages:{
								  	username:{
								  		required:$.pfAjaxUserSystemVars.username_err,
								  		minlength:$.pfAjaxUserSystemVars.username_err2
								  	},
								  	password:$.pfAjaxUserSystemVars.password_err
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfLoginwithAjax(form.serialize(),'login');
							};
							return false;
						});
						// LOGIN FUNCTION FINISHED --------------------------------------------------------------------------------------------



						// REGISTER FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-register-button').on('click',function(){
							var form = $('#pf-ajax-register-form');

							

							var recaptchanum = form.find('#recaptcha_div_us .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};

							var pfsearchformerrors = form.find(".pfsearchformerrors");
							if ($.isEmptyObject($.pfAjaxUserSystemVars2)) {
								$.pfAjaxUserSystemVars2 = {};
								$.pfAjaxUserSystemVars2.username_err = 'Please write username';
								$.pfAjaxUserSystemVars2.username_err2 = 'Please enter at least 3 characters for Username.';
								$.pfAjaxUserSystemVars2.email_err = 'Please write an email';
								$.pfAjaxUserSystemVars2.email_err2 = 'Your email address must be in the format of name@domain.com';
								
								$.pfAjaxUserSystemVars2.pass_err2 = 'Please enter password';
								$.pfAjaxUserSystemVars2.phn_err2 = 'Please enter phone.';
								$.pfAjaxUserSystemVars2.mbl_err2 = 'Please enter mobile.';
								$.pfAjaxUserSystemVars2.fn_err2 = 'Please enter first name.';
								$.pfAjaxUserSystemVars2.ln_err2 = 'Please enter last name.';
								$.pfAjaxUserSystemVars2.tnc_err2 = 'Please check terms and conditions checkbox.';

								$.pfAjaxUserSystemVars2.phn_req = false;
								$.pfAjaxUserSystemVars2.mbl_req = false;
								$.pfAjaxUserSystemVars2.fn_req = false;
								$.pfAjaxUserSystemVars2.ln_req = false;
							}
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	username:{
								      required: true,
								      minlength: 3
								    },
								  	email:{
								  		required:true,
								  		email:true
								  	},
								  	pass:"required",
								  	phone:{required:$.pfAjaxUserSystemVars2.phn_req},
								  	mobile:{required:$.pfAjaxUserSystemVars2.mbl_req},
								  	firstname:{required:$.pfAjaxUserSystemVars2.fn_req},
								  	lastname:{required:$.pfAjaxUserSystemVars2.ln_req},
								  	pftermsofuser:"required"
								  },
								  messages:{
								  	username:{
									  	required:$.pfAjaxUserSystemVars2.username_err,
									  	minlength:$.pfAjaxUserSystemVars2.username_err2
								  	},
								  	email: {
									    required: $.pfAjaxUserSystemVars2.email_err,
									    email: $.pfAjaxUserSystemVars2.email_err2
								    },
								    pass:$.pfAjaxUserSystemVars2.pass_err2,
								  	phone:{required:$.pfAjaxUserSystemVars2.phn_err2},
								  	mobile:{required:$.pfAjaxUserSystemVars2.mbl_err2},
								  	firstname:{required:$.pfAjaxUserSystemVars2.fn_err2},
								  	lastname:{required:$.pfAjaxUserSystemVars2.ln_err2},
								  	pftermsofuser:$.pfAjaxUserSystemVars2.tnc_err2 
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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


							if(form.valid()){
								$.pfLoginwithAjax(form.serialize(),'register');
							};
							return false;
						});
						// REGISTER FUNCTION FINISHED --------------------------------------------------------------------------------------------



						// LOST PASSWORD FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-lp-button').on('click',function(){
							var form = $('#pf-ajax-lp-form');

							var recaptchanum = form.find('#recaptcha_div_us .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.widgetIdlp;
								form.find('.g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};

							var pfsearchformerrors = form.find(".pfsearchformerrors");
							if ($.isEmptyObject($.pfAjaxUserSystemVars3)) {
								$.pfAjaxUserSystemVars3 = {};
								$.pfAjaxUserSystemVars3.username_err = 'Username or Email must be filled.';
								$.pfAjaxUserSystemVars3.username_err2 = 'Please enter at least 3 characters for Username.';
								$.pfAjaxUserSystemVars3.email_err2 = 'Your email address must be in the format of name@domain.com';
							}
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	username:{
								    	minlength: 3
								    },
								  	email:{
								  		email:true
								  	}
								  },
								  messages:{
								  	username:{
									  	minlength:$.pfAjaxUserSystemVars3.username_err2
								  	},
								  	email: {
									    required: $.pfAjaxUserSystemVars3.email_err,
									    email: $.pfAjaxUserSystemVars3.email_err2
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


							if(form.valid()){
								if(form.find("input[name=username]").val() != '' || form.find("input[name=email]").val() != ''){
									$.pfLoginwithAjax(form.serialize(),'lp');
								}else{

									$("ul", pfsearchformerrors).append('<li>'+$.pfAjaxUserSystemVars3.username_err+'</li>');
									$("ul", pfsearchformerrors).show();
									pfsearchformerrors.show("slide",{direction : "up"},100);
										form.find(".pfsearch-err-button").on('click',function(){
											pfsearchformerrors.hide("slide",{direction : "up"},100);
											return false;
										});
									return false;
								}

							};
							return false;
						});
						// LOST PASSWORD FUNCTION FINISHED --------------------------------------------------------------------------------------------


						// ERROR FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-cl-button').on('click',function(){

							$.pfOpenLogin('close')
							return false;

						});
						// ERROR FUNCTION FINISHED --------------------------------------------------------------------------------------------



						// RESET FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-lpr-button').on('click',function(){
							var form = $('#pf-ajax-lpr-form');

							var pfsearchformerrors = form.find(".pfsearchformerrors");

							if ($.isEmptyObject($.pfAjaxUserSystemVars)) {
								$.pfAjaxUserSystemVars = {};
								$.pfAjaxUserSystemVars.password_err = 'Please write password';
								$.pfAjaxUserSystemVars.password_err2 = 'Please enter at least 3 characters for password.';
								$.pfAjaxUserSystemVars.password2_err = 'Please write password';
								$.pfAjaxUserSystemVars.password2_err2 = 'Please enter at least 3 characters for password.';
								$.pfAjaxUserSystemVars.password2_err3 = 'The two passwords you entered don\'t match.';
							}
					
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	password:{
								      required: true,
								      minlength: 3,
								    },
								  	password2:{
								      required: true,
								      minlength: 3,
								      equalTo: "#password"
								    },
								  },
								  messages:{
								  	password:{
								  		required:$.pfAjaxUserSystemVars.password_err,
								  		minlength:$.pfAjaxUserSystemVars.password_err2
								  	},
								  	password2:{
								  		required:$.pfAjaxUserSystemVars.password2_err,
								  		minlength:$.pfAjaxUserSystemVars.password2_err2,
								  		equalTo:$.pfAjaxUserSystemVars.password2_err3
								  	},
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfLoginwithAjax(form.serialize(),'reset');
							};
							return false;
						});
						// RESET FUNCTION FINISHED --------------------------------------------------------------------------------------------



		            },
		            error: function (request, status, error) {

	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);

		            },
		            complete: function(){

	            		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
						setTimeout(function(){$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});},500);
						$('.pointfinder-dialog').center(true);
		            },
		        });

	        	if(modalname != ''){
	        	$.widget("ui.dialog", $.ui.dialog, {
		            _allowInteraction: function (event) {
		                //This function fixes issue with IE11 not able to verify Recaptcha v2
		                if (this._super(event)) {
		                    return true;
		                }
		                // address interaction issues with general iframes with the dialog
		                if (event.target.ownerDocument != this.document[0]) {
		                    return true;
		                }

		                // address interaction issues with iframe based drop downs in IE
		                if ($(event.target).closest("iframe").length) {
		                    return true;
		                }
		            }
		        });
			    $("#pf-membersystem-dialog").dialog({
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center", collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "destroy" );
				$.pfdialogstatus = '';
			}
		}

	};

	$('#pf-login-trigger-button').on('click',function(){$.pfOpenLogin('open','login')});
	$('#pf-login-trigger-button-mobi').on('click',function(){$.pfOpenLogin('open','login')});
	$('.pf-login-modal').on('click',function(){$.pfOpenLogin('open','login')});
	$('.comment-reply-login').on('click',function(){$.pfOpenLogin('open','login');return false;});



	$('#pf-register-trigger-button').on('click',function(){$.pfOpenLogin('open','register')});
	$('#pf-register-trigger-button-mobi').on('click',function(){$.pfOpenLogin('open','register')});
	$('#pf-lp-trigger-button').on('click',function(){$.pfOpenLogin('open','lp')});
	$('#pf-lp-trigger-button-mobi').on('click',function(){$.pfOpenLogin('open','lp')});
	$('body').on('click','.pf-membersystem-overlay',function(){$.pfOpenLogin('close')});
	$('body').on('click','.pfmodalclose',function(){$.pfOpenLogin('close');});

/***************************************************************************************************************
*
*
* MODAL SYSTEM ACTIONS
*
*
***************************************************************************************************************/
	

	$.pfModalwithAjax = function(vars,formtype){
		$.ajax({
			beforeSend: function(){
				$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
			},
            type: 'POST',
            dataType: 'json',
            url: theme_scriptspf.ajaxurl,
            data: {
                'action': 'pfget_modalsystemhandler',
                'formtype': formtype,
                'vars': vars,
                'security': theme_scriptspf.pfget_modalsystemhandler,
                'lang': theme_scriptspf.pfcurlang
            },
            success:function(data){
				var obj = [];
				$.each(data, function(index, element) {
					obj[index] = element;
				});


				// Contact Form works ---------------------------------------------------
				if(formtype == 'enquiryform'){
					var form = $('#pf-ajax-enquiry-form');
				}
				// Contact Form works ---------------------------------------------------



				// Author Form works ---------------------------------------------------
				if(formtype == 'enquiryformauthor'){
					var form = $('#pf-ajax-enquiry-form-author');

				}
				// Author Form works ---------------------------------------------------



				// Report Form works ---------------------------------------------------
				if(formtype == 'reportitem'){
					var form = $('#pf-ajax-report-form');
				}
				// Report Form works ---------------------------------------------------


				// Claim Form works ---------------------------------------------------
				if(formtype == 'claimitem'){
					var form = $('#pf-ajax-claim-form');
				}
				// Claim Form works ---------------------------------------------------



				// Flag Review Form works ---------------------------------------------------
				if(formtype == 'flagreview'){
					var form = $('#pf-ajax-flag-form');
				}
				// Flag Review Form works ---------------------------------------------------


				// Contact Form works ---------------------------------------------------
				if(formtype == 'contactform'){
					var form = $('#pf-contact-form');
				}
				// Contact Form works ---------------------------------------------------

				if(formtype == 'enquiryform'){
					var pfreviewoverlay = $("#pfmdcontainer-overlaynew");
				}else{
					var pfreviewoverlay = $("#pfmdcontainer-overlay");
				}


				if (typeof grecaptcha !== 'undefined') {
					$.reCAPTCHA_execute(form.selector.replace("#",""));
				};

				if(obj.process == true){
					if (formtype == 'contactform') {
						pfreviewoverlay.pfLoadingOverlay({action:'hide'});
					};

					pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext pfoverlayapprove'><i class='fas fa-check-circle'></i><span>"+obj.mes+"</span></div>");
					pfreviewoverlay.show("slide",{direction : "up"},100);

					pfreviewoverlay.on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();
						if(obj.process == true && formtype != 'contactform' && formtype != 'enquiryform'){
							$.pfOpenModal('close');
							if (formtype == 'flagreview') {
								window.location.reload();
							};
						}else{

							if (formtype == 'enquiryform') {
								$('#pf-ajax-enquiry-button').attr('disabled',false);

							};
						}
					});

				}else{
					if (formtype == 'contactform') {
						pfreviewoverlay.pfLoadingOverlay({action:'hide'});
					};

					pfreviewoverlay.append("<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div><div class='pfrevoverlaytext'><i class='fas fa-exclamation-triangle'></i><span>"+obj.mes+"</span></div>");
					pfreviewoverlay.show("slide",{direction : "up"},100);

					pfreviewoverlay.on('click',function(){
						pfreviewoverlay.hide("slide",{direction : "up"},100);
						pfreviewoverlay.find('.pf-overlay-close').remove();
						pfreviewoverlay.find('.pfrevoverlaytext').remove();
						if (formtype == 'enquiryform') {
							$('#pf-ajax-enquiry-button').attr('disabled',false);
						};
						if(obj.process == true){
							form.find("textarea").val("");
						}
					});

				}


				$('.pf-overlay-close').on('click touchstart',function(){
					pfreviewoverlay.hide("slide",{direction : "up"},100);
					pfreviewoverlay.find('.pf-overlay-close').remove();
					pfreviewoverlay.find('.pfrevoverlaytext').remove();
					if(obj.process == true && formtype != 'contactform' && formtype != 'enquiryform'){
						$.pfOpenModal('close');
					}
					if (formtype == 'enquiryform') {
						$('#pf-ajax-enquiry-button').attr('disabled',false);

					};
					if (formtype == 'flagreview') {
						window.location.reload();
					};
				});

            },
            error: function (request, status, error) {
                $("#pf-membersystem-dialog").html('Error:'+request.responseText);
            },
            complete: function(){
            	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
            },
        });
	};


	$.pfOpenModal = function(status,modalname,errortext,errortype,itemid,userid) {
		$.pfdialogstatus = '';

		if (modalname != 'error') {
			var errortext = '';
			var errortype = 0;
		};
		if(errortype != 2){
		    if(status == 'open'){

		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}
		    	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show'});
	    		var minwidthofdialog = 380;
	    		var maxwidthofdialog = 1140;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};
	    		
	    		if($.pf_mobile_check() && modalname == 'quickpreview'){ minwidthofdialog = 1140;maxwidthofdialog = 1140;};

	    		$.ajax({
		            type: 'POST',
		            dataType: 'html',
		            url: theme_scriptspf.ajaxurl,
		            data: {
		                'action': 'pfget_modalsystem',
		                'formtype': modalname,
		                'itemid': itemid,
		                'userid': userid,
		                'security': theme_scriptspf.pfget_modalsystem,
		                'errortype': errortype,
		                'lang': theme_scriptspf.pfcurlang
		            },
		            success:function(data){

						$("#pf-membersystem-dialog").html(data);

						if (modalname == 'error') {
							$('#pf-ajax-cl-details').html(errortext);
						};

						$('.pftermshortc').on('click', function(event) {
							setTimeout(function(){
								$.pfOpenLogin('open','terms');
							},0);
							return false;
						});


						$(function(){
							if (modalname == 'quickpreview') {
								var lat = $(".pfquickviewmap").data('welat');
								var lng = $(".pfquickviewmap").data('welng');
								
								if (lat != '') {
									var quickmap = $.pointfinderbuildmap("pfquickviewmap");
									var marker = L.marker(
							          L.latLng(parseFloat(lat),parseFloat(lng)),
							          {
							            riseOnHover: true,
							            bubblingMouseEvents: true
							          }
							        );
						        }else{
						        	$(".pfquickviewmap").remove();
						        }

						        quickmap.addLayer(marker);
							}

						})

						// AUTHOR CONTACT FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-enquiry-button-author').on('click touchstart',function(){
							var form = $('#pf-ajax-enquiry-form-author');

							var recaptchanum = form.find('#recaptcha_div_mod .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};


							var pfsearchformerrors = form.find(".pfsearchformerrors");
							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	name:"required",
								  	email:{
								  		required:true,
								  		email:true
								  	},
								  	msg:"required",
								  	pftermsofuser:"required"
								  },
								  messages:{
								  	name:theme_scriptspf.pfnameerr,
								  	email: {
									    required: theme_scriptspf.pfemailerr,
				    					email: theme_scriptspf.pfemailerr2
								    },
								    msg:theme_scriptspf.pfmeserr,
								    pftermsofuser:theme_scriptspf.tnc_err2
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfModalwithAjax(form.serialize(),'enquiryformauthor');
							};
							return false;
						});
						// AUTHOR CONTACT FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------




						// REPORT FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-report-button').on('click',function(){
							var form = $('#pf-ajax-report-form');
							var pfsearchformerrors = form.find(".pfsearchformerrors");

							var recaptchanum = form.find('#recaptcha_div_mod .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};

							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	name:"required",
								  	email:{
								  		required:true,
								  		email:true
								  	},
								  	msg:"required",
								  	pftermsofuser:"required"
								  },
								  messages:{
								  	name:theme_scriptspf.pfnameerr,
								  	email: {
									    required: theme_scriptspf.pfemailerr,
									    email: theme_scriptspf.pfemailerr2
								    },
								    msg:theme_scriptspf.pfmeserr2,
								    pftermsofuser:theme_scriptspf.tnc_err2
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfModalwithAjax(form.serialize(),'reportitem');
							};
							return false;
						});
						// REPORT FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------



						// CLAIM FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-claim-button').on('click touchstart',function(){
							var form = $('#pf-ajax-claim-form');
							var pfsearchformerrors = form.find(".pfsearchformerrors");

							var recaptchanum = form.find('#recaptcha_div_mod .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};

							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	name:"required",
								  	email:{
								  		required:true,
								  		email:true
								  	},
								  	msg:"required",
								  	pftermsofuser:"required"
								  },
								  messages:{
								  	name:theme_scriptspf.pfnameerr,
								  	email: {
									    required: theme_scriptspf.pfemailerr,
									    email: theme_scriptspf.pfemailerr2
								    },
								    msg:theme_scriptspf.pfmeserr2,
								    pftermsofuser:theme_scriptspf.tnc_err2
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfModalwithAjax(form.serialize(),'claimitem');
							};
							return false;
						});
						// CLAIM FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------




						// FLAG REVIEW FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-flag-button').on('click touchstart',function(){
							var form = $('#pf-ajax-flag-form');
							var pfsearchformerrors = form.find(".pfsearchformerrors");

							var recaptchanum = form.find('#recaptcha_div_mod .g-recaptcha-field').data('rekey');
							if (recaptchanum) {
								recaptchanum = $.recaptchanum;
								form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
							};

							form.validate({
								  debug:false,
								  onfocus: false,
								  onfocusout: false,
								  onkeyup: false,
								  rules:{
								  	name:"required",
								  	email:{
								  		required:true,
								  		email:true
								  	},
								  	msg:"required",
								  	pftermsofuser:"required"
								  },
								  messages:{
								  	name:theme_scriptspf.pfnameerr,
								  	email: {
									    required: theme_scriptspf.pfemailerr,
									    email: theme_scriptspf.pfemailerr2
								    },
								    msg:theme_scriptspf.pfmeserr,
								    pftermsofuser:theme_scriptspf.tnc_err2
								  },
								  validClass: "pfvalid",
								  errorClass: "pfnotvalid pfaddnotvalidicon",
								  errorElement: "li",
								  errorContainer: pfsearchformerrors,
								  errorLabelContainer: $("ul", pfsearchformerrors),
								  invalidHandler: function(event, validator) {
									var errors = validator.numberOfInvalids();
									if (errors) {
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



							if(form.valid()){
								$.pfModalwithAjax(form.serialize(),'flagreview');
							};
							return false;
						});
						// FLAG REVIEW FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------





						// ERROR FUNCTION STARTED --------------------------------------------------------------------------------------------
						$('#pf-ajax-cl-button').on('click',function(){

							$.pfOpenModal('close')
							return false;

						});
						// ERROR FUNCTION FINISHED --------------------------------------------------------------------------------------------



		            },
		            error: function (request, status, error) {

	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);

		            },
		            complete: function(){

	            		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});

	            		setTimeout(function(){$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});},500);
		            	$('.pointfinder-dialog').center(true);
		            },
		        });

	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        maxWidth: maxwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';

			   
		    	if(!$.pf_tablet_check() && modalname == 'quickpreview'){
		    		$( "#pf-membersystem-dialog" ).dialog( "option", "width", 'calc(100vw - 40px)' );
	    	 	};
			 
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "destroy" );
				if($('.pfquickpreviewmain').length > 0){$('.pfquickpreviewmain').remove();}
				$.pfdialogstatus = '';
			}
		}

	};


	// CONTACT FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
	$('#pf-ajax-enquiry-button').on('click touchstart',function(){
		var form = $('#pf-ajax-enquiry-form');

		var recaptchanum = form.find('#recaptcha_div_mod .g-recaptcha-field').data('rekey');
		if (recaptchanum) {
			recaptchanum = $.recaptchanum;
			form.find('#g-recaptcha-response').text(grecaptcha.getResponse(recaptchanum));
		};

		var pfsearchformerrors = form.find(".pfsearchformerrors");
		form.validate({
			  debug:false,
			  onfocus: false,
			  onfocusout: false,
			  onkeyup: false,
			  rules:{
			  	name:"required",
			  	email:{
			  		required:true,
			  		email:true
			  	},
			  	msg:"required",
			  	pftermsofuser:"required"
			  },
			  messages:{
			  	name:theme_scriptspf.pfnameerr,
			  	email: {
				    required: theme_scriptspf.pfemailerr,
					email: theme_scriptspf.pfemailerr2
			    },
			    msg:theme_scriptspf.pfmeserr,
			    pftermsofuser:theme_scriptspf.tnc_err2
			  },
			  validClass: "pfvalid",
			  errorClass: "pfnotvalid pfaddnotvalidicon",
			  errorElement: "li",
			  errorContainer: pfsearchformerrors,
			  errorLabelContainer: $("ul", pfsearchformerrors),
			  invalidHandler: function(event, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
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



		if(form.valid()){
			$('#pf-ajax-enquiry-button').attr('disabled',true);
			$.pfModalwithAjax(form.serialize(),'enquiryform');
		};
		return false;
	});
	// CONTACT FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------


	// CONTACT FORM FUNCTION STARTED --------------------------------------------------------------------------------------------
	$('#pf-contact-form-submit').on('click touchstart',function(){
		var form = $('#pf-contact-form');
		var pfsearchformerrors = form.find(".pfsearchformerrors");

		form.validate({
			  debug:false,
			  onfocus: false,
			  onfocusout: false,
			  onkeyup: false,
			  rules:{
			  	name:"required",
			  	email:{
			  		required:true,
			  		email:true
			  	},
			  	pftermsofuser:"required"
			  },
			  messages:{
			  	name: theme_scriptspf.pfnameerr,
			  	email: {
				    required: theme_scriptspf.pfemailerr,
				    email: theme_scriptspf.pfemailerr2
			    },
			    pftermsofuser:theme_scriptspf.tnc_err2
			  },
			  validClass: "pfvalid",
			  errorClass: "pfnotvalid pfaddnotvalidicon",
			  errorElement: "li",
			  errorContainer: pfsearchformerrors,
			  errorLabelContainer: $("ul", pfsearchformerrors),
			  invalidHandler: function(event, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
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



		if(form.valid()){
			var pfreviewoverlay = $("#pfmdcontainer-overlay");
			pfreviewoverlay.pfLoadingOverlay({action:'show'});
			pfreviewoverlay.show("slide",{direction : "up"},100);
			$.pfModalwithAjax(form.serialize(),'contactform');
		};
		return false;
	});
	// CONTACT FORM FUNCTION FINISHED --------------------------------------------------------------------------------------------



	$('#pf-enquiry-trigger-button-author').on('click',function(){$.pfOpenModal('open','enquiryformauthor','','','',$('#pf-enquiry-trigger-button-author').attr('data-pf-user'))});

	

	// MANUAL SEARCH BUTTON STARTED --------------------------------------------------------------------------------------------
	$('body').on('click','#pf-search-button-manual',function(){
		var form = $('#pointfinder-search-form-manual');

		if (typeof $('#pointfinder_google_search_coord').attr("value") == 'undefined' || $('#pointfinder_google_search_coord').attr("value") == '') {
			$('#pointfinder_google_search_coord').attr("value",'');
			$('input[name=ne]').attr("value",'');$('input[name=ne2]').attr("value",'');$('input[name=sw]').attr("value",'');$('input[name=sw2]').attr("value",'');
		}

		form.validate();

		var temp = ['input[name=pointfinder_radius_search]', 'input[name=ne]', 'input[name=ne2]', 'input[name=sw]', 'input[name=sw2]']

		form.find("div:hidden[id$='_main']").each(function(){
			$(this).find('input[type=hidden]').not(temp.join(',')).attr("value","");
			$(this).find('input[type=text]').attr("value",$.pfsliderdefaults.fields[$(this).attr('id')]);
			$(this).find('.slider-wrapper .ui-slider-range').css('width','0%');
			$(this).find('.slider-wrapper a:nth-child(2)').css('left','0%');
			$(this).find('.slider-wrapper a:nth-child(3)').css('left','100%');
		});

		if(form.valid()){
			form.submit();
		};
		return false;
	});
	// MANUAL SEARCH BUTTON END --------------------------------------------------------------------------------------------

	// Footer row fix v1.6.1.3
	$(function(){
		$(".pointfinderexfooterclassx").appendTo(".wpf-footer-row-move");
	});

	$(function(){
		$( "#pfccs_changer" ).change(function() {

		   var selected_value_pfcss = $(this).val();


		   $.ajax({
            type: 'POST',
            dataType: 'json',
            url: theme_scriptspf.ajaxurl,
            data: {
                'action': 'pfget_currencychange',
                'security': theme_scriptspf.pfget_modalsystemhandler,
                'value': selected_value_pfcss
            },
            complete:function(){
            	window.location = theme_scriptspf.pfcurrentpage+"/?c_code="+selected_value_pfcss;
            }
        	});

		   
		});
	});
})(jQuery);
