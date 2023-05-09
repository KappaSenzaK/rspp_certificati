<?php 

include 'utils/database-consts.php';

$email = $_POST['user'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$note = $_POST['note'];
$stato = $_POST['stato'];
$in_servizio = $_POST['in_servizio'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="w-50" style="padding-left: 50px">
    <h1>Info su utente: <?php echo $nome ?></h1>
    <div style="padding-bottom: 50px;">
        <button style="margin-bottom: 15px;" id="modifica" class="btn btn-success">Modifica</button>
        <form  id="modifica-form" action="./modifica_form.php" method="post">
            <div>
                Nome: <input type="text" id="nome" name="nome" value="<?php echo $nome ?>" disabled />
            </div>

            <div>
                Cognome: <input type="text" id="cognome" name="cognome" value="<?php echo $cognome ?>" disabled />
            </div>

            <div>
                Email: <input type="text" id="email" name="email" value="<?php echo $email.'@tulliobuzzi.edu.it' ?>" disabled />
            </div>

            <div>
                Stato: <select id="stato" name="stato" disabled>
                    <?php
                        $stati = personale_stato();
                        foreach($stati as $val) {
                            echo "<option value='$val'>$val</option>";
                        }
                    ?>
                </select>
            </div>

            <div>
                In servizio: <select id="in_servizio" name="in_servizio" disabled>
                    <?php
                        $in_servizio_enum = personale_in_servizio();
                        foreach($in_servizio_enum as $val) {
                            echo "<option value='$val'>$val</option>";
                        }
                    ?>
                </select>
            </div>

            <input type="submit" id="submit-btn" value="Accetta le modifiche" class="d-none btn btn-success" />
        </form>
    </div>

    <a href=cuccurullo_page.php>Ritorna alla pagina dell'RSPP </a>
    <script>
        let canModifica = false;
        let modifica = document.getElementById('modifica');

        let nome =document.getElementById('nome');
        let cognome =document.getElementById('cognome')
        let email =document.getElementById('email')
        let codiceFiscale =document.getElementById('codice_fiscale')
        let stato =document.getElementById('stato')
        let inServizio =document.getElementById('in_servizio')

        let submitBtn =document.getElementById('submit-btn')

        modifica.onclick = function (e) {
            toggleModifica();
        }

        
        function toggleModifica() {
            if(canModifica) {
                stato.disabled = true;
                inServizio.disabled = true;

                modifica.classList.remove('btn-secondary')
                modifica.classList.add('btn-success')

                submitBtn.classList.remove('d-block')
                submitBtn.classList.add('d-none')
            } else {
                alert("Ora puoi modificare l'utente")

                stato.disabled = false;
                inServizio.disabled = false;

                modifica.classList.remove('btn-success')
                modifica.classList.add('btn-secondary')
                
                submitBtn.classList.add('d-block')

            }
            canModifica = !canModifica
        }

    </script>
</body>
</html>