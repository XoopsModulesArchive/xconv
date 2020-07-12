<?php
// $Id: chinese.php,v 1.1.1.3 2004/11/08 03:39:57 phppp Exp $
//  ------------------------------------------------------------------------ //
//                        xconv:: iconv for XOOPS                            //
//             Copyright (c) 2004 Xoops China Community                      //
//                    <http://www.xoops.org.cn/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: D.J.(phppp) php_pp@hotmail.com                                    //
// URL: http://www.xoops.org.cn                                              //
// ------------------------------------------------------------------------- //

function big5_gb($text)
{
    static $chinese_handler;
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    $converted_text = $chinese_handler->big5_gb($text);
    return $converted_text;
}

function gb_big5($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->gb_big5($text);
}

function gb_utf8($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->gb_utf8($text);
}

function big5_utf8($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->big5_utf8($text);
}

function utf8_gb($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->utf8_gb($text);
}

function utf8_big5($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->utf8_big5($text);
}

function gb_pinyin($text)
{
    static $chinese_handler;   
    if(!is_object($chinese_handler)) $chinese_handler =& xoops_getmodulehandler('chinese', 'xconv');
    return $chinese_handler->gb_pinyin($text);
}
?>