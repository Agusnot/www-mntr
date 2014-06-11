<?

?>
<head>
<link href="../css/all.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function getId(id){
	if(!document.getElementById)id = document.all[id];
	else id = document.getElementById(id);
	return id;
}
function getWinSize(){
	var size = new Object();
	size.w = document.documentElement.clientWidth;
	size.h = document.documentElement.clientHeight;
	return size;
}
function getWinPos(){
	var pos = new Object();
	pos.x = window.screenX;
	pos.y = window.screenY;
	return pos;
}
window.onresize = window.onload = function (){
	var size = getWinSize();
	var pos = getWinPos();
	getId("w").value = size.w;
	getId("h").value = size.h;
	getId("x").value = pos.x;
	getId("y").value = pos.y;
	//alert(size.w+","+size.h);
}
</script>
</head>

<body>
<form name="FORMA" method="post">
  <p>
    Width: &nbsp;
    <input type="text" name="w" id="w"/>
  <br /><br/>
    Height: 
    <input type="text" name="h" id="h"/>
  <br /><br/>
	X: &nbsp;
    <input type="text" name="x" id="x"/>
  <br /><br/>
    Y: 
    <input type="text" name="y" id="y"/>
  </p>
  <p><a href="js_winsize.zip">.Zip</a></p>
</form>
<hr />
<p id="cepF"><a href="/">Cep.la</a></p>
</body>
