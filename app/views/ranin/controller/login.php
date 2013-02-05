<?php if($STEP == 'login'):?>
    <form method="post">
        <table class="tbl" style="width: 50%;direction:rtl">
            <thead>
                <tr>
                    <th colspan="2">تسجيل الدخول</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أسم المستخدم :</td>
                    <td><input type="text" name="username" required="required" placeholder="أسم المستخدم ..." /></td>
                </tr>
                <tr>
                    <td>كلمة المرور :</td>
                    <td><input type="password" name="password" required="required" placeholder="كلمة المرور ..." /></td>
                </tr>
                <?php if(@$ERROR): ?>
                    <tr>
                        <td colspan="2">خطأ في البيانات المدخلة</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="تسجيل الدخول" /></td>
                </tr>
                <tr>
                    <td colspan="2">هل فقدت كلمة المرور؟ يمكنك <a href="<?=base_url()?>login/resetpassword">استعادة الباسورد</a></td>
                </tr>
                <?php if(@$REGISTER): ?>
                    <tr>
                        <td colspan="2">ليس لديك حساب سابق؟ يمكنك <a href="<?=base_url()?>register">التسجيل</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
<?php elseif($STEP == 'logout'): ?>
<div class="message">
   تم تسجيل الخروج بنجاح
</div>
<?php elseif($STEP == 'permission'): ?>
<div class="message">
    ليس لديك صلاحية بدخول هذه المنطقة
<br />
        لهذا الرجاء ابلاغ إدارة الموقع بخصوص هذه المشكلة ان كانت لديك صلاحية
    
</div>
<?php elseif($STEP ==  'resetpass'): ?>
<form method="post">
    <table class="tbl" style="width: 65%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أستعادة الباسورد</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>البريد الإلكتروني المسجل :</td>
                <td><input type="text" name="email" placeholder="E-Mail" /></td>
            </tr>
            <tr>
                <td>الكود الأمني</td>
                <td><?=$CAPTCHA?></td>
            </tr>
            <tr>
                <td>أدخل السؤال الأمني</td>
                <td><input type="text" name="captcha" /></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2" class="error"><?=@$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="طلب أستعادة الباسورد" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php endif; ?>

