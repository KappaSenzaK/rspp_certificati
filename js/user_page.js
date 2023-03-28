let helpStatus = false;

function help() {
    if(helpStatus) {
        helpStatus = false;
        document.getElementById("help").innerHTML = "";
    } else {
        helpStatus = true;
        document.getElementById("help").innerHTML = "In questa schermata puoi visualizzare i certificati che hai inserito.<br>" +
                                                    "Premi sul pulsante 'Visualizza pdf' per visualizzare il documento in questione.<br>" +
                                                    "Se sei in possesso di un certificato specifico, potrai anche leggere la data di scadenza.<br>" +
                                                    "Se necessiti di fare una modifica ai dati, puoi premere sul pulsante 'Richiedi modifica',<br>" +
                                                    "così che l'amministratore possa darti la possibilità di ri-inserire i dati.";
    }
}