<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('play');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
</head>

<body translate="no">
	<?php $core->navbar() ?>
	<div class="container">
		<div class="how-content">
			<hr>
			<div class="how-header">
			Space Station 13
			</div>
			<hr>
			<div class="how-quote">
				<div class="how-text">
				"Ты СОВЕРШЕННО не понимаешь в чем суть Space Station. Space Station это не браузерка «о, привет чуваки, зацените прикольную вещицу нашел, гыгы». Space Station это не симулятор бюрократии. Space Station это не SA:MP или CS. Space Station это место, где люди могут побыть чудовищами — ужасными, бесчувственными, безразличными чудовищами, которыми они на самом деле и являются. Захватили медбэй убив всех обезьян, а мы смеемся. Клоуны подожгли вардена, за отказ дать наручники, а мы смеемся. Три химика, приняв 8 таблеток калия и запив водой, взорвали себя, взявшись за руки, а мы смеемся и просим еще. Самоубийства, убийства, геноцид — мы смеемся. Расизм, сексизм, дискриминация, ксенофобия, изнасилования, беспричинная ненависть — мы смеемся. Офицер СБ убил капитана из шприцемёта — мы смеемся. Мы бездушно подпишемся под чем угодно, наши предпочтения не основаны на здравом смысле, бесцельные споры — наша стихия, мы — истинное лицо космоса." - lurkmore.to
				</div>
			</div>
			<hr>
			<div class="how-header">
			Как начать играть?
			</div>
			<hr>
			<div class="how-to">
				<ul>
					<li>
					В первую очередь тебе понадобится аккаунт BYOND. Создать его можно здесь: <a href="https://secure.byond.com/Join" target="_blank">https://secure.byond.com/Join</a>
					</li>
					<li>
					Далее нужен [BETA] клиент BYOND. Загрузить его можно тут: <a href="http://www.byond.com/download/build/512/512.1456_byond.exe">512.1456_byond.exe</a> (если знаешь, что делаешь: <a href="http://www.byond.com/download/" target="_blank">http://www.byond.com/download/</a>)
					</li>
					<li>
					Теперь подключаемся к серверу переходом по ссылке (браузер скорее всего сделает запрос на разрешение, лучше дозволить ему это для удобства):
					<ul style="list-style: square">
						<li>
						Yellow /tg/Station: <a href="byond://frosty.space:2019"> byond://frosty.space:2019</a> | <a href="https://tgstation13.org/wiki/Main_Page" target="_blank">WIKI [EN]</a>
						</li>
						<li>
						/vg/Station: <a href="byond://frosty.space:2025"> byond://frosty.space:2025</a> | <a href="https://ss13.moe/wiki/index.php/Main_Page" target="_blank">WIKI [EN]</a>
						</li>
					</ul>
					</li>
				</ul>
			</div>
			<hr>
			<div class="how-header">
			У меня остались вопросы
			</div>
			<hr>
			<div class="how-to-suck">
				<ul>
					<li>
					Если у тебя появятся вопросы по игре, то смело спрашиваем в нашей дружелюбной конференции ответы на них: <a href="https://discord.gg/5aXdgXv" target="_blank">https://discord.gg/5aXdgXv</a>
					</li>
					<li>
					Так как Wiki у нас не переведён до конца, пользуемся пока этим (некоторая информация может быть устаревшей): <a href="https://wiki.ss13.ru/index.php?title=Getting_Started" target="_blank">Getting Started (wiki.ss13.ru)</a>
					</li>
				</ul>
				<div class="how-to-fuck-this-shit-im-going-to-kill-myself">
				Обзоры с YouTube:
				</div>
				<div class="how-video">
					<center>
					<iframe width="560" height="315" src="https://www.youtube.com/embed/PAim8Cg1zHw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					<iframe width="560" height="315" src="https://www.youtube.com/embed/nLAHBexJxrE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</center>
				</div>
				<div class="how-source">
				Другие источники: <a href="https://ru.wikipedia.org/wiki/Space_Station_13" target="_blank">Space Station 13 (ru.wikipedia.org)</a> | <a href="http://lurkmore.to/Space_Station_13" target="_blank">Space Station 13 (lurkmore.to)</a> | <a href="https://dtf.ru/flood/17925-space-station-13-adskaya-stanciya" target="_blank">Space Station 13 - Адская станция (dtf.ru)</a>
				</div>
			</div>
		</div>
	</div>
	<?php $core->footer() ?>
</body>

</html>
