var ajaxku;

function ajaxangg(id){
  debugger;
  ajaxku=buatajax();
  var url="select_tipe.php";
  url=url+"?q="+id;
  url=url+"&sid"+Math.random();
  ajaxku.onreadystatechange=stateChanged(id);
  ajaxku.open("GET",url,true);
  ajaxku.send(null);
}

function buatajax(){
  debugger;
  if (window.XMLHttpRequest){
  return new XMLHttpRequest();
  }
  if (window.ActiveXObject){
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
  return null;
}

function stateChanged(data){
  debugger;
  var data;
  if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
    document.getElementById("angg").innerHTML = data
    }else{
    document.getElementById("angg").value = "<option selected>Pilih Kota/Kab</option>";
    }
  }
}
