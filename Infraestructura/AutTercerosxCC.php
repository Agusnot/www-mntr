<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
            if(!$Admins)
            {
                $cons = "Delete from Infraestructura.TercerosxCC where Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio
                and Administrador is NULL";
            }
            else
            {
                $cons = "Update Infraestructura.TercerosxCC set Administrador = NULL where Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio";
            }
            $res = ExQuery($cons);
            if($Check)
            {
                while( list($cad,$val) = each($Check))
                {
                    if($Admins)
                    {
                        $consx = "Select * from Infraestructura.TercerosXCC Where Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio
                        and CC='$cad'";
                        $resx = ExQuery($consx);
                        if(ExNumRows($resx)==0)
                        {
                            $cons1 = "Insert into Infraestructura.TercerosxCC (Compania,Tercero,CC,Anio,Administrador)
                            values ('$Compania[0]','$Identificacion','$cad',$Anio,1)";
                        }
                        else
                        {
                            $cons1 = "Update Infraestructura.TercerosxCC set Administrador = 1 Where
                            Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio and CC='$cad'";
                        }
                        $res1 = ExQuery($cons1);
                    }
                    else
                    {
                        $consx = "Select * from Infraestructura.TercerosXCC Where Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio
                        and CC='$cad'";
                        $resx = ExQuery($consx);
                        if(ExNumRows($resx)==0)
                        {
                            $cons = "Insert into Infraestructura.TercerosxCC (Compania,Tercero,CC,Anio) values ('$Compania[0]','$Identificacion','$cad',$Anio)";
                            $res = ExQuery($cons);    
                        }
                    }
                }
            }
	}
	$cons = "Select CC from Infraestructura.TercerosxCC where Compania='$Compania[0]' and Tercero='$Identificacion' and Anio=$Anio";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Checked[$fila[0]] = " checked ";
	}
?>
<script language="JavaScript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Marcar()
	{
		if(document.FORMA.Habilitar.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Tercero" value="<? echo $Tercero?>" />
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>" />
<input type="hidden" name="Admins" value="<? echo $Admins?>" />
<table border="0" width="100%">
<tr><td align="right">
	<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
	<button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button>
	<input type="checkbox" name="Habilitar" title="Habilitar/Deshabilitar Todo" onClick="Marcar()" />
</td></tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2"><? echo "$Tercero - $Identificacion";?></td></tr>
	<?
    	$cons = "Select Codigo,CentroCostos,Tipo from Central.CentrosCosto where Anio = '$Anio' and Compania = '$Compania[0]' and Codigo<>'000' Order by Codigo";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			$Tabs = strlen($fila[0]);
			if($fila[2]=='Titulo'){$BgColor = "#e5e5e5";$Check="&nbsp;";}
			else{$BgColor = "";$Check="<input type='checkbox' name='Check[$fila[0]]' ".$Checked[$fila[0]]." />";}
			echo "<tr bgcolor='$BgColor'><td>";
			for($i=0;$i<$Tabs;$i++){echo "&nbsp;";}
			echo "$fila[0] - $fila[1]</td><td width='10px' align='right'>$Check</td></tr>";
		}
		
	?>
</table>
</form>
</body>