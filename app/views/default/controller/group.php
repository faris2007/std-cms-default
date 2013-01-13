<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">عرض المجموعات</th>
                <th><a href="<?=base_url()?>group/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة مجموعة جديدة" title="أضافة مجموعة جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($GROUPS)): ?>
                <?php foreach ($GROUPS as $row): ?>
                    <tr id="group<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->name."</strike>":$row->name?></td>
                        <td>
                            <a href="<?=base_url()?>group/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <a href="<?=base_url()?>group/permission/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/add_permission.png" alt="أضافة صلاحيات" title="أضافة صلاحيات" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>group/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','group<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
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
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">المجموعات المحذوفة</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">المجموعات الغير محذوفه</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>group/show/'+$('#available').val())">أذهب</button>
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
                <th colspan="2">أضافة مجموعة جديده</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>أسم المجموعة : </td>
                <td><input type="text" required="required" name="name" /></td>
            </tr>
            <tr>
                <td>هل تريد هذه المجموعة كمجموعة إدارية؟</td>
                <td><input type="radio" name="admin" checked="checked" value="1" />نعم <input type="radio" name="admin" value="0" />لا</td>
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
                <th colspan="2">تعديل المجموعة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>أسم المجموعة : </td>
                <td><input type="text" name="name" value="<?=$GROUPNAME?>" /></td>
            </tr>
            <tr>
                <td>هل تريد هذه المجموعة كمجموعة إدارية؟</td>
                <td><input type="radio" name="admin"<?=($GROUPADMIN)? ' checked="checked"' :''?> value="1" />نعم <input type="radio" name="admin"<?=(!$GROUPADMIN)? ' checked="checked"' :''?> value="0" />لا</td>
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
<?php elseif ($STEP == 'permission'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table class="tbl" id="list" dataajax="permission/<?=$GROUPID?>" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">الصلاحيات الخاصة بمجموعة <?=$GROUPNAME?></th>
                <th><img id="addNewPermission" val="open" src="<?=base_url()?>style/default/icon/add.png" alt="أضافة صلاحية جديده" /></th>
            </tr>
            <tr>
                <th>أسم الخدمة</th>
                <th>نوع الصلاحية</th>
                <th>القيمة (ان وجد)</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr id="addnewHidden" style="display:none">
                <td><input type="hidden" id="groupId" value="<?=$GROUPID?>" />
                    <select id="service_name" name="service_name">
                        <option value="" disabled="disabled">اختر نوع الخدمة</option>
                        <?php if(isset($SERVICES)):?>
                            <?php foreach ($SERVICES as $key => $value): ?>
                                <option value="<?=$key?>"><?=$value?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td>
                    <select id="functions" name="functions">
                        <option disabled="disabled">جاري التحميل ...</option>
                    </select>
                </td>
                <td>
                    <select id="value" name="value">
                        <option value="all">الجميع</option>
                    </select>
                </td>
                <td>
                    <button id="addButton">أضافة</button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php endif; ?>

