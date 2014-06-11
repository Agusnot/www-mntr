<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	$cons="select formato,usucrea from facturacion.facturascredito,contratacionsalud.contratos where facturascredito.compania='$Compania[0]' and contratos.compania='$Compania[0]' and 	
	nofactura=$NoFac and contratos.entidad=facturascredito.entidad and facturascredito.contrato=contratos.contrato and facturascredito.nocontrato=contratos.numero";
	$res=ExQuery($cons); 
	$fila=ExFetch($res); ?>
	<script language="javascript">
		location.href="<? echo $fila[0]?>?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $NoFac?>&NoFacFin=<? echo $NoFacFin?>&Formato=<? echo $fila[0]?>&Estado=<? echo $Estado?>&Impresion=<? echo $Impresion?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&ActCtAg=<? echo "true";?>&Usuario=<? echo $fila[1];?>";</script>	
	<script language="javascript">/*location.href="Factura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $NoFac?>&NoFacFin=<? echo $NoFacFin?>";</script>	
