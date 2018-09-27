//Est appelé à un nouveau caractère dans la barre de recherche
function majSearch(){
    //Envoie le contenu de search
    getSeriesRecherche(document.getElementById("searchInput").value);

}

//Recherche des séries (barre de recherche)
function getSeriesRecherche(recherche){

    var request = new XMLHttpRequest();
    var url = 'https://api.trakt.tv/search/show?query='+recherche;
    request.open('GET', url);

    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('trakt-api-version', '2');
    request.setRequestHeader('trakt-api-key', '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6');

    request.onreadystatechange = function () {
            if (this.readyState === 4) {
                reponse=JSON.parse(this.responseText);

                //Récupère le basepath (url de base), cachée dans un champs hidden
				var basepath=document.getElementById("searchInputBasePath").value;


                //Si la recherche ne renvoie rien, reste sur les résultats précédents
				if(reponse[0] && reponse[1] && reponse[2]){

                    //Vide les champs, renseigne le titre et la date. Assigne ensuite l'url ajoutée au basepath
                    document.getElementById("search"+1).innerHTML=reponse[0]['show']['title']+"</br><span id='search1Date'>"+reponse[0]['show']['year']+"</span>";
                    document.getElementById("search"+1).setAttribute('href', basepath+'/serie/'+reponse[0]['show']['ids']['slug']);

                    document.getElementById("search"+2).innerHTML=reponse[1]['show']['title']+"</br><span id='search2Date'>"+reponse[1]['show']['year']+"</span>";
                    document.getElementById("search"+2).setAttribute('href', basepath+'/serie/'+reponse[1]['show']['ids']['slug']);

                    document.getElementById("search"+3).innerHTML=reponse[2]['show']['title']+"</br><span id='search3Date'>"+reponse[2]['show']['year']+"</span>";
                    document.getElementById("search"+3).setAttribute('href', basepath+'/serie/'+reponse[2]['show']['ids']['slug']);

                    //todo: lien vers série

				}
            }
    };

    request.send();


}


function displaySearch(){
    document.getElementById("results").classList.remove("hidden");
}

//todo: Ne marche pas, à vérifier
function hideSearch(){
    // document.getElementById("results").classList.add("hidden");
}


function note(note){
    star1=document.getElementById("star1");
    star2=document.getElementById("star2");
    star3=document.getElementById("star3");
    star4=document.getElementById("star4");
    star5=document.getElementById("star5");

    if(note>=1){star1.classList.add("checked")}
    if(note>=2){star2.classList.add("checked")}
    if(note>=3){star3.classList.add("checked")}
    if(note>=4){star4.classList.add("checked")}
    if(note>=5){star5.classList.add("checked")}

    if(note<5){star5.classList.remove("checked")}
    if(note<4){star4.classList.remove("checked")}
    if(note<3){star3.classList.remove("checked")}
    if(note<2){star2.classList.remove("checked")}
    if(note<1){star1.classList.remove("checked")}

}

