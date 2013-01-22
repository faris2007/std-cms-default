<!-- slider -->
    <div class="m-slider">
            <div class="slider-holder">
                    <span class="slider-shadow"></span>
                    <span class="slider-b"></span>
                    <div class="slider flexslider">
                            <ul class="slides">
                                <?php if(isset($SLIDERS)): ?>
                                    <?php foreach ($SLIDERS as $row): ?>
                                        <li>
                                                <div class="img-holder">
                                                        <img src="<?=$row->picture?>" alt="<?=$row->slider_name?>" />
                                                </div>
                                                <div class="slide-cnt">
                                                        <h2><?=$row->slider_name?></h2>
                                                        <div class="box-cnt">
                                                                <?=$row->desc?>
                                                        </div>
                                                        <a href="<?=$row->url?>" class="grey-btn">التفاصيل</a>
                                                </div>
                                        </li>   
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                    </div>
            </div>
    </div>		
    <!-- end of slider -->
