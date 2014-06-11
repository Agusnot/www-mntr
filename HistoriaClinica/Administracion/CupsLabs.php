<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="delete from historiaclinica.cupslabs where compania='$Compania[0]' and cup='$Eliminar' and formato='$NewFormato' 
		and tipoformato='$TF'";	
		$res=ExQuery($cons);
		$Eliminar="";
	}
?>	
<script language='javascript' src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5" cellpadding="5" style="font-family:Tahoma; font-size:11px;">        
<tr style="color:white; font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>">   
<td colspan="2"><center><div class="style3">CUPS DE LABORATORIO LIGADOS AL FORMATO</div></center></td>	
<tr align="center">
	<td colspan="2">
    	<input type="button" value="Nuevo" onClick="location.href='NewCupsLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
    </td>
</tr>
</tr>
<?	$cons="select cup,nombre from historiaclinica.cupslabs,contratacionsalud.cups 
	where cupslabs.compania='$Compania[0]' and cups.codigo=cup and formato='$NewFormato' and tipoformato='$TF' order by cup";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{?>
		<tr bgcolor="white">
        	<td><? echo "$fila[0] - $fila[1]";?></td>
            <td>
            	<img src="/Imgs/b_drop.png" title='Eliminar' style="cursor:hand"
                onClick="if(confirm('Esta seguro de eliminar este registro?')){
                	location.href='CupsLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Eliminar=<? echo $fila[0]?>'
                }">
            </td>
        </tr>
<?	}?>        
</form>
</body>
