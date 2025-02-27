<?php

use PHPUnit\Framework\TestCase;

class TestPrenotazioni extends TestCase {

    public function testConnessione(){
        /* Si va a testare la connessione al database */
        $mockTest = $this->createMock(mysqli::class);

        $mockTest->method('connect_error')->willReturn(null);

        $this->assertNull($mockTest->connect_error);

    }

    public function testOrario() {
        /* Si va a testare il formato dell'orario, uno delle cose che ci ha dato problemi */
        $orario_1 = "11:00";
        $orario_2 = "20:70";

        $dateTime_1 = DateTime::createFromFormat('H:i:s', $orario_1 . ":00");
        /* Controlla se è nel formato corretto */
        $this->isInstanceOf(DateTime::class, $dateTime_1);
        /* Controlla se la variabile dell'utente è diversa dal formato che ci aspettiamo */
        $this->assertEquals($orario_1 . ":00", $dateTime_1->format('H:i:s')); 


        $dateTime_2 = DateTime::createFromFormat('H:i:s', $orario_2 . ":00");
        /* Se non è nel formato corretto, allora il test fallisce */
        $this->assertFalse( $dateTime_2);

    }


    public function testQuery() {
        /* Si va a testare la query di prenotazione */
        $mockConn = $this->createMock(mysqli::class);
        $mockStmt = $this->createMock(mysqli_stmt::class); 

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('bind_param')->willReturn(true);
        $mockStmt->method('execute')->willReturn(true);

        $mockRisultato = $this->createMock(mysqli_result::class);
        $mockRisultato->method('num_rows')->willReturn(1);
        $mockRisultato->method('fetch_assoc')->willReturn([
            'orario_inizio' => "8:00:00",  
            'orario_fine' => "20:00:00"
        ]);

        $mockStmt->method('get_result')->willReturn($mockRisultato);

        /* ===Simuliamo ora con dati fittizi la query di prenotazione=== */

        $reparto = "Oncologia";
        $codiceFiscale = "CMLFNC80B01H501W";
        $orario = "10:30:00";

        $sql = "SELECT orario_inizio, orario_fine FROM Dottore WHERE id_reparto = ?";
        if ($stmt = $mockConn->prepare($sql)) {
            $stmt->bind_param("s", $reparto);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $orario_inizio = $row['orario_inizio'];
                    $orario_fine = $row['orario_fine'];

                    if ($orario >= $orario_inizio && $orario <= $orario_fine) {
                        $sqlInsert = "INSERT INTO prenotazioni (id_reparto, codice_fiscale, data_ora) VALUES (?, ?, ?)";
                        if ($stmt = $mockConn->prepare($sqlInsert)) {
                            $stmt->bind_param("sss", $reparto, $codiceFiscale, $orario);
                            $this->assertTrue($stmt->execute());
                        }
                    }
                }
            }
        }
    }
}




