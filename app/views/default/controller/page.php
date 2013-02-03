<?php if($STEP == 'view'): ?>
<div>
    <?=$CONTENTPAGE?>
</div>
<?php elseif($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض الصفحات</th>
                <th><a href="<?=base_url()?>page/add<?=(is_null($PARENTPAGE))? '':'/'.$PARENTPAGE?>"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة صفحة جديدة" title="أضافة صفحة جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>مفعل ؟</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($PAGES)): ?>
                <?php foreach ($PAGES as $row): ?>
                    <tr id="page<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->title."</strike>":$row->title?></td>
                        <td><img id="enable<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>page/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" /></td>
                        <td>
                            <a href="<?=base_url()?>page/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <a href="<?=base_url()?>page/show/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/show.png" alt="مشاهدة محتوى الصفحة" title="مشاهدة محتوى الصفحة" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>page/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','page<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
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
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الصفحات المفعلة</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الصفحات الغير مفعله</option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">الصفحات المحذوفه</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">الصفحات الغير محذوفة</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>page/show<?=(is_null($PARENTPAGE))? '/all':'/'.$PARENTPAGE?>/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما تكون الصفحة مفعله
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما تكون الصفحة غير مفعله
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif($STEP == 'add'): ?>
<form method="post">
    <table class="tbl" style="width: 90%;direction: rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة صفحة جديدة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" name="title" required="required" /></td>
            </tr>
            <tr>
                <td colspan="2">محتوى الصفحة</td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea id="content" name="content" style="width: 100%"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">خيارات خاصة بمحركات البحث</td>
            </tr>
            <tr>
                <td>الكلمات المفتاحية</td>
                <td><input type="text" name="keyword" /></td>
            </tr>
            <tr>
                <td>وصف للصفحة</td>
                <td><input type="text" name="desc" /></td>
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
<?php elseif($STEP == 'edit'): ?>
<form method="post">
    <table class="tbl" style="width: 90%;direction: rtl">
        <thead>
            <tr>
                <th colspan="2">تعديل الصفحة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" name="title" required="required" value="<?=$PAGETITLE?>" /></td>
            </tr>
            <tr>
                <td colspan="2">محتوى الصفحة</td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea id="content" name="content" style="width: 100%"><?=$PAGECONTENT?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">خيارات خاصة بمحركات البحث</td>
            </tr>
            <tr>
                <td>الكلمات المفتاحية</td>
                <td><input type="text" name="keyword" value="<?=$PAGEKEY?>" /></td>
            </tr>
            <tr>
                <td>وصف للصفحة</td>
                <td><input type="text" name="desc" value="<?=$PAGEDESC?>" /></td>
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
<?php elseif($STEP == 'error'): ?>
<div class="message">
    عذراً الصفحة التي طلبته غير موجوده يبدو انك استخدمت رابط خطأ
    <br />
    <a href="<?=base_url()?>">أضغط هنا للتوجه للصفحة الرئيسية</a>
</div>
<?php endif; ?>

