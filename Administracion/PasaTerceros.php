<?
	session_start();
	$conex2 = mysql_connect("localhost", "root", 'Server*1492');
	$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<?
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,RepLegal,Direccion,Telefono,Pais,Departamento,Municipio,Tipo,Regimen,AutoReteFte,AutoReteIVA,Email 
	from Contabilidad.Terceros";
	$res=mysql_query($cons);
	while($fila=mysql_fetch_row($res))
	{

	$cons2="Insert into Central.Terceros (identificacion, primape, segape, primnom, segnom, replegal, direccion, 
            telefono, pais, departamento, municipio, tipo, regimen, autoretefte, 
            autoreteiva, email, compania)
			values('$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]',NULL,NULL,'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','Hospital San Rafael de Pasto')";
		$res2=pg_query($cons2);
	}
?>