<script>
    $(document).ready(function(){
        jQuery.validator.addMethod("checkusername", function(username, element) {
                var check =  $.get('<?=base_url()?>register/check/username/'+username,function(data){
                    if(data == '0'){
                        $('#checkusername').removeClass();
                        $('#checkusername').addClass('strong');
                        $('#checkusername').html('متاح');
                    }else{
                        $('#checkusername').removeClass();
                        $('#checkusername').addClass('short');
                        if(data == '2')
                            $('#checkusername').html('حدث خطأ ما');
                        else if(data == '3')
                            $('#checkusername').html('مسجل مسبقاً');
                        else
                            $('#checkusername').html('عفواً لايمكن استخدام غير الأحرف الانجليزية');
                    }
                    return data;
                });
                if(check == '0')
                    return false;
                else
                    return true;
       },'');
        jQuery.validator.addMethod("checkemail", function(email, element) {
                var check = $.post('<?=base_url()?>register/check',{ type: "email", value: email },function(data){
                    if(data == '0'){
                        $('#checkemail').removeClass();
                        $('#checkemail').addClass('strong');
                        $('#checkemail').html('متاح');
                    }else{
                        $('#checkemail').removeClass();
                        $('#checkemail').addClass('short');
                        if(data == '2')
                            $('#checkemail').html('حدث خطأ ما');
                        else if(data == '3')
                            $('#checkemail').html("أذا كنت نسيت كلمة المرور يمكنك طلبه من <a href=\"<?=base_url()?>login/resetpassword\">هنا</a>");
                        else
                            $('#checkemail').html('عفواً لايمكن استخدام غير الأحرف الانجليزية');
                    }
                    return data;
                });
                if(check == '0')
                    return false;
                else
                    return true;
        },'');

        $("#register").validate({
            rules: {
                repassword: {
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true,
                    checkemail:true
                },
                username:{
                    minlength:4,
                    required: true,
                    checkusername:true
                }
            },
            messages: {
                username: {
                  required:  "أسم المستخدم",
                  minlength: jQuery.format("على الأقل {0} أحرف مطلوب")
                },
                email: {
                required: "نحتاج لأيميلك للتواصل معك",
                email: "صيغة الايميل يجب ان تكون على الشكل التالي name@domain.com"
                },
                repassword: {
                    equalTo: "يجب ان يكون الباسورد متطابق"
                }
            }
        });
        $('#password').keyup(function(){
            $('#result').html(checkStrength($('#password').val()));
        })
        $('#repassword').keyup(function(){
            if($(this).equalTo('password')){
                $('#resultre').removeClass();
                $('#resultre').addClass('strong');
                $('#resultre').html('صحيح');
            }else{
                $('#resultre').removeClass();
                $('#resultre').addClass('short');
                $('#resultre').html('خطأ');
            }
        }) 

        function checkStrength(password){

            //initial strength
            var strength = 0;

            //if the password length is less than 6, return message.
            if (password.length < 6) {
                $('#result').removeClass();
                $('#result').addClass('short');
                return 'قصيرة جداً';
            }

            //length is ok, lets continue.

            //if length is 8 characters or more, increase strength value
            if (password.length > 7) strength += 1;

            //if password contains both lower and uppercase characters, increase strength value
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;

            //if it has numbers and characters, increase strength value
            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;

            //if it has one special character, increase strength value
            if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;

            //if it has two special characters, increase strength value
            if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

            //now we have calculated strength value, we can return messages

            //if value is less than 2
            if (strength < 2 ) {
                $('#result').removeClass();
                $('#result').addClass('weak');
                return 'ضعيف';
            } else if (strength == 2 ) {
                $('#result').removeClass();
                $('#result').addClass('good');
                return 'جيد';
            } else {
                $('#result').removeClass();
                $('#result').addClass('strong');
                return 'قوي';
            }
        }
    });
</script>

<form method="post" id="register">
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="3">التسجيل</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أسم المستخدم :</td>
                    <td><input type="text" name="username" required id="username" /></td>
                    <td id="checkusername"></td>
                </tr>
                <tr>
                    <td>الأسم الكامل :</td>
                    <td colspan="2"><input type="text" name="fullName" required id="fullName" /></td>
                </tr>
                <tr>
                    <td>الأيميل :</td>
                    <td><input type="text" name="email" required id="email" /></td>
                    <td id="checkemail"></td>
                </tr>
                <tr>
                    <td>الجوال :</td>
                    <td colspan="2"><input type="text" name="mobile" id="mobile" /></td>
                </tr>
                <tr>
                    <td>كلمة المرور :</td>
                    <td><input type="password" name="password" required id="password" /></td>
                    <td id="result"></td>
                </tr>
                <tr>
                    <td>تأكيد كلمة المرور :</td>
                    <td><input type="password" name="repassword" required id="repassword" /></td>
                    <td id="resultre"></td>
                </tr>
                <tr>
                    <td>الكود الأمني</td>
                    <td colspan="2"><?=$CAPTCHA?></td>
                </tr>
                <tr>
                    <td>أدخل السؤال الأمني</td>
                    <td colspan="2"><input type="text" required name="captcha" /></td>
                </tr>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="3" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3"><input type="submit" value="تسجيل" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
