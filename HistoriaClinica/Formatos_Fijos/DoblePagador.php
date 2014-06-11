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
		$consP="select fechaini,fechafin from salud.pagadorxservicios 
		where compania='$Compania[0]' and entidad='$EPS' and contrato='$Contra' and nocontrato='$NoContra' and numservicio=$NumServ and tipo=1
		order by fechaini desc";
		$resP=ExQuery($consP);
		$filaP=ExFetch($resP);
		if($filaP[0])
		{
			if($filaP[1]){$FF1=",fechafin";$FF2=",'$filaP[1]'";}
			$cons="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,contrato,nocontrato,tipo $FF1) values
			('$Compania[0]','$TMPC','$Paciente[1]','$Entidad','$filaP[0]','$Contrato','$Nocontrato',2 $FF2)";
			//echo $cons;
			$res=ExQuery($cons);		
		}
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
<input type="hidden" name="EPS" value="<? echo $EPS?>">
<input type="hidden" name="Contra" value="<? echo $Contra?>">
<input type="hidden" name="NoContra" value="<? echo $NoContra?>">
<input type="hidden" name="TMPC" value="<? echo $TMPC?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>    	
    <?	//if($Entidad==''&&!$Ban){ $Entidad=$EPS;	$Contrato=$Contra;	$Nocontrato=$NoContra; $Ban=1;}?>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>                
    		<td colspan="3">
       	<?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros 
			where Tipo='Asegurador' and Compania='$Compania[0]' and identificacion!='$EPS' order by primape";
			$res=ExQuery($cons);
			//echo $cons;?>
            <select name="Entidad" onChange="document.FORMA.submit();"><option></option>
			<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Entidad){
						echo "<option selected value='$fila[0]'>$fila[1]</option>";
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
    	<td><select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato"; 
			$res=ExQuery($cons);
			$banContrato=0;
			while($fila=ExFetch($res))
			{				
				if($Contrato==$fila[0]){
						echo "<option selected value='$fila[0]'>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
			}		?>        
	        </select></td>     
      	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>   
    <? 	if($Entidad!=$AuxAntAseg){$Contrato='';}
			if($Contrato==''){
				$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato"; 
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Contrato=$fila[0];
			}?>
			<td><select name="Nocontrato"><option></option>
    	 <?	$cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' and Contrato='$Contrato'"; 
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
   	</tr>
    <tr align="center">
    	<td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"></td>
    </tr>
</table>
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="Ban" value="<? echo $Ban?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
