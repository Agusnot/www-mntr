<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	@require_once ("xajax/xajax_core/xajax.inc.php");
//----- funcion actualizar
    function ActualizaMpo($Dpto){
		$respuesta=new xajaxResponse();
		$respuesta->addScript("alert('Respondo!');");
		return $respuesta->getXML();
	}
	$obj=new xajax();
	$obj->configure('javascript URI','xajax/');//
	$obj->registerFunction("ActualizaMpo");
//-------------------------
        $ND=getdate();

//---- boton guardar
	if($Guardar)
	{
		$FecNac=$AnioNac."-".$MesNac."-".$DiaNac;
//		echo $FecNac;
	 /*	if($PermiteCrear)
                {
                    $cons="select identificacion from central.terceros where Compania='$Compania[0]' and Identificacion='$Identificacion'";
                    $res=ExQuery($cons);
                    $Cont=ExNumRows($res);
                    if(ExNumRows($res)==0)
                    {		
                            $cons="insert into central.terceros(identificacion,primnom,segnom,primape,segape,lugarexp,pais,departamento,municipio,direccion,compania,email,tipo) values('$Identificacion','$PrimNom','$SegNom','$PrimApe','$SegApe','$LugarExp','$Pais','$Departamento','$Mpo','$Direccion','$Compania[0]','$Email','Empleado')";
                            $res=ExQuery($cons);
                            ?> <script language="javascript">parent.location.href='/Nomina/HojadeVida.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion ?>';</script><?
                    }
                    else
                    {
                        ?><script languaje="javascript">alert("El Empleado ya se encuentra registrado");</script>
                        <script language="javascript">parent.location.href='/Nomina/HojadeVida.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion ?>';</script>
                         <?
                    }
                    
                }
                else
                {*/
                    $cons="select identificacion from central.terceros where Compania='$Compania[0]' and Identificacion='$Identificacion'";
                    $res=ExQuery($cons);
                    $Cont=ExNumRows($res);
              /*      if(ExNumRows($res)==0)
                    {		
                            $cons="insert into central.terceros(identificacion,primnom,segnom,primape,segape,lugarexp,pais,departamento,municipio,direccion,compania,email,tipo) values('$Identificacion','$PrimNom','$SegNom','$PrimApe','$SegApe','$LugarExp','$Pais','$Departamento','$Mpo','$Direccion','$Compania[0]','$Email','Empleado')";
                            $res=ExQuery($cons);	
                    }
                    else
                    {*/
                            $cons="update central.terceros set primnom='$PrimNom',segnom='$SegNom',primape='$PrimApe',segape='$SegApe',lugarexp='$LugarExp',pais='$Pais',departamento='$Departamento',municipio='$Mpo',direccion='$Direccion',Email='$Email',fecnac='$FecNac',tiposangre='$TipoSangre',sexo='$Sexo',ecivil='$EstCivil',telefono='$Telefono',nomcontacto='$Representante',telcontacto='$TelRepresentante',parentcontacto='$Parentesco',usuariomod='$usuario[0]',dircontacto='$DirContacto',ciudcontacto='$CiudContacto' where compania='$Compania[0]' and identificacion='$Identificacion'";							
                            $res=ExQuery($cons);
                    //}                  
                //}
                //echo "guarda";
		
	}
//-----------------comienza la consulta	
        //echo $Identificacion;
	$cons="select identificacion,primnom,segnom,primape,segape,tipodoc,lugarexp,pais,departamento,municipio,direccion,compania,email,fecnac,tiposangre,sexo,ecivil,telefono,nomcontacto,telcontacto,parentcontacto,dircontacto,ciudcontacto from central.terceros where Compania='$Compania[0]' and identificacion='$Identificacion' and (tipo='Empleado' or regimen='Empleado')";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$Identificacion){$Identificacion=$fila[0];}
	if(!$PrimNom){$PrimNom=$fila[1];}
	if(!$SegNom){$SegNom=$fila[2];}
	if(!$PrimApe){$PrimApe=$fila[3];}
	if(!$SegApe){$SegApe=$fila[4];}
	if(!$TipoDoc){$TipoDoc=$fila[5];}
	if(!$LugarExp){$LugarExp=$fila[6];}
	if(!$Pais){$Pais=$fila[7];}
	if(!$Departamento){$Departamento=$fila[8];}
	if(!$Mpo){$Mpo=$fila[9];}
	if(!$Direccion){$Direccion=$fila[10];}
	if(!$Compania0){$Compania0=$fila[11];}
	if(!$Email){$Email=$fila[12];}
	if(!$FecNac){$FecNac=$fila[13];}
	if(!$AnioNac){$AnioNac=substr($fila[13],0,4);$MesNac=substr($fila[13],5,2);$DiaNac=substr($fila[13],8,2);}
	if(!$TipoSangre){$TipoSangre=$fila[14];}
	if(!$Sexo){$Sexo=$fila[15];}
	if(!$EstCivil){$EstCivil=$fila[16];}
	if(!$Telefono){$Telefono=$fila[17];}
	if(!$Representante){$Representante=$fila[18];}
	if(!$TelRepresentante){$TelRepresentante=$fila[19];}
	if(!$Parentesco){$Parentesco=$fila[20];}
	if(!$DirContacto){$DirContacto=$fila[21];}
	if(!$CiudContacto){$CiudContacto=$fila[22];}
//	echo $AnioNac;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="/calendario/Calendar/calendar-win2k-cold-1.css" title="win2k-cold-1"/>
<? $obj->printJavascript("/xajax");?>
<script language="javascript" src="/Funciones.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-es.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-setup.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function ConfigCal(Campo)
{
	//alert(Campo.name)
	Calendar.setup({
	inputField     :    Campo.name, 	      
	ifFormat       :    "%Y-%m-%d",       
	showsTime      :    true,            
	//button         :    "calendario",   
	singleClick    :    false,           
	step           :    1                
	});	
}
function Validar()
{
   if(document.FORMA.Identificacion.value==""){alert("Por favor ingrese el documento de identificacion!!!");document.FORMA.Identificacion.focus();return false;}
   if(document.FORMA.LugarExp.value==""){alert("Por favor ingrese el Lugar de Expedicion!!!");document.FORMA.LugarExp.focus();return false;}
   if(document.FORMA.PrimNom.value==""){alert("Por favor ingrese el Primer Nombre!!!");document.FORMA.PrimNom.focus();return false;}      
   if(document.FORMA.PrimApe.value==""){alert("Por favor ingrese el Primer Apellido!!!");document.FORMA.PrimApe.focus();return false;}   
   if(document.FORMA.TipoSangre.value==""){alert("Por favor ingrese el Tipo de Sangre!!!");document.FORMA.TipoSangre.focus();return false;}   
   if(document.FORMA.Sexo.value==""){alert("Por favor ingrese el Sexo!!!");document.FORMA.Sexo.focus();return false;}
   if(document.FORMA.EstCivil.value==""){alert("Por favor el estado civil!!");document.FORMA.EstCivil.focus();return false;}
   if(document.FORMA.Pais.value==""){alert("Por favor ingrese el Pais de Ubicacion!!!");document.FORMA.Pais.focus();return false;}
   if(document.FORMA.Departamento.value==""){alert("Por favor ingrese el Departamento de Ubicacion!!!");document.FORMA.Departamento.focus();return false;}
   if(document.FORMA.Mpo.value==""){alert("Por favor ingrese el Municipio de Ubicacion!!!");document.FORMA.Mpo.focus();return false;}
   if(document.FORMA.Direccion.value==""){alert("Por favor ingrese la Direccion de Residencia!!!");document.FORMA.Direccion.focus();return false;}   
 //  if(document.FORMA.Email.value==""){alert("Por favor ingrese el E-Mail!!!");document.FORMA.Email.focus();return false;}
//   result=valEmail(document.FORMA.Email.value);
   
}
function validarvalor(Valor)
{	
	var Fec = Valor.value.split('-');
	if(Fec[0]>9999)
	{
		alert("El Año no debe ser mayor de 9999");
		document.FORMA.FecInicio.focus();
	}
	if(Fec[1]>12)
	{
		alert("El Mes no debe ser mayor de 12");
		document.FORMA.FecInicio.focus();
	}
	if(Fec[2]>31)
	{
		alert("El Dia no debe ser mayor de 31");
		document.FORMA.FecInicio.focus();
	}
}
function valEmail(valor)
{
    re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
	result=re.exec(valor);
	if(!result){alert('email no valido');document.FORMA.Email.focus();return false;}
//	document.write("<br />Returned value: " + result + "=" + valor );
	/*if(!result)
	{
		alert("este Correo Electronico No Es Valido");		
	}*/
	return result;
	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
    <input type="hidden" name="PermiteCrear" value="<? echo $PermiteCrear?>">
	<input type="hidden" name="AnioNac" value="<? echo $AnioNac?>">
    <input type="hidden" name="MesNac" value="<? echo $MesNac?>">
    <input type="hidden" name="DiaNac" value="<? echo $DiaNac?>">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="60%">
		<tr>
		<td colspan=7 bgcolor="#666699" style="color:white" align="center">FICHA DE IDENTIFICACION</td>
		</tr>
		<tr>
        <td>No De Identificacion</td>
			<td colspan="3"><input type="text" name="Identificacion"  value="<? echo $Identificacion;?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" <? if(!$PermiteCrear){echo "ReadOnly";}?> style="width:100%" /> </td>
            <td>Lugar de Expedicion <font color="red">*</font></td>
            <td><input type="text" name="LugarExp" value="<? echo $LugarExp;?>" style="width:100%"></td>
		</tr>
        <tr>
        	<td>Primer Nombre <font color="red">*</font></td>
            <td colspan="3"><input type="text" name="PrimNom"  value="<? echo $PrimNom;?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" style="width:100%" \></td>
            <td>Segundo Nombre</td>
            <td><input type="text" name="SegNom" value="<? echo $SegNom;?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" style="width:100%"></td>
        </tr>
        <tr>
        	<td>Primer Apellido <font color="red">*</font></td>
            <td colspan="3"><input type="text" name="PrimApe" value="<? echo $PrimApe;?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" style="width:100%"></td>
            <td>Segundo Apellido</td>
            <td><input type="text" name="SegApe" value="<? echo $SegApe;?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" style="width:100%"></td>
        </tr>
        <tr>
        	<td>Fecha de Nacimiento <font color="red">*</font><br><font color="#000099" size="-4">AAAA-MM-DD</font></td>
<!--            <td colspan="3"><input type="text" readonly name="FecNac"  onFocus="ConfigCal(this);" onClick="popUpCalendar(this, this, 'yyyy-mm-dd')" style="width:auto" value="<? // echo 								$FecNac?>"></td> -->
            
            <td><select name="AnioNac" style="width:55px" onChange="FORMA.submit()">
            	<option></option>
                <?
                    $cons = "select Anio from central.anios where compania='$Compania[0]' order by anio desc";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($AnioNac==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                    }
				?>
                </select>
            </td>
            <td><select name="MesNac" style="width:100px" onChange="FORMA.submit()">
            	<option></option>
                <?
                    $cons = "select numero,mes,numdias from central.meses order by numero asc";
                    $resultado = ExQuery($cons);
                    while ($filaM = ExFetch($resultado))
                    {                        
						if($MesNac==$filaM[0])
                        {
                            echo "<option value='$filaM[0]' selected>$filaM[1]</option>";
							$dias=$filaM[2];
                        }
                        else
                        {
                            echo "<option value='$filaM[0]'>$filaM[1]</option>";
                        }
                    }
				?>
                </select>
            </td>
            <td><select name="DiaNac" style="width:50px">
            <option></option>
            <?
			$I=1;
			while($I<=$dias)
			{
				if($DiaNac==$I)
				{
					echo "<option value='$I' selected>$I</option>";
				}
				else
				{
					echo "<option value='$I'>$I</option>";
				}
				$I++;
			}
			?>
            </select>
            </td>
        	<td>Tipo de Sangre<font color="red">*</font></td>
            <td><select name="TipoSangre" style="width:100%">
            <option ></option>
                    <?
                    $cons = "select tiposangre from central.tipossangre";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($TipoSangre==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                    }
				?>
            </select></td>
        </tr>
        <tr>
        	<td>Sexo<font color="red">*</font> </td>
            <td colspan="3"><select name="Sexo" style="width:100%">
            <option ></option>
                    <?
                    $cons = "select codigo,sexo from central.listasexo";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($Sexo==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[1]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[1]</option>";
                        }
                    }
				?>
            </select></td>
        	<td>Estado Civil<font color="red">*</font></td>
            <td><select name="EstCivil" style="width:100%">
            <option ></option>
                    <?
                    $cons = "select estadocivil from central.estadosciviles";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($EstCivil==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                    }
				?>
            </select></td>
        </tr>
        <tr>
        	<td>Pais <font color="red">*</font></td>
            <td colspan="3"><input type="text" name="Pais" value="<? echo $Pais;?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" style="width:100%"></td>
            <td>Departamento <font color="red">*</font></td>
            <td>
            <select name="Departamento" style="width:100%;" onChange="xajax_ActualizaMpo(this.value);BuscaMpo.location.href='BuscarMunicipios.php?Dep='+this.value+'&DatNameSID=<? echo $DatNameSID?>'"/>
            <option ></option>
                    <?
                    $cons = "select Codigo,Departamento from Central.Departamentos Order By Departamento";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($Departamento==$fila[1])
                        {
                            echo "<option value='$fila[1]' selected>$fila[1]</option>";
							//$Dep=$fila[0];
                        }
                        else
                        {
                            echo "<option value='$fila[1]'>$fila[1]</option>";
                        }
                  }
				?>
            </select>
			</td>
        </tr>
        <tr>
        	<td>Municipio<font color="red">*</font></td>
                <td colspan="3">
                <select name="Mpo" style="width:100%" >
                <option></option>
                <?php
                                $cons = "SELECT municipio,codmpo FROM Central.Municipios,Central.Departamentos
								where Departamentos.Departamento='$Departamento' and Departamentos.Codigo=Municipios.Departamento 
								order by codmpo";
								//echo $cons;
                                $resultado = ExQuery($cons);
                                while ($fila = ExFetch($resultado))
                                {
                                        if($Mpo==$fila[0])
                                        {
                                                echo "<option value='$fila[0]' selected>$fila[0]</option>";
                                        }
                                        else
                                        {
                                                echo "<option value='$fila[0]' >$fila[0]</option>";
                                        }
                                }?>
                </select>

</td>
            <td>Direccion <font color="red">*</font></td>
            <td><input type="text" name="Direccion" value="<? echo $Direccion;?>" style="width:100%"></td>
        </tr>
        <tr>
        	<td>Telefono</td>
            <td colspan="3"><input type="text" name="Telefono" value="<? echo $Telefono;?>" style="width:100%"></td>
        <!--	<td>Telefono Celular</td>
            <td><input type="text" name="Celular" value="<? echo $Celular;?>"></td>
        </tr>
        <tr>-->
        	<td>E-Mail</td>
            <td><input type="text" name="Email" value="<? echo $Email;?>" style="width:100%"></td>
        </tr>
        <tr>
        <td>Contacto</td>
        <td colspan="6"><input type="text" name="Representante" value="<? echo $Representante;?>" style="width:100%"/></td>
        </tr>
        <tr>
        <td>Parentesco</td>
        <td colspan="3"><input type="text" name="Parentesco" value="<? echo $Parentesco;?>" style="width:100%"/></td>
        <td>Telefono</td>
        <td><input type="text" name="TelRepresentante" value="<? echo $TelRepresentante;?>" style="width:100%"/></td>
        </tr>
        <tr>
        <td>Direccion Contacto</td>
        <td colspan="3"><input type="text" name="DirContacto" value="<? echo $DirContacto;?>" style="width:100%"/></td>
        <td>Ciudad Contacto</td>
        <td><input type="text" name="CiudContacto" value="<? echo $CiudContacto;?>" style="width:100%"/></td>
        </tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar"></center>
</form>
</body>
<iframe name="BuscaMpo" id="BuscaMpo" src="BuscarMunicipios.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
</html>