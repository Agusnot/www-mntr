<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<br><br><br><br>
<?
	$FecIL=explode("-",$FecIniLiq);			
	$FIL = mktime (0,0,0,$FecIL[1],$FecIL[2],$FecIL[0]);	
	$FecFL=explode("-",$FecFinLiq2);			
	$FFL = mktime (0,0,0,$FecFL[1],$FecFL[2],$FecFL[0]);
		
	$cons="select fechaini,fechafin from facturacion.liquidacion where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res)){
		$FecICons=explode("-",$fila[0]);			
		$FIC = mktime (0,0,0,$FecICons[1],$FecICons[2],$FecICons[0]);	
		$FecFCons=explode("-",$fila[1]);			
		$FFC = mktime (0,0,0,$FecFCons[1],$FecFCons[2],$FecFCons[0]);
		//echo "FIL=$FecIniLiq FFL=$FecFinLiq2 FIC=$fila[0] FFC=$fila[1]<br>\n";		
		if($FIL<=$FIC){  		
			if($FFL>=$FFC){
				$Rep=1; 
			}
			else{
				if($FFL>=$FCI){
					$Rep=1;					
				}
			}			
		}
		else{ 
			if($FFC>=$FFL){
				$Rep=1;

			}	
			else{
				if($FIL<=$FFC){
					$Rep=1;					
				}	
			}					
		}	
	}	
if($Rep==1){?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">	
        <td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">¡Este Periodo Ya Ha Sido Facturado!</td>
	</tr> 
    <tr align="center">
    	<td colspan="2">Desea continuar</td>
    </tr>       
    <tr align="center">
    	<td><input type="button" value="Si" 
        onClick="location.href='Liquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $NumServ?>&FecIniLiq=<? echo $FecIniLiq?>&FecFinLiq=<? echo $FecFinLiq?>&FecFinLiq2=<? echo $FecFinLiq2?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNocont=<? echo $PagaNocont?>'"></td>
        <td><input type="button" value="No" onClick="location.href='SelectPeriodoLiq.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FecIniLiq?>&FechaFin=<? echo $FecFinLiq2?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNocont=<? echo $PagaCont?>''"></td>
    </tr>
</table>
<?
}
else{/*?>
	<script language="javascript">location.href='Liquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $NumServ?>&FecIniLiq=<? echo $FecIniLiq?>&FecFinLiq=<? echo $FecFinLiq?>&FecFinLiq2=<? echo $FecFinLiq2?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNocont=<? echo $PagaNocont?>'		
    </script>	*/?>
    <script language="javascript">location.href='Liquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $NumServ?>&FecIniLiq=<? echo $FecIniLiq?>&FecFinLiq2=<? echo $FecFinLiq2?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNocont=<? echo $PagaNocont?>'		
    </script>	
<?
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
