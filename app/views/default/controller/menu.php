<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض القائمة <?=$TYPEMENU?></th>
                <th><a href="<?=base_url()?>menu/add<?=(is_null($PARENTMENU))? '':'/'.$PARENTMENU?>"><img src="<?=base_url()?>style/default/icon/add.png" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>مفعل ؟</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($MENUS)): ?>
                <?php foreach ($MENUS as $row): ?>
                    <tr id="menu<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->title."</strike>":$row->title?></td>
                        <td id='enable<?=$row->id?>'><img src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>menu/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>')" /></td>
                        <td>
                            <a href="<?=base_url()?>menu/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" /></a>
                            <a href="<?=base_url()?>menu/show/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/show.png" /></a>
                            <img src="<?=base_url()?>style/default/icon/del.png" onclick="action('<?=base_url()?>menu/action/delete/<?=$row->id?>','delete','menu<?=$row->id?>')">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة قائمة جديده</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" name="title" /></td>
            </tr>
            <tr>
                <td>الرابط</td>
                <td><input type="text" name="url" /></td>
            </tr>
            <tr>
                <td>الترتيب</td>
                <td><input type="text" name="sort" /></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="أضافة" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif ($STEP == 'edit') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">تعديل هذه القائمة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" name="title" value="<?=$MENUTITLE?>" /></td>
            </tr>
            <tr>
                <td>الرابط</td>
                <td><input type="text" name="url" value="<?=$MENUURL?>" /></td>
            </tr>
            <tr>
                <td>الترتيب</td>
                <td><input type="text" name="sort" value="<?=$MENUSORT?>" /></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="تعديل" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php endif; ?>

