<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript">  
 
var hombre = new Array("___\n", "   |\n", "   O\n", "  /", "|", "\\\n", "  /", " \\\n", "___")  
var palabra  
var libreriaPalabras = new Array("m u l t i m e d i a", "i n t e r n a u t a", "s e r v i d o r", "p r o t o c o l o", "c o r t a f u e g o s",  
    "n a v e g a d o r", "n o d o", "m a r c o", "p a g i n a", "t e l a r a ñ a",  
    "d e s c a r g a r", "v i r t u a l", "m e m o r i a", "d i s c o", "l o c a l",  
    "c o n e c t a r", "d e s c o n e c t a r", "e n c a m i n a d o r", "i n t e r n e t", "d o m i n i o",  
    "d i n a m i c o", "h i p e r v i n c u l o", "e n l a c e", "m a r c a d o r", "o r d e n a d o r")  
var partes = 0  
var colNueva = 0  
var jugando  
  
function ObtienePalabra() {  
//    //obtiene la palabra para jugar de forma pseudoaleatoria  
    var indice = Math.round ( Math.random() * 24 )  
    var cadena = new String( libreriaPalabras[indice] )  
    palabra = cadena.split(" ")  
}  
  
function DibujaHombre(visor, partes) {  
 //   //dibuja el hombre ahorcado  
//    //partes indica el numero de partes a dibujar  
      
    var dibujo = ""  
    if (partes < 10)  
        for(var x = 0; x < partes; x++) {  
            dibujo += hombre[x]  
        }  
      
    visor.displayHombre.value = dibujo  
}  
  
function DibujaLetra(visor, letra) {  
//    //dibuja una letra de la palabra  
 //   //posicion indica donde debe dibujar la letra  
  
    var flag = false     //   //indica si se encontro la letra      
      
//    //obtiene cadena actual  
    var cadena = new String(visor.displayPalabra.value)  
      
    //la separa en sus espacios  
    var letrasCadena = cadena.split(" ")  
  
    cadena = ""   
    for (var x = 0; x < palabra.length; x++) {  
        if (palabra[x] == letra) {  
            cadena += letra + " "  
            flag = true  
        } else  
            cadena += letrasCadena[x] + " "  
    }  
      
    visor.displayPalabra.value = cadena  
    return flag  
}  
  
function NuevaLetra(visor, letra) {  
    //añade letra lista de letras  
    visor.displayLetras.value += letra + " "  
  
    //comprueba si ha de pasar a la siguiente fila  
    if(colNueva == 3) {  
        visor.displayLetras.value += "\n"  
        colNueva = 0  
    } else  
        colNueva++  
}  
  
function Juega(visor, letra) {  
  
    //comprueba si esta jugando  
    if (jugando) {  
  
        //ciclo de jugada  
      
        //1. añade letra a la lista  
        NuevaLetra(visor, letra)  
      
        //2. dibuja la letra y comprueba si acierto  
        var acierto = DibujaLetra(visor, letra)  
      
        //3. si no acierto, dibuja hombre  
        if (!acierto)  
            DibujaHombre(visor, ++partes)  
      
        //4. comprueba si fin  
        if (partes == 9)  
            FinJuego(false)  
        else if ( CompruebaPalabra(visor) )  
            FinJuego(true)  
  
    } else {  
        alert('Pulsa Juego nuevo para comenzar\nuna partida nueva.')  
    }  
}  
  
function IniciaJuego() {  
    //inicializa visor y variables globales  
    jugando = true  
    partes = 0  
    colNueva = 0  
    ObtienePalabra()  
    DibujaHombre(document.visor, partes)  
    document.visor.displayPalabra.value = ""  
    for (var x = 0; x < palabra.length; x++)  
        document.visor.displayPalabra.value += "_ "  
    document.visor.displayLetras.value = ""  
}  
  
function CompruebaPalabra(visor) {  
    //comprueba si se completo toda la palabra  
  
    var fin = true  
      
    //obtiene cadena actual  
    var cadena = new String(visor.displayPalabra.value)  
      
    //la separa en sus espacios  
    var letrasCadena = cadena.split(" ")  
      
    for(var x = 0; x < letrasCadena.length; x++)  
        if (letrasCadena[x] == "_")  
            fin = false  
  
    return fin  
}  
  
function FinJuego(resultado) {  
    //indica que si se ha perdido o ganado  
    var solucion = ""  
  
    jugando = false   
    if (resultado) {  
        document.visor.ganadas.value++  
        alert("¡Acertaste!")  
    } else {  
        document.visor.perdidas.value++  
        //construye la palabra solucion  
        for (var x = 0; x < palabra.length; x++)  
            solucion += palabra[x]  
        alert("¡Has muerto!\n La palabra era: " + solucion)  
    }  
}  
  
window.onload = IniciaJuego;  
if (document.captureEvents) {               //N4 requiere invocar la funcion captureEvents  
    document.captureEvents(Event.LOAD)  
}  
  
</script>  
</head>
<body>
<!-- Para visualizar el juego -->  
<form name="visor">  
  <div align="center"><center><table width="85%">  
    <tr>  
      <td colspan="3" width="33%"><p><textarea name="displayHombre" cols="14" rows="6"></textarea></p></td>  
      <td colspan="3" width="33%"><p><textarea name="displayLetras" cols="14" rows="6"></textarea></p></td>  
      <td width="34%" valign="top">  
      <p><input type="text" name="ganadas" size="4" value="0"><small> Ganadas</small></p>  
      <p><input type="text" name="perdidas" size="4" value="0"><small> Perdidas</small></p>  
      <p><input type="button" value="Limpiar" name="B1" class="metal" onClick="this.form.ganadas.value='0'; this.form.perdidas.value='0'"></p>  
      </td>  
    </tr>  
    <tr>  
      <td colspan="6" width="84%"><input name="displayPalabra" value size="34"></td>  
      <td width="16%"></td>  
    </tr>  
    <tr>  
      <td width="11%"><input type="button" name="botA" value=" A " onClick="Juega(this.form, 'a')" class="metal"></td>  
      <td width="11%"><input type="button" name="botB" value=" B " onClick="Juega(this.form, 'b')" class="metal"></td>  
      <td width="11%"><input type="button" name="botC" value=" C " onClick="Juega(this.form, 'c')" class="metal"></td>  
      <td width="11%"><input type="button" name="botD" value=" D " onClick="Juega(this.form, 'd')" class="metal"></td>  
      <td width="11%"><input type="button" name="botE" value=" E " onClick="Juega(this.form, 'e')" class="metal"></td>  
      <td width="11%"><input type="button" name="botF" value=" F " onClick="Juega(this.form, 'f')" class="metal"></td>  
      <td width="34%"><input type="button" name="Inicia" value="Juego nuevo" onClick="IniciaJuego()" class="metal"></td>  
    </tr>  
    <tr>  
      <td width="11%"><input type="button" name="botG" value=" G " onClick="Juega(this.form, 'g')" class="metal"></td>  
      <td width="11%"><input type="button" name="botH" value=" H " onClick="Juega(this.form, 'h')" class="metal"></td>  
      <td width="11%"><input type="button" name="botI" value=" I " onClick="Juega(this.form, 'i')" class="metal"></td>  
      <td width="11%"><input type="button" name="botJ" value=" J " onClick="Juega(this.form, 'j')" class="metal"></td>  
      <td width="11%"><input type="button" name="botK" value=" K " onClick="Juega(this.form, 'k')" class="metal"></td>  
      <td width="11%"><input type="button" name="botL" value=" L " onClick="Juega(this.form, 'l')" class="metal"></td>  
      <td width="34%"></td>  
    </tr>  
    <tr>  
      <td width="11%"><input type="button" name="botM" value=" M " onClick="Juega(this.form, 'm')" class="metal"></td>  
      <td width="11%"><input type="button" name="botN" value=" N " onClick="Juega(this.form, 'n')" class="metal"></td>  
      <td width="11%"><input type="button" name="botÑ" value=" Ñ " onClick="Juega(this.form, 'ñ')" class="metal"></td>  
      <td width="11%"><input type="button" name="botO" value=" O " onClick="Juega(this.form, 'o')" class="metal"></td>  
      <td width="11%"><input type="button" name="botP" value=" P " onClick="Juega(this.form, 'p')" class="metal"></td>  
      <td width="11%"><input type="button" name="botQ" value=" Q " onClick="Juega(this.form, 'q')" class="metal"></td>  
      <td width="34%"></td>  
    </tr>  
    <tr>  
      <td width="11%"><input type="button" name="botR" value=" R " onClick="Juega(this.form, 'r')" class="metal"></td>  
      <td width="11%"><input type="button" name="botS" value=" S " onClick="Juega(this.form, 's')" class="metal"></td>  
      <td width="11%"><input type="button" name="botT" value=" T " onClick="Juega(this.form, 't')" class="metal"></td>  
      <td width="11%"><input type="button" name="botU" value=" U " onClick="Juega(this.form, 'u')" class="metal"></td>  
      <td width="11%"><input type="button" name="botV" value=" V " onClick="Juega(this.form, 'v')" class="metal"></td>  
      <td width="11%"><input type="button" name="botW" value=" W " onClick="Juega(this.form, 'w')" class="metal"></td>  
      <td width="34%"></td>  
    </tr>  
    <tr>  
      <td width="11%"><input type="button" name="botX" value=" X " onClick="Juega(this.form, 'x')" class="metal"></td>  
      <td width="11%"><input type="button" name="botY" value=" Y " onClick="Juega(this.form, 'y')" class="metal"></td>  
      <td width="11%"><input type="button" name="botZ" value=" Z " onClick="Juega(this.form, 'z')" class="metal"></td>  
      <td colspan="3" width="33%"></td>  
      <td width="34%"></td>  
    </tr>  
  </table>  
  </center></div>  
</form>  
  
<h2>Cómo jugar</h2>  
<p>Cada nueva partida que comienza, el ordenador selecciona una palabra al azar de la  
biblioteca de palabras. En el cuadro de texto del centro se muestra un guión bajo  
("_") por cada letra que tiene la palabra.</p>  
<p>Para sugerir una letra, basta con pulsar el botón de la letra correspondiente. Cada  
letra sugerida aparecerá en el cuadro situado en la parte superior derecha del panel.</p>  
<p>Cuando la letra sugerida está en la palabra, los guiones correspondientes a esa letra  
son sustituidos por ella en el cuadro de texto central. Si la letra sugerida no está en  
la palabra, se añade un trazo al dibujo del ahorcado en el cuadro que aparece en la parte  
superior izquierda.</p>  
<p>Si se completa la palabra antes de que se dibuje el muñeco del ahorcado, se gana la  
partida. En caso contrario, el ordenador indicará cual era la palabra buscada. Cada vez  
que se termina una partida hay que pulsar el botón <strong>Juego nuevo</strong> para  
comenzar un nuevo juego con otra palabra.</p>  
<p>Los contadores de la derecha llevan la cuenta del número de partidas ganadas y  
perdidas. Se pueden poner a cero pulsando el botón <strong>Limpiar</strong>.</p>  
</body>
</html>