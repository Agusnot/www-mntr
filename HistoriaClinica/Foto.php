<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	if($AjustarCedula)
	{
		sleep(2);
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/Fotos/FOTO.JPG"))
		{
			copy($_SERVER['DOCUMENT_ROOT']."/Fotos/FOTO.JPG",$_SERVER['DOCUMENT_ROOT']."/Fotos/Pacientes/$Paciente[1].JPG");
			unlink($_SERVER['DOCUMENT_ROOT']."/Fotos/FOTO.JPG");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title><style type="text/css">
<!--
<?
	if($NoSistema!=1){
?>
body {
	background-image: url(/Imgs/HistoriaClinica/Foto.jpg);
}<?	}?>
-->
</style></head>&nbsp; 

<?	if($Pacie){$Paciente[1]=$Pacie;}
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/Fotos/Pacientes/$Paciente[1].JPG"))
	{
		echo "&nbsp;&nbsp;<img src='/Fotos/Pacientes/$Paciente[1].JPG' style='width:132px;' height'146px;'>";
	}
	else
	{
		echo "<img src='/Imgs/Logo.jpg' style='width:132px;left:35px;position:absolute' height'146px;'>";
	}
	if($AjustarCedula)
	{?>
		<script language="javascript">
			location.href="Foto.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Paciente[1]?>";
		</script>
<?	}
?>


<body  bgcolor="<? echo $Estilo[1]?>">
</body>
</html>
