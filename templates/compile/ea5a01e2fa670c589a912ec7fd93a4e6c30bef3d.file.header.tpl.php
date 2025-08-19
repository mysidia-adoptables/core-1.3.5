<?php /* Smarty version Smarty-3.1.12, created on 2020-12-04 16:52:16
         compiled from "/home/mysidia/public_html/site/mys135rc/templates/acp/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1933652755fca6940b57fb6-51285284%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'ea5a01e2fa670c589a912ec7fd93a4e6c30bef3d' =>
     [
      0 => '/home/mysidia/public_html/site/mys135rc/templates/acp/header.tpl',
      1 => 1607098364,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '1933652755fca6940b57fb6-51285284',
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
    'js' => 0,
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5fca6940b6da59_73045949',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5fca6940b6da59_73045949')) {
    function content_5fca6940b6da59_73045949($_smarty_tpl)
    {?><html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['browser_title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['header']->value->loadFavicon(((string)$_smarty_tpl->tpl_vars['home']->value)."favicon.ico");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadStyle(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/media/acp-style.css");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadScript("http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js");?>

<?php echo $_smarty_tpl->tpl_vars['header']->value->loadScript(((string)$_smarty_tpl->tpl_vars['home']->value).((string)$_smarty_tpl->tpl_vars['js']->value)."/acp.js");?>

</head><?php }
    } ?>