<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if($Eliminar)
	{
		$cons = "Select conname,nspname,conrelid,confrelid,conkey,confkey,pg_class.relname as referencia1,pg2.relname as referencia2,unique_constraint_schema,
                constraint_schema
                from pg_constraint,pg_class,pg_class as pg2,pg_namespace,information_schema.referential_constraints
                where pg_class.oid=conrelid and pg2.oid=confrelid
                and pg_namespace.oid=connamespace
                and pg2.relname = '".strtolower($Tabla)."'
                and constraint_name=conname";
                $res = ExQuery($cons);
                if(ExNumRows($res)>0)
                {
                    while($fila=ExFetch($res))
                    {
                        $campos = substr($fila[4],1,strlen($fila[4])-2);
                        $camposref=explode(",",$campos);
                        $cons1 = "select * from $fila[9].$fila[6] Where Compania='$Compania[0]'";
                        $tablaNE = $fila[6];
                        $res1 = ExQuery($cons1);
                        while($fila1=ExFetch($res1))
                        {
                            foreach($camposref as $camposind)
                            {
                                if(strtolower($fila1[$camposind-1])==strtolower($Nombre))
                                {
                                    $NoEliminar=1;
                                    break;
                                }
                            }
                        }
                    }
                }
                if(!$NoEliminar)
                {
                    $cons = "Delete from Consumo.".$Tabla." where ".$Campo."='$Nombre' and Compania = '$Compania[0]'";
                    $res = ExQuery($cons);
                }
                else{$NE=1;}

	}
?>
<script language="javascript">
	function Validar()
	{
		document.FORMA.action = "NewAdministrarTablasConsumo.php";
		document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" >
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<?
	$cons="Select ".$Campo." from Consumo.".$Tabla." where Compania='$Compania[0]'";
	$res = ExQuery($cons);
	echo ExError();
	echo "<table width='600px' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
		<tr style='font-weight:bold' width='280px' bgcolor='#e5e5e5'><td>Nombre $Campo</td></tr>";
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td>" ?>
        <td width="20px">
        <a href="NewAdministrarTablasConsumo.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Nombre=<? echo $fila[0];?>&Tabla=<? echo $Tabla; ?>&Campo=<? echo $Campo; ?>">
        <img border="0" src="/Imgs/b_edit.png" /></a>
        </td>
		<td width="20px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
        {location.href='AdministrarTablasConsumo.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Tabla=<? echo $Tabla;?>&Campo=<? echo $Campo; ?>&Nombre=<? echo $fila[0];?>';}">
		<img border="0" src="/Imgs/b_drop.png"/></a></td></tr>

	<? } ?>
</table>
<input type="button" name="Nuevo" value="Nuevo" onClick="Validar()"  />
</form>
    <?
    if($NE)
    {
        ?>
        <script language="javascript">
            alert("El item no puede ser borrado, aun se encuentra referenciado desde <? echo $tablaNE?>");
        </script>
        <?
    }
    ?>
</body>