<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:21:09
         compiled from "/home/mysidia/public_html/site/mys135b/templates/acp/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16837873957d978f50317f1-71423871%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'dff81110f3bd5cfc6db9561cf2b21c52c0fbfd71' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/acp/header.tpl',
      1 => 1473868449,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '16837873957d978f50317f1-71423871',
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
  'unifunc' => 'content_57d978f503d383_47290902',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d978f503d383_47290902')) {
    function content_57d978f503d383_47290902($_smarty_tpl)
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