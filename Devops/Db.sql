

USE 5q_ombrello_phoenix; 

-- Inserimento dati nella tabella Ruoli
INSERT INTO Ruoli (tipoRuolo, id_ruoli) VALUES
('Dottore', 1),
('Infermiere', 2),
('Paziente', 3);

-- Inserimento dati nella tabella utenti
INSERT INTO utenti (codiceFiscale, nome, cognome, data_nascita, email, password) VALUES
('RSSMRA85M01H501Z', 'Mario', 'Rossi', '1985-01-01', 'mario.rossi@example.com', 'password1'),
('VRNGNN90A01H501Y', 'Giovanni', 'Verdi', '1990-02-15', 'giovanni.verdi@example.com', 'password2'),
('BNCMRA75D01H501X', 'Anna', 'Bianchi', '1975-03-20', 'anna.bianchi@example.com', 'password3'),
('CMLFNC80B01H501W', 'Francesca', 'Colombo', '1980-04-25', 'francesca.colombo@example.com', 'password4'),
('DLMNMR85C01H501V', 'Luca', 'De Luca', '1985-05-30', 'luca.deluca@example.com', 'password5'),
('FRTGNN92E01H501U', 'Marco', 'Ferrari', '1992-06-10', 'marco.ferrari@example.com', 'password6'),
('PLMZRT88F01H501T', 'Elena', 'Pellegrini', '1988-07-15', 'elena.pellegrini@example.com', 'password7'),
('GHTMRA80G01H501S', 'Simone', 'Gatti', '1980-08-20', 'simone.gatti@example.com', 'password8'),
('RNGNMR76H01H501R', 'Chiara', 'Rinaldi', '1976-09-25', 'chiara.rinaldi@example.com', 'password9'),
('TSTLRA83I01H501Q', 'Alessandro', 'Tosi', '1983-10-30', 'alessandro.tosi@example.com', 'password10');

-- Inserimento dati nella tabella Documenti
INSERT INTO Documenti (id_documento, codiceFiscale) VALUES
(1, 'RSSMRA85M01H501Z'),
(2, 'VRNGNN90A01H501Y'),
(3, 'BNCMRA75D01H501X'),
(4, 'CMLFNC80B01H501W'),
(5, 'DLMNMR85C01H501V'),
(6, 'FRTGNN92E01H501U'),
(7, 'PLMZRT88F01H501T'),
(8, 'GHTMRA80G01H501S'),
(9, 'RNGNMR76H01H501R'),
(10, 'TSTLRA83I01H501Q');

-- Inserimento dati nella tabella Patologia
INSERT INTO Patologia (id_malattia) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);

-- Inserimento dati nella tabella utenti_ruoli
INSERT INTO utenti_ruoli (codiceFiscale, id_ruoli) VALUES
('RSSMRA85M01H501Z', 1),
('VRNGNN90A01H501Y', 2),
('BNCMRA75D01H501X', 3),
('CMLFNC80B01H501W', 1),
('DLMNMR85C01H501V', 2),
('FRTGNN92E01H501U', 3),
('PLMZRT88F01H501T', 1),
('GHTMRA80G01H501S', 2),
('RNGNMR76H01H501R', 3),
('TSTLRA83I01H501Q', 1);

-- Inserimento dati nella tabella Patologia_Documenti
INSERT INTO Patologia_Documenti (id_documento, id_malattia, codiceFiscale) VALUES
(1, 1, 'RSSMRA85M01H501Z'),
(2, 2, 'TSTLRA83I01H501Q');