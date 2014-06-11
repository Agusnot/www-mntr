<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >  
<?
if($Ver)
{
	if($Ambito){
		$Amb=" and tiposervicio='$Ambito'";
		$cons="select hospitalizacion,urgencias from salud.ambitos where compania='$Compania[0]' and ambito='$Ambito'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if($fila[1]==1||$fila[0]==1){$BanCreaLiq=1;}		
	}else{$Amb="";}
	if($CedPac){
		$CP="and servicios.cedula ilike '$CedPac%'";
	}else{$CP="";}
	if($Operador==">"){if($FecBusq){$FB="and fechaing>'$FecBusq 23:59:59'";}}
	if($Operador=="<"){if($FecBusq){$FB="and fechaing<'$FecBusq 00:00:00'";}}
	if($Operador=="="){if($FecBusq){$FB="and fechaing<='$FecBusq 23:59:59' and fechaing>='$FecBusq 00:00:00'";}}
	
	$cons="select servicios.numservicio,tiposervicio,servicios.cedula,primape,segape,primnom,segnom,fechaing,fechaegr,noliquidacion,pagador,contrato,nocontrato 
	from salud.servicios,central.terceros,salud.ambitos,facturacion.liquidacion
	where servicios.compania='$Compania[0]' and ambitos.ambito!='Sin Ambito' and terceros.compania='$Compania[0]' 
	and identificacion=servicios.cedula and ambitos.ambito=tiposervicio $FB and liquidacion.estado='AC'
	and ambitos.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' 
	and liquidacion.numservicio=servicios.numservicio and nofactura is null $Amb $CP
	order by primape,segape,primnom,segnom,tiposervicio";	
	//echo $cons;
	if($BanCreaLiq==1)
	{
		$cons="select servicios.numservicio,tiposervicio,servicios.cedula,primape,segape,primnom,segnom,fechaing
		,fechaegr,primape,entidad,contrato,nocontrato
		from salud.servicios,central.terceros,salud.pagadorxservicios,salud.ambitos
		where pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' 
		and ambitos.compania='$Compania[0]' and tiposervicio=ambito
		and terceros.compania='$Compania[0]' and servicios.cedula=identificacion 
		and pagadorxservicios.numservicio=servicios.numservicio $FB $Amb $CP
		order by fechaing desc,primape,segape,primnom,segnom,tiposervicio";	
	}
	if($usuario[1]=="jacamon"){
		//echo $cons;
	}	
	$res=ExQuery($cons);?>
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
        	<td>Idenficacion</td><td>Nombre</td><td>Proceso</td><td>Fecha Inicio</td><td>Fecha Final</td>
        </tr>
<?	while($fila=ExFetch($res))
	{
		if($BanCreaLiq==1){
			$cons2="select noliquidacion,nofactura from facturacion.liquidacion where compania='$Compania[0]' 
			and numservicio=$fila[0] and estado='AC'";
			$res2=ExQuery($cons2); 

			if(ExNumRows($res2)>0){
				$fila2=ExFetch($res2); $fila[9]=$fila2[0];
				if($fila2[1]){
					$cons3="select nofactura from facturacion.facturascredito where nofactura='$fila2[1]'
					and estado='AC'";	
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0){
						$BanNoUrg=1;
					}
				}
				$BanNoUrg=1;
				
				//
				//
					
				//echo "entra";	
				//}
				$BanCreaLiq="";

			}
			else{
				$fila[9]="DebeCrear";
				$BanNoUrg="";
			}
		}
		$Fecha=explode(" ",$fila[7]);
		if($BanNoUrg!=1){?>
			<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
        onClick="location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $fila[0]?>&CargarEnLiq=1&NoLiq=<? echo $fila[9]?>&CedPac=<? echo $fila[2]?>&NomPac=<? echo "$fila[3] $fila[4] $fila[5] $fila[6]" ?>&PagaM=<? echo $fila[10]?>&ContraM=<? echo $fila[11]?>&NoContraM=<? echo $fila[12]?>&Ambito=<? echo $fila[1]?>&FechaIni=<? echo $Fecha[0]?>'">
        		<td><? echo $fila[2]?></td><td><? echo "$fila[3] $fila[4] $fila[5] $fila[6]"?></td><td><? echo $fila[1]?></td>
            	<td><? echo $fila[7]?></td><td><? echo $fila[8]?>&nbsp;</td>
        	</tr>	
<?		}
	}?>        
	</table><?
}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>