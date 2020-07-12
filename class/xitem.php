<?php
// $Id: xitem.php,v 1.1.1.2 2004/11/08 03:39:45 phppp Exp $
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

class Xitem extends XoopsObject 
{
    var $db;
	var $table;
			
	function Xitem() {
	    $this -> db = & Database :: getInstance();
		$this -> table = $this -> db -> prefix( "xconv" );
	    $this->initVar('xconv_id', XOBJ_DTYPE_INT);
	    $this->initVar('charset', XOBJ_DTYPE_TXTBOX);
	    $this->initVar('alias', XOBJ_DTYPE_TXTBOX);
	    $this->initVar('inc', XOBJ_DTYPE_TXTBOX);
	}
	
    function prepareVars()
    {
        foreach ($this->vars as $k => $v) {	        
				$cleanv = $this->cleanVars[$k];
                switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                case XOBJ_DTYPE_SOURCE:
                case XOBJ_DTYPE_EMAIL:
                    $cleanv = ($v['changed'])?$cleanv:'';
                    if (!isset($v['not_gpc']) || !$v['not_gpc']) {
                        $cleanv =$this->db->quoteString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = ($v['changed'])?intval($cleanv):0;
	                break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = ($v['changed'])?$cleanv:serialize(array());
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = ($v['changed'])?$cleanv:0;
                    break;
                    
                default:
                    break;
                }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        return true;
    }
}

class XconvXitemHandler extends XoopsObjectHandler 
{
    
    function &create($isNew = true)
    {
        $xitem = new Xitem();
        if ($isNew) {
            $xitem->setNew();
        }
        return $xitem;
    }
    
    function &get($id = 0) {
        $xitem =& $this->create(false);
        if ($id > 0) {
            $sql = "SELECT * FROM ".$this->db->prefix( "xconv" )." WHERE xconv_id = ".intval($id);
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            while ($row = $this->db->fetchArray($result)) {
                $xitem->assignVars($row);
            }
        }
        return $xitem;
    }
    
    function getAllXitems() {
        $ret = array();
        $sql = "SELECT * FROM ".$this->db->prefix("xconv");
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        while ($row = $this->db->fetchArray($result)) {
            $xitem =& $this->create(false);
            $xitem->assignVars($row);
            $ret[] = $xitem;
            unset($xitem);
        }
        return $ret;
    }
    
    function insert(&$xitem) {
	    
        if (!$xitem->isDirty())  return true;
        if (!$xitem->cleanVars())return false;
        $xitem->prepareVars();
        foreach ($xitem->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        
        if ( $xitem->isNew() )
        {
            $xconv_id = $this->db->genId($xitem->table."_xconv_id_seq");
            $sql = "INSERT INTO ".$xitem->table." 
            			( xconv_id,  charset,  alias,  inc)
					VALUES 
                    	($xconv_id, $charset, $alias, $inc)";
                    	
            if ( !$result = $this->db->query($sql) ) {
                echo "<br />Insert xitem error:".$sql;
                return false;
            }
            if ( $xconv_id == 0 ) $xconv_id = $this->db->getInsertId();
            
      		$xitem->setVar('xconv_id',$xconv_id);            
        }else{
            $sql = "UPDATE ".$xitem->table." SET charset = $charset, alias= $alias, inc= $inc WHERE xconv_id = ". $xitem->getVar('xconv_id');
            $result = $this->db->query($sql);
            if ( !$result = $this->db->query($sql) ) {
            	echo "<br />update xitem error:".$sql;
                return false;
            }
        }
        return $xitem->getVar('xconv_id');
    }
    
    function delete(&$xitem) 
    {
        $sql = "DELETE FROM ".$xitem->table." WHERE xconv_id= ". $xitem->getVar('xconv_id');
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }
}
?>