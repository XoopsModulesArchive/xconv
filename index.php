<?php
    include "../../mainfile.php";
	ob_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>2004</title>
</head>

<body>
        來了第三次
        <b>类别:</b>
      <hr>
        作者: <a href="http://dev.xoops.org.cn/userinfo.php?uid=6">sabrina</a> 
        (10:17 pm)
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td>
        看了 course reviews <br>
        還不會用,<br>
        因為太多選項要填,<br>
        還有一堆star?????<br>
        <br>
        再研究再研究,<br>
        不過,這倒是我所需要的
      </td>
    </tr>
  </table>
</body>

</html>
<?php
    $out = ob_get_contents();            
    ob_end_clean();
	$module_handler =& xoops_gethandler('module');
	$xconv =& $module_handler->getByDirname('xconv');
	$xconv_handler =& xoops_getmodulehandler('xconv', 'xconv');
	//echo $out;
	$out = $xconv_handler->iconv('gb2312', 'big5', $out, true);
	//echo "<br><hr>converted:<br>".$out;
	echo "<br><hr><hr>converted:<br>".$out;
?>