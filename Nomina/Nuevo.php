<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
 <html> 
<head> 
 <script language="JavaScript"> 

  var aFinMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 

  function finMes(nMes, nAnio){ 
   return aFinMes[nMes - 1] + (((nMes == 2) && (nAnio % 4) == 0)? 1: 0); 
  } 

   function padNmb(nStr, nLen, sChr){ 
    var sRes = String(nStr); 
    for (var i = 0; i < nLen - String(nStr).length; i++) 
     sRes = sChr + sRes; 
    return sRes; 
   } 

   function makeDateFormat(nYear,nMonth,nDay){ 
    var sRes; 
    sRes = padNmb(nYear, 4, "0") + "-" + padNmb(nMonth, 2, "0") + "-" + padNmb(nDay, 2, "0");
     return sRes; 
   } 
    
  function incDate(sFec0){ 
   var nDia = parseInt(sFec0.substr(8, 2), 10); 
   var nMes = parseInt(sFec0.substr(5, 2), 10); 
   var nAnio = parseInt(sFec0.substr(0, 4), 10); 
   nDia += 1; 
   if (nDia > finMes(nMes, nAnio)){ 
    nDia = 1; 
    nMes += 1; 
    if (nMes == 13){ 
     nMes = 1; 
     nAnio += 1; 
    } 
   } 
   return makeDateFormat(nAnio,nMes,nDia); 
  } 

  function decDate(sFec0){ 
   var nDia = Number(sFec0.substr(0, 2)); 
   var nMes = Number(sFec0.substr(3, 2)); 
   var nAnio = Number(sFec0.substr(6, 4)); 
   nDia -= 1; 
   if (nDia == 0){ 
    nMes -= 1; 
    if (nMes == 0){ 
     nMes = 12; 
     nAnio -= 1; 
    } 
    nDia = finMes(nMes, nAnio); 
   } 
   return makeDateFormat(nAnio,nMes,nDia); 
  } 

  function addToDate(sFec0, sInc){ 
   var nInc = Math.abs(parseInt(sInc)); 
   var sRes = sFec0; 
   if (parseInt(sInc) >= 0) 
    for (var i = 0; i < nInc; i++) sRes = incDate(sRes); 
   else 
    for (var i = 0; i < nInc; i++) sRes = decDate(sRes); 
   return sRes; 
  } 

  function recalcF1(){    
   if(document.formulario.fecha0.value!=''&&document.formulario.increm.value!=''&&parseInt(document.formulario.increm.value)>=0)
   { 
    	document.formulario.fecha1.value = addToDate(document.formulario.fecha0.value, document.formulario.increm.value); 
   }  
  } 

 </script> 
</head> 
<body> 
 <form name="formulario"> 
  <table> 
   <tr> 
    <td align="right"> 
     Fecha (dd/mm/aaaa): 
    </td> 
    <td> 
     <input type="text" name="fecha0" size="10" onKeyUp="recalcF1()" onChange="recalcF1()"> 
    </td> 
   </tr> 
   <tr> 
    <td align="right"> 
     Incremento: 
    </td> 
    <td> 
     <input type="text" name="increm" size="3" onKeyUp="recalcF1()"  onChange="recalcF1()"> 
    </td> 
   </tr> 
   <tr> 
    <td align="right"> 
     Resultado (dd/mm/aaaa): 
    </td> 
    <td> 
     <input type="text" name="fecha1" disabled size="10"> 
    </td> 
   </tr> 
   <tr> 
    <td colspan="2" align="center"> 
     <input type="button" onClick="recalcF1()" value="Calcular"> 
    </td> 
   </tr> 
  </table> 
 </form> 
</body> 
</html>  