<?php use MLA\Tuileries\Titles; ?>

<?php if ( Titles\title() ): ?>
	<div class="page-header">
		<h1>
			<?php echo Titles\title(); ?>
		</h1>
	</div>
<?php endif; ?>
