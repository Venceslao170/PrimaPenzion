<div class="foto">

<?php
	$slozka = "./upload/source/fotky-galerie";
	$poleNazvu = scandir($slozka);

		foreach($poleNazvu AS $nazev){

			if($nazev == "." || $nazev == ".."){
				continue;
			}

			//musime zjistit dimenze obrazku
			$poleInformaci = getimagesize("./$slozka/$nazev");
//var_dump($poleInformaci);

			echo"<a href='$slozka/$nazev'
			data-pswp-width='$poleInformaci[0]'
			data-pswp-height='$poleInformaci[1]'
			target='_blank'>
			<img src='$slozka/$nazev' alt='obr1' />
	  </a>";

		}
		?>

</div>
<!--module je novinka (zvětšuje scope-- místo používání var.. promenne se propíšou i do jiných
module znamena, ze budeme chtit cist z jineho JS souboru
-->
<link rel="stylesheet" href="./vendor/PhotoSwipe-master/dist/photoswipe.css">

<script type="module">
//importujeme třídu z jiného JS souboru
import PhotoSwipeLightbox from './vendor/PhotoSwipe-master/dist/photoswipe-lightbox.esm.js';

    const lightbox = new PhotoSwipeLightbox({
    gallery: '.foto',
    children: 'a',//anchor kotva
    pswpModule: () => import('./vendor/PhotoSwipe-master/dist/photoswipe.esm.js')
    });
    lightbox.init();
	
</script>