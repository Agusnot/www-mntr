<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){		
		if($Interprogramas=="on"){$Interprogramas="1";}else{$Interprogramas="0";}
		if(!$Editar){			
			$cons="insert into salud.actvasistenciales (compania,nomactvidad,especialidad,formato,id_item,msjhc,cup,usucrea,fechacrea,interprog) values
			('$Compania[0]','$NomAct','$Especialidad','$Formato',$Item,'$MsjHC','$CUP','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$Interprogramas)";
		}
		else{
			$cons="update salud.actvasistenciales set nomactvidad='$NomAct',especialidad='$Especialidad',formato='$Formato',id_item=$Item,msjhc='$MsjHC',cup='$CUP'
			,usumod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',interprog=$Interprogramas 
			where compania='$Compania[0]' and nomactvidad='$NomActAnt' and especialidad='$EspecialidadAnt' and formato='$FormatoAnt' and id_item=$ItemAnt";
		}
		//echo $cons;

		$res=ExQuery($cons);
		?>
		<script language="javascript">
            location.href='ActAsistenciales.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
?>	

<html>
<head>
<script language='javascript' src="/Funciones.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function SelectCup(e) { 
		x = e.clientX; 
		y = e.clientY; 	
		frames.FrameOpener.location.href="AsigCup.php?DatNameSID=<? echo $DatNameSID?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10%';
		document.getElementById('FrameOpener').style.left='10%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='400px';
	} 
	function Validar()
	{
		if(document.FORMA.NomAct.value==""){alert("Debe digitar el nombre de la actividad!!!");return false;}
		if(document.FORMA.Especialidad.value==""){alert("Debe seleccionar la especialidad!!!");return false;}
		if(document.FORMA.Formato.value==""){alert("Debe seleccionar el formato!!!");return false;}
		if(document.FORMA.Item.value==""){alert("Debe seleccionar el item!!!");return false;}
		if(document.FORMA.MsjHC.value==""){alert("Debe digitar el mensaje a colocar en las historia clinica!!!");return false;}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<?
if($Editar){ ?>
	<input type="hidden" name="EspecialidadAnt" value="<? echo $EspecialidadAnt?>">
	<input type="hidden" name="FormatoAnt" value="<? echo $FormatoAnt?>">
	<input type="hidden" name="ItemAnt" value="<? echo $ItemAnt?>">
	<input type="hidden" name="NomActAnt" value="<? echo $NomActAnt?>">
<?	if(!$Especialidad){$Especialidad="$EspecialidadAnt";}
	if(!$Formato){$Formato="$FormatoAnt";}
	if(!$Item){$Item="$ItemAnt";}
	if(!$NomAct){$NomAct="$NomActAnt";}
}
?>
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Nombre Actividad</td>
        <td><input type="text" name="NomAct" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:430" value="<? echo $NomAct?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Especialidad</td>
  	<?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Especialidad" onChange="document.FORMA.submit()">
            	<option></option>
       		<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Especialidad){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Formato</td>
     <?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$Especialidad' and estado='AC' order by formato";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Formato" onChange="document.FORMA.submit()"><option></option>
       		<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
       	</td>
    </tr>    
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Item</td>
  	<?	$cons="select id_item,item from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$Especialidad' and estado='AC'";
		$res=ExQuery($cons);
		?>
        <td>
        	<select name="Item">            	
       		<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Item){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}?>
            </select>
       	</td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Mensaje en Historia Clinica</td>
        <td><textarea name="MsjHC"  onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" cols="50" rows="3"><? echo $MsjHC?></textarea>        
        </td>
    </tr>   
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">CUP</td>
        <td><input type="text" name="CodCUP" readonly style="width:430" value="<? echo $CodCUP?>" 
        onClick="SelectCup(event)" onFocus="SelectCup(event)"></td>
        <input type="hidden" name="CUP" value="<? echo $CUP?>">
    </tr>
     <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Interprogamas</td>
        <td><input type="checkbox" name="Interprogramas" <? if($InterProg==1){?> checked<? }?>></td>
    </tr>
     <tr>
    	<td colspan="2" align="center">
        <input type="submit" value="Guardar" name="Guardar">
        <input type="button" value="Cancelar" onClick="location.href='ActAsistenciales.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Editar" value="<? echo $Editar?>">
</form>    
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
