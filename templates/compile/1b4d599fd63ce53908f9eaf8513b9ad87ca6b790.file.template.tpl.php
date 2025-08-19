<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:31:50
         compiled from "/home/mysidia/public_html/site/mys135b/templates/green/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:150866681257d97b768b0511-94974491%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    '1b4d599fd63ce53908f9eaf8513b9ad87ca6b790' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/green/template.tpl',
      1 => 1473868461,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '150866681257d97b768b0511-94974491',
  'function' =>
   [
  ],
  'variables' =>
   [
    'root' => 0,
    'temp' => 0,
    'theme' => 0,
    'menu' => 0,
    'site_name' => 0,
    'document_title' => 0,
    'document_content' => 0,
    'sidebar' => 0,
    'footer' => 0,
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d97b768cd548_59557890',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d97b768cd548_59557890')) {
    function content_57d97b768cd548_59557890($_smarty_tpl)
    {?><?php echo $_smarty_tpl->getSubTemplate(((string)$_smarty_tpl->tpl_vars['root']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, [], 0);?>

<body>
<div id='content'><ul id='toplist'><?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
</ul><h1><?php echo $_smarty_tpl->tpl_vars['site_name']->value;?>
</h1>
	<div id='container'>
	<div id='right'><h2><?php echo $_smarty_tpl->tpl_vars['document_title']->value;?>
</h2><p id='text'><?php echo $_smarty_tpl->tpl_vars['document_content']->value;?>
</p></div>
	<div id='left'><?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>
</div>
	<div style='clear: both'> </div>
	</div>
	<center><?php echo $_smarty_tpl->tpl_vars['footer']->value;?>
Theme by <a href="http://www.pixelpuppy.net" target="_blank">Arianna</a>.</center>
</div>
<div id='v1'> </div><div id='v2'></div><div id='v3'></div><div id='v4'></div><div id='v5'></div>
<div id='h1'></div><div id='h2'></div><div id='h3'></div><div id='h4'></div>
</body>
</html><?php }
    } ?>