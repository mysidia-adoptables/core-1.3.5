<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:20:58
         compiled from "/home/mysidia/public_html/site/mys135b/templates/main/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:23605566257d978ea97c018-35855910%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'e36392a4cb06771bc4090c201fbffe75b2628b38' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/main/header.tpl',
      1 => 1473868470,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '23605566257d978ea97c018-35855910',
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
  'unifunc' => 'content_57d978ea98fae2_39756840',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d978ea98fae2_39756840')) {
    function content_57d978ea98fae2_39756840($_smarty_tpl)
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