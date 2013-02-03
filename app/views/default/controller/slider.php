<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<form method="post">
    <div class="demo_jui">
        <table id="list" class="tbl" style="width:100%;direction:rtl">
            <thead>
                <tr>
                    <th colspan="4">عرض عناصر واجهة العرض</th>
                    <th><a href="<?=base_url()?>slider/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة عنصر جديد" title="أضافة عنصر جديد" /></a></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>الإسم</th>
                    <th>الترتيب</th>
                    <th>مفعل ؟</th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($SLIDERS)): ?>
                    <?php foreach ($SLIDERS as $row): ?>
                        <tr id="slider<?=$row->id?>">
                            <td><?=$row->id?></td>
                            <td><?=($row->isDelete == 1)? "<strike>".$row->slider_name."</strike>":$row->slider_name?></td>
                            <td><input type="text" style="width:30px;" name="slider_<?=$row->id?>" value="<?=$row->sort_id?>" /></td>
                            <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>slider/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" /></td>
                            <td>
                                <a href="<?=base_url()?>slider/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                                <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>slider/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','slider<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><input type="submit" value="حفظ" /></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="5">يمكن أظهار النتائج بناء على
                        <select onchange="window.location.assign('<?=base_url()?>slider/show/'+$('#available').val())" id="available">
                            <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                            <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">العناصر المفعله</option>
                            <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">العناصر الغير مفعله</option>
                            <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">العناصر المحذوفة</option>
                            <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">العناصر الغير محذوفة</option>
                        </select>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="message">
            <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما يكون العنصر مفعل
            | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما يكون العنصر غير مفعل
            <br />
            طريقة التفعيل/التعطيل بالضغط على الصورة
        </div>
    </div>
</form>
<?php elseif ($STEP == 'add') : ?>
<form method="post" enctype="multipart/form-data">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة عنصر جديد</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>اسم العنصر</td>
                <td><input type="text" required name="name" /></td>
            </tr>
            <tr>
                <td>الرابط</td>
                <td><input type="text" required name="url" id="url" /></td>
            </tr>
            <tr>
                <td>الصورة</td>
                <td><input type="file" name="userfile" /></td>
            </tr>
            <tr>
                <td>الوصف</td>
                <td><textarea name="desc" required>

                    </textarea></td>
            </tr>
            <tr>
                <td>الترتيب</td>
                <td><input type="text" required name="sort" value="1" /></td>
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
                <th colspan="2">تعديل هذه العنصر</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>اسم العنصر</td>
                <td><input type="text" value="<?=$SLIDER_NAME?>" required name="name" /></td>
            </tr>
            <tr>
                <td>الرابط</td>
                <td><input type="text" value="<?=$SLIDER_URL?>" required name="url" id="url" /></td>
            </tr>
            <tr>
                <td>الصورة السابقة</td>
                <td><a target="_blank" href="<?=$SLIDER_PICTURE?>">أضغط هنا</a></td>
            </tr>
            <tr>
                <td>تحديث الصورة</td>
                <td><input type="radio" name="update" onclick="$('#userfile').attr('disabled', false);" value="1" />نعم <input type="radio" name="update" value="0"  onclick="$('#userfile').attr('disabled', true);" checked="checked" />لا</td>
            </tr>
            <tr>
                <td>الصورة</td>
                <td><input type="file" name="userfile" id="userfile" disabled="disabled" /></td>
            </tr>
            <tr>
                <td>الوصف</td>
                <td>
                    <textarea name="desc"  required>
                        <?=$SLIDER_DESC?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td>الترتيب</td>
                <td><input type="text" required name="sort" value="<?=$SLIDER_SORT?>" /></td>
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

