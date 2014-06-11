<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	//echo "ambito=$Ambito und=$UnidadHosp";   	
	
	if($delest){
			$consD="delete from salud.confestancia where confestancia.compania='$Compania[0]' 
			and confestancia.compania='$Compania[0]' and confestancia.entidad='$Entidad' and confestancia.contrato='$Contrato'
			and pabellon='$pabellon' and confestancia.nocontrato='$Numero' and confestancia.cup='$cup' and confestancia.ambito='$ambt'";
			$resD=ExQuery($consD);
		    }
	
	if($Guardar){
		if($Edit==1){
			$cons="delete from salud.confestancia where confestancia.compania='$Compania[0]'  and entidad='$Entidad' 
			and contrato='$Contrato' and nocontrato='$Numero' and ambito='$Ambito' and pabellon='$UnidadHosp'";
			$res=ExQuery($cons); echo ExError();
		}	
		$cons="insert into salud.confestancia (compania,fecha,usucrea,entidad,contrato,nocontrato,ambito,pabellon,cup) values
		('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$Entidad','$Contrato','$Numero','$Ambito','$UnidadHosp','$Codigo')";		
		$res=ExQuery($cons); echo ExError();
		?>
        	<script language="javascript">///location.href="NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>";</script>
		<?
	}
	if($Ambito&&$UnidadHosp){
		$cons="select cup,nombre from salud.confestancia,contratacionsalud.cups
		where confestancia.compania='$Compania[0]' and cups.compania='$Compania[0]' and cup=codigo  and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Numero' 
		and ambito='$Ambito' and pabellon='$UnidadHosp'";
		$res=ExQuery($cons); echo ExError();
		if(ExNumRows($res)>0){
			$fila=ExFetch($res);
			$Codigo=$fila[0];			
			$Nombre=$fila[1];
			$Edit=1;
		}
		else{
			$Codigo="";			
			$Nombre="";
			$Edit=0;			
		}
	}
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function stance(d,c,p,a){
	document.FORMA.delest.value=d;
	document.FORMA.cup.value=c;
	document.FORMA.pabellon.value=p;
	document.FORMA.ambt.value=a;
	document.FORMA.submit();
	} 
	
	function BuscarCUP(T)	
	{		
		frames.FrameOpener.location.href="BusqCUPEstancia.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+document.FORMA.Codigo.value+"&Nombre="+document.FORMA.Nombre.value+"&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=130;
		document.getElementById('FrameOpener').style.left=150;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='750px';
		document.getElementById('FrameOpener').style.height='350px';
	}
	function Validar()
	{
		if(document.FORMA.Ambito.value==""){ alert("Debe seleccionar el ambito!!!");}
		else{
			if(document.FORMA.UnidadHosp.value==""){ alert("Debe seleccionar la unidad de hospitalizacion!!!");}
			else{
				if(document.FORMA.Codigo.value==""){ alert("Debe ingresar un codigo de procedimiento!!!");}
				else{
					document.FORMA.Guardar.value=1;
					document.FORMA.submit();
				}
			}
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <td colspan="3"> 
    <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	
			$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   		</select></td>
   	</tr>
    <tr>
   	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Unidad</td>   
<? 
		
   		$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
		$result=ExQuery($consult);?>        	           
		<td colspan="3"><select name="UnidadHosp" onChange="document.FORMA.submit()"><option></option>      			
	<?	if(ExNumRows($result)>0){
			while($row = ExFetchArray($result)){									
				if($row[0]==$UnidadHosp){
					echo "<option value='$row[0]' selected>$row[0]</option>";
				}
				else{
					echo "<option value='$row[0]'>$row[0]</option>";
				}
			}
		}?>	</select></td>	
 	</tr>
    <tr align="center">
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td>
        <td><input type="text" name="Codigo" readonly onFocus="BuscarCUP()" style="width:90px" value="<? echo $Codigo?>"></td>    
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" >Nombre</td>
        <td><input type="text" name="Nombre" readonly onFocus="BuscarCUP()" style="width:530px"  value="<? echo $Nombre?>">
      	</td>
    </tr>
    <tr>
    	<td colspan="8" align="center">
        	<input type="button" value="Guardar" onClick="Validar()">       
        	<input type="button" value="Regresar" onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Edit=1'">
        </td>
    </tr>
</table>
<?
$cons="select ambito,pabellon,cups.nombre,confestancia.cup,confestancia.pabellon,confestancia.ambito from salud.confestancia,contratacionsalud.cups where confestancia.compania='$Compania[0]' 
and cups.compania='$Compania[0]' and confestancia.entidad='$Entidad' and confestancia.contrato='$Contrato'
and confestancia.nocontrato='$Numero' and confestancia.cup=cups.codigo order by ambito,pabellon";
//echo $cons;
$res=ExQuery($cons);
if(ExNumRows($res)>0){?>
<br>
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tR  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Proceso</td><td>Servicio</td><td>CUP</td><td></td> 
    </tR>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td>
			<img style="cursor:hand"  title="Eliminar" 
    	                	onClick="if(confirm('Desea anular este registro?')){stance('true','<?echo $fila[3]?>','<?echo $fila[4]?>','<?echo $fila[5]?>');}" 
    						src="/Imgs/b_drop.png">
			</td>
        </tr>
<?	
}    ?>
</table>
<?
}?>

<input type="hidden" name="Entidad" value="<? echo $Entidad?>"> 
<input type="hidden" name="Contrato" value="<? echo $Contrato?>"> 
<input type="hidden" name="Numero" value="<? echo $Numero?>"> 
<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
<input type="hidden" name="Regresa" value="">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="delest" value="<? echo $delest?>">
<input type="hidden" name="cup" value="<? echo $cup?>">
<input type="hidden" name="pabellon" value="<? echo $pabellon?>">
<input type="hidden" name="ambt" value="<? echo $ambt?>">
<input type="hidden" name="Guardar" >
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
