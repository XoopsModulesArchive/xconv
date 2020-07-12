<?php
// $Id: chinese.php,v 1.1.1.2 2004/11/08 03:39:45 phppp Exp $
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
define("XCONV_CHINESE_TABLES_DIR", XOOPS_ROOT_PATH."/modules/xconv/include/chinese_tables");

/**
 * Chinese encoding lib
 * based on::
 * @Hessian (solarischan@21cn.com)
 * @Wang Jue (wjue@wjue.org)
 */

class XconvChineseHandler  extends XoopsObjectHandler
{
	function _hex2bin( $hexdata )
	{
		$bindata = '';
		for ( $i=0; $i<strlen($hexdata); $i+=2 )
			$bindata.=chr(hexdec(substr($hexdata,$i,2)));

		return $bindata;
	}

	function CHSUtoUTF8($c)
	{
		$str="";

		if ($c < 0x80) {
			$str.=$c;
		}

		else if ($c < 0x800) {
			$str.=(0xC0 | $c>>6);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x10000) {
			$str.=(0xE0 | $c>>12);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x200000) {
			$str.=(0xF0 | $c>>18);
			$str.=(0x80 | $c>>12 & 0x3F);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		return $str;
	}
	
	function utf8_gb($text){
		$tmp = file(XCONV_CHINESE_TABLES_DIR.'/gb-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,7,6))]=substr($value,0,6);

		$out = "";
		$len = strlen($text);
		$i = 0;
		while($i < $len) {
			$c = ord( substr( $text, $i++, 1 ) );
			switch($c >> 4)
			{ 
				case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
					// 0xxxxxxx
					$out .= substr( $text, $i-1, 1 );
				break;
				case 12: case 13:
					// 110x xxxx   10xx xxxx
					$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
					$char3 = $table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];
					$out .= $this->_hex2bin( dechex(  $char3 + 0x8080 ) );

				break;
				case 14:
					// 1110 xxxx  10xx xxxx  10xx xxxx
					$char2 = ord( substr( $text, $i++, 1 ) );
					$char3 = ord( substr( $text, $i++, 1 ) );
					$char4 = $table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];
					$out .= $this->_hex2bin( dechex ( $char4 + 0x8080 ) );
				break;
			}
		}
		unset($table);
		return $out;
	}
	
	function utf8_big5($text){
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/big5-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,7,6))]=substr($value,0,6);

		$out = "";
		$len = strlen($text);
		$i = 0;
		while($i < $len) {
			$c = ord( substr( $text, $i++, 1 ) );
			switch($c >> 4)
			{ 
				case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
					// 0xxxxxxx
					$out .= substr( $text, $i-1, 1 );
				break;
				case 12: case 13:
					// 110x xxxx   10xx xxxx
					$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
					$char3 = $table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];
					$out .= $this->_hex2bin( $char3 );

				break;
				case 14:
					// 1110 xxxx  10xx xxxx  10xx xxxx
					$char2 = ord( substr( $text, $i++, 1 ) );
					$char3 = ord( substr( $text, $i++, 1 ) );
					$char4 = $table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];
					$out .= $this->_hex2bin( $char4 );
				break;
			}
		}
		unset($table);
		return $out;
	}
	
	function gb_utf8($text){
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/gb-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,0,6))]=substr($value,7,6);

		$ret="";
		while(strlen($text) > 0){
			if(ord(substr($text,0,1))>127){
				$utf8=$this->CHSUtoUTF8(hexdec($table[hexdec(bin2hex(substr($text,0,2)))-0x8080]));
				for($i=0;$i<strlen($utf8);$i+=3) $ret.=chr(substr($utf8,$i,3));
				$text=substr($text,2,strlen($text));
			}else{
				$ret.=substr($text,0,1);
				$text=substr($text,1,strlen($text));
			}
		}
		unset($table);
		return $ret;
	}
	
	function big5_utf8($text){
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/big5-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,0,6))]=substr($value,7,6);

		$ret="";
		while(strlen($text) > 0){
			if(ord(substr($text,0,1))>127){
						$utf8=$this->CHSUtoUTF8(hexdec($table[hexdec(bin2hex(substr($text,0,2)))]));
				for($i=0;$i<strlen($utf8);$i+=3) $ret.=chr(substr($utf8,$i,3));
				$text=substr($text,2,strlen($text));
			}else{
				$ret.=substr($text,0,1);
				$text=substr($text,1,strlen($text));
			}
		}
		unset($table);
		return $ret;
	}
	
	function gb_unicode($text)
	{
		$utf="";
		$utf .= $this->_hex2bin( feff );
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/gb-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,0,6))]=substr($value,7,6);

		while(strlen($text) > 0){
			if (ord(substr($text,0,1))>127){
				$temp = hexdec( $table[hexdec( bin2hex( substr( $text,0,2 ) ) )- 0x8080] );
				$utf .= $this->_hex2bin( dechex( $temp ) );
				$text=substr($text,2,strlen($text));
			}else{
				$utf.=substr($text,0,1);
				$text=substr($text,1,strlen($text));
			}
		}
		return $utf;
	} 
	
	function big5_unicode($text)
	{
		$utf="";
		$utf .= $this->_hex2bin( feff );
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/big5-unicode.table');
		$table = array();
		while(list($key,$value)=each($tmp))
			$table[hexdec(substr($value,0,6))]=substr($value,7,6);

		while(strlen($text) > 0){
			if (ord(substr($text,0,1))>127){
				$utf.="&#x".$table[hexdec(bin2hex(substr($text,0,2)))].";";
				$text=substr($text,2,strlen($text));
			}else{
				$utf.=substr($text,0,1);
				$text=substr($text,1,strlen($text));
			}
		}
		return $utf;
	} 

    
    function _conv($str,$fd) {
        
        $c=ord(substr($str,0,1));
        $x=ord(substr($str,1,1));
        $address=(($c-160)*510)+($x-1)*2;
        fseek($fd, $address);
        $hi=fgetc($fd);
        $lo=fgetc($fd);
        
        return "$hi$lo";
    }
    
    function big5_gb($str) 
    {
        $fd = fopen (XCONV_CHINESE_TABLES_DIR.'/big5-gb.table', "r");
        $outstr="";
        for($i=0;$i<strlen($str);$i++) {
            $ch=ord(substr($str,$i,1));
            if($ch > 127) {
                $outstr.=$this->_conv(substr($str,$i,2),$fd);
                $i++;
            } else {
                $outstr.=substr($str,$i,1);
            }
        }
        
        fclose ($fd);
        return $outstr;
    }
    
    function gb_big5($str) 
    {
        $fd = fopen (XCONV_CHINESE_TABLES_DIR.'/gb-big5.table', "r");
        $outstr="";
        for($i=0;$i<strlen($str);$i++) {
            $ch=ord(substr($str,$i,1));
            if($ch > 127) {
                $outstr.=$this->_conv(substr($str,$i,2),$fd);
                $i++;
            } else {
                $outstr.=substr($str,$i,1);
            }
        }
        fclose ($fd);
        return $outstr;
    }      

	function PinYinSearch($table, $num)
	{
		if($num>0&&$num<160) return chr($num);
		if($num<-20319||$num>-10247) return "";
		$count = count($table);
		for($i=$count-1;$i>=0;$i--){
			if($table[$i][1]<=$num)	break;
		}
		return $table[$i][0];
	}
	
	function gb_pinyin($text){
		$tmp = @file(XCONV_CHINESE_TABLES_DIR.'/gb-pinyin.table');
		for ($i=0; $i<count($tmp); $i++) {
			$tmp1 = explode("	", $tmp[$i]);
			$table[$i]=array($tmp1[0],$tmp1[1]);
		}

		$ret = array();
		$ri = 0;
		for($i=0;$i<strlen($text);$i++){
			$p=ord(substr($text,$i,1));
			if($p>160){
				$q=ord(substr($text,++$i,1));
				$p=$p*256+$q-65536;
			}
			$ret[$ri]=$this->PinYinSearch($table,$p);
			$ri = $ri + 1;
		}
		unset($table);

		return implode(" ", $ret);
	} 
} 
?>
