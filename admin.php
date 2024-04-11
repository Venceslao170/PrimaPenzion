<?php
session_start();
require_once "./data.php";

$aktivniInstance = null;

if (array_key_exists("login-submit", $_POST)) {
	$jmenoUzivatele = $_POST["uzivatelske-jmeno"];
	$hesloUzivatele = $_POST["uzivatelske-heslo"];
	if ($jmenoUzivatele == "admin" && $hesloUzivatele == "cici123") {
		$_SESSION["jePrihlasen"] = $jmenoUzivatele;
	}
}

if (array_key_exists("logout-submit", $_GET)) {
	unset($_SESSION["jePrihlasen"]);
	header("Location: ?");
	exit;
}

//kontrolujeme zda je prihlaseny
if (array_key_exists("jePrihlasen", $_SESSION)) {

	//zpracování ajax formuláře
	
	if(array_key_exists("poleSerazenychId",$_POST)){
		$poleId = $_POST["poleSerazenychId"];
		Stranka::seradStranky($poleId);
		echo"hotovo";
		exit;
	}



	//uzivatel chce smazat stránku
	if(array_key_exists("delete",$_GET)){
		$idStranky = $_GET["delete"];

		$poleStranek[$idStranky]->smazSE();
		
		header("Location: ?");
		exit;
	}

	//uzviatel chce editovat novou stranku
	if (array_key_exists("new", $_GET)) {
		$aktivniInstance = new Stranka("", "", "", "");
	}
	//chce editovat stranku
	
	if (array_key_exists("edit", $_GET)) {
		$idStranky = $_GET["edit"];
		$aktivniInstance = $poleStranek[$idStranky];
	}
	
	//uzivatel chce stranku ulozit
	if (array_key_exists("aktualizovat-submit", $_POST)) {
		$idStranky = trim($_POST["id-stranky"]);
		$titulekStranky = trim($_POST["titulek-stranky"]);
		$menuStranky = trim($_POST["menu-stranky"]);
		$obrazekStranky = trim($_POST["obrazek-stranky"]);
		
		if ($idStranky == "") {
			header("Location: ?");
			exit;
		}

		$aktivniInstance->setID($idStranky);
		$aktivniInstance->setTitulek($titulekStranky);
		$aktivniInstance->setMenu($menuStranky);
		$aktivniInstance->setObrazek($obrazekStranky);
		$aktivniInstance->zapisDoDatabaze();

		$obsahStranky = $_POST["obsah-stranky"];
		$aktivniInstance->setObsah($obsahStranky);
	
		header("Location: ?edit={$aktivniInstance->getId()}");
		exit;
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin sekce</title>
</head>

<body>
	<h1>Admin sekce</h1>

	<?php
	if (array_key_exists("jePrihlasen", $_SESSION)) {

		echo "Jste přihlášen";
		echo "<br>";
		echo "<hr>";
		echo "<a href ='?logout-submit=ano'>Odhlásit se </a>";
		echo "<hr>";

		echo "<ul class='had'>";
		foreach ($poleStranek as $stranka) {
			echo "<li id='{$stranka->getId()}'>
			<a href='?edit={$stranka->getId()}'>{$stranka->getId()}</a>
			<a class='labut' href='?delete={$stranka->getId()}'>Smazat Stránku</a>
			</li>";
		}
		echo "</ul>";
		echo "<br>";
		echo "<hr>";

		//vytvoříme odkaz pro novou stranku
		echo "<a href='?new=true'>Otevřít novou stránku</a>";
		echo "<br>";
		echo "<hr>";


		if ($aktivniInstance != null) {
	?>
		<h1>Editor stránky</h1>
			
			<form action="" method="post">
				<label for="slon">ID</label>
				<input type="text" name="id-stranky" id="slon" value="<?php echo $aktivniInstance->getID(); ?>">
				<br>
				<hr>
				<label for="zizala">Titulek</label>
				<input type="text" name="titulek-stranky" id="zizala" value="<?php echo $aktivniInstance->getTitulek(); ?>">
				<br>
				<hr>
				<label for="zralok">Menu</label>
				<input type="text" name="menu-stranky" id="zralok" value="<?php echo $aktivniInstance->getMenu(); ?>">
				<br>
				<hr>
				<label for="sob">Obrazek</label>
				<input type="text" name="obrazek-stranky" id="sob" value="<?php echo $aktivniInstance->getObrazek(); ?>">
				<br>
				<hr>

				<label for="kocka">Obsah</label>
				<textarea name="obsah-stranky" id="kocka" cols="30" rows="10">
				<?php echo htmlspecialchars($aktivniInstance->getObsah());
				//htmlspecialchars znehodnoti kod, pro bezpecnost,(před html tagy přidá &lt; )
				?>
				</textarea>
				<hr>
				<input type="submit" value="Aktualizovat web" name="aktualizovat-submit">
			</form>
			<script src="./vendor/tinymce/tinymce/tinymce.js"></script>
			<script>
				tinymce.init({
					selector: '#kocka', 
					entity_encoding: 'raw',
					plugins: ["code", "responsivefilemanager", "image", "anchor", "autolink", "autoresize", "link", "media", "lists"], //aktivujeme je zde, pluginy tinymce uz obsahuje
					toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat', //řádky nástrojů
					toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
					cleanup: false,
					verify_html: false, 
					content_css: [
						'./css/all.min.css',
						'./css/style.css'
					], 
					external_plugins: {
						'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
					},
					external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/", //pluginy na ukladani obrazku na server
					filemanager_title: "File manager",
				});
			</script>

		<?php
		}
	} else { ?>

		<form action="" method="post">
			<label for="cici">Username</label>
			<input type="text" name="uzivatelske-jmeno" id="cici">
			<label for="haf">Heslo</label>
			<input type="password" name="uzivatelske-heslo" id="haf">
			<input type="submit" value="Přihlásit se" name="login-submit">
		</form>

	<?php
	}
	?>


	<script src="./vendor/components/jquery/jquery.js"></script>

	<script src="./vendor/components/jqueryui/jquery-ui.js"></script>

	<script src="./js/admin.js"></script>
</body>

</html>
