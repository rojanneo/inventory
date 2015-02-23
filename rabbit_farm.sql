/*
SQLyog Enterprise - MySQL GUI v8.14 
MySQL - 5.6.21 : Database - inventory
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`inventory` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `inventory`;

/*Table structure for table `aa_death` */

DROP TABLE IF EXISTS `aa_death`;

CREATE TABLE `aa_death` (
  `d_id` bigint(100) NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) DEFAULT NULL,
  `lid` bigint(100) DEFAULT NULL,
  `death_reason` text NOT NULL,
  PRIMARY KEY (`d_id`),
  UNIQUE KEY `rid` (`rid`),
  UNIQUE KEY `lid` (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `aa_death` */

insert  into `aa_death`(`d_id`,`rid`,`lid`,`death_reason`) values (1,16,NULL,'death');

/*Table structure for table `aa_family` */

DROP TABLE IF EXISTS `aa_family`;

CREATE TABLE `aa_family` (
  `f_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_DT` date NOT NULL,
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `aa_family` */

insert  into `aa_family`(`f_id`,`created_DT`) values (1,'2014-12-30');

/*Table structure for table `aa_family_to_be` */

DROP TABLE IF EXISTS `aa_family_to_be`;

CREATE TABLE `aa_family_to_be` (
  `buck_r_id` bigint(20) NOT NULL,
  `doe_r_id` bigint(20) NOT NULL,
  `ID` bigint(100) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

/*Data for the table `aa_family_to_be` */

insert  into `aa_family_to_be`(`buck_r_id`,`doe_r_id`,`ID`) values (116,116,1),(126,58,2),(126,59,3),(126,60,4),(126,61,5),(127,58,6),(127,59,7),(127,60,8),(127,61,9),(127,126,10),(127,127,11),(131,58,12),(131,59,13),(131,60,14),(131,61,15),(131,127,16),(133,58,17),(133,59,18),(133,60,19),(133,61,20),(133,127,21),(154,58,22),(154,59,23),(154,60,24),(154,61,25),(154,127,26),(158,58,27),(158,59,28),(158,60,29),(158,61,30),(158,127,31),(160,58,32),(160,59,33),(160,60,34),(160,61,35),(160,127,36),(162,58,37),(162,59,38),(162,60,39),(162,61,40),(162,127,41),(167,58,42),(167,59,43),(167,60,44),(167,61,45),(167,127,46),(169,58,47),(169,59,48),(169,60,49),(169,61,50),(169,127,51),(171,58,52),(171,59,53),(171,60,54),(171,61,55),(171,127,56),(172,58,57),(172,59,58),(172,60,59),(172,61,60),(172,127,61),(202,202,62),(203,202,63),(203,203,64),(204,202,65),(204,203,66),(204,204,67),(205,205,68),(208,205,69),(208,208,70),(220,202,71),(1,1,72),(1,202,73),(6,6,74),(6,202,75),(7,7,76),(7,203,77),(7,204,78),(7,205,79),(7,208,80),(8,7,81),(8,8,82),(8,203,83),(8,204,84),(8,205,85),(8,208,86),(9,7,87),(9,8,88),(9,9,89),(9,203,90),(9,204,91),(9,205,92),(9,208,93),(14,14,94),(14,202,95),(15,15,96),(15,203,97),(15,204,98),(15,205,99),(15,208,100),(16,15,101),(16,16,102),(16,203,103),(16,204,104),(16,205,105),(16,208,106),(17,15,107),(17,16,108),(17,17,109),(17,203,110),(17,204,111),(17,205,112),(17,208,113);

/*Table structure for table `aa_litter` */

DROP TABLE IF EXISTS `aa_litter`;

CREATE TABLE `aa_litter` (
  `l_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `f_id` bigint(20) NOT NULL,
  `DOB` date NOT NULL,
  `does_no` bigint(20) NOT NULL,
  `bucks_no` bigint(20) NOT NULL,
  PRIMARY KEY (`l_id`),
  KEY `f_id` (`f_id`),
  CONSTRAINT `aa_litter_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `aa_family` (`f_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Data for the table `aa_litter` */

insert  into `aa_litter`(`l_id`,`f_id`,`DOB`,`does_no`,`bucks_no`) values (38,1,'2015-02-20',0,0),(39,1,'2015-02-20',0,0),(40,1,'2015-02-20',0,0),(41,1,'2015-02-20',0,0),(42,1,'2015-02-20',0,0),(43,1,'2015-02-22',0,0),(44,1,'2015-02-14',0,0);

/*Table structure for table `aa_rabbits` */

DROP TABLE IF EXISTS `aa_rabbits`;

CREATE TABLE `aa_rabbits` (
  `r_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` enum('B','D') NOT NULL,
  `l_id` bigint(20) DEFAULT NULL,
  `f_id` bigint(20) DEFAULT NULL,
  `does_id` bigint(20) DEFAULT NULL,
  `buck_id` bigint(20) DEFAULT NULL,
  `last_given_birth` date DEFAULT NULL,
  `status` enum('0','1') NOT NULL,
  `rabbit_slug` varchar(100) NOT NULL,
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `rabbit_slug` (`rabbit_slug`),
  KEY `l_id` (`l_id`,`f_id`),
  KEY `f_id` (`f_id`),
  CONSTRAINT `aa_rabbits_ibfk_1` FOREIGN KEY (`l_id`) REFERENCES `aa_litter` (`l_id`),
  CONSTRAINT `aa_rabbits_ibfk_2` FOREIGN KEY (`f_id`) REFERENCES `aa_family` (`f_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `aa_rabbits` */

insert  into `aa_rabbits`(`r_id`,`type`,`l_id`,`f_id`,`does_id`,`buck_id`,`last_given_birth`,`status`,`rabbit_slug`) values (14,'B',NULL,1,0,0,NULL,'1','14'),(15,'D',NULL,1,0,0,NULL,'0','15'),(16,'D',NULL,1,0,0,NULL,'0','16'),(17,'D',NULL,1,0,0,NULL,'1','17');

/*Table structure for table `attribute_attributeset` */

DROP TABLE IF EXISTS `attribute_attributeset`;

CREATE TABLE `attribute_attributeset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `attribute_set_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`,`attribute_set_id`),
  KEY `attribute_set_id` (`attribute_set_id`),
  CONSTRAINT `attribute_attributeset_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attribute_attributeset_ibfk_2` FOREIGN KEY (`attribute_set_id`) REFERENCES `attribute_sets` (`attribute_set_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `attribute_attributeset` */

insert  into `attribute_attributeset`(`id`,`attribute_id`,`attribute_set_id`,`sort_order`) values (9,25,4,0),(11,27,4,2),(12,28,4,3),(13,29,4,4),(14,30,4,5),(15,16,5,0),(16,31,4,6),(19,34,4,9),(20,35,4,10),(21,27,6,0),(22,36,6,1),(24,38,4,11),(25,39,4,11),(26,40,4,12),(27,41,4,13),(28,42,4,14),(29,43,4,15),(30,36,4,8),(31,44,4,15),(32,46,4,16),(33,16,4,0);

/*Table structure for table `attribute_sets` */

DROP TABLE IF EXISTS `attribute_sets`;

CREATE TABLE `attribute_sets` (
  `attribute_set_id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_set_code` varchar(255) NOT NULL,
  `attribute_set_name` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`attribute_set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `attribute_sets` */

insert  into `attribute_sets`(`attribute_set_id`,`attribute_set_code`,`attribute_set_name`,`sort_order`) values (4,'rabbit','Rabbit',0),(5,'feed','Feed',1),(6,'litters','Litters',2);

/*Table structure for table `attribute_values` */

DROP TABLE IF EXISTS `attribute_values`;

CREATE TABLE `attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Data for the table `attribute_values` */

insert  into `attribute_values`(`id`,`attribute_id`,`value`,`sort_order`) values (11,25,'Male',0),(12,25,'Female',1),(13,25,'Litter',2),(14,37,'No',0),(15,37,'Yes',1),(16,38,'No',0),(17,38,'Yes',1),(18,39,'Parents',0),(19,39,'Parents to be',1),(20,39,'Products',2),(21,39,'Products to be ',3),(22,44,'No',0),(23,44,'yes',1),(24,46,'Adult Male',0),(25,46,'Adult Female',1),(26,46,'Pregnant/Lactating',2),(27,46,'Litters',3);

/*Table structure for table `attributes` */

DROP TABLE IF EXISTS `attributes`;

CREATE TABLE `attributes` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_code` varchar(255) NOT NULL,
  `attribute_default_value` text NOT NULL,
  `attribute_type` enum('number','text','date','select','multiselect') NOT NULL,
  `attribute_requires_editor` enum('0','1') NOT NULL,
  `attribute_admin_label` varchar(255) NOT NULL,
  `attribute_frontend_label` varchar(255) NOT NULL,
  `is_unique` enum('0','1') NOT NULL,
  `is_required` enum('0','1') NOT NULL,
  `is_used_for_variation` enum('0','1') NOT NULL,
  `is_hidden` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`attribute_id`),
  UNIQUE KEY `attribute_code` (`attribute_code`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

/*Data for the table `attributes` */

insert  into `attributes`(`attribute_id`,`attribute_code`,`attribute_default_value`,`attribute_type`,`attribute_requires_editor`,`attribute_admin_label`,`attribute_frontend_label`,`is_unique`,`is_required`,`is_used_for_variation`,`is_hidden`) values (16,'weight','0','number','0','Weight','Weight','0','1','','0'),(18,'test','test','select','0','test','test','0','0','0','0'),(25,'rabbit_gender','','select','0','Gender','Gender','0','0','','0'),(27,'rabbit_family_id','','number','0','Family ID','Family ID','0','0','0','0'),(28,'rabbit_dob','','date','0','DOB','DOB','0','0','0','0'),(29,'rabbit_latest_mate_date','','date','0','Mated Date','Mated Date','0','0','0','0'),(30,'rabbit_latest_pregnant_date','','date','0','Pregnant Date','Pregnant Date','0','0','0','0'),(31,'rabbit_latest_birth_date','0000-00-00','date','0','Latest Birth Date','Latest Birth Date','0','0','','0'),(33,'litters_born','','number','0','Litters Born','Litters Born','0','0','0','0'),(34,'rabbit_latest_weaning_date','','date','0','Latest Weaning Date','Latest Weaning Date','0','0','0','0'),(35,'rabbit_latest_culling_date','','date','0','Latest Culling Date','Latest Culling Date','0','0','0','0'),(36,'parent_id','','number','0','Parent Rabbit','Parent Rabbit','0','1','','1'),(37,'is_pregnant','','select','0','Is Pregnant','Is Pregnant','0','0','0','0'),(38,'is_matured','','select','0','Matured','Matured','0','1','0','0'),(39,'rabbit_group','','select','0','Rabbit Group','Rabbit Group','0','1','0','0'),(40,'recently_mated_buck','0','number','0','Recently Mated Buck','Recently Mated Buck','0','0','','1'),(41,'parent_doe_id','','number','0','Parent Doe (TO BE UPDATED BY SYSTEM)','Parent Doe','0','0','','1'),(42,'parent_buck_id','','number','0','Parent Buck (TO BE UPDATED BY SYSTEM)','Parent Buck','0','0','','1'),(43,'litter_id','','number','0','Litter Group','Litter Group','0','0','','1'),(44,'is_litter','','select','0','Is Litter','Is Litter','0','0','0','0'),(45,'','','number','0','','','0','0','0','0'),(46,'rabbit_feeding_group','','select','0','Feeding Group','Feeding Group','0','1','0','0');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_code` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_display_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `is_root` enum('0','1') NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`category_id`,`category_code`,`category_name`,`category_display_name`,`category_description`,`parent_id`,`is_root`,`sort_order`) values (2,'food','Food','Food','Food for rabbits',0,'1',0),(3,'feed','Feed','Feed','Feed for rabbits',2,'0',0),(4,'rabbits','Rabbits','Rabbits','',0,'0',0),(5,'litters','Litters','Litters','',0,'0',3);

/*Table structure for table `dead_rabbits` */

DROP TABLE IF EXISTS `dead_rabbits`;

CREATE TABLE `dead_rabbits` (
  `death_id` int(11) NOT NULL AUTO_INCREMENT,
  `rabbit_id` int(11) NOT NULL,
  `litter_id` int(11) DEFAULT NULL,
  `death_date` date NOT NULL,
  `death_reason` int(11) NOT NULL,
  PRIMARY KEY (`death_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `dead_rabbits` */

insert  into `dead_rabbits`(`death_id`,`rabbit_id`,`litter_id`,`death_date`,`death_reason`) values (1,16,NULL,'2015-02-23',1);

/*Table structure for table `death_reasons` */

DROP TABLE IF EXISTS `death_reasons`;

CREATE TABLE `death_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `death_reasons` */

insert  into `death_reasons`(`id`,`reason`) values (1,'Mouse'),(2,'Cold');

/*Table structure for table `product_attribute_value_date` */

DROP TABLE IF EXISTS `product_attribute_value_date`;

CREATE TABLE `product_attribute_value_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` date NOT NULL,
  `updated_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;

/*Data for the table `product_attribute_value_date` */

insert  into `product_attribute_value_date`(`id`,`attribute_id`,`product_id`,`value`,`updated_date`) values (1,28,1,'2015-02-02','2015-02-01'),(2,29,1,'0000-00-00','2015-02-01'),(3,30,1,'0000-00-00','2015-02-01'),(4,31,1,'0000-00-00','2015-02-01'),(5,34,1,'0000-00-00','2015-02-01'),(6,35,1,'0000-00-00','2015-02-01'),(7,28,2,'0000-00-00','2015-02-01'),(8,29,2,'0000-00-00','2015-02-01'),(9,30,2,'0000-00-00','2015-02-01'),(10,31,2,'0000-00-00','2015-02-01'),(11,34,2,'0000-00-00','2015-02-01'),(12,35,2,'0000-00-00','2015-02-01'),(13,28,3,'0000-00-00','2015-02-01'),(14,29,3,'0000-00-00','2015-02-01'),(15,30,3,'0000-00-00','2015-02-01'),(16,31,3,'0000-00-00','2015-02-01'),(17,34,3,'0000-00-00','2015-02-01'),(18,35,3,'0000-00-00','2015-02-01'),(19,28,4,'0000-00-00','2015-02-01'),(20,29,4,'0000-00-00','2015-02-01'),(21,30,4,'0000-00-00','2015-02-01'),(22,31,4,'0000-00-00','2015-02-01'),(23,34,4,'0000-00-00','2015-02-01'),(24,35,4,'0000-00-00','2015-02-01'),(25,28,5,'0000-00-00','2015-02-01'),(26,29,5,'0000-00-00','2015-02-01'),(27,30,5,'0000-00-00','2015-02-01'),(28,31,5,'0000-00-00','2015-02-01'),(29,34,5,'0000-00-00','2015-02-01'),(30,35,5,'0000-00-00','2015-02-01'),(31,28,6,'0000-00-00','2015-02-01'),(32,29,6,'2015-02-18','2015-02-18'),(33,30,6,'0000-00-00','2015-02-01'),(34,31,6,'0000-00-00','2015-02-01'),(35,34,6,'0000-00-00','2015-02-01'),(36,35,6,'0000-00-00','2015-02-01'),(37,28,7,'2015-01-01','2015-02-18'),(42,35,7,'0000-00-00','2015-02-18'),(43,28,8,'0000-00-00','2015-02-01'),(44,29,8,'0000-00-00','2015-02-01'),(45,30,8,'0000-00-00','2015-02-01'),(46,31,8,'0000-00-00','2015-02-01'),(47,34,8,'0000-00-00','2015-02-01'),(48,35,8,'0000-00-00','2015-02-01'),(49,28,9,'2015-01-01','2015-02-18'),(50,29,9,'2015-02-06','2015-02-18'),(51,30,9,'2015-01-22','2015-02-18'),(52,31,9,'2015-02-18','2015-02-18'),(53,34,9,'0000-00-00','2015-02-18'),(54,35,9,'0000-00-00','2015-02-18'),(55,28,6,'2015-01-01','2015-02-18'),(56,29,6,'2015-02-18','2015-02-18'),(57,30,6,'0000-00-00','2015-02-18'),(58,31,6,'0000-00-00','2015-02-18'),(59,34,6,'0000-00-00','2015-02-18'),(60,35,6,'0000-00-00','2015-02-18'),(61,28,7,'2015-01-01','2015-02-18'),(66,35,7,'0000-00-00','2015-02-18'),(67,28,8,'2015-01-01','2015-02-18'),(68,29,8,'0000-00-00','2015-02-18'),(69,30,8,'0000-00-00','2015-02-18'),(70,31,8,'0000-00-00','2015-02-18'),(71,34,8,'0000-00-00','2015-02-18'),(72,35,8,'0000-00-00','2015-02-18'),(73,28,9,'2015-01-01','2015-02-18'),(74,29,9,'2015-02-06','2015-02-18'),(75,30,9,'2015-01-22','2015-02-18'),(76,31,9,'2015-02-18','2015-02-18'),(77,34,9,'0000-00-00','2015-02-18'),(78,35,9,'0000-00-00','2015-02-18'),(79,28,10,'2015-02-01','2015-02-18'),(80,29,10,'0000-00-00','2015-02-18'),(81,30,10,'0000-00-00','2015-02-18'),(82,31,10,'0000-00-00','2015-02-18'),(83,34,10,'2015-02-16','2015-02-18'),(84,35,10,'0000-00-00','2015-02-18'),(85,28,11,'2015-02-18','2015-02-18'),(86,29,11,'0000-00-00','2015-02-18'),(87,30,11,'0000-00-00','2015-02-18'),(88,31,11,'0000-00-00','2015-02-18'),(89,34,11,'2015-02-18','2015-02-18'),(90,35,11,'0000-00-00','2015-02-18'),(91,28,12,'2015-02-18','2015-02-18'),(92,29,12,'0000-00-00','2015-02-18'),(93,30,12,'0000-00-00','2015-02-18'),(94,31,12,'0000-00-00','2015-02-18'),(95,34,12,'2015-02-18','2015-02-18'),(96,35,12,'0000-00-00','2015-02-18'),(101,28,13,'2015-02-18','2015-02-18'),(102,29,13,'0000-00-00','2015-02-18'),(103,30,13,'0000-00-00','2015-02-18'),(104,31,13,'0000-00-00','2015-02-18'),(105,34,13,'2015-02-18','2015-02-18'),(106,35,13,'0000-00-00','2015-02-18'),(107,29,7,'2015-02-06','2015-02-18'),(108,30,7,'2015-01-22','2015-02-18'),(109,31,7,'2015-01-28','2015-02-18'),(110,34,7,'0000-00-00','2015-02-18'),(111,28,14,'2015-01-01','2015-02-20'),(112,29,14,'2015-02-23','2015-02-23'),(113,30,14,'0000-00-00','2015-02-20'),(114,31,14,'0000-00-00','2015-02-20'),(115,34,14,'0000-00-00','2015-02-20'),(116,35,14,'0000-00-00','2015-02-20'),(117,28,15,'2015-01-01','2015-02-22'),(122,35,15,'0000-00-00','2015-02-22'),(123,28,16,'2015-01-01','2015-02-23'),(128,35,16,'0000-00-00','2015-02-23'),(129,28,17,'2015-01-01','2015-02-20'),(130,29,17,'0000-00-00','2015-02-20'),(131,30,17,'0000-00-00','2015-02-20'),(132,31,17,'0000-00-00','2015-02-20'),(133,34,17,'0000-00-00','2015-02-20'),(134,35,17,'0000-00-00','2015-02-20'),(135,28,18,'2015-02-20','2015-02-20'),(136,29,18,'0000-00-00','2015-02-20'),(137,30,18,'0000-00-00','2015-02-20'),(138,31,18,'0000-00-00','2015-02-20'),(139,34,18,'2014-12-20','2015-02-20'),(140,35,18,'0000-00-00','2015-02-20'),(141,28,19,'2015-02-01','2015-02-20'),(142,29,19,'0000-00-00','2015-02-20'),(143,30,19,'0000-00-00','2015-02-20'),(144,31,19,'0000-00-00','2015-02-20'),(145,34,19,'2015-02-18','2015-02-20'),(146,35,19,'0000-00-00','2015-02-20'),(147,28,21,'2015-02-01','2015-02-20'),(148,29,21,'0000-00-00','2015-02-20'),(149,30,21,'0000-00-00','2015-02-20'),(150,31,21,'0000-00-00','2015-02-20'),(151,34,21,'2015-02-18','2015-02-20'),(152,35,21,'0000-00-00','2015-02-20'),(153,28,22,'2015-02-01','2015-02-20'),(154,29,22,'0000-00-00','2015-02-20'),(155,30,22,'0000-00-00','2015-02-20'),(156,31,22,'0000-00-00','2015-02-20'),(157,34,22,'2015-02-19','2015-02-20'),(158,35,22,'0000-00-00','2015-02-20'),(159,28,23,'2015-02-01','2015-02-22'),(160,29,23,'0000-00-00','2015-02-22'),(161,30,23,'0000-00-00','2015-02-22'),(162,31,23,'0000-00-00','2015-02-22'),(163,34,23,'2015-02-19','2015-02-22'),(164,35,23,'0000-00-00','2015-02-22'),(169,29,15,'2015-02-23','2015-02-23'),(170,29,16,'2015-01-18','2015-02-23'),(171,30,16,'2015-02-01','2015-02-23'),(172,31,16,'2015-02-14','2015-02-23'),(173,34,16,'0000-00-00','2015-02-23');

/*Table structure for table `product_attribute_value_number` */

DROP TABLE IF EXISTS `product_attribute_value_number`;

CREATE TABLE `product_attribute_value_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` float NOT NULL,
  `updated_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_attribute_value_number_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=latin1;

/*Data for the table `product_attribute_value_number` */

insert  into `product_attribute_value_number`(`id`,`attribute_id`,`product_id`,`value`,`updated_date`) values (1,27,1,1,'2015-02-01'),(2,40,1,0,'2015-02-01'),(3,41,1,0,'2015-02-01'),(4,42,1,0,'2015-02-01'),(5,43,1,0,'2015-02-01'),(6,36,1,0,'2015-02-01'),(7,16,1,0,'2015-02-01'),(8,27,2,0,'2015-02-01'),(9,40,2,0,'2015-02-01'),(10,41,2,0,'2015-02-01'),(11,42,2,0,'2015-02-01'),(12,43,2,0,'2015-02-01'),(13,36,2,0,'2015-02-01'),(14,16,2,0,'2015-02-01'),(15,27,3,0,'2015-02-01'),(16,40,3,0,'2015-02-01'),(17,41,3,0,'2015-02-01'),(18,42,3,0,'2015-02-01'),(19,43,3,0,'2015-02-01'),(20,36,3,0,'2015-02-01'),(21,16,3,0,'2015-02-01'),(22,27,4,0,'2015-02-01'),(23,40,4,0,'2015-02-01'),(24,41,4,0,'2015-02-01'),(25,42,4,0,'2015-02-01'),(26,43,4,0,'2015-02-01'),(27,36,4,0,'2015-02-01'),(28,16,4,0,'2015-02-01'),(29,27,5,0,'2015-02-01'),(30,40,5,0,'2015-02-01'),(31,41,5,0,'2015-02-01'),(32,42,5,0,'2015-02-01'),(33,43,5,0,'2015-02-01'),(34,36,5,0,'2015-02-01'),(35,16,5,0,'2015-02-01'),(36,27,6,0,'2015-02-01'),(37,40,6,0,'2015-02-01'),(38,41,6,0,'2015-02-01'),(39,42,6,0,'2015-02-01'),(40,43,6,0,'2015-02-01'),(41,36,6,0,'2015-02-01'),(42,16,6,0,'2015-02-01'),(43,27,7,1,'2015-02-18'),(44,40,7,6,'2015-02-18'),(45,41,7,0,'2015-02-18'),(46,42,7,0,'2015-02-18'),(47,43,7,0,'2015-02-18'),(48,36,7,0,'2015-02-18'),(49,16,7,2.56,'2015-02-18'),(50,27,8,0,'2015-02-01'),(51,40,8,0,'2015-02-01'),(52,41,8,0,'2015-02-01'),(53,42,8,0,'2015-02-01'),(54,43,8,0,'2015-02-01'),(55,36,8,0,'2015-02-01'),(56,16,8,0,'2015-02-01'),(57,27,9,1,'2015-02-18'),(58,40,9,6,'2015-02-18'),(59,41,9,0,'2015-02-18'),(60,42,9,0,'2015-02-18'),(61,43,9,0,'2015-02-18'),(62,36,9,0,'2015-02-18'),(63,16,9,0,'2015-02-18'),(64,27,6,1,'2015-02-18'),(65,40,6,0,'2015-02-18'),(66,41,6,0,'2015-02-18'),(67,42,6,0,'2015-02-18'),(68,43,6,0,'2015-02-18'),(69,36,6,0,'2015-02-18'),(70,16,6,2.5,'2015-02-18'),(71,27,7,1,'2015-02-18'),(72,40,7,6,'2015-02-18'),(73,41,7,0,'2015-02-18'),(74,42,7,0,'2015-02-18'),(75,43,7,0,'2015-02-18'),(76,36,7,0,'2015-02-18'),(77,16,7,2.56,'2015-02-18'),(78,27,8,1,'2015-02-18'),(79,40,8,0,'2015-02-18'),(80,41,8,0,'2015-02-18'),(81,42,8,0,'2015-02-18'),(82,43,8,0,'2015-02-18'),(83,36,8,0,'2015-02-18'),(84,16,8,2.5,'2015-02-18'),(85,27,9,1,'2015-02-18'),(86,40,9,6,'2015-02-18'),(87,41,9,0,'2015-02-18'),(88,42,9,0,'2015-02-18'),(89,43,9,0,'2015-02-18'),(90,36,9,0,'2015-02-18'),(91,16,9,0,'2015-02-18'),(92,27,10,1,'2015-02-18'),(93,40,10,0,'2015-02-18'),(94,41,10,8,'2015-02-18'),(95,42,10,6,'2015-02-18'),(96,43,10,0,'2015-02-18'),(97,36,10,0,'2015-02-18'),(98,16,10,1,'2015-02-18'),(99,27,11,1,'2015-02-18'),(100,16,11,1.5,'2015-02-18'),(101,43,11,34,'2015-02-18'),(102,41,11,7,'2015-02-18'),(103,42,11,6,'2015-02-18'),(104,40,11,0,'2015-02-18'),(105,36,11,0,'2015-02-18'),(106,27,12,1,'2015-02-18'),(107,16,12,1,'2015-02-18'),(108,43,12,34,'2015-02-18'),(109,41,12,7,'2015-02-18'),(110,42,12,6,'2015-02-18'),(111,27,13,1,'2015-02-18'),(112,16,13,0,'2015-02-18'),(113,43,13,37,'2015-02-18'),(114,41,13,7,'2015-02-18'),(115,42,13,6,'2015-02-18'),(116,27,14,1,'2015-02-20'),(117,40,14,0,'2015-02-20'),(118,41,14,0,'2015-02-20'),(119,42,14,0,'2015-02-20'),(120,43,14,0,'2015-02-20'),(121,36,14,0,'2015-02-20'),(122,16,14,0,'2015-02-20'),(123,27,15,1,'2015-02-22'),(124,40,15,14,'2015-02-23'),(125,41,15,0,'2015-02-22'),(126,42,15,0,'2015-02-22'),(127,43,15,0,'2015-02-22'),(128,36,15,0,'2015-02-22'),(129,16,15,0,'2015-02-22'),(130,27,16,1,'2015-02-23'),(131,40,16,14,'2015-02-23'),(132,41,16,0,'2015-02-23'),(133,42,16,0,'2015-02-23'),(134,43,16,0,'2015-02-23'),(135,36,16,0,'2015-02-23'),(136,16,16,0,'2015-02-23'),(137,27,17,1,'2015-02-20'),(138,40,17,0,'2015-02-20'),(139,41,17,0,'2015-02-20'),(140,42,17,0,'2015-02-20'),(141,43,17,0,'2015-02-20'),(142,36,17,0,'2015-02-20'),(143,16,17,0,'2015-02-20'),(144,27,18,1,'2015-02-20'),(145,16,18,0,'2015-02-20'),(146,43,18,38,'2015-02-20'),(147,41,18,15,'2015-02-20'),(148,42,18,14,'2015-02-20'),(149,27,19,1,'2015-02-20'),(150,40,19,0,'2015-02-20'),(151,41,19,16,'2015-02-20'),(152,42,19,14,'2015-02-20'),(153,43,19,0,'2015-02-20'),(154,36,19,0,'2015-02-20'),(155,16,19,0,'2015-02-20'),(156,27,21,1,'2015-02-20'),(157,40,21,0,'2015-02-20'),(158,41,21,16,'2015-02-20'),(159,42,21,14,'2015-02-20'),(160,43,21,0,'2015-02-20'),(161,36,21,0,'2015-02-20'),(162,16,21,0,'2015-02-20'),(163,27,22,1,'2015-02-20'),(164,40,22,0,'2015-02-20'),(165,41,22,16,'2015-02-20'),(166,42,22,14,'2015-02-20'),(167,43,22,0,'2015-02-20'),(168,36,22,0,'2015-02-20'),(169,16,22,0,'2015-02-20'),(170,40,18,0,'2015-02-20'),(171,36,18,0,'2015-02-20'),(172,27,23,1,'2015-02-22'),(173,40,23,0,'2015-02-22'),(174,41,23,16,'2015-02-22'),(175,42,23,14,'2015-02-22'),(176,43,23,0,'2015-02-22'),(177,36,23,0,'2015-02-22'),(178,16,23,0,'2015-02-22');

/*Table structure for table `product_attribute_value_option` */

DROP TABLE IF EXISTS `product_attribute_value_option`;

CREATE TABLE `product_attribute_value_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `product_id` (`product_id`),
  KEY `value_id` (`value`),
  CONSTRAINT `product_attribute_value_option_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_attribute_value_option_ibfk_3` FOREIGN KEY (`value`) REFERENCES `attribute_values` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

/*Data for the table `product_attribute_value_option` */

insert  into `product_attribute_value_option`(`id`,`attribute_id`,`product_id`,`value`,`updated_date`) values (1,25,1,11,'2015-02-01'),(2,38,1,16,'2015-02-01'),(3,39,1,18,'2015-02-01'),(4,44,1,22,'2015-02-01'),(5,46,1,24,'2015-02-01'),(6,25,2,11,'2015-02-01'),(7,38,2,16,'2015-02-01'),(8,39,2,18,'2015-02-01'),(9,44,2,22,'2015-02-01'),(10,46,2,24,'2015-02-01'),(11,25,3,11,'2015-02-01'),(12,38,3,16,'2015-02-01'),(13,39,3,18,'2015-02-01'),(14,44,3,22,'2015-02-01'),(15,46,3,24,'2015-02-01'),(16,25,4,11,'2015-02-01'),(17,38,4,16,'2015-02-01'),(18,39,4,18,'2015-02-01'),(19,44,4,22,'2015-02-01'),(20,46,4,24,'2015-02-01'),(21,25,5,11,'2015-02-01'),(22,38,5,16,'2015-02-01'),(23,39,5,18,'2015-02-01'),(24,44,5,22,'2015-02-01'),(25,46,5,24,'2015-02-01'),(26,25,6,11,'2015-02-01'),(27,38,6,16,'2015-02-01'),(28,39,6,18,'2015-02-01'),(29,44,6,22,'2015-02-01'),(30,46,6,24,'2015-02-01'),(31,25,7,12,'2015-02-18'),(32,38,7,16,'2015-02-18'),(33,39,7,18,'2015-02-18'),(34,44,7,22,'2015-02-18'),(35,46,7,25,'2015-02-18'),(36,25,8,11,'2015-02-01'),(37,38,8,16,'2015-02-01'),(38,39,8,18,'2015-02-01'),(39,44,8,22,'2015-02-01'),(40,46,8,24,'2015-02-01'),(41,25,9,12,'2015-02-18'),(42,38,9,16,'2015-02-18'),(43,39,9,18,'2015-02-18'),(44,44,9,22,'2015-02-18'),(45,46,9,26,'2015-02-18'),(46,25,6,11,'2015-02-18'),(47,38,6,16,'2015-02-18'),(48,39,6,18,'2015-02-18'),(49,44,6,22,'2015-02-18'),(50,46,6,24,'2015-02-18'),(51,25,7,12,'2015-02-18'),(52,38,7,16,'2015-02-18'),(53,39,7,18,'2015-02-18'),(54,44,7,22,'2015-02-18'),(55,46,7,25,'2015-02-18'),(56,25,8,12,'2015-02-18'),(57,38,8,16,'2015-02-18'),(58,39,8,18,'2015-02-18'),(59,44,8,22,'2015-02-18'),(60,46,8,25,'2015-02-18'),(61,25,9,12,'2015-02-18'),(62,38,9,16,'2015-02-18'),(63,39,9,18,'2015-02-18'),(64,44,9,22,'2015-02-18'),(65,46,9,26,'2015-02-18'),(66,25,10,11,'2015-02-18'),(67,38,10,16,'2015-02-18'),(68,39,10,18,'2015-02-18'),(69,44,10,23,'2015-02-18'),(70,46,10,27,'2015-02-18'),(72,25,11,11,'2015-02-18'),(73,46,11,27,'2015-02-18'),(74,44,11,23,'2015-02-18'),(75,38,11,16,'2015-02-18'),(76,39,11,18,'2015-02-18'),(77,25,12,12,'2015-02-18'),(78,46,12,25,'2015-02-18'),(79,44,12,23,'2015-02-18'),(80,37,9,15,'2015-02-18'),(82,25,13,11,'2015-02-18'),(83,46,13,24,'2015-02-18'),(84,44,13,23,'2015-02-18'),(85,25,14,11,'2015-02-20'),(86,38,14,16,'2015-02-20'),(87,39,14,18,'2015-02-20'),(88,44,14,22,'2015-02-20'),(89,46,14,24,'2015-02-20'),(90,25,15,12,'2015-02-22'),(91,38,15,16,'2015-02-22'),(92,39,15,18,'2015-02-22'),(93,44,15,22,'2015-02-22'),(94,46,15,26,'2015-02-22'),(95,25,16,12,'2015-02-23'),(96,38,16,16,'2015-02-23'),(97,39,16,18,'2015-02-23'),(98,44,16,22,'2015-02-23'),(99,46,16,26,'2015-02-23'),(100,25,17,12,'2015-02-20'),(101,38,17,16,'2015-02-20'),(102,39,17,18,'2015-02-20'),(103,44,17,22,'2015-02-20'),(104,46,17,24,'2015-02-20'),(106,25,18,11,'2015-02-20'),(107,46,18,24,'2015-02-20'),(108,44,18,23,'2015-02-20'),(109,25,19,11,'2015-02-20'),(110,38,19,16,'2015-02-20'),(111,39,19,18,'2015-02-20'),(112,44,19,23,'2015-02-20'),(113,46,19,24,'2015-02-20'),(114,25,21,11,'2015-02-20'),(115,38,21,16,'2015-02-20'),(116,39,21,18,'2015-02-20'),(117,44,21,23,'2015-02-20'),(118,46,21,24,'2015-02-20'),(119,25,22,12,'2015-02-20'),(120,38,22,16,'2015-02-20'),(121,39,22,18,'2015-02-20'),(122,44,22,23,'2015-02-20'),(123,46,22,24,'2015-02-20'),(124,38,18,16,'2015-02-20'),(125,39,18,18,'2015-02-20'),(127,25,23,11,'2015-02-22'),(128,38,23,16,'2015-02-22'),(129,39,23,18,'2015-02-22'),(130,44,23,23,'2015-02-22'),(131,46,23,24,'2015-02-22');

/*Table structure for table `product_attribute_value_text` */

DROP TABLE IF EXISTS `product_attribute_value_text`;

CREATE TABLE `product_attribute_value_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `updated_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_attribute_value_text_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `product_attribute_value_text` */

/*Table structure for table `product_category` */

DROP TABLE IF EXISTS `product_category`;

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=latin1;

/*Data for the table `product_category` */

insert  into `product_category`(`id`,`product_id`,`category_id`,`sort_order`) values (1,3,2,0),(2,3,3,0),(3,4,2,0),(4,5,2,0),(5,5,3,0),(6,6,2,0),(7,6,3,0),(10,8,3,0),(25,17,3,0),(32,19,3,0),(49,21,4,0),(50,23,5,0),(51,24,4,0),(52,25,4,0),(53,26,4,0),(54,27,4,0),(57,32,4,0),(58,33,4,0),(59,34,4,0),(61,31,4,0),(62,30,4,0),(63,29,4,0),(64,28,4,0),(65,20,4,0),(68,14,4,0),(70,13,4,0),(71,12,4,0),(72,35,4,0),(73,36,4,0),(74,37,4,0),(75,38,4,0),(76,41,4,0),(77,42,4,0),(78,43,4,0),(79,44,4,0),(80,45,4,0),(81,46,4,0),(82,47,4,0),(83,48,4,0),(84,49,4,0),(85,56,4,0),(86,57,4,0),(91,62,4,0),(92,73,4,0),(93,75,4,0),(94,77,4,0),(95,78,4,0),(97,79,4,0),(98,80,4,0),(99,81,4,0),(100,82,4,0),(101,83,4,0),(102,84,4,0),(103,85,4,0),(104,86,4,0),(105,87,4,0),(106,88,4,0),(107,89,4,0),(108,90,4,0),(109,95,4,0),(110,96,4,0),(111,97,4,0),(112,98,4,0),(113,99,4,0),(114,100,4,0),(115,101,4,0),(116,102,4,0),(117,103,4,0),(118,104,4,0),(119,105,4,0),(120,106,4,0),(121,107,4,0),(122,108,4,0),(123,109,4,0),(124,110,4,0),(125,111,4,0),(126,112,4,0),(127,113,4,0),(128,114,4,0),(129,115,4,0),(131,116,4,0),(132,117,4,0),(133,118,4,0),(134,119,4,0),(135,120,4,0),(136,121,4,0),(137,122,4,0),(138,123,4,0),(139,124,4,0),(143,128,4,0),(144,129,4,0),(145,130,4,0),(146,131,4,0),(147,132,4,0),(148,133,4,0),(149,134,4,0),(150,135,4,0),(151,136,4,0),(152,137,4,0),(153,138,4,0),(154,139,4,0),(155,140,4,0),(156,141,4,0),(157,142,4,0),(158,143,4,0),(159,144,4,0),(160,145,4,0),(161,146,4,0),(162,147,4,0),(163,148,4,0),(164,149,4,0),(165,150,4,0),(166,151,4,0),(167,152,4,0),(168,153,4,0),(169,154,4,0),(170,155,4,0),(171,156,4,0),(172,157,4,0),(173,158,4,0),(174,159,4,0),(175,160,4,0),(176,161,4,0),(177,162,4,0),(178,163,4,0),(179,164,4,0),(180,165,4,0),(181,166,4,0),(186,125,4,0),(187,126,4,0),(188,127,4,0),(189,167,4,0),(190,168,4,0),(191,169,4,0),(192,170,4,0),(193,171,4,0),(194,172,4,0),(195,173,4,0),(196,174,4,0),(197,175,4,0),(198,176,4,0),(199,177,4,0),(200,178,4,0),(201,179,4,0),(202,180,4,0),(203,181,4,0),(205,183,4,0),(208,186,4,0),(218,58,4,0),(219,59,4,0),(220,60,4,0),(221,61,4,0),(222,182,4,0),(223,184,4,0),(224,185,4,0),(225,188,4,0),(226,189,4,0),(227,190,4,0),(228,191,4,0),(229,192,4,0),(230,193,4,0),(231,194,4,0),(232,195,4,0),(233,196,4,0),(234,197,4,0),(236,187,3,0),(237,199,4,0),(238,200,4,0),(239,201,4,0),(248,206,4,0),(249,207,4,0),(253,210,4,0),(254,209,4,0),(255,211,4,0),(256,212,4,0),(258,214,4,0),(265,213,4,0),(275,218,4,0),(280,219,4,0),(284,222,4,0),(285,223,4,0),(286,220,4,0),(290,224,4,0),(291,225,4,0),(292,226,4,0),(293,227,4,0),(294,221,4,0),(295,6,4,0),(310,11,4,0),(311,12,4,0),(366,13,4,0),(367,7,2,0),(368,7,3,0),(369,7,4,0),(374,18,4,0),(383,15,4,0),(388,16,3,0);

/*Table structure for table `products_inventory` */

DROP TABLE IF EXISTS `products_inventory`;

CREATE TABLE `products_inventory` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type_id` int(11) NOT NULL,
  `attribute_set_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(255) NOT NULL,
  `parent_doe_id` int(11) DEFAULT NULL,
  `parent_buck_id` int(11) DEFAULT NULL,
  `daily_use_status` enum('0','1') NOT NULL DEFAULT '0',
  `daily_use_quantity` double DEFAULT NULL,
  `product_quantity` double NOT NULL,
  `in_stock` enum('0','1') NOT NULL,
  `unit_price` double NOT NULL,
  `status` enum('0','1') NOT NULL,
  `is_variation` enum('0','1') NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_type` enum('in','out') NOT NULL,
  `is_sick` enum('0','1') NOT NULL DEFAULT '0',
  `is_dead` enum('0','1') NOT NULL DEFAULT '0',
  `is_slaughtered` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `product_type_id` (`product_type_id`,`attribute_set_id`),
  KEY `attribute_set_id` (`attribute_set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `products_inventory` */

insert  into `products_inventory`(`product_id`,`product_type_id`,`attribute_set_id`,`product_name`,`product_sku`,`parent_doe_id`,`parent_buck_id`,`daily_use_status`,`daily_use_quantity`,`product_quantity`,`in_stock`,`unit_price`,`status`,`is_variation`,`sort_order`,`created_date`,`updated_date`,`product_type`,`is_sick`,`is_dead`,`is_slaughtered`) values (14,1,4,'14','14',NULL,NULL,'0',0,1,'1',100,'1','0',0,'2015-02-20 00:00:00','2015-02-20 00:00:00','out','0','0','0'),(15,1,4,'15','15',NULL,NULL,'0',0,1,'1',100,'1','0',0,'2015-02-22 00:00:00','2015-02-22 00:00:00','out','0','0','0'),(16,1,4,'16','16',NULL,NULL,'0',0,1,'1',100,'1','0',0,'2015-02-23 00:00:00','2015-02-23 15:01:49','out','1','0','0'),(17,1,4,'17','17',NULL,NULL,'0',0,1,'1',100,'1','0',0,'2015-02-20 00:00:00','2015-02-23 14:25:12','out','1','0','0'),(23,1,4,'23','23',NULL,NULL,'0',0,1,'1',100,'1','0',0,'2015-02-22 00:00:00','2015-02-22 00:00:00','out','0','0','0');

/*Table structure for table `products_type` */

DROP TABLE IF EXISTS `products_type`;

CREATE TABLE `products_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type_code` varchar(255) NOT NULL,
  `product_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_type_code` (`product_type_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `products_type` */

insert  into `products_type`(`id`,`product_type_code`,`product_type_name`) values (1,'simple','Simple');

/*Table structure for table `purchase_orders` */

DROP TABLE IF EXISTS `purchase_orders`;

CREATE TABLE `purchase_orders` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `purchase_order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `purchase_order_recieve_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `purchase_order_assigned_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`purchase_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `purchase_orders` */

insert  into `purchase_orders`(`purchase_order_id`,`employee_id`,`purchase_order_date`,`purchase_order_recieve_date`,`purchase_order_assigned_date`) values (10,0,'2015-01-07 00:00:00','2015-01-07 00:00:00','2015-01-07 00:00:00');

/*Table structure for table `purchaseorder_product` */

DROP TABLE IF EXISTS `purchaseorder_product`;

CREATE TABLE `purchaseorder_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `unit_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_id` (`purchase_order_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchaseorder_product_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`purchase_order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `purchaseorder_product` */

insert  into `purchaseorder_product`(`id`,`purchase_order_id`,`product_id`,`product_name`,`product_sku`,`unit_price`,`quantity`,`total_price`) values (12,10,16,'Grass','grass',100,10,1000),(13,10,187,'Hay','hay',100,100,10000);

/*Table structure for table `rabbit_daily_feeds` */

DROP TABLE IF EXISTS `rabbit_daily_feeds`;

CREATE TABLE `rabbit_daily_feeds` (
  `daily_feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feeding_group_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `weight_group_id` int(11) NOT NULL,
  `quantity` float NOT NULL,
  PRIMARY KEY (`daily_feed_id`),
  KEY `product_id` (`product_id`),
  KEY `weight_group_id` (`weight_group_id`),
  CONSTRAINT `rabbit_daily_feeds_ibfk_2` FOREIGN KEY (`weight_group_id`) REFERENCES `rabbit_weight_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rabbit_daily_feeds` */

/*Table structure for table `rabbit_litters` */

DROP TABLE IF EXISTS `rabbit_litters`;

CREATE TABLE `rabbit_litters` (
  `litter_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `parent_buck_id` int(11) DEFAULT NULL,
  `family_id` int(11) NOT NULL,
  `litter_group_id` int(11) DEFAULT NULL,
  `litters_dob` date NOT NULL,
  `litters_weaning_date` date NOT NULL,
  `litter_weight` float DEFAULT NULL,
  `updated_date` date NOT NULL,
  `rabbit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`litter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

/*Data for the table `rabbit_litters` */

insert  into `rabbit_litters`(`litter_id`,`parent_id`,`parent_buck_id`,`family_id`,`litter_group_id`,`litters_dob`,`litters_weaning_date`,`litter_weight`,`updated_date`,`rabbit_id`) values (58,16,14,1,43,'2015-02-01','0000-00-00',NULL,'2015-02-22',23),(59,16,14,1,44,'2015-02-14','0000-00-00',NULL,'0000-00-00',NULL);

/*Table structure for table `rabbit_still_birth` */

DROP TABLE IF EXISTS `rabbit_still_birth`;

CREATE TABLE `rabbit_still_birth` (
  `still_birth_id` int(11) NOT NULL AUTO_INCREMENT,
  `rabbit_id` int(11) NOT NULL,
  `still_birth_date` date NOT NULL,
  `still_birth_reason` text NOT NULL,
  PRIMARY KEY (`still_birth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `rabbit_still_birth` */

insert  into `rabbit_still_birth`(`still_birth_id`,`rabbit_id`,`still_birth_date`,`still_birth_reason`) values (1,15,'2015-02-22','Still birth by accident'),(2,15,'2015-02-22','Another Still Birth\r\n');

/*Table structure for table `rabbit_weight_group` */

DROP TABLE IF EXISTS `rabbit_weight_group`;

CREATE TABLE `rabbit_weight_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `min_weight` float NOT NULL,
  `max_weight` float DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `rabbit_weight_group` */

insert  into `rabbit_weight_group`(`id`,`min_weight`,`max_weight`,`name`) values (1,0.6,0.7,'0.6 - 0.7 kg'),(2,1,2,'1 kg - 2 kg'),(3,2,3,'2 kg - 3 kg'),(4,3,4,'3 kg - 4 kg'),(5,4,5,'4 kg - 5 kg'),(6,5,NULL,'5+ kg');

/*Table structure for table `sick_rabbits` */

DROP TABLE IF EXISTS `sick_rabbits`;

CREATE TABLE `sick_rabbits` (
  `sick_rabbit_id` int(11) NOT NULL AUTO_INCREMENT,
  `rabbit_id` int(11) NOT NULL,
  `sick_date` date NOT NULL,
  `sick_reason` int(11) NOT NULL,
  PRIMARY KEY (`sick_rabbit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `sick_rabbits` */

insert  into `sick_rabbits`(`sick_rabbit_id`,`rabbit_id`,`sick_date`,`sick_reason`) values (1,17,'2015-02-23',2);

/*Table structure for table `sick_reasons` */

DROP TABLE IF EXISTS `sick_reasons`;

CREATE TABLE `sick_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `sick_reasons` */

insert  into `sick_reasons`(`id`,`reason`) values (1,'Diarrhoea'),(2,'Loss of Hair'),(3,'Paralysis');

/*Table structure for table `stocks` */

DROP TABLE IF EXISTS `stocks`;

CREATE TABLE `stocks` (
  `sid` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(100) NOT NULL,
  `Quantity` bigint(100) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `stocks` */

insert  into `stocks`(`sid`,`product_id`,`Quantity`,`date`) values (23,16,23,'2015-01-08'),(24,127,5,'2015-01-08'),(26,187,0,'2015-01-08');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
