<?php if($STEP == 'init'):?>
<form method="post">
    <table class="tbl" style="width:85%;direction:rtl">
        <thead>
            <tr>
                <td colspan="2">السجل</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>اسم العضو</td>
                <td>
                    <select name="users">
                        <option value="all">الجميع</option>
                        <?php if(isset($USERS)): ?>
                            <?php foreach ($USERS as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->username?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>الفترة</td>
                <td>
                    <select name="time">
                        <option value="all">جميع السجلات</option>
                        <option value="day">خلال يوم</option>
                        <option value="month">خلال شهر</option>
                        <option value="year">خلال سنة</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="البدء" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif($STEP == 'view'): ?>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <td colspan="5">عرض السجلات</td>
            </tr>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>النشاط</th>
                <th>ip</th>
                <th>العضو</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($LOGS)): ?>
                <?php foreach($LOGS as $row): ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=date('Y-m-d H:i',$row->date)?></td>
                <td><?=$row->activity?></td>
                <td><?=$row->ip?></td>
                <td><?=$this->users->getUsername($row->users_id)?></td>
            </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

