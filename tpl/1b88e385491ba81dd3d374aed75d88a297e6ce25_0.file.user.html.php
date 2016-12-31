<?php
/* Smarty version 3.1.31, created on 2016-12-30 20:43:41
  from "/Users/wangyong/www/mphp/templates/user.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5866567d7c04d7_55205181',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1b88e385491ba81dd3d374aed75d88a297e6ce25' => 
    array (
      0 => '/Users/wangyong/www/mphp/templates/user.html',
      1 => 1483101816,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5866567d7c04d7_55205181 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Mphp</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['JS_CSS_DOMAIN']->value;?>
/css/style.css">
</head>
<body>
<div class="container">
	<img src="<?php echo $_smarty_tpl->tpl_vars['JS_CSS_DOMAIN']->value;?>
/img/mphp-logo.png">
	<h3 class="title">Welcome , <span class="red" id="user"></span> this is your page <br><a id="js_logout" href="/mphp/logout">Login out</a></h3>
</div>
</body>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['JS_CSS_DOMAIN']->value;?>
/js/jquery.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
	$(function() {
		$("#user").html(localStorage.nickname);
		$("#js_logout").click(function(){
			localStorage.removeItem("nickname");
		});
	})
<?php echo '</script'; ?>
>
</html>
<?php }
}
