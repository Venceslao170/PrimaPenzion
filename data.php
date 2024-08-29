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
				
			$prikaz = $GLOBALS["instancePDO"]->prepare("SELECT MAX(poradi) AS nejvyssi_poradi FROM stranka");
			$prikaz->execute(array());
			$vysledekDotazu = $prikaz->fetch();
			$nejvyssiCislo = $vysledekDotazu["nejvyssi_poradi"];
				


			$prikaz = $GLOBALS["instancePDO"]->prepare("INSERT INTO stranka SET id=?, titulek=?, menu=?, obrazek=?, poradi =?");
			$prikaz -> execute(array($this->id, $this->titulek, $this->menu, $this->obrazek,$nejvyssiCislo+1));
		}

	}

	function smazSE(){
		$prikaz = $GLOBALS["instancePDO"]->prepare("DELETE FROM stranka WHERE id=?");
		$prikaz->execute(array($this->id));
	}
	
	//GET&SET
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
		
		$prikaz = $GLOBALS["instancePDO"]->prepare("SELECT * FROM stranka WHERE id=?");
		$prikaz -> execute(array($this->id));
		$vysledekPrikazu = $prikaz -> fetch();

		if($vysledekPrikazu != NULL){
			return $vysledekPrikazu ["obsah"];
		}else {
			
			return "";
		}		
	}

	function setObsah($argNovyObsah){
		$prikaz = $GLOBALS["instancePDO"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
		$prikaz -> execute(array($argNovyObsah,$this->id));	
			
	}	
}


$poleStranek = array();
$prikaz = $instancePDO -> prepare ("SELECT * FROM stranka ORDER BY poradi ASC");
$prikaz -> execute(array());
$poleVysledku = $prikaz -> fetchAll();

foreach($poleVysledku AS $vysledek){
	$poleStranek[$vysledek["id"]] = new Stranka($vysledek["id"],$vysledek["titulek"],$vysledek["menu"],$vysledek["obrazek"]);
}


