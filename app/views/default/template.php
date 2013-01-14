<!DOCTYPE html>
<html>
<head>
    <title><?=$HEAD['TITLE']?></title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <script type="text/javascript">
        <?=(@$DISABLE)?'' :"var Token = '".$this->core->token(TRUE)."';\n"?>
        var base_url = '<?=base_url()?>';
        var style_dir = base_url + 'style/default';
        var js_files = ["tiny_mce/tiny_mce","jquery","jquery.dataTables","jquery-ui","functions","jquery.popupWindow"];
        for (js_x in js_files){document.write('<script type="text/javascript" src="' + style_dir + '/js/' + js_files[js_x] + '.js"></' + 'script>');}
	document.write('<link type="text/css" rel="stylesheet" href="' + style_dir + '/style.css">');
    </script>
    <script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
    
    <!--[if IE 6]>
    <style>
        body {behavior: url("csshover3.htc");}
        #menu li .drop {background:url("img/drop.gif") no-repeat right 8px; 
    </style>
    <![endif]-->
    <meta charset="utf-8" />
	<?=meta($HEAD['META']['META'])?>
    <?=$HEAD['OTHER']?>
</head>

<body>
    <div id="top_bar">
    	<a href="#" id="login_link">Login</a>
        <span style="float:right;"><?=date("F j, Y, g:i a")?></span>
    </div>
	<div id="container">
    	<div id="header"></div>
        <div id="main_menu">
            <?=$MENU?>
        </div>
        <?php if (@$NAV): ?>
        <div id="nav">
            <ul>
                <?php foreach($NAV as $key => $value): ?>
                <li>&rsaquo;</li>
                <li><a href="<?=$key?>"><?=$value?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div id="main_content">
			<?=$CONTENT?>
            <br />
            <br />
        </div>
        <div id="footer">
            <span><?=@$DEVELOPMENT?></span>
        </div>
    </div>
</body>

</html>