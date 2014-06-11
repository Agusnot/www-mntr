<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
</style></head>&nbsp; 

<?	$raiz=$_SERVER['DOCUMENT_ROOT'];
//	$X="/Fotos/Empleados/$Identificacion.jpg";
//	echo $X;
//	echo getcwd();
//	echo basename(getcwd());
//	echo $_SERVER['DOCUMENT_ROOT'];
//      echo $raiz;
        $Direc="$raiz/Fotos/Empleados/$Identificacion.jpg";
        //echo $Direc;
	if($Emp){$Emp[1]=$Identificacion;}
	if (is_file("$Direc"))
	//if(file_exists("/Fotos/Pacientes/".$Emple.".jpg"))
	{
		echo "<img src=/Fotos/Empleados/$Identificacion.jpg style='width:132px;' height'146px;'>";
		echo "<img src=/Fotos/Empleados/$Identificacion.JPG style='width:132px;' height'146px;'>";
	}
	else
	{
		echo "<img src='/Imgs/Logo.jpg' style='width:132px;left:35px;position:absolute' height'146px;'>";
	}
?>


<body  bgcolor="<? echo $Estilo[1]?>">
</body>
</html>
