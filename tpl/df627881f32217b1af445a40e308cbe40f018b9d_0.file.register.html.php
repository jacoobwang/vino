<?php
/* Smarty version 3.1.31, created on 2016-12-30 21:01:33
  from "/Users/wangyong/www/mphp/templates/register.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58665aadef52c8_51625418',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df627881f32217b1af445a40e308cbe40f018b9d' => 
    array (
      0 => '/Users/wangyong/www/mphp/templates/register.html',
      1 => 1483102866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58665aadef52c8_51625418 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mphp-注册页面测试</title>
    <!--用百度的静态资源库的cdn安装bootstrap环境-->
    <!-- Bootstrap 核心 CSS 文件 -->
    <link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!--font-awesome 核心我CSS 文件-->
    <link href="//cdn.bootcss.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- 在bootstrap.min.js 之前引入 -->
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['JS_CSS_DOMAIN']->value;?>
/js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"><?php echo '</script'; ?>
>
    <!-- Bootstrap 核心 JavaScript 文件 -->
    <?php echo '<script'; ?>
 src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <style type="text/css">
        body{background: #ccc;}
        .form{background: rgba(255,255,255,0.2);width:400px;margin:100px auto;}
        #login_form{display: block;}
        #register_form{display: none;}
        .fa{display: inline-block;top: 27px;left: 6px;position: relative;color: #ccc;}
        input[type="text"],input[type="password"]{padding-left:26px;}
        .checkbox{padding-left:21px;}
    </style>
</head>
<body>
<!--
    基础知识：
    网格系统:通过行和列布局
    行必须放在container内
    手机用col-xs-*
    平板用col-sm-*
    笔记本或普通台式电脑用col-md-*
    大型设备台式电脑用col-lg-*
    为了兼容多个设备，可以用多个col-*-*来控制；
-->
<div class="container">
    <div class="form row">
        <form class="form-horizontal col-sm-offset-3 col-md-offset-3" id="login_form">
            <h3 class="form-title">Login to your account</h3>
            <div class="col-sm-9 col-md-9">
                <div class="form-group">
                    <i class="fa fa-user fa-lg"></i>
                    <input type="hidden" name="__csrfToken" value="<?php echo $_smarty_tpl->tpl_vars['csrfToken']->value;?>
">
                    <input class="form-control required" type="text" placeholder="Username" name="username" autofocus="autofocus" maxlength="20"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-lock fa-lg"></i>
                    <input class="form-control required" type="password" placeholder="Password" name="password"/>
                </div>
                <div class="form-group">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" value="1"/> Remember me
                    </label>
                    <hr />
                    <a href="javascript:;" id="register_btn" class="">Create an account</a>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success pull-right" value="Login "/>
                </div>
            </div>
        </form>
    </div>

    <div class="form row">
        <form class="form-horizontal col-sm-offset-3 col-md-offset-3" id="register_form">
            <h3 class="form-title">Sign up your account</h3>
            <div class="col-sm-9 col-md-9">
                <div class="form-group">
                    <i class="fa fa-user fa-lg"></i>
                    <input class="form-control required" type="text" placeholder="Username" name="reg_username" autofocus="autofocus"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-lock fa-lg"></i>
                    <input class="form-control required" type="password" placeholder="Password" id="register_password" name="reg_password"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-check fa-lg"></i>
                    <input class="form-control required" type="password" placeholder="Re-type Your Password" name="reg_rpassword"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-envelope fa-lg"></i>
                    <input class="form-control eamil" type="text" placeholder="Email" name="reg_email"/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success pull-right" id="js_sign_up" value="Sign Up "/>
                    <input type="button" class="btn btn-info pull-left" id="back_btn" value="Back"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JS_CSS_DOMAIN']->value;?>
/js/reg.js" ><?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
