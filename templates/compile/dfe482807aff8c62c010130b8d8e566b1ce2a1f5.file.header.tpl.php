<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:31:39
         compiled from "/home/mysidia/public_html/site/mys135b/templates/elements/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:130920793857d97b6b173d15-20697237%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'dfe482807aff8c62c010130b8d8e566b1ce2a1f5' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/elements/header.tpl',
      1 => 1473868453,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '130920793857d97b6b173d15-20697237',
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
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d97b6b17c585_46954980',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d97b6b17c585_46954980')) {
    function content_57d97b6b17c585_46954980($_smarty_tpl)
    {?><html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['browser_title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['header']->value->loadFavicon(((string)$_smarty_tpl->tpl_vars['home']->value)."favicon.ico");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/inc/style.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadAdditionalStyle();?>

</head><?php }
    } ?>