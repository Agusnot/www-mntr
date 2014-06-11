<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Apertura Historias</title>
<style type="text/css"> 

td,th{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	background-image: url(../../Imgs/Fondo.jpg);
	margin-top: 0px;
	margin-bottom: 0px;
} 

</style>

</head>
<body>

<br>

<table BORDER="1" border="1" bordercolor="#e5e5e5" cellpadding="6" align="center" style="font-size:14px; font-family:Tahoma; width:85%; text-align: center;">	
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
            <td colspan="6">PACIENTES EN SALA DE ESPERA</td>        
	</tr>
        
        <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
            <td>NOMBRE DEL PACIENTE</td>
            <td>FECHA DE INGRESO</td>
            <td>USUARIO QUE ENVÍA</td>
            <td>REQUISAR</td>
            <td>ATENCIÓN</td>
        </tr>
        
        <?php 
		$cons0="select cedula,fecha,usuario,requisa,atender from salud.salasintriage where salasintriage.fecha>current_date or estado=1 order by autoid asc";
		$res0=ExQuery($cons0);
		while($fila0=ExFetch($res0)){
	?>
        
	<tr>
            <td>
                <?php
                $cons1="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila0[0]'";
                $res1=ExQuery($cons1);
                $fila1=ExFetch($res1);
                echo"$fila1[0] $fila1[1] $fila1[2] $fila1[2]";
                ?>
            </td>
            <td>
                <?php
                echo"$fila0[1]";
		?>
            </td>
            <td>
                <?php
                $cons2="select nombre from central.usuarios where usuario='$fila0[2]'";
                $res2=ExQuery($cons2);
                $fila2=ExFetch($res2);
                echo"$fila2[0]";
                ?>
            </td>
            <td>
                <?php
                if($fila0[3]==1){
                    echo"Requisado";
                }
                else{
                    $cons3="select cargo from salud.medicos where usuario='$usuario[1]'";
                    $res3=ExQuery($cons3);
                    $fila3=ExFetch($res3);
                    if(($fila3[0]=="AUXILIAR DE ENFERMERIA")||($fila3[0]=="JEFE DE ENFERMERIA")){
		?>
			<a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Requisar</a>
		<?php
                    }
                    else{
                        echo "Requisar";
                    }
		}
		?>
            </td>
            <td>
                <?php
		if($fila0[4]==1){
                    echo"Atendido";
                }
                else{
                    $cons3="select cargo from salud.medicos where usuario='$usuario[1]'";
                    $res3=ExQuery($cons3);
                    $fila3=ExFetch($res3);
                    $cons4="select extra from salud.extraordinario order by fecha desc limit 1";
                    $res4=ExQuery($cons4);
                    $fila4=ExFetch($res4);
                    if($fila4[0]==0){
                        if((($fila3[0]=="MEDICO GENERAL")||($fila3[0]=="MEDICO GENERAL DE URGENCIAS"))&&($fila0[3]==1)){
		?>
                            <a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Atender</a>
		<?php
                        }
			else{
                            echo "Atender";
			}
                    }
                    if($fila4[0]==1){
                        if((($fila3[0]=="MEDICO GENERAL")||($fila3[0]=="MEDICO GENERAL DE URGENCIAS")||($fila3[0]=="INTERNO")||($fila3[0]=="RESIDENTE")||($fila3[0]=="PSIQUIATRA"))&&($fila0[3]==1)){
		?>
                            <a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Atender</a>
		<?php
                        }
                        else{
                            echo "Atender";
                        }
                    }
                }
		?>
            </td>	
        </tr>
	<?php
            }
	?>
</table>
</body>
</html>