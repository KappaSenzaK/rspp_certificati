<?php
    if(!isset($_SESSION['mail'])) {
        ?>
            <h1>Errore nell'accesso!</h1><br><br><br>
            <button id="helpButton" class="button" onclick="window.open('index.php'); window.close()"> Torna alla pagina di accesso </button>
        <?php 
        die();
    }
?>