<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$Clase){$Clase = "Devolutivos";}
	if($Origen == "Solicitud")
	{
            $IRO = " readonly ";
            $cons = "Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros,Central.Usuarios
            Where Compania='$Compania[0]' and Terceros.Identificacion = Usuarios.Cedula and Nombre='$usuario[0]'";
            $res = ExQuery($cons);
            $fila = ExFetch($res);
            $Tercero="$fila[0] $fila[1] $fila[2] $fila[3]";
            $Identificacion="$fila[4]";

            $cons = "Select CC from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Administrador=1 And Anio=$ND[year] and tercero='$usuario[2]'";
            $res = ExQuery($cons);
            if(ExNumRows($res)>0)
            {
                unset($IRO);
                while($fila=ExFetch($res))
                {
                    if(!$CCAdm){$CCAdm="'$fila[0]'";}
                    else{$CCAdm=$CCAdm.",'$fila[0]'";}
                }
                $cons1 = "Select distinct(tercero) from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Anio=$ND[year] and cc in($CCAdm)";
                $res1 = ExQuery($cons1);
                while($fila1=ExFetch($res1))
                {
                    if(!$TerceroAdm){$TerceroAdm = "'$fila1[0]'";}
                    else{$TerceroAdm=$TerceroAdm.",'$fila1[0]'";}
                }
                $CCAdm = str_replace("'","",$CCAdm);
                $TerceroAdm = str_replace("'","",$TerceroAdm);
                //echo $CCAdm."-----".$TerceroAdm;
            }
        }
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='110px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Enviar()
	{
		parent.document.getElementById('Superior').rows="45,*";
		document.FORMA.B.style.visibility = "visible";
		document.FORMA.Imprimir.style.visibility = "visible";
		document.FORMA.Financiero.style.visibility = "visible";
		if(document.FORMA.Codigo.value != "")
		{
			document.FORMA.Codigo1.style.visibility = "visible";
			document.getElementById("lblcodigo").style.visibility = "visible";
		}
		document.FORMA.target = "BusquedaElemento";
		document.FORMA.action = "BusquedaElemento.php?Buscar=1;Clase="+document.FORMA.Clase.value;
		if(document.FORMA.Relacion.value=="No Encontrados")
		{
			document.FORMA.E.style.visibility = "visible";
		}
		document.FORMA.submit();
	}
	function Regresar()
	{
		document.FORMA.target = "";
		document.FORMA.action = "";
		parent(1).location.href="about:blank";
		parent.document.getElementById('Superior').rows="100%,*";
		document.FORMA.B.style.visibility = "hidden";
		document.FORMA.E.style.visibility = "hidden";
		document.FORMA.Imprimir.style.visibility = "hidden";
		document.FORMA.Financiero.style.visibility = "hidden";
		document.FORMA.Codigo1.style.visibility = "hidden";
		document.getElementById("lblcodigo").style.visibility = "hidden";
	}
	function Imp()
	{
		document.FORMA.target = "_blank";
		if(document.FORMA.Financiero.checked == false){document.FORMA.action = "/Informes/Infraestructura/Reportes/ConsultaElementos.php?";}
		else{document.FORMA.action = "/Informes/Infraestructura/Reportes/ElementosFinanciero.php?";}
		document.FORMA.submit();
	}
	function HacerSubmit(Objeto,Evento)
	{
		if(Evento.keyCode==13){Enviar();document.FORMA.Codigo1.value="";}
		if(Objeto.name == "Codigo" || Objeto.name == "Codigo1"){document.FORMA.Codigo1.focus();}
			
	}
        function VerSolicitudes(Identificacion)
	{
		//posY = e.clientY;
		//sT = document.body.scrollTop;
                frames.FrameOpener.location.href="VerSolicitudes.php?TercerosAdm=<? echo $TerceroAdm?>&DatNameSID=<? echo $DatNameSID?>&Identificacion="+Identificacion;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.left='10px';
		document.getElementById('FrameOpener').style.top='50';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='750';
		document.getElementById('FrameOpener').style.height='350';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Origen" value="<? echo $Origen?>" />
<input type="hidden" name="Responsable" value="<? echo $Responsable?>" />
<input type="hidden" name="Fecha" value="<? echo $Fecha?>" />
<input type="hidden" name="H" value="<? echo $H?>" />
<input type="hidden" name="M" value="<? echo $M?>" />
<input type="hidden" name="CCAdm" value="<? echo $CCAdm;?>" />
<input type="hidden" name="TerceroAdm" value="<? echo $TerceroAdm;?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5">
<tr>
<td valign="middle">
<button type="button" name="B" style="visibility:hidden;" title="Volver a la Busqueda" onClick="Regresar()"><img src="/Imgs/b_search.png" /></button>
<button type="button" name="Imprimir" style="visibility:hidden" title="Imprimir Busqueda" onClick="Imp()"><img src="/Imgs/b_print.png" /></button>
<input type="checkbox" name="Financiero" style="visibility:hidden" title="Financiero" />
</td>
<td><label id="lblcodigo" style="visibility:hidden; background-color:#e5e5e5">Codigo:</label></td><td><input type="text" name="Codigo1" size="8" onFocus="Ocultar()" style="visibility:hidden"
onkeyup="xLetra(this);HacerSubmit(this,event)" onKeyDown="xLetra(this)" />
</td>
<td>&nbsp;</td>
<td>
<button type="button" name="E" style="visibility:hidden;" title="Eliminar Seleccionados" onClick="parent(1).document.FORMA.submit()" ><img src="/Imgs/b_drop.png" /></button>
</td>
</tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="300px">
<tr>
<td colspan="7" align="right" bgcolor="#e5e5e5">Clase</td>
<td><select name="Clase" <? if($Origen=="Agenda"){ echo " disabled ";}?> onChange="FORMA.submit()" onFocus="Ocultar()">
	<option value="Devolutivos" <? if($Clase=="Devolutivos"){ echo " selected ";}?> >Devolutivos</option>
    <option value="Activos Fijos" <? if($Clase=="Activos Fijos"){ echo " selected ";}?>>Activos Fijos</option>
</select></td>
</tr>
<tr>
<td bgcolor="#e5e5e5">Codigo</td><td><input type="text" name="Codigo" size="8" onFocus="Ocultar()"
onkeyup="xLetra(this);HacerSubmit(this,event)" onKeyDown="xLetra(this)" /></td>
<td bgcolor="#e5e5e5">Fecha Adquisicion</td><td><input type="text" name="FechaAd" size="8" value="<? echo $FechaAd?>" onFocus="Ocultar()"
        onclick="popUpCalendar(this, FORMA.FechaAd, 'yyyy-mm-dd')" readonly onDblClick="this.value=''" /></td>
<td bgcolor="#e5e5e5">Grupo</td>
<? if($Grupo && $Origen=="Agenda")
	{
		$DIS="disabled";
		?><input type="hidden" name="GrupoX" value="<? echo $Grupo?>" /><?
	}
	else{$DIS="";} ?>
<td><select name="Grupo" onFocus="Ocultar()" <? echo $DIS?> ><option></option>
	<?
    $cons = "Select Grupo From Infraestructura.GruposdeElementos Where Compania='$Compania[0]' and Clase='$Clase' and Anio=$ND[year] order by Clase,Grupo";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($fila[0]==$Grupo){echo "<option selected value='$fila[0]'>$fila[0]</option>";	}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	?>
</select></td>
<td bgcolor="#e5e5e5">Impacto</td>
<td><select name="Impacto" onFocus="Ocultar()"><option></option>
	<?
    $cons = "Select Nombre from Central.Impactos";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";	
	}	
	?>
</select></td>
</tr>
<tr>
<td bgcolor="#e5e5e5">Nombre</td><td colspan="7"><input type="text" name="Nombre" style="width:550px" onFocus="Ocultar()"
onkeyup="xLetra(this)" onKeyDown="xLetra(this)" /></td>
</tr>
<tr>
<td bgcolor="#e5e5e5">Caracteristicas</td><td colspan="7"><input type="text" name="Caracteristicas" style="width:550px" onFocus="Ocultar()"
onkeyup="xLetra(this)" onKeyDown="xLetra(this)" /></td>
</tr>
<tr>
<td bgcolor="#e5e5e5">Modelo</td><td><input type="text" name="Modelo" size="8" onKeyUp="xLetra(this)" 
onKeyDown="xLetra(this)" onFocus="Ocultar()" /></td>
<td bgcolor="#e5e5e5">Estado</td>
<td><select name="Estado" onFocus="Ocultar()"><option></option>
	<?
    $cons = "Select Nombre from Central.Estados";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";	
	}
	?>
</select></td>
<td bgcolor="#e5e5e5">Serie</td><td><input type="text" name="Serie" size="8"  onFocus="Ocultar()"
onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
<td bgcolor="#e5e5e5" >Marca</td><td><input type="text" name="Marca" size="8" onFocus="Ocultar()"
onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
</tr>
<tr>
<td bgcolor="#e5e5e5">Responsable</td>
<td colspan="4">
<input type="Text" name="Tercero" style="width:100%"  <? if($Origen=="Solicitud"){ echo "value='$Tercero' $IRO ";}?> onFocus="Mostrar();
if(CC.value==''){frames.Busquedas.location.href='Busquedas.php?TerceroAdm=<? echo $TerceroAdm?>&DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;}
else{ frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Identificacion&ObjTercero=Tercero&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}" 
onKeyUp="xLetra(this);Identificacion.value='';
if(CC.value==''){frames.Busquedas.location.href='Busquedas.php?TerceroAdm=<? echo $TerceroAdm?>&DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;}
else{ frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Identificacion&ObjTercero=Tercero&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}"
onKeyDown="xLetra(this)"/>

<input type="hidden" name="Identificacion" <? if($Origen=="Solicitud"){ echo " value='$Identificacion' ";}?> /> 
</td>
<td bgcolor="#e5e5e5" align="right">Centro Costos</td>
<td colspan="2">
    <input type="text" name="CC" style="width:100%;text-align:right;" 
onFocus="Mostrar();
if(Identificacion.value==''){frames.Busquedas.location.href='Busquedas.php?CCAdm=<? echo $CCAdm?>&DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCC=CC&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+Identificacion.value;};"
onkeyup="if(Identificacion.value==''){frames.Busquedas.location.href='Busquedas.php?CCAdm=<? echo $CCAdm?>&DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+Identificacion.value;};
xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /> 
</td>
</tr>
<tr>
    <?
        if($Origen=="Agenda")
        {
            ?>
            <input type="hidden" name="Incluir" value="Solo Activos" />
            <input type="hidden" name="Relacion" value="Encontrados" />
            <?
        }
    ?>
    <td bgcolor="#e5e5e5" colspan="5" align="right">Incluir
    <select name="Incluir" <? if($Origen=="Agenda"){ $Incluir="Solo Activos"; echo " disabled ";}?> onFocus="Ocultar()">
    	<option value="Solo Activos">Solo Activos</option>
        <option value="Solo Bajas">Solo Dados de Baja</option>
        <option value="Todos">Activos y Dados de Baja</option>
    </select></td>
    <td align="right" bgcolor="#e5e5e5">Relacion de</td>
    <td colspan="2"><select name="Relacion" style="width:100%;" <? if($Origen=="Agenda"){ $Relacion="Encontrados"; echo " disabled ";}?> onFocus="Ocultar()">
    	<option value="Encontrados">Encontrados</option>
        <option value="No Encontrados">No Encontrados</option>
        <option value="">Todos</option>
    </select></td>
</tr>
</table>
<button type="button" name="Buscar" value="Buscar" onClick="Enviar();Ocultar();document.body.scrollTop = 0">Buscar</button>
<?
if($Origen=="Agenda")
{
?>
<input type="button" name="Volver" value="Volver" onClick="location.href='AgendaMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Responsable=<? echo $Responsable?>&Grupo=<? echo $Grupo?>'" />
<?	
}
if($Origen == "Solicitud")
{
?>
<input type="button" name="VerSol" value="Ver solicitudes" onClick="VerSolicitudes(Identificacion.value);Ocultar()" />
<?
}
?>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>