<?php if($STEP == "show"): ?>
<div id="delete" class="tbl" style="color:white;background-color:red;display:none;width:50%;text-align:center" ></div>
    <table class="tbl" id="list" style="width:100%">
        <thead>
            <tr>
                <td colspan="3">عرض اخر عمليات التواصل</td>
                <th><a href="<?=base_url()?>communication/add"><img src="<?=$STYLE_FOLDER?>icon/add.png" alt="تواصل مع الأدارة" title="تواصل مع الأدارة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>القسم</th>
                <th>المستخدم</th>
            </tr>
        </thead>
        <tbody>
            <?php if($query) : ?>
                <?php foreach(@$query as $row): ?>
                    <tr id="posts<?=$row->id?>">
                        <td><?=$row->id?></a></td>
                        <td><a href="<?=base_url()?>communication/view/<?=$row->id?>"><?=$row->title?></a></td>
                        <td><?=$this->cats->getNameOfCat($row->cat_id)?></td>
                        <td><?=$this->users->getUsername($row->users_id)?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php elseif($STEP == "add"): ?>
    <form method="post">
        <table class="tbl" style="width:80%;margin-top: 20px;">
            <thead>
                <tr>
                    <td colspan="2">التواصل مع الادارة</td>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: palegoldenrod;">
                    <td>العنوان</td>
                    <td><input type="text" name="title" style="width: 95%;" id="title" required="required" /></td>
                </tr>
                <tr>
                    <td>القسم</td>
                    <td>
                        <select name="cat">
                            <?php if($CATS): ?>
                                <?php foreach ($CATS as $row): ?>
                                    <option value="<?=$row->id?>"><?=$row->name?> - <?=$row->desc?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>المحتوى</td>
                    <td>
                        <textarea id="content" name="content" rows="10" style="width: 95%"></textarea>
                    </td>
                </tr>
                <?php if(@$ERROR): ?>
                    <tr>
                        <td colspan="2"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="أضافة"/></td>
                </tr>
            </tbody>
        </table>
    </form>
<?php elseif($STEP == "view"): ?>
<table class="tbl" style="width:80%">
    <tbody>
        <tr>
            <td>العنوان :</td>
            <td><?=@$TITLEM?></td>
        </tr>
        <tr>
            <td>المستخدم :</td>
            <td><?=@$FROM?></td>
        </tr>
        <tr>
            <td>التاريخ :</td>
            <td><?=@$DATE?></td>
        </tr>
        <tr>
            <td colspan="2"><?=@$CONTENT_MSG?></td>
        </tr>
    </tbody>
</table>
<?php if(@$REPLAY): ?>
<div class="message">الردود :</div>
    <?php foreach($REPLAY as $row): ?>
        <table class="tbl" style="width:80%">
            <tbody>
                <tr>
                    <td>العنوان :</td>
                    <td><?=@$row->title?></td>
                </tr>
                <tr>
                    <td>المستخدم :</td>
                    <td><?=$this->users->getUsername($row->users_id)?></td>
                </tr>
                <tr>
                    <td>التاريخ :</td>
                    <td><?=date('Y-m-d H:i:s',$row->date)?></td>
                </tr>
                <tr>
                    <td colspan="2"><?=@$row->content?></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php endif; ?>
<br />
<form method="post">
    <table class="tbl" style="width:80%;margin-top: 20px;">
        <thead>
            <tr>
                <td colspan="2">الرد</td>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: palegoldenrod;">
                <td>العنوان</td>
                <td><input type="text" name="title" style="width: 95%;" id="title" required="required" /></td>
            </tr>
            <tr>
                <td>المحتوى</td>
                <td>
                    <textarea id="content" name="content" rows="10" style="width: 95%"></textarea>
                </td>
            </tr>
            <?php if(@$ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="أضافة الرد"/></td>
            </tr>
        </tbody>
    </table>
</form>
<?php endif; ?>

