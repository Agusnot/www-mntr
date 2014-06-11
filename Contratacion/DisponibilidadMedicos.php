<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<!-- <frameset border="0" rows="25%,*">
	   <frame name="DispoEncabezado" scrolling="no" src="EncDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>" />
       <frame name="DispoDetalles" src="VerDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>"/>
</frameset><noframes></noframes> -->
<script language="javascript">
	location.href="EncDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>";
</script>