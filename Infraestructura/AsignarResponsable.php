<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
    $ND = getdate();
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
	if($Asignar)
	{
		//echo $usuario_mant[$Encargado];exit;
        $nocodi = explode("|",$AutoId);
        if(count($nocodi)==1)
        {
            $cons = "Update Infraestructura.Mantenimiento set Encargado = '$Encargado' Where AutoId=$AutoId and EstadoSolicitud='Aprobado'";
        }
        else
        {
            $cons = "Update Infraestructura.Mantenimiento set Encargado = '$Encargado' Where AutoId=0 and EstadoSolicitud='Aprobado'
            and Tercero='$nocodi[0]' and Descripcion='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud ='$nocodi[4]'";
        }
        $res = ExQuery($cons);
        $Mensaje = "Se le ha asignado el mantenimiento del producto <b>$NombreElemento</b><br>
        para hacer revision de los elementos a su cargo presione click
        <a href=/Infraestructura/Mantenimiento.php?DatNameSID=SYS2741361>aqui</a>";
        $consxx = "Select Id from Central.Correos Where COmpania = '$Compania[0]' order by ID desc LIMIT 1";
        $resxx = ExQuery($consxx);
        $filaxx = ExFetch($resxx);
        $Id = $filaxx[0] + 1;
        $cons1 = "Insert into Central.Correos
        (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
         values
        ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
        '".$usuario_mant[$Encargado]."','$Mensaje',$Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
        $res1 = ExQuery($cons1);
        $Id++;
		?>
			<script language="javascript">
            	CerrarThis();
                parent.document.FORMA.submit();
            </script>
		<?
	}
	
	$nocodi = explode("|",$AutoId);
        if(count($nocodi)==1)
        {
            $cons = "Select Encargado From InfraEstructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=$AutoId and EstadoSolicitud='Aprobado'";
        }
        else
        {
            $cons = "Select Encargado From InfraEstructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=0 and EstadoSolicitud='Aprobado'
            and Tercero='$nocodi[0]' and Descripcion='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud ='$nocodi[4]'";
        }
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Enc = $fila[0];
	
	$cons = "Select distinct(Nombre),Cedula,Usuarios.usuario From Central.Usuarios,Infraestructura.ResponsablesMantenimiento Where
	Compania='$Compania[0]' and Usuarios.Cedula = ResponsablesMantenimiento.Usuario and (GrupoElementos='$Grupo' or NoCodificados = 1)";
    $res = ExQuery($cons);
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="hidden" name="Grupo" value="<? echo $Grupo?>"  />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="2">Asignar Encargado de Mantenimiento</td></tr>
<?
	while($fila = ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td>";
		?><td align="center"><input type="radio" <? if($Enc == $fila[1]){ echo " checked ";}?> name="Encargado" value="<? echo $fila[1]?>" />
        <input type="hidden" name="usuario_mant[<?echo $fila[1]?>]" value="<?echo $fila[2]?>" />
        </td><?
		echo "</tr>";	
	}
?>
</table>
<input type="hidden" name="NombreElemento" value="<? echo $NombreElemento?>" />
       
<input type="submit" name="Asignar" value="Asignar" />
</form>
</body>