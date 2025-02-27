Per eseguire test con phpUnit abbiamo seguito questi passaggi, aiutati un po dal sommo poeta:
1.Verificare che si aveva phpUnit installato.
    Molto probabilmente verra riportato un errore dopo l'istruzione eseguita sulla repository del progetto: 
       a. "composer require --dev phpunit/phpunit"
       b. phpunit --version : 
            Comporterà un errore lungo dipeso dal fatto che Pearl è stato deprecato dalla versione php ^8.*
            senza andare troppo a fondo per evitare problemi più seri, ho disinstallato la cartella di Pearl nella 
            directory xampp/php/pearl[da disistallare]
2. Successivamente nella directory del progetto ho eseguito il comando "php vendor/bin/phpunit --version" per verificare che phpUnit fosse installato
   correttamente.
3. Ho modificato il file composer per adattare con la versione php, la versione di phpunit

