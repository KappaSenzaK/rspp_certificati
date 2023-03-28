let helpStatus = false;

function help() {
    if(helpStatus) {
        helpStatus = false;
        document.getElementById("help").innerHTML = "";
    } else {
        helpStatus = true;
        document.getElementById("help").innerHTML = "In questa schermata puoi inserire i certificati sulla sicurezza di cui sei gia in possesso.<br>" +
                                                    "Premi sul pulsante 'Scegli il file' per selezionare il file .pdf del certificato che vuoi inviare dal tuo dispositivo.<br>" + 
                                                    "Attenzione: se possiedi un certificato specifico, oltre al file .pdf dovrai inserire anche la data di scadenza !";
    }
}