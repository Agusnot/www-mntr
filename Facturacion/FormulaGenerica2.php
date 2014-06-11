<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select (primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),
	pagadorxservicios.entidad,pagadorxservicios.contrato,pagadorxservicios.nocontrato,
	direccion,telefono,tipoasegurador,codigosgsss,planbeneficios,planservmeds
	from salud.pagadorxservicios,central.terceros,contratacionsalud.contratos
	where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and
	pagadorxservicios.entidad=identificacion and NumServicio = $Numero
	and contratos.compania='$Compania[0]' and contratos.entidad=pagadorxservicios.entidad and pagadorxservicios.contrato=contratos.contrato
	and numero=nocontrato";
	$res=ExQuery($cons);
	$Cliente=Exfetch($res);
	
	$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and clase='Medicamentos' and autoid='$Cliente[9]'";
	$res=ExQuery($cons);
	$PlanMed=Exfetch($res);
	$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and clase='CUPS' and autoid='$Cliente[8]'";
	$res=ExQuery($cons);
	$PlansCups=Exfetch($res);
	
	$cons="select cedula,(primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom)
	,terceros.nocarnet,terceros.tipousu,terceros.nivelusu,fecnac,age('$ND[year]-$ND[mon]-$ND[mday]',fecnac),sexo,autorizac1,autorizac2,autorizac3,tiposervicio
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and identificacion=cedula and Identificacion='$Cedula'
	and numservicio=$Numero";
	//echo $cons;
	$res=ExQuery($cons);
	$DatPaciente=ExFetch($res);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	
</script>
</head>

<body>
<form name="FORMA" method="post">  


<table  style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" width="100%">  
<tr>
	<TD align="center">
    	<div>
        	<table  style="font-family:Tahoma;font-size:11;"  cellpadding="2">
            <?	$Raiz=$_SERVER['DOCUMENT_ROOT'];?>
            	<tr align="center"><td align="center" rowspan="4"><img src="/Imgs/Logo.jpg" style="width:80; height:100"/>                	
                </tr>
                
            </table>
        </div>
    </TD>
    <td>
    	<div>
	        <table style="font-family:<?echo $Estilo[8]?>;font-size:15;font-style:<?echo $Estilo[10]?>" cellpadding="2">
            	<tr><td><strong><? echo strtoupper($Compania[0])?></strong></td></tr>
                <tr><td><? echo $Compania[1]?></td></tr>
                <tr><td>CODIGO SGSSS <? echo $Compania[17]?></td></tr>
                <TR><td><? echo "$Compania[2] - TELEFONOS:".strtoupper($Compania[3])?></td></TR>
          	</table>
        </div>
    </td>
	<td  align="center"><strong>FORMULA DE MEDICAMENTOS</strong></td>
</tr>
</table>
<table    style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" cellpadding='0'>  
	<tr>
    	<td><strong>ASEGURADOR:</strong></td><td colspan="5"><? echo $Cliente[0]?></td>
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td><td><? echo $Cliente[1]?></td><td><strong>CODIGO SGSSS:</strong></td><TD><? echo $Cliente[7]?></TD>
        <td><strong>REGIMEN</strong></td><td><? echo $Cliente[6]?></td>
    </tr>
    <tr>
    	<td><strong>CONTRATO:</strong></td><td><? echo $Cliente[2]?></td><td><strong>No CONTRATO:</strong></td><td><? echo $Cliente[3]?></td>
        <td><strong>PLAN MEDS:</strong></td><td><? echo $PlanMed[0]?></td>
    </tr>
    <tr>
    	<td><strong>DIRECCION:</strong></td><td><? echo $Cliente[4]?></td><td><strong>TELEFONO:</strong></td><td><? echo $Cliente[5]?></td>
        <td><strong>PLAN SERVS:</strong></td><td><? echo $PlansCups[0]?></td>
    </tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
    	<td><strong>PACIENTE:</strong></td><td colspan="3" width="60%"><? echo $DatPaciente[1]?></td>
        <td><strong>IDENTIFICACION:</strong></td><td><? echo $DatPaciente[0]?></td>
    </tr>
    	<td><strong>No CARNET:</strong></td><td><? echo $DatPaciente[2]?></td><td><strong>Tipo Usuario:</strong></td><td><? echo $DatPaciente[3]?></td>
        <td><strong>NIVEL USUARIO:</strong></td><td><? echo $DatPaciente[4]?></td>
    </tr>
     <tr>
    	<td><strong>EDAD:</strong></td>
        <td><? $DatPaciente[6]=str_replace("years","a&ntilde;os",$DatPaciente[6]); $DatPaciente[6]=str_replace("mons","meses",$DatPaciente[6]); 
			$DatPaciente[6]=str_replace("days","dias",$DatPaciente[6]); echo $DatPaciente[6]." ($DatPaciente[5])";?></td>
        <td><strong>SEXO:</strong></td><td><? if($DatPaciente[7]=="F"){echo "Femenino";}elseif($DatPaciente[6]=="M"){echo "Masculino";}?></td>
        <td><strong>SERVICIO:</strong></td><td><? echo $DatPaciente[11]?></td>
    </tr>
<?	if($Med){$ME=" and usuario='$Med'";}
	if($FechaIni){$FI="and fechaformula>='$FechaIni 00:00:00'";}
	if($FechaFin){$FF="and fechaformula<='$FechaFin 23:59:59'";} 
	if($NoFac)
	{
		$NF="and autoid in (select cast(codigo as int) 
		from facturacion.detallefactura where compania='$Compania[0]' and nofactura='$NoFac' and tipo='Medicamentos')";
	}
	if($Cedula){$Ced="and cedpaciente='$Cedula'";}
	$consFec="select fechaformula
	from salud.plantillamedicamentos,consumo.codproductos where codproductos.compania='$Compania[0]' and plantillamedicamentos.compania='$Compania[0]' 
	and numservicio=$Numero and autoid=autoidprod and plantillamedicamentos.almacenppal='$AlmacenPpal' and codproductos.almacenppal='$AlmacenPpal'
	$ME $FI $FF $NF $Ced order by numorden desc,idescritura desc";
	$resFec=ExQuery($consFec); 
	$filaFec=Exfetch($resFec);?>    
    <tr>
    	<td><strong>FECHA ORDEN</strong></td><td><? echo $filaFec[0]?></td>
    </tr>
   <!-- </tr>
    	<td><strong>Autorizacion 1:</strong></td><td><? echo $DatPaciente[5]?></td><td><strong>Autorizacion 2:</strong></td><td><? echo $DatPaciente[6]?></td>
        <td><strong>Autorizacion 3:</strong></td><td><? echo $DatPaciente[7]?></td>
    </tr>-->
</table>
<br />
<table style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>"  cellpadding='0' border="1"  bordercolor="#e5e5e5" align="center">  
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td>No</td><td>MEDICAMENTO</td><td>CANT</td><td>POSOLOGIA</td><td>VIA SUMINISTRO</td>
</tr><?
$cons="select codigo1,nombreprod1,unidadmedida,presentacion,cantdiaria,viasuministro,detalle,posologia,usuario,numorden,idescritura
from salud.plantillamedicamentos,consumo.codproductos where codproductos.compania='$Compania[0]' and plantillamedicamentos.compania='$Compania[0]' 
and numservicio=$Numero and autoid=autoidprod and plantillamedicamentos.almacenppal='$AlmacenPpal' and codproductos.almacenppal='$AlmacenPpal'
$ME $FI $FF $NF $Ced order by codigo2,codigo1";
$res=ExQuery($cons); 

//echo $cons;
$cont=1;
while($fila=Exfetch($res))
{
	
	echo "<tr align='center'><td>$cont</td><td>$fila[1] $fila[3] $fila[2]</td><td>$fila[4]</td><td>$fila[7]</td><td>$fila[5]</td></tr>";
	$Med=$fila[8];
	$cont++;
}
?>
</table>
<?
	$cons="select nombre,cedula,rm,cargo from central.usuarios,salud.medicos where usuarios.usuario='$Med' and medicos.compania='$Compania[0]'
	and medicos.usuario=usuarios.usuario";
	$res=ExQuery($cons);
	//echo $cons;
	$fila=ExFetch($res);
?>
<br /><br />
<table  style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>"  width="100" align="center" cellspacing="1" cellpadding="1">
	<tr align="center">
    	<td><img src="/Firmas/<? echo $fila[1]?>.GIF" style="width:130; height:80"/></td>
    </tr>
    <tr align="center"><td><? echo "<strong>".strtoupper($fila[3])."</strong>: ".strtoupper($fila[0])." - <strong>RM:</strong> $fila[2]"?></td>
	<tr align="center">
    <td><hr width="380px"/></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><hr width="380px" /></td>
    </tr>
    <tr align="center">
    	<td>quien formula</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>quien recibe</td>
    </tr>
</table>

</form>
</body>
</html>