<?php
/* Smarty version 3.1.30, created on 2016-12-30 11:13:28
  from "D:\wamp\www\Mphp\templates\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5865d0d8ef3672_69930314',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'afbf5c42c72ea2b9638824f65595711b5c1306cd' => 
    array (
      0 => 'D:\\wamp\\www\\Mphp\\templates\\index.html',
      1 => 1483067439,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5865d0d8ef3672_69930314 (Smarty_Internal_Template $_smarty_tpl) {
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
	<h1 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
	<p><?php echo $_smarty_tpl->tpl_vars['word']->value;?>
</p>
	<p><?php echo $_smarty_tpl->tpl_vars['contact']->value;?>
</p>
	<p><?php echo $_smarty_tpl->tpl_vars['author']->value;?>
</p>
</div>
</body>
</html>
<?php }
}
