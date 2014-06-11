<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">	
    function Validar()
	{
		if(document.FORMA.FechaIni.value==""){ 
			alert("Debe seleccionar la fecha de inicio!!!"); 
		}
		else{
			if(document.FORMA.FechaFin.value==""){ 
				alert("Debe seleccionar la fecha final!!!"); 
			}
			else{	
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
					alert("La fecha inicial debe ser menor o igual a la fecha final!!!");
				}					
				else{				
					document.FORMA.Ver.value=1;
					document.FORMA.submit();					
					
				}
			}
		}
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg"> 
<form name="FORMA" method="post"  enctype="multipart/form-data" >
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">		
    <tr align="center">
    	<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold">Informes de Posibles Incosistencias En Las Bases De Datos De La Entidad Responsable del Pago</td>        
  	</tr> 
	<tr>    	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" >		
		</td>        
        <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo</td>    
        <td>
        <?	$cons="select tipo from central.tiposaseguramiento";
			$res=ExQuery($cons);?>
        	<select name="TipoAseg" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$TipoAseg){ 
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
				}?>
            </select>
       	</td>   
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
   	 <?	if($TipoAseg){$TA="and tipoasegurador='$TipoAseg'";}
	 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion $TA 
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="4">
        	<select name="Entidad" onChange="document.FORMA.submit()"><option></option>
      	<?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Entidad==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
              <? }
			  }?>
             </select>
        </td>
        <td align="center">
        	<input type="button" value="Ver" onClick="Validar();">
        </td>
	</tr>
</table>    
<?
if($Ver){
	if($TipoAseg){$TAseg=" and tipoasegurador='$TipoAseg'";}
	if($Entidad){$Ent="and terceros.identificacion='$Entidad'";}
	$cons="select numinforme,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,fecha from reportes3047.inc_bd,central.terceros
	where inc_bd.compania='$Compania[0]' and terceros.compania='$Compania[0]' and inc_bd.fecha>='$FechaIni 00:00:00' and inc_bd.fecha<='$FechaFin 23:59:59' $Ent $TAseg
	and inc_bd.entidad=terceros.identificacion order by numinforme";
	$res=ExQuery($cons);?>
		<br>
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">		
<?	if(ExNumRows($res)>0){	?>
        <tr  colspan="6" bgcolor="#e5e5e5" style="font-weight:bold">
        	<td>No. Informe</td><td>Entidad</td><td>Fecha</td>
        </tr>
  	<?	while($fila=ExFetch($res)){?>
			<tr title="Ver" style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center"
            onclick="open('InformePDFIncBD.php?DatNameSID=<? echo $DatNameSID?>&NumInf=<? echo $fila[0]?>','','width=1100,height=600');">
            	<td><? echo $fila[0]?></td><td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td><td><? echo $fila[5]?></td>
            </tr>
	<?	}?>
<?	}
    else{?>
		<tr align="center">
    		<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold">NO SE ENCOTRARON RESULTADOS QUE COINCIDAN CON LOS CRITERIOS DE BUSQUEDA</td>        
	  	</tr> 
<?	}?>
	</table><?	
}
?>
<input type="hidden" name="Ver" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
