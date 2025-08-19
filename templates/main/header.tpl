<html>
<head>
<title>{$browser_title}</title>
{$header->loadFavicon("{$home}favicon.ico")}
{$header->loadStyle("{$home}{$temp}{$theme}/media/style-city.css")}
{$header->loadStyle("{$home}{$css}/menu.css")}
{$header->loadAdditionalStyle()}
<!--[if lte IE 6]>
{$header->loadStyle("{$home}{$css}/media/dropdown_ie.css")}
<![endif]-->
</head>