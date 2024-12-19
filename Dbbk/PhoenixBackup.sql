-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: 5q_ombrello_phoenix
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `documenti`
--

DROP TABLE IF EXISTS `documenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documenti` (
  `id_documento` int(6) NOT NULL,
  `codiceFiscale` varchar(20) NOT NULL,
  PRIMARY KEY (`id_documento`),
  KEY `FK_codiceFiscale` (`codiceFiscale`),
  CONSTRAINT `FK_codiceFiscale` FOREIGN KEY (`codiceFiscale`) REFERENCES `utenti` (`codiceFiscale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documenti`
--

LOCK TABLES `documenti` WRITE;
/*!40000 ALTER TABLE `documenti` DISABLE KEYS */;
INSERT INTO `documenti` VALUES (3,'BNCMRA75D01H501X'),(4,'CMLFNC80B01H501W'),(5,'DLMNMR85C01H501V'),(6,'FRTGNN92E01H501U'),(8,'GHTMRA80G01H501S'),(7,'PLMZRT88F01H501T'),(9,'RNGNMR76H01H501R'),(1,'RSSMRA85M01H501Z'),(10,'TSTLRA83I01H501Q'),(2,'VRNGNN90A01H501Y');
/*!40000 ALTER TABLE `documenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `letto`
--

DROP TABLE IF EXISTS `letto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `letto` (
  `id_letto` int(6) NOT NULL,
  `isTaken` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_letto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `letto`
--

LOCK TABLES `letto` WRITE;
/*!40000 ALTER TABLE `letto` DISABLE KEYS */;
INSERT INTO `letto` VALUES (1,1),(2,1),(3,0),(4,0);
/*!40000 ALTER TABLE `letto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patologia`
--

DROP TABLE IF EXISTS `patologia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patologia` (
  `id_malattia` int(6) NOT NULL,
  PRIMARY KEY (`id_malattia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patologia`
--

LOCK TABLES `patologia` WRITE;
/*!40000 ALTER TABLE `patologia` DISABLE KEYS */;
INSERT INTO `patologia` VALUES (1),(2),(3),(4),(5),(6),(7),(8),(9),(10);
/*!40000 ALTER TABLE `patologia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patologia_documenti`
--

DROP TABLE IF EXISTS `patologia_documenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patologia_documenti` (
  `id_documento` int(6) NOT NULL,
  `id_malattia` int(6) NOT NULL,
  `codiceFiscale` varchar(20) NOT NULL,
  PRIMARY KEY (`id_documento`,`id_malattia`),
  KEY `FK_codiceFiscale_test` (`codiceFiscale`),
  KEY `FK_idMalattia_test` (`id_malattia`),
  CONSTRAINT `FK_codiceFiscale_test` FOREIGN KEY (`codiceFiscale`) REFERENCES `utenti` (`codiceFiscale`),
  CONSTRAINT `FK_idDocumento_test` FOREIGN KEY (`id_documento`) REFERENCES `documenti` (`id_documento`),
  CONSTRAINT `FK_idMalattia_test` FOREIGN KEY (`id_malattia`) REFERENCES `patologia` (`id_malattia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patologia_documenti`
--

LOCK TABLES `patologia_documenti` WRITE;
/*!40000 ALTER TABLE `patologia_documenti` DISABLE KEYS */;
INSERT INTO `patologia_documenti` VALUES (1,1,'RSSMRA85M01H501Z'),(2,2,'TSTLRA83I01H501Q');
/*!40000 ALTER TABLE `patologia_documenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reparto`
--

DROP TABLE IF EXISTS `reparto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reparto` (
  `id_reparto` int(6) NOT NULL,
  `id_malattia` int(6) NOT NULL,
  `nome_reparto` varchar(30) NOT NULL,
  PRIMARY KEY (`id_reparto`),
  KEY `FK_idMalattia` (`id_malattia`),
  CONSTRAINT `FK_idMalattia` FOREIGN KEY (`id_malattia`) REFERENCES `patologia` (`id_malattia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reparto`
--

LOCK TABLES `reparto` WRITE;
/*!40000 ALTER TABLE `reparto` DISABLE KEYS */;
INSERT INTO `reparto` VALUES (1,1,'Oncologia'),(2,2,'Neurologia'),(3,3,'Cardiologia'),(4,4,'Dermatologia');
/*!40000 ALTER TABLE `reparto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reparto_letto`
--

DROP TABLE IF EXISTS `reparto_letto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reparto_letto` (
  `id_reparto` int(6) NOT NULL,
  `id_letto` int(6) NOT NULL,
  PRIMARY KEY (`id_reparto`,`id_letto`),
  KEY `FK_IdLetto_Letto` (`id_letto`),
  CONSTRAINT `FK_IdLetto_Letto` FOREIGN KEY (`id_letto`) REFERENCES `letto` (`id_letto`),
  CONSTRAINT `FK_IdReparto_Reparto` FOREIGN KEY (`id_reparto`) REFERENCES `reparto` (`id_reparto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reparto_letto`
--

LOCK TABLES `reparto_letto` WRITE;
/*!40000 ALTER TABLE `reparto_letto` DISABLE KEYS */;
INSERT INTO `reparto_letto` VALUES (1,1),(1,2),(2,3),(3,4);
/*!40000 ALTER TABLE `reparto_letto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruoli`
--

DROP TABLE IF EXISTS `ruoli`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ruoli` (
  `tipoRuolo` varchar(30) NOT NULL DEFAULT '',
  `id_ruoli` int(6) NOT NULL,
  PRIMARY KEY (`id_ruoli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruoli`
--

LOCK TABLES `ruoli` WRITE;
/*!40000 ALTER TABLE `ruoli` DISABLE KEYS */;
INSERT INTO `ruoli` VALUES ('Dottore',1),('Infermiere',2),('Paziente',3);
/*!40000 ALTER TABLE `ruoli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utenti` (
  `codiceFiscale` varchar(20) NOT NULL DEFAULT '',
  `nome` varchar(100) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `data_nascita` date NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codiceFiscale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES ('BNCMRA75D01H501X','Anna','Bianchi','1975-03-20','anna.bianchi@gmail.com',NULL,'819b0643d6b89dc9b579fdfc9094f28e'),('CMLFNC80B01H501W','Francesca','Colombo','1980-04-25','francesca.colombo@gmail.com',NULL,'34cc93ece0ba9e3f6f235d4af979b16c'),('DLMNMR85C01H501V','Luca','De Luca','1985-05-30','luca.deluca@gmail.com',NULL,'db0edd04aaac4506f7edab03ac855d56'),('FRTGNN92E01H501U','Marco','Ferrari','1992-06-10','marco.ferrari@gmail.com',NULL,'218dd27aebeccecae69ad8408d9a36bf'),('GHTMRA80G01H501S','Simone','Gatti','1980-08-20','simone.gatti@gmail.com',NULL,'b25ef06be3b6948c0bc431da46c2c738'),('PLMZRT88F01H501T','Elena','Pellegrini','1988-07-15','elena.pellegrini@gmail.com',NULL,'00cdb7bb942cf6b290ceb97d6aca64a3'),('RNGNMR76H01H501R','Chiara','Rinaldi','1976-09-25','chiara.rinaldi@gmail.com',NULL,'5d69dd95ac183c9643780ed7027d128a'),('RSSMRA85M01H501Z','Mario','Rossi','1985-01-01','mario.rossi@gmail.com',NULL,'7c6a180b36896a0a8c02787eeafb0e4c'),('TSTLRA83I01H501Q','Alessandro','Tosi','1983-10-30','alessandro.tosi@gmail.com',NULL,'87e897e3b54a405da144968b2ca19b45'),('VRNGNN90A01H501Y','Giovanni','Verdi','1990-02-15','giovanni.verdi@gmail.com',NULL,'6cb75f652a9b52798eb6cf2201057c73');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti_ruoli`
--

DROP TABLE IF EXISTS `utenti_ruoli`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utenti_ruoli` (
  `codiceFiscale` varchar(20) NOT NULL,
  `id_ruoli` int(6) NOT NULL,
  PRIMARY KEY (`codiceFiscale`,`id_ruoli`),
  KEY `FK_idRuolo` (`id_ruoli`),
  CONSTRAINT `FK_codiceFiscale_utenti` FOREIGN KEY (`codiceFiscale`) REFERENCES `utenti` (`codiceFiscale`),
  CONSTRAINT `FK_idRuolo` FOREIGN KEY (`id_ruoli`) REFERENCES `ruoli` (`id_ruoli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti_ruoli`
--

LOCK TABLES `utenti_ruoli` WRITE;
/*!40000 ALTER TABLE `utenti_ruoli` DISABLE KEYS */;
INSERT INTO `utenti_ruoli` VALUES ('BNCMRA75D01H501X',3),('CMLFNC80B01H501W',1),('DLMNMR85C01H501V',2),('FRTGNN92E01H501U',3),('GHTMRA80G01H501S',2),('PLMZRT88F01H501T',1),('RNGNMR76H01H501R',3),('RSSMRA85M01H501Z',1),('TSTLRA83I01H501Q',1),('VRNGNN90A01H501Y',2);
/*!40000 ALTER TABLE `utenti_ruoli` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-19 21:11:16
