<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Validar()
	{		
		if(document.FORMA.Entidad.value==""){
			alert("Debe seleccionar una Entidad!!!");return false;
		}
		if(document.FORMA.Contrato.value==""){
			alert("Debe haber un Contrato!!!");return false;
		}
		if(document.FORMA.Nocontrato.value==""){
			alert("Debe haber un numero de Contrato!!!");return false;
		}
	}
</script>
<?
	if($Guardar)
	{
		$cons="update salud.tmppagadorxfactura set entidad='$Entidad',contrato='$Contrato',nocontrato='$Nocontrato'
		where compania='$Compania[0]' and cedula='$Paciente[1]' and entidad='$EPSAnt' and contrato='$ContraAnt' and nocontrato='$NoContraAnt' and fechaini='$Inicio' and tmpcod='$TMPC'";
		//echo $cons;
		$res=ExQuery($cons);		
	?>	<script language="javascript">						
			//parent.document.FORMA.NoEnvia.value=1;			
			parent.document.FORMA.submit();	
			CerrarThis();
     	</script><?
	}
?>
</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Inicio" value="<? echo $Inicio?>">
<input type="hidden" name="EPSAnt" value="<? echo $EPS?>">
<input type="hidden" name="ContraAnt" value="<? echo $Contra?>">
<input type="hidden" name="NoContraAnt" value="<? echo $NoContra?>">
<input type="hidden" name="TMPC" value="<? echo $TMPC?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>    	
    <?	if($Entidad==''&&!$Ban){ $Entidad=$EPS;	$Contrato=$Contra;	$Nocontrato=$NoContra; $Ban=1;}?>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>                
    		<td colspan="3"><select name="Entidad" onChange="document.FORMA.submit();">
		<? 		$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros 
				where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$Entidad){
						echo "<option selected value='$fila[0]'>$fila[1]</option>";
						$nf1=$fila[0];
					}
					else{
						echo "<option value='$fila[0]'>$fila[1]</option>";
					}
				}
			?> 	</select>
			</td>
		  	<input type="hidden" name="AuxAntAseg" value="<? echo $Entidad?>">
    </tr>
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>                
    	<td><select name="Contrato" onChange="document.FORMA.submit()">
         <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$nf1' Group By Contrato"; 
			$res=ExQuery($cons);
			$banContrato=0;
			while($fila=ExFetch($res))
			{				
				if($Contrato==$fila[0]){
						echo "<option selected value='$fila[0]'>$fila[0]</option>";
						$nf2=$fila[0];
						
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
			}		?>        
	        </select>
			</td>     
      	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>   
    <? 	if($Entidad!=$AuxAntAseg){$Contrato='';}
			if($Contrato==''){
				$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$nf1' Group By Contrato"; 
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Contrato=$fila[0];
			}?>
			<td><select name="Nocontrato">
    	 <?	$cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$nf1' and Contrato='$nf2'"; 
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($NoContrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}				?>
	        </select></td>
			<?php //echo"$cons"; ?>
   	</tr>
    <tr align="center">
    	<td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"></td>
    </tr>
</table>
<input type="hidden" name="Ban" value="<? echo $Ban?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
