<?php if($STEP == 'init'):?>
<form method="post" action="<?=base_url()?>install/step/1">
    <div class="message">
        <span>مرحباً بكم<br />
            صفحة تركيب السكربت
        <br />
        تأكد من تعبئة البيانات في ملف database.php
        لبدأ التنصيب اضغط على زر ابدأ<br />
        <input type="submit" value="ابـــــــــــــدأ" name="submit" /></span>
    </div>
</form>
<?php elseif ($STEP == "checkdb") : ?>
<div  class="message">
    <?php if(@$ERROR): ?>
        <form method="post" action="<?=base_url()?>install">
            <span style="color:#FF0000">هناك مشكلة في الاتصال بقاعدة البيانات<br />
            &nbsp;<br />
            تأكد من ادخال البيانات بشكل صحيح في ملف config.php
            <br /><input type="submit" value="أضغط هنا للرجوع" name="B1" /></span>
        </form>
    <?php else: ?>
        <form method="post" action="<?=base_url()?>install/step/2">
            <span align="center">تم الأتصال بقواعد البيانات بنجاح<br />
            <input type="submit" value="الخــــطـــوة الـــتـــالـــيـــــة" name="B1" /></span>
        </form>
    <?php endif; ?>
</div>
<?php elseif($STEP == 'createtable'): ?>
<form method="post" action="<?=base_url()?>install/step/3">
    <div class="message">
        يتم الآن تركيب الجداول
        <br />
        <ul>
            <?php foreach (@$tables as $value): ?>
                <li>تم تركيب الجدول <?=$value?></li>
            <?php endforeach; ?>
        </ul>
        تم الأنتهاء من تركيب الجداول 
        <br />
        <input type="submit" value="الخــــطـــوة الـــتـــالـــيـــــة" name="B1" />
    </div>
</form>
<?php elseif ($STEP == "addinfo"): ?>
    <form method="post" action="<?=base_url()?>install/step/4">
        <table class="tbl">
            <tr>
                <td colspan="2" class="alt1">يتم الآن إدخال البيانات للجداول</td>
            </tr>
            <tr>
                <td colspan="2" class="alt2">الرجاء تعبئة البيانات التالية</td>
            </tr>
            <tr>
                <td class="alt1">اسم الموقع : </td>
                <td class="alt1"><input type="text" name="nameurl" size="20"></td>
            </tr>
            <tr>
                <td class="alt2">رابط الموقع :</td>
                <td class="alt2"><input type="text" name="url" size="20" value="<?=base_url()?>"></td>
            </tr>
            <tr>
                <td class="alt1">اسم المستخدم للإدارة : </td>
                <td class="alt1"><input type="text" name="useradmin" size="20"></td>
            </tr>
            <tr>
                <td class="alt2">كلمة المرور :</td>
                <td class="alt2"><input  type="password" name="pass1" size="20"></td>
            </tr>
            <tr>
                <td class="alt2">الايميل :</td>
                <td class="alt2"><input type="text" name="email" size="20" /></td>
            </tr>
            <tr>
                <td colspan="2" class="alt1"><input type="submit" value="الخــــطـــوة الـــتـــالـــيـــــة" name="B1"></td>
            </tr>
        </table>
    </form>
<?php elseif($STEP == "insertinfo"): ?>
    <form method="post" action="<?=base_url()?>admin">
        <div class="message">
            <ul>
                <li> تم أضافة اعدادات الموقع</li>
                <li>تم أضافة مجموعة الأدارة</li>
                <li>تم أضافة كافة الصلاحيات لمجموعة الأدارة</li>
                <li>تم أضافة بيانات الإدارة</li>
            </ul>
            <span style="text-align:center">تم ادخال البيانات بشكل صحيح 
            <br /> الرجاء حذف ملف install.php
            <br />لكي تتمكن من الدخول للوحة التحكم</span>
            <br /><input type="submit" value="لــــــوحــــة الــــتــــــحـــــكـــــم" name="B1" />
        </div>
    </form>
<?php endif; ?>

