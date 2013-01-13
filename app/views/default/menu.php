<div id="menu">
<ul class="navigation">
    <li><a href="<?=base_url()?>" style="padding:5px 7px 8px 7px;"><img style="vertical-align: middle;" src="<?=base_url()?>style/default/icon/home.png"></a></li>
    <?php if(isset($MAINMENU) && is_array($MAINMENU)): ?>
        <?php foreach ($MAINMENU as $value): ?>
            <li><?=$value?></li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<div class="clear"></div>
</div>