{include file="{$root}{$temp}{$theme}/header.tpl"}

<body>
<div id="wrapper">
<table cellspacing="0" cellpadding="0">
<tr><th colspan="2">{$menu}</th></tr>
<tr><td colspan="2" id="image"><span><a href="{$path}index">{$site_name}</a></span></td></tr>
<tr><td id="menu">
{$sidebar}


</td><td id="content">
<h1>{$document_title}</h1>
<p>{$document_content}</p>

</td></tr>
<tr><td colspan="2" id="footer">{$footer}</td></tr>

</table>
</div>

</body>

</html>