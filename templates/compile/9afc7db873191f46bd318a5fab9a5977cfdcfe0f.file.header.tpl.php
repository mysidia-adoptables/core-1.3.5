<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:31:50
         compiled from "/home/mysidia/public_html/site/mys135b/templates/green/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:92140803157d97b768cef00-35093754%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    '9afc7db873191f46bd318a5fab9a5977cfdcfe0f' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/green/header.tpl',
      1 => 1473868456,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '92140803157d97b768cef00-35093754',
  'function' =>
   [
  ],
  'variables' =>
   [
    'browser_title' => 0,
    'home' => 0,
    'header' => 0,
    'temp' => 0,
    'theme' => 0,
    'css' => 0,
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d97b768da283_06970499',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d97b768da283_06970499')) {
    function content_57d97b768da283_06970499($_smarty_tpl)
    {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_smarty_tpl->tpl_vars['browser_title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['header']->value->loadFavicon(((string)$_smarty_tpl->tpl_vars['home']->value)."favicon.ico");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/style.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['css']->value)."/menu.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadAdditionalStyle();?>

</head><?php }
    } ?>