<?  if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
//	echo $Estado."<--".$Identificacion."<--".$NoEmpleado;
	$ND=getdate();
	$cons="select identificacion,primnom,segnom,primape,segape,tipodoc,fecnac from central.terceros where identificacion='$Identificacion' and compania='$Compania[0]' and (tipo='Empleado' or regimen='Empleado')";
	$res=ExQuery($cons);
//	echo $cons;
	$fila=ExFetch($res);
//	while()
//	{
		$datos[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);
//	}
//	echo "-->".$fila[6]."<--";
?>
<html>
<head>
<meta http-equiv="refresh" content="60">
<style type="text/css">
<!--
a{color:black;text-decoration:none;}
a:hover{color:yellow}
<?
	if($NoSistema!=1){
?>
body{background-image: url(/Imgs/encabezado.jpg);}<?	}?>
</style>
</head>
<body  bgcolor="<? echo $Estilo[1]?>">
<table width="100%" style="color:yellow" cellspacing="0" style='font-size:11px;text-align:justify;font-family: Tahoma;position:absolute;top:1px;text-align:center'>
<? if($datos)
	{
		foreach($datos as $Auto)
			{ 
				//$edad=ObtenEdad($Auto[6]);
				?>
				<tr align="center" style="color:yellow; font-weight:bold; font-size:20px;">
				<td><? echo "$Auto[1] "; echo "$Auto[2] "; echo "$Auto[3] "; echo "$Auto[4] - ";echo "$Auto[5] # "; echo "$Auto[0]"; ?></td>
                </tr>
                <tr align="center" style="font-size:16px">
                <td>EDAD: <? echo ObtenEdad($Auto[6])." AÑOS";?></td>
                </tr>
                <?
				if($Estado=="Activo")
				{
					$cons1="select cargos.cargo from nomina.cargos,nomina.contratos where contratos.compania='$Compania[0]' and cargos.compania=contratos.compania and tipovinculacion=vinculacion and contratos.cargo=cargos.codigo and identificacion='$Identificacion' and contratos.estado='Activo'";
					$res1=ExQuery($cons1);
					$fila1=ExFetch($res1);
					?>
                    <tr align="center" style="color:yellow; font-size:16px">
                    	<td>CARGO: <? echo $fila1[0];?>
                        </td>
                    </tr>
                    <?
				}
				?>
<?			}
	}?>
</table>
</body>
</html>