<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript"> 
function ValidarCarlos()
{
if (document.FORMA.Nota.value=='')
{
alert('la justificacion no puede estar vacia, por favor diligencie el campo correspondiente');
}
else
{
GuardarDatos();
}
}
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener2').style.position='absolute';
		parent.document.getElementById('FrameOpener2').style.top='1px';
		parent.document.getElementById('FrameOpener2').style.left='1px';
		parent.document.getElementById('FrameOpener2').style.width='1';
		parent.document.getElementById('FrameOpener2').style.height='1';
		parent.document.getElementById('FrameOpener2').style.display='none';
	}	
	function Quitar()
	{
		parent.document.getElementById('Dia[<? echo $Dia?>]').checked = false;		
		
	<? for ($i=1;$i<=7;$i++){?>    	
			parent.document.getElementById('Dia[<? echo $i?>]').disabled = false;			 		
<?		}?>  
		parent.document.FORMA.submit();
		CerrarThis();
	}
	function GuardarDatos(){
	<?	for ($i=1;$i<=7;$i++){?>
			parent.document.getElementById('Dia[<? echo $i?>]').disabled = false;	
	<?	}?>
//	alert(parent.document.getElementById('Hora[<?echo $Dia?>]').value);
		parent.document.getElementById('Hora[<? echo $Dia?>]').value=document.FORMA.Hora.value;
		parent.document.getElementById('Nota[<? echo $Dia?>]').value=document.FORMA.Nota.value;
		parent.document.FORMA.submit();
		CerrarThis();			
	}
</script>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="Guardar">
	<table width="100%" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
    	<tr><td bgcolor="#e5e5e5" style="font-weight:bold"align="center">Hora</td>
        </tr>
        <tr> <td width="50%" bgcolor="#e5e5e5"><textarea name="Hora" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></textarea></td>           
        </tr>
        <tr><td colspan="2" bgcolor="#e5e5e5" align="center">Justificacion</td>
        </tr>
        <tr>
        	<td colspan="2">
            	<textarea name="Nota" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></textarea>
            </td>
        </tr>
    </table>
<img src="/Imgs/b_drop.png" title="cancelar" onClick="Quitar()" style="cursor:hand" />
<img src="/Imgs/b_check.png" title="Guardar" onClick="javascript:ValidarCarlos()" style="cursor:hand"/>
<input type="hidden" name="Dia" value="<? echo $Dia?>">
<input type="hidden" name="Ced" value="<? echo $Ced?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>