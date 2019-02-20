<?php
    define("INDEX", "yes");
    include '../core/class.core.php';
    $core = new Core();
    $core->loc('dcp');
    $c = new Cpi();
?>
<!DOCTYPE html>
<html>
    <head>
        <head>
        	<?php $core->header() ?>
        <style>
            .dm {
                width: 1200px;
                margin: auto;
                margin-top: 16px;
                display: flex;
                font-size: 12px;
                color: var(--a-color);
            }
            .dmh {
                background: var(--hcolor);
                width: 216px;
                height: 44px;
                border: 1px var(--color3) solid;
            }
            .drh, .deh {
                background: var(--hcolor);
                width: 900px;
                padding: 4px;
                border: 1px var(--color3) solid;
            }
            .deh {
                width: 1190px;
            }
            .sh, .ch, #ss, #cs {
                display: inline-block;
                padding: 4px;
            }
            #ss, #cs {
                float: right;
            }
            .sh, .ch {
            }
            .tabs{
                margin-left: 4px;
                display:inline-block;
            }
            .tabs > div{
                padding-top: 0px;
            }
            .tabs ul{
                margin:0px;
                padding:0px;
            }
            .tabs ul:after{
                content:"";
                display:block;
                clear:both;
                height:1px;
                background: var(--dcolor);
            }
            .tabs ul li{
                margin:0px;
                padding:0px;
                cursor:pointer;
                display:block;
                float:left;
                padding:5px 10px;
                background: var(--hcolor);
                color:#707070;
            }
            .tabs ul li.active, .tabs ul li.active:hover{
                background: var(--dcolor);
                color:#fff;
            }
            .tabs ul li:hover{
                background-color: var(--color42);
            }
            .dmc {
                display: block;
                padding-left: 3px;
                background: var(--hcolor);
                width: 213px;
                margin-top: 4px;
                height: 49px;
                border: 1px var(--color3) solid;
            }
            .btn {
                cursor: pointer;
                display: inline-block;
                border: 1px var(--color3) solid;
                color: var(--acolor);
                padding: 3px;
                margin-top: 3px;
                width: 60px;
                height: 12px;
                font-size: 8pt;
                text-align: center;
                background-color: var(--hcolor);
            }
            .btn:hover {
                background-color: var(--color42);
            }
            .conform {
                color: #aaa;
                background: var(--hcolor);
                border: 1px var(--color3) solid;
            }
            .nav-dev {
                height: 32px;
                margin-top: -8px;
            }
            .nav-dev .navbar-in {

            }
            .navbar-brand::after {
                border-style: solid;
                border-width: 10px 5px 10px 5px;
                border-color: var(--color2) transparent transparent var(--color2);
                position: absolute;
                left: 79px;
                content: "\A";
            }
        </style>
        <script>
        (function($){
            jQuery.fn.lightTabs = function(options){

                var createTabs = function(){
                    tabs = this;
                    i = 0;

                    showPage = function(i){
                        $(tabs).children("div").children("div").hide();
                        $(tabs).children("div").children("div").eq(i).show();
                        $(tabs).children("ul").children("li").removeClass("active");
                        $(tabs).children("ul").children("li").eq(i).addClass("active");
                    }

                    showPage(0);

                    $(tabs).children("ul").children("li").each(function(index, element){
                        $(element).attr("data-page", i);
                        i++;
                    });

                    $(tabs).children("ul").children("li").click(function(){
                        showPage(parseInt($(this).attr("data-page")));
                    });
                };
                return this.each(createTabs);
            };
        })(jQuery);

        function log() {
            $.ajax({
                url: "index.php?json=dd",
                cache: false,
                success: function(rs){
                    $("#dd").html(rs);
                }
            }),
            $.ajax({
                url: "index.php?json=cl",
                cache: false,
                success: function(rs){
                    $("#cl").html(rs);
                }
            }),
            $.ajax({
                url: "index.php?json=ul",
                cache: false,
                success: function(rs){
                    $("#ul").html(rs);
                }
            }),
            $.ajax({
                url: "index.php?json=dcl",
                cache: false,
                success: function(rs){
                    $("#dcl").html(rs);
                }
            })
        }

        function compile() {
            $.ajax({
                url: "index.php?json=compile",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            })
        }

        function stop() {
            $.ajax({
                url: "index.php?json=stop",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            })
        }

        function start() {
            $.ajax({
                url: "index.php?json=start",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            })
        }

        function update() {
            $.ajax({
                url: "index.php?json=update",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            })
        }

        function reload() {
            $.ajax({
                url: "index.php?json=reload",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            })
        }

        function show() {
            $.ajax({
                url: "index.php?json=ss",
                cache: false,
                success: function(rs){
                    $("#ss").html(rs);
                }
            }),
            $.ajax({
                url: "index.php?json=cs",
                cache: false,
                success: function(rs){
                    $("#cs").html(rs);
                }
            })
        }

        $(function() {
            $('#start').click(function() {
                $.ajax({
                    url: "index.php?json=start"
                });
            });
            $('#stop').click(function() {
                $.ajax({
                    url: "index.php?json=stop"
                });
            });
            $('#reload').click(function() {
                $.ajax({
                    url: "index.php?json=reload"
                });
            });
            $('#update').click(function() {
                $.ajax({
                    url: "index.php?json=update"
                });
            });
            $('#compile').click(function() {
                $.ajax({
                    url: "index.php?json=compile"
                });
            });
            $('#revert').click(function() {
                $.ajax({
                    url: "index.php?json=revert"
                });
            });
        });

        $(document).ready(function(){
            $(".tabs").lightTabs();
            show();
            log();
            setInterval('show()', 1000);
            setInterval('log()', 5000);
        });
        </script>
    </head>
    <body translate="no">
        <div class="navbar nav-dev">
            <div class="navbar-in">
                <a class="navbar-brand" href="/">Frosty DEV</a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="?p=log">
                        Starter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?p=config">
                        Config Editor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?p=wordconfig">
                        Bad Words
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?p=bookconfig">
                        Bibliotekary
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="dm">
                <?php if (isset($_GET['p']) and $_GET['p'] == 'log'): ?>
            <div class="left">
                <div class="dmh">
                    <div class="sh">Статус сервера:</div>
                    <div id="ss"></div>
                    <div class="ch">Статус компиляции:</div>
                    <div id="cs"></div>
                </div>
                <div class="dmc">
                    <div class="btn" id="start">
                    START
                    </div>
                    <div class="btn" id="stop">
                    STOP
                    </div>
                    <div class="btn" id="reload">
                    RELOAD
                    </div>
                    <div class="btn" id="update">
                    UPDATE
                    </div>
                    <div class="btn" id="compile">
                    COMPILE
                    </div>
                    <div class="btn" id="revert">
                    REVERT
                    </div>
                </div>
            </div>
            <div class="tabs">
                <ul>
                    <li>DCP log</li>
                    <li>Dream Daemon</li>
                    <li>Compile</li>
                    <li>Update</li>
                </ul>
                <div>
                    <div class="drh" id="dcl"></div>
                    <div class="drh" id="dd"></div>
                    <div class="drh" id="cl"></div>
                    <div class="drh" id="ul"></div>
                </div>
            </div>
            <?php endif ?>
            <?php if (isset($_GET['p']) and $_GET['p'] == 'config'): ?>
                <div class="tabs">
                    <ul>
                        <li>config.txt</li>
                        <li>game_options.txt</li>
                        <li>lavaruinblacklist.txt</li>
                        <li>maps.txt</li>
                        <li>motd.txt</li>
                        <li>admins.txt</li>
                        <li>admin_ranks.txt</li>
                    </ul>
                    <div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="config"><?php $c->gconfig('config.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="game_options"><?php $c->gconfig('game_options.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="lavaruinblacklist"><?php $c->gconfig('lavaruinblacklist.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="maps"><?php $c->gconfig('maps.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="motd"><?php $c->gconfig('motd.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="admins"><?php $c->gconfig('admins.txt') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="admin_ranks"><?php $c->gconfig('admin_ranks.txt') ?></textarea><input type="submit" value="Save"></form></div>
                    </div>
                </div>
            <?php endif ?>
            <?php if (isset($_GET['p']) and $_GET['p'] == 'wordconfig'): ?>
                <div class="tabs">
                    <ul>
                        <li>bad_words</li>
                        <li>debix_list</li>
                        <li>exc_end</li>
                        <li>exc_full</li>
                        <li>exc_start</li>
                    </ul>
                    <div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="bad_words"><?php $c->gbconfig('bad_words.fackuobema') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="debix_list"><?php $c->gbconfig('debix_list.fackuobema') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="exc_end"><?php $c->gbconfig('exc_end.fackuobema') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="exc_full"><?php $c->gbconfig('exc_full.fackuobema') ?></textarea><input type="submit" value="Save"></form></div>
                        <div class="deh"><form method="post"><textarea class="input conform" cols=167 rows=50 name="exc_start"><?php $c->gbconfig('exc_start.fackuobema') ?></textarea><input type="submit" value="Save"></form></div>
                    </div>
                </div>
            <?php endif ?>
            <?php if (isset($_GET['p']) and $_GET['p'] == 'bookconfig'): ?>
                <table class="table table-sm table-bordered">
    				<thead>
    					<tr>
    						<th>ID</th>
    						<th>Автор</th>
    						<th>Название</th>
    						<th>Категория</th>
    						<th width=32><i class="fas fa-star"></i></th>
                            <th width=32><i class="fas fa-times"></i></th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php $core->li->libraryQuery('bibliotekary');?>
    				</tbody>
    			</table>
            <?php endif ?>
        </div>
        <?php $core->footer() ?>
    </body>
</html>
