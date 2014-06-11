<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?> 
<script language="JavaScript">
lck=0;
function r(hval)
{
   if ( lck == 0 )
   {
     document.formelia.color.value=hval;
   }
}

function l()
{
   if (lck == 0)
   {
     lck = 1;
   } else {
     lck = 0;
   }
}
</script> 
<form id="formelia" name="formelia" method="post" action="editoregistro.PHP">
   <table border="0" height="18">
      <tr>
          <td> <input type="text" size="8" maxlength=7 class="textbox" name="color" value="" readonly>   </td>
          <td height="18" bgcolor="#A8A9AC"><a href="JavaScript:l()" onmouseover="r('#A8A9AC'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
          <td height="18" bgcolor="#BDBEC0"><a href="JavaScript:l()" onMouseOver="r('#BDBEC0'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
           <td height="18" bgcolor="#D3DCE3"><a href="JavaScript:l()" onMouseOver="r('#D3DCE3'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
           <td height="18" bgcolor="#FFFFFF"><a href="JavaScript:l()" onMouseOver="r('#FFFFFF'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
           <td height="18" bgcolor="#FF0000"><a href="JavaScript:l()" onMouseOver="r('#FF0000'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
           <td height="18" bgcolor="#0000FF"><a href="JavaScript:l()" onMouseOver="r('#0000FF'); return true"><img src="images/col.png" height=18 width=10 border=0></a></td>
     </tr>
   </table>
</form>