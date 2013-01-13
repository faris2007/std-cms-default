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
        var js_files = ["jquery","jquery.dataTables","jquery.flexslider-min","functions","jquery.popupWindow"];
        for (js_x in js_files){document.write('<script type="text/javascript" src="' + style_dir + 'js/' + js_files[js_x] + '.js"></' + 'script>');}
	/*document.write('<link type="text/css" rel="stylesheet" href="' + style_dir + 'css/style_1.css">');*/
        document.write('<link type="text/css" rel="stylesheet" href="' + style_dir + 'css/style.css">');
    </script>
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
                                                    مرحبا بك يا <?=$userInfo->username?>
                                                    <?php if($userInfo->isAdmin): ?>
                                                        <a href="<?=base_url()?>admin">لوحة التحكم</a> |
                                                    <?php endif; ?>
                                                        <a href="<?=base_url()?>login/logout">تسجيل الخروج</a>
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
                                <!-- slider -->
				<div class="m-slider">
					<div class="slider-holder">
						<span class="slider-shadow"></span>
						<span class="slider-b"></span>
						<div class="slider flexslider">
							<ul class="slides">
								<li>
									<div class="img-holder">
										<img src="<?=$STYLE_FOLDER?>css/images/slide-img2.png" alt="" />
									</div>
									<div class="slide-cnt">
										<h2>ريادة الأعمال التجارية</h2>
										<div class="box-cnt">
											<p>يقدم مركز رنين الليل خدمة ريادة الأعمال التجارية</p>
										</div>
										<a href="#" class="grey-btn">التفاصيل</a>
									</div>
								</li>
								<li>
									<div class="img-holder">
										<img src="<?=$STYLE_FOLDER?>css/images/slide-img2.png" alt="" />
									</div>
									<div class="slide-cnt">
										<h2>ريادة الأعمال التجارية</h2>
										<div class="box-cnt">
											<p>يقدم مركز رنين الليل خدمة ريادة الأعمال التجارية</p>
										</div>
										<a href="#" class="grey-btn">التفاصيل</a>
									</div>
								</li>
								<li>
									<div class="img-holder">
										<img src="<?=$STYLE_FOLDER?>css/images/slide-img2.png" alt="" />
									</div>	
									<div class="slide-cnt">
										<h2>ريادة الأعمال التجارية</h2>
										<div class="box-cnt">
											<p>يقدم مركز رنين الليل خدمة ريادة الأعمال التجارية</p>
										</div>
										<a href="#" class="grey-btn">التفاصيل</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>		
				<!-- end of slider -->
                                <!-- main -->
				<div class="main">
                                    <?php if (@$NAV): ?>
                                    <div id="nav">
                                        <ul>
                                            <li>&rsaquo;</li>
                                            <li><a href="<?=base_url()?>">Home</a></li>
                                            <?php foreach($NAV as $key => $value): ?>
                                            <li>&rsaquo;</li>
                                            <li><a href="<?=$key?>"><?=$value?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                    <?=$CONTENT?>
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
								<li><a href="#">خدمات إقتصادية</a></li>
								<li><a href="#">خدمات تجارية</a></li>
								<li><a href="#">خدمات إدارية</a></li>
								<li><a href="#">خدمات مالية</a></li>
							</ul>
						</div>
						<div class="col">
							<h2>المشاريع</h2>
							<ul>
								<li><a href="#">المشروع الأول</a></li>
								<li><a href="#">المشروع الثاني</a></li>
								<li><a href="#">المشروع الثالث </a></li>
								<li><a href="#">المشروع الرابع</a></li>
							</ul>
						</div>
						<div class="col">
							<h2>الحلول واﻻستشارات</h2>
							<ul>
								<li><a href="#">استشارات مالية</a></li>
								<li><a href="#">استشارات فانونية</a></li>
								<li><a href="#">استشارات تجارية</a></li>
								<li><a href="#">استشارات تجارية</a></li>
							</ul>
						</div>
						<div class="col">
							<h2>شركاؤنا</h2>
							<ul>
								<li><a href="#">الشركة الأولى</a></li>
								<li><a href="#">الشركة الثانية</a></li>
								<li><a href="#">الشركة الثالثة</a></li>
								<li><a href="#">الشركة الرابعة</a></li>
							</ul>
						</div>
						<div class="cl">&nbsp;</div>
					</div>
					<!-- end of footer-cols -->
					<div class="footer-bottom">
						<nav class="footer-nav">
							<ul>
								<li class="active"><a href="#">الصفحة الرئيسية</a></li>
								<li><a href="#">الخدمات</a></li>
								<li><a href="#">المشاريع</a></li>
								<li><a href="#">الحلول واﻻستشارات</a></li>
								<li><a href="#">الوظائف</a></li>
								<li><a href="#">المدونة</a></li>
								<li><a href="#">اتصل بنا</a></li>
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