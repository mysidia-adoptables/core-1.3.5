<?php

// This file parses BBCode for user editable pages
// Thanks to TheMagnitude for this code and for sharing
// BBcodes mod credit to Teshia

function bbconvert($text)
{
    // CONVERT ALL HTML SPECIAL CHARS TO THERE ENTITIES
    $text = htmlentities((string) $text, ENT_QUOTES);

    // PARSE BB CODE
    $text = preg_replace('|\[b\](.+?)\[\/b\]|i', '<strong>$1</strong>', $text);
    $text = preg_replace('|\[i\](.+?)\[\/i\]|i', '<em>$1</em>', (string) $text);
    $text = preg_replace('|\[u\](.+?)\[\/u\]|i', '<u>$1</u>', (string) $text);
    $text = preg_replace('|\[img\](.+?)\[\/img\]|i', '<img src="$1" border="0">', (string) $text);
    $text = preg_replace('|\[url=(.+?)\](.+?)\[\/url\]|i', '<a href="$1" target="_blank">$2</a>', (string) $text);
    $text = preg_replace('|\[color=(.+?[^;])\](.+?)\[\/color\]|i', '<span style="color:$1;">$2</span>', (string) $text);
    $text = preg_replace('|\[size=(.+?[^;])\](.+?)\[\/size\]|i', '<span style="font-size:$1;">$2</span>', (string) $text);
    $text = preg_replace('|\[left\](.+?)\[\/left\]|i', '<span style="text-align: left;">$1</span>', (string) $text);
    $text = preg_replace('|\[right\](.+?)\[\/right\]|i', '<span style="text-align: right;">$1</span>', (string) $text);
    $text = preg_replace('|\[center\](.+?)\[\/center\]|i', '<center>$1</center>', (string) $text);
    $text = preg_replace('|\[urlsame=(.+?)\](.+?)\[\/urlsame\]|i', '<a href="$1" target="_self">$2</a>', (string) $text);
    $text = preg_replace('|\[s\](.+?)\[\/s\]|i', '<del>$1</del>', (string) $text);
    $text = preg_replace('|\[url\](.+?)\[\/url\]|i', '<a href="$1" target="_blank">$1</a>', (string) $text);
    $text = preg_replace('|\:hr\:|i', '<hr>', (string) $text);
    $text = preg_replace('|\[youtube\](.+?)\[\/youtube\]|i', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="425" height="350"></embed></object>', (string) $text);
    $text = preg_replace('|\[profile\](.+?)\[\/profile\]|i', '<a href="profile.php?user=$1">$1</a>', (string) $text);
    $text = preg_replace('|\[imgmap\](.+?)\[\/imgmap\]|i', '<img src="$1" border="0" usemap="#map1">', (string) $text);
    $text = preg_replace('|\[map\]|i', '<map name="map1">', (string) $text);
    $text = preg_replace('|\[\/map\]|i', '</map>', (string) $text);
    $text = preg_replace('|\[where=(.+?)\,(.+?)\,(.+?)\,(.+?)=(.+?)\]|i', '<area shape="rect" coords="$1,$2,$3,$4" href="$5">', (string) $text);
    $text = preg_replace('|\[wherecirc=(.+?)\,(.+?)\,(.+?)\=(.+?)\]|i', '<area shape="circle" coords="$1,$2,$3" href="$4">', (string) $text);

    // RETURN HTML RESULT
    return $text;
}
