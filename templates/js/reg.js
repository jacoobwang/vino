/**
 * Created by wangyong7 on 2016/12/16.
 */
$().ready(function() {
    $("#login_form").validate({
        rules: {
            username: "required",
            password: {
                required: true,
                minlength: 5
            },
        },
        messages: {
            username: "请输入姓名",
            password: {
                required: "请输入密码",
                minlength: "密码不能小于{0}个字 符"
            },
        },
        submitHandler:function(){
            var data = {
                user : $("input[name=username]").val(),
                pwd  : $("input[name=password]").val()
            };

            $.ajax(
                {
                    url  : "/mphp/api/user/login",
                    type : "POST",
                    data : data,
                    dataType : "json",
                    success : function(res){
                        console.log(res);
                        if(res.errno == 0) {
                            localStorage.nickname = res.body.nickname;
                            location.href="/mphp/userInfo";
                        } else {
                            if(typeof res.error == "string") {
                                alert(res.error);
                            } else {
                                var info = 'Error:';
                                for(i in res.error){
                                    info += res.error.i+";";
                                }
                                alert(info);
                            }
                        }
                    }
                }
            );
        }
    });
    $("#register_form").validate({
        rules: {
            username: "required",
            password: {
                required: true,
                minlength: 5
            },
            rpassword: {
                equalTo: "#register_password"
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            username: "请输入姓名",
            password: {
                required: "请输入密码",
                minlength: "密码不能小于{0}个字 符"
            },
            rpassword: {
                equalTo: "两次密码不一样"
            },
            email: {
                required: "请输入邮箱",
                email: "请输入有效邮箱"
            }
        },
        submitHandler:function(){
            var data = {
                user : $("input[name=reg_username]").val(),
                pwd  : $("input[name=reg_password]").val(),
                email: $("input[name=reg_email]").val()
            };
            $.ajax(
                {
                    url  : "/mphp/api/user/reg",
                    type : "POST",
                    data : data,
                    dataType : "json",
                    success : function(res){
                        console.log(res);
                        if(res.errno == 0) {
                            alert(res.body.msg);
                        } else {
                            if(typeof res.error == "string") {
                                alert(res.error);
                            } else {
                                var info = 'Error:';
                                for(i in res.error){
                                    info += res.error[i]+";";
                                }
                                alert(info);
                            }
                        }
                    }
                }
            );
        }
    });
});
$(function() {
    $("#register_btn").click(function() {
        $("#register_form").css("display", "block");
        $("#login_form").css("display", "none");
    });
    $("#back_btn").click(function() {
        $("#register_form").css("display", "none");
        $("#login_form").css("display", "block");
    });
});