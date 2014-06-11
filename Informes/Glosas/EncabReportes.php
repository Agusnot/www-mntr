<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		parent(1).location.href="about:blank";
		//parent.document.getElementById('Reporteador').rows="340,*";
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		//parent.document.getElementById('Reporteador').rows="80,*";
		document.getElementById('Busquedas').style.display='none';
	}	
	function CambiarValores(Nombre,Objeto)	
	{
		if(Nombre=="MesIni")
		{
			Objeto.value=document.FORMA1.MesIni.value;
			document.FORMA1.MesFin.value=Objeto.value;
		}	
	}
	function CopiarFac(){
		document.FORMA1.FacFin.value=document.FORMA1.FacIni.value;
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg" onFocus="Ocultar()">

<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
    <form name="FORMA1" method="post">
                          <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
                       
	<tr align="center">	
        <td width="547" colspan="8" bgcolor="#e5e5e5" style="font-weight:bold">Informe de Respuesta</td>
        
	</tr>
    <tr>	
 <td>
                        	 Seleccione una opcion:
						<select name="Seleccion" onChange="location.href='EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Seleccion=' + this.value+'&Tipo=<? echo $Tipo?>' ">
						<option value=""></option>
						<?
						$cons="Select Nombre from Central.Reportes where Clase='Glosas' and Modulo='Glosas' Order By Id";echo $cons;
						$res=ExQuery($cons);
						while($fila=ExFetch($res))
						{
							if($Seleccion==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
							else{echo "<option value='$fila[0]'>$fila[0]</option>";}
						}
					?>
						</select> 
    				</td>
	</tr>        
    <tr><td align="center" colspan="9">
    
     <?	if($Seleccion){
							$cons="Select Tipo,Archivo from Central.Reportes where Nombre='$Seleccion'  and Modulo='Glosas'";
							$res=ExQuery($cons);
							$fila=ExFetch($res);
							//echo $cons;
							$Tipo=$fila[0]; 
							$NomArchivo=$fila[1];						
							//if($Tipo==10){
								if(!$Anio)
								{					
									$Anio=$ND[year];				
								}
								if(!$MesIni){$MesIni=1;}
								if(!$MesFin){$MesFin=$ND[mon];}
								if(!$DiaIni){$DiaIni=1;}
								if(!$DiaFin){if($MesFin==$ND[mon]){$DiaFin=$ND[mday];}else{$DiaFin=1;}}			?>                               
      	<?	if($Tipo==10){?>
  <input type="button" value="Ver" onClick="frames.Busquedas.location.href='Radicadas.php?DatNameSID=<? echo $DatNameSID?>';document.getElementById('Busquedas').style.display='';" >                                        
                               	<?	} ?>
								
								
			<?	if($Tipo==12){?>
  <input type="button" value="Ver" onClick="frames.Busquedas.location.href='Respuesta.php?DatNameSID=<? echo $DatNameSID?>';document.getElementById('Busquedas').style.display='';" >                                        
                               	<?	} ?>
                                <?	if($Tipo==13){?>
  <input type="button" value="Ver" onClick="frames.Busquedas.location.href='Radicadas.php?DatNameSID=<? echo $DatNameSID?>';document.getElementById('Busquedas').style.display='';" >                                        
                               	<?	} ?>	
                                <?	if($Tipo==14){?>
  <input type="button" value="Ver" onClick="frames.Busquedas.location.href='Radicadas.php?DatNameSID=<? echo $DatNameSID?>';document.getElementById('Busquedas').style.display='';" >                                        
                               	<?	} ?>	
                                  <?	if($Tipo==15){?>
  <input type="button" value="Ver" onClick="frames.Busquedas.location.href='Trazabilidad.php?DatNameSID=<? echo $DatNameSID?>';document.getElementById('Busquedas').style.display='';" >                                        
                               	<?	} ?>								
								
								<? } ?>
                                
                                
    
    </td></tr>
    
   </form>
</table>


<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0"  width="100%" height="85%"></iframe>      
</body>
</html>
