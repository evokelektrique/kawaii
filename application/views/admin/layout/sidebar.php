<!-- Sidebar -->
<div class="sidebar">
	<?php 
	$settings = $this->settings_model->settings()[0];
	?>
	<h2 class="web_name"><a href="<?=base_url()?>"><?= $settings->site_name ?></a></h2>
	<ul class="">
		<li>
			<a href="<?=base_url('dashboard').'/upload'?>" class="waves-effect waves-light upload button"><span>مطلب جدید</span> <i class="material-icons right">note_add</i></a>
			<a href="<?=base_url('dashboard').'/'?>" class="waves-effect <?php echo ($this->uri->segment(2) == '')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">dashboard</i><span>صفحه اصلی</span></a>
			<a href="<?=base_url('dashboard').'/archive'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'archive')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">archive</i><span>مطالب</span></a>
			<a href="<?=base_url('dashboard').'/categories'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'categories')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">create_new_folder</i><span>دسته بندی ها</span></a>
			<a href="<?=base_url('dashboard').'/comments'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'comments')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">chat</i><span>نظرات</span></a>
			<a href="<?=base_url('dashboard').'/links'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'links')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">settings</i><span>لینک ها</span></a>
			<a href="<?=base_url('dashboard').'/alerts'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'alerts')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">error</i><span>گزارش ها</span></a>
			<a href="<?=base_url('dashboard').'/contacts'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'contacts')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">mail</i><span>پیام ها</span></a>
			<a href="<?=base_url('dashboard').'/settings'?>" class="waves-effect <?php echo ($this->uri->segment(2) == 'settings')?'blue-grey lighten-1 active' : ''?>"><i class="material-icons right">settings</i><span>تنظیمات</span></a>
			<a href="<?=base_url().'auth/logout'?>" class="waves-effect orange-text"><i class="material-icons right">exit_to_app</i><span>خروج</span></a>
		</li>
	</ul>

	<div class="sidebar_bg" style="background-image:url(<?=base_url()?>public/img/backgrounds/catgirl2.jpg)"><a href="#">نسخه 1.0</a></div>
</div>
