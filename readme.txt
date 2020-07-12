2004-09-28: D.J.(phppp) http://www.xoops.org.cn, http://dev.xoops.org/modules/xfmod/project/?digest
===========================================================================================================
Xconv Version 1.5 - Xoops Iconv

This module is built to provide more practical/effective functions than php iconv for character encoding conversion. 
Chinese package has been included: gb<->big5, (gb,big5)<->utf8<->unicode, (gb, big5)->pinyin.
The Chinese package was built based on the Chinese encoding lib: Hessian (solarischan@21cn.com); Wang Jue (wjue@wjue.org)

You can add your own functions/classes to handle your character encoding conversion.
:: 1 module admin area: add charset
:: 2 charset: the charset name to be used
:: 3 alias: 'alias' for the charset, such as the Chinese gb charset can include gb2312,gb18030,gb,gbk
:: 4 local function definition files: you should build your conversion functions and upload the files to XOOPS_ROOT_PATH/modules/xconv/include/localfunctions

How to use it:
example: Convert gb2312 to utf-8
<?php
	$in_charset = "gb2312";
	$out_charset = "utf8";
	$xconv_ok = false;
	if(!isset($xconv_handler)) $xconv_handler =@xoops_getmodulehandler('xconv', 'xconv', true);
	if(is_object($xconv_handler)) $xconv_ok = $xconv_handler->getXconv($in_charset, $out_charset);			
	if($xconv_ok) $converted_text = $xconv_handler->iconv($in_charset, $out_charset, $text);
	else $converted_text = @iconv($in_charset, $out_charset."//TRANSLIT", $text); // if xconv not available, use php iconv function
?>

Acknowledgement:
Translation: French, by outch from http://www.xoops-modules.com