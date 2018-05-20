function updateTableSQL(tablename)
{
	$.post('pushAPI/updateSQL.php',{tablename:tablename},function(data){});
}
function recieveData(){
  var ajaxObject = new XMLHttpRequest();
  ajaxObject.open("GET", "pushAPI/push.php");
  ajaxObject.onload = function(){
     if(ajaxObject.readyState == 4 || ajaxObject.status == 200)
     {
         //ajaxObject.responseText <- zwrotna
         if(ajaxObject.responseText==1)
         	{
         		pushAction();
         		//alert('dupa');
         	}
     }
  }
  ajaxObject.send();
}
recieveData();