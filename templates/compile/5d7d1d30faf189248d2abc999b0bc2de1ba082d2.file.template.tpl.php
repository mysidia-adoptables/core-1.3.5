<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:20:58
         compiled from "/home/mysidia/public_html/site/mys135b/templates/main/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:206693970057d978ea945096-07481979%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    '5d7d1d30faf189248d2abc999b0bc2de1ba082d2' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/main/template.tpl',
      1 => 1473868471,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '206693970057d978ea945096-07481979',
  'function' =>
   [
  ],
  'variables' =>
   [
    'root' => 0,
    'temp' => 0,
    'theme' => 0,
    'menu' => 0,
    'path' => 0,
    'site_name' => 0,
    'sidebar' => 0,
    'document_title' => 0,
    'document_content' => 0,
    'footer' => 0,
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d978ea9797b6_62916287',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d978ea9797b6_62916287')) {
    function content_57d978ea9797b6_62916287($_smarty_tpl)
    {?><?php echo $_smarty_tpl->getSubTemplate(((string)$_smarty_tpl->tpl_vars['root']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, [], 0);?>


<body>
<div id="wrapper">
<table cellspacing="0" cellpadding="0">
<tr><th colspan="2"><?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
</th></tr>
<tr><td colspan="2" id="image"><span><a href="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
index"><?php echo $_smarty_tpl->tpl_vars['site_name']->value;?>
</a></span></td></tr>
<tr><td id="menu">
<?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>



</td><td id="content">
<h1><?php echo $_smarty_tpl->tpl_vars['document_title']->value;?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['document_content']->value;?>
</p>

</td></tr>
<tr><td colspan="2" id="footer"><?php echo $_smarty_tpl->tpl_vars['footer']->value;?>
</td></tr>

</table>
</div>

</body>

</html><?php }
    } ?>