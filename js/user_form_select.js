let helpStatus = false;

function help() {
  if (helpStatus) {
    helpStatus = false;
    document.getElementById("help").innerHTML = "";
  } else {
    helpStatus = true;
    document.getElementById("help").innerHTML =
      "Seleziona il tipo di certificato che vuoi inviare.<br>" +
      "Se hai delle certificazioni ma non sai a che tipo associarle, clicca su 'Altro'.<br><br><br>";
  }
}