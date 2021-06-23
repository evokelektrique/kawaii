{header}
	<!-- Header -->
	{article}
	<div class="header_placeholder" style="
background: linear-gradient(transparent, rgba(0, 0, 0, 0.7)),url(<?=base_url('public/img/post_covers')?>/{post_cover}); background-position: center; background-size: cover;"></div>
	{/article}
	<header>
		<!-- Header Image -->
		<div class="header_wrapper">
			<!-- Links -->
			<ul class="links">
				<?php if(!empty($user)): ?>
					<?php foreach($user as $u): ?>
						<div class="user_profile">
							<a href="<?=base_url('profile/'.$u->username)?>">
								<img src="<?=base_url('public/img/profile_images/'.$u->profile_picture_url)?>" width="48" alt="<?=$u->firstname.' '.$u->lastname?>">
								<span><?=$u->username?></span>
							</a>
						</div>
					<?php endforeach; ?>
					<li><a href="<?= base_url('auth/logout') ?>">خروج</a></li>
					<?php else: ?>
					<li><a href="<?= base_url('auth') ?>">ورود</a></li>

				<?php endif; ?>
				<?php foreach($links as $link): ?>
					<?php if($link->position == "top"): ?>
						<?php if($link->parent_id == 0 ): ?>
							<?php $child_links = $this->link_model->get_links_by_parent_id($link->id) ?>
							<li><a href="<?=$link->address?>"><i class="<?=$link->icon?>"></i><?php echo $link->name; ?></a>
							<?php if(!empty($child_links)): ?>
								<ul>
									<?php foreach($child_links as $child): ?>
										<a href="<?=$link->address?>"><i class="<?=$child->icon?>"></i><?php echo $child->name; ?></a>
									<?php endforeach; ?>
								</ul>
							</li>
							<?php else: ?>
							</li>
							<?php endif; ?>
						<?php endif;?>
					<?php endif;?>
				<?php endforeach; ?>
				<li><a href="<?= base_url('contact') ?>">تماس با ما</a></li>
				<li><a href="<?= base_url('about') ?>">درباره ما</a></li>
				<a class="logo" href="<?=base_url()?>">
					<img height="48" src="{settings}<?=base_url('{logo_url}')?>{/settings}" alt="">
				</a>
			</ul>
			<!-- Title and descriptions -->
			{article}
			<h1 id="title">
				{name}
			</h1>
			{/article}
		</div>
	</header>
	
	<section class="post_details post_details_ grid-noGutter">
		{article}
		<div class="detail_box empty_box detail_image_box">
			<div class="detail_image_box_placeholder" style="background-image: url(<?=base_url('public/img/post_images')?>/{post_image});"></div>
			<div class="detail_image_box_img liked_article" style="background-image: url(<?=base_url('public/img/post_images')?>/{post_image});"><?php echo($is_liked) ? '<i class="fas fa-bookmark"></i>' : '';?></div>
			<div class="article_links">
				<a class="download-button" href="<?=base_url('download/{id}')?>"><i class="fas fa-download"></i>دانلود </a>
				<a href="#" class="like single_like" data-article_id='{id}'><i class="fas fa-heart"></i><span style="display: none;"><?=count($likes)?></span></a>
			</div>
		</div>
		<div class="detail_box black_box detail_tags">
			<div class="detail_row">
				<span class="detail_column_tag">نام</span>
				<p class="detail_column_content">{name}</p>
			</div>
			<div class="detail_row">
				<span class="detail_column_tag">نویسنده</span>
				<p class="detail_column_content">{author}</p>
			</div>
			<div class="detail_row">
				<span class="detail_column_tag">تعداد چپتر</span>
				<p class="detail_column_content"><?php echo(count($chapters)); ?></p>
			</div>
			<div class="detail_row">
				<span class="detail_column_tag">وضعیت</span>
				<p class="detail_column_content">
					<?php 
						switch ($article[0]->status) {
						 	case 1:
						 		echo "نا معلوم";
						 		break;
						 	case 2:
						 		echo "در حال پخش";
						 		break;
						 	case 3:
						 		echo "متوقف شده";
						 		break;
						 	default:
						 		break;
						 } 
					 ?>
				</p>
			</div>
			<div class="detail_row">
				<span class="detail_column_tag">تاریخ انتشار</span>
				<p class="detail_column_content">{release_date}</p>
			</div>
			<div class="detail_row">
				<span class="detail_column_tag">ژانر</span>
				<p class="detail_column_content">
					<?php $tags = explode(',', $article[0]->tags); ?>
					<?php foreach($tags as $tag ): ?>
						<a href="#" style="color: #fff"><?=$tag?></a>,&nbsp;
					<?php endforeach; ?>
				</p>
			</div>			
			<div class="detail_row">
				<span class="detail_column_tag">پسندیده ها</span>
				<p class="detail_column_content">
					<i class="fas fa-heart"></i>&nbsp;<span><?=count($likes)?></span>
				</p>
			</div>
		</div>
		{/article}
	</section>
	<div class="post_details_wrapper">
		<section class="post_details descriptions_details grid-noGutter">
			<div class="detail_box black_box">
				<p class="detail_column_content detail_title">خلاصه داستان</p>
				<p class="description_content">
					{article}
						{description}
					{/article}

					<div class="post_pictures">
						<?php if(!empty($screenshots)): ?>
						<?php foreach($screenshots as $sc): ?>
							<img src="data:image/png;base64,<?= $this->mangareader->view_image($sc->image_name) ?>" alt="">
						<?php endforeach; ?>
						<?php else: ?>
							عکسی موجود نمی باشد
						<?php endif; ?>
					</div>

				</p>
				<div class="ad reader_ad">
					{settings}{ads2}{/settings}
				</div>
			</div>
		</section>
		<section class="post_details similar_posts_single_details grid-noGutter">
			<div class="detail_box similar_posts_single">
				<h2>مطالب  مشابه</h2>
				<ul>
					{similar_posts}
					<li><a href="<?=base_url()?>{url_slug}/{id}"><img src="<?=base_url('public/img/post_covers/{post_cover}')?>"></a></li>
					{/similar_posts}
				</ul>
				<div class="ad">
					{settings}{ads3}{/settings}
				</div>
			</div>
		</section>
	</div>
	<section class="post_details descriptions_details grid-noGutter">
		<div class="detail_box black_box">
			<p class="detail_column_content detail_title">فصل ها</p>
			<ul class="post_chapters">
				<?php if(!empty($chapters)): ?>
				<?php foreach($chapters as $chapter): ?>
				<li class="">
					<a href="<?=base_url()?><?=$article[0]->url_slug?>/<?=$article[0]->id?>/chapter/<?=$chapter->id?>"><?=$chapter->name?></a>
					<span class="post_chapter_date">
						<?php 
$time = strtotime($chapter->created_at);
$date = date('Y-m-d-H-i-s', $time);
$date_array = explode('-', $date);
$jalali_date = $this->jalalicalendar->gregorian_to_jalali($date_array[0],$date_array[1],$date_array[2]);
echo(implode('/', $jalali_date));
						 ?>
					</span>
<!-- 					<span class="post_chapter_options">
						<i class="fas fa-ellipsis-h"></i>
						<ul class="post_more_options_menu">
							<li><i class="fas fa-bookmark"></i>ذخیره</li>
							<li><i class="fas fa-exclamation-triangle"></i>گزارش</li>
						</ul>
					</span> -->
					<?php if($article[0]->allow_download == 'yes'): ?>
						<a class="download_chapter" href="<?=base_url()?>download_chapter/<?=$chapter->id?>">دانلود</a>
					<?php else: ?>
						<a class="download_chapter disabled" href="<?=base_url()?>download_chapter/<?=$chapter->id?>">دانلود</a>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
				<?php else: ?>
					<p style="text-align: center">موردی یافت نشد</p>
				<?php endif; ?>
			</ul>
		</div>
	</section>


{footer}