<HTML>
<FRAMESET ROWS="75,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&MesI=<? echo $MesI?>&AnioI=<? echo $AnioI?>&Tipo=<? echo $Tipo?>&Origen=<? echo $Origen;?>"  
   NAME="opciones" marginheight=8 marginwidth=8 SCROLLING=no>
   <FRAME <? if($Tipo != "Traslados" && $Tipo!="Bajas"){$Lista = "ListaMovimiento.php";} 
   if($Tipo=="Traslados"){$Lista = "ListaTraslados.php";}
   if($Tipo=="Bajas"){$Lista = "ListaBajas.php";} ?> 
   SRC="<? echo $Lista?>?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>&MesI=<? echo $MesI?>&AnioI=<? echo $AnioI?>&DiaI=<? echo $DiaI?>&Numero=<? echo $Numero?>&Detalle=<? echo $Detalle?>"  NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>
</HTML>
