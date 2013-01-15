<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض القائمة <?=$TYPEMENU?></th>
                <th><a href="<?=base_url()?>menu/add<?=(is_null($PARENTMENU))? '':'/'.$PARENTMENU?>"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة قائمة جديدة" title="أضافة قائمة جديدة" /></a></th>
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
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>menu/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" /></td>
                        <td>
                            <a href="<?=base_url()?>menu/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <a href="<?=base_url()?>menu/show/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/show.png" alt="مشاهدة محتوى القائمة" title="مشاهدة محتوى القائمة" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>menu/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','menu<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">يمكن أظهار النتائج بناء على
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">القوائم المفعلة</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">القوائم الغير مفعله</option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">القوائم المحذوفه</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">القوائم الغير محذوفة</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>menu/show<?=(is_null($PARENTMENU))? '/all':'/'.$PARENTMENU?>/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
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
                <td>نوع الرابط</td>
                <td>
                    <select name="type_url" id="type_url">
                        <option value="external">رابط خارجي</option>
                        <option value="page">صفحة داخلية</option>
                    </select>
                </td>
            </tr>
            <tr id="select_page" style="display:none">
                <td>أختر الصفحة</td>
                <td>
                    <select name="page_num">
                        <option selected="selected" value="" disabled="disabled">أختر الصفحة المطلوبة ...</option>
                        <?php if(isset($PAGES)): ?>
                            <?php foreach($PAGES as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->title?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>الرابط</td>
                <td><input type="text" name="url" id="url" /></td>
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
                <td>نوع الرابط</td>
                <td>
                    <select name="type_url" id="type_url">
                        <option value="external">رابط خارجي</option>
                        <option value="page">صفحة داخلية</option>
                    </select>
                </td>
            </tr>
            <tr id="select_page" style="display:none">
                <td>أختر الصفحة</td>
                <td>
                    <select name="page_num">
                        <option selected="selected" value="" disabled="disabled">أختر الصفحة المطلوبة ...</option>
                        <?php if(isset($PAGES)): ?>
                            <?php foreach($PAGES as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->title?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr id="select_url">
                <td>الرابط</td>
                <td><input type="text" name="url" id="url" value="<?=$MENUURL?>" /></td>
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

