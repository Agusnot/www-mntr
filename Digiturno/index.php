<?
	include("Funciones.php");//#000066 
	$ND=getdate();
?>
<HTML>
<HEAD>
</HEAD>

<body bgcolor="#6699FF">
<table border="0" bordercolor="white" align="center" width="100%" height="100%" >
<tr>
<td style="width:100px;">
<iframe src="/Digiturno/HoraAct.php" width="100%" frameborder="0"></iframe>

<object width="750" height="550">
<param name="movie" value="http://www.youtube.com/p/FE3B0C8039F16B3E&hl=en_US&fs=1"></param>
<param name="allowFullScreen" value="true"></param>
<param name="allowscriptaccess" value="always"></param>
<embed src="http://www.youtube.com/p/FE3B0C8039F16B3E&hl=en_US&fs=1" type="application/x-shockwave-flash" width="750" height="550" allowscriptaccess="always" allowfullscreen="true"></embed></object>

</td>

<td><iframe src="SalaEspera.php" width="100%" height="100%" frameborder="0"></iframe></td></tr>

<tr><td style="height:10px;" bgcolor="#000066" colspan="2">
<?

	$cons="Select Msj from digitmensajes Order By Id";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Msj=$Msj.$fila[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

?>

<marquee scrolldelay="10">
<font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:60px;color:#F90">
<? echo $Msj?></font></marquee>

</td></tr>
</table>

</body>


</HTML>