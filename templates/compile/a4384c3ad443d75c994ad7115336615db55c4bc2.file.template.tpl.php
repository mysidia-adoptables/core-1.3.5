<?php /* Smarty version Smarty-3.1.12, created on 2016-09-14 16:31:39
         compiled from "/home/mysidia/public_html/site/mys135b/templates/elements/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173785584357d97b6b14f628-63329964%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'a4384c3ad443d75c994ad7115336615db55c4bc2' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/elements/template.tpl',
      1 => 1473868455,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '173785584357d97b6b14f628-63329964',
  'function' =>
   [
  ],
  'variables' =>
   [
    'root' => 0,
    'temp' => 0,
    'theme' => 0,
    'menu' => 0,
    'document_title' => 0,
    'document_content' => 0,
    'sidebar' => 0,
    'footer' => 0,
  ],
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d97b6b1720b4_97705081',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d97b6b1720b4_97705081')) {
    function content_57d97b6b1720b4_97705081($_smarty_tpl)
    {?><?php echo $_smarty_tpl->getSubTemplate(((string)$_smarty_tpl->tpl_vars['root']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, [], 0);?>


<body>
<div id="wrapper">
<div id="left">
<img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
templates/elements/inc/images/left.png">
<ul> 
<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>

</ul> 

</div>
<div id="center">
<img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
templates/elements/inc/images/center.png">
<h1><?php echo $_smarty_tpl->tpl_vars['document_title']->value;?>
</h1>

<p><?php echo $_smarty_tpl->tpl_vars['document_content']->value;?>
</p>

</div>

<div id="right">
<img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
templates/elements/inc/images/right.png">
<p><?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>
</p>
</div>

<div id="footer">
<?php echo $_smarty_tpl->tpl_vars['footer']->value;?>
<p>Theme by <a href="http://sessions-st.net">Sessions Street</a></p> 
</div>

</div>


</body>

</html>
<?php }
    } ?>