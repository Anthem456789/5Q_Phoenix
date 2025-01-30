<?php 

require __DIR__ . '/vendor/autoload.php';

$log = new Monolog\Logger('ANDREA');

/* Si crea un solo handler con il liv più basso cosi si può avere un unico file di log con i liv restanti.
*  Altrimenti bisognava creare un handler per ogni liv con un file diverso per ognuno. 
*/

$log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::DEBUG));

 $log->warning('Prova warning');

 $log->debug('Prova debug');
 
 $log->info('Prova INFO: Notizia importante -> sei appena stato rickrollato (SI TU)');

 $log->notice('Prova NOTICE: Imprevisti -> studi troppo (NON è vero , ma non mi importa');

 $log->alert('Prova ALERT:  Attento, ricordati di consegnare');

 $log->emergency('Prova EMERGENCY: non ti piacciono le ragazze ma i ragazzx');


