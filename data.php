<?php
$instancePDO = new PDO (
	"mysql:host=localhost;dbname=penzion;charset=utf8mb4",
	"root",
	"",
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);
//
class Stranka {
	private $id;
	private $titulek;
	private $menu;
	private $obrazek;
	private $oldId = "";

	function __construct($argId,$argTitulek,$argMenu,$argObrazek)
	{
		$this->id = $argId;
		$this->titulek = $argTitulek;
		$this->menu = $argMenu;
		$this->obrazek = $argObrazek;
	}

	static Function seradStranky($argPoleId){
		foreach($argPoleId AS $index => $id){
			$prikaz = $GLOBALS["instancePDO"]->prepare("UPDATE stranka SET poradi=? WHERE id=?");
			$prikaz -> execute(array($index, $id));
		}
	}

	function zapisDoDatabaze () {

		if($this->oldId != ""){
			$prikaz = $GLOBALS["instancePDO"]->prepare("UPDATE stranka SET id=?, titulek=?, menu=?, obrazek=? WHERE id=?");
		$prikaz -> execute(array($this->id,$this->titulek,$this->menu,$this->obrazek,$this->oldId));
		}else{
				//nejprve zjistit nejvysssi hodnotu poradi
			$prikaz = $GLOBALS["instancePDO"]->prepare("SELECT MAX(poradi) AS nejvyssi_poradi FROM stranka");
			$prikaz->execute(array());
			$vysledekDotazu = $prikaz->fetch();
			$nejvyssiCislo = $vysledekDotazu["nejvyssi_poradi"];
				//pote vlozit novou stranku do databaze


			$prikaz = $GLOBALS["instancePDO"]->prepare("INSERT INTO stranka SET id=?, titulek=?, menu=?, obrazek=?, poradi =?");
			$prikaz -> execute(array($this->id, $this->titulek, $this->menu, $this->obrazek,$nejvyssiCislo+1));
		}

	}

	function smazSE(){
		$prikaz = $GLOBALS["instancePDO"]->prepare("DELETE FROM stranka WHERE id=?");
		$prikaz->execute(array($this->id));
	}
	
	//GETTERY
	function getId(){
		return $this->id;
	}

	function setId($argNoveId){
		$this->oldId = $this->id;
		$this->id = $argNoveId;
	}

	function getTitulek(){
		return $this->titulek;
	}

	function setTitulek($argNovyTitulek){
		$this->titulek = $argNovyTitulek;
	}

	function getMenu(){
		return $this->menu;
	}

	function setMenu($argNoveMenu){
		$this->menu= $argNoveMenu;
	}

	function getObrazek(){
		return $this->obrazek;
	}

	function setObrazek($argNovyObrazek){
		$this->obrazek = $argNovyObrazek;
	}

	function getObsah(){
		//uvnitr classy neexistuje promenna $instancePDO
		//musíme použít $GLOBALS["instancePDO] -- toto pole ma v sobe vsechny promenne nadefinovane mimo classu
		$prikaz = $GLOBALS["instancePDO"]->prepare("SELECT * FROM stranka WHERE id=?");
		$prikaz -> execute(array($this->id));
		$vysledekPrikazu = $prikaz -> fetch();

		if($vysledekPrikazu != NULL){
			return $vysledekPrikazu ["obsah"];
		}else {
			//stranka nebyla v databazi nalezena
			//vratime prazdny string
			return "";
		}

		//toto je stary zpusob, kdy jsme data meli v HTML
		//return file_get_contents("./{$this->id}.html");
	}

	function setObsah($argNovyObsah){
		$prikaz = $GLOBALS["instancePDO"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
		$prikaz -> execute(array($argNovyObsah,$this->id));		
		
		//return file_put_contents("./{$this->id}.html",$argNovyObsah);
	}	

}

//predelat pole stranek, aby se data tahaly z databaze
$poleStranek = array();
$prikaz = $instancePDO -> prepare ("SELECT * FROM stranka ORDER BY poradi ASC");
$prikaz -> execute(array());
$poleVysledku = $prikaz -> fetchAll();

foreach($poleVysledku AS $vysledek){
	$poleStranek[$vysledek["id"]] = new Stranka($vysledek["id"],$vysledek["titulek"],$vysledek["menu"],$vysledek["obrazek"]);
}


/* -- data ze souborů
$poleStranek = array(
	"domu" => new Stranka ("domu", "PrimaPenzion", "Domů", "primapenzion-main.jpg"),
	"galerie" => new Stranka ("galerie","Fotogalerie","Fotky","primapenzion-pool-min.jpg"),
	"rezervace" => new Stranka ("rezervace","Rezervace","Chci pokoj","primapenzion-room.jpg"),
	"kontakt" => new Stranka ("kontakt","Kontakt","Napište nám","primapenzion-room2.jpg"),
)
*/

/*
$poleStranek = array(
        "domu" => [
            "id" => "domu",
            "titulek" => "PrimaPenzion",
            "menu" => "Domů",
            "obrazek" => "primapenzion-main.jpg"
        ],
        "galerie" => [
            "id" => "galerie",
            "titulek" => "Fotogalerie",
            "menu" => "Fotky",
            "obrazek" => "primapenzion-pool-min.jpg"
        ],
        "rezervace" => [
            "id" => "rezervace",
            "titulek" => "Rezervace",
            "menu" => "Chci pokoj",
            "obrazek" => "primapenzion-room.jpg"
        ],
        "kontakt" => [
            "id" => "kontakt",
            "titulek" => "Kontakt",
            "menu" => "Napište nám",
            "obrazek" => "primapenzion-room2.jpg"
        ],
    );

*/
