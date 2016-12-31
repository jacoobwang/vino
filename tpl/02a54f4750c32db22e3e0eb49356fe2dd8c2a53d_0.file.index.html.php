<?php
/* Smarty version 3.1.31, created on 2016-12-30 18:05:36
  from "/Users/wangyong/www/mphp/templates/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58663170903ac0_78993464',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '02a54f4750c32db22e3e0eb49356fe2dd8c2a53d' => 
    array (
      0 => '/Users/wangyong/www/mphp/templates/index.html',
      1 => 1483091664,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58663170903ac0_78993464 (Smarty_Internal_Template $_smarty_tpl) {
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
