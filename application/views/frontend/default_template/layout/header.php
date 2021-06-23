<html>
	<head>
		<title><?=$title?> - <?php $settings = $this->settings_model->settings(); echo $settings[0]->site_name;?></title>
		<meta name="description" content="<?=$settings[0]->site_description?> <?= $settings[0]->site_tags?>">
		<meta name="og:title" property="og:title" content="<?=$title?> - <?=$settings[0]->site_name;?>">
		<meta name="robots" content="index, follow">
		<link href="URL" rel="canonical">


		<meta charset="UTF-8">
		<link rel="stylesheet" href="<?=base_url()?>public/css/<?=$template_name?>/gridlex.min.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/<?=$template_name?>/jquery.modal.min.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/<?=$template_name?>/jquery.toast.css">
		<?php if(isset($body_class)): ?>
		<?php if($body_class == 'reader'): ?>
		<link rel="stylesheet" href="<?=base_url()?>public/css/<?=$template_name?>/selectize.min.css">
		<?php endif; ?>
		<?php endif; ?>
		<link rel="stylesheet" href="<?=base_url()?>public/css/<?=$template_name?>/style.css">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <script src="<?=base_url()?>public/js/jquery.min.js"></script>
	    <script src="<?=base_url()?>public/js/jquery.toast.js"></script>
	    <script src="<?=base_url()?>public/js/jquery.modal.min.js"></script>
	    <script>
	    	<?php if($body_class == "tag"): ?>
	    	var base_url = '<?=base_url("tag");?>';
	    	<?php elseif($body_class == "search_"): ?>
	    	var base_url = '<?=base_url("search");?>';
	    	<?php else: ?>
	    	var base_url = '<?=base_url();?>';
	    	<?php endif; ?>
	    	var csrf_name = '<?=$csrf_name?>';
	    	var csrf_hash = '<?=$csrf_hash?>';
		</script>
	    <script src="<?=base_url()?>public/js/script.js"></script>
			
		<?php if(isset($body_class)): ?>
		<?php if($body_class == 'reader'): ?>
	    <script src="<?=base_url()?>public/js/selectize.min.js"></script>
		<?php endif; ?>
		<?php endif; ?>
		
		<script src="https://kit.fontawesome.com/36915bfa78.js"></script>

	</head>
	<body class="<?php echo (!empty($body_class)) ? $body_class : 'default'?>">


<!-- Alert Modal -->
<div id="alert_modal" class="modal">
	<div id="modal">
		<div class="modal_seperate">
			<p>گزارش تخلف مطلب مورد نظر طبق <a href="#">قوانین و شرایط استفاده</a> ما</p>
		</div>

		<div class="modal_seperate">
			<form id="alert_form">
				<label for="alert_text">متن گزارش</label>
				<input id="form_alert_id" type="hidden" name="id" value="">
				<input id="form_alert_type" type="hidden" name="type" value="">
				<input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
				<textarea name="text" id="alert_text"></textarea>

				<button>ارسال</button>
			</form>
		</div>
	</div>

  <!-- <a href="#" rel="modal:close">بستن</a> -->
</div>

<?php if($this->session->flashdata('success')):  // Unsuccess Toasts?>

    <script>
		$.toast({
			text: '<?=$this->session->flashdata('success')?>',
			heading: 'دانلود',
			showHideTransition: 'fade',
			allowToastClose: true,
			hideAfter: 3000,
			loader: false,
			loaderBg: '9EC600',
			stack: 1,
			position: 'bottom-right', 
			bgColor: "green",
			textColor: '#eee',
			textAlign: 'right',
			icon: false,
			beforeShow: function () {},
			afterShown: function () {},
			beforeHide: function () {},
			afterHidden: function () {},
			onClick: function () {}
		});
    </script>
<?php endif; ?>


<?php if($this->session->flashdata('unsuccess')):  // Unsuccess Toasts?>

    <script>
		$.toast({
			text: '<?=$this->session->flashdata('unsuccess')?>',
			heading: 'دانلود',
			showHideTransition: 'fade',
			allowToastClose: true,
			hideAfter: 3000,
			loader: false,
			loaderBg: '9EC600',
			stack: 1,
			position: 'bottom-right', 
			bgColor: "#f08000",
			textColor: '#eee',
			textAlign: 'right',
			icon: false,
			beforeShow: function () {},
			afterShown: function () {},
			beforeHide: function () {},
			afterHidden: function () {},
			onClick: function () {}
		});
    </script>
<?php endif; ?>
