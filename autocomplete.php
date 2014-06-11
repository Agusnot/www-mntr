<?php
include "adodb5/adodb5/adodb-active-record.inc.php";
include "adodb5/adodb5/adodb.inc.php";
include "xajax/xajax_core/xajax.inc.php";
include "conexion.php";
ADOdb_Active_Record::SetDatabaseAdapter($con);
include "estudiante.php";
$xajax= new xajax();
//--------
function autocompleta($input)
{
global $con;
$respuesta = new xajaxResponse();
$con = "SELECT Nombre FROM paises WHERE Nombre LIKE '".$input."%' ";
$res = mysql_query($con);
$num = mysql_num_rows($res);
if ($input == "")
        {  $respuesta->Assign("divSugerencias", "innerHTML", "");
           return $respuesta;
        }
if ($num == 0) 
               { $output = "<font color='red'>No existe ningun dato con ese nombre</font>";
               }			   
else if ($num == 1)  
                   { $row = mysql_fetch_row($res);
                       if (strcasecmp($input, $row[0]) == 0)
                                 { 
                                     $output = "";
                                 }
else   { 
 $output = " <div id='divLista'> <table > <tr> <td
onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
[0]."</td> </tr> </div> </table>";
       }
                     }
else{ 
$output .= "<div id='divLista'> <table  cellpadding='0'cellspacing='0'>";
while ($row = mysql_fetch_row($res))  
 { $output .= "<tr><td
onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
[0]."</td></tr>";
                                       }
$output .= "</div></table>";
    }

$respuesta->Assign("divSugerencias", "innerHTML", $output);
return $respuesta;
}
//----
function seleccion($pais){
$respuesta = new xajaxResponse();
$respuesta->Assign("pais_est", "value", $pais);
return $respuesta;
}
// fin segunda funcion
$xajax->registerFunction('autocompleta');
$xajax->registerFunction('seleccion');
$xajax->processRequest();
?>
<html>
<head>
<?php 
$xajax->printJavascript("xajax/");
?>
<style type="text/css">
#divLista{
position:absolute;
left: 9px;
width:265px;
height:100px;
overflow:auto;
border:solid 1px #ccc;
background-color:#e4f1fb;
}
</style>
</head>
<body>
<input name="textPais" type="text"  id="pais_est"
onkeyup="xajax_autocompleta(this.value)"  size="40"/>
              <div id="divSugerencias" style="margin-top:3px;"></div>

</body>
</html>