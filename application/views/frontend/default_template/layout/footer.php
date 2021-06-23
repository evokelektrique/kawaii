

	<?php $date = $this->jalalicalendar->gregorian_to_jalali(date('Y'), date('m'), date('d')); ?>
	<footer>
		<?php if(isset($body_class)): ?>
		<?php if($body_class !== 'reader'): ?>
		<div class="footer_wrapper grid-noGutter grid-sm-1_xs-1">
			<?php $links = $this->link_model->get_latest_links(); ?>
			<?php foreach($links as $link): ?>
				<?php if($link->position == "bottom"): ?>
					<?php if($link->parent_id == 0 ): ?>
						<?php $child_links = $this->link_model->get_links_by_parent_id($link->id) ?>
						<div class="col">
							<h3><a href="<?=$link->address?>"><?php echo $link->name; ?></a></h3>
							<?php if(!empty($child_links)): ?>
								<ul>
									<?php foreach($child_links as $child): ?>
										<a href="<?=$link->address?>"><?php echo $child->name; ?></a>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif;?>
				<?php endif;?>
			<?php endforeach; ?>			
		<?php endif;?>
		<?php endif;?>
		</div>


		<p>تمامی حقوق محفوظ می باشد <?=$date[0]?> &copy;</p>
	</footer>
	<?php $settings = $this->settings_model->settings();?>


	<!-- Custom CSS -->
	<style type="text/css">
	<?=$settings[0]->custom_css?>
	</style>

	<!-- Custom JS -->
	<script type="text/javascript">
	<?=$settings[0]->custom_js?>
	</script>

	</body> 
</html>