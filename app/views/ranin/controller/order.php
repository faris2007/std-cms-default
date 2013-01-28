<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="4">عرض آخر الطلبات لجميع الدورات</th>
            </tr>
            <tr>
                <th>#</th>
                <th>أسم صاحب الطلب</th>
                <th>أسم الدورة</th>
                <th>مفعل ؟</th>
            </tr>
        </thead>
        <tbody>
            <?php if($ORDERS): ?>
                <?php foreach ($ORDERS as $row): ?>
                    <tr id="order<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><a href="<?=base_url()?>order/user/<?=$row->users_id?>"><?=$this->users->getUsername($row->users_id)?></a></td>
                        <td><a href="<?=base_url()?>order/course/<?=$row->course_id?>"><?=$this->courses->getNameOfCourse($row->course_id)?></a></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isAccept == 1)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>order/action/<?=($row->isAccept == 0)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isAccept == 0)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" title="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" /></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">يمكن أظهار النتائج بناء على
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الطلبات المفعله</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الطلبات الغير مفعله</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>order/show/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما يكون الطلب مفعل
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما يكون الطلب غير مفعل
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif($STEP == 'course'): ?>
<div class="layer1">
    <p class="heading">معلومات عن الدورة</p>
    <div class="contentCol">
        <ul>
            <li>أسم الدورة : <?=$COURSE_NAME?></li>
            <li>مكان الدورة : <?=$COURSE_LOCATION?></li>
            <li>مدة الدورة : <?=$COURSE_LENGTH?></li>
            <li>تاريخ بداية الدورة : <?=$COURSE_START_DATE?></li>
            <li>السعة المسموح بها في هذه الدورة : <?=$COURSE_CAPACITY?></li>
            <li>تاريخ أنتهاء التسجيل : <?=$COURSE_REGISTER_DATE?></li>
            <li>عدد الطلبات : <?=$COURSE_ORDERS?></li>
            <li>سعر الدورة : <?=$COURSE_PRICE?></li>
        </ul>
    </div>
</div>
<br />
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="4">عرض آخر الطلبات</th>
            </tr>
            <tr>
                <th>#</th>
                <th>أسم صاحب الطلب</th>
                <th>أسم الدورة</th>
                <th>مفعل ؟</th>
            </tr>
        </thead>
        <tbody>
            <?php if($ORDERS): ?>
                <?php foreach ($ORDERS as $row): ?>
                    <tr id="order<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><a href="<?=base_url()?>order/user/<?=$row->users_id?>"><?=$this->users->getUsername($row->users_id)?></a></td>
                        <td><a href="<?=base_url()?>order/course/<?=$row->course_id?>"><?=$this->courses->getNameOfCourse($row->course_id)?></a></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isAccept == 1)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>order/action/<?=($row->isAccept == 0)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isAccept == 0)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" title="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" /></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">يمكن أظهار النتائج بناء على
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الطلبات المفعله</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الطلبات الغير مفعله</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>order/course/<?=$COURSE_ID?>/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما يكون الطلب مفعل
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما يكون الطلب غير مفعل
        <br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
    </div>
</div>
<?php elseif($STEP == 'user'): ?>
<div class="layer1">
    <p class="heading">معلومات عن العضو</p>
    <ul class="contentCol">
        <li>أسم العضو : <?=$USER_NAME?></li>
        <li> الإيميل : <?=$USER_EMAIL?></li>
        <li>رقم الجوال : <?=$USER_MOBILE?></li>
    </ul>
</div>
<br />
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="4">عرض آخر الطلبات</th>
            </tr>
            <tr>
                <th>#</th>
                <th>أسم صاحب الطلب</th>
                <th>أسم الدورة</th>
                <th>مفعل ؟</th>
            </tr>
        </thead>
        <tbody>
            <?php if($ORDERS): ?>
                <?php foreach ($ORDERS as $row): ?>
                    <tr id="order<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><a href="<?=base_url()?>order/user/<?=$row->users_id?>"><?=$this->users->getUsername($row->users_id)?></a></td>
                        <td><a href="<?=base_url()?>order/course/<?=$row->course_id?>"><?=$this->courses->getNameOfCourse($row->course_id)?></a></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isAccept == 1)? 'en':'dis'?>able.png"<?if($userInfo->isAdmin):?> onclick="action('<?=base_url()?>order/action/<?=($row->isAccept == 0)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isAccept == 0)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')"<? endif; ?> alt="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" title="<?=($row->isAccept == 0)? 'تفعيل':'تعطيل'?>" /></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">يمكن أظهار النتائج بناء على
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all">الجميع</option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable">الطلبات المفعله</option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable">الطلبات الغير مفعله</option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>order/user/<?=$USER_ID?>/'+$('#available').val())">أذهب</button>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> تظهر عندما يكون الطلب مفعل
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> تظهر عندما يكون الطلب غير مفعل
        <?if($userInfo->isAdmin):?><br />
        طريقة التفعيل/التعطيل بالضغط على الصورة
        <?endif; ?>
    </div>
</div>
<?php endif; ?>

