-- MySQL dump 10.13  Distrib 8.0.18, for Win64 (x86_64)
--
-- Host: localhost    Database: myhotel
-- ------------------------------------------------------
-- Server version	5.7.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `noticia`
--

DROP TABLE IF EXISTS `noticia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `noticia` (
  `codNoticia` int(11) NOT NULL AUTO_INCREMENT,
  `titular` varchar(95) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `Categoria_idCategoria` int(11) NOT NULL,
  PRIMARY KEY (`codNoticia`),
  KEY `fk_Noticia_Categoria1_idx` (`Categoria_idCategoria`),
  CONSTRAINT `fk_Noticia_Categoria1` FOREIGN KEY (`Categoria_idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticia`
--

LOCK TABLES `noticia` WRITE;
/*!40000 ALTER TABLE `noticia` DISABLE KEYS */;
INSERT INTO `noticia` VALUES (1,'Se prevé un importante atasco este fin de semana','Las carreteras de salida de las grandes ciudades se encuentran colapsadas por la operación salida de Semana Santa.  Hay diez kilómetros de atasco en la carretera de Toledo, entre Getafe y Torrejón de la Calzada, en Madrid, y otros diez en la carretera de','n9.jpg','2020-05-02 00:00:00',3),(2,'La alimentación es muy importante','Dice la OMS que la alimentación es muy importante y hay que tenerla en cuenta siempre.','n1.jpg','2020-04-20 00:00:00',2),(4,'Aas3','sdadsadasdas','n8.jpg','2020-05-06 00:00:00',1),(5,'Aasdasdads','dasasdsadsa','n9.jpg','2020-05-04 00:00:00',1),(6,'qweqwqwe','wqeqwewqew','n9.jpg','2020-05-02 00:00:00',1),(7,'NPrueba','Prueba','n9.jpg','2019-12-07 00:00:00',1),(13,'PRUEBASBUENAS','PRUEBASBUENAS','n10.jpg','2020-05-02 00:00:00',1),(15,'5565555','Asaaaaaaaaaaas','n8.jpg','2020-05-07 12:40:50',1);
/*!40000 ALTER TABLE `noticia` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-11 23:12:01
