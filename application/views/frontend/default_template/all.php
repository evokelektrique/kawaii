{header}
	<!-- Header -->
	<div class="header_placeholder"></div>
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
			<h1 id="title">
				{settings}{site_name}{/settings}
			</h1>
			<h3 id="description">
				{settings}{site_description}{/settings}
			</h3>
			<!-- Search -->
			<div class="search">
				<form id="search_form_home">
					<input type="text" class="search_input" value="<?php if(!empty($this->uri->segment(2)) && $this->uri->segment(2) !== 'latest') {echo urldecode(str_replace("%20"," ",$this->uri->segment(2)));} else {}?>">
					<button><i class="fas fa-search"></i></button>
				</form>
			</div>
		</div>
	</header>


	<section id="wrapper">
		<!-- Title -->
		<div class="section_title white news_title"><span>{sort_name}</span></div>
		
		<!-- Articles -->
		<ul class="articles grid-6_md-4_sm-3_xs-2">
			<?php foreach($articles as $article):?>
			<?php if($this->session->has_userdata('logged_in')): ?>
				<?php if($this->post_model->is_liked($article->id, $this->session->userdata('user_id'))): ?>
				<li class="col liked_articles">
					<i class="fas fa-bookmark liked_icon"></i>
				<?php else: ?>
				<li class="col">
				<?php endif; ?>
			<?php else: ?>
			<li class="col">
			<?php endif; ?>
				<div class="article_image">
					<img src="<?=base_url('./public/img/post_images/')?><?=$article->post_image?>" alt="">
				</div>
				<div class="article_image_placeholder" style="background-image: url('<?=base_url('./public/img/')?>post_images/<?=$article->post_image?>')"></div>
				<div class="article_description_placeholder">
					<p class="article_description_content">
						<?= substr($article->description, 0, 140) ?>...
						<div class="article_options">
							<a href="#" class="article_more_options">
								<i class="fas fa-ellipsis-h"></i>
								بیشتر
								<ul class="article_more_options_menu">
									<li><i class="fas fa-bookmark"></i>ذخیره</li>
									<li><i class="fas fa-exclamation-triangle"></i>گزارش</li>
								</ul>
							</a>
							<a href="<?=base_url()?><?=$article->url_slug?>/<?=$article->id?>" class="article_more_links">
								<i class="fas fa-chevron-right"></i>
								ادامه
							</a>
						</div>
					</p>
				</div>
				<div class="article_content">
					<div class="article_link">
						<a href="<?=base_url()?><?=$article->url_slug?>/<?=$article->id?>" class="article_title_link" title="<?=$article->name?>"><?=$article->name?></a>
						<span class="genre">
							<?php $tags = explode(',', $article->tags); ?>
							<?php foreach($tags as $tag): ?>
								<a href="<?= base_url('/tag/'.$tag)?>"><?=$tag?></a>, 
							<?php endforeach; ?>
						</span>
					</div>
					<div class="article_description">
						<span class="title"><i class="fas fa-eye"></i><?=$article->view_count?></span>
						<a href="#" class="like" data-article_id='<?=$article->id?>'>
							<b class="rate">
								<i class="fas fa-heart"></i>
								<span>
									<?=count($this->post_model->get_likes($article->id))?>
								</span>
							</b>
						</a>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="ad">
			{settings}{ads1}{/settings}
		</div>
		<?php if(!empty($pagination)): ?>
		<ul class="page_links_wrapper">
			<div class="page_links">
				{pagination}
			</div>
		</ul>
		<?php endif; ?>

	</section>




{footer}