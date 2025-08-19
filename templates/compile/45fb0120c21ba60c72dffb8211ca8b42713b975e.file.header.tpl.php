<?php /* Smarty version Smarty-3.1.12, created on 2020-12-04 16:52:06
         compiled from "/home/mysidia/public_html/site/mys135rc/templates/main/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10437001515fca69365911e7-85740553%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    '45fb0120c21ba60c72dffb8211ca8b42713b975e' =>
     [
      0 => '/home/mysidia/public_html/site/mys135rc/templates/main/header.tpl',
      1 => 1607098380,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '10437001515fca69365911e7-85740553',
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
  'unifunc' => 'content_5fca69365ab997_71183013',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5fca69365ab997_71183013')) {
    function content_5fca69365ab997_71183013($_smarty_tpl)
    {?><html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['browser_title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['header']->value->loadFavicon(((string)$_smarty_tpl->tpl_vars['home']->value)."favicon.ico");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/media/style-city.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['css']->value)."/menu.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadAdditionalStyle();?>

<!--[if lte IE 6]>
<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['css']->value)."/media/dropdown_ie.css");?>

<![endif]-->
</head><?php }
    } ?>