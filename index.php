<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('main');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
    <script>
    setInterval(function(){
        $("#ys").attr("src", "/status/?server=yellow&"+new Date().getTime());
    },5000);
    /*
    setInterval(function(){
        $("#fs").attr("src", "/status/?server=fallout&"+new Date().getTime());
    },5000);

    setInterval(function(){
        $("#vg").attr("src", "/status/?server=vgstation&"+new Date().getTime());
    },5000);
    */
    </script>
</head>

<body translate="no">
	<?php $core->navbar() ?>
	<div class="index-page">
		<div class="index-left">
            <div class="index-short">
                <div class="index-short-head">
                <h3>Header</h3>
                </div>
                <div class="index-short-content">
                <p>Content</p>
                </div>
            </div>
		</div>
		<div class="index-right">
			<center><a rel="nofollow" href="byond://frosty.space:2019"><img id="ys" src="/status/?server=yellow"></a></center><br>
            <!--<center><a rel="nofollow" href="byond://frosty.space:2025"><img id="vg" src="/status/?server=vgstation"></a></center><br>
			<center><a rel="nofollow" href="byond://frosty.space:1337"><img id="fs" src="/status/?server=fallout"></a></center>-->
		</div>
	</div>
	<?php $core->footer() ?>
</body>

</html>
