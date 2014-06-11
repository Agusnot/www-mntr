<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($No)
	{?>
		<script language="javascript">
			window.close()
		</script>
<?	}
	if($Si)
	{

		$cons="Delete from Central.Compania where Nombre='$Entidad'";
		$res=ExQuery($cons);
		if(ExError())
		{
			echo "<font color='red'><em>No puede eliminar la compania!</font>";
		}
		else
		{
		$NumRegs=$NumRegs+ExAfectedRows($res);
		
		?>
        <script language="javascript">
			alert("Proceso Finalizado. \n <? echo $NumRegs?> Registros Afectados!!!");
			window.close();
			opener.location.href=opener.location.href;
		</script>
        <?
		}
	}
?>
<head>
<title>Compuconta Software</title></head>
<center>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<p><font size="4"><em><br />
  Para adelantar este proceso,<br>
  la compa&ntilde;ia no debe contener registros. <br />
Realmente Desea eliminar toda la compania?</font><font size="4"><br />
  </font></em><br />
  <input type="submit" name="Si" value="Si" style="width:70px;">
  <input type="submit" name="No" value="No" style="width:70px;">
  <input type="hidden" name="Entidad" value="<? echo $Entidad?>" />
</p>
</body>
</form>