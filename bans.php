<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('bans');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
</head>
<body>
    <?php $core->navbar() ?>
    <div class="container">
		<div class="ontop">
        <?php $p = $core->ba->bansQuery('page'); $t = $core->ba->bansQuery('total'); ?>
        <ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/bans/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/bans/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/bans/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
		<div class="search">
			<form action="" method="POST">
				<input type="text" name="ckey" placeholder="player's ckey" />
				<input type="text" name="a_ckey" placeholder="admin's ckey" />
				<input type="submit" value="Поиск" />
			</form>
		</div>
		</div>
        <table class="table table-bordered table-sm table-striped small">
            <thead>
                <tr>
                    <th scope="col" rowspan='2' width='48' title="ID раунда">ID</th>
                    <th scope="col" width='155'>Player's CKey</th>
					<th scope="col" width='155'>Admin's CKey</th>
					<th scope="col" colspan='2' width='40'>Длительность</th>
                    <th scope="col" colspan='30' rowspan='2'>Описание</th>
                </tr>
				<tr>
					<th scope="col" width='155'>Выдан</th>
					<th scope="col" width='155'>Заканчивается</th>
					<th scope="col" width='100'>Тип</th>
					<th scope="col" width='16' title="Разбанен?">Р</th>
				</tr>
            </thead>
            <tbody>
                <?php $core->ba->bansQuery('print');?>
            </tbody>
        </table>
        <ul class="pagination">
			<?php if ($p != 1): ?>
			<a class="page-link" href="/bans/page/1">First</a>
			<?php endif ?>
			<?php if ($p >= 4): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 3; ?>"><?php echo $p - 3; ?></a>
			<?php endif ?>
			<?php if ($p >= 3): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 2; ?>"><?php echo $p - 2; ?></a>
			<?php endif ?>
			<?php if ($p >= 2): ?>
			<a class="page-link" href="/bans/page/<?php echo $p - 1; ?>"><?php echo $p - 1; ?></a>
			<?php endif ?>

			<a class="page-link active" href="/bans/page/<?php echo $p; ?>"><?php echo $p; ?></a>

			<?php if ($p < $t): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 1; ?>"><?php echo $p + 1; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 1)): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 2; ?>"><?php echo $p + 2; ?></a>
			<?php endif ?>
			<?php if ($p < ($t - 2)): ?>
			<a class="page-link" href="/bans/page/<?php echo $p + 3; ?>"><?php echo $p + 3; ?></a>
			<?php endif ?>
			<?php if ($p != $t): ?>
			<a class="page-link" href="/bans/page/<?php echo $t; ?>">Last</a>
			<?php endif ?>
        </ul>
    </div>
	<?php $core->footer() ?>
</body>

</html>
