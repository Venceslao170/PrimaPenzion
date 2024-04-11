//

//vypneme vsechny odkazy pro mazani s classou labut (odkazy - smazat stranku)

// # pro ID . pro classu !!

const poleOdkazu = document.querySelectorAll(".labut");

for(let odkaz of poleOdkazu){

	odkaz.addEventListener("click", (udalost) => {
		//vypnuto
		udalost.preventDefault();	
		//zeptame se uzivatele pomocí confirm
		//vrací boolean, uzivatel muze potvrdit nebo odmitnout
		const odpovedUzivatele = confirm("Opravdu chcete stranku smazat?");

		if(odpovedUzivatele == true) {
			//zjistime kam odkaz vede
			const cilOdkazu = odkaz.getAttribute("href");
			//presmerujeme uzivatele ne cilOdkazu
			window.location.href = cilOdkazu;
		}

	})	
	
};


//vytvoříme sortable seznam

//selectneme seznam

const seznam = document.querySelector(".had");

//prevest vanilla element na jquery $()
//	$(".had").sortable(); můžu použit také jen element

$(seznam).sortable({
	update: () => {
		console.log("posilam ajax"); //jen tak 

		//toto nam vrátí poleId z našeho seznamu
		const poleId = $(seznam).sortable("toArray") ;

		console.log(poleId);
		//posleme formular do serveru
		$.ajax({
			type: "post",
			url: "./admin.php",
			data: {
				poleSerazenychId: poleId
			},
			dataType: "json",
			success: function (response) {
				console.log(response);
			}
		});
	}
});


