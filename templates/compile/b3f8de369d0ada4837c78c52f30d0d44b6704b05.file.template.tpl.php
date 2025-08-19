<?php /* Smarty version Smarty-3.1.12, created on 2020-12-04 16:52:16
         compiled from "/home/mysidia/public_html/site/mys135rc/templates/acp/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11331503235fca6940b21a63-19895706%%*/if (!defined('SMARTY_DIR')) {
    exit('no direct access allowed');
}
$_valid = $_smarty_tpl->decodeProperties([
  'file_dependency' =>
   [
    'b3f8de369d0ada4837c78c52f30d0d44b6704b05' =>
     [
      0 => '/home/mysidia/public_html/site/mys135rc/templates/acp/template.tpl',
      1 => 1607098365,
      2 => 'file',
    ],
  ],
  'nocache_hash' => '11331503235fca6940b21a63-19895706',
  'function' =>
   [
  ],
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
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5fca6940b55699_33609764',
], false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5fca6940b55699_33609764')) {
    function content_5fca6940b55699_33609764($_smarty_tpl)
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