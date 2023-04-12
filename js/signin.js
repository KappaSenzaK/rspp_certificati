var help_f = true;
function help() {
    if(help_f) {
        help_f = false;
        document.getElementById('help_div').innerHTML = "<br><br>In questa pagina puoi inserire i tuoi dati per poi accedere alla piattaforma.<br>"+
                                                    "Tutti i dati inseriti verranno poi controllati dall'RSPP della scuola prima di essere validati.<br>" +
                                                    "<br>" +
                                                    "<table><tr><td>▸ Scrivere nome e cognome con l'iniziale maiuscola</td></tr>" +
                                                    "<tr><td>▸ Usare le lettere maiuscole nel codice fiscale</td></tr>" +
                                                    "<tr><td>▸ Nell'indirizzo e-mail, omettere la parte finale '@tulliobuzzi.edu.it'</td></tr>" +
                                                    "<tr><td>▸ Separare possibili secondi nomi e cognomi con uno spazio'</td></tr>" +
                                                    "<table>";
    }
    else
    {
        help_f = true;
        document.getElementById('help_div').innerHTML = "";
    }
}