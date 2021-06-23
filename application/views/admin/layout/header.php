<html>
	<head>
		<title>داشبورد</title>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	    <link rel="stylesheet" href="<?=base_url()?>public/css/materialize.min.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/Chart.min.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/dropify.css">
		<link rel="stylesheet" href="<?=base_url()?>public/css/dashboard.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <script src="<?=base_url()?>public/js/jquery.min.js"></script>
	    <script src="<?=base_url()?>public/js/Chart.min.js"></script>
	    <script src="<?=base_url()?>public/js/materialize.min.js"></script>
	    <script src="<?=base_url()?>public/js/pace.min.js"></script>
	    <script src="<?=base_url()?>public/js/dropify.js"></script>


	</head>
	<body>


	<div class="wrapper">

		<a class="btn-floating pulse blue-grey sidebar_trigger"><i class="material-icons">menu</i></a>
		
		<?php if($this->session->flashdata('success')): // Success Toasts ?>
		    <script>
		        M.toast({
		            html: '<?=$this->session->flashdata('success')?>',
		            classes: 'custom_toast green lighten-1',
		        });
		    </script>
		<?php endif; ?>
		<?php if($this->session->flashdata('unsuccess')):  // Unsuccess Toasts?>

		    <script>
		        M.toast({
		            html: '<?=$this->session->flashdata('unsuccess')?>',
		            classes: 'custom_toast red lighten-1',
		        });
		    </script>
		<?php endif; ?>






