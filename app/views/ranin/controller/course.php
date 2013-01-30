<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض الدورات</th>
                <th><a href="<?=base_url()?>course/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="أضافة دورة جديدة" title="أضافة دورة جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>أسم الدورة</th>
                <th>مفعل ؟</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($COURSES)): ?>
                <?php foreach ($COURSES as $row): ?>
                    <tr id="course<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->course_name."</strike>":$row->course_name?></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>course/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" title="<?=($row->isHidden == 1)? 'تفعيل':'تعطيل'?>" /></td>
                        <td>
                            <a href="<?=base_url()?>course/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>course/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','course<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" title="<?=($row->isDelete == 1)? 'أسترجاع':'حذف'?>" />
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
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الدورات المفعله</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الدورات الغير مفعله</option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete">الدورات المحذوفة</option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete">الدورات الغير محذوفة</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>course/show/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما تكون الدورة مفعله
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما تكون الدورة غير مفعله
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة دورة جديد</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>اسم الدورة</td>
                <td><input type="text" required name="name" /></td>
            </tr>
            <tr>
                <td>السعر</td>
                <td><input type="text" required name="price" id="price" value="0" /> ريال</td>
            </tr>
            <tr>
                <td>تاريخ بداية الدورة</td>
                <td><input type="date" name="start_date" class="styleDate" /></td>
            </tr>
            <tr>
                <td>مدة الدورة</td>
                <td><input type="text" required name="length" /> يوم</td>
            </tr>
            <tr>
                <td>مكان إقامة الدورة</td>
                <td><input type="text" required name="location"  /></td>
            </tr>
            <tr>
                <td>تاريخ إنتهاء التسجيل للدورة</td>
                <td><input type="text" required name="register_end" class="styleDate" /></td>
            </tr>
            <tr>
                <td>السعة</td>
                <td><input type="text" required name="capacity" value="0" /></td>
            </tr>
            <tr>
                <td colspan="2" style="color:red">تنبيه: تاريخ انتهاء التسجيل سوف يهمل اذا وضعت السعة اكبر من 0</td>
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
                <th colspan="2">تعديل بيانات الدورة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>اسم الدورة</td>
                <td><input type="text" required name="name" value="<?=$COURSE_NAME?>" /></td>
            </tr>
            <tr>
                <td>السعر</td>
                <td><input type="text" required name="price" id="price" value="<?=$COURSE_PRICE?>" /> ريال</td>
            </tr>
            <tr>
                <td>تاريخ بداية الدورة</td>
                <td><input type="date" name="start_date" class="styleDate" value="<?=$COURSE_START_DATE?>" /></td>
            </tr>
            <tr>
                <td>مدة الدورة</td>
                <td><input type="text" required name="length"  value="<?=$COURSE_LENGTH?>" /> يوم</td>
            </tr>
            <tr>
                <td>مكان إقامة الدورة</td>
                <td><input type="text" required name="location"  value="<?=$COURSE_LOCATION?>" /></td>
            </tr>
            <tr>
                <td>تاريخ إنتهاء التسجيل للدورة</td>
                <td><input type="text" required name="register_end" class="styleDate"  value="<?=$COURSE_REGISTER_DATE?>" /></td>
            </tr>
            <tr>
                <td>السعة</td>
                <td><input type="text" required name="capacity" value="<?=$COURSE_CAPACITY?>" /></td>
            </tr>
            <tr>
                <td colspan="2" style="color:red">تنبيه: تاريخ انتهاء التسجيل سوف يهمل اذا وضعت السعة اكبر من 0</td>
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
<?php elseif($STEP == 'available'): ?>
<div id="action" class="message" style="display:none"></div>
    <?php if($COURSES): ?>
        <?php if(!$this->users->isLogin()): ?>
            <div class="message">
                تنبيه : يجب عليك
                <a href="<?=base_url()?>register">التسجيل</a>
                في موقعنا لطلب احدى هذه الدورات
                <br />
                أذا كان لديك حساب سابق لدينا يمكنك 
                <a href="<?=base_url()?>login">تسجيل الدخول</a>
            </div>
        <?php endif; ?>
        <div class="layer1">
            <?php foreach ($COURSES as $row): ?>
            <div id="course_<?=$row->id?>">
                <p class="heading"><?=$row->course_name?></p>
                <div class="contentCol">
                    <ul>
                        <li>مكان الدورة : <?=$row->course_location?></li>
                        <li>مدة الدورة : <?=$row->course_length?></li>
                        <li>تاريخ بداية الدورة : <?=date('d-m-Y',$row->course_start)?></li>
                        <li>السعة المسموح بها في هذه الدورة : <?=$COURSE_CAPACITY?></li>
                        <li>تاريخ أنتهاء التسجيل : <?=date('d-m-Y',$row->course_register_end)?></li>
                        <li>سعر الدورة : <?=$COURSE_PRICE?></li>
                    </ul>
                    <?php if($this->users->isLogin()): ?>
                        <button onclick="action('<?=base_url()?>order/action/order/<?=$row->id?>','order','course_<?=$row->id?>','<?=$row->id?>')">التسجيل</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="message">
                عفواً لاتوجد دورات متاحه حالياً
        </div>
    <?php endif; ?>
<?php endif; ?>

