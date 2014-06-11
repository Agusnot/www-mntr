<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        if(!$Anio){$Anio = $ND[year];}
	if($Eliminar)
	{
            if($Admins){$AdWhere=" and Administrador=1";}
            $cons = "Select Tercero from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Tercero='$Tercero' and Anio=$Anio
            and CC!='000' $AdWhere";
            $res = ExQuery($cons);
            if(ExNumRows($res)==0)
            {
                if(!$Admins)
                {
                    $cons1 = "Delete from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Tercero='$Tercero'";
                }
                else
                {
                    $cons1 = "Update Infraestructura.TercerosxCC set Administrador = NULL Where Compania='$Compania[0]' and Tercero='$Tercero' and Anio=$Anio";
                }
                $res1 = ExQuery($cons1);
            }
            else
            {
                ?><script language="javascript">alert("Asegurese de Eliminar los Centros de Costo para este tercero");</script><?
            }
	}
	if($Agregar)
	{
            if($Identificacion)
            {
                $cons = "Select Tercero from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Anio = $Anio and Tercero='$Identificacion'";
                $res = ExQuery($cons);
                if(ExNumRows($res)==0)
                {
                    if($Admins){$AdCons = ",Administrador";$AdValues=",1";}
                    $cons = "Insert into Infraestructura.TercerosxCC (Compania,Tercero,CC,Anio $AdCons)
                    values ('$Compania[0]','$Identificacion','000',$Anio $AdValues)";
                    $res = ExQuery($cons);
                    $Ag=1;
                }
                else
                {
                    if($Admins)
                    {
                        $cons1="Update Infraestructura.TercerosxCC set Administrador=1 Where Compania='$Compania[0]' and Anio = $Anio and Tercero='$Identificacion'";
                        $res1 = ExQuery($cons1);
                    }
                    else
                    {
                        ?><script language="javascript">alert("El tercero ya se ha ingresado anteriormente");</script><?
                    }
                }
            }
	}
?>
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
    function AbrirCCxTercero(Tercero,Identificacion)
    {
        St = document.body.scrollTop;
        frames.FrameOpener.location.href="AutTercerosxCC.php?Admins=<? echo $Admins?>&DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year]?>&Tercero="+Tercero+"&Identificacion="+Identificacion;
        document.getElementById('FrameOpener').style.position='absolute';
        document.getElementById('FrameOpener').style.top=St + 20;
        document.getElementById('FrameOpener').style.left='38px';
        document.getElementById('FrameOpener').style.display='';
        document.getElementById('FrameOpener').style.width='400';
        document.getElementById('FrameOpener').style.height='450';
    }
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Admins" value="<? echo $Admins?>" />
<table border="1" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>" width="40%">
<tr>
	<td colspan="3" bgcolor="#e5e5e5" style="font-weight:bold">A&ntilde;o
    <select name="Anio" onChange="FORMA.submit()">
    	<?
        	$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($fila[0]==$Anio){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
    </select></td>
</tr>

<tr bgcolor="#e5e5e5" style="font-weight:bold" ><td align="center">TERCERO</td><td width="2%">&nbsp;</td><td width="2%">&nbsp;</td></tr>
<tr>
	<td><input type="Text" name="Tercero" style="width:100%" onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;" 
        onKeyUp="xLetra(this);Identificacion.value='';
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;"
        onKeyDown="xLetra(this)"/>
        <input type="hidden" name="Identificacion" />
	</td>
    <td><button type="submit" name="Agregar" title="Agregar" onClick="Ocultar()"><img src="/Imgs/b_save.png" /></button></td>
    <td bgcolor="#e5e5e5">&nbsp;</td>
</tr>
<?
	if($Admins==1){$AdCons="and Administrador=1";}
        $cons = "Select PrimApe,SegApe,PrimNom,SegNom,Identificacion From Central.Terceros,Infraestructura.TercerosxCC
	Where TercerosxCC.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' and TercerosxCC.Tercero = Terceros.Identificacion
	and TercerosxCC.Anio=$Anio $AdCons
	Group by PrimApe,SegApe,PrimNom,SegNom,Identificacion order by PrimApe,SegApe,PrimNom,SegNom,Identificacion";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        <td><? echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]");?></td>
        <td><button type="button" name="Abrir" title="Ver Centros de Costos" onClick="AbrirCCxTercero('<? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?>','<? echo $fila[4]?>')">
        	<img src="/Imgs/s_process.png" />
        </button></td>
        <td><img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar"
        onClick="if(confirm('Desea eliminar el registro?')){location.href='TercerosxCC.php?Admins=<? echo $Admins?>&Anio=<? echo $Anio?>&DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Tercero=<? echo $fila[4]?>';}" /></td></tr><?
	}
?>
</table>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
<?
	if($Ag)
	{
		?><script language="javascript">AbrirCCxTercero('<? echo $Tercero;?>','<? echo $Identificacion;?>');</script><?	
	}
?>
</body>