<nav id="navigation">
    <a href="#" class="nav-btn">الرئيسية<span class="arr"></span></a>
    <ul>
        <li class="active first"><a href="<?=base_url()?>" >الرئيسية</a></li>
        <?php if(isset($MAINMENU) && is_array($MAINMENU)): ?>
            <?php foreach ($MAINMENU as $value): ?>
                <li><?=$value?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>