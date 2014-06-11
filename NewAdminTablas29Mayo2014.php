<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$Original=$Tabla;
	$Tabla=explode(".",$Tabla);
	$NomTabla=$Tabla[1];
	$BD=$Tabla[0];
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
	$cons0="SELECT column_name,column_default,data_type,character_maximum_length,data_type FROM information_schema.columns 
			WHERE table_name ='".strtolower($NomTabla)."' and table_schema='".strtolower($BD)."'";
	$res0=ExQuery($cons0);
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons="Insert into $Original (";
			$b=0;
			while($fila0=ExFetch($res0))
			{
				$cons = $cons . $fila0[0] . ",";
				$b++;
			}
			$cons=substr($cons,0,strlen($cons)-1);
			$cons= $cons .") values (";
			$b = 0;
			while( list($cad,$val) = each ($Valor))
			{
				$cons = $cons ."'". $val . "',";
				$b++;
			}
			$cons=substr($cons,0,strlen($cons)-1);
			$cons=$cons . ")";
		}
		else
		{
			$cons = "Update $Original set ";
			while( list($cad,$val) = each ($Valor))
			{
				$cons = $cons. $cad . "='" . $val ."',";
			}
			$cons=substr($cons,0,strlen($cons)-1). " where ";
			$Valores = explode("|",$Criterio);
			for($i=0;$i<count($Valores)-2;$i+=2)
			{
				if($i==count($Valores)-3){$cons = $cons. $Valores[$i]. "='". $Valores[$i+1]."'";}
				else{$cons = $cons. $Valores[$i]. " ='". $Valores[$i+1]. "' and ";}
			}
		}
		$res = ExQuery($cons);
		?><script language="javascript">
		<? if($VienedeOtro) { ?>CerrarThis()<? }
		else { ?>location.href="AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>"<? } ?>
		</script><?
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script language="javascript" src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	if($Editar)
	{
		$Valores = explode("|",$Criterio);
		for($i=0;$i<count($Valores);$i+=2)
		{
			$Valor[$Valores[$i]] = $Valores[$i+1];
			//echo "Valor[".$Valores[$i]."] = ".$Valores[$i+1]."<hr>";
		}
	}
?>
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" 
        style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">

<?
	$res0=ExQuery($cons0);
	while($fila0=ExFetch($res0))
	{
		$Pl = strtoupper(substr($fila0[0],0,1));
		$fila0[0] = $Pl.substr($fila0[0],1,strlen($fila0[0]));
		if($fila0[0]=="Compania")
		{$Campo = "<input type='hidden' name='Valor[$fila0[0]]' value='$Compania[0]'/>";}
		else
		{$Campo = "<tr><td bgcolor='#e5e5e5'>$fila0[0]</td><td><input type='text' name='Valor[$fila0[0]]' onKeyUp='xLetra(this)' onKeyDown='xLetra(this)' 
		value='".$Valor[$fila0[0]]."' maxlength='$fila0[3]'></td></tr>";}
		echo $Campo;
	}
?>
</table>
<input type="hidden" name="Original" value="<? echo $Original?>">
<input type="submit" name="Guardar" value="Guardar">
<input type="button" name="Cancelar" value="Volver" 
	<? if($VienedeOtro) { ?>onClick="CerrarThis()"<? }
		else { ?>onClick="location.href='AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>'"<? } ?> />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
