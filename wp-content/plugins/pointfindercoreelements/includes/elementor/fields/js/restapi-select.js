( function( $ ) {

	$( window ).on( 'elementor:init', function() {
		var restapiselect2 = elementor.modules.controls.BaseData.extend( {
			getSelect2Placeholder: function() {
				return this.ui.select.children( 'option:first[value=""]' ).text();
			},

			getSelect2DefaultOptions: function(mxurl) {
				return {
					allowClear: true,
					placeholder: this.getSelect2Placeholder(),
					dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
					ajax: {
		              delay: 10,
		              url: mxurl,
		              dataType: 'json',
		              processResults: function (data) {
		                return {results: data};
		              }
		            }
				};
			},

			getSelect2Options: function(mxurl) {
				return jQuery.extend( this.getSelect2DefaultOptions(mxurl), this.model.get( 'select2options' ) );
			},

			onReady: function() {
				var mxurl = '';
				

				if (this.ui.select[0].dataset.setting == 'mx_page_listing_type_filter') {
					$.listing_type = '';
					$.listing_type = $('.elementor-control-mx_page_listing_type_filter select').val();

					mxurl = modulexelmlocalize.resturl+'modulexlistingtypelist/v1/posts';

					this.ui.select.select2( this.getSelect2Options(mxurl) );

					this.ui.select.on('select2:select', function (e) {

						if(typeof e.params.data.id != 'undefined'){
					  		$.listing_type = e.params.data.id;
					  	}else if (typeof e.params.data.results != 'undefined') {
					  		$.listing_type = e.params.data.results[0].id;
					  	}

					});

					if (this.ui.select.data('selected') != '') {

						var field = this.ui.select;
						$.ajax({
		                  type: 'GET',
		                  url: modulexelmlocalize.resturl+'modulexlistingtypelist/v1/posts/'+this.ui.select.data('selected'),
		                }).then(function (data) {
			                if ($.isEmptyObject(data)) {return;}
			                var option = new Option(data[0].text, data[0].id, true, true);
			                field.append(option).trigger('change');
			                field.trigger({
			                	type: 'select2:select',params: {data: {results: data}}
			                });
			                $.listing_type = data[0].id;
			            });
					}

				}

				if (this.ui.select[0].dataset.setting == 'mx_page_region_filter') {
					mxurl = modulexelmlocalize.resturl+'modulexretalist/v1/mxregions';

					this.ui.select.select2( this.getSelect2Options(mxurl) );
			
					if (this.ui.select.data('selected') != '') {

						var field = this.ui.select;
						var selected_data = JSON.parse("[" + this.ui.select.data('selected') + "]");

						$.ajax({
		                  type: 'GET',
		                  url: modulexelmlocalize.resturl+'modulexretalist/v1/mxregions/?lt='+selected_data,
		                }).then(function (data) {
			                if ($.isEmptyObject(data)) {return;}
			                var obj = [];var option;
	        				$.each(data, function(index, element) {
        						obj[index] = element;
		                  		option = new Option(obj[index].text, obj[index].id, true, true);
		                  		field.append(option);
	        				});

			                field.trigger('change');
			                field.trigger({
			                  type: 'select2:select',
			                  params: {
			                      data: {results: data}
			                  }
			                });
			            });
					}
				}

				if (this.ui.select[0].dataset.setting == 'mx_page_category_filter') {
					mxurl = modulexelmlocalize.resturl+'modulexcategorylist/v1/cats/'+$.listing_type+'/?fw=c';

					this.ui.select.select2( this.getSelect2Options(mxurl) );

					if (this.ui.select.data('selected') != '') {

						var field = this.ui.select;
						var selected_data = JSON.parse("[" + this.ui.select.data('selected') + "]");

						$.ajax({
		                  type: 'GET',
		                  url: modulexelmlocalize.resturl+'modulexcategorylist/v1/cats/'+$.listing_type+'/?fw=c&sl='+selected_data,
		                }).then(function (data) {
			                if ($.isEmptyObject(data)) {return;}
			                var obj = [];var option;
	        				$.each(data, function(index, element) {
        						obj[index] = element;
		                  		option = new Option(obj[index].text, obj[index].id, true, true);
		                  		field.append(option);
	        				});

			                field.trigger('change');
			                field.trigger({
			                  type: 'select2:select',
			                  params: {
			                      data: {results: data}
			                  }
			                });
			            });
					}
				}

				if (this.ui.select[0].dataset.setting == 'mx_page_tag_filter') {
					mxurl = modulexelmlocalize.resturl+'modulexcategorylist/v1/cats/'+$.listing_type+'/?fw=t';

					this.ui.select.select2( this.getSelect2Options(mxurl) );

					if (this.ui.select.data('selected') != '') {

						var field = this.ui.select;
						var selected_data = JSON.parse("[" + this.ui.select.data('selected') + "]");

						$.ajax({
		                  type: 'GET',
		                  url: modulexelmlocalize.resturl+'modulexcategorylist/v1/cats/'+$.listing_type+'/?fw=t&sl='+selected_data,
		                }).then(function (data) {
			                if ($.isEmptyObject(data)) {return;}
			                var obj = [];var option;
	        				$.each(data, function(index, element) {
        						obj[index] = element;
		                  		option = new Option(obj[index].text, obj[index].id, true, true);
		                  		field.append(option);
	        				});

			                field.trigger('change');
			                field.trigger({
			                  type: 'select2:select',
			                  params: {
			                      data: {results: data}
			                  }
			                });
			            });
					}
				}
			},
			saveValue: function() {

				this.setValue( this.ui.select.getOption() );
			},
			onBeforeDestroy: function() {
				if ( this.ui.select.data( 'select2' ) ) {
					this.ui.select.select2( 'destroy' );
				}

				this.$el.remove();
			},
		} );
		elementor.addControlView( 'mxselect2', restapiselect2 );
	} );


} )( jQuery );