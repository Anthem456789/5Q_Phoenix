<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulsante di Test</title>
    <style>
        .invisible {
            display: none;
        }
    </style>
</head>
<body>

    <button id="ToggleBox">Effettua Prenotazione</button>
    <div id="popupBox" class="invisible">Questo è il popup!</div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("ToggleBox");

            if (toggleButton) {
                toggleButton.addEventListener("click", function() {
                    const popupBox = document.getElementById("popupBox");
                    popupBox.classList.toggle("invisible");
                });
            }
        });
    </script>

</body>
</html>