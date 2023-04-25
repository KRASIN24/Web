<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Portal recenzje książek</title>

	<style>
          
        .stop-scrolling {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body>
		<div class="holder">
	<h1>Portal recenzje książek</h1>
		</div>
<div class="nav-bar">
	<button class="open-button-book" onclick="openFormBook()">Dodaj książke</button>
	<button class="open-button-review"  onclick="openFormReview()">Dodaj recenzje</button>
	<button class="open-button-data"  onclick="openFormData()">Pokaż książki</button>
		
		<form method="post" class="search-tile">
	<div class="show">
		<div class="search-box">
			<input class="search" placeholder="Wyszukaj" name="search">
            <div class="icon-box"><i id="icon" class="fa fa-search"></i></div>
        </div>
	</div>
			<input class="search-button" type="submit" value="Wyszukaj">
		</form>

</div>

<div class="form-popup" id="myFormBook">
<form  method="POST">
<div class="inform">
<h2>Dodaj książke</h2> 
<input
class="writein"
name="title-FB"
placeholder="Tytuł książki">

<input
class="writein"
name="autorI-FB"
placeholder="Imie autora">

<input
class="writein"
name="autorN-FB"
placeholder="Nazwisko autora">

<div class="button-space">
<button type="button" class="btn cancel" onclick="closeFormBook()">Zamknij</button>
<input id="send" value="Dodaj" type="submit" class="press">
</div>

</div>
</form>
</div>


<div class="form-popup" id="myFormReview">
<form  method="POST">
<div class="inform">
<h2>Dodaj recenzje</h2> 
<input
class="writein"
name="title-FR"
placeholder="Tytuł książki">

<input
class="writein"
name="autorI-FR"
placeholder="Imie autora">

<input
class="writein"
name="autorN-FR"
placeholder="Nazwisko autora">

<textarea
name="recenzja"
rows="10"
placeholder="Wpisz swoją recenzje"></textarea>

<div class="button-space">
<button type="button" class="btn cancel" onclick="closeFormReview()">Zamknij</button>
<input id="send" value="Dodaj" type="submit" name="submit" class="press">
</div>

</div>
</form>
</div>


<div class="form-popup-like" id="myFormData">
		<div class="inform-like">
			<h2>Lista książek</h2> 
<?php

# połączenie do bazy danych

$servername = "localhost";
$database = "portal_recenzji_ksiazek"; 
$username = "root";
$password = "";

  $con = mysqli_connect($servername, $username, $password, $database);

# pokazanie ksiązek

$result = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko
FROM ksiazki join autorzy  on ksiazki.id_autora  = autorzy.id_autora");

if ($result) {

?>
		<table class="list">

			<tr>
				<th>Tytuł</th>
				<th>Autor</th>
			</tr>
<?php

while($row = mysqli_fetch_assoc($result)){
foreach($row as $klucz=>$wartosc){

	if($klucz=="Ksiazki"){
echo "<tr>";
	echo "<td>$wartosc</td>";
}
elseif($klucz=="imie")
	{
		$bridge = $wartosc;
	}

	elseif($klucz=="nazwisko")
	{
		echo "<td>$bridge $wartosc</td>";
		echo "</tr>";
	}
	}
	}
}

?>
<button type="button" class="btn cancel" onclick="closeFormData()">Zamknij</button>
</table>
</div>
</div>

<?php

 # wypisywanie bazy danych

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

if (isset($_POST["search"])) {
	$str = $_POST["search"];
	if (!str_contains($str, " ")) {
		$result = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko, recenzje.Recenzja 
	FROM (autorzy join ksiazki on autorzy.id_autora = ksiazki.id_autora)
	join recenzje on ksiazki.ID_ksiazki = recenzje.id_ksiazki
	WHERE ksiazki.Ksiazki LIKE '%$str%' OR autorzy.imie LIKE '%$str%' OR autorzy.nazwisko LIKE '%$str%'");
	}
	else {
		$strs = explode(" ", $str,2);
		$first = $strs[0];
		$second = $strs[1];
		$result = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko, recenzje.Recenzja 
	FROM (autorzy join ksiazki on autorzy.id_autora = ksiazki.id_autora)
	join recenzje on ksiazki.ID_ksiazki = recenzje.id_ksiazki
	WHERE ksiazki.Ksiazki LIKE '%$str%' OR autorzy.imie LIKE '%$first%' OR autorzy.nazwisko LIKE '%$first%' OR
	 autorzy.imie LIKE '%$second%' OR autorzy.nazwisko LIKE '%$second%'");
	}
}
else{

	$result = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko, recenzje.Recenzja 
	FROM (ksiazki join autorzy  on ksiazki.id_autora  = autorzy.id_autora)
	join recenzje on ksiazki.ID_ksiazki = recenzje.id_ksiazki");
}

if ($result) {

	?>
		<div class="book-list">
			<table class="list">

				<tr>
					<th>Tytuł</th>
					<th>Autor</th>
					<th>Recenzja</th>
				</tr>
	<?php

while($row = mysqli_fetch_assoc($result)){
foreach($row as $klucz=>$wartosc){
	if($klucz=="Ksiazki"){
echo "<tr>";
		echo "<td>$wartosc</td>";
	}
	elseif($klucz=="imie")
		{
			$bridge = $wartosc;
		}

		elseif($klucz=="nazwisko")
		{
			echo "<td>$bridge $wartosc</td>";
		}

		elseif($klucz=="Recenzja"){
			if(empty($wartosc))
			$wartosc = "Brak recenzji";
			echo "<td>$wartosc</td>";
	echo "</tr>";
		}
		}
	}
}

?>

</table>
</div>
<?php

# dodawanie książek

if (isset($_POST["title-FB"]) && isset($_POST["autorI-FB"]) && isset($_POST["autorN-FB"]) && !empty($_POST["title-FB"]) 
&& !empty($_POST["autorI-FB"]) && !empty($_POST["autorN-FB"])) {

	$titleB = $_POST["title-FB"];
	$autorIB = $_POST["autorI-FB"];
	$autorNB = $_POST["autorN-FB"];

	if($temp1 = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko, autorzy.id_autora
	FROM autorzy join ksiazki on autorzy.id_autora = ksiazki.id_autora
	WHERE ksiazki.Ksiazki = '$titleB' AND autorzy.imie = '$autorIB' AND autorzy.nazwisko = '$autorNB'")){	

		if(mysqli_num_rows($temp1) == 0) {

			if($temp0 = mysqli_query($con, "SELECT autorzy.imie, autorzy.nazwisko, autorzy.id_autora FROM autorzy
			WHERE autorzy.imie = '$autorIB' AND autorzy.nazwisko = '$autorNB'")){	
				
				if(mysqli_num_rows($temp0) != 0) {
				foreach ($temp0 as $key => $value) {
					$to1 = $value["id_autora"];
				}
				mysqli_query($con, "INSERT INTO ksiazki (Ksiazki, id_autora) 
				VALUES ('$titleB', '$to1')");
				echo "<meta http-equiv='refresh' content='0'";
			}
		
			else{	
				$query=mysqli_query($con, "SELECT * FROM autorzy ORDER BY Id_autora DESC LIMIT 1"); 
				$lastRow = mysqli_fetch_assoc($query); 
				$to1 = $lastRow["Id_autora"] +1; 	
	
			mysqli_query($con, "INSERT INTO autorzy (imie, nazwisko) 
			VALUES ('$autorIB', '$autorNB')");
			mysqli_query($con, "INSERT INTO ksiazki (Ksiazki, id_autora) 
			VALUES ('$titleB', '$to1')");
			echo "<meta http-equiv='refresh' content='0'";
			}
		}

	}
	else{
		echo "<div class=\"error\">";
		echo "<h3>Błąd</h3>";
		echo "<p> Książka już istnieje w bazie danych </p>";
		echo "</div>";
	}
}
}


# dodawanie recenzji

if (isset($_POST["title-FR"]) && isset($_POST["autorI-FR"]) && isset($_POST["autorN-FR"]) && !empty($_POST["title-FR"]) && !empty($_POST["autorI-FR"]) 
&& !empty($_POST["autorN-FR"])  && isset($_POST["recenzja"]) && !empty($_POST["recenzja"])) {
	$titleR = $_POST["title-FR"];
	$autorIR = $_POST["autorI-FR"];
	$autorNR = $_POST["autorN-FR"];
	$to2[] = 0;

	if($temp2 = mysqli_query($con, "SELECT ksiazki.Ksiazki, autorzy.imie, autorzy.nazwisko, ksiazki.ID_ksiazki
	FROM autorzy join ksiazki on autorzy.id_autora = ksiazki.id_autora
	WHERE ksiazki.Ksiazki = '$titleR' AND autorzy.imie = '$autorIR' AND autorzy.nazwisko = '$autorNR'")){	

		if(mysqli_num_rows($temp2) != 0) {
	$recenzja = $_POST["recenzja"];
	foreach ($temp2 as $key => $value) {
		$to2 = $value["ID_ksiazki"];
	}
	mysqli_query($con, "INSERT INTO recenzje (Recenzja, Id_ksiazki)
	VALUES ('$recenzja', '$to2')");
	echo "<meta http-equiv='refresh' content='0'";
	}
	else{
		echo "<div class=\"error\">";
		echo "<h3>Błąd</h3>";
		echo "<p> Podana książka nie jest w bazie danych </p>";
		echo "</div>";
	}
}
}

mysqli_close($con);
?>

<footer>
        <div class="copyright">
            &copy; 2023 
			Jakub Krasiński, <br>
			Igor Legucki, 
			Śp. Oskar Bugajski
        </div>
    </footer>

</body>

<script>
	document.getElementById("myFormBook").addEventListener("click", (e) => {
    if (e.target === document.getElementById("myFormBook")) {
		closeFormBook();
    }
});

document.getElementById("myFormReview").addEventListener("click", (e) => {
    if (e.target === document.getElementById("myFormReview")) {
		closeFormReview();
    }
});

document.getElementById("myFormData").addEventListener("click", (e) => {
    if (e.target === document.getElementById("myFormData")) {
		closeFormData();
    }
});

	function openFormBook() {
    document.getElementById("myFormBook").style.display = "block";
	document.body.classList.add("stop-scrolling");
  }
  
  function closeFormBook() {
    document.getElementById("myFormBook").style.display = "none";
	document.body.classList.remove("stop-scrolling");
  }

function openFormReview() {
    document.getElementById("myFormReview").style.display = "block";
	document.body.classList.add("stop-scrolling");
}
  
  function closeFormReview() {
    document.getElementById("myFormReview").style.display = "none";
	document.body.classList.remove("stop-scrolling");
  }

  function openFormData() {
    document.getElementById("myFormData").style.display = "block";
  }

  function closeFormData() {
    document.getElementById("myFormData").style.display = "none";
  }

  document.addEventListener("keydown", (e) => {
	if (e.code === "Escape" || e.keyCode === 27) {
            closeFormReview();
			closeFormBook();
			closeFormData();
        }
  });

</script>
</html>
