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
		<div class="section_title white news_title"><span>درباره ما</span></div>

		<div class="contact_form">
			{settings}
				{about_us_text}
			{/settings}
		</div>



	</section>





{footer}