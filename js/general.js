function logOut()
{
	$.post('logOut.php',{},function(data){
		switch(parseInt(data))
		{
			case 1:
			window.location='index.php';
		}
	});
}