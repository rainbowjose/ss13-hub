<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('library');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
</head>
<body>
	<?php $core->navbar() ?>
    <div class="container mt-3">
		<?php if ($core->li->libraryMain() == 'page'): $p = $core->li->libraryQuery('page'); $t = $core->li->libraryQuery('total'); ?>
        <ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/library/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/library/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/library/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
			<table class="table table-sm table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Автор</th>
						<th>Название</th>
						<th>Категория</th>
						<th width=32><i class="fas fa-star"></i></th>
					</tr>
				</thead>
				<tbody>
					<?php $core->li->libraryQuery('print');?>
				</tbody>
			</table>
		<ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/library/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/library/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/library/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/library/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/library/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
		<?php endif ?>
		<?php if ($core->li->libraryMain() == 'id'): ?>
		<div class="book">
			<h3 class="book-header">
				<?php echo $core->li->bookQuery(($_GET['id']), 'title') ?>
				<small class="text-muted">от <?php echo $core->li->bookQuery(($_GET['id']), 'author') ?></small>
				<div class="rating"><?php echo $core->li->bookQuery(($_GET['id']), 'rr') ?> - <?php echo $core->li->bookQuery(($_GET['id']), 'rating') ?></div>
			</h3>
			<div class="book-content">
				<?php echo $core->li->bookQuery(($_GET['id']), 'content') ?>
			</div>
			<div class="book-footer">
				Опубликовано
				<span class="timestamp">
					<time><?php echo $core->li->bookQuery(($_GET['id']), 'time') ?></time>
				</span>
				во время раунда <a href="/rounds/<?php echo $core->li->bookQuery(($_GET['id']), 'round') ?>"><i class="far fa-circle"></i> <?php echo $core->li->bookQuery(($_GET['id']), 'round') ?></a>
			</div>
		</div>
		<?php endif ?>
    </div>
	<?php $core->footer() ?>
</body>

</html>
