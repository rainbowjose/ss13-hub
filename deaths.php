<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('deaths');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
</head>

<body translate="no">
	<?php $core->navbar() ?>
	<div class="container">
		<?php if ($core->de->deathsMain() == 'page'): $p = $core->de->deathsQuery('page'); $t = $core->de->deathsQuery('total'); ?>
		<ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/deaths/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/deaths/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/deaths/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
		<table class="table">
			<thead>
				<tr>
					<th scope="col"><abbr title="ID раунда">ID</abbr></th>
					<th scope="col">Погибший</th>
					<th scope="col">Локация</th>
					<th scope="col" width='138'>Когда</th>
					<th scope="col" width='285'><span class='damage brute' title='Физический (BRUTE)'>BRU</span><span class='damage fire' title='Ожоговый (FIRE)'>FIR</span><span class='damage oxy' title='Кислородный (OXYGEN)'>OXY</span><span class='damage tox' title='Токсины (TOXIC)'>TOX</span><span class='damage brain' title='Мозговой (BRAIN)'>BRA</span><span class='damage clone' title='Генетический (CLONE)'>CLN</span><span class='damage stamina' title='Выносливость (STAMINA)'>STM</span></th>
				</tr>
			</thead>
			<tbody>
                <?php $core->de->deathsQuery('print');?>
			</tbody>
		</table>
		<ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/deaths/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/deaths/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/deaths/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/deaths/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
		<?php endif ?>
		<?php if ($core->de->deathsMain() == 'id'): $d = $core->de->deathDbQuery($_GET['id']); ?>
		<div class="death">
			<div class="d-header">
				<hr>
				<h2><span class="text-muted"><i class="fas fa-user-times"></i> <?php echo $core->de->deathQuery(($_GET['id']), 'id', $d) ?> </span><?php echo $core->de->deathQuery(($_GET['id']), 'name', $d) ?> / <span class="text-muted"><?php echo $core->de->deathQuery(($_GET['id']), 'byondkey', $d) ?></span></h2>
				<hr>
			</div>
			<div class="d-middle">
				<?php if ($core->de->deathQuery(($_GET['id']), 'laname', $d) != NULL): ?>
				<div class="d-card">
					<h3 class="d-card-h">Возможный убийца</h3>
					<div class="d-card-c">
					<?php echo $core->de->deathQuery(($_GET['id']), 'laname', $d) ?> / <span class="text-muted"><?php echo $core->de->deathQuery(($_GET['id']), 'lakey', $d) ?></span>
					</div>
				</div>
				<?php endif ?>
				<div class="d-card">
					<h3 class="d-card-h">Урон на момент смерти</h3>
					<div class="d-card-c">
					<?php echo $core->de->deathQuery(($_GET['id']), 'vitals', $d) ?>
					</div>
				</div>
				<div class="d-card">
					<h3 class="d-card-h">Должность</h3>
					<div class="d-card-c">
					<?php echo $core->de->deathQuery(($_GET['id']), 'rank', $d) ?>
					</div>
				</div>
				<div class="d-card">
					<h3 class="d-card-h">Время смерти</h3>
					<div class="d-card-c">
					<?php echo $core->de->deathQuery(($_GET['id']), 'tod', $d) ?>
					</div>
				</div>
			</div>
			<hr>
			<div class="d-bottom">
				<div class="d-card">
					<h3 class="d-card-h">Место смерти</h3>
					<div class="d-card-c">
					<?php echo $core->de->deathQuery(($_GET['id']), 'loc', $d) ?>
					</div>
				</div>
				<?php if ($core->de->deathQuery(($_GET['id']), 'lwe', $d)): ?>
				<div class="d-card">
					<h3 class="d-card-h">Последние слова</h3>
					<div class="d-card-c">
					<?php $core->de->deathQuery(($_GET['id']), 'lw', $d) ?>
					</div>
				</div>
				<?php endif ?>
			</div>
		</div>
		<?php endif ?>
	</div>
	<?php $core->footer() ?>
</body>

</html>
