<?php /* Smarty version Smarty-3.1.12, created on 2020-11-07 09:06:50
         compiled from "/home/mysidia/public_html/site/mys135b/templates/acp/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:115587912557d978f5013fa8-43312786%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    '82ea20f5e1612d177e7638cfa394a4d9942c028d' =>
     [
      0 => '/home/mysidia/public_html/site/mys135b/templates/acp/template.tpl',
      1 => 1603942988,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '115587912557d978f5013fa8-43312786',
  'function' =>
   [
  ],
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_57d978f502fd27_64681984',
  'variables' =>
   [
    'root' => 0,
    'temp' => 0,
    'theme' => 0,
    'home' => 0,
    'sidebar' => 0,
    'document_title' => 0,
    'document_content' => 0,
  ],
  'has_nocache_code' => false,
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57d978f502fd27_64681984')) {
    function content_57d978f502fd27_64681984($_smarty_tpl)
    {?><?php echo $_smarty_tpl->getSubTemplate(((string)$_smarty_tpl->tpl_vars['root']->value).((string)$_smarty_tpl->tpl_vars['temp']->value).((string)$_smarty_tpl->tpl_vars['theme']->value)."/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, [], 0);?>


<body>
<table cellpadding="0" cellspacing="0">
<tr><td colspan="2" class="banner"><center>
<img src="<?php echo $_smarty_tpl->tpl_vars['home']->value;?>
<?php echo $_smarty_tpl->tpl_vars['temp']->value;?>
<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/media/acp-banner.png" alt="Mysidia PHP Adoptables" title="Mysidia Adoptables" />
</center></td></tr>
<tr><th width="25%" id="logo"><strong>MyMysidia</strong> Admin</th>
<th id="admin">Welcome Admin!</th></tr>
<tr><td width="25%" id="menu"><?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>
</td>
<td id="content">

<p><font size="5"><b><?php echo $_smarty_tpl->tpl_vars['document_title']->value;?>
</b></font></p><hr>
<p><?php echo $_smarty_tpl->tpl_vars['document_content']->value;?>
</p>
</td></tr>
</table>

<center><b>MyMysidia</b> Powered By <a href="http://mysidiaadoptables.com">Mysidia Adoptables</a> &copy;Copyright 2011-2020.</center>


</body>
</html><?php }
    } ?>