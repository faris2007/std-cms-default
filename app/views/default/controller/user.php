<?php if($STEP == 'show'):?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="4">عرض الأعضاء</th>
                <th><a href="<?=base_url()?>user/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة عضو جديد" title="أضافة عضو جديد" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>أسم المستخدم</th>
                <th>الأسم الكامل</th>
                <th>مفعل ؟</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($USERS)): ?>
                <?php foreach ($USERS as $row): ?>
                    <tr id="user<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->username."</strike>":$row->username?></td>
                        <td><?=$row->full_name?></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isActive == 1)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>user/action/<?=($row->isActive == 0)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isActive == 0)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isActive == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isActive == 1)? 'تفعيل':'تعطيل'?>" /></td>
                        <td>
                            <a href="<?=base_url()?>user/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>user/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','group<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">يمكن أظهار النتائج بناء على
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الإعضاء المفعلين</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الإعضاء الغير المفعلين</option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">الإعضاء المحذوفين</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">الإعضاء الغير محذوفين</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>user/show/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما يكون المستخدم مفعل
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما يكون المستخدم غير مفعل
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif($STEP == 'add'): ?>
<form method="post" id="register">
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="2">أضافة عضو جديد</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أسم المستخدم :</td>
                    <td><input type="text" name="username" required id="username" /></td>
                </tr>
                <tr>
                    <td>الأسم الكامل :</td>
                    <td><input type="text" name="fullName" required id="fullName" /></td>
                </tr>
                <tr>
                    <td>الأيميل :</td>
                    <td><input type="text" name="email" required id="email" /></td>
                </tr>
                <tr>
                    <td>الجوال :</td>
                    <td><input type="text" name="mobile" id="mobile" /></td>
                </tr>
                <tr>
                    <td>كلمة المرور :</td>
                    <td><input type="password" name="password" required id="password" /></td>
                </tr>
                <tr>
                    <td>تأكيد كلمة المرور :</td>
                    <td><input type="password" name="repassword" required id="repassword" /></td>
                </tr>
                <tr>
                    <td>المجموعة</td>
                    <td>
                        <select name="group_id">
                            <?php if(isset($GROUPS)): ?>
                                <?php foreach ($GROUPS as $row): ?>
                                    <option value="<?=$row->id?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="2" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="أضافة" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<?php elseif($STEP == 'edit'): ?>
<form method="post" id="register">
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="3">تعديل بيانات العضو <?=$fullname?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أسم المستخدم :</td>
                    <td><input type="text" name="username" required id="username" value="<?=$username?>" /></td>
                    <td id="checkusername"></td>
                </tr>
                <tr>
                    <td>الأسم الكامل :</td>
                    <td colspan="2"><input type="text" name="fullName" required id="fullName" value="<?=$fullname?>" /></td>
                </tr>
                <tr>
                    <td>الأيميل :</td>
                    <td><input type="text" name="email" required id="email" value="<?=$email?>" /></td>
                    <td id="checkemail"></td>
                </tr>
                <tr>
                    <td>الجوال :</td>
                    <td colspan="2"><input type="text" name="mobile" id="mobile" value="<?=$mobile?>" /></td>
                </tr>
                <tr>
                    <td>كلمة المرور : (أذا تركت فارغ لن يتم تعديل كلمة المرور)</td>
                    <td><input type="password" name="password" id="password" /></td>
                    <td id="result"></td>
                </tr>
                <tr>
                    <td>تأكيد كلمة المرور :</td>
                    <td><input type="password" name="repassword" id="repassword" /></td>
                    <td id="resultre"></td>
                </tr>
                <?php if($ADMIN): ?>
                <tr>
                    <td>المجموعة</td>
                    <td>
                        <select name="group_id">
                            <?php if(isset($GROUPS)): ?>
                                <?php foreach ($GROUPS as $row): ?>
                                    <option<?=($group_id == $row->id)? ' selected="selected"':''?> value="<?=$row->id?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="3" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3"><input type="submit" value="تعديل" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<?php endif; ?>

