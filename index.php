<?php 
session_start(); 
if($_SESSION['userid']!='')
{
	header('Location:main.php');
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>Info Login</title>
<link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='styles/general.css'>
<link rel='stylesheet' type='text/css' href='styles/buttons.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel='stylesheet' href='jquery-ui/jquery-ui.css'>
<link rel='stylesheet' type='text/css' href='styles/index.css'>
<link rel='stylesheet' href='dialogAPI/dialogBox.css'>
<script src="jquery.js"></script>
<script src="jquery-ui/jquery-ui.js"></script>
<script src='dialogAPI/showDialog.js'></script>
<script>
 </script>
<script>
$(function(){
	$('#logInContainer').accordion({
		heightStyle:'content'
	});
});
var cnt=2;
var previous = '';
$(document).on("keypress","#logInPassword",function(e){
	if(e.which==13)
	{
		logIn();
	}
});
function logIn()
{
	if($('#logInEmail').val()==''||$('#logInPassword').val()=='')
	{
		showDialog("Błąd!","Wypełnij pola logowania",true);
	}
	else
	{
		$.post('logIn.php',{email:$('#logInEmail').val(),password:$('#logInPassword').val()},function(data){
			switch(parseInt(data)){
				case 1:
					window.location='main.php';
					break;
				case 2:
					showDialog("Błąd!","Nieprawidłowe hasło",true);
					break;
				case 3:
					showDialog("Błąd!","Użytkownik nie istnieje",true);
					break;
				case 4:
					showDialog("Błąd!","Nie logowałeś się po raz pierwszy. Użyj więc formularza \"Pierwsze logowanie\".",true);
					break;
				default:
					showDialog("Błąd!",data,true);
					break;
				}

		});
	}
}
function firstLogIn()
{
	if($('#firstLogInEmail').val()==''||$('#firstLogInInitialPassword').val()==''||$('#firstLogInPassword1').val()==''||$('#firstLogInPassword2').val()=='')
	{
		showDialog("Błąd!","Wypełnij wszystkie pola",true);
	}
	else if($('#firstLogInPassword1').val()!=$('#firstLogInPassword2').val())
	{
		showDialog("Błąd!","Nowe hasła nie zgadzają się",true);
	}
	else
	{
		$.post('firstLogIn.php',{email:$('#firstLogInEmail').val(),initialPassword:$('#firstLogInInitialPassword').val(),newPassword:$('#firstLogInPassword1').val()},function(data){
			
			switch(parseInt(data)){
				case 1:
					window.location='main.php';
					break;
				case 2:
					showDialog("Błąd!","Nieprawidłowe hasło",true);
					break;
				case 3:
					showDialog("Błąd!","Użytkownik nie istnieje",true);
					break;
				case 5:
					showDialog("Błąd!","Już logowałeś się po raz pierwszy. Użyj więc zwykłego formularza logowania.",true);
					break;
				}
		});
	}
}
function cookiesApplied()
{
	$.post('cookiesApplied.php',{},function(data){

	});
	$('div#cookiesNotice').hide();
}
</script>
</head>
<body>
<div id='totalContainer'>
<div id='upperContainer'>
<img id='logotype' src='images/logo-info-2000x900.png'>
</div>
<div id='contentContainer'>
<div id='logInContainer'>
<h1>Zaloguj się</h1>
<div>
<img src='images/icons/User Male Filled-100.png'><p class='loginFormPara'><input id='logInEmail' type='text' class='loginFormInput' id='username' placeholder='e-mail'>
<input id='logInPassword' type='password' class='loginFormInput' placeholder='hasło' id='password'></p>
<button onclick='logIn()' class='logInButton bigButton'>Zaloguj się</button>
</div>

<h1>Pierwsze logowanie</h1>
<div>
<div><img src='images/icons/Checked User Filled-100.png'><p class='loginFormPara'>
<input id='firstLogInEmail' type='text' class='loginFormInput' id='username' placeholder='e-mail'>
<input id='firstLogInInitialPassword' type='password' class='loginFormInput' placeholder='hasło początkowe' id='password'>
<input id='firstLogInPassword1' type='password' class='loginFormInput' placeholder='nowe hasło' id='password'>
<input id='firstLogInPassword2' type='password' class='loginFormInput' placeholder='powtórz nowe hasło' id='password'></p>
<button onclick='firstLogIn()' class='logInButton bigButton'>Zaloguj się</button></div>
</div>
</div>
</div>
</div>
<p class="copyrightFooter">Copyright &copy; by Piotr Muzyczuk<br/>Ikony z serwisu Icons8 (icons8.com)</p>
<?php
if(!isset($_COOKIE['cookiesApplied']))
{
	echo "<div id='cookiesNotice'><img src='images/cookies.jpg' class='inlinowe inlinowe-mid' style='height:50px; margin-right:10px'><div class='inlinowe inlinowe-mid'><h5>Pliki cookie</h5><p>Potrzebujemy plików cookie, by zapewnić działanie tego serwisu. Korzystając z niego, zgadzasz się na korzystanie z plików cookie. Mniam, mniam...</p></div><img onclick='cookiesApplied()' src='images/closingx.png' style='float:right'></div>";
}

?>
<div id='dialogSpace'></div>
</body>
</html>
