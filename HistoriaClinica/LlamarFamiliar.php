<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Egreso Pacientes</title>
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
<form name="FORMA" method="post" action="/HistoriaClinica/LlamarFamiliar.php?DatNameSID=<?php echo $DatNameSID; ?>">
    <table BORDER="1" border="1" bordercolor="#e5e5e5" cellpadding="6" align="center" style="font-size:14px; font-family:Tahoma; width:85%; text-align: center;">	
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
            <td colspan="6">PACIENTES PENDIENTES POR EGRESO</td>        
	</tr>
       
        <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
            <td>DOCUMENTO DEL PACIENTE</td>
            <td>NOMBRE DEL PACIENTE</td>
            <td>FECHA DE SALIDA</td>
            <td>QUIEN AUTORIZA </td>
            <td>ATENCI&Oacute;N</td>
        </tr>
        
        <?php
           
            $cons0="SELECT 
			a.cedula, 
			a.fecha, 
			a.usuario, 
			CONCAT(primnom, ' ', b.segnom, ' ', b.primape, ' ', b.segape) as nombre 
			FROM salud.ordenesmedicas as a, 
			central.terceros as b, 
			salud.servicios as c
			WHERE a.tipoorden = 'Orden Egreso' 
			AND a.estado = 'AC' 
			AND b.identificacion = a.cedula 
			AND a.numservicio = c.numservicio 
			AND c.estado = a.estado 
			AND a.numservicio NOT IN (SELECT d.numservicio FROM  histoclinicafrms.tbl00030 as d
			WHERE a.cedula = d.cedula AND a.numservicio = d.numservicio AND d.cmp00001 = 'SI')";
            
            $res0=ExQuery($cons0);
            while($fila0=ExFetch($res0)){
	?>
        
	<tr>
            <td>
            <?php
                    echo"$fila0[0]";
            ?>
            </td>
            <td>
            <?php
                    echo"$fila0[3]";
            ?>
            </td>
            
            <td>
		   <?php
                    echo"$fila0[1]";
            ?>
            </td>				
            <td>
		   <?php
                    echo"$fila0[2]";
            ?>
            </td>
            <td>
            <?php
            
                   
            ?>
              <a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>&at_urgencias=1" target="_parent">Llamar a Familiar</a>
            <?php
		
                    
		}
            ?>
            </td>	
        </tr>
            
    </table>
</form>
</body>
</html>