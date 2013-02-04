<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض الأقسام</th>
                <th><a href="<?=base_url()?>cat/add"><img src="<?=$STYLE_FOLDER?>icon/add.png" alt="أضافة قسم جديدة" title="أضافة قسم جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>عنوان القسم</th>
                <th>مفعل ؟</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($CATS)): ?>
                <?php foreach ($CATS as $row): ?>
                    <tr id="cat<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->name."</strike>":$row->name?></td>
                        <td><img id='enable<?=$row->id?>' src="<?=$STYLE_FOLDER?>icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>cat/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" /></td>
                        <td>
                            <a href="<?=base_url()?>cat/edit/<?=$row->id?>"><img src="<?=$STYLE_FOLDER?>icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=$STYLE_FOLDER?>icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>cat/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','cat<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">يمكن أظهار النتائج بناء على
                    <select onchange="window.location.assign('<?=base_url()?>cat/show/'+$('#available').val())" id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الأقسام المفعلة</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الأقسام الغير مفعله</option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">الأقسام المحذوفه</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">الأقسام الغير محذوفة</option>
                    </select>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=$STYLE_FOLDER?>icon/enable.png" /> تظهر عندما يكون القسم مفعل
        | <img src="<?=$STYLE_FOLDER?>icon/disable.png" /> تظهر عندما يكون القسم غير مفعل
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة قسم جديد</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" required="required" name="name" /></td>
            </tr>
            <tr>
                <td>وصف مختصر للقسم</td>
                <td><input type="text" required="required" name="desc" /></td>
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
                <th colspan="2">تعديل القسم</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان</td>
                <td><input type="text" required="required" name="name" value="<?=$CATNAME?>" /></td>
            </tr>
            <tr>
                <td>وصف مختصر للقسم</td>
                <td><input type="text" required="required" name="desc" value="<?=$CATDESC?>" /></td>
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

