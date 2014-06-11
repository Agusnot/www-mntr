<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons="delete from contratacionsalud.restriccionescobro where compania='$Compania[0]' and entidad='$Entidad' and Contrato='$Contrato' and nocontrato='$Numero'";
		$res=ExQuery($cons);
		while(list($cad,$val) = each($Incluir))
		{
			if($Cobrar[$cad]=='on'){$Cobrar[$cad]="Si";}else{$Cobrar[$cad]="No";}
			if($Mostrar[$cad]=='on'){$Mostrar[$cad]="Si";}else{$Mostrar[$cad]="No";}
			if(!$MontoFijo[$cad]){$MontoFijo[$cad]="0";}
			$cons="insert into contratacionsalud.restriccionescobro (compania,entidad,contrato,nocontrato,grupo,cobrar,mostrar,montofijo) values
			('$Compania[0]','$Entidad','$Contrato','$Numero','$cad','".$Cobrar[$cad]."','".$Mostrar[$cad]."',".$MontoFijo[$cad].")";
			$res=ExQuery($cons);
			//echo $cons."<br>";
			$cons="update ContratacionSalud.Contratos set restriccioncobro=1 where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'
			and numero='$Numero'";
			$res=ExQuery($cons);
		}
	}	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		
	}
	function Incluye(Dato)
	{	
		if(document.getElementById("Incluir_"+Dato).checked==true)	
		{
			document.getElementById("Cobrar_"+Dato).disabled=false;			
			document.getElementById("Mostrar_"+Dato).disabled=false;
			document.getElementById("MontoFijo_"+Dato).disabled=false;
		}
		else
		{			
			document.getElementById("Cobrar_"+Dato).disabled=true;
			document.getElementById("Cobrar_"+Dato).checked=false;
			document.getElementById("Mostrar_"+Dato).disabled=true;
			document.getElementById("Mostrar_"+Dato).checked=false;
			document.getElementById("MontoFijo_"+Dato).disabled=true;
			document.getElementById("MontoFijo_"+Dato).value="0";
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>  
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td>Grupo</td><td>Incluir</td><td>Cobrar</td><td>Mostrar</td><td>Monto Fijo</td>            
		</tr>
        
	<?	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by grupo";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$cons2="select grupo,cobrar,mostrar,montofijo from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$Entidad' and Contrato='$Contrato' and nocontrato='$Numero' and grupo='$fila[0]'";			
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">	
            	<td><? echo $fila[1];?> </td>
                <td><input type="checkbox" name="Incluir[<? echo $fila[0]?>]" id="Incluir_<? echo $fila[0]?>" onClick="Incluye('<? echo $fila[0]?>');"
                	<? if($fila2[0]){?> checked<? }?>>
                </td>
                <td>
                	<input type="checkbox" name="Cobrar[<? echo $fila[0]?>]" id="Cobrar_<? echo $fila[0]?>" <? if(!$fila2[0]){?> disabled<? }?>
                    <? if($fila2[1]=="Si"){?> checked<? }?>>
                </td>                
                <td>
                	<input type="checkbox" name="Mostrar[<? echo $fila[0]?>]" id="Mostrar_<? echo $fila[0]?>" <? if(!$fila2[0]){?> disabled<? }?>
                    <? if($fila2[2]=="Si"){?> checked<? }?>>
              	</td>
                <td>
                	<input type="text" name="MontoFijo[<? echo $fila[0]?>]" id="MontoFijo_<? echo $fila[0]?>" <? if(!$fila2[0]){?> disabled<? }?>
                    onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this);" style="width:90; text-align:right"
                    value="<? echo $fila2[3]?>">
                </td>
            </tr>
	<?		$fila2=NULL;
		}?>       
    	<tr align="center">
        	<td colspan="7">
            	<input type="submit" name="Guardar" value="Guardar">
            	<input type="button" value="Regresar" 
		        onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&VieneRestric=1&Edit=1'">
            </td>
        </tr> 
  	</table>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>">
<input type="hidden" name="Contrato" value="<? echo $Contrato?>">
<input type="hidden" name="Numero" value="<? echo $Numero?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>            
</body>
</html>
