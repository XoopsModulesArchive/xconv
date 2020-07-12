<?php
// $Id: xconv.php,v 1.2 2004/12/12 04:39:36 phppp Exp $
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
include_once XOOPS_ROOT_PATH."/modules/xconv/include/vars.php";

class Xconv
{
    var $in_charset  =  "iso-8859-1";
    var $out_charset  =  "iso-8859-1";
	var $alias = array();
	var $convFunc = "";

	function Xconv($in_charset, $out_charset, &$config)
	{
		$this->setInCharset($in_charset);
		$this->setOutCharset($out_charset);
		$this->setConfig($config);
		if(!($this->getConvFunc()))  $this = false;
	}

	function convert($text)
	{
		if(!is_array($text)) {
			$text = $this->customConv($text);
		}else{
			foreach($text as $key => $val)
			 $text[$key] = $this->convert($val);
		}
		return $text;
	}

	function setInCharset($in_charset)
	{
		$this->in_charset = strtolower($in_charset);
	}

	function setOutCharset($out_charset)
	{
		$this->out_charset = strtolower($out_charset);
	}

	function getConvFunc()
	{
		if(count($this->alias)==0) return false;
		$in_set = "";
		$out_set = "";
		foreach($this->alias as $charset=>$alias){
			if(in_array($this->in_charset,$alias)) $in_set = $charset;
			if(in_array($this->out_charset,$alias)) $out_set = $charset;
			if($in_set&&$out_set) {
				$required_convFunc =strtolower($in_set.'_'.$out_set);
				if(!function_exists($required_convFunc)) return false;
				$this->convFunc = $required_convFunc;
				return true;
			}
		}
		return false;
	}

	function customConv($text)
	{
		$convFunc = $this->convFunc;
		$converted_text = $convFunc($text);
		return $converted_text;
	}

	function setConfig(& $config)
	{
        $this->alias = & $config['alias'];
	}
}

class XconvXconvHandler extends XoopsObjectHandler
{
	var $xconv;
	var $config;

	function &get($out_charset, $in_charset)
	{
		static $xconvs = array();
		if(isset($xconvs[$in_charset][$out_charset])) return $xconvs[$in_charset][$out_charset];
		if(!isset($this->config)) if(!$load = $this->loadConfig()) return false;
		$xconvs[$in_charset][$out_charset] = New Xconv($in_charset, $out_charset, $this->config);
		return $xconvs[$in_charset][$out_charset];
	}

	function convert_encoding($text, $out_charset, $in_charset)
	{
		if(empty($text)) {
			//echo "<br />Nothing to Convert:$text";
			return false;
		}
		$xconv =& $this->get($out_charset, $in_charset);

		if(!is_object($xconv)) {
			//echo "<br />No conversion available";
			return false;
		}
		$converted_text = trim($xconv->convert($text));
		return $converted_text;
	}

	// deprecated
	function getXconv($in_charset, $out_charset)
	{
		if(!isset($this->config)) if(!$load = $this->loadConfig()) return false;
		$this->xconv = New Xconv($in_charset, $out_charset, $this->config);
		return (is_object($this->xconv))?true:false;
	}

	// deprecated
	function iconv($text, $isPage = false)
	{
		if(empty($text)||!is_object($this->xconv)) return $text;
		$converted_text = trim($this->xconv->convert($text));
		$converted_text = (empty($converted_text))? $text: $converted_text;
		return $converted_text;
	}

	function createConfig()
	{
		$file_config = XCONV_CONFIG_FILE;
		if(!$fp = fopen($file_config,'w')) {
			echo "<br /> the config file can not be created: ".$file_config;
			return false;
		}

		$file_content = "<?php";
		$file_content .= "\n	global \$".XCONV_CONFIG_VAR.";";
        $db = &Database::getInstance();
		$sql = "SELECT * FROM ". $db->prefix('xconv');
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
			$file_content .= "\n	\$".XCONV_CONFIG_VAR."['".$myrow['charset']."'] = array(";
			$file_content .= "\n		\"alias\"=>\"".$myrow['alias']."\",";
			$file_content .= "\n		\"inc\"=>\"".$myrow['inc']."\"";
			$file_content .= "\n	);";
        }

		$file_content .= "\n?>";
		fputs($fp,$file_content);
		fclose($fp);
		return true;
	}

	function loadConfig()
	{
		$file_config = XCONV_CONFIG_FILE;
		if(!file_exists($file_config)||filesize($file_config)==0) $this->createConfig();
		if(!is_readable($file_config)) {
			return false;
		}else{
			include($file_config);
	        foreach(${XCONV_CONFIG_VAR} as $charset => $config){
	            $alias = explode(',',$config['alias']);
	            for($i=0;$i<count($alias);$i++) $alias[$i] = trim($alias[$i]);

	            $this->config['alias'][$charset] = $alias;
				if(!empty($config['inc'])) include_once XCONV_LOCAL_FUNCTIONS_DIR."/".$config['inc'];
	        }
			return true;
		}
	}
}
?>