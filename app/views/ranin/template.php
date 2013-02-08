<!DOCTYPE html>
<html lang="en" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?=$HEAD['TITLE']?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=$STYLE_FOLDER?>css/images/favicon.ico" />
    <link rel="stylesheet" href="<?=$STYLE_FOLDER?>css/flexslider.css" type="text/css" media="all" />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
        <?=(@$DISABLE)?'' :"var Token = '".$this->core->token(TRUE)."';\n"?>
        var base_url = '<?=base_url()?>';
        var style_dir = '<?=$STYLE_FOLDER?>';
        /*var js_files = ["jquery","jquery.dataTables","jquery.flexslider-min","jquery-ui","tiny_mce/tiny_mce","functions"];
        for (js_x in js_files){document.write('<script type="text/javascript" src="' + style_dir + 'js/' + js_files[js_x] + '.js"></' + 'script>');}*/
    </script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/jquery.js"></script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/functions.js"></script>
    <link type="text/css" rel="stylesheet" href="<?=$STYLE_FOLDER?>css/style.css">
    <script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
    <!--[if IE 6]>
    <style>
        body {behavior: url("csshover3.htc");}
        #menu li .drop {background:url("img/drop.gif") no-repeat right 8px; 
    </style>
    <![endif]-->
    <!--[if lt IE 9]>
            <script src="<?=$STYLE_FOLDER?>js/modernizr.custom.js"></script>
    <![endif]-->
    <meta charset="utf-8" />
	<?=meta($HEAD['META']['META'])?>
    <?=$HEAD['OTHER']?>
</head>

<body>
    <div class="header1-right">
					<div class="subscribe">
                                                <?php if(!$userInfo):?>
						<div class="login">
                                                        <form method="post" action="<?=base_url()?>login">
                                                            <span class="passfield"><input name="password" type="password" class="field" placeholder="كلمة المرور ... " /></span>
                                                            <span class="userfield"><input name="username" type="text" class="field" placeholder="أسم المستخدم ... " /></span>
								<input type="submit" class="submit-btn" value="تسجيل الدخول" />
							</form>
						</div>
                                                <?php else: ?>
                                                <div style="text-align:center">
                                                    <?php if($userInfo->isAdmin): ?>
                                                        <a href="<?=base_url()?>admin">لوحة التحكم</a> |
                                                    <?php else: ?>
                                                        <a href="<?=base_url()?>communication">التواصل مع الإدارة</a> |
                                                    <?php endif; ?>
                                                        <a href="<?=base_url()?>myprofile">بياناتي</a> |
                                                        <a href="<?=base_url()?>myorder">طلباتي</a> |
                                                        <a href="<?=base_url()?>logout">تسجيل الخروج</a> |
                                                    مرحبا يا <?=$userInfo->full_name?>
                                                    
                                                </div>
                                                <?php endif; ?>
					</div>
					</div>					
	<!-- wraper -->
	<div id="wrapper">
		<!-- shell -->
		<div class="shell">
			<!-- container -->
			<div class="container">
				<!-- header -->
				<header id="header">
					<h1 id="logo"><a href="#">مركز رنين الليل لريادة الأعمال</a></h1>
					<!-- search -->
					<div class="search">
						<form method="post">
							<span class="field"><input type="text" class="field" value="البحث ..." title="البحث ..." /></span>
							<input type="submit" class="search-btn" value="" />
						</form>
					</div>
					<!-- end of search -->
				</header>
				<!-- end of header -->
                                <!-- navigation -->
                                <div id="main_menu">
                                    <?=$MENU?>
                                </div>
                                <!-- end of navigation -->
                                <?=$SLIDERS_SHOW?>
                                <!-- main -->
				<div class="main">
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
                                    <?=$CONTENT?>
                                    <div style="text-align:center">الوقت المستغرق : {elapsed_time}</div>
                                </div>
				<!-- end of main -->
                                <br />
                                <br />

                                <div class="socials">
					<div class="socials-inner">
						<h3> تابعنا</h3>
						<ul>
							<li><a href="#" class="facebook-ico"><span></span>Facebook</a></li>
							<li><a href="#" class="twitter-ico"><span></span>Twitter</a></li>
							<li><a href="#" class="rss-feed-ico"><span></span>Rss-feed</a></li>
							<li><a href="#" class="myspace-ico"><span></span>myspace</a></li>
						</ul>
						<div class="cl">&nbsp;</div>
					</div>
				</div>
				<div id="footer">
					<div class="footer-cols">
						<div class="col">
							<h2>خدماتنا</h2>
							<ul>
							    <?php if($EXTMENU4): ?>
                                                                <?php foreach ($EXTMENU4 as $row): ?>
                                                                    <li><a href="<?=$row->url?>"><?=$row->title?></a></li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
							</ul>
						</div>
						<div class="col">
							<h2>المشاريع</h2>
							<ul>
							    <?php if($EXTMENU3): ?>
                                                                <?php foreach ($EXTMENU3 as $row): ?>
                                                                    <li><a href="<?=$row->url?>"><?=$row->title?></a></li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
							</ul>
						</div>
						<div class="col">
							<h2>الحلول واﻻستشارات</h2>
							<ul>
							    <?php if($EXTMENU2): ?>
                                                                <?php foreach ($EXTMENU2 as $row): ?>
                                                                    <li><a href="<?=$row->url?>"><?=$row->title?></a></li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
							</ul>
						</div>
						<div class="col">
							<h2>شركاؤنا</h2>
                                                        <ul>
                                                            <?php if($EXTMENU1): ?>
                                                                <?php foreach ($EXTMENU1 as $row): ?>
                                                                    <li><a href="<?=$row->url?>"><?=$row->title?></a></li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </ul>
						</div>
						<div class="cl">&nbsp;</div>
					</div>
					<!-- end of footer-cols -->
					<div class="footer-bottom">
						<nav class="footer-nav">
							<ul>
                                                            <?php if($EXTMENU5): ?>
                                                                <?php foreach ($EXTMENU5 as $row): ?>
                                                                    <li><a href="<?=$row->url?>"><?=$row->title?></a></li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
							</ul>
						</nav>
						<p class="copy"> جميع الحقوق محفوظة 2012 مركز رنين الليل لريادة الأعمال &copy; <span>|</span> <strong><?=@$DEVELOPMENT?></strong></p>
						<div class="cl">&nbsp;</div>
					</div>
				</div>
			</div>
			<!-- end of container -->	
		</div>
		<!-- end of shell -->	
	</div>
	<!-- end of wrapper -->
</body>
</html>