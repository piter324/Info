<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<link rel='stylesheet' type='text/css' href='../styles/adminpanel.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<title>Panel administracyjny info</title>
<link rel='stylesheet' href='dialogAPI/dialogBox.css'>
<script src='../jquery.js'></script>
<script src='dialogAPI/showDialog.js'></script>
<script>
const klasa = '<?php session_start(); echo $_SESSION['userclass'];?>';
if(klasa!='administrator')
{
	alert('Nie masz uprawnień administratora!');
	window.location='index.php';
}
var globalParentUid;
function showServices(service)
{
	$('div.service').hide();
	if(service=='manageUsersAndPasswords')
	{
		$('div.service#manageUsersAndPasswords').show();
		listUsers();
	}
	else if(service=='manageAdves')
	{
		$('div.service#manageAdves').show();
		listAdves();
	}
	else if(service=='manageForum')
	{
		$('div.service#manageForum').show();
		listEntries();
		$('#commentsForEntry').html('(nie wybrano wpisu)');
		$('#selectedEntryId').html('nie wybrano');
	}
	else if(service=='manageEvents')
	{
		$('div.service#manageEvents').show();
		listEvents();
	}
}


function changePasses()
{
	var nowehaslo = $('#newpass').val();

	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedUser').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('changepasses.php',{ids:iddozmiany,newpass:nowehaslo},function(data){
	$('#outputPass').html(data);
	listUsers();
	});
}
function listUsers()
{
	$.post('listusers.php',function(data){
		$('div.userList').html(data);
	});

}
function listAdves()
{
	var filter = $('#filterByAdveContentText').val();
	$.post('listAdves.php',{filter:filter},function(data){
		$('div#adveList').html(data);
	});

}
function listEntries()
{
	var filter = $('#filterByEntryContentText').val();
	$.post('listEntries.php',{filter:filter},function(data){
		$('div#entriesList').html(data);
	});

}
function listComments(parentuid)
{
	globalParentUid=parentuid;
	$.post('listComments.php',{parentuid:parentuid},function(data){
		$('div#commentsForEntry').html(data);
		$('span#selectedEntryId').html(parentuid);
	});

}
function listEvents()
{
	var filter = $('#filterByEventContentText').val();
	$.post('listEvents.php',{filter:filter},function(data){
		$('div#eventsList').html(data);
	});

}
function filterUsersName()
{
	var filtr = $('#filterusersname').val();
	$('div.useronthelist').hide();
	$.post('listusersfilteredbyname.php',{filter:filtr},function(data){
		var showedusersArray = data.split(',');
		
	for(var i=0;i<showedusersArray.length;i++)
	{
		$('div.useronthelist#'+showedusersArray[i]).show();
	}
	});
}
function filterUsersClass()
{
	var filtr = $('#filterusersclass').val();
	$('div.useronthelist').hide();
	$.post('listusersfilteredbyclass.php',{filter:filtr},function(data){
		var showedusersArray = data.split(',');
		
	for(var i=0;i<showedusersArray.length;i++)
	{
		$('div.useronthelist#'+showedusersArray[i]).show();
	}
	});
}
function changeUsers()
{
	var nowanazwa = $('#newname').val();
	var nowaklasa = $('#newclass').val();

	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedUser').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('changeusers.php',{ids:iddozmiany,newname:nowanazwa,newclass:nowaklasa},function(data){
	if(data=="done")
	{
		$('#outputChangeUsers').html("Gotowe!");
		listUsers();
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function removeUsers()
{
	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedUser').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('removeusers.php',{ids:iddozmiany},function(data){
	if(data=="done")
	{
		$('#outputChangeUsers').html("Gotowe!");
		listUsers();
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function removeAdve()
{
	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedAdve').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('removeAdve.php',{ids:iddozmiany},function(data){
	if(data=="done")
	{
		$('#outputRemoveAdve').html("Gotowe!");
		listAdves();
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function removeEntry()
{
	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedEntry').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('removeEntry.php',{ids:iddozmiany},function(data){
	if(data=="done")
	{
		$('#outputRemoveEntry').html("Gotowe!");
		listEntries();
		listComments(globalParentUid);
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function removeComment()
{
	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedComment').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('removeEntry.php',{ids:iddozmiany},function(data){
	if(data=="done")
	{
		$('#outputRemoveComment').html("Gotowe!");
		listComments(globalParentUid);
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function removeEvent()
{
	//petla zbierająca zaznaczone osoby
	var zaznaczonepersony=$('input:checkbox:checked.listedEvent').map(function(){
				return this.value;
			}).get();
	var iddozmiany = zaznaczonepersony.join(',');
	
	$.post('removeEvent.php',{ids:iddozmiany},function(data){
	if(data=="done")
	{
		$('#outputRemoveEvent').html("Gotowe!");
		listEvents();
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
function manuallyAddUser()
{
	var username = $('#username').val();
	var password = $('#password').val();
	var userclass = $('#userclass').val();
	var email = $('#email').val();
	
	$.post('manuallyadduser.php',{uname:username,upass:password,uclass:userclass,uemail:email},function(data){
	if(data=="done")
	{
		$('#outputAddUser').html("Gotowe!");
		listUsers();
	}
	else
	{
		showDialog("Błąd!",data,true);
	}
	});
}
</script>
</head>
<body>
<div class='container'>
<div class='upbelt'>
<p style='font-size:26px'>Witaj <strong><?php echo $_SESSION['username'];?></strong> w panelu administracyjnym info!</p>
<p>Kliknij ikonę poniżej, aby przejść do usługi LUB <a href="javascript:window.location='../index.php';">wróć do info</a></p>
</div>
<div class='servicesHub'>
<div class='servicesHubElement' onclick="showServices('manageUsersAndPasswords')"><p><img src='../images/profiles/defaultprofile.png' width=100></p><p>Zarządzaj użytkownikami i hasłami</p></div>
<div class='servicesHubElement' onclick="showServices('manageAdves')"><p><img src='../images/icons/adv-100.png' width=100></p><p>Zarządzaj ogłoszeniami</p></div>
<div class='servicesHubElement' onclick="showServices('manageForum')"><p><img src='../images/icons/Share-100.png' width=100></p><p>Zarządzaj wpisami i komentarzami</p></div>
<div class='servicesHubElement' onclick="showServices('manageEvents')"><p><img src='../images/icons/Calendar 7-100.png' width=100></p><p>Zarządzaj terminarzem</p></div>
</div>
</div>

<div class='service' id='manageUsersAndPasswords'>
<div id='userListAndFilter'>
<p class='serviceElementHeader'>Filtruj użytkowników</p><p>po nazwie:<input type="text" id="filterusersname" onkeyup="filterUsersName()"><button onclick="$('#filterusersname').val(''); filterUsersName();">X</button>&emsp;po klasie:<input type="text" id="filterusersclass" onkeyup="filterUsersClass()"><button onclick="$('#filterusersclass').val(''); filterUsersClass();">X</button></p>
<center><div class='userList'>
</div></center>
<button onclick="$('input:checkbox:visible.listedUser').prop('checked',true);">Zaznacz widoczne</button><button onclick="$('input:checkbox:visible.listedUser').prop('checked',false);">Odznacz widoczne</button><br />
<button onclick="removeUsers()">Usuń zaznaczonych użytkowników</button>
</div>

<div class='serviceElement'>
<p class='serviceElementHeader'>Zresetuj hasło</p>
<p>Nowe hasło: <input type="text" id="newpass">&emsp;
<button onclick="changePasses()">Resetuj</button></p>
<div id='outputPass'>
</div>
</div>

<div class='serviceElement'>
<p class='serviceElementHeader'>Zmień/usuń zaznaczonych użytkowników</p>
<p>Nowa nazwa użytkownika: <input type="text" id="newname"></p>
<p>Nowa klasa: <input type="text" id="newclass"></p>
<button onclick="changeUsers()">Zmień</button>
<div id='outputChangeUsers'>
</div>
</div>

<div class='serviceElement dashed'>
<p class='serviceElementHeader'>Dodaj użytkowników</p>
<p>Nazwa użytkownika: <input type="text" id="username"></p>
<p>Hasło: <input type="text" id="password"></p>
<p>Klasa: <input type="text" id="userclass"></p>
<p>E-mail: <input type="text" id="email"></p>
<button onclick="manuallyAddUser()">Dodaj</button>
<div id='outputAddUser'>
</div>
</div>

<div class='serviceElement dashed'>
<p class='serviceElementHeader'>Dodaj użytkowników przez plik CSV</p>
<p style='font-size:16px'>Plik CSV powinien zawierać kolumny: nazwa użytkownika | hasło | klasa | email w tej kolejności, lecz bez nagłówków, tylko czyste dane. Nazwa użytkownika powinna być w formacie: <em>Nazwisko Imię</em> rozpoczęte wielką literą z polskimi znakami diakrytycznymi. Plik CSV musi mieć kodowanie UTF-8.</p><p>Przykład rozkładu treści:</p><p><img src='csvexample.jpg'></p>
<form action='importcsv.php' method='post' enctype='multipart/form-data'>
<p><label for='file'>Wybierz plik (tylko *.csv):</label>
<input type='file' name='filename'></p>
<p>Znak podziału pliku CSV: <input type='text' name='znakpodzialu'></p>
<p><input type='submit' value='Prześlij!'></p>
</form>
<div id='outputAddUser'>
</div>
</div>
</div>

<div class='service' id='manageAdves'>
<div id='advertsListAndFilter'>
<p class='serviceElementHeader'>Filtruj ogłoszenia</p><p>po tytule lub treści:<input type="text" id="filterByAdveContentText" onkeyup="listAdves()"><button onclick="$('#filterByAdveContentText').val(''); listAdves();">X</button></p>
<center><div id='adveList'>
</div></center>
<button onclick="$('input:checkbox:visible.listedAdve').prop('checked',true);">Zaznacz widoczne</button><button onclick="$('input:checkbox:visible.listedAdve').prop('checked',false);">Odznacz widoczne</button><br />
<button onclick="removeAdve()">Usuń zaznaczone ogłoszenia</button>
<div id='outputRemoveAdve'></div>
</div>
</div>

<div class='service' id='manageForum'>
<div id='advertsListAndFilter'>
<p class='serviceElementHeader'>Filtruj wpisy</p><p>po treści:<input type="text" id="filterByEntryContentText" onkeyup="listEntries()"><button onclick="$('#filterByEntryContentText').val(''); listEntries();">X</button></p>
<center><div id='entriesList'>
</div></center>
<button onclick="$('input:checkbox:visible.listedEntry').prop('checked',true);">Zaznacz widoczne</button><button onclick="$('input:checkbox:visible.listedEntry').prop('checked',false);">Odznacz widoczne</button><br />
<button onclick="removeEntry()">Usuń zaznaczone wpisy</button>
<div id='outputRemoveEntry'></div>
<p id='entryComments'>komentarze do wybranego wpisu (uid: <span id='selectedEntryId'>nie wybrano</span>)</p>
<div id='commentsForEntry'>(nie wybrano wpisu)</div>
</div>
</div>

<div class='service' id='manageEvents'>
<div id='advertsListAndFilter'>
<p class='serviceElementHeader'>Filtruj wydarzenia</p><p>po tytule lub treści:<input type="text" id="filterByEventContentText" onkeyup="listEvents()"><button onclick="$('#filterByEventContentText').val(''); listEvents();">X</button></p>
<center><div id='eventsList'>
</div></center>
<button onclick="$('input:checkbox:visible.listedEvent').prop('checked',true);">Zaznacz widoczne</button><button onclick="$('input:checkbox:visible.listedEvent').prop('checked',false);">Odznacz widoczne</button><br />
<button onclick="removeEvent()">Usuń zaznaczone wydarzenia</button>
<div id='outputRemoveEvent'></div>
</div>
</div>
<div id='dialogSpace'></div>
</div>
</body>
</html>
