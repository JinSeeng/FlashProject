//on récupére ce qui a été entré
let motdepasse = this.value;

//les éléments utiles 
let minuscule = document.querySelector("minuscule");
let majuscule = document.querySelector("majuscule");
let chiffre = document.querySelector("chiffre");
let special = document.querySelector("special");
let longueur = document.querySelector("longueur");

//verifier qu'on a des minuscules
if (/[a-z]/test(motdepasse)){;
//On passe en vert "valid"
    minuscule.classList.replace("valid","invalid");
}else{
//On passe en rouge "invalid"
    minuscule.classList.replace("valid","invalid");
}

//verifier qu'on a des majuscules
if (/[A-Z]/test(motdepasse)){;
    //On passe en vert "valid"
        majuscule.classList.replace("valid","invalid");
    }else{
    //On passe en rouge "invalid" 
        majuscule.classList.replace("valid","invalid");
    }

    //verifier qu'on a des chiffres
if (/[0-9]/test(motdepasse)){;
    //On passe en vert "valid"
        chiffre.classList.replace("valid","invalide");
    }else{
    //On passe en rouge "invalid" 
        chiffre.classList.replace("valid","invalid");
    }

    //verifier qu'on a des caractères spéciales
    if (/[#@&§$*£_-<>?!.;:%{}[]()]/test(motdepasse)){;
        //On passe en vert "valid"
            special.classList.replace("valid","invalide");
        }else{
        //on passe en rouge "invalid"
            special.classList.replace("valid","invalid");
        }
    
    //On verifie la longueur du mot de passe
    if (motdepasse.lenght >=8){
    //passer en vert "valid"
    special.classList.replace("valid","invalide");
}else{
    //passer en rouge "invalid" 
    special.classList.replace("valid","invalid");
}
    document.querySelector([type = "submit"]).style

   
    //les conditions de validité
    if (minuscule && majuscule && chiffre &&special && longueur){
        return ("mot de passe fort"); }
    if (minuscule && majuscule && chiffre){
        return ("mot de passe moyen"); }
    else
        return("mot de passe faible");