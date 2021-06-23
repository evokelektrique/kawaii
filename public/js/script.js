$(function() {




			$('#fullscreen').on('click', function() {
				toggleFullscreen($('body').get(0));
				$(this).toggleClass('active');
				$('body').toggleClass('fullscreen');
				$('.comments').toggle(0,'linear');
			});




			$('.app').on('click', function(e) {
				$('.navbar').toggleClass('active');
				$('.pagination').toggleClass('active');
			}).on('mouseleave', function() {
				$('.navbar').addClass('active');
				$('.pagination').addClass('active');
			});
			var $page = $('.app img').each(function() {
				this._img = $(this);
			});
			function pagination() {
				$page.each(function(e) {
					var br = this.getBoundingClientRect();
					if(br.top<0 && br.bottom>0) {
						// console.log(this.id);
						$('.current_page').html(this.id);
						$('#pagination').val(this.id);
					}
				});
			}

			$('.display_pagination').click(function(e) {
				$('#pagination').toggleClass('active');
			});

			$('#pagination').on('change', function(e) {
				e.preventDefault()
				var page = this.value;
				console.log(page);
				$('html,body').animate({
					scrollTop: $("#" + String(page)).offset().top,
				}, 500, 'linear');
				$(this).toggleClass('active');
			});


			$(window).on('load scroll', pagination);




			$('.article_more_options').on('click', function(e) {
				// e.preventDefault();
				$(this).children().toggleClass("active");
			});
			$('.post_chapter_options').on('click', function(e) {
				e.preventDefault();
				$(this).children().toggleClass("active");
			});

			$('#profile_change_image').click(function(e) {
				e.preventDefault();
				$('#profile_cover_file').trigger('click');
				console.log('click');
			});

			$('#profile_picture_change').click(function(e) {
				e.preventDefault();
				$('#profile_picture_file').trigger('click');
				console.log('click');
			});




			$('.like').click(function(e) {
				e.preventDefault();
				toast('گزارش', 'ذخیره کردن');
				var data_article_id = $(this).data('article_id');
				var formData = new FormData();
				formData.append('article_id', data_article_id);
				formData.append(csrf_name, csrf_hash);
				var current_likes = parseInt($(this).find('span').html().trim());
				// $("." + this.className + ' span').html(parseInt($("." + this.className + ' span').html()) + 1);
				console.log(current_likes);
				$(this).find('span').html(parseInt(current_likes + 1));


				$.ajax({
					url: base_url+"like",
					type: "POST",
					data: formData,
				    processData: false,
				    contentType: false,
				    cache: false,
				    asnyc: false,
					success:function(response) {
		                var response = response;
		                response = response.replace(/\\n/g, "\\n")  
		                               .replace(/\\'/g, "\\'")
		                               .replace(/\\"/g, '\\"')
		                               .replace(/\\&/g, "\\&")
		                               .replace(/\\r/g, "\\r")
		                               .replace(/\\t/g, "\\t")
		                               .replace(/\\b/g, "\\b")
		                               .replace(/\\f/g, "\\f");
		                response = response.replace(/[\u0000-\u0019]+/g,"");
		                var json_decoded = JSON.parse(response);
		                json_data = json_decoded.data;
		                json_data = json_data.replace(/<[^>]*>?/gm, '');
						toast('گزارش', json_data, 2000);
					}
				});
			});







			$('#search_form button').click(function(e) {
				e.preventDefault();
				var query = $(".search_input").val();
				window.location.href = base_url + '/' + query;
			});

			$('#search_form_home button').click(function(e) {
				e.preventDefault();
				var query = $(".search_input").val();
				window.location.href = base_url + 'search/' + query;
			});



			$('.alert_link').on('click', function(e) {
				// e.preventDefault();
				var type_id = $(this).data('type-id');
				var type = $(this).data('type');
				$('#alert_form #form_alert_id').val(type_id);
				$('#alert_form #form_alert_type').val(type);
			});



			$('#alert_form').on('submit', function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				$("#alert_form textarea").val('');
				$.ajax({
					url: base_url+"alert",
					type: "POST",
					data: formData,
				    processData: false,
				    contentType: false,
				    cache: false,
				    asnyc: false,
					success:function(response) {
		                var response = response;
		                response = response.replace(/\\n/g, "\\n")  
		                               .replace(/\\'/g, "\\'")
		                               .replace(/\\"/g, '\\"')
		                               .replace(/\\&/g, "\\&")
		                               .replace(/\\r/g, "\\r")
		                               .replace(/\\t/g, "\\t")
		                               .replace(/\\b/g, "\\b")
		                               .replace(/\\f/g, "\\f");
		                response = response.replace(/[\u0000-\u0019]+/g,"");
		                var json_decoded = JSON.parse(response);
		                json_data = json_decoded.data;
		                json_data = json_data.replace(/<[^>]*>?/gm, '');
						toast('گزارش', json_data);
					}
				});
			});





			function toast(heading, text, duration=1000, bg_color="#4caf50") {
				$.toast({
					text: text,
					heading: heading,
					showHideTransition: 'fade',
					allowToastClose: true,
					hideAfter: duration,
					loader: false,
					loaderBg: '#9EC600',
					stack: 5,
					position: 'bottom-right',
					bgColor: bg_color,
					textColor: '#eee',
					textAlign: 'right',
					icon: false,
					beforeShow: function () {},
					afterShown: function () {},
					beforeHide: function () {},
					afterHidden: function () {},
					onClick: function () {}
				});
			}



});