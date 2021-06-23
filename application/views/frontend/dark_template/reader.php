{header}

<div class="options_modal">
	<h2>حالت خواندن</h2>
	<div class="reader_mode">
		<a href="#" id="manga_mode">مانگا</a>
		<a href="#" id="webtoon_mode">وب تون</a>
	</div>
</div>



<div class="navbar active">
	<div class="reader_descriptions">
		<a href="<?=base_url()?>">
			<img class="reader_logo" src="{settings}<?=base_url('{logo_url}')?>{/settings}" alt="">
		</a>
		<div class="reader_descriptions_content">
			<div class="reader_title">{article} <a href="<?=base_url('{url_slug}/{id}')?>">{name}</a> {/article}</div>
			<div class="chapter_selector">
				<select name="chapters">
				{chapters}
					<option value="{id}">{name} - <?=$article[0]->name?></option>
				{/chapters}
				</select>
			</div>
		</div>
		<div class="navbar_options">
			<a href="#" id="settings">
				<i class="fas fa-cog fa-2x"></i>
			</a>
			<a href="#" id="fullscreen" class="">
				<i class="fas fa-expand fa-2x"></i>
				<i class="fas fa-compress fa-2x"></i>
			</a>
		</div>
	</div>
</div>





<section class="app">

	<ul>
		<?php $id = 1 ?>
		<?php if(!empty($episodes)): ?>
		<?php foreach($episodes as $episode): ?>
		<li>

			<img id='<?=$id?>' src="data:image/png;base64,<?= $this->mangareader->view_image($episode->image_name) ?>" alt="">

		</li>
		<?php $id++; ?>
		<?php endforeach; ?>
		<?php else: ?>
			<div class="reader_empty">صفحه ای برای نمایش وجود ندارد</div>
		<?php endif; ?>
	</ul>

	<?php if(!empty($next_chapter)): ?>
		{article}
			<a href="<?=base_url("{url_slug}/{id}/chapter/".$next_chapter['id'])?>" class="button next_button">فصل  قبلی</a>
		{/article}
	<?php endif; ?>

	<?php if(!empty($prev_chapter)): ?>
		{article}
			<a href="<?=base_url("{url_slug}/{id}/chapter/".$prev_chapter['id'])?>" class="button next_button">فصل بعدی</a>
		{/article}
	<?php endif; ?>

</section>




<?php if(!empty($episodes)): ?>
<div class="pagination active">
	<select name="pagination" id="pagination">
		<?php $id = 1 ?>
		<?php foreach($episodes as $episode): ?>

		<option value="<?=$id?>">صفحه <?=$id?> / <?=count($episodes)?></option>
		<?php $id++; ?>
		<?php endforeach; ?>
	</select>
	<div class="display_pagination">
		صفحه <span class="total_page"><?=count($episodes)?></span>/<span class="current_page">1</span>
	</div>
</div>
<?php endif; ?>




















<div class="comments">






	<?php if($this->session->has_userdata('logged_in')): ?>

	<h2><i class="fas fa-comments"></i>بخش نظرات (<?=count($comments)?>)</h2>
	<h4 class="reply_to">در حال پاسخ به <span class="reply_to_name"></span></h4>
	<h3>ارسال نظر</h3>
	<br>
	<form id="comment_form" name="comment" method="post" action="<?=base_url('create_comment')?>">
		<label for="">متن نظر</label>
		<textarea name="comment_text"></textarea>
		<label for="">کد امنیتی</label>

		<input type="text" name="captcha">
		<input type="hidden" name="comment_post_id" value="{article}{id}{/article}">
		{captcha}
		<input type="hidden" name="{csrf_name}" value="{csrf_hash}">
		<input id="comment_user_id" type="hidden" name="comment_user_id" value="<?php
		$id = $this->session->userdata('user_id');
		$user = $this->users_model->get_users_by_id($id);
		echo $user[0]->id;
		?>">
		<input id="comment_post_id" type="hidden" name="comment_post_id" value="{article}{id}{/article}">
		<input id="comment_reply_id" type="hidden" name="comment_reply_id" value="0">
		<button name="comment" class="button login_button" value="ثبت">ثبت</button>
	</form>
	<?php else: ?>

	<div class="comments_login">
		برای ارسال نظر باید وارد شوید <a href="<?=base_url('auth')?>" class="button login_button">ورود</a>
	</div>
	<?php endif; ?>


	<ul>
		<?php foreach($comments as $comment): ?>
		<?php $user = $this->users_model->get_users_by_id($comment->comment_user_id); ?>
		<?php $comments_reply = $this->comments_model->get_comments_by_reply_id($comment->id); ?>
		<?php if($comment->comment_reply_id == 0): ?>
			<li>
				<?php if($user[0]->role == 2): ?>
					<i class="fas fa-star admin_badage"></i>
				<?php endif; ?>
				<img src="<?=base_url('public/img/profile_images/').$user[0]->profile_picture_url?>" class="comment_img" alt="<?=$user[0]->firstname.' '.$user[0]->lastname?>">
				<p>
					<span class="comment_info"><a href="<?=base_url('user/').$user[0]->username?>">
						<?php if(empty($user->firstname)): ?>
							<?=ucfirst($user[0]->username)?>
						<?php else: ?>
							<?=$user[0]->firstname?>
						<?php endif; ?>	
					</a><br><span class="comment_date">

						<?php 
$time = strtotime($comment->comment_date);
$date = date('Y-m-d-H-i-s', $time);
$date_array = explode('-', $date);
$jalali_date = $this->jalalicalendar->gregorian_to_jalali($date_array[0],$date_array[1],$date_array[2]);
echo(implode('/', $jalali_date). ' در ساعت ' . $date_array['3'] .':'.$date_array['4']);
						 ?>

						</span>
										<a class="alert_link alert_link_comments" data-type='comment' data-type-id="<?=$comment->id?>" href="#alert_modal"  rel="modal:open">
											<i class="fas fa-exclamation-triangle"></i>گزارش
										</a>
					</span>
				</p>
				<p class="comment_text"><?=$comment->comment_text?></p>
				<a href="#" data-name="<?php if(empty($user->firstname)): ?><?=ucfirst($user[0]->username)?><?php else: ?><?=$user[0]->firstname?><?php endif; ?>" id="<?=$comment->id?>" class="comment_reply_link">پاسخ</a>
				<ul>
					<?php foreach ($comments_reply as $comment_replay): ?>
						<?php if($comment_replay->comment_reply_id > 0): ?>
							<li>
								<?php if($user[0]->role == 2): ?>
									<i class="fas fa-star admin_badage"></i>
								<?php endif; ?>
								<img src="<?=base_url('public/img/profile_images/').$user[0]->profile_picture_url?>" class="comment_img" alt="<?=$user[0]->firstname.' '.$user[0]->lastname?>">
								<p>
									<span class="comment_info"><a href="<?=base_url('user/').$user[0]->username?>">
										<?php if(empty($user->firstname)): ?>
											<?=ucfirst($user[0]->username)?>
										<?php else: ?>
											<?=$user[0]->firstname?>
										<?php endif; ?>	
									</a><br><span class="comment_date">
						<?php 
$time = strtotime($comment_replay->comment_date);
$date = date('Y-m-d-H-i-s', $time);
$date_array = explode('-', $date);
$jalali_date = $this->jalalicalendar->gregorian_to_jalali($date_array[0],$date_array[1],$date_array[2]);
echo(implode('/', $jalali_date). ' در ساعت ' . $date_array['3'] .':'.$date_array['4']);
						 ?>
									</span></span>
								</p>
								<p class="comment_text"><?=$comment_replay->comment_text?></p>
								<a href="#" data-name="<?php if(empty($user->firstname)): ?><?=ucfirst($user[0]->username)?><?php else: ?><?=$user[0]->firstname?><?php endif; ?>" id="<?=$comment->id?>" class="comment_reply_link">پاسخ</a>
							</li>
							
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>

		<?php endforeach; ?>
	</ul>
</div>




















{footer}
<script src="<?=base_url('public/js/')?>js.cookie.min.js"></script>
<script>

// Cookies.set('options', {mode: "webtoon"});
// console.log(Cookies.get('options'));


$(document).ready(function() {
	var $select = $('.chapter_selector select').selectize();
	var pathname = window.location.pathname.split('/');
	$select[0].selectize.setValue(pathname[pathname.length - 1]);
	$select[0].selectize.on('change',function() {
		var chapter_id = this.getValue();
		var currentLocation = window.location;
		var hostname = currentLocation.hostname;
		var pathname = currentLocation.pathname.split('/');
		pathname[pathname.length - 1] = chapter_id;
		window.location.replace("http://" + hostname+pathname.join('/'));
	});
	$('.reply_to').hide();
	$('.comment_reply_link').on('click', function(e) {
		$('.reply_to').show();
		$id = $(this).attr('id');
		$('.reply_to_name').html($(this).attr('data-name'));
		console.log($(this).attr('data-name'));
		$('#comment_reply_id').val($id);
		$('html, body').animate({
			scrollTop: $("#comment_form").offset().top
		}, 300);
	});


	$('#settings').on('click', function(e) {
		$('.options_modal').toggleClass('active');
	});

	$('#manga_mode').click(function(e) {
		e.preventDefault();
		Cookies.set('options', 'manga');
		$('.options_modal').removeClass('active');
		location.reload();
	});
	$('#webtoon_mode').click(function(e) {
		e.preventDefault();
		Cookies.set('options', 'webtoon');
		$('.options_modal').removeClass('active');
		location.reload();
	});

	$('.app').addClass(Cookies.get('options'));
	$('body').addClass(Cookies.get('options'));





});


function toggleFullscreen(elem) {
  elem = elem || document.documentElement;
  if (!document.fullscreenElement && !document.mozFullScreenElement &&
    !document.webkitFullscreenElement && !document.msFullscreenElement) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
      elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}
</script>