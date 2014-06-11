<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($CodRestric)
	{
		switch($Restric){
			case 1:	$DiaElim='Lunes';break;
			case 2:	$DiaElim='Martes';break;
			case 3:	$DiaElim='Miercoles';break;
			case 4:	$DiaElim='Jueves';break;
			case 5:	$DiaElim='Viernes';break;
			case 6:	$DiaElim='Sabado';break;
			case 0:	$DiaElim='Domingo';break;
		}
		$cons = "Select Idhora from salud.tempconsexterna where  dia='$DiaElim' and tmpcod='$TMPCOD' and compania='$Compania[0]' order by Idhora desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);		
		$Idhora = $fila[0];
		
		$cons="update salud.tempconsexterna set cuppermitido='$CodRestric' 
		where dia='$DiaElim' and idhora=$Idhora and tmpcod='$TMPCOD' and compania='$Compania[0]'";
		//echo $cons;
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			parent.document.FORMA.NoSubmit.value=1;			
			parent.document.FORMA.submit();;
		</script>
        <?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener2').style.position='absolute';
		parent.document.getElementById('FrameOpener2').style.top='1px';
		parent.document.getElementById('FrameOpener2').style.left='1px';
		parent.document.getElementById('FrameOpener2').style.width='1';
		parent.document.getElementById('FrameOpener2').style.height='1';
		parent.document.getElementById('FrameOpener2').style.display='none';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
	<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
   		<td><input type="text" name="Codigo" style="width:70" value="<? echo $Codigo?>"
        onKeyUp="xLetra(this);frames.NewCupBloqxHorario.location.href='NewCupBloqxHorario.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Codigo='+Codigo.value+'&Restric=<? echo $Restric?>&Nombre='+Nombre.value"
        onKeyDown="xLetra(this)"/></td>
   		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td>		
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
        onkeyup="xLetra(this);frames.NewCupBloqxHorario.location.href='NewCupBloqxHorario.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Codigo='+Codigo.value+'&Restric=<? echo $Restric?>&Nombre='+this.value" onKeyDown="xLetra(this)" /></td>               
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="NewCupBloqxHorario" src="NewCupBloqxHorario.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>" width="100%" height="85%"></iframe>
<?
	if($Cargo && $Nombre)
	{
		?><script language="javascript">
        	frames.NewCupBloqxHorario.location.href="NewCupBloqxHorario.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Restric=<? echo $Restric?>";
        </script><?
	}
?>
</table>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
</form>    
</body>
</html>