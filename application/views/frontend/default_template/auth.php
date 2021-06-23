<!-- {header}
{sidebar}
Content
{footer} -->


<html>
	<head>
		<title>داشبورد</title>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	    <link rel="stylesheet" href="<?=base_url()?>public/css/materialize.min.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/{template_name}/style.css">

	    <script src="<?=base_url()?>public/js/jquery.min.js"></script>
	    <script src="<?=base_url()?>public/js/materialize.min.js"></script>


	</head>
	<body class="auth">


	<!-- Wrapper -->
	<div class="wrapper">
			<div class="col auth">

					<div class="card grey z-depth-5 lighten-4">

						<div class="card-content white-text blue">
							<p>
								بخش ورود و ثبت نام به سایت
							</p>
						</div>
						<div class="card-tabs blue">
							<ul class="tabs tabs-fixed-width tabs-transparent">
								<li class="tab"><a class="active" href="#login">ورود</a></li>
								<li class="tab"><a href="#register">ثبت نام</a></li>
							</ul>
						</div>
						<div class="card-content">
								<?= validation_errors()?>
							<div id="login">
								<div class="row">
									<form class="col s12" id="login_form">
										<div class="row">
											<div class="input-field col s12 m6">
												<i class="material-icons prefix">lock_outline</i>
												<input tabindex="0" placeholder="رمز عبور" id="login_password" type="password" class="validate">

											</div>

											<div class="input-field col s12 m6">
												<i class="material-icons prefix">mail_outline</i>
												<input tabindex="1" type="text" id="login_email" placeholder="ایمیل" class="validate">
											</div>
											<!-- captcha -->

											<div class="input-field col s12 m6 right right-align">
												<i class="material-icons prefix">lock_outline</i>
												<input id="captchaL" type="text" placeholder="کد امنیتی" class="right validate">
												
											</div>
											<div class="input-field col s12 m6 right right-align">
												<span class="right">{captcha}</span>
											</div>
										</div>
										<input type="hidden" name="{csrf_name}" value="{csrf_hash}">
										<button class="login_button btn-small right waves-effect  waves-light blue" type="submit">ورود</button>
	
									</form>
								</div>
							</div>

							<div id="register">

								<div class="row">
									<form class="col s12" id="register_form">
										<div class="row">
											<div class="input-field col s6">
												<i class="material-icons prefix">person_outline</i>
												<input name="lastname" id="lastname" type="text" placeholder="نام خانوداگی" class="validate">
											</div>

											<div class="input-field col s6">
												<i class="material-icons prefix">person_outline</i>
												<input name="firstname" id="firstname" placeholder="نام" type="text" class="validate">
											</div>
										</div>
										<div class="row">
											<div class="input-field col s6">
												<i class="material-icons prefix">mail_outline</i>
												<input name="email" id="email" type="email" placeholder="ایمیل" class="validate">
											</div>											<div class="input-field col s6">
												<i class="material-icons prefix">mail_outline</i>
												<input name="username" id="username" type="text" placeholder="نام کاربری" class="validate">
											</div>
										</div>
										<div class="row">
											<div class="input-field col s6">
												<i class="material-icons prefix">lock_outline</i>
												<input name="password_confirm" id="password_confirm" type="password" placeholder="تکرار رمز عبور" class="validate">
											</div>
											<div class="input-field col s6">
												<i class="material-icons prefix">lock_outline</i>
												<input name="password" id="password" type="password" placeholder="رمز عبور" class="validate">
											</div>
										</div>

										<div class="row">

											<!-- captcha -->

											<div class="input-field col s12 m6 right right-align">
												<i class="material-icons prefix">lock_outline</i>
												<input id="captchaR" name="captcha" type="text" placeholder="کد امنیتی" class="right validate">
												
											</div>
											<div class="input-field col s12 m6 right right-align">
												<span class="right">{captcha}</span>
											</div>
										</div>

										<button href="#" class="register_button btn-small right waves-effect  waves-light blue" type="submit">ثبت نام</button>

									</form>
								</div>
							</div>
						</div>
					</div>
        
			</div>

	</div>







	<!-- Footer -->
	<footer class="auth_footer">

        <div class="container">
			<p>تمامی حقوق محفوظ می باشد 1398 &copy;</p>
        </div>

    </footer>
            
<script>
M.AutoInit();
$(document).ready(function(){
	$('#login_form').submit(function(e) {
		e.preventDefault();
		var email 				= $("#login_email").val().trim();
		var password 			= $("#login_password").val().trim();
		var captchaL 			= $("#captchaL").val().trim();
		$.ajax({
			url: '<?=base_url() ?>auth/login',
			type: "POST",
			data: {
				email:email,
				password:password,
				captcha:captchaL
			},
			success:function(response) {
				console.log(response);
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
				if(json_decoded.status == 1) {
					toasts = json_data.split('\n').filter(Boolean);
					toasts.forEach(create_toast);
					var delay = 1000; 
					setTimeout(function(){ window.location = json_decoded.redirect; }, delay);
					// console.log('status 1');
					// console.log('status: ' + json_decoded.status);
				} else {
					// console.log('status not 1');
					// console.log('status: ' + json_decoded.status);
					toasts = json_data.split('\n').filter(Boolean);
					toasts.forEach(create_toast);

				}
			}
		});


	});


	$('#register_form').submit(function(e) {
		e.preventDefault();
		var firstname 			= $("#firstname").val().trim();
		var lastname 			= $("#lastname").val().trim();
		var email 				= $("#email").val().trim();
		var password 			= $("#password").val().trim();
		var password_confirm 	= $("#password_confirm").val().trim();
		var username 			= $("#username").val().trim();
		var captchaR 			= $("#captchaR").val().trim();
		// var captcha = $("#captcha");
		$.ajax({
			url: '<?=base_url() ?>auth/register',
			type: "POST",
			data: {
				firstname:firstname,
				lastname:lastname,
				username:username,
				email:email,
				password:password,
				password_confirm:password_confirm,
				captcha:captchaR

			},
			success:function(response) {
				// console.log(response);
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
				if(json_decoded.status == 1) {
					toasts = json_data.split('\n').filter(Boolean);
					toasts.forEach(create_toast);
					var delay = 1000; 
					setTimeout(function(){ window.location = json_decoded.redirect; }, delay);
					console.log('status 1');
					console.log('status: ' + json_decoded.status);
				} else {
					console.log('status not 1');
					console.log('status: ' + json_decoded.status);
					toasts = json_data.split('\n').filter(Boolean);
					toasts.forEach(create_toast);

				}
			}
		});


	});

});
function create_toast(data) {	
	M.toast({html: data});
}
</script>
	</body>
</html>