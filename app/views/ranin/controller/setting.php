<div>
    <form method="post">
        <table class="tbl" style="width:90%;direction:rtl">
            <thead>
                <tr>
                    <th colspan="2">الإعدادات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">الإعدادات العامة</td>
                </tr>
                <tr>
                    <td>أسم الموقع</td>
                    <td><input type="text" name="sitename" value="<?=$SITENAME?>" /></td>
                </tr>
                <tr>
                    <td>رابط الموقع</td>
                    <td><input type="text" name="siteurl" value="<?=$SITEURL?>" /></td>
                </tr>
                <tr>
                    <td>أيميل إدارة الموقع</td>
                    <td><input type="text" name="siteemail" value="<?=$SITEEMAIL?>" /></td>
                </tr>
                <tr>
                    <td>التصميم المختار للموقع</td>
                    <td>
                        <select name="style">
                            <?php if($STYLE): ?>
                                <?php foreach ($STYLE as $value): ?>
                                    <option<?=($STYLEVALUE == $value)? ' selected="selected"':''?> value="<?=$value?>"><?=$value?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>الصفحة الرئيسية للموقع</td>
                    <td>
                        <select name="homepage">
                            <option<?=($HOMEPAGE == 'home')? ' selected="selected"':''?> value="home">الافتراضي</option>
                            <?php if($PAGES): ?>
                                <?php foreach ($PAGES as $value): ?>
                                    <option<?=($HOMEPAGE == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->title?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>تفعيل الموقع</td>
                    <td><input type="radio" name="siteenable"<?=($SITEENABLE == 1)?' checked="checked"':''?> value="1" />نعم <input type="radio" name="siteenable"<?=($SITEENABLE == 0)?' checked="checked"':''?> value="0" />لا</td>
                </tr>
                <tr>
                    <td>رسالة الإغلاق</td>
                    <td><textarea name="disable_msg"><?=$DISABLE_MSG?></textarea></td>
                </tr>
                <tr>
                    <td>المجموعة المستثناه من الأغلاق</td>
                    <td>
                        <select name="group_disable">
                            <?php if($GROUP): ?>
                                <?php foreach ($GROUP as $value): ?>
                                    <option<?=($GROUPDISABLE == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">إعدادات التسجيل</td>
                </tr>
                <tr>
                    <td>تفعيل التسجيل</td>
                    <td><input type="radio" name="registerenable"<?=($REGISTERENABLE == 1)?' checked="checked"':''?> value="1" />نعم <input type="radio" name="registerenable"<?=($REGISTERENABLE == 0)?' checked="checked"':''?> value="0" />لا</td>
                </tr>
                <tr>
                    <td>المجموعة الخاصة بالأعضاء الجدد</td>
                    <td>
                        <select name="register_group">
                            <?php if($GROUP): ?>
                                <?php foreach ($GROUP as $value): ?>
                                    <option<?=($GROUPREGSITER == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>تنشيط العضوية تلقائي ؟</td>
                    <td><input type="radio" name="registeractive"<?=($REGISTERACTIVE == 1)?' checked="checked"':''?> value="1" />نعم <input type="radio" name="registeractive"<?=($REGISTERACTIVE == 0)?' checked="checked"':''?> value="0" />لا</td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="حفظ" /></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
