<?php 
	// et saada ligi sessioonile
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();
	
	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	//ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	//kas kasutaja tahab välja logida
	// kas aadressireal on logout olemas
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
	}
	
	if (	isset($_POST["note"]) && 
			isset($_POST["color"]) && 
			!empty($_POST["note"]) && 
			!empty($_POST["color"]) 
	) {
		
		$note = $Helper->cleanInput($_POST["note"]);
		$color = $Helper->cleanInput($_POST["color"]);
		
		$Note->saveNote($note, $color);
		
	}
	
	$q = "";
	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);
	}
	
	$sort = "id";
	$order = "asc";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	$notes = $Note->getAllNotes($q, $sort, $order);
	
	//echo "<pre>";
	//var_dump($notes);
	//echo "</pre>";
?>
<?php require ("../header.php")?>
<div align="center">
<h1>Treeningplaan</h1>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>!
	<a href="?logout=1">Logi välja</a>
</p>
<form method="POST">
<br><h3>Sisesta harjutus ja kuupäev</h3>
<textarea name="note" rows="4" cols="50" value="text"></textarea>
<br>
<input name="color" type="date" style="width: 200px; height: 30px">
<br><br>
 <input type="submit" value="lisa">
 </form>
 <br>

<h4 style="clear:both;">Lisa hiljem ka harjutuse juurde enda tulemus ja muud märkmed.</h4>
<?php 
	$html = "<table class='table table-hover'>";
		
		$html .= "<tr>";
			
			$orderId = "DESC";
		
		
			$orderNote = "desc";
			if (isset($_GET["order"]) &&
				$_GET["order"] == "desc" &&
				$_GET["sort"] == "harjutus" ) {
				$orderNote = "DESC";
			}
		
			$orderColor = "desc";
			if (isset($_GET["order"]) &&
				$_GET["order"] == "asc" &&
				$_GET["sort"] == "kuupäev" ) {
				$orderColor = "DESC";
			}
		
			$html .= "<th>
			
				<a href='?q=".$q."&sort=id&order=".$orderId."'>
					id
				</a>
			</th>";
			$html .= "<th>
				<a href='?q=".$q."&sort=harjutus&order=".$orderNote."'>
					harjutus
				</a>
			</th>";
			$html .= "<th>
				<a href='?q=".$q."&sort=kuupäev&order=".$orderColor."'>
					kuupäev
				</a>
			</th>";
		$html .= "</tr>";
	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->note."</td>";
			$html .= "<td>".$note->noteColor."</td>";
			$html .= "<td><a class='btn btn-default' href='edit.php?id=".$note->id."'><span class='glyphicon glyphicon-pencil'><span>edit.php</a></td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
?>
<?php require ("../footer.php")?>