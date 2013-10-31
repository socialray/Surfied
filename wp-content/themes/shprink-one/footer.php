<?php
/**
 * Template footer
 *
 * @package     WordPress
 * @subpackage  shprink_one
 * @since       1.0
 */
$condition = is_active_sidebar('footer-widget-left') || is_active_sidebar('footer-widget-middle-left') || is_active_sidebar('footer-widget-middle-right') || is_active_sidebar('footer-widget-right');
?>
<?php if ($condition) : ?>
	<footer id="footer" class="well well-small">
		<div class="footer-inner">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-lg-3">
						<?php if (is_active_sidebar('footer-widget-left')) : ?>
							<?php dynamic_sidebar('footer-widget-left'); ?>
						<?php endif; ?>
					</div>
					<div class="col-md-3 col-lg-3">
						<?php if (is_active_sidebar('footer-widget-middle-left')) : ?>
							<?php dynamic_sidebar('footer-widget-middle-left'); ?>
						<?php endif; ?>
					</div>
					<div class="col-md-3 col-lg-3">
						<?php if (is_active_sidebar('footer-widget-middle-right')) : ?>
							<?php dynamic_sidebar('footer-widget-middle-right'); ?>
						<?php endif; ?>
					</div>
					<div class="col-md-3 col-lg-3">
						<?php if (is_active_sidebar('footer-widget-right')) : ?>
							<?php dynamic_sidebar('footer-widget-right'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</footer>
<?php endif; ?>

<?php wp_footer(); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Add Bootstrap class to lists within the sidebar
		$('#sidebar .widget ul').addClass('nav nav-pills nav-stacked');
		$('footer .widget ul').addClass('nav nav-pills nav-stacked');
		$('.widget_recent_comments ul').removeClass('nav nav-tabs nav-pills nav-stacked').addClass('list-unstyled');
		$('[data-toggle=tooltip]').tooltip()
		$('#header ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			// Avoid following the href location when clicking
			event.preventDefault();
			// Avoid having the menu to close when clicking
			event.stopPropagation();
			// If a menu is already open we close it
			$('#header ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
			// opening the one you clicked on
			$(this).parent().addClass('open');
		});
	});
</script>
</body>
</html>