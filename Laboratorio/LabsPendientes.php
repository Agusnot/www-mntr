<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<script language="javascript">
	function CambiarValores(Nombre,Objeto)	
	{
		if(Nombre=="MesIni")
		{
			Objeto.value=document.FORMA.MesIni.value;
			document.FORMA.MesFin.value=Objeto.value;
		}	
	}
	function AbrirProcedimientos()
	{
		frames.FrameOpener.location.href="Procedimientos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=Programados";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='120px';
		document.getElementById('FrameOpener').style.left='120px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='300px';
		
	}	
</script>
<script language="javascript" src="/Funciones.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="Codigo" value="<? echo $Codigo?>">
<input type="hidden" name="TipoCup" value="<? echo $TipoCup?>">
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>" onFocus="Ocultar()">
<tr>	
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Proceso</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">A&ntilde;o</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Mes Inicio</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Dia Inicio</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Mes Fin</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Dia Fin</td>
    <td rowspan="4"><input type="submit" name="Ver" value="Ver"></td>
</tr>
   
<tr align="center" >
	<td>
    	<input type="text" name="Ced" value="<? echo $Ced?>" style="width:100" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)">
    </td>
	<td>
 <?	if(!$Anio)
	{					
		$Anio=$ND[year];				
	}
	if(!$MesIni){$MesIni=1;}
	if(!$MesFin){$MesFin=$ND[mon];}
	if(!$DiaIni){$DiaIni=1;}
	if(!$DiaFin){if($MesFin==$ND[mon]){$DiaFin=$ND[mday];}else{$DiaFin=1;}}			
 /*
 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  
    from Central.Terceros,contratacionsalud.contratos
    where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion 
    group by identificacion,primape,segape,primnom,segnom order by primape";		//echo $cons;?>
    <select name="Entidad" onChange="FORMA.submit();"><option></option>
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
     </select>    */
	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
	$res=ExQuery($cons); ?>
    <select name="Ambito">
    <option></option>
<?	while($fila=ExFetch($res))
	{
		if($Ambito==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}?>
	</td>  
    <td>
        <select name="Anio" onChange="FORMA.submit();">
        <?
		if(!$Anio){$Anio=$ND[year]; }
        $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
        $res = ExQuery($cons);
        while($fila=ExFetch($res))					
        {
            if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
            else{echo "<option value='$fila[0]'>$fila[0]</option>";}
        }?>                 
   		</select>   
  	</td>
   	<td>                         
        <select name="MesIni" onchange="CambiarValores('MesIni',this);FORMA.submit();">                	
        <?					
        $cons = "Select Mes,Numero from Central.Meses";
        $res = ExQuery($cons);
        while($fila=ExFetch($res))					
        {
            if($MesIni == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
            else{echo "<option value='$fila[1]'>$fila[0]</option>";}
        }
        ?>
        </select>          
        </td>
        <td>
        <select name="DiaIni" onChange="CambiarValores('DiaIni',this);FORMA.submit();">                	
        <?					
        $cons = "Select NumDias from Central.Meses where Numero=$MesIni";
        //echo $cons;					
        $res = ExQuery($cons);
        $fila=ExFetch($res);													
        for($i=1;$i<=$fila[0];$i++)
        {						
            if($DiaIni == $i){echo "<option selected value=$i>$i</option>";}
            else{echo "<option value=$i>$i</option>";}
            
        }
        ?>
        </select>          
        </td>
        <td>
        <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA.submit();"  >                	
        <?					
        $cons = "Select Mes,Numero from Central.Meses";
        $res = ExQuery($cons);
        while($fila=ExFetch($res))					
        {
            if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
            else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}
        }
        ?>
        </select>          
        </td>
        <td>
        <select name="DiaFin" onChange="CambiarValores('DiaFin',this);FORMA.submit();">                	
        <?					
        $cons = "Select NumDias from Central.Meses where Numero=$MesFin";					
        $res = ExQuery($cons);
        $fila=ExFetch($res);													
        for($i=1;$i<=$fila[0];$i++)
        {						
            if($DiaFin == $i && $i>=$DiaIni){echo "<option selected value=$i>$i</option>";}
            else{if($i>=$DiaIni){echo "<option value=$i>$i</option>";}}
            
        }
        ?>
        </select>   
        <input type="hidden" name="FechaIni" value="<? echo $Anio."-".$MesIni."-".$DiaIni?>" />
        <input type="hidden" name="FechaFin" value="<? echo $Anio."-".$MesFin."-".$DiaFin?>" />       
        </td> 
   	</tr>
<tr>    
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Estado</td>    
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">CUP</td>    
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">Nombre CUP</td>    
</tr>     
	<tr align="center">
        <td>
  	<?	$cons="select clasificacion from salud.clasifclabs where compania='$Compania[0]'";
		$res=ExQuery($cons);?>
        <select name="Tipo" onChange="CambiarValores('DiaFin',this);FORMA.submit();"><option></option>
     <?	while($fila=ExFetch($res))					
        {
            if($Tipo == $fila[0]){
				echo "<option selected value='$fila[0]'>$fila[0]</option>";
			}
            else{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
        }?>
        </select> 
        </td>
        <td>       	
        	<select name="Estado" onChange="CambiarValores('DiaFin',this);FORMA.submit();">
            	<option value="Pendiente"<? if($Estado=='Pendiente'){echo "selected";}?>>Pendiente</option> 
            	<option value="Atendido"<? if($Estado=='Atendido'){echo "selected";}?>>Atendido</option>                                            	
            </select>
        </td>
        <td>
        	<input type="text" name="CodCUP" value="<? echo $CodCUP?>" style="width:65" readonly onClick="AbrirProcedimientos()">
        </td>
        <td colspan="4">
        	<input type="text" name="NomCUP" value="<? echo $NomCUP?>" style="width:350" readonly onClick="AbrirProcedimientos()">
        </td>
</tr>
</table>
<?
if($Ver){

if($Tipo){$TipoLab="and laboratorio='$Tipo'";}
if($Estado=='Atendido'){$Estad="and fechalab is not null";  $NoIdH=",id_historia,formato,tipoformato"; }
if($Estado=='Pendiente'){$Estad="and fechalab is null ";$SubC1="and estado='AC'";}
if($CodCUP){$Proced="and plantillaprocedimientos.cup='$CodCUP'";}
if($Entidad){$Ent="and entidad='$Entidad'";}
if($Ambito){$Amb="and ambitoreal='$Ambito'";}
if($Ced){$Cd="and cedula='$Ced'"; }
$cons="select codigo,diagnostico from salud.cie";
$res=ExQuery($cons);
while($fila=ExFetch($res))
{
	$CIE[$fila[0]]=$fila[1];	
}

$cons="select usuario,nombre from central.usuarios";
$res=ExQuery($cons);
while($fila=ExFetch($res))
{
	$Usus[$fila[0]]=$fila[1];	
}
$cons="select cup,nombre,cedula,numservicio,usuario,fechaini,ambitoreal,plantillaprocedimientos.numprocedimiento,diagnostico
,(primape || ' ' || segape || '' || primnom || ' ' || segnom) $NoIdH 
from salud.plantillaprocedimientos,contratacionsalud.cups,central.terceros
where plantillaprocedimientos.compania='$Compania[0]' and cups.compania='$Compania[0]' and fechaini>='$FechaIni' and fechaini<='$FechaFin' and cups.codigo=cup
and cup in (select cup from historiaclinica.cupslabs,historiaclinica.formatos where cupslabs.compania='$Compania[0]' and formatos.compania='$Compania[0]'
and formatos.formato=cupslabs.formato and formatos.tipoformato=cupslabs.tipoformato $SubC1 $TipoLab) 
and terceros.compania='$Compania[0]' and cedula=identificacion $Estad $Proced $Amb $Cd order by fechaini desc,primape,segape,primnom,segnom";
//echo $cons;
$res=ExQuery($cons);
?>
<br>
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>" >
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td>Fecha Solicitud</td><td>Medico Ordena</td><td>Cod CUP</td><td>Procedimiento</td><td>Identificacion</td><td>Nombre</td>
        <td>Diagnostico</td><td>Proceso Consulta</td>
    </tr>
<?	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
            <? 	if($Estado=="Pendiente"){?>
	                onClick="open('VerFormatosHC.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $fila[2]?>&CUP=<? echo $fila[0]?>&Fecha=<? echo $fila[5]?>&NumSer=<? echo $fila[3]?>&NumProced=<? echo $fila[7]?>&DX=<? echo $fila[8]?>','','width=1100,height=600,menubar=yes,scrollbars=YES,resizable=1')"
         	<?	}
				else{?>
                    onClick="open('/HistoriaClinica/Datos.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $fila[2]?>&CUP=<? echo $fila[0]?>&Fecha=<? echo $fila[5]?>&NumSer=<? echo $fila[3]?>&SoloUno=<? echo $fila[10]?>&NumProced=<? echo $fila[7]?>&DX=<? echo $fila[8]?>&Formato=<? echo $fila[11]?>&TipoFormato=<? echo $fila[12]?>','','width=1100,height=600')"
            <?	}?>
             >	
            	<td><? echo $fila[5]?></td><td><? echo $Usus[$fila[4]]?></td><td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
                <td><? echo $fila[9]?></td><td><? echo "$fila[8]-".$CIE[$fila[8]]?></td><td><? echo $fila[6]?></td>
            </tr>
	<?	}
	}	?>
</table>
<?
}?>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
