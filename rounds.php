<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('rounds');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
    <script> var rounds = true;</script>
</head>
<body>
	<?php $core->navbar() ?>
    <div class="container">
	<?php if ($core->ro->roundsMain() == 'page'): $p = $core->ro->roundsQuery('page'); $t = $core->ro->roundsQuery('total');  ?>
        <ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/rounds/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/rounds/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/rounds/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/rounds/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
        <div class="notice">Логи это прикол, а приколы бывают смешные.</div>
        <table class="table" id=rnds>
            <thead>
                <tr>
                    <th scope="col" width="32"><abbr title="ID раунда">ID</abbr></th>
                    <th scope="col" width="192">Режим</th>
                    <th scope="col" width="300">Результат</th>
                    <th scope="col" width="100">Карта</th>
                    <th scope="col" width="150">Начало</th>
                    <th scope="col" width="150">Конец</th>
                </tr>
            </thead>
            <tbody>
                <?php $core->ro->roundsQuery('print');?>
            </tbody>
        </table>
	<?php endif ?>
	<?php if ($core->ro->roundsMain() == 'round' and (!isset($_GET['stat']))): $r = $core->ro->roundDbQuery($_GET['round']); ?>
		<hr>
		<div class='r-header'>
			<div class='r-id'>
				<i class="fas fa-circle"></i> <?php $core->ro->roundQuery(($_GET['round']), 'id', $r); ?><br>
				<small><?php $core->ro->roundQuery(($_GET['round']), 'map', $r); ?></small>
			</div>
			<div class='r-type'>
				<?php $core->ro->roundQuery(($_GET['round']), 'gm', $r); ?>
				<div class="r-end">
					<?php $core->ro->roundQuery(($_GET['round']), 'es', $r); ?>
				</div>
				<small><i style="color: green; text-shadow: none;" class="fas fa-play"></i> <?php $core->ro->roundQuery(($_GET['round']), 'start', $r);?> | <?php $core->ro->roundQuery(($_GET['round']), 'end', $r); ?> <i style="color: red; text-shadow: none;" class="fas fa-stop"></i></small>
			</div>
			<div title='Длительность раунда' class='r-time'>
				<?php $core->ro->roundQuery(($_GET['round']), 'time', $r); ?>
			</div>
		</div>
		<hr>
		<div class="r-info-cont">
		<div class="r-info-head">
		<div class="r-info-base">
		<h4>Базовые детали</h4>
		<table class="table r-base">
			<tbody>
				<tr>
					<th class="text-right align-middle">
						Название станции
					</th>
					<td class="align-middle">
						<?php $core->ro->roundQuery(($_GET['round']), 'sn', $r); ?>
					</td>
					<th class="text-right align-middle">
						Смерти
					</th>
					<td class="align-middle">
						<a href="/deaths/round/<?php $core->ro->roundQuery(($_GET['round']), 'id', $r); ?>"><?php $core->ro->roundQuery(($_GET['round']), 'deaths', $r); ?></a>
					</td>
				</tr>
				<tr>
					<th class="text-right align-middle">
						Эвакуационный шаттл
					</th>
					<td class="align-middle">
						<?php $core->ro->roundQuery(($_GET['round']), 'sh', $r); ?>
					</td>
					<th class="text-right align-middle">
						Логи
					</th>
					<td class="align-middle">
                        <?php $core->ro->le(); ?>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="r-info-tech">
		<h4>Технические детали</h4>
		<table class="table r-tech">
			<tbody>
				<tr>
					<th class="align-middle text-right">Длительность раунда</th>
					<td class="align-middle"><?php $core->ro->roundQuery(($_GET['round']), 'time', $r); ?></td>
                    <td class="align-middle" colspan=2></td>
				</tr>
				<tr>
					<th class="align-middle text-right">Длительность инициализации</th>
					<td class="align-middle"><?php $core->ro->roundQuery(($_GET['round']), 'itime', $r); ?></td>
					<th class="align-middle text-right">Длительность завершения</th>
					<td class="align-middle"><?php $core->ro->roundQuery(($_GET['round']), 'stime', $r); ?></td>
				</tr>
				<tr>
					<th class="align-middle text-right">Начало инициализации</th>
					<td class="align-middle"><?php $core->ro->roundQuery(($_GET['round']), 'init', $r); ?></td>
					<th class="align-middle text-right">Время завершения</th>
					<td class="align-middle"><?php $core->ro->roundQuery(($_GET['round']), 'shut', $r); ?></td>
				</tr>
			</tbody>
		</table>
		</div>
		</div>
		<hr>
		<h4>Базовая информация</h4>
		<div class="r-misc-info">
			<div class="r-misc-stats">
				<div class="r-misc-box">
					<div class="progress-bar">
                        <?php $sr = $core->ro->roundQuery(($_GET['round']), 'survrate', $r) ?>
						<?php if ($sr['players']['total'] != 0): ?>
						<div class="progress" role="progressbar" style="width:<?php echo $sr['deadp'] ?>%;" data-toggle="tooltip" title="">
							<?php echo $sr['players']['dead'] ?> погибших
						</div>
						<div class="progress alive" role="progressbar" style="width:<?php echo $sr['survp'] ?>%;" data-toggle="tooltip" title="">
							<?php echo $sr['survivors']['total'] ?> выживших
						</div>
						<div class="progress evac" role="progressbar" style="width:<?php echo $sr['escap'] ?>%;" data-toggle="tooltip" title="">
							<?php echo $sr['escapees']['total'] ?> эвакуировавшихся
						</div>
						<?php else: ?>
						<div class="progress" role="progressbar" style="width:100%;" data-toggle="tooltip" title="">
							выживших нет
						</div>
						<?php endif ?>
					</div>
				</div>
			</div>
        <?php if ($core->ro->roundQuery(($_GET['round']), 'sreason', $r) != NULL): ?>
		<div class="r-shuttle">
		<h4>Причина шаттла</h4>
			<div class="r-misc-stats">
			<?php echo $core->ro->roundQuery(($_GET['round']), 'sreason', $r); ?>
			</div>
		</div>
        <?php endif ?>
		<div class="r-bottom">
		<div class="r-antags">
		<button class="collapse">Антагонисты</button>
			<div class="contentc">
				<div class="r-antags-inner">
				<?php $core->ro->roundQuery(($_GET['round']), 'antags', $r) ?>
				</div>
			</div>
		</div>
		<div class="r-expl">
			<button class="collapse">Взрывы</button>
				<div class="contentc">
                    <div class="r-antags-inner">
					<?php $core->ro->roundQuery(($_GET['round']), 'expl', $r) ?>
                    </div>
				</div>
		</div>
		</div>
		</div>
        <div class="r-advanced">
            <h4>Статистика раунда</h4>
            <div class="r-advanced-c">
			<?php $core->ro->roundQuery(($_GET['round']), 'misc', $r); ?>
			</div>
        </div>
	<?php elseif (isset($_GET['stat'])): ?>
        <hr>
        <a href="/rounds/round/<?php echo $_GET['round']; ?>"><i class="fas fa-fw fa-circle"></i><?php echo $_GET['round']; ?></a> - <?php echo $core->ro->csn($_GET['stat']); ?>
        <hr>
        <div class='sh'>
            <?php $core->ro->statQuery(($_GET['round']), ($_GET['stat'])); ?>
        </div>
    <?php endif ?>
    </div>
	<?php $core->footer() ?>
</body>

</html>
