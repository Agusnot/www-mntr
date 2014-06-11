<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");	
	$ND=getdate();
	
?>
<html>
<head>
<script language="javascript">	
	function CerrarThis()
	{
		parent.parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.parent.document.getElementById('FrameFondo').style.top='1px';
		parent.parent.document.getElementById('FrameFondo').style.left='1px';
		parent.parent.document.getElementById('FrameFondo').style.width='1';
		parent.parent.document.getElementById('FrameFondo').style.height='1';
		parent.parent.document.getElementById('FrameFondo').style.display='none';
		
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		
		//parent.document.FORMA.submit();
		parent.parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.parent.document.getElementById('FrameOpener').style.top='1px';
		parent.parent.document.getElementById('FrameOpener').style.left='1px';
		parent.parent.document.getElementById('FrameOpener').style.width='1';
		parent.parent.document.getElementById('FrameOpener').style.height='1';
		parent.parent.document.getElementById('FrameOpener').style.display='none';
	}	
	function CambiaChk(Num)
	{
		if(document.getElementById("Programas["+Num+"]").checked==true){			
			document.getElementById("AuxProg["+Num+"]").value=1;
		}
		else{
			document.getElementById("AuxProg["+Num+"]").value='x';
		}
		//alert(document.getElementById("AuxProg["+Num+"]").value);
	}
</script>
<?
if($Guardar){
	//$cons="delete from  pyp.ususariosvinculados where compania='$Compania[0]' and identificacion='$Paciente[1]'";
	//$res=ExQuery($cons);	
	$cons="select numusu from pyp.ususariosvinculados where compania='$Compania[0]' order by numusu desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumUsu=$fila[0]+1;	
	if($AuxProg){
		$cad=''; $val='';
		while( list($cad,$val) = each($AuxProg)){		
			//echo "$cad .... $val<br>";
			if($val!='x'){	
				if($ProgOriginal[$cad]=='x'){		
					$cons="insert into pyp.ususariosvinculados (compania,usuario,fecha,identificacion,numprograma,entidad,fechaing,numusu) values
				   ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$cad,'$Entidad','$ND[year]-$ND[mon]-$ND[mday]',$NumUsu)";	
					//echo $cons."<br>";			
					$res=ExQuery($cons);
					$NumUsu++;
				}
			}
			else{
				if($ProgOriginal[$cad]!='x'){
					$cons="update pyp.ususariosvinculados set fechaegr='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where compania='$Compania[0]'
					and identificacion='$Paciente[1]' and numprograma=$cad and fechaegr is null";
					$res=ExQuery($cons);
					//echo $cons."<br>";
				}
			}
		}
	}
	if(!$CodDx){
		?><script language="javascript">parent.document.FORMA.submit();</script><?
	}
	else{?>
		<script language="javascript">
		CerrarThis();       
        </script>
<?	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<? 
if($Sexo=="M"){$Sex="and (sexo='Hombre' or sexo='Ambos')";}else{$Sex="and (sexo='Mujer' or sexo='Ambos')";}
$cons="select nombre,programa,sexo,programas.numprograma from contratacionsalud.pypcontratos,pyp.programas where pypcontratos.compania='$Compania[0]' and programas.compania='$Compania[0]' 
and entidad='$Entidad' and programas.numprograma=pypcontratos.programa $Sex
group by nombre,programa,sexo,programas.numprograma";
//echo $cons;
$res=ExQuery($cons);
if(ExNumRows($res)>0){ ?>
    <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">	
        <tr style="font-weight:bold" align="center">
            <td bgcolor="<? echo $Estilo[1]?>" style="color:white">Programas P y P</td>            
        </tr>
    <?	$ban=0;
        while($fila=ExFetch($res)){
            $cons3="select cup from pyp.cupsxprogramas where compania='$Compania[0]' and numprograma=$fila[1] and ($Edad>=edadini or edadini=-1) 
            and ($Edad<=edadfin or edadfin=-1)";	
            $res3=ExQuery($cons3);
                    
            if(ExNumRows($res3)>0){			
                if($CodDx){
                    $cons4="select dx from pyp.dxprogramas where compania='$Compania[0]' and numprograma=$fila[1] and dx='$CodDx'";
                    $res4=ExQuery($cons4);
                    if(ExNumRows($res4)>0){
                        $ban=1;
                        $cons2="select numprograma from pyp.ususariosvinculados where compania='$Compania[0]' and identificacion='$Paciente[1]' and entidad='$Entidad' and numprograma=$fila[1]
                        and fechaegr is null";
                        $res2=ExQuery($cons2);?>
                        <tr>
                            <input type="hidden" name="AuxProg[<? echo $fila[1]?>]" id="AuxProg[<? echo $fila[1]?>]" <? if(ExNumRows($res2)>0){?>  value="1"<? }else{?> value="x"<? }?>>
                            <td><input type="checkbox" name="Programas[<? echo $fila[1]?>]" id="Programas[<? echo $fila[1]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>
                            onClick="CambiaChk('<? echo $fila[1]?>')"> <? echo $fila[0]?></td>                
                            <input type="hidden" name="ProgOriginal[<? echo $fila[1]?>]" 
                            id="ProgOriginal[<? echo $fila[1]?>]"<? if(ExNumRows($res2)>0){?>  value="<? echo $fila[1]?>"<? }else{?> value="x"<? }?>>
                        </tr>
            <?		}
                }
                else{
                    $ban=1;
                    $cons2="select numprograma from pyp.ususariosvinculados where compania='$Compania[0]' and identificacion='$Paciente[1]' and entidad='$Entidad' and numprograma=$fila[1]
                    and fechaegr is null";
                    $res2=ExQuery($cons2);?>
                    <tr>
                        <input type="hidden" name="AuxProg[<? echo $fila[1]?>]" id="AuxProg[<? echo $fila[1]?>]" <? if(ExNumRows($res2)>0){?>  value="1"<? }else{?> value="x"<? }?>>
                        <td><input type="checkbox" name="Programas[<? echo $fila[1]?>]" id="Programas[<? echo $fila[1]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>
                        onClick="CambiaChk('<? echo $fila[1]?>')"> <? echo $fila[0]?></td>                
                        <input type="hidden" name="ProgOriginal[<? echo $fila[1]?>]" 
                        id="ProgOriginal[<? echo $fila[1]?>]"<? if(ExNumRows($res2)>0){?>  value="<? echo $fila[1]?>"<? }else{?> value="x"<? }?>>
                    </tr>
    <?			}
                
            }
        }?>     
        <input type="checkbox" style="visibility:hidden" name="Programas" value="9999" checked>  
    <?	if($ban==0){?>   
            <script language="javascript">CerrarThis();</script>
            <tr align="center">
                <td>No hay programas de Promocion y Prevencion a los cuales el paciente pueda ingresar</td>
            </tr>
    <?	}?>
        <tr align="center">
            <td><input type="submit" value="Aceptar" name="Guardar"></td>
        </tr>
    </table><?
}
else{ ?>  
	 <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">	
        <tr style="font-weight:bold" align="center">
            <td bgcolor="#e5e5e5" style=" font-weight:bold" align="center">Aun No Se Han Creado Programas de P y P</td>            
        </tr>
        <tr>
	<?	if($HistoC){?>        
    		<td align="center"><input type="button" value="Aceptar" onClick="CerrarThis();"></td>
    <?	}
    	else{?>            
                <td align="center"><input type="button" value="Aceptar" onClick="parent.document.FORMA.submit();"></td>            
   	<?	}?>
	    </tr>
   	</table>  
<?
}?>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NoProgramas">
<input type="hidden" name="CodDx" value="<? echo $CodDx?>">
</form>
</body>
</body>
</html>
