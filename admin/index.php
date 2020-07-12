<?php
// $Id: index.php,v 1.1.1.2 2004/11/08 03:39:45 phppp Exp $
//  ------------------------------------------------------------------------ //
//                        xconv:: iconv for XOOPS                            //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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

include("../../../include/cp_header.php");
include_once XOOPS_ROOT_PATH."/modules/xconv/include/vars.php";
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";

$op = 'manage';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['xconv_id'])) $xconv_id = $_GET['xconv_id'];
if (isset($_POST['xconv_id'])) $xconv_id = $_POST['xconv_id'];
$xitem_handler =& xoops_getmodulehandler('xitem', 'xconv');

function newXitem(){
	editXitem();
}

function editXitem($xconv_id = 0){
	global $xoopsModule, $xitem_handler;
    if ($xconv_id > 0) {
        $xitem =& $xitem_handler->get($xconv_id);
    }
    else {
        $xitem =& $xitem_handler->create();
    }
	
	if ($xconv_id){
		$sform = new XoopsThemeForm(_MD_A_XCONV_EDITXITEM . " " . $xitem->getVar('charset'), "op", xoops_getenv('PHP_SELF'));
		
	}else{
		$sform = new XoopsThemeForm(_MD_A_XCONV_CREATENEWXITEM, "op", xoops_getenv('PHP_SELF'));
		$xitem->setVar('chaset', '');
		$xitem->setVar('chasets', '');
		$xitem->setVar('inc', '');
	}
	
	$sform->addElement(new XoopsFormText(_MD_A_XCONV_CHARSET, 'charset', 80, 255, $xitem->getVar('charset')), true);
	$sform->addElement(new XoopsFormText(_MD_A_XCONV_ALIAS, 'alias', 80, 255, $xitem->getVar('alias')), true);
	$sform->addElement(new XoopsFormHidden('xconv_id', $xconv_id));
	
	$inc_option_tray = new XoopsFormElementTray(_MD_A_XCONV_INC, '<br />');
	$inc_arr =& XoopsLists::getFileListAsArray(XCONV_LOCAL_FUNCTIONS_DIR);
	$inc_array = array();
	foreach($inc_arr as $key => $val){
		if(strtolower($val) == 'index.php'||strtolower($val) == 'index.htm'||strtolower($val) == 'index.html') continue;
		$inc_array[$key] = $val;
	}
	unset($inc_arr);
	$inc_array['']="NULL";
	$inc_select = new XoopsFormSelect('', 'inc', $xitem->getVar('inc'));
	$inc_select->addOptionArray($inc_array);
	$inc_tray = new XoopsFormElementTray('', '&nbsp;');
	$inc_tray->addElement($inc_select);
	$inc_option_tray->addElement($inc_tray);
	$sform->addElement($inc_option_tray);
	
	$button_tray = new XoopsFormElementTray('', '');
	$button_tray->addElement(new XoopsFormHidden('op', 'save'));
	
	$butt_save = new XoopsFormButton('', '', _SUBMIT, 'submit');
	$butt_save->setExtra('onclick="this.form.elements.op.value=\'save\'"');
	$button_tray->addElement($butt_save);
	if ($xconv_id){
		$butt_delete = new XoopsFormButton('', '', _CANCEL, 'submit');
		$butt_delete->setExtra('onclick="this.form.elements.op.value=\'default\'"');
		$button_tray->addElement($butt_delete);
	}
	$sform->addElement($button_tray);
	$sform->display();
}

xoops_cp_header();

switch($op){
	
	
	case "manage":	
	
	$xitems = $xitem_handler->getAllXitems();
	if (empty($xitems)){
		echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _MD_A_XCONV_CREATENEWXITEM  . "</legend>";
		echo "<br />";
		
		newXitem();
		
		echo "</fieldset>";

		
		break;
	}
	
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" .  _MD_A_XCONV_XITEMADMIN . "</legend>";
	echo"<br />";
	echo "<a style='border: 1px solid #5E5D63; color: #000000; font-family: verdana, tahoma, arial, helvetica, sans-serif; font-size: 1em; padding: 4px 8px; text-align:center;' href='index.php?op=new'>"._MD_A_XCONV_CREATENEWXITEM."</a><br /><br />";
	
	
	echo "<table border='0' cellpadding='4' cellspacing='1' width='100%' class='outer'>";
	echo "<tr align='center'>";
	echo "<td class='bg3' width='10%'>"._MD_A_XCONV_CHARSET."</td>";
	echo "<td class='bg3'>"._MD_A_XCONV_ALIAS."</td>";
	echo "<td class='bg3' width='20%'>"._MD_A_XCONV_INC."</td>";
	echo "<td class='bg3' width='5%'>"._EDIT."</td>";
	echo "<td class='bg3' width='5%'>"._DELETE."</td>";
	echo "</tr>";
	
	foreach($xitems as $xitem){
		$edit_link = "<a href=\"index.php?op=mod&xconv_id=" . $xitem->getVar('xconv_id') . "\"><img src=\"../images/edit.gif\"></a>";
		$del_link = "<a href=\"index.php?op=del&xconv_id=" . $xitem->getVar('xconv_id') . "\"><img src=\"../images/delete.gif\"></a>";
		
		echo "<tr class='odd' align='left'>";
		echo "<td>" . $xitem->getVar('charset') . "</td>";
		echo "<td>" . $xitem->getVar('alias') . "</td>";
		echo "<td>" . $xitem->getVar('inc') . "</td>";
		echo "<td align='center' >".$edit_link."</td>";
		echo "<td align='center' >".$del_link."</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</fieldset>";
	break;
	
	
	case "mod":
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" .  _MD_A_XCONV_EDITXITEM  . "</legend>";
	echo"<br />";		
	editXitem($xconv_id);	
	echo "</fieldset>";
	break;
	
	case "del":
	if (isset($_POST['confirm']) != 1){
		xoops_confirm( array( 'op' => 'del', 'xconv_id' => intval( $_GET['xconv_id'] ), 'confirm' => 1 ), 'index.php', _MD_A_XCONV_CONFIRMDEL );
        break;
	}
	else{
		$xitem =& $xitem_handler->create(false);
		$xitem->setVar('xconv_id', $_POST['xconv_id']);
		$xitem_handler->delete($xitem);
		redirect_header("index.php", 2, _MD_A_XCONV_XITEMDELETED);
		exit();
	}
	exit();
	
	
	case "save":
	
	if ($xconv_id){
		$xitem =& $xitem_handler->get($xconv_id);
		$message=_MD_A_XCONV_XITEMUPDATED;
	}else{
		$xitem =& $xitem_handler->create();
		$message=_MD_A_XCONV_XITEMCREATED;
	}
	
	$xitem->setVar('charset', $_POST['charset']);
	$xitem->setVar('alias', $_POST['alias']);
	$xitem->setVar('inc', $_POST['inc']);
	
	if ( !$xitem_handler->insert($xitem)) {
        $message = _MD_A_XCONV_DATABASEERROR;
	}
	redirect_header("index.php", 2, $message);
    exit();	
	
	default:
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _MD_A_XCONV_CREATENEWXITEM  . "</legend>";
	echo"<br />";
	echo "<a style='border: 1px solid #5E5D63; color: #000000; font-family: verdana, tahoma, arial, helvetica, sans-serif; font-size: 1em; padding: 4px 8px; text-align:center;' href='index.php?op=manage'>"._MD_A_XCONV_XITEMADMIN."</a><br /><br />";
	
	newXitem();
	
	echo "</fieldset>";
	
}
xoops_cp_footer();
?>