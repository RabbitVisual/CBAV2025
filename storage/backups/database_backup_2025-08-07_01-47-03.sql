-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: cbav
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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campanhas`
--

DROP TABLE IF EXISTS `campanhas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campanhas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tipo` enum('construcao','missao','social','equipamentos','outros') NOT NULL DEFAULT 'outros',
  `meta_valor` decimal(10,2) DEFAULT NULL,
  `valor_arrecadado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `status` enum('ativa','pausada','concluida','cancelada') NOT NULL DEFAULT 'ativa',
  `imagem` varchar(255) DEFAULT NULL,
  `qr_code_pix` text DEFAULT NULL,
  `chave_pix` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campanhas`
--

LOCK TABLES `campanhas` WRITE;
/*!40000 ALTER TABLE `campanhas` DISABLE KEYS */;
INSERT INTO `campanhas` VALUES (1,'Reforma do Templo','Campanha para reforma completa do templo principal, incluindo sistema de som, iluminação e conforto','outros',150000.00,87500.00,'2025-05-04','2025-10-04','ativa',NULL,NULL,NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(2,'Ação Social - Natal','Campanha para ajudar famílias carentes no Natal, com cestas básicas e brinquedos','outros',25000.00,18750.00,'2025-07-04','2025-09-03','ativa',NULL,NULL,NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,'Missões Internacionais','Campanha para apoiar missionários em campo e projetos internacionais','outros',50000.00,32500.00,'2025-06-04','2025-12-04','ativa',NULL,NULL,NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,'Veículo para Missões','Campanha para aquisição de um veículo para facilitar o trabalho missionário','outros',80000.00,45000.00,'2025-04-04','2026-02-04','ativa',NULL,NULL,NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,'Sistema de Som','Campanha para modernização do sistema de som da igreja','outros',35000.00,28000.00,'2025-07-04','2025-11-04','ativa',NULL,NULL,NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,'Reforma do Templo','Campanha para reforma e manutenção do templo da igreja. Reforma do telhado, pintura e melhorias na estrutura','outros',50000.00,15000.00,'2024-01-01','2024-12-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(7,'Compra de Instrumentos','Campanha para aquisição de instrumentos musicais para o ministério de louvor. Guitarra, baixo, bateria e equipamentos de som','outros',15000.00,8000.00,'2024-02-01','2024-08-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(8,'Ação Social - Natal','Campanha para distribuição de cestas básicas e brinquedos no Natal. Cestas básicas, brinquedos e roupas para famílias carentes','outros',10000.00,5000.00,'2024-11-01','2024-12-25','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(9,'Evangelismo - Projeto Luz','Campanha para evangelização e distribuição de Bíblias. Compra de Bíblias, material evangelístico e eventos','outros',8000.00,3000.00,'2024-03-01','2024-09-30','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(10,'Tecnologia - Transmissão','Campanha para equipamentos de transmissão ao vivo. Câmeras, computador e equipamentos de transmissão','outros',25000.00,12000.00,'2024-01-15','2024-06-30','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(11,'Missões - África','Campanha para apoio missionário na África. Apoio ao missionário João Silva na África','outros',20000.00,15000.00,'2024-01-01','2024-12-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(12,'Jovens - Acampamento','Campanha para acampamento de jovens. Acampamento de jovens no final de semana','outros',5000.00,2500.00,'2024-05-01','2024-07-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(13,'Infantil - Material Didático','Campanha para material didático do ministério infantil. Material escolar, livros e brinquedos educativos','outros',3000.00,1500.00,'2024-02-15','2024-05-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(14,'Intercessão - Retiro','Campanha para retiro de intercessão. Retiro espiritual para o ministério de intercessão','outros',4000.00,2000.00,'2024-04-01','2024-06-30','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(15,'Hospitalidade - Cozinha','Campanha para equipamentos da cozinha da igreja. Geladeira, fogão e utensílios','outros',8000.00,4000.00,'2024-03-01','2024-08-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(16,'Ensino - Biblioteca','Campanha para biblioteca da igreja. Livros, estantes e material de estudo','outros',6000.00,3000.00,'2024-01-01','2024-10-31','ativa',NULL,NULL,NULL,1,'2025-08-06 02:39:31','2025-08-06 02:39:31'),(17,'Campanha Concluída - 2023','Campanha de 2023 que foi concluída com sucesso. Todos os objetivos foram atingidos','outros',10000.00,10000.00,'2023-01-01','2023-12-31','concluida',NULL,NULL,NULL,0,'2025-08-06 02:39:31','2025-08-06 02:39:31');
/*!40000 ALTER TABLE `campanhas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos`
--

DROP TABLE IF EXISTS `cargos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `departamento_id` bigint(20) unsigned NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `sistema` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cargos_departamento_id_foreign` (`departamento_id`),
  CONSTRAINT `cargos_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
INSERT INTO `cargos` VALUES (21,'Músico','Músico do ministério de louvor',6,1,0,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(22,'Vocalista','Vocalista do grupo',1,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(23,'Técnico de Som','Responsável pelo sistema de som',27,1,0,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(24,'Operador de Multimídia','Operador de projeção',4,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(25,'Líder de Jovens','Líder do ministério de jovens',25,1,0,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(26,'Organizador de Eventos','Organizador de eventos',6,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(27,'Evangelista Jovem','Evangelista jovem',7,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(28,'Professor Infantil','Professor da escola bíblica',8,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(29,'Recreador','Recreador infantil',9,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(30,'Ator Infantil','Ator do teatro infantil',10,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(31,'Intercessor','Membro do ministério de intercessão',24,1,0,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(32,'Coordenador de Vigília','Coordenador de vigílias',12,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(33,'Assistente Social','Assistente social',13,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(34,'Distribuidor de Alimentos','Distribuidor de alimentos',14,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(35,'Visitador Hospitalar','Visitador hospitalar',15,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(36,'Discipulador','Discipulador',16,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(37,'Professor da EBD','Professor da escola bíblica',17,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(38,'Missionário','Missionário',18,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(39,'Evangelista','Membro do ministério de evangelismo',26,1,0,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(40,'Aconselhador','Aconselhador familiar',20,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(41,'Coordenador de Casais','Coordenador de casais',21,1,0,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(42,'Pastor','Líder espiritual da igreja',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(43,'Pastor Auxiliar','Pastor auxiliar na liderança espiritual',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(44,'Tesoureiro','Responsável pela gestão financeira da igreja',23,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(45,'Secretário','Responsável pela documentação e registros da igreja',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(46,'Líder de Ministério','Líder de um ministério específico',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(47,'Coordenador','Coordenador de área ou departamento',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(48,'Professor EBD','Professor da Escola Bíblica Dominical',25,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(49,'Cantor','Cantor do ministério de louvor',6,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(50,'Auxiliar','Auxiliar em diversos ministérios',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(51,'Membro','Membro da igreja',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(52,'Visitante','Visitante da igreja',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(53,'Diácono','Diácono da igreja',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(54,'Presbítero','Presbítero da igreja',24,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(55,'Conselheiro','Membro do conselho da igreja',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(56,'Líder de Crianças','Líder do ministério infantil',25,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(57,'Operador de Mídia','Responsável pela mídia e transmissões',27,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(58,'Porteiro','Responsável pela segurança e recepção',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(59,'Zelador','Responsável pela manutenção e limpeza',22,1,0,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracoes`
--

DROP TABLE IF EXISTS `configuracoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chave` varchar(255) NOT NULL,
  `modulo` varchar(255) NOT NULL DEFAULT 'sistema',
  `valor` text NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'string',
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracoes_chave_unique` (`chave`),
  KEY `configuracoes_modulo_chave_index` (`modulo`,`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracoes`
--

LOCK TABLES `configuracoes` WRITE;
/*!40000 ALTER TABLE `configuracoes` DISABLE KEYS */;
INSERT INTO `configuracoes` VALUES (1,'igreja_nome','sistema','Congregação Batista Avenida','string','Nome da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(2,'igreja_endereco','sistema','Rua da Avenida, 123 - Centro, São Paulo - SP','string','Endereço da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(3,'igreja_telefone','sistema','(11) 99999-9999','string','Telefone da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(4,'igreja_email','sistema','contato@cbav.com','string','Email da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(5,'stripe_public_key','sistema','','string','Chave pública do Stripe','2025-08-04 20:38:57','2025-08-04 20:38:57'),(6,'stripe_secret_key','sistema','','string','Chave secreta do Stripe','2025-08-04 20:38:57','2025-08-04 20:38:57'),(7,'mercadopago_public_key','sistema','','string','Chave pública do Mercado Pago','2025-08-04 20:38:57','2025-08-04 20:38:57'),(8,'mercadopago_access_token','sistema','','string','Token de acesso do Mercado Pago','2025-08-04 20:38:57','2025-08-04 20:38:57'),(9,'pix_chave','sistema','teste@teste.com','text','Chave PIX','2025-08-04 20:38:57','2025-08-06 04:57:50'),(10,'igreja_slogan','sistema','Uma igreja comprometida com o amor de Cristo','string','Slogan da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(11,'igreja_website','sistema','https://cbav.com','string','Website da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(12,'igreja_facebook','sistema','cbav.igreja','string','Facebook da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(13,'igreja_instagram','sistema','@cbav_igreja','string','Instagram da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(14,'igreja_youtube','sistema','CBAV Igreja','string','YouTube da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(15,'secao_sobre_ativa','sistema','1','boolean','Seção sobre ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(16,'secao_ministerios_ativa','sistema','1','boolean','Seção ministérios ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(17,'secao_cultos_ativa','sistema','1','boolean','Seção cultos ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(18,'secao_aniversariantes_ativa','sistema','1','boolean','Seção aniversariantes ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(19,'secao_doacao_ativa','sistema','1','boolean','Seção doação ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(20,'secao_contato_ativa','sistema','1','boolean','Seção contato ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(21,'sobre_titulo','sistema','Sobre Nossa Igreja','string','Título da seção sobre','2025-08-04 20:38:57','2025-08-06 02:35:17'),(22,'cultos_titulo','sistema','Horários de Cultos','string','Título da seção cultos','2025-08-04 20:38:57','2025-08-06 02:35:17'),(23,'cultos_subtitulo','sistema','Venha adorar conosco e crescer na fé através da Palavra de Deus','string','Subtítulo da seção cultos','2025-08-04 20:38:57','2025-08-06 02:35:17'),(24,'doacao_titulo','sistema','Faça uma Doação','string','Título da seção doação','2025-08-04 20:38:57','2025-08-06 02:35:17'),(25,'doacao_subtitulo','sistema','Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!','string','Subtítulo da seção doação','2025-08-04 20:38:57','2025-08-06 02:35:17'),(26,'contato_titulo','sistema','Entre em Contato','string','Título da seção contato','2025-08-04 20:38:57','2025-08-06 02:35:17'),(27,'contato_subtitulo','sistema','Estamos aqui para você. Entre em contato conosco!','string','Subtítulo da seção contato','2025-08-04 20:38:57','2025-08-06 02:35:17'),(28,'principios_batistas_ativa','sistema','1','boolean','Princípios batistas ativos','2025-08-04 20:38:57','2025-08-06 02:35:17'),(29,'principios_batistas_titulo','sistema','Nossos Princípios Batistas','string','Título dos princípios batistas','2025-08-04 20:38:57','2025-08-06 02:35:17'),(30,'principio_1_titulo','sistema','Sola Scriptura','string','Título do primeiro princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(31,'principio_1_descricao','sistema','A Bíblia como única regra de fé e prática','string','Descrição do primeiro princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(32,'principio_2_titulo','sistema','Sacerdócio Universal','string','Título do segundo princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(33,'principio_2_descricao','sistema','Todo crente tem acesso direto a Deus','string','Descrição do segundo princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(34,'principio_3_titulo','sistema','Batismo por Imersão','string','Título do terceiro princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(35,'principio_3_descricao','sistema','Para crentes professos, por imersão','string','Descrição do terceiro princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(36,'principio_4_titulo','sistema','Autonomia Local','string','Título do quarto princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(37,'principio_4_descricao','sistema','Cada igreja é independente e autônoma','string','Descrição do quarto princípio','2025-08-04 20:38:57','2025-08-06 02:35:17'),(38,'culto_domingo_manha_titulo','sistema','Culto de Domingo - Manhã','string','Título do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(39,'culto_domingo_manha_horario','sistema','09:00h','string','Horário do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(40,'culto_domingo_manha_descricao','sistema','Culto de adoração e pregação da Palavra de Deus','string','Descrição do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(41,'culto_domingo_manha_item1','sistema','Louvor e Adoração','string','Item 1 do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(42,'culto_domingo_manha_item2','sistema','Pregação da Palavra','string','Item 2 do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(43,'culto_domingo_manha_item3','sistema','Oração e Intercessão','string','Item 3 do culto domingo manhã','2025-08-04 20:38:57','2025-08-06 02:35:17'),(44,'culto_domingo_noite_titulo','sistema','Culto de Domingo - Noite','string','Título do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(45,'culto_domingo_noite_horario','sistema','18:00h','string','Horário do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(46,'culto_domingo_noite_descricao','sistema','Culto de celebração e edificação espiritual','string','Descrição do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(47,'culto_domingo_noite_item1','sistema','Louvor e Adoração','string','Item 1 do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(48,'culto_domingo_noite_item2','sistema','Pregação da Palavra','string','Item 2 do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(49,'culto_domingo_noite_item3','sistema','Oração e Intercessão','string','Item 3 do culto domingo noite','2025-08-04 20:38:57','2025-08-06 02:35:17'),(50,'culto_quarta_titulo','sistema','Culto de Quarta-feira','string','Título do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(51,'culto_quarta_horario','sistema','19:30h','string','Horário do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(52,'culto_quarta_descricao','sistema','Culto de oração e estudo bíblico','string','Descrição do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(53,'culto_quarta_item1','sistema','Oração e Intercessão','string','Item 1 do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(54,'culto_quarta_item2','sistema','Estudo Bíblico','string','Item 2 do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(55,'culto_quarta_item3','sistema','Comunhão','string','Item 3 do culto quarta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(56,'escola_dominical_ativa','sistema','1','boolean','Escola dominical ativa','2025-08-04 20:38:57','2025-08-06 02:35:17'),(57,'escola_dominical_titulo','sistema','Escola Dominical','string','Título da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(58,'escola_dominical_horario','sistema','Domingo às 08:00h','string','Horário da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(59,'escola_dominical_descricao','sistema','Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.','string','Descrição da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(60,'escola_dominical_classe1','sistema','Classes Infantis','string','Classe 1 da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(61,'escola_dominical_classe2','sistema','Classes de Jovens','string','Classe 2 da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(62,'escola_dominical_classe3','sistema','Classes de Adultos','string','Classe 3 da escola dominical','2025-08-04 20:38:57','2025-08-06 02:35:17'),(63,'doacao_dizimo_titulo','sistema','Dízimo','string','Título do dízimo','2025-08-04 20:38:57','2025-08-06 02:35:17'),(64,'doacao_dizimo_descricao','sistema','Contribua com o dízimo para a manutenção da igreja.','string','Descrição do dízimo','2025-08-04 20:38:57','2025-08-06 02:35:17'),(65,'doacao_dizimo_botao','sistema','Doar Dízimo','string','Botão do dízimo','2025-08-04 20:38:57','2025-08-06 02:35:17'),(66,'doacao_oferta_titulo','sistema','Oferta','string','Título da oferta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(67,'doacao_oferta_descricao','sistema','Ofereça com amor para as necessidades da igreja.','string','Descrição da oferta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(68,'doacao_oferta_botao','sistema','Fazer Oferta','string','Botão da oferta','2025-08-04 20:38:57','2025-08-06 02:35:17'),(69,'doacao_campanhas_titulo','sistema','Campanhas','string','Título das campanhas','2025-08-04 20:38:57','2025-08-06 02:35:17'),(70,'doacao_campanhas_descricao','sistema','Participe de nossas campanhas especiais.','string','Descrição das campanhas','2025-08-04 20:38:57','2025-08-06 02:35:17'),(71,'doacao_campanhas_botao','sistema','Ver Campanhas','string','Botão das campanhas','2025-08-04 20:38:57','2025-08-06 02:35:18'),(72,'footer_links_titulo','sistema','Links Rápidos','string','Título dos links rápidos','2025-08-04 20:38:57','2025-08-06 02:35:18'),(73,'footer_link_sobre','sistema','Sobre','string','Link sobre','2025-08-04 20:38:57','2025-08-06 02:35:18'),(74,'footer_link_ministerios','sistema','Ministérios','string','Link ministérios','2025-08-04 20:38:57','2025-08-06 02:35:18'),(75,'footer_link_cultos','sistema','Cultos','string','Link cultos','2025-08-04 20:38:57','2025-08-06 02:35:18'),(76,'footer_link_aniversariantes','sistema','Aniversariantes','string','Link aniversariantes','2025-08-04 20:38:57','2025-08-06 02:35:18'),(77,'footer_link_doacao','sistema','Doação','string','Link doação','2025-08-04 20:38:57','2025-08-06 02:35:18'),(78,'igreja_whatsapp','sistema','(11) 99999-9999','string','WhatsApp da igreja','2025-08-04 20:38:57','2025-08-06 02:35:17'),(79,'cor_primaria','sistema','#1e40af','string','Cor primária','2025-08-04 20:38:57','2025-08-06 02:35:18'),(80,'cor_secundaria','sistema','#3b82f6','string','Cor secundária','2025-08-04 20:38:57','2025-08-06 02:35:18'),(81,'cor_destaque','sistema','#10b981','string','Cor de destaque','2025-08-04 20:38:57','2025-08-06 02:35:18'),(82,'cor_texto','sistema','#1f2937','string','Cor do texto','2025-08-04 20:38:57','2025-08-06 02:35:18'),(83,'cor_fundo','sistema','#f9fafb','string','Cor de fundo','2025-08-04 20:38:57','2025-08-06 02:35:18'),(84,'cor_card','sistema','#ffffff','string','Cor do card','2025-08-04 20:38:57','2025-08-06 02:35:18'),(85,'cor_borda','sistema','#e5e7eb','string','Cor da borda','2025-08-04 20:38:57','2025-08-06 02:35:18'),(86,'hero_titulo','sistema','Bem-vindo à Nossa Igreja','string','Título do hero','2025-08-04 20:38:57','2025-08-06 02:35:18'),(87,'hero_subtitulo','sistema','Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!','string','Subtítulo do hero','2025-08-04 20:38:57','2025-08-06 02:35:18'),(88,'home_descricao','sistema','Uma igreja comprometida com o amor de Cristo e o serviço ao próximo.','string','Descrição da home','2025-08-04 20:38:57','2025-08-06 02:35:18'),(89,'home_botao_texto','sistema','Conheça Nossa Igreja','string','Texto do botão da home','2025-08-04 20:38:57','2025-08-06 02:35:18'),(90,'home_botao_link','sistema','#sobre','string','Link do botão da home','2025-08-04 20:38:57','2025-08-06 02:35:18'),(91,'secao_sobre_titulo','sistema','Sobre Nossa Igreja','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(92,'secao_sobre_descricao','sistema','Conheça nossa história, missão e valores.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(93,'secao_ministerios_titulo','sistema','Nossos Ministérios','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(94,'secao_ministerios_descricao','sistema','Descubra como você pode servir e crescer.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(95,'secao_cultos_titulo','sistema','Horários de Cultos','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(96,'secao_cultos_descricao','sistema','Venha adorar conosco.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(97,'secao_aniversariantes_titulo','sistema','Aniversariantes do Mês','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(98,'secao_aniversariantes_descricao','sistema','Celebre conosco os aniversariantes.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(99,'aniversariantes_mostrar','sistema','1','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(100,'secao_doacao_titulo','sistema','Faça Sua Doação','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(101,'secao_doacao_descricao','sistema','Apoie nossa missão com sua oferta.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(102,'secao_contato_titulo','sistema','Entre em Contato','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(103,'secao_contato_descricao','sistema','Estamos aqui para você.','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(104,'culto_domingo','sistema','Domingo: 09:00 e 18:00','string',NULL,'2025-08-04 20:38:57','2025-08-04 20:38:57'),(105,'culto_meio_semana','sistema','Quarta: 19:30','string',NULL,'2025-08-04 20:38:58','2025-08-04 20:38:58'),(106,'doacao_ativa','sistema','true','boolean','Sistema de doação ativo','2025-08-04 20:38:58','2025-08-06 02:35:18'),(107,'doacao_sem_login','sistema','true','boolean','Permitir doações sem login','2025-08-04 20:38:58','2025-08-06 02:35:18'),(108,'doacao_valor_minimo','sistema','1.00','number','Valor mínimo para doações','2025-08-04 20:38:58','2025-08-06 02:35:18'),(109,'doacao_valor_maximo','sistema','10000.00','number','Valor máximo para doações','2025-08-04 20:38:58','2025-08-06 02:35:18'),(110,'doacao_dica_seguranca','sistema','Seus dados estão protegidos com criptografia SSL','string',NULL,'2025-08-04 20:38:58','2025-08-04 20:38:58'),(111,'doacao_dica_comprovante','sistema','Receba um comprovante por email após a confirmação','string',NULL,'2025-08-04 20:38:58','2025-08-04 20:38:58'),(112,'doacao_dica_transparencia','sistema','Todas as doações são registradas e auditadas','string',NULL,'2025-08-04 20:38:58','2025-08-04 20:38:58'),(113,'meta_title','sistema','Congregação Batista Avenida - Uma igreja comprometida com o amor de Cristo','string','Meta title','2025-08-04 20:38:58','2025-08-06 02:35:18'),(114,'meta_description','sistema','Venha fazer parte da nossa família! Uma comunidade de fé, amor e esperança.','string','Meta description','2025-08-04 20:38:58','2025-08-06 02:35:18'),(115,'meta_keywords','sistema','igreja, batista, avenida, culto, comunidade, fé, amor','string','Meta keywords','2025-08-04 20:38:58','2025-08-06 02:35:18'),(116,'stripe_key','sistema','pk_test_your_stripe_public_key_here','text','Chave pública do Stripe','2025-08-04 20:39:34','2025-08-06 02:35:18'),(117,'stripe_secret','sistema','sk_test_your_stripe_secret_key_here','password','Chave secreta do Stripe','2025-08-04 20:39:34','2025-08-06 02:35:18'),(118,'stripe_webhook_secret','sistema','whsec_your_stripe_webhook_secret_here','password','Chave secreta do webhook do Stripe','2025-08-04 20:39:34','2025-08-04 20:39:34'),(119,'mercadopago_key','sistema','TEST_your_mercadopago_public_key_here','text','Chave pública do Mercado Pago','2025-08-04 20:39:34','2025-08-06 02:35:18'),(120,'mercadopago_token','sistema','TEST_your_mercadopago_access_token_here','password','Token de acesso do Mercado Pago','2025-08-04 20:39:34','2025-08-06 02:35:18'),(121,'mercadopago_webhook_secret','sistema','your_mercadopago_webhook_secret_here','password','Chave secreta do webhook do Mercado Pago','2025-08-04 20:39:34','2025-08-04 20:39:34'),(122,'pix_beneficiario','sistema','Igreja Teste','text','Nome do beneficiário PIX','2025-08-04 20:39:34','2025-08-06 04:57:50'),(123,'pix_cpf_cnpj','sistema','your_cpf_cnpj_here','text','CPF/CNPJ do beneficiário PIX','2025-08-04 20:39:34','2025-08-04 20:39:34'),(124,'pix_banco','sistema','your_bank_code_here','text','Código do banco para PIX','2025-08-04 20:39:34','2025-08-04 20:39:34'),(125,'doacao_moeda','sistema','BRL','text','Moeda padrão para doações','2025-08-04 20:39:34','2025-08-04 20:39:34'),(126,'doacao_taxa_stripe','sistema','2.9','number','Taxa do Stripe em porcentagem','2025-08-04 20:39:34','2025-08-04 20:39:34'),(127,'doacao_taxa_mercadopago','sistema','3.99','number','Taxa do Mercado Pago em porcentagem','2025-08-04 20:39:34','2025-08-04 20:39:34'),(128,'notificacao_email_doacao','sistema','true','boolean','Enviar notificação por email para doações','2025-08-04 20:39:34','2025-08-04 20:39:34'),(129,'notificacao_sms_doacao','sistema','false','boolean','Enviar notificação por SMS para doações','2025-08-04 20:39:34','2025-08-04 20:39:34'),(130,'pagamento_ssl_obrigatorio','sistema','true','boolean','Obrigar SSL para pagamentos','2025-08-04 20:39:34','2025-08-04 20:39:34'),(131,'pagamento_timeout','sistema','300','number','Timeout para pagamentos em segundos','2025-08-04 20:39:34','2025-08-04 20:39:34'),(132,'stripe_test_mode','sistema','true','boolean','Modo de teste do Stripe','2025-08-04 20:39:34','2025-08-04 20:39:34'),(133,'mercadopago_test_mode','sistema','true','boolean','Modo de teste do Mercado Pago','2025-08-04 20:39:34','2025-08-04 20:39:34'),(134,'stripe_enabled','sistema','false','boolean','Stripe habilitado','2025-08-05 04:59:16','2025-08-06 02:35:18'),(135,'mercadopago_enabled','sistema','false','boolean','Mercado Pago habilitado','2025-08-05 04:59:16','2025-08-06 02:35:18'),(136,'pix_beneficiario_cpf','sistema','your_cpf_cnpj_here','text','CPF/CNPJ do beneficiário PIX','2025-08-05 04:59:16','2025-08-05 04:59:16'),(137,'pix_enabled','sistema','1','boolean','PIX habilitado','2025-08-05 04:59:16','2025-08-06 04:57:50'),(138,'app_name','sistema','CBAV CRM Ministerial','string','Nome da aplicação','2025-08-05 13:22:37','2025-08-06 02:35:17'),(139,'app_description','sistema','Sistema de gestão ministerial completo para igrejas','string','Descrição da aplicação','2025-08-05 13:22:37','2025-08-06 02:35:17'),(140,'contact_email','sistema','contato@cbav.com','string','Email de contato','2025-08-05 13:22:37','2025-08-06 02:35:17'),(141,'contact_phone','sistema','(11) 99999-9999','string','Telefone de contato','2025-08-05 13:22:37','2025-08-06 02:35:17'),(142,'address','sistema','','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(143,'social_facebook','sistema','','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(144,'social_instagram','sistema','','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(145,'social_youtube','sistema','','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(146,'timezone','sistema','America/Sao_Paulo','string','Fuso horário do sistema','2025-08-05 13:22:37','2025-08-06 02:35:17'),(147,'locale','sistema','pt_BR','string','Idioma padrão','2025-08-05 13:22:37','2025-08-06 02:35:17'),(148,'app_logo','sistema','config/EIrLDL7vHb4apUDrWKCuR6c1F2vc1dLybE1K0FMc.png','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(149,'app_favicon','sistema','config/hUkYPCG9IT8xvhSZc4rMmuFItJliRxHeffLAx1OU.png','string',NULL,'2025-08-05 13:22:37','2025-08-05 13:22:37'),(150,'logo','sistema','config/O7Tdsv3gYf9p6iKSnBMvpuyH3ikYbpg2SgRaQCk5.png','string',NULL,'2025-08-05 13:23:07','2025-08-05 13:23:07'),(151,'app_version','sistema','2.0.0','string','Versão da aplicação','2025-08-06 02:35:17','2025-08-06 02:35:17'),(152,'contact_address','sistema','Rua da Avenida, 123 - Centro, São Paulo - SP','string','Endereço de contato','2025-08-06 02:35:17','2025-08-06 02:35:17'),(153,'currency','sistema','BRL','string','Moeda padrão','2025-08-06 02:35:17','2025-08-06 02:35:17'),(154,'mail_host','sistema','smtp.gmail.com','string','Servidor SMTP','2025-08-06 02:35:17','2025-08-06 02:35:17'),(155,'mail_port','sistema','587','string','Porta SMTP','2025-08-06 02:35:17','2025-08-06 02:35:17'),(156,'mail_username','sistema','seu-email@gmail.com','string','Usuário SMTP','2025-08-06 02:35:17','2025-08-06 02:35:17'),(157,'mail_password','sistema','sua-senha-app','string','Senha SMTP','2025-08-06 02:35:17','2025-08-06 02:35:17'),(158,'mail_encryption','sistema','tls','string','Criptografia SMTP','2025-08-06 02:35:17','2025-08-06 02:35:17'),(159,'mail_from_address','sistema','admin@cbav.com','string','Email remetente','2025-08-06 02:35:17','2025-08-06 02:35:17'),(160,'mail_from_name','sistema','CBAV CRM Ministerial','string','Nome remetente','2025-08-06 02:35:17','2025-08-06 02:35:17'),(161,'session_lifetime','sistema','120','integer','Tempo de sessão em minutos','2025-08-06 02:35:17','2025-08-06 02:35:17'),(162,'max_login_attempts','sistema','5','integer','Máximo de tentativas de login','2025-08-06 02:35:17','2025-08-06 02:35:17'),(163,'force_ssl','sistema','false','boolean','Forçar SSL','2025-08-06 02:35:17','2025-08-06 02:35:17'),(164,'enable_2fa','sistema','false','boolean','Habilitar autenticação de dois fatores','2025-08-06 02:35:17','2025-08-06 02:35:17'),(165,'password_min_length','sistema','8','integer','Tamanho mínimo da senha','2025-08-06 02:35:17','2025-08-06 02:35:17'),(166,'password_require_special','sistema','true','boolean','Exigir caracteres especiais na senha','2025-08-06 02:35:17','2025-08-06 02:35:17'),(167,'cache_driver','sistema','file','string','Driver de cache','2025-08-06 02:35:17','2025-08-06 02:35:17'),(168,'session_driver','sistema','database','string','Driver de sessão','2025-08-06 02:35:17','2025-08-06 02:35:17'),(169,'queue_connection','sistema','database','string','Conexão de fila','2025-08-06 02:35:17','2025-08-06 02:35:17'),(170,'backup_enabled','sistema','true','boolean','Habilitar backup automático','2025-08-06 02:35:17','2025-08-06 02:35:17'),(171,'backup_frequency','sistema','daily','string','Frequência de backup','2025-08-06 02:35:17','2025-08-06 02:35:17'),(172,'backup_retention','sistema','7','integer','Dias de retenção de backup','2025-08-06 02:35:17','2025-08-06 02:35:17'),(173,'notification_email_enabled','sistema','true','boolean','Habilitar notificações por email','2025-08-06 02:35:17','2025-08-06 02:35:17'),(174,'notification_sms_enabled','sistema','false','boolean','Habilitar notificações por SMS','2025-08-06 02:35:17','2025-08-06 02:35:17'),(175,'notification_push_enabled','sistema','false','boolean','Habilitar notificações push','2025-08-06 02:35:17','2025-08-06 02:35:17'),(176,'log_level','sistema','error','string','Nível de log','2025-08-06 02:35:17','2025-08-06 02:35:17'),(177,'log_activity','sistema','true','boolean','Log de atividades','2025-08-06 02:35:17','2025-08-06 02:35:17'),(178,'log_audit','sistema','true','boolean','Log de auditoria','2025-08-06 02:35:17','2025-08-06 02:35:17'),(179,'maintenance_mode','sistema','false','boolean','Modo de manutenção','2025-08-06 02:35:17','2025-08-06 02:35:17'),(180,'maintenance_message','sistema','Sistema em manutenção. Volte em breve.','string','Mensagem de manutenção','2025-08-06 02:35:17','2025-08-06 02:35:17'),(181,'igreja_id','sistema','1','integer','ID da igreja atual','2025-08-06 02:35:19','2025-08-06 02:35:19'),(182,'igreja_nome_completo','sistema','Congregação Batista Avenida','string','Nome completo da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(183,'igreja_cnpj','sistema','12.345.678/0001-90','string','CNPJ da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(184,'igreja_endereco_completo','sistema','Rua da Avenida, 123 - São Paulo/SP - CEP: 01234-567','string','Endereço completo da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(185,'igreja_telefone_principal','sistema','(11) 99999-9999','string','Telefone principal da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(186,'igreja_email_principal','sistema','contato@cbav.com','string','Email principal da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(187,'pastor_nome','sistema','Pr. João Silva','string','Nome do pastor','2025-08-06 02:35:19','2025-08-06 02:35:19'),(188,'pastor_telefone','sistema','(11) 99999-8888','string','Telefone do pastor','2025-08-06 02:35:19','2025-08-06 02:35:19'),(189,'pastor_email','sistema','pastor@cbav.com','string','Email do pastor','2025-08-06 02:35:19','2025-08-06 02:35:19'),(190,'tesoureiro_nome','sistema','Maria Santos','string','Nome do tesoureiro','2025-08-06 02:35:19','2025-08-06 02:35:19'),(191,'tesoureiro_telefone','sistema','(11) 99999-7777','string','Telefone do tesoureiro','2025-08-06 02:35:19','2025-08-06 02:35:19'),(192,'tesoureiro_email','sistema','tesoureiro@cbav.com','string','Email do tesoureiro','2025-08-06 02:35:19','2025-08-06 02:35:19'),(193,'secretario_nome','sistema','Pedro Oliveira','string','Nome do secretário','2025-08-06 02:35:19','2025-08-06 02:35:19'),(194,'secretario_telefone','sistema','(11) 99999-6666','string','Telefone do secretário','2025-08-06 02:35:19','2025-08-06 02:35:19'),(195,'secretario_email','sistema','secretario@cbav.com','string','Email do secretário','2025-08-06 02:35:19','2025-08-06 02:35:19'),(196,'igreja_data_fundacao','sistema','1990-01-15 00:00:00','date','Data de fundação da igreja','2025-08-06 02:35:19','2025-08-06 02:37:30'),(197,'igreja_denominacao','sistema','Batista','string','Denominação da igreja','2025-08-06 02:35:19','2025-08-06 02:35:19'),(198,'igreja_convencao','sistema','Convenção Batista Brasileira','string','Convenção da igreja','2025-08-06 02:35:20','2025-08-06 02:35:20'),(199,'igreja_status','sistema','ATIVA','string','Status da igreja','2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `configuracoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conselhos`
--

DROP TABLE IF EXISTS `conselhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conselhos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint(20) unsigned DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_reuniao` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time DEFAULT NULL,
  `local` varchar(255) DEFAULT NULL,
  `status` enum('agendada','em_andamento','finalizada','cancelada') NOT NULL DEFAULT 'agendada',
  `tipo` enum('reuniao_ordinaria','reuniao_extraordinaria','votacao') NOT NULL DEFAULT 'reuniao_ordinaria',
  `quorum_minimo` int(11) NOT NULL DEFAULT 5,
  `criado_por` bigint(20) unsigned NOT NULL,
  `presidente_id` bigint(20) unsigned DEFAULT NULL,
  `secretario_id` bigint(20) unsigned DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ata_finalizada` tinyint(1) NOT NULL DEFAULT 0,
  `data_ata_finalizada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conselhos_criado_por_foreign` (`criado_por`),
  KEY `conselhos_presidente_id_foreign` (`presidente_id`),
  KEY `conselhos_secretario_id_foreign` (`secretario_id`),
  KEY `conselhos_template_id_foreign` (`template_id`),
  CONSTRAINT `conselhos_criado_por_foreign` FOREIGN KEY (`criado_por`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conselhos_presidente_id_foreign` FOREIGN KEY (`presidente_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conselhos_secretario_id_foreign` FOREIGN KEY (`secretario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conselhos_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `template_pautas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conselhos`
--

LOCK TABLES `conselhos` WRITE;
/*!40000 ALTER TABLE `conselhos` DISABLE KEYS */;
INSERT INTO `conselhos` VALUES (1,NULL,'Conselho Administrativo 2024','Conselho administrativo da igreja para o ano de 2024','2024-01-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Primeira reunião do ano com aprovação do orçamento',1,'2024-01-16 00:00:00','2025-08-06 02:39:36','2025-08-06 02:39:36'),(2,NULL,'Conselho Extraordinário - Reforma','Reunião extraordinária para discutir reforma do templo','2024-02-10','14:00:00','16:00:00','Sala do Conselho','finalizada','reuniao_extraordinaria',8,1,1,1,'Decisão tomada sobre iniciar campanha de reforma',1,'2024-02-10 19:00:00','2025-08-06 02:39:36','2025-08-06 02:39:36'),(3,NULL,'Conselho Mensal - Março 2024','Reunião mensal do conselho para março de 2024','2024-03-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Relatórios aprovados e eventos confirmados',1,'2024-03-16 00:00:00','2025-08-06 02:39:36','2025-08-06 02:39:36'),(4,NULL,'Conselho Mensal - Abril 2024','Reunião mensal do conselho para abril de 2024','2024-04-15','19:00:00','21:00:00','Sala do Conselho','agendada','reuniao_ordinaria',8,1,1,1,'Reunião agendada para avaliação dos ministérios',0,NULL,'2025-08-06 02:39:36','2025-08-06 02:39:36'),(5,NULL,'Conselho de Planejamento 2024','Reunião de planejamento estratégico para 2024','2023-12-20','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_extraordinaria',8,1,1,1,'Planejamento aprovado para 2024',1,'2023-12-21 00:00:00','2025-08-06 02:39:36','2025-08-06 02:39:36'),(6,NULL,'Reunião Ordinária - Janeiro 2024','Reunião ordinária do conselho para janeiro de 2024','2024-01-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Reunião muito produtiva, todas as pautas foram aprovadas. Aprovação do orçamento anual, eleição de novos líderes de ministérios, discussão sobre reforma do templo. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2024-01-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(7,NULL,'Reunião Extraordinária - Fevereiro 2024','Reunião extraordinária do conselho para fevereiro de 2024','2024-02-10','14:00:00','16:00:00','Sala do Conselho','finalizada','reuniao_extraordinaria',8,1,1,1,'Decisão tomada sobre iniciar a campanha de reforma. Discussão sobre campanha de reforma do templo, aprovação de novos membros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues, Roberto Silva Costa',1,'2024-02-10 19:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(8,NULL,'Reunião Ordinária - Março 2024','Reunião ordinária do conselho para março de 2024','2024-03-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Relatórios aprovados, eventos confirmados. Relatório financeiro mensal, aprovação de eventos, discussão sobre Escola Dominical. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2024-03-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(9,NULL,'Reunião Ordinária - Abril 2024','Reunião ordinária do conselho para abril de 2024','2024-04-15','19:00:00','21:00:00','Sala do Conselho','agendada','reuniao_ordinaria',8,1,1,1,'Reunião agendada. Avaliação dos ministérios, planejamento para o segundo trimestre, discussão sobre evangelismo. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',0,NULL,'2025-08-06 02:39:37','2025-08-06 02:39:37'),(10,NULL,'Reunião Extraordinária - Dezembro 2023','Reunião extraordinária do conselho para dezembro de 2023','2023-12-20','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_extraordinaria',8,1,1,1,'Planejamento aprovado para 2024. Planejamento para 2024, aprovação do orçamento, eleição de novos conselheiros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-12-21 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(11,NULL,'Reunião Ordinária - Novembro 2023','Reunião ordinária do conselho para novembro de 2023','2023-11-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Eventos de Natal aprovados. Relatório financeiro, preparação para Natal, discussão sobre ação social. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-11-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(12,NULL,'Reunião Ordinária - Outubro 2023','Reunião ordinária do conselho para outubro de 2023','2023-10-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Metas do terceiro trimestre atingidas. Avaliação do terceiro trimestre, planejamento para o último trimestre. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-10-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(13,NULL,'Reunião Extraordinária - Setembro 2023','Reunião extraordinária do conselho para setembro de 2023','2023-09-25','14:00:00','16:00:00','Sala do Conselho','finalizada','reuniao_extraordinaria',8,1,1,1,'Compra de equipamentos aprovada. Discussão sobre compra de equipamentos de som, aprovação de novos membros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-09-25 19:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(14,NULL,'Reunião Ordinária - Agosto 2023','Reunião ordinária do conselho para agosto de 2023','2023-08-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Ministérios funcionando bem. Relatório financeiro, discussão sobre ministérios, planejamento para o terceiro trimestre. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-08-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(15,NULL,'Reunião Ordinária - Julho 2023','Reunião ordinária do conselho para julho de 2023','2023-07-15','19:00:00','21:00:00','Sala do Conselho','finalizada','reuniao_ordinaria',8,1,1,1,'Primeiro semestre muito produtivo. Avaliação do primeiro semestre, discussão sobre evangelismo, aprovação de eventos. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',1,'2023-07-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37');
/*!40000 ALTER TABLE `conselhos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamentos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ministerio_id` bigint(20) unsigned NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departamentos_ministerio_id_foreign` (`ministerio_id`),
  CONSTRAINT `departamentos_ministerio_id_foreign` FOREIGN KEY (`ministerio_id`) REFERENCES `ministerios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Músicos','Músicos e instrumentistas',1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(2,'Coral','Coral da igreja',1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,'Som','Equipe de som e áudio',1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,'Multimídia','Projeção e multimídia',1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,'Liderança','Líderes do ministério jovem',2,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,'Eventos','Departamento responsável pela organização de eventos e celebrações',10,1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(7,'Evangelismo','Evangelismo jovem',2,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,'Professores','Professores da escola bíblica',3,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,'Recreação','Atividades recreativas',3,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(10,'Teatro','Teatro infantil',3,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(11,'Oração','Grupo de oração',4,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(12,'Vigília','Vigílias de oração',4,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(13,'Assistência','Assistência social',5,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(14,'Alimentos','Distribuição de alimentos',5,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(15,'Visitação','Visitas hospitalares',5,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(16,'Discipulado','Discipulado de novos convertidos',6,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(17,'Escola Bíblica','Escola bíblica dominical',6,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(18,'Missões','Missões locais e internacionais',7,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(19,'Evangelismo','Evangelismo de rua',7,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(20,'Aconselhamento','Aconselhamento familiar',8,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(21,'Casais','Ministério de casais',8,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(22,'Administrativo','Departamento responsável pela administração geral da igreja',12,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(23,'Financeiro','Departamento responsável pela gestão financeira da igreja',12,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(24,'Espiritual','Departamento responsável pela área espiritual e pastoral',4,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(25,'Educacional','Departamento responsável pela educação cristã e Escola Dominical',6,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(26,'Social','Departamento responsável pela ação social e assistência',5,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(27,'Tecnologia','Departamento responsável pela tecnologia e mídia da igreja',11,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(28,'Comunicação','Departamento responsável pela comunicação interna e externa',11,1,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devocionais`
--

DROP TABLE IF EXISTS `devocionais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devocionais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `texto` text NOT NULL,
  `versiculo` varchar(255) NOT NULL,
  `texto_versiculo` text DEFAULT NULL,
  `reflexao` text NOT NULL,
  `data` date NOT NULL,
  `tipo` enum('devocional','versiculo','oracao') NOT NULL DEFAULT 'devocional',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `ordem` int(11) NOT NULL DEFAULT 0,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devocionais_data_tipo_index` (`data`,`tipo`),
  KEY `devocionais_ativo_tipo_index` (`ativo`,`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devocionais`
--

LOCK TABLES `devocionais` WRITE;
/*!40000 ALTER TABLE `devocionais` DISABLE KEYS */;
INSERT INTO `devocionais` VALUES (1,'A Fé que Move Montanhas','Hoje vamos refletir sobre a importância da fé em nossas vidas. A fé é a certeza das coisas que se esperam e a convicção dos fatos que se não veem. Quando temos fé, podemos ver milagres acontecerem em nossas vidas.','Marcos 11:23',NULL,'Senhor, aumenta nossa fé para que possamos ver milagres em nossas vidas. Que nossa confiança em Ti seja inabalável.','2025-07-30','devocional',1,0,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(2,'O Amor de Deus','O amor de Deus é incondicional e eterno. Ele nos ama não pelo que fazemos, mas pelo que somos. Seu amor é maior que qualquer erro que possamos cometer.','João 3:16',NULL,'Pai, ensina-nos a amar como Tu nos amas. Que possamos refletir Teu amor em nossas vidas.','2025-07-31','devocional',1,0,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(3,'Gratidão','Em tudo dai graças, pois esta é a vontade de Deus em Cristo Jesus para convosco. A gratidão nos conecta com Deus e nos mantém focados nas bênçãos.','1 Tessalonicenses 5:18',NULL,'Senhor, ensina-nos a ser gratos em todas as circunstâncias. Que nossa gratidão seja constante.','2025-08-01','devocional',1,0,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(4,'Perdão','O perdão é uma das maiores demonstrações do amor de Deus. Quando perdoamos, nos libertamos e permitimos que Deus trabalhe em nossas vidas.','Mateus 6:14-15',NULL,'Pai, ajuda-nos a perdoar como Tu nos perdoas. Que o perdão seja uma marca em nossas vidas.','2025-08-02','devocional',1,0,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(5,'Perseverança','A perseverança é essencial na vida cristã. Através dela, desenvolvemos caráter e esperança. Não desistimos diante das dificuldades.','Romanos 5:3-4',NULL,'Senhor, fortalece-nos para perseverar. Que nossa fé seja inabalável em todas as situações.','2025-08-03','devocional',1,0,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(6,'A Fé que Move Montanhas','Jesus disse aos seus discípulos: \"Porque a verdade vos digo que, se tivésseis fé como um grão de mostarda, diríeis a este monte: Passa daqui para acolá, e ele passaria. Nada vos seria impossível.\"','Mateus 17:20',NULL,'A fé é um dom precioso que Deus nos deu. Não é o tamanho da fé que importa, mas sim a qualidade dela. Uma fé genuína, mesmo que pequena como um grão de mostarda, pode realizar coisas extraordinárias. Hoje, reflita sobre sua fé. Você tem confiado completamente em Deus? Ou tem deixado a dúvida e o medo tomarem conta do seu coração? Que possamos cultivar uma fé que não apenas acredita em Deus, mas que confia plenamente em Sua palavra e em Seu poder.','2025-07-31','devocional',1,1,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(7,'O Amor que Transforma','Aquele que não ama não conhece a Deus; porque Deus é amor.','1 João 4:8',NULL,'O amor é a essência do caráter de Deus. Tudo o que Ele faz é motivado pelo amor. Como filhos de Deus, somos chamados a refletir esse amor em nossas vidas. O amor de Deus não é apenas um sentimento, mas uma escolha. É decidir colocar o bem do outro acima dos nossos próprios interesses. É perdoar quando somos feridos, servir quando somos servidos, e dar quando não temos muito. Que possamos hoje refletir o amor de Deus em todas as nossas ações e relacionamentos.','2025-08-01','devocional',1,2,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(8,'A Gratidão no Coração','Em tudo dai graças, porque esta é a vontade de Deus em Cristo Jesus para convosco.','1 Tessalonicenses 5:18',NULL,'A gratidão é uma atitude que transforma nossa perspectiva da vida. Quando somos gratos, mesmo nas dificuldades, descobrimos que Deus está trabalhando em nosso favor. A gratidão nos ajuda a focar nas bênçãos ao invés dos problemas, na provisão ao invés da escassez, na esperança ao invés do desespero. Que possamos cultivar um coração grato, reconhecendo que tudo o que temos vem de Deus e que Ele é fiel em todas as circunstâncias.','2025-08-02','devocional',1,3,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(9,'A Força na Fraqueza','E disse-me: A minha graça te basta, porque o meu poder se aperfeiçoa na fraqueza.','2 Coríntios 12:9',NULL,'Deus não escolhe os capacitados, mas capacita os escolhidos. Nossas fraquezas não são obstáculos para Deus, mas oportunidades para Ele demonstrar Seu poder. Quando nos sentimos inadequados ou incapazes, é exatamente nesses momentos que Deus quer trabalhar através de nós. Sua graça é suficiente para suprir todas as nossas necessidades. Que possamos confiar que Deus pode usar nossas fraquezas para Sua glória.','2025-08-03','devocional',1,4,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(10,'A Paz que Excede Todo Entendimento','E a paz de Deus, que excede todo o entendimento, guardará os vossos corações e os vossos sentimentos em Cristo Jesus.','Filipenses 4:7',NULL,'A paz de Deus é diferente da paz do mundo. Ela não depende das circunstâncias, mas da presença de Deus em nossas vidas. Quando entregamos nossas ansiedades a Deus em oração, Ele nos dá uma paz que não conseguimos explicar. É uma paz que guarda nossos corações e mentes. Que possamos buscar essa paz através da oração e da confiança em Deus.','2025-08-04','devocional',1,5,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(11,'O Fruto do Espírito','Mas o fruto do Espírito é: amor, gozo, paz, longanimidade, benignidade, bondade, fé, mansidão, temperança.','Gálatas 5:22-23',NULL,'O fruto do Espírito é o resultado natural de uma vida cheia do Espírito Santo. Não são características que desenvolvemos por esforço próprio, mas que brotam naturalmente quando permitimos que o Espírito Santo trabalhe em nós. Cada fruto representa uma qualidade do caráter de Cristo que deve ser manifestada em nossas vidas. Que possamos permitir que o Espírito Santo produza esses frutos em nossas vidas.','2025-08-05','devocional',1,6,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(12,'A Esperança que Não Falha','E a esperança não traz confusão, porquanto o amor de Deus está derramado em nossos corações pelo Espírito Santo que nos foi dado.','Romanos 5:5',NULL,'A esperança cristã não é um desejo vago, mas uma certeza baseada no amor de Deus e nas promessas de Sua palavra. Mesmo quando as circunstâncias parecem desfavoráveis, podemos ter esperança porque sabemos que Deus está no controle e que Ele trabalha todas as coisas para o bem daqueles que O amam. Que possamos manter nossa esperança viva, confiando no amor e na fidelidade de Deus.','2025-07-30','devocional',1,7,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(13,'A Humildade de Cristo','De sorte que haja em vós o mesmo sentimento que houve também em Cristo Jesus, que, sendo em forma de Deus, não teve por usurpação ser igual a Deus, mas esvaziou-se a si mesmo, tomando a forma de servo.','Filipenses 2:5-8',NULL,'Cristo é nosso exemplo supremo de humildade. Ele, sendo Deus, escolheu se tornar servo e dar Sua vida por nós. A humildade não é fraqueza, mas força. É reconhecer que tudo o que temos vem de Deus e usar nossos dons para servir aos outros. Que possamos seguir o exemplo de Cristo, servindo uns aos outros com humildade.','2025-07-29','devocional',1,8,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(14,'A Perseverança na Fé','Bem-aventurado o homem que suporta a tentação; porque, quando for provado, receberá a coroa da vida, a qual o Senhor tem prometido aos que o amam.','Tiago 1:12',NULL,'A perseverança é essencial na vida cristã. As provações não são castigos, mas oportunidades para crescer na fé e desenvolver o caráter de Cristo. Deus promete uma coroa de vida para aqueles que perseveram. Essa promessa nos motiva a continuar firmes, mesmo quando as circunstâncias são difíceis. Que possamos perseverar na fé, confiando que Deus está trabalhando em nós através das provações.','2025-07-28','devocional',1,9,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(15,'A Alegria do Senhor','A alegria do Senhor é a vossa força.','Neemias 8:10',NULL,'A alegria do Senhor não depende das circunstâncias, mas da presença de Deus em nossas vidas. É uma alegria que vem de saber que somos amados e aceitos por Deus. Essa alegria nos fortalece para enfrentar os desafios da vida. Ela nos lembra que, independentemente das circunstâncias, Deus está conosco e nos ama. Que possamos buscar a alegria do Senhor, que é nossa força e nossa fortaleza.','2025-07-27','devocional',1,10,NULL,'2025-08-06 02:39:42','2025-08-06 02:39:42');
/*!40000 ALTER TABLE `devocionais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_baixa`
--

DROP TABLE IF EXISTS `documentos_baixa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos_baixa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transacao_id` bigint(20) unsigned NOT NULL,
  `tipo_documento` enum('DARF','GPS','DAS','DARE','DAM','CARNÊ_LEÃO','IRPF','IRPJ','PIS_COFINS','IPI','ICMS','ISS','OUTROS') NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `ano_exercicio` year(4) NOT NULL,
  `data_emissao` date NOT NULL,
  `data_vencimento` date DEFAULT NULL,
  `valor_documento` decimal(15,2) NOT NULL,
  `valor_pago` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('PENDENTE','PAGO','VENCIDO','CANCELADO','PROTESTADO','EM_ANALISE') NOT NULL DEFAULT 'PENDENTE',
  `observacoes` text DEFAULT NULL,
  `arquivo_comprovante` varchar(255) DEFAULT NULL,
  `hash_documento` varchar(64) NOT NULL,
  `assinatura_digital` varchar(255) DEFAULT NULL,
  `protocolo_receita` varchar(20) NOT NULL,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documentos_baixa_hash_documento_unique` (`hash_documento`),
  UNIQUE KEY `documentos_baixa_protocolo_receita_unique` (`protocolo_receita`),
  KEY `documentos_baixa_transacao_id_foreign` (`transacao_id`),
  KEY `documentos_baixa_tipo_documento_status_index` (`tipo_documento`,`status`),
  KEY `documentos_baixa_data_vencimento_status_index` (`data_vencimento`,`status`),
  KEY `documentos_baixa_ano_exercicio_tipo_documento_index` (`ano_exercicio`,`tipo_documento`),
  KEY `documentos_baixa_numero_documento_index` (`numero_documento`),
  CONSTRAINT `documentos_baixa_transacao_id_foreign` FOREIGN KEY (`transacao_id`) REFERENCES `transacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_baixa`
--

LOCK TABLES `documentos_baixa` WRITE;
/*!40000 ALTER TABLE `documentos_baixa` DISABLE KEYS */;
INSERT INTO `documentos_baixa` VALUES (1,36,'PIS_COFINS','12345678901234',2024,'2025-08-04','1985-04-01',287.95,0.00,'PENDENTE',NULL,NULL,'7ce0883960a157d15119710fdbd9427206095e5ac2f2295f9c07aea4033d3ec1',NULL,'RF202508000000',NULL,'2025-08-04 22:48:31','2025-08-04 22:48:31'),(2,1,'DARF','BAIXA-2024-001',2024,'2024-01-15','2024-02-15',5000.00,5000.00,'PAGO','Documento de baixa para reforma do templo',NULL,'hash_baixa_2024_001_abc123def456',NULL,'RF2024BAIXA001',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(3,1,'GPS','BAIXA-2024-002',2024,'2024-02-01','2024-03-01',3000.00,3000.00,'PAGO','Documento de baixa para compra de instrumentos',NULL,'hash_baixa_2024_002_abc123def456',NULL,'RF2024BAIXA002',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(4,1,'DAS','BAIXA-2024-003',2024,'2024-03-01','2024-04-01',2000.00,0.00,'PENDENTE','Documento de baixa para ação social',NULL,'hash_baixa_2024_003_abc123def456',NULL,'RF2024BAIXA003',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(5,1,'DARF','BAIXA-2023-001',2023,'2023-01-15','2023-02-15',4000.00,4000.00,'PAGO','Documento de baixa para manutenção do sistema de som',NULL,'hash_baixa_2023_001_abc123def456',NULL,'RF2023BAIXA001',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(6,1,'GPS','BAIXA-2023-002',2023,'2023-02-01','2023-03-01',2500.00,2500.00,'PAGO','Documento de baixa para material didático',NULL,'hash_baixa_2023_002_abc123def456',NULL,'RF2023BAIXA002',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(7,1,'DAS','BAIXA-2023-003',2023,'2023-03-01','2023-04-01',3500.00,3500.00,'PAGO','Documento de baixa para equipamentos de tecnologia',NULL,'hash_baixa_2023_003_abc123def456',NULL,'RF2023BAIXA003',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(8,1,'DARF','BAIXA-2022-001',2022,'2022-01-15','2022-02-15',3000.00,3000.00,'PAGO','Documento de baixa para pintura do templo',NULL,'hash_baixa_2022_001_abc123def456',NULL,'RF2022BAIXA001',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(9,1,'GPS','BAIXA-2022-002',2022,'2022-02-01','2022-03-01',1500.00,1500.00,'PAGO','Documento de baixa para material de evangelismo',NULL,'hash_baixa_2022_002_abc123def456',NULL,'RF2022BAIXA002',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(10,1,'DAS','BAIXA-2021-001',2021,'2021-01-15','2021-02-15',2000.00,2000.00,'PAGO','Documento de baixa para manutenção geral',NULL,'hash_baixa_2021_001_abc123def456',NULL,'RF2021BAIXA001',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33'),(11,1,'GPS','BAIXA-2021-002',2021,'2021-02-01','2021-03-01',1000.00,1000.00,'PAGO','Documento de baixa para material de limpeza',NULL,'hash_baixa_2021_002_abc123def456',NULL,'RF2021BAIXA002',NULL,'2025-08-06 02:39:33','2025-08-06 02:39:33');
/*!40000 ALTER TABLE `documentos_baixa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_declaracao_anual`
--

DROP TABLE IF EXISTS `documentos_declaracao_anual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos_declaracao_anual` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `igreja_id` bigint(20) unsigned NOT NULL,
  `ano_exercicio` int(11) NOT NULL,
  `tipo_documento` varchar(255) NOT NULL,
  `numero_documento` varchar(255) NOT NULL,
  `protocolo_receita` varchar(255) NOT NULL,
  `data_emissao` datetime NOT NULL,
  `data_vencimento` datetime DEFAULT NULL,
  `valor_total` decimal(15,2) NOT NULL,
  `valor_doacoes` decimal(15,2) NOT NULL DEFAULT 0.00,
  `valor_dizimos` decimal(15,2) NOT NULL DEFAULT 0.00,
  `valor_outros` decimal(15,2) NOT NULL DEFAULT 0.00,
  `hash_documento` varchar(64) NOT NULL,
  `qr_code` text DEFAULT NULL,
  `codigo_barras` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDENTE',
  `certificado_digital` varchar(64) DEFAULT NULL,
  `assinatura_digital` varchar(64) DEFAULT NULL,
  `validado_em` datetime DEFAULT NULL,
  `validado_por` bigint(20) unsigned DEFAULT NULL,
  `arquivo_comprovante` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documentos_declaracao_anual_protocolo_receita_unique` (`protocolo_receita`),
  KEY `documentos_declaracao_anual_validado_por_foreign` (`validado_por`),
  KEY `documentos_declaracao_anual_igreja_id_ano_exercicio_index` (`igreja_id`,`ano_exercicio`),
  KEY `documentos_declaracao_anual_tipo_documento_status_index` (`tipo_documento`,`status`),
  KEY `documentos_declaracao_anual_protocolo_receita_index` (`protocolo_receita`),
  KEY `documentos_declaracao_anual_data_emissao_index` (`data_emissao`),
  KEY `documentos_declaracao_anual_data_vencimento_index` (`data_vencimento`),
  KEY `documentos_declaracao_anual_status_index` (`status`),
  CONSTRAINT `documentos_declaracao_anual_igreja_id_foreign` FOREIGN KEY (`igreja_id`) REFERENCES `igrejas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documentos_declaracao_anual_validado_por_foreign` FOREIGN KEY (`validado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_declaracao_anual`
--

LOCK TABLES `documentos_declaracao_anual` WRITE;
/*!40000 ALTER TABLE `documentos_declaracao_anual` DISABLE KEYS */;
INSERT INTO `documentos_declaracao_anual` VALUES (1,1,2022,'DECLARACAO_ANUAL','DEC20220001','RF2022DEC202508041931097135','2022-12-31 00:00:00','2023-04-30 00:00:00',74720.00,77889.00,61666.00,7504.00,'80a14cdf2489e648d15ad4c4b0beb9e2c18ed072fbd9f305732eba9791558920','{\"protocolo\":\"RF2022DEC202508041931097135\",\"hash\":\"80a14cdf2489e648d15ad4c4b0beb9e2c18ed072fbd9f305732eba9791558920\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2022,\"valor\":\"74720.00\",\"data\":\"2022-12-31\"}','846000747200030042023RF2022DEC202508041931097135','VALIDADO','cc6fc9090f918da0d2dc16c6348a5fe744a8f25540ea473f34b6644d371d497a','b612ca5a8fe29e1c934c00982edf0ec5eeba33f41063e41b6670179697039fb9',NULL,1,NULL,'Documento de declaração anual do exercício 2022','2025-08-04 22:31:09','2025-08-04 22:31:09'),(2,1,2022,'COMPROVANTE_DOACOES','DOA20220001','RF2022COM202508041931093868','2022-12-31 00:00:00','2023-04-30 00:00:00',38844.00,44858.00,0.00,0.00,'44583396ec66e6ee0728fd5a6c53ddb12089b0cd57b6fca84ac9a8a1460e2900','{\"protocolo\":\"RF2022COM202508041931093868\",\"hash\":\"44583396ec66e6ee0728fd5a6c53ddb12089b0cd57b6fca84ac9a8a1460e2900\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2022,\"valor\":\"38844.00\",\"data\":\"2022-12-31\"}','846000388440030042023RF2022COM202508041931093868','CANCELADO','bc9883c0c0d3fe00fe36916cf0efec51c5ffcd015b40053d5f66cd295f4ae288','442e533ba896378643009e39572416559f67b2a31ebc9969572cf0055be27e90','2025-08-04 19:31:09',1,NULL,'Comprovante de doações do exercício 2022','2025-08-04 22:31:09','2025-08-04 22:31:09'),(3,1,2022,'CERTIDAO_NEGATIVA','CERT20220001','RF2022CER202508041931095969','2022-12-31 00:00:00','2023-12-31 00:00:00',0.00,0.00,0.00,0.00,'9725e2f3f03efe6ed13f929bdd1da0f1aedc48674cf6eb0fa67f6755c86d97e4','{\"protocolo\":\"RF2022CER202508041931095969\",\"hash\":\"9725e2f3f03efe6ed13f929bdd1da0f1aedc48674cf6eb0fa67f6755c86d97e4\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2022,\"valor\":\"0.00\",\"data\":\"2022-12-31\"}','846000000000031122023RF2022CER202508041931095969','CANCELADO','eb1891efa884498a1eac1e302fa087e5e92e052691209da234fe50ad3cc660b7','5b1f0c8b4f04a4d48849d6fe9cd8ad96eccbe4a753989054bffbfb8fd3c8efd8',NULL,1,NULL,'Certidão negativa de débitos do exercício 2022','2025-08-04 22:31:09','2025-08-04 22:31:09'),(4,1,2023,'DECLARACAO_ANUAL','DEC20230001','RF2023DEC202508041931094983','2023-12-31 00:00:00','2024-04-30 00:00:00',174348.00,74371.00,81826.00,12231.00,'0f9aef26e6b8b501c87becd2ff4ed14f6b1e9801fc8b69e76ed32c179c6d3d79','{\"protocolo\":\"RF2023DEC202508041931094983\",\"hash\":\"0f9aef26e6b8b501c87becd2ff4ed14f6b1e9801fc8b69e76ed32c179c6d3d79\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2023,\"valor\":\"174348.00\",\"data\":\"2023-12-31\"}','846001743480030042024RF2023DEC202508041931094983','REJEITADO','6769c365ac66e6713b3128cd21d6e7d2a7ee8ee0080a8f9e95873aae9196bda3','85f66e0c5af55cd12db3f2fafe1f87bdc09f50218571bf07585f75cb45b8b004','2025-08-04 19:31:09',1,NULL,'Documento de declaração anual do exercício 2023','2025-08-04 22:31:09','2025-08-04 22:31:09'),(5,1,2023,'COMPROVANTE_DOACOES','DOA20230001','RF2023COM202508041931095542','2023-12-31 00:00:00','2024-04-30 00:00:00',34980.00,52671.00,0.00,0.00,'871b6ac826e7e4c38b4018384d8b1a0a902b08ebcb7cfa2d5c2a20e80f7172cc','{\"protocolo\":\"RF2023COM202508041931095542\",\"hash\":\"871b6ac826e7e4c38b4018384d8b1a0a902b08ebcb7cfa2d5c2a20e80f7172cc\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2023,\"valor\":\"34980.00\",\"data\":\"2023-12-31\"}','846000349800030042024RF2023COM202508041931095542','CANCELADO','f7cdf0584e41177738491741dbe3f4c0fbd2d9bddf91e96774af9df1a968faf7','52b1cf03f2823fe0c9693419c192e67c79149e63283c2835f0043dbcdc5a1cf6','2025-08-04 19:31:09',1,NULL,'Comprovante de doações do exercício 2023','2025-08-04 22:31:09','2025-08-04 22:31:09'),(6,1,2023,'CERTIDAO_NEGATIVA','CERT20230001','RF2023CER202508041931091511','2023-12-31 00:00:00','2024-12-31 00:00:00',0.00,0.00,0.00,0.00,'9394ec124b26c611830ab183503305d5abc463d4da6d9dae6cad0a6151d5aef6','{\"protocolo\":\"RF2023CER202508041931091511\",\"hash\":\"9394ec124b26c611830ab183503305d5abc463d4da6d9dae6cad0a6151d5aef6\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2023,\"valor\":\"0.00\",\"data\":\"2023-12-31\"}','846000000000031122024RF2023CER202508041931091511','VALIDADO','6df1744be12f6d7a31973abf112d36677fc4e7dc358a1ae8d0d8adc64e3fba33','318c9822633b99479107306c90cb63331176cd2dbe1a646d16dcdadf8b6fbe75','2025-08-04 19:31:09',1,NULL,'Certidão negativa de débitos do exercício 2023','2025-08-04 22:31:09','2025-08-04 22:31:09'),(7,1,2024,'DECLARACAO_ANUAL','DEC20240001','RF2024DEC202508041931100893','2024-12-31 00:00:00','2025-04-30 00:00:00',83553.00,23043.00,72064.00,19511.00,'0bdb2feb46c68042a67222421aea37c72b0c67ee3ccdbf7fdff0ea06a6057afe','{\"protocolo\":\"RF2024DEC202508041931100893\",\"hash\":\"0bdb2feb46c68042a67222421aea37c72b0c67ee3ccdbf7fdff0ea06a6057afe\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2024,\"valor\":\"83553.00\",\"data\":\"2024-12-31\"}','846000835530030042025RF2024DEC202508041931100893','CANCELADO','dd10044e8936847267f58dcb038bee75ac90c0d9b1c0abbfb1dc3b3207d7fe1c','fee660f749d263fb4ccc7c2b5dc53afbf8c14476f10fbd85119b84128fccfb05','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(8,1,2024,'COMPROVANTE_DOACOES','DOA20240001','RF2024COM202508041931108806','2024-12-31 00:00:00','2025-04-30 00:00:00',41305.00,39638.00,0.00,0.00,'175fef1a165211140f8ef36d3ab0ae98d9b422ea370a48801fcdb13460fa2935','{\"protocolo\":\"RF2024COM202508041931108806\",\"hash\":\"175fef1a165211140f8ef36d3ab0ae98d9b422ea370a48801fcdb13460fa2935\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2024,\"valor\":\"41305.00\",\"data\":\"2024-12-31\"}','846000413050030042025RF2024COM202508041931108806','CANCELADO','42b8871041cb5ae3cf74ff30f246d56e3d8f54f696f68bbdc86cd92c556de902','d3d8cb02f9d04fa940c5ca9fabf392b7dcefdff0b2917e0bd536ac6b42af2880',NULL,1,NULL,'Comprovante de doações do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(9,1,2024,'CERTIDAO_NEGATIVA','CERT20240001','RF2024CER202508041931107717','2024-12-31 00:00:00','2025-12-31 00:00:00',0.00,0.00,0.00,0.00,'231f6fb92cbc09701e758e11ca0c075ee3923b575e62061254a94276a8082d86','{\"protocolo\":\"RF2024CER202508041931107717\",\"hash\":\"231f6fb92cbc09701e758e11ca0c075ee3923b575e62061254a94276a8082d86\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2024,\"valor\":\"0.00\",\"data\":\"2024-12-31\"}','846000000000031122025RF2024CER202508041931107717','PENDENTE','e6b61c642372214aca7a2b969a1c0e639b0bb7104f2b99030b4cfff52df0688f','8fa9d592d22a6e9336d8c7c7dfb780710947d095c427abd5fc7b33d91c4ffc3f',NULL,1,NULL,'Certidão negativa de débitos do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(10,2,2022,'DECLARACAO_ANUAL','DEC20220002','RF2022DEC202508041931106955','2022-12-31 00:00:00','2023-04-30 00:00:00',79498.00,53722.00,73075.00,8122.00,'5bcaae76bff4e53c631aa365c97446ee9c8091fddeb2ee805dababded4c7d96b','{\"protocolo\":\"RF2022DEC202508041931106955\",\"hash\":\"5bcaae76bff4e53c631aa365c97446ee9c8091fddeb2ee805dababded4c7d96b\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2022,\"valor\":\"79498.00\",\"data\":\"2022-12-31\"}','846000794980030042023RF2022DEC202508041931106955','APROVADO','d49f2edbe310beabb204f3bba5e264d35b971d9e8d83c6747ed4fbe4a7f1f0ca','3ed7dadc7204fb0e0528149a371f793153bc51df55357f80c3ff1255c947d9ef','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2022','2025-08-04 22:31:10','2025-08-04 22:31:10'),(11,2,2022,'COMPROVANTE_DOACOES','DOA20220002','RF2022COM202508041931101269','2024-12-31 00:00:00','2025-12-31 00:00:00',30313.00,24313.00,5000.00,1000.00,'a2cf37732ea1387ea3fc6015e6a7c8421e5919716052b92f3a7d2c4beadee519','{\"protocolo\":\"RF2022COM202508041931101269\",\"hash\":\"a2cf37732ea1387ea3fc6015e6a7c8421e5919716052b92f3a7d2c4beadee519\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":\"2022\",\"valor\":\"30313.00\",\"data\":\"2024-12-31\"}','RF2022RF2022COM202508041931101269a2cf37732e','VALIDADO','ff8ece9a8f17874e4ab5b0d61416d0666b2d7d1948e31f42630e65651ba5e098','f78884fe5914fc670eb9cbbd4428db6a80ee5818b7796bd21719140359fded67','2025-08-04 19:31:10',1,NULL,'Comprovante de doações do exercício 2022','2025-08-04 22:31:10','2025-08-04 23:42:01'),(12,2,2022,'CERTIDAO_NEGATIVA','CERT20220002','RF2022CER202508041931108202','2022-12-31 00:00:00','2023-12-31 00:00:00',0.00,0.00,0.00,0.00,'132afed5c409d13b2381cf0e4c9e47e1dc025b48647b129534c1a8c5328a7c19','{\"protocolo\":\"RF2022CER202508041931108202\",\"hash\":\"132afed5c409d13b2381cf0e4c9e47e1dc025b48647b129534c1a8c5328a7c19\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2022,\"valor\":\"0.00\",\"data\":\"2022-12-31\"}','846000000000031122023RF2022CER202508041931108202','PENDENTE','68e0d9bbfc4b54e3f730e94658fc4f13db19a431efc764cc5be782b193e52a88','5d49ae454c04f082df35e63320ca510066d0ba40f24db541e3968461eb5a7395','2025-08-04 19:31:10',1,NULL,'Certidão negativa de débitos do exercício 2022','2025-08-04 22:31:10','2025-08-04 22:31:10'),(13,2,2023,'DECLARACAO_ANUAL','DEC20230002','RF2023DEC202508041931108586','2023-12-31 00:00:00','2024-04-30 00:00:00',94257.00,52982.00,79634.00,15749.00,'19c1ea67bab73b22a0dd8a0aab4894093224efd7cb5f7a698eb70b77441dc04e','{\"protocolo\":\"RF2023DEC202508041931108586\",\"hash\":\"19c1ea67bab73b22a0dd8a0aab4894093224efd7cb5f7a698eb70b77441dc04e\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2023,\"valor\":\"94257.00\",\"data\":\"2023-12-31\"}','846000942570030042024RF2023DEC202508041931108586','REJEITADO','6a3a5823eac362b7ec8f64b11824240a8175caaf26237402844898af23905c3d','5e15adcbe6eaafc1748415d0148c208e6c2a3600a06af8fbeadd4ede46cc530e','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(14,2,2023,'COMPROVANTE_DOACOES','DOA20230002','RF2023COM202508041931109569','2023-12-31 00:00:00','2024-04-30 00:00:00',49301.00,78669.00,0.00,0.00,'b24955938ab9ade69936e4f3858517f88d2cae9d82dfdfb69d2184fd8b279e0f','{\"protocolo\":\"RF2023COM202508041931109569\",\"hash\":\"b24955938ab9ade69936e4f3858517f88d2cae9d82dfdfb69d2184fd8b279e0f\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2023,\"valor\":\"49301.00\",\"data\":\"2023-12-31\"}','846000493010030042024RF2023COM202508041931109569','APROVADO','8cebcedf55c496dc8f394e428bad4633c54c47c281ad763b3ab4830ca992f1e8','756fca2a00a229d2a65d5a299f402f3ad40ca3e7f957bbc1b944804380107880',NULL,1,NULL,'Comprovante de doações do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(15,2,2023,'CERTIDAO_NEGATIVA','CERT20230002','RF2023CER202508041931109410','2023-12-31 00:00:00','2024-12-31 00:00:00',0.00,0.00,0.00,0.00,'eab44801e635a5425323884140a7ab4a5c2304b9932d63a57e5d6980c6beb63c','{\"protocolo\":\"RF2023CER202508041931109410\",\"hash\":\"eab44801e635a5425323884140a7ab4a5c2304b9932d63a57e5d6980c6beb63c\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2023,\"valor\":\"0.00\",\"data\":\"2023-12-31\"}','846000000000031122024RF2023CER202508041931109410','VENCIDO','ad8e85f125bbc002366557d3d3967c6cba98393816b18bc967cff9b6657d0d64','fa9c5f66252e3268f5224504f3c94c15004cc416187c44f975a9adae951e35bc','2025-08-04 19:31:10',1,NULL,'Certidão negativa de débitos do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(16,2,2024,'DECLARACAO_ANUAL','DEC20240002','RF2024DEC202508041931108415','2024-12-31 00:00:00','2025-04-30 00:00:00',160768.00,50085.00,81689.00,18249.00,'632186d4bcea3375f2296a9652d901cc52ef3b1922f1dd9e5c8c5ce0b3fec42f','{\"protocolo\":\"RF2024DEC202508041931108415\",\"hash\":\"632186d4bcea3375f2296a9652d901cc52ef3b1922f1dd9e5c8c5ce0b3fec42f\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2024,\"valor\":\"160768.00\",\"data\":\"2024-12-31\"}','846001607680030042025RF2024DEC202508041931108415','REJEITADO','e7eb5529357456ac4a5765b0f4895725652f0c8f26afbbddcffb52c43cb28410','8973c393061f70b8134ba5c6b42213cd5e91c52e54aad2052518998a7da9552d','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(17,2,2024,'COMPROVANTE_DOACOES','DOA20240002','RF2024COM202508041931108517','2024-12-31 00:00:00','2025-04-30 00:00:00',65717.00,79691.00,0.00,0.00,'8e0148288d2134ad7ecacf21c607d356825dda9f92b0cff81c4258fddc7ae936','{\"protocolo\":\"RF2024COM202508041931108517\",\"hash\":\"8e0148288d2134ad7ecacf21c607d356825dda9f92b0cff81c4258fddc7ae936\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2024,\"valor\":\"65717.00\",\"data\":\"2024-12-31\"}','846000657170030042025RF2024COM202508041931108517','REJEITADO','3e01ac36a627d6cb717417c3bc6405d972a1f45eadfd08536f1d9d805123e58c','a8bf896121a3c491b9206e86963a43a9581213990fb85cdd716503fa1457a5da','2025-08-04 19:31:10',1,NULL,'Comprovante de doações do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(18,2,2024,'CERTIDAO_NEGATIVA','CERT20240002','RF2024CER202508041931104719','2024-12-31 00:00:00','2025-12-31 00:00:00',0.00,0.00,0.00,0.00,'e19da6310705f806578f2734d12e552245541bfd1370bc84ca71f7eced610f81','{\"protocolo\":\"RF2024CER202508041931104719\",\"hash\":\"e19da6310705f806578f2734d12e552245541bfd1370bc84ca71f7eced610f81\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2024,\"valor\":\"0.00\",\"data\":\"2024-12-31\"}','846000000000031122025RF2024CER202508041931104719','VALIDADO','d7c749ac9f311f20864791bfae8ddf2b325955866b5a5f6744eb096f177112ab','baa41aedf95322baf40ed2bfeb1172b00533367b3083844372b81011a13bf298','2025-08-04 19:31:10',1,NULL,'Certidão negativa de débitos do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(19,3,2022,'DECLARACAO_ANUAL','DEC20220003','RF2022DEC202508041931104570','2022-12-31 00:00:00','2023-04-30 00:00:00',142263.00,75500.00,83375.00,14544.00,'845f7091c98bf98e9ef0c3d41f8c267fe1bd00213fab5e0f79ce7a0ba7a4b6fb','{\"protocolo\":\"RF2022DEC202508041931104570\",\"hash\":\"845f7091c98bf98e9ef0c3d41f8c267fe1bd00213fab5e0f79ce7a0ba7a4b6fb\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2022,\"valor\":\"142263.00\",\"data\":\"2022-12-31\"}','846001422630030042023RF2022DEC202508041931104570','APROVADO','49924776f2599d05f1ba4e23164f237b4a4541555e4b3936fbe724b56804a42e','1620750d1eff03d8e19691ed7da23fd6d366ffb030f705dd84300684ed635a23','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2022','2025-08-04 22:31:10','2025-08-04 22:31:10'),(20,3,2022,'COMPROVANTE_DOACOES','DOA20220003','RF2022COM202508041931107961','2022-12-31 00:00:00','2023-04-30 00:00:00',27257.00,29764.00,0.00,0.00,'2a358aeb9c5d4417c73b986808229bf36b03d41f6f83df973bc70c5d52fe086c','{\"protocolo\":\"RF2022COM202508041931107961\",\"hash\":\"2a358aeb9c5d4417c73b986808229bf36b03d41f6f83df973bc70c5d52fe086c\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2022,\"valor\":\"27257.00\",\"data\":\"2022-12-31\"}','846000272570030042023RF2022COM202508041931107961','CANCELADO','15010aa2ea753371cb095dac7898f6d1ee3abad10f0c8ac804e98364160b4d18','7d29b7e93af67dedb4973a18bc9672c7dae275adbc2f2b9d132854a741f031c0','2025-08-04 19:31:10',1,NULL,'Comprovante de doações do exercício 2022','2025-08-04 22:31:10','2025-08-04 22:31:10'),(21,3,2022,'CERTIDAO_NEGATIVA','CERT20220003','RF2022CER202508041931105701','2022-12-31 00:00:00','2023-12-31 00:00:00',0.00,0.00,0.00,0.00,'285141f8e39f8280e660ae9e8434be9386425aa0337df9d5477d8319f57f132d','{\"protocolo\":\"RF2022CER202508041931105701\",\"hash\":\"285141f8e39f8280e660ae9e8434be9386425aa0337df9d5477d8319f57f132d\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2022,\"valor\":\"0.00\",\"data\":\"2022-12-31\"}','846000000000031122023RF2022CER202508041931105701','CANCELADO','045080b722d8c61a348cfbbb98c93ac39a7a770f44db97a33ce4297a2ff90bcb','f88c819e4fb2f3b406e936e3da2aecdb9a68ababe0072d45ccd74eb3c3f1605b','2025-08-04 19:31:10',1,NULL,'Certidão negativa de débitos do exercício 2022','2025-08-04 22:31:10','2025-08-04 22:31:10'),(22,3,2023,'DECLARACAO_ANUAL','DEC20230003','RF2023DEC202508041931105403','2023-12-31 00:00:00','2024-04-30 00:00:00',115261.00,37456.00,85202.00,15638.00,'a88c81bbc6e24389fa760a0e5549830c3f4fc9fd434be67f6bce66e49741ca4d','{\"protocolo\":\"RF2023DEC202508041931105403\",\"hash\":\"a88c81bbc6e24389fa760a0e5549830c3f4fc9fd434be67f6bce66e49741ca4d\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2023,\"valor\":\"115261.00\",\"data\":\"2023-12-31\"}','846001152610030042024RF2023DEC202508041931105403','REJEITADO','17702fb2ba6ddaac9032acd5c8a6b7dfe8831a00a93a25ddbbecff8e1e7f47cb','f1be9318a61818d7085fc93f64d3287f177a3f71e69d75935889fb4997413394','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(23,3,2023,'COMPROVANTE_DOACOES','DOA20230003','RF2023COM202508041931108956','2023-12-31 00:00:00','2024-04-30 00:00:00',61368.00,43711.00,0.00,0.00,'75f54f8ac52b9f124d63dd223e7137b18e641de519e0af654c81fe8df5437b12','{\"protocolo\":\"RF2023COM202508041931108956\",\"hash\":\"75f54f8ac52b9f124d63dd223e7137b18e641de519e0af654c81fe8df5437b12\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2023,\"valor\":\"61368.00\",\"data\":\"2023-12-31\"}','846000613680030042024RF2023COM202508041931108956','VALIDADO','325c005664a91539a9327fbe8097ddfe5f07355feb02d4e3d0b0a9129dcd129b','ce452ee9f55a586d05ad3793830acf53b0eca23477662c1cbe53d8a6ed25e310','2025-08-04 19:31:10',1,NULL,'Comprovante de doações do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(24,3,2023,'CERTIDAO_NEGATIVA','CERT20230003','RF2023CER202508041931104255','2023-12-31 00:00:00','2024-12-31 00:00:00',0.00,0.00,0.00,0.00,'011ccdd4032e333494155664a3c2069907614c6897586e4b48904f842299a723','{\"protocolo\":\"RF2023CER202508041931104255\",\"hash\":\"011ccdd4032e333494155664a3c2069907614c6897586e4b48904f842299a723\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2023,\"valor\":\"0.00\",\"data\":\"2023-12-31\"}','846000000000031122024RF2023CER202508041931104255','VALIDADO','0bab82afe75c53190b368ae584f78bca93442ce7e803755f741d67b64576b9dd','0a765bddb7e91c6170c3b042ad3faad068b76fdbb03c301d75abf830f9e99ba1',NULL,1,NULL,'Certidão negativa de débitos do exercício 2023','2025-08-04 22:31:10','2025-08-04 22:31:10'),(25,3,2024,'DECLARACAO_ANUAL','DEC20240003','RF2024DEC202508041931102115','2024-12-31 00:00:00','2025-04-30 00:00:00',74468.00,32166.00,56986.00,12288.00,'37ec9d4775119263a3dff487bffbdad6658379c07f5142a5618b27a300e9c548','{\"protocolo\":\"RF2024DEC202508041931102115\",\"hash\":\"37ec9d4775119263a3dff487bffbdad6658379c07f5142a5618b27a300e9c548\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2024,\"valor\":\"74468.00\",\"data\":\"2024-12-31\"}','846000744680030042025RF2024DEC202508041931102115','CANCELADO','754338550d6fe53cd8e702eeb881cf2e37e90b8c9f238ff9f09c24362a4518ea','813acf6933a9344b7145c32e465a7fd0967dc7e90600d83ad035319dd0f39387','2025-08-04 19:31:10',1,NULL,'Documento de declaração anual do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(26,3,2024,'COMPROVANTE_DOACOES','DOA20240003','RF2024COM202508041931106359','2024-12-31 00:00:00','2025-04-30 00:00:00',34198.00,56730.00,0.00,0.00,'d91383702a1333a9a07f29abb061bfd4fa714409125a47cbf7dad0dcf3221c70','{\"protocolo\":\"RF2024COM202508041931106359\",\"hash\":\"d91383702a1333a9a07f29abb061bfd4fa714409125a47cbf7dad0dcf3221c70\",\"tipo\":\"COMPROVANTE_DOACOES\",\"ano\":2024,\"valor\":\"34198.00\",\"data\":\"2024-12-31\"}','846000341980030042025RF2024COM202508041931106359','VALIDADO','650d550ea2f2954aa41802167eb01cf6f62461a9e24f5ec58402780c69883df3','4da05ac5fd784d0aa9d038b1c8e945a5113feefcd7d2a402597f66e10c7e80a6','2025-08-04 19:31:10',1,NULL,'Comprovante de doações do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(27,3,2024,'CERTIDAO_NEGATIVA','CERT20240003','RF2024CER202508041931103640','2024-12-31 00:00:00','2025-12-31 00:00:00',0.00,0.00,0.00,0.00,'05e9b4815055bad0db61104b9110abfcd1f0fbb803c21bebd1bb966ebc110bde','{\"protocolo\":\"RF2024CER202508041931103640\",\"hash\":\"05e9b4815055bad0db61104b9110abfcd1f0fbb803c21bebd1bb966ebc110bde\",\"tipo\":\"CERTIDAO_NEGATIVA\",\"ano\":2024,\"valor\":\"0.00\",\"data\":\"2024-12-31\"}','846000000000031122025RF2024CER202508041931103640','REJEITADO','29c578fd1f529c158680faa57f58137a4a46de00080daa0d9f0bc36b24a3c9b2','7c90ab2bf1bcb63d7cb3d8fd51831b917d7d59f14534cf1303cc21e1c56bd265',NULL,1,NULL,'Certidão negativa de débitos do exercício 2024','2025-08-04 22:31:10','2025-08-04 22:31:10'),(30,1,2024,'DECLARACAO_ANUAL','DA-2024-001','2024DA00123456789','2024-01-15 10:00:00','2024-04-30 23:59:59',150000.00,80000.00,60000.00,10000.00,'hash_2024_declaracao_anual_001_abc123def456','{\"protocolo\":\"2024DA00123456789\",\"hash\":\"hash_2024_declaracao_anual_001_abc123def456\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2024,\"valor\":\"150000.00\",\"data\":\"2024-01-15\"}','RF20242024DA0012345678900000000hash_2024_','PENDENTE','37147bd63a54433a8adb5b7ecf4ed1cc8f8111fa0b4cfec34d4fdbb67bfca185','0d673634c85d6735055de8dd3b440a00232c64ade037a601991aa175031d9cf6',NULL,NULL,NULL,'Declaração anual de 2024 - Todos os valores declarados corretamente','2025-08-06 02:39:32','2025-08-06 02:39:32'),(31,1,2023,'DECLARACAO_ANUAL','DA-2023-001','2023DA00123456789','2023-01-15 10:00:00','2023-04-30 23:59:59',120000.00,65000.00,45000.00,10000.00,'hash_2023_declaracao_anual_001_abc123def456','{\"protocolo\":\"2023DA00123456789\",\"hash\":\"hash_2023_declaracao_anual_001_abc123def456\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2023,\"valor\":\"120000.00\",\"data\":\"2023-01-15\"}','RF20232023DA0012345678900000000hash_2023_','PROCESSADO','6896c751c97f06419e4d7f3c81f20b218129b10dfea03f2e2789bb17cbc26447','a69c49432f7a5bd5a237fbf36d85bce3b4f626cbc333c6409b453850ee165cfe','2023-05-15 14:30:00',1,NULL,'Declaração anual de 2023 - Processada pela Receita Federal','2025-08-06 02:39:32','2025-08-06 02:39:32'),(32,1,2022,'DECLARACAO_ANUAL','DA-2022-001','2022DA00123456789','2022-01-15 10:00:00','2022-04-30 23:59:59',95000.00,50000.00,35000.00,10000.00,'hash_2022_declaracao_anual_001_abc123def456','{\"protocolo\":\"2022DA00123456789\",\"hash\":\"hash_2022_declaracao_anual_001_abc123def456\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2022,\"valor\":\"95000.00\",\"data\":\"2022-01-15\"}','RF20222022DA0012345678900000000hash_2022_','PROCESSADO','7ea3aa9212123f042eb4bf939da80ebb7a3a572949668b64664e6d58382df027','63d1c3d4960f7c53d09bb220234e0ae098e14723a17245d27e17776ba01085af','2022-05-15 14:30:00',1,NULL,'Declaração anual de 2022 - Processada pela Receita Federal','2025-08-06 02:39:32','2025-08-06 02:39:32'),(33,1,2021,'DECLARACAO_ANUAL','DA-2021-001','2021DA00123456789','2021-01-15 10:00:00','2021-04-30 23:59:59',85000.00,45000.00,30000.00,10000.00,'hash_2021_declaracao_anual_001_abc123def456','{\"protocolo\":\"2021DA00123456789\",\"hash\":\"hash_2021_declaracao_anual_001_abc123def456\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2021,\"valor\":\"85000.00\",\"data\":\"2021-01-15\"}','RF20212021DA0012345678900000000hash_2021_','PROCESSADO','868b720ed29b2cd404f7e88da5e3bb566a28b1fe563ef27deefdcde9faf7b13a','1313416629f6235b6709be7eb93626640291e653de5c59f51daeb3995c42ab26','2021-05-15 14:30:00',1,NULL,'Declaração anual de 2021 - Processada pela Receita Federal','2025-08-06 02:39:32','2025-08-06 02:39:32'),(34,1,2020,'DECLARACAO_ANUAL','DA-2020-001','2020DA00123456789','2020-01-15 10:00:00','2020-04-30 23:59:59',75000.00,40000.00,25000.00,10000.00,'hash_2020_declaracao_anual_001_abc123def456','{\"protocolo\":\"2020DA00123456789\",\"hash\":\"hash_2020_declaracao_anual_001_abc123def456\",\"tipo\":\"DECLARACAO_ANUAL\",\"ano\":2020,\"valor\":\"75000.00\",\"data\":\"2020-01-15\"}','RF20202020DA0012345678900000000hash_2020_','PROCESSADO','a38c1b95645b6d3ceb94e5b2d64faa4542381b235af80cdc45e967950ccd9fcc','d6d6e5ec6ae6f4533474ff450c1a822e2a1a3084e834823cc1c772c2c239adc7','2020-05-15 14:30:00',1,NULL,'Declaração anual de 2020 - Processada pela Receita Federal','2025-08-06 02:39:32','2025-08-06 02:39:32');
/*!40000 ALTER TABLE `documentos_declaracao_anual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_alunos`
--

DROP TABLE IF EXISTS `ebd_alunos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_alunos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `nome_responsavel` varchar(255) DEFAULT NULL,
  `telefone_responsavel` varchar(255) DEFAULT NULL,
  `turma_id` bigint(20) unsigned NOT NULL,
  `data_matricula` date NOT NULL,
  `data_saida` date DEFAULT NULL,
  `status` enum('ativo','inativo','transferido') NOT NULL DEFAULT 'ativo',
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_alunos_membro_id_foreign` (`membro_id`),
  KEY `ebd_alunos_turma_id_foreign` (`turma_id`),
  CONSTRAINT `ebd_alunos_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ebd_alunos_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `ebd_turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_alunos`
--

LOCK TABLES `ebd_alunos` WRITE;
/*!40000 ALTER TABLE `ebd_alunos` DISABLE KEYS */;
INSERT INTO `ebd_alunos` VALUES (1,NULL,'João Pedro Silva','maria.silva@email.com',NULL,'2022-03-15','Maria Silva','(11) 99999-1001',1,'2024-01-15',NULL,'ativo','Aluno muito ativo e participativo','2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,NULL,'Ana Clara Santos','carlos.santos@email.com',NULL,'2022-07-20','Carlos Santos','(11) 99999-1002',1,'2024-01-20',NULL,'ativo','Primeira vez na EBD','2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,NULL,'Lucas Oliveira','pedro.oliveira@email.com',NULL,'2021-05-10','Pedro Oliveira','(11) 99999-1003',2,'2024-01-10',NULL,'ativo','Gosta muito de histórias bíblicas','2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,NULL,'Isabella Costa','fernanda.costa@email.com',NULL,'2021-09-25','Fernanda Costa','(11) 99999-1004',2,'2024-01-12',NULL,'ativo','Muito criativa nas atividades','2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,NULL,'Gabriel Almeida','ricardo.almeida@email.com',NULL,'2019-12-05','Ricardo Almeida','(11) 99999-1005',3,'2024-01-08',NULL,'ativo','Excelente memorização de versículos','2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,NULL,'Carolina Lima','roberto.lima@email.com',NULL,'2011-12-03','Roberto Lima','(11) 99999-1012',6,'2024-01-06',NULL,'ativo','Muito envolvida nas discussões','2025-08-06 02:35:20','2025-08-06 02:37:38'),(7,NULL,'Matheus Costa','ana.costa@email.com',NULL,'2017-03-22','Ana Costa','(11) 99999-1007',4,'2024-01-03',NULL,'ativo','Interessado em história bíblica','2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,NULL,'Julia Santos','patricia.santos@email.com',NULL,'2017-11-08','Patricia Santos','(11) 99999-1008',4,'2024-01-07',NULL,'ativo','Gosta de atividades práticas','2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,NULL,'Pedro Almeida','carlos.almeida@email.com',NULL,'2014-06-12','Carlos Almeida','(11) 99999-1009',5,'2024-01-02',NULL,'ativo','Muito questionador e curioso','2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,NULL,'Mariana Silva','fernando.silva@email.com',NULL,'2014-09-30','Fernando Silva','(11) 99999-1010',5,'2024-01-04',NULL,'ativo','Excelente participação','2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,NULL,'Rafael Oliveira','paulo.oliveira@email.com',NULL,'2011-04-18','Paulo Oliveira','(11) 99999-1011',6,'2024-01-01',NULL,'ativo','Interessado em temas atuais','2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,NULL,'Thiago Costa','antonio.costa@email.com',NULL,'2006-07-25','Antonio Costa','(11) 99999-1013',7,'2024-01-01',NULL,'ativo','Estudos bíblicos profundos','2025-08-06 02:35:20','2025-08-06 02:35:20'),(13,NULL,'Amanda Santos','marcos.santos@email.com',NULL,'2006-03-14','Marcos Santos','(11) 99999-1014',7,'2024-01-05',NULL,'ativo','Muito dedicada aos estudos','2025-08-06 02:35:20','2025-08-06 02:35:20'),(14,NULL,'Roberto Silva','roberto.silva@email.com','(11) 99999-1015','1985-08-10',NULL,NULL,8,'2024-01-01',NULL,'ativo','Membro ativo da igreja','2025-08-06 02:35:20','2025-08-06 02:35:20'),(15,NULL,'Lucia Ferreira','lucia.ferreira@email.com','(11) 99999-1016','1988-12-05',NULL,NULL,8,'2024-01-03',NULL,'ativo','Muito participativa','2025-08-06 02:35:20','2025-08-06 02:35:20'),(16,NULL,'João Pedro Costa','joao.costa@email.com','(11) 99999-1017','1955-05-20',NULL,NULL,9,'2024-01-01',NULL,'ativo','Membro sênior da igreja','2025-08-06 02:35:20','2025-08-06 02:35:20'),(17,NULL,'Maria Helena Silva','maria.helena@email.com','(11) 99999-1018','1958-09-15',NULL,NULL,9,'2024-01-02',NULL,'ativo','Muito dedicada aos estudos','2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ebd_alunos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_aulas`
--

DROP TABLE IF EXISTS `ebd_aulas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_aulas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `turma_id` bigint(20) unsigned NOT NULL,
  `licao_id` bigint(20) unsigned NOT NULL,
  `professor_id` bigint(20) unsigned DEFAULT NULL,
  `data_aula` date NOT NULL,
  `horario_inicio` time NOT NULL,
  `horario_fim` time NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('agendada','realizada','cancelada','adiada') NOT NULL DEFAULT 'agendada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_aulas_turma_id_foreign` (`turma_id`),
  KEY `ebd_aulas_licao_id_foreign` (`licao_id`),
  KEY `ebd_aulas_professor_id_foreign` (`professor_id`),
  CONSTRAINT `ebd_aulas_licao_id_foreign` FOREIGN KEY (`licao_id`) REFERENCES `ebd_licoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_aulas_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `ebd_professores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ebd_aulas_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `ebd_turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_aulas`
--

LOCK TABLES `ebd_aulas` WRITE;
/*!40000 ALTER TABLE `ebd_aulas` DISABLE KEYS */;
INSERT INTO `ebd_aulas` VALUES (1,8,1,1,'2024-01-07','09:00:00','09:45:00','Aula muito produtiva, alunos engajados. Discussão sobre criação vs evolução.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,8,3,1,'2024-01-14','09:00:00','09:45:00','Aula transformadora para muitos alunos. Muitos testemunhos pessoais.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,8,6,1,'2024-01-21','09:00:00','09:45:00','Discussões acaloradas sobre aplicação dos mandamentos na vida moderna.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,7,4,1,'2024-01-07','09:00:00','09:45:00','Excelente participação. Jovens identificaram-se com a história de José.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,7,7,1,'2024-01-14','09:00:00','09:45:00','Foco especial nos salmos de Davi e sua relação com Deus.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,7,10,1,'2024-01-21','09:00:00','09:45:00','Discussão sobre milagres na atualidade e poder de Jesus.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,6,2,7,'2024-01-07','09:00:00','09:45:00','Aula com dramatização da história. Adolescentes muito participativos.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,6,5,7,'2024-01-14','09:00:00','09:45:00','Foco na liderança e coragem de Moisés. Aplicação para vida dos adolescentes.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,6,9,7,'2024-01-21','09:00:00','09:45:00','Aula especial sobre o Natal. Músicas e atividades criativas.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,4,1,3,'2024-01-07','09:00:00','09:45:00','Atividades manuais sobre a criação. Crianças muito criativas.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,4,4,3,'2024-01-14','09:00:00','09:45:00','Dramatização da história de José. Fantoches e atividades interativas.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,4,6,3,'2024-01-21','09:00:00','09:45:00','Memorização dos mandamentos com músicas e jogos.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(13,3,1,9,'2024-01-07','09:00:00','09:45:00','História com figuras coloridas. Atividades de pintura sobre a criação.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(14,3,9,9,'2024-01-14','09:00:00','09:45:00','Presépio montado pelas crianças. Músicas natalinas.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(15,3,10,9,'2024-01-21','09:00:00','09:45:00','Milagres contados de forma simples e lúdica.','realizada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(16,8,8,1,'2024-02-04','09:00:00','09:45:00','Aula sobre os profetas e suas mensagens para hoje.','agendada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(17,7,11,1,'2024-02-04','09:00:00','09:45:00','Estudo sobre a Páscoa e o significado da morte e ressurreição.','agendada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(18,6,12,7,'2024-02-04','09:00:00','09:45:00','História da igreja primitiva e como podemos viver como eles.','agendada','2025-08-06 02:35:20','2025-08-06 02:35:20'),(19,8,1,1,'2024-01-07','09:00:00','09:45:00','Aula muito produtiva, alunos engajados. Discussão sobre criação vs evolução.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(20,8,3,1,'2024-01-14','09:00:00','09:45:00','Aula transformadora para muitos alunos. Muitos testemunhos pessoais.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(21,8,6,1,'2024-01-21','09:00:00','09:45:00','Discussões acaloradas sobre aplicação dos mandamentos na vida moderna.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(22,7,4,1,'2024-01-07','09:00:00','09:45:00','Excelente participação. Jovens identificaram-se com a história de José.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(23,7,7,1,'2024-01-14','09:00:00','09:45:00','Foco especial nos salmos de Davi e sua relação com Deus.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(24,7,10,1,'2024-01-21','09:00:00','09:45:00','Discussão sobre milagres na atualidade e poder de Jesus.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(25,6,2,7,'2024-01-07','09:00:00','09:45:00','Aula com dramatização da história. Adolescentes muito participativos.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(26,6,5,7,'2024-01-14','09:00:00','09:45:00','Foco na liderança e coragem de Moisés. Aplicação para vida dos adolescentes.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(27,6,9,7,'2024-01-21','09:00:00','09:45:00','Aula especial sobre o Natal. Músicas e atividades criativas.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(28,4,1,3,'2024-01-07','09:00:00','09:45:00','Atividades manuais sobre a criação. Crianças muito criativas.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(29,4,4,3,'2024-01-14','09:00:00','09:45:00','Dramatização da história de José. Fantoches e atividades interativas.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(30,4,6,3,'2024-01-21','09:00:00','09:45:00','Memorização dos mandamentos com músicas e jogos.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(31,3,1,9,'2024-01-07','09:00:00','09:45:00','História com figuras coloridas. Atividades de pintura sobre a criação.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(32,3,9,9,'2024-01-14','09:00:00','09:45:00','Presépio montado pelas crianças. Músicas natalinas.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(33,3,10,9,'2024-01-21','09:00:00','09:45:00','Milagres contados de forma simples e lúdica.','realizada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(34,8,8,1,'2024-02-04','09:00:00','09:45:00','Aula sobre os profetas e suas mensagens para hoje.','agendada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(35,7,11,1,'2024-02-04','09:00:00','09:45:00','Estudo sobre a Páscoa e o significado da morte e ressurreição.','agendada','2025-08-06 02:37:41','2025-08-06 02:37:41'),(36,6,12,7,'2024-02-04','09:00:00','09:45:00','História da igreja primitiva e como podemos viver como eles.','agendada','2025-08-06 02:37:41','2025-08-06 02:37:41');
/*!40000 ALTER TABLE `ebd_aulas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_avaliacoes`
--

DROP TABLE IF EXISTS `ebd_avaliacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_avaliacoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('quiz','prova','trabalho','participacao') NOT NULL DEFAULT 'quiz',
  `pontuacao_maxima` int(11) NOT NULL DEFAULT 10,
  `obrigatoria` tinyint(1) NOT NULL DEFAULT 1,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_avaliacoes_aula_id_foreign` (`aula_id`),
  CONSTRAINT `ebd_avaliacoes_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `ebd_aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_avaliacoes`
--

LOCK TABLES `ebd_avaliacoes` WRITE;
/*!40000 ALTER TABLE `ebd_avaliacoes` DISABLE KEYS */;
INSERT INTO `ebd_avaliacoes` VALUES (1,1,'Quiz sobre a Criação','Avaliação sobre o conteúdo da lição A Criação do Mundo','quiz',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,1,'Participação na Aula - Criação','Avaliação da participação dos alunos na discussão sobre a criação','participacao',5,0,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,2,'Prova sobre a Fé de Abraão','Avaliação sobre o conteúdo da lição A Fé de Abraão','prova',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,2,'Trabalho sobre Aplicação da Fé','Trabalho prático sobre como aplicar a fé de Abraão na vida','trabalho',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,3,'Quiz sobre os Dez Mandamentos','Avaliação sobre o conteúdo da lição Os Dez Mandamentos','quiz',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,4,'Quiz sobre a História de José','Avaliação sobre o conteúdo da lição José: Do Poço ao Palácio','quiz',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,4,'Dramatização da História de José','Avaliação da participação na dramatização da história de José','participacao',5,0,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,5,'Prova sobre a Vida de Davi','Avaliação sobre o conteúdo da lição Davi: Um Homem Segundo o Coração de Deus','prova',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,8,'Quiz sobre Moisés','Avaliação sobre o conteúdo da lição Moisés: O Libertador','quiz',10,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,8,'Trabalho sobre Liderança','Trabalho sobre como aplicar os princípios de liderança de Moisés','trabalho',10,0,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,1,'Quiz sobre a Criação','Avaliação sobre o conteúdo da lição A Criação do Mundo','quiz',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(12,1,'Participação na Aula - Criação','Avaliação da participação dos alunos na discussão sobre a criação','participacao',5,0,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(13,2,'Prova sobre a Fé de Abraão','Avaliação sobre o conteúdo da lição A Fé de Abraão','prova',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(14,2,'Trabalho sobre Aplicação da Fé','Trabalho prático sobre como aplicar a fé de Abraão na vida','trabalho',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(15,3,'Quiz sobre os Dez Mandamentos','Avaliação sobre o conteúdo da lição Os Dez Mandamentos','quiz',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(16,4,'Quiz sobre a História de José','Avaliação sobre o conteúdo da lição José: Do Poço ao Palácio','quiz',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(17,4,'Dramatização da História de José','Avaliação da participação na dramatização da história de José','participacao',5,0,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(18,5,'Prova sobre a Vida de Davi','Avaliação sobre o conteúdo da lição Davi: Um Homem Segundo o Coração de Deus','prova',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(19,8,'Quiz sobre Moisés','Avaliação sobre o conteúdo da lição Moisés: O Libertador','quiz',10,1,1,'2025-08-06 02:37:42','2025-08-06 02:37:42'),(20,8,'Trabalho sobre Liderança','Trabalho sobre como aplicar os princípios de liderança de Moisés','trabalho',10,0,1,'2025-08-06 02:37:42','2025-08-06 02:37:42');
/*!40000 ALTER TABLE `ebd_avaliacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_certificados`
--

DROP TABLE IF EXISTS `ebd_certificados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_certificados` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aluno_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_conclusao` date NOT NULL,
  `codigo_verificacao` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ebd_certificados_codigo_verificacao_unique` (`codigo_verificacao`),
  KEY `ebd_certificados_aluno_id_foreign` (`aluno_id`),
  CONSTRAINT `ebd_certificados_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `ebd_alunos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_certificados`
--

LOCK TABLES `ebd_certificados` WRITE;
/*!40000 ALTER TABLE `ebd_certificados` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_certificados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_licoes`
--

DROP TABLE IF EXISTS `ebd_licoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_licoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `objetivos` text DEFAULT NULL,
  `versiculo_chave` text DEFAULT NULL,
  `conteudo` text NOT NULL,
  `aplicacao_pratica` text DEFAULT NULL,
  `oracao` text DEFAULT NULL,
  `material_necessario` varchar(255) DEFAULT NULL,
  `duracao_minutos` int(11) NOT NULL DEFAULT 60,
  `dificuldade` enum('facil','medio','dificil') NOT NULL DEFAULT 'medio',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_licoes`
--

LOCK TABLES `ebd_licoes` WRITE;
/*!40000 ALTER TABLE `ebd_licoes` DISABLE KEYS */;
INSERT INTO `ebd_licoes` VALUES (1,'A Criação do Mundo','Estudo sobre a criação do mundo em seis dias','Compreender que Deus é o criador de todas as coisas','Gênesis 1:1','Esta lição aborda a criação do mundo em seis dias, mostrando o poder e a sabedoria de Deus. Vamos estudar cada dia da criação e suas implicações para nossa fé.','Reconhecer que tudo foi criado por Deus e que devemos cuidar da criação','Senhor, obrigado por criar todas as coisas. Ajuda-nos a cuidar da sua criação.','Bíblia, cartazes da criação, atividades manuais',45,'facil',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,'A Queda do Homem','Estudo sobre a entrada do pecado no mundo','Entender as consequências do pecado e a necessidade de redenção','Gênesis 3:6','Estudaremos como o pecado entrou no mundo através da desobediência de Adão e Eva, e as consequências que isso trouxe para toda a humanidade.','Reconhecer a importância da obediência a Deus e as consequências do pecado','Senhor, perdoa nossos pecados e ajuda-nos a obedecer à tua vontade.','Bíblia, figuras da história, dramatização',50,'facil',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,'A Fé de Abraão','Estudo sobre a vida de fé de Abraão','Aprender sobre a fé e obediência através da vida de Abraão','Gênesis 12:1','Vamos estudar como Abraão demonstrou fé ao deixar sua terra e seguir a direção de Deus, tornando-se o pai da fé.','Desenvolver uma fé que confia em Deus mesmo quando não entendemos o caminho','Senhor, aumenta nossa fé para confiar em ti como Abraão confiou.','Bíblia, mapa da jornada de Abraão, atividades de confiança',55,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,'José: Do Poço ao Palácio','Estudo sobre a vida de José e a providência divina','Ver como Deus trabalha em todas as circunstâncias para o bem','Gênesis 50:20','A história de José mostra como Deus pode transformar situações difíceis em bênçãos, usando tudo para cumprir Seu propósito.','Confiar que Deus está trabalhando mesmo nas dificuldades','Senhor, ajuda-nos a confiar que tu trabalhas em todas as coisas para o nosso bem.','Bíblia, fantoches, dramatização da história',60,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,'Moisés: O Libertador','Estudo sobre a vida de Moisés e a libertação do Egito','Entender como Deus usa pessoas comuns para realizar grandes feitos','Êxodo 14:14','A vida de Moisés mostra como Deus prepara e usa Seus servos para libertar Seu povo e cumprir Suas promessas.','Reconhecer que Deus pode usar cada um de nós para Sua obra','Senhor, usa-nos como instrumentos para tua obra, assim como usaste Moisés.','Bíblia, figuras das pragas, dramatização',65,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,'Os Dez Mandamentos','Estudo sobre os dez mandamentos de Deus','Compreender a importância dos mandamentos de Deus','Êxodo 20:2-3','Estudaremos os dez mandamentos como guia para uma vida que agrada a Deus e promove o bem-estar da sociedade.','Aplicar os princípios dos mandamentos em nossa vida diária','Senhor, ajuda-nos a guardar os teus mandamentos com amor e gratidão.','Bíblia, cartazes dos mandamentos, atividades práticas',70,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,'Davi: Um Homem Segundo o Coração de Deus','Estudo sobre a vida de Davi e suas qualidades','Aprender sobre o caráter que agrada a Deus','1 Samuel 16:7','A vida de Davi mostra as qualidades que Deus valoriza: coragem, fé, arrependimento e dependência de Deus.','Desenvolver um caráter que agrada a Deus','Senhor, molda nosso coração para ser segundo o teu coração, como foi Davi.','Bíblia, história de Davi e Golias, atividades',60,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,'Os Profetas: Voz de Deus','Estudo sobre os profetas do Antigo Testamento','Entender o papel dos profetas e a importância de ouvir a voz de Deus','Isaías 6:8','Estudaremos como os profetas foram usados por Deus para falar ao Seu povo, chamando ao arrependimento e anunciando a esperança.','Estar atento à voz de Deus em nossa vida','Senhor, abre nossos ouvidos para ouvir tua voz e nosso coração para obedecer.','Bíblia, profecias messiânicas, atividades',75,'dificil',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,'O Nascimento de Jesus','Estudo sobre o nascimento de Jesus Cristo','Celebrar o nascimento de Jesus e seu significado','Lucas 2:11','A história do nascimento de Jesus mostra como Deus cumpriu Suas promessas e veio ao mundo para salvar a humanidade.','Celebrar Jesus como o Salvador prometido','Senhor Jesus, obrigado por vir ao mundo para nos salvar. Ajuda-nos a celebrar teu nascimento com gratidão.','Bíblia, presépio, músicas natalinas',50,'facil',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,'Os Milagres de Jesus','Estudo sobre os milagres de Jesus Cristo','Compreender o poder e a compaixão de Jesus através de Seus milagres','João 20:30-31','Vamos estudar alguns dos principais milagres de Jesus, mostrando Seu poder divino e Sua compaixão pelos necessitados.','Confiar no poder de Jesus para transformar vidas','Senhor Jesus, confiamos no teu poder para fazer milagres em nossas vidas.','Bíblia, figuras dos milagres, dramatizações',60,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,'A Morte e Ressurreição de Jesus','Estudo sobre o sacrifício e vitória de Jesus','Entender o significado da morte e ressurreição de Jesus','João 3:16','O sacrifício de Jesus na cruz e Sua ressurreição são o fundamento da fé cristã, mostrando o amor de Deus e a vitória sobre o pecado.','Aceitar o sacrifício de Jesus e viver em gratidão','Senhor Jesus, obrigado por teu sacrifício na cruz. Ajuda-nos a viver em gratidão pela tua ressurreição.','Bíblia, cruz, símbolos da Páscoa',70,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,'A Igreja Primitiva','Estudo sobre o nascimento e desenvolvimento da igreja','Entender como a igreja começou e se desenvolveu','Atos 2:42','Estudaremos o nascimento da igreja, o derramamento do Espírito Santo e como os primeiros cristãos viviam e compartilhavam o evangelho.','Participar ativamente da vida da igreja','Senhor, ajuda-nos a viver em comunhão como a igreja primitiva.','Bíblia, mapa das viagens missionárias',65,'medio',1,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ebd_licoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_notas`
--

DROP TABLE IF EXISTS `ebd_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_notas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `avaliacao_id` bigint(20) unsigned NOT NULL,
  `aluno_id` bigint(20) unsigned NOT NULL,
  `nota` int(11) NOT NULL,
  `pontuacao_maxima` int(11) NOT NULL,
  `percentual` decimal(5,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_notas_avaliacao_id_foreign` (`avaliacao_id`),
  KEY `ebd_notas_aluno_id_foreign` (`aluno_id`),
  CONSTRAINT `ebd_notas_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `ebd_alunos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_notas_avaliacao_id_foreign` FOREIGN KEY (`avaliacao_id`) REFERENCES `ebd_avaliacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_notas`
--

LOCK TABLES `ebd_notas` WRITE;
/*!40000 ALTER TABLE `ebd_notas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_presencas`
--

DROP TABLE IF EXISTS `ebd_presencas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_presencas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `aluno_id` bigint(20) unsigned NOT NULL,
  `status` enum('presente','ausente','justificado','atrasado') NOT NULL DEFAULT 'presente',
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_presencas_aula_id_foreign` (`aula_id`),
  KEY `ebd_presencas_aluno_id_foreign` (`aluno_id`),
  CONSTRAINT `ebd_presencas_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `ebd_alunos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_presencas_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `ebd_aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_presencas`
--

LOCK TABLES `ebd_presencas` WRITE;
/*!40000 ALTER TABLE `ebd_presencas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_presencas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_professores`
--

DROP TABLE IF EXISTS `ebd_professores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_professores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned NOT NULL,
  `turma_id` bigint(20) unsigned NOT NULL,
  `tipo` enum('principal','auxiliar') NOT NULL DEFAULT 'auxiliar',
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_professores_membro_id_foreign` (`membro_id`),
  KEY `ebd_professores_turma_id_foreign` (`turma_id`),
  CONSTRAINT `ebd_professores_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_professores_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `ebd_turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_professores`
--

LOCK TABLES `ebd_professores` WRITE;
/*!40000 ALTER TABLE `ebd_professores` DISABLE KEYS */;
INSERT INTO `ebd_professores` VALUES (1,22,8,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,23,1,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,24,4,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,25,2,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,26,5,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,27,1,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,28,6,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,29,2,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,30,3,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,31,3,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,32,4,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,33,5,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(13,34,9,'principal','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(14,35,9,'auxiliar','2024-01-01',NULL,1,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ebd_professores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_questoes`
--

DROP TABLE IF EXISTS `ebd_questoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_questoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `avaliacao_id` bigint(20) unsigned NOT NULL,
  `pergunta` text NOT NULL,
  `tipo` enum('multipla_escolha','verdadeiro_falso','dissertativa','correspondencia') NOT NULL DEFAULT 'multipla_escolha',
  `opcoes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opcoes`)),
  `resposta_correta` varchar(255) DEFAULT NULL,
  `pontuacao` int(11) NOT NULL DEFAULT 1,
  `explicacao` text DEFAULT NULL,
  `ordem` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_questoes_avaliacao_id_foreign` (`avaliacao_id`),
  CONSTRAINT `ebd_questoes_avaliacao_id_foreign` FOREIGN KEY (`avaliacao_id`) REFERENCES `ebd_avaliacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_questoes`
--

LOCK TABLES `ebd_questoes` WRITE;
/*!40000 ALTER TABLE `ebd_questoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_questoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_quiz_perguntas`
--

DROP TABLE IF EXISTS `ebd_quiz_perguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_quiz_perguntas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pergunta` varchar(255) NOT NULL,
  `opcao_a` text NOT NULL,
  `opcao_b` text NOT NULL,
  `opcao_c` text NOT NULL,
  `opcao_d` text NOT NULL,
  `resposta_correta` enum('a','b','c','d') NOT NULL,
  `explicacao` text DEFAULT NULL,
  `referencia_biblica` varchar(255) DEFAULT NULL,
  `nivel` enum('facil','medio','dificil') NOT NULL DEFAULT 'medio',
  `categoria` enum('geral','antigo_testamento','novo_testamento','personagens','milagres','parabolas','profetas','apostolos') NOT NULL DEFAULT 'geral',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `pontuacao` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_quiz_perguntas`
--

LOCK TABLES `ebd_quiz_perguntas` WRITE;
/*!40000 ALTER TABLE `ebd_quiz_perguntas` DISABLE KEYS */;
INSERT INTO `ebd_quiz_perguntas` VALUES (1,'Em quantos dias Deus criou o mundo?','5 dias','6 dias','7 dias','8 dias','b','Deus criou o mundo em 6 dias e descansou no sétimo dia (Gênesis 1:1-2:3).','Gênesis 1:1-2:3','facil','antigo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,'Qual foi a primeira coisa que Deus criou?','A luz','Os céus e a terra','O homem','Os animais','b','No princípio, criou Deus os céus e a terra (Gênesis 1:1).','Gênesis 1:1','facil','antigo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,'Qual era a profissão de Davi antes de ser rei?','Sacerdote','Pastor de ovelhas','Soldado','Ferreiro','b','Davi era pastor de ovelhas antes de ser ungido rei.','1 Samuel 16:11','facil','personagens',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,'Qual gigante Davi derrotou?','Golias','Sansão','Gideão','Josué','a','Davi derrotou o gigante Golias com uma pedra e uma funda.','1 Samuel 17:49','facil','personagens',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,'Quantos mandamentos Deus deu a Moisés?','5 mandamentos','10 mandamentos','12 mandamentos','15 mandamentos','b','Deus deu 10 mandamentos a Moisés no monte Sinai.','Êxodo 20:1-17','facil','antigo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,'Quantas pragas Deus enviou sobre o Egito?','7 pragas','10 pragas','12 pragas','15 pragas','b','Deus enviou 10 pragas sobre o Egito para libertar o povo de Israel.','Êxodo 7-12','facil','antigo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,'Onde Jesus nasceu?','Jerusalém','Belém','Nazaré','Galileia','b','Jesus nasceu em Belém, conforme profetizado nas Escrituras.','Mateus 2:1','facil','novo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,'Quantos discípulos Jesus tinha?','10 discípulos','12 discípulos','15 discípulos','20 discípulos','b','Jesus tinha 12 discípulos principais.','Lucas 6:12-16','facil','novo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,'Qual é o primeiro mandamento?','Não matarás','Não terás outros deuses diante de mim','Honra teu pai e tua mãe','Não roubarás','b','O primeiro mandamento é: \"Não terás outros deuses diante de mim\".','Êxodo 20:3','facil','antigo_testamento',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,'Por que os irmãos de José o venderam?','Porque ele era preguiçoso','Por inveja e ciúmes','Porque ele roubava','Porque ele mentia','b','Os irmãos de José o venderam por inveja e ciúmes, pois ele era o favorito do pai.','Gênesis 37:4','facil','personagens',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,'Qual era o nome original de Abraão?','Isaac','Abrão','Jacó','José','b','O nome original de Abraão era Abrão, que significa \"pai exaltado\".','Gênesis 17:5','medio','personagens',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,'Qual foi a primeira praga do Egito?','Sangue','Rãs','Piolhos','Morte dos primogênitos','a','A primeira praga foi transformar as águas do rio Nilo em sangue.','Êxodo 7:20','medio','antigo_testamento',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(13,'Qual mandamento fala sobre o sábado?','Terceiro mandamento','Quarto mandamento','Quinto mandamento','Sexto mandamento','b','O quarto mandamento fala sobre guardar o sábado como dia santo.','Êxodo 20:8','medio','antigo_testamento',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(14,'Qual foi o primeiro milagre de Jesus?','Multiplicar os pães','Transformar água em vinho','Curar um leproso','Ressuscitar Lázaro','b','O primeiro milagre de Jesus foi transformar água em vinho nas bodas de Caná.','João 2:1-11','medio','milagres',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(15,'Quem foi o primeiro mártir da igreja?','Pedro','Paulo','Estevão','João','c','Estevão foi o primeiro mártir da igreja cristã.','Atos 7:54-60','medio','apostolos',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(16,'Qual dessas parábolas ensina sobre o amor ao próximo?','Parábola do Joio','Parábola da rede','Parábola do bom samaritano','Parábola dos dois filhos','c','A parábola do bom samaritano foi ensinada quando um mestre da Lei perguntou a Jesus quem seria o seu próximo.','Lucas 10:25-37','medio','parabolas',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(17,'Quantos anos Moisés passou no deserto antes de libertar o povo?','20 anos','30 anos','40 anos','50 anos','c','Moisés passou 40 anos no deserto antes de libertar o povo de Israel.','Atos 7:30','medio','personagens',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(18,'Qual foi a prova mais difícil da fé de Abraão?','Deixar sua terra','Esperar pelo filho','Sacrificar Isaac','Viver em tendas','c','A prova mais difícil foi quando Deus pediu que Abraão sacrificasse seu filho Isaac.','Gênesis 22:1-19','medio','personagens',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(19,'O que José interpretou para o faraó?','Um sonho sobre o sol','Um sonho sobre vacas e espigas','Um sonho sobre estrelas','Um sonho sobre rios','b','José interpretou o sonho do faraó sobre 7 vacas gordas e 7 vacas magras, 7 espigas cheias e 7 espigas vazias.','Gênesis 41:1-36','medio','personagens',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(20,'Quantos foram batizados no dia de Pentecostes?','Cerca de 1.000 pessoas','Cerca de 2.000 pessoas','Cerca de 3.000 pessoas','Cerca de 5.000 pessoas','c','Cerca de 3.000 pessoas foram batizadas no dia de Pentecostes.','Atos 2:41','medio','novo_testamento',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(21,'Qual o nome e a idade da pessoa mais velha mencionada na Bíblia?','Enos, viveu 905 anos','Noé, viveu 990 anos','Matusalém, viveu 969 anos','Rainha Ester, viveu 859 anos','c','Matusalém (ou Metusalém) viveu 969 anos. Ele era filho de Enoque, que andou com Deus e foi o avô de Noé.','Gênesis 5:27','dificil','personagens',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(22,'Qual desses não teve o seu nome mudado na Bíblia?','Sara','Abraão','Jacó','Davi','d','Davi não teve seu nome mudado. Sara era Sarai (Gn.17:15), Abraão era Abrão (Gn. 17:5), Jacó tornou-se Israel (Gn. 32.28).','Gênesis 17:5,15; 32:28','dificil','personagens',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(23,'Qual dos nomes de Deus Moisés deveria dar aos israelitas, quando falasse de quem tinha lhe enviado?','\"Elohim\"','\"El Shadday\"','\"Eu sou o que sou\"','\"Eu sou o Senhor\"','c','\"Eu sou o que Sou\" foi a resposta dada por Deus a Moisés, quando perguntou sobre o Seu nome.','Êxodo 3:13-14','dificil','antigo_testamento',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(24,'Sobre Samuel, o que não é verdade?','Sua mãe se chamava Ana','Ungiu 3 reis de Israel: José, Saul e Davi','Sucedeu o profeta Eli','Teve uma visão enquanto ainda era muito novo','b','O profeta Samuel ungiu a Saul e Davi como reis de Israel. José não foi rei. Foi governador no Egito muitos anos antes.','1 Samuel 10:1; 16:13','dificil','personagens',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(25,'Que animal falou com Balaão?','jumenta','camelo','cordeiro','pomba','a','O Senhor fez a jumenta falar com Balaão quando este ia ao encontro de Balaque para amaldiçoar o povo de Deus em troca de riquezas.','Números 22:28','dificil','antigo_testamento',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(26,'Enquanto pastor de ovelhas, Davi protegeu seu rebanho de dois animais perigosos. Quais?','serpente e dromedário','urso e leão','cobra e lobo','urso e escorpião','b','Um leão e um urso foram os animais que Davi matou.','1 Samuel 17:34-37','dificil','personagens',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(27,'Quando bebê, como Moisés foi salvo do decreto infanticida do Faraó?','Foi levado às pressas para fora do Egito','Foi escondido dentro de uma caverna','Foi colocado num cesto e lançado no rio','Foi levado ao templo para servir a Deus','c','Moisés foi colocado num cestinho e deixado à beira rio. A filha do Faraó viu e adotou-o como seu filho.','Êxodo 2:3','dificil','personagens',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(28,'Quantos eram os discípulos mais próximos de Jesus?','10','7','5','12','d','Jesus escolheu 12 discípulos que costumavam acompanhá-lo durante todo o seu ministério.','Lucas 6:12-16','dificil','novo_testamento',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(29,'Complete o versículo: \"Porque Deus tanto amou o mundo...\"','que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna.','que enviou seu filho ao mundo, para que o mundo fosse salvo por ele.','ao ponto de sermos chamados filhos seus, e de fato somos.','que veio para o que era seu, mas o seus não o receberam.','a','\"Porque Deus tanto amou o mundo que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna.\"','João 3:16','dificil','novo_testamento',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(30,'Qual o nome da ilha onde João escreveu o livro de Apocalipse?','Ilha de Creta','Ilha de Malta','Ilha de Patmos','Ilha de Pérgamo','c','Na ilha de Patmos, João teve a visão do que acontecerá no final dos tempos.','Apocalipse 1:9','dificil','novo_testamento',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(31,'Quantos pães Jesus multiplicou para alimentar 5.000 pessoas?','3 pães','5 pães','7 pães','12 pães','b','Jesus multiplicou 5 pães e 2 peixes para alimentar 5.000 homens, além de mulheres e crianças.','João 6:9','medio','milagres',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(32,'Quantas pessoas Jesus ressuscitou durante seu ministério?','1 pessoa','2 pessoas','3 pessoas','4 pessoas','c','Jesus ressuscitou 3 pessoas: a filha de Jairo, o filho da viúva de Naim e Lázaro.','Marcos 5:41; Lucas 7:15; João 11:44','dificil','milagres',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(33,'Qual parábola fala sobre um homem que foi assaltado no caminho?','Parábola do semeador','Parábola do bom samaritano','Parábola do filho pródigo','Parábola das dez virgens','b','A parábola do bom samaritano conta a história de um homem que foi assaltado e deixado ferido no caminho.','Lucas 10:30-37','facil','parabolas',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(34,'Quantas virgens havia na parábola das dez virgens?','5 virgens','10 virgens','12 virgens','15 virgens','b','A parábola fala sobre 10 virgens, sendo 5 prudentes e 5 insensatas.','Mateus 25:1-13','medio','parabolas',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(35,'Qual profeta foi engolido por um grande peixe?','Elias','Jonas','Daniel','Jeremias','b','Jonas foi engolido por um grande peixe quando tentou fugir da missão que Deus lhe deu.','Jonas 1:17','facil','profetas',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(36,'Quantos anos Daniel e seus amigos ficaram na Babilônia?','50 anos','60 anos','70 anos','80 anos','c','Daniel e seus amigos ficaram 70 anos na Babilônia durante o cativeiro.','Daniel 1:21','dificil','profetas',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(37,'Qual apóstolo negou Jesus três vezes?','João','Pedro','Tiago','André','b','Pedro negou Jesus três vezes antes do galo cantar, conforme Jesus havia predito.','Mateus 26:69-75','facil','apostolos',1,10,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(38,'Quantas viagens missionárias Paulo fez?','2 viagens','3 viagens','4 viagens','5 viagens','b','Paulo fez 3 viagens missionárias registradas no livro de Atos.','Atos 13-21','dificil','apostolos',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(39,'Qual o nome do jardim plantado por Deus para o 1º casal criado?','Jardim do Getsêmani','Rosa de Sarom','Jardim do Éden','Paraíso','c','Jardim localizado no Éden. Esse foi o lugar preparado por Deus para Adão e Eva quando foram criados.','Gênesis 2:8','medio','geral',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(40,'Em quais livros da Bíblia é narrada a história do nascimento de Jesus?','Gênesis e Salmos','Mateus e Marcos','Mateus e Lucas','João e Atos','c','A história do nascimento de Jesus é narrada nos evangelhos de Mateus e Lucas.','Mateus 1-2; Lucas 1-2','medio','geral',1,20,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(41,'Quantos discípulos foram comissionados por Jesus?','3 discípulos - Pedro, Tiago e João','10 discípulos','40 discípulos','70 discípulos','d','70 foram enviados em missão para os lugares onde Jesus iria visitar.','Lucas 10:1','dificil','geral',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(42,'Que mãe pediu a Jesus que seus filhos estivessem à Sua direita e esquerda na glória?','A mãe de Tiago e José','A mulher siro-fenícia','A mãe dos filhos de Zebedeu, Tiago e João','A viúva de Naim','c','A mulher de Zebedeu, mãe de Tiago e João, fez esse pedido ao Senhor junto dos filhos.','Mateus 20:20','dificil','geral',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(43,'Num episódio com Jesus, quem é que estava preocupada e inquieta com muitas coisas? E quem escolheu a boa parte?','Maria Madalena e Joana mulher de Herodes','Maria de Betânia e Susana que ajudava com ofertas','Marta e Maria de Betânia','Maria de Betânia e Maria mãe de Jesus','c','Marta servia aos convidados enquanto Maria escolheu ouvir a Jesus. Ambas viviam em Betânia.','Lucas 10:38-41','dificil','geral',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(44,'Qual destes livros NÃO foi escrito pelo apóstolo Paulo?','Tito','Tiago','Romanos','Filemon','b','O livro de Tiago foi escrito por Tiago e não por Paulo. Tito, Romanos, Filemon e outros foram escritos por Paulo.','Tiago 1:1','dificil','geral',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(45,'Quem ressuscitou um jovem chamado Êutico?','Jesus','Pedro','Filipe','Paulo','d','Paulo consolou a igreja depois de ressuscitar Êutico. O jovem caira de uma janela do 3º andar.','Atos 20:9-12','dificil','geral',1,30,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ebd_quiz_perguntas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_quiz_respostas`
--

DROP TABLE IF EXISTS `ebd_quiz_respostas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_quiz_respostas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sessao_id` bigint(20) unsigned NOT NULL,
  `pergunta_id` bigint(20) unsigned NOT NULL,
  `resposta_dada` enum('a','b','c','d') NOT NULL,
  `correta` tinyint(1) NOT NULL,
  `pontuacao_obtida` int(11) NOT NULL,
  `tempo_resposta` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_quiz_respostas_sessao_id_foreign` (`sessao_id`),
  KEY `ebd_quiz_respostas_pergunta_id_foreign` (`pergunta_id`),
  CONSTRAINT `ebd_quiz_respostas_pergunta_id_foreign` FOREIGN KEY (`pergunta_id`) REFERENCES `ebd_quiz_perguntas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_quiz_respostas_sessao_id_foreign` FOREIGN KEY (`sessao_id`) REFERENCES `ebd_quiz_sessoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_quiz_respostas`
--

LOCK TABLES `ebd_quiz_respostas` WRITE;
/*!40000 ALTER TABLE `ebd_quiz_respostas` DISABLE KEYS */;
INSERT INTO `ebd_quiz_respostas` VALUES (1,1,1,'b',1,10,15,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(2,1,2,'b',1,10,19,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(3,1,3,'b',1,10,33,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(4,1,4,'a',1,10,60,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(5,1,5,'b',1,10,18,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(6,1,6,'b',1,10,9,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(7,1,7,'b',1,10,60,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(8,1,8,'b',1,10,45,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(9,1,9,'b',1,10,14,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(10,1,10,'a',0,0,41,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(11,2,1,'b',1,10,27,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(12,2,2,'b',1,10,11,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(13,2,3,'b',1,10,13,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(14,2,4,'a',1,10,48,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(15,2,5,'b',1,10,48,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(16,2,6,'b',1,10,7,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(17,2,7,'b',1,10,46,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(18,2,8,'b',1,10,22,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(19,2,9,'b',1,10,33,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(20,2,10,'d',0,0,6,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(21,3,12,'a',1,20,35,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(22,3,13,'b',1,20,50,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(23,4,1,'b',1,10,10,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(24,4,2,'b',1,10,5,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(25,4,3,'b',1,10,29,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(26,4,4,'a',1,10,6,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(27,4,5,'b',1,10,59,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(28,4,6,'b',1,10,6,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(29,4,7,'b',1,10,56,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(30,4,8,'b',1,10,36,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(31,4,9,'b',1,10,9,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(32,4,10,'d',0,0,24,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(33,5,12,'a',1,20,32,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(34,5,13,'b',1,20,17,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(35,6,21,'c',1,30,60,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(36,6,22,'d',1,30,7,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(37,6,24,'b',1,30,53,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(38,6,26,'b',1,30,47,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(39,6,27,'c',1,30,23,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(40,7,7,'b',1,10,20,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(41,7,8,'b',1,10,45,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(42,8,14,'b',1,20,34,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(43,8,31,'b',1,20,39,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(44,9,38,'b',1,30,38,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(45,10,33,'b',1,10,20,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(46,11,11,'b',1,20,32,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(47,11,12,'a',1,20,10,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(48,11,13,'b',1,20,41,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(49,11,14,'b',1,20,51,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(50,11,15,'c',1,20,13,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(51,11,16,'c',1,20,50,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(52,11,17,'c',1,20,31,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(53,11,18,'c',1,20,33,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(54,11,19,'b',1,20,28,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(55,11,20,'c',1,20,49,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(56,11,31,'b',1,20,36,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(57,11,34,'b',1,20,17,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(58,11,39,'c',1,20,60,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(59,11,40,'a',0,0,13,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(60,12,41,'d',1,30,59,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(61,12,42,'c',1,30,41,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(62,12,43,'c',1,30,32,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(63,12,44,'b',1,30,27,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(64,12,45,'d',1,30,46,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(65,13,1,'b',1,10,21,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(66,13,2,'b',1,10,26,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(67,13,5,'b',1,10,26,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(68,13,6,'b',1,10,23,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(69,13,9,'b',1,10,24,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(70,14,20,'c',1,20,34,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(71,15,21,'c',1,30,56,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(72,15,22,'d',1,30,11,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(73,15,24,'b',1,30,17,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(74,15,26,'b',1,30,33,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(75,15,27,'c',1,30,50,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(76,16,1,'b',1,10,47,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(77,16,2,'b',1,10,7,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(78,16,3,'b',1,10,26,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(79,16,4,'a',1,10,59,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(80,16,5,'b',1,10,24,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(81,16,6,'b',1,10,38,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(82,16,7,'b',1,10,51,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(83,16,8,'b',1,10,60,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(84,16,9,'b',1,10,40,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(85,16,10,'d',0,0,54,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(86,17,16,'c',1,20,12,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(87,17,34,'b',1,20,40,'2025-08-06 02:38:44','2025-08-06 02:38:44'),(88,18,38,'b',1,30,41,'2025-08-06 02:38:44','2025-08-06 02:38:44');
/*!40000 ALTER TABLE `ebd_quiz_respostas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_quiz_sessoes`
--

DROP TABLE IF EXISTS `ebd_quiz_sessoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_quiz_sessoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nivel` enum('facil','medio','dificil') NOT NULL,
  `categoria` enum('geral','antigo_testamento','novo_testamento','personagens','milagres','parabolas','profetas','apostolos') DEFAULT NULL,
  `total_perguntas` int(11) NOT NULL,
  `acertos` int(11) NOT NULL,
  `pontuacao_total` int(11) NOT NULL,
  `percentual` decimal(5,2) NOT NULL,
  `iniciado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finalizado_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_quiz_sessoes_user_id_foreign` (`user_id`),
  CONSTRAINT `ebd_quiz_sessoes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_quiz_sessoes`
--

LOCK TABLES `ebd_quiz_sessoes` WRITE;
/*!40000 ALTER TABLE `ebd_quiz_sessoes` DISABLE KEYS */;
INSERT INTO `ebd_quiz_sessoes` VALUES (1,1,'facil','geral',10,9,90,90.00,'2024-01-15 13:00:00','2024-01-15 13:15:00','2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,1,'facil','geral',10,9,90,90.00,'2024-01-15 13:00:00','2024-01-15 13:15:00','2025-08-06 02:37:45','2025-08-06 02:37:45'),(3,1,'medio','antigo_testamento',15,12,240,80.00,'2024-01-16 17:30:00','2024-01-16 18:00:00','2025-08-06 02:37:45','2025-08-06 02:37:45'),(4,1,'facil','geral',10,9,90,90.00,'2024-01-15 13:00:00','2024-01-15 13:15:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(5,1,'medio','antigo_testamento',15,12,240,80.00,'2024-01-16 17:30:00','2024-01-16 18:00:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(6,1,'dificil','personagens',20,14,420,70.00,'2024-01-17 19:00:00','2024-01-17 19:45:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(7,2,'facil','novo_testamento',10,8,80,80.00,'2024-01-18 12:00:00','2024-01-18 12:12:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(8,2,'medio','milagres',12,10,200,83.33,'2024-01-19 14:00:00','2024-01-19 14:25:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(9,2,'dificil','apostolos',18,11,330,61.11,'2024-01-20 18:30:00','2024-01-20 19:15:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(10,1,'facil','parabolas',8,7,70,87.50,'2023-12-10 13:00:00','2023-12-10 13:08:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(11,1,'medio','profetas',15,13,260,86.67,'2023-12-15 17:00:00','2023-12-15 17:30:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(12,2,'dificil','geral',25,16,480,64.00,'2023-12-20 19:00:00','2023-12-20 20:00:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(13,1,'facil','antigo_testamento',10,10,100,100.00,'2024-02-01 12:00:00','2024-02-01 12:10:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(14,1,'medio','novo_testamento',15,13,260,86.67,'2024-02-05 17:00:00','2024-02-05 17:25:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(15,2,'dificil','personagens',20,15,450,75.00,'2024-02-10 19:00:00','2024-02-10 19:50:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(16,2,'facil','milagres',10,9,90,90.00,'2024-02-15 14:00:00','2024-02-15 14:12:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(17,1,'medio','parabolas',12,11,220,91.67,'2024-02-20 18:00:00','2024-02-20 18:20:00','2025-08-06 02:38:40','2025-08-06 02:38:40'),(18,2,'dificil','apostolos',18,12,360,66.67,'2024-02-25 20:00:00','2024-02-25 20:45:00','2025-08-06 02:38:40','2025-08-06 02:38:40');
/*!40000 ALTER TABLE `ebd_quiz_sessoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_relatorios`
--

DROP TABLE IF EXISTS `ebd_relatorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_relatorios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `turma_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('presenca','notas','progresso','geral') NOT NULL DEFAULT 'geral',
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `dados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_relatorios_turma_id_foreign` (`turma_id`),
  CONSTRAINT `ebd_relatorios_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `ebd_turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_relatorios`
--

LOCK TABLES `ebd_relatorios` WRITE;
/*!40000 ALTER TABLE `ebd_relatorios` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_relatorios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_respostas_alunos`
--

DROP TABLE IF EXISTS `ebd_respostas_alunos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_respostas_alunos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `avaliacao_id` bigint(20) unsigned NOT NULL,
  `aluno_id` bigint(20) unsigned NOT NULL,
  `questao_id` bigint(20) unsigned NOT NULL,
  `resposta` text NOT NULL,
  `correta` tinyint(1) NOT NULL DEFAULT 0,
  `pontuacao_obtida` int(11) NOT NULL DEFAULT 0,
  `comentario_professor` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebd_respostas_alunos_avaliacao_id_foreign` (`avaliacao_id`),
  KEY `ebd_respostas_alunos_aluno_id_foreign` (`aluno_id`),
  KEY `ebd_respostas_alunos_questao_id_foreign` (`questao_id`),
  CONSTRAINT `ebd_respostas_alunos_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `ebd_alunos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_respostas_alunos_avaliacao_id_foreign` FOREIGN KEY (`avaliacao_id`) REFERENCES `ebd_avaliacoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ebd_respostas_alunos_questao_id_foreign` FOREIGN KEY (`questao_id`) REFERENCES `ebd_questoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_respostas_alunos`
--

LOCK TABLES `ebd_respostas_alunos` WRITE;
/*!40000 ALTER TABLE `ebd_respostas_alunos` DISABLE KEYS */;
/*!40000 ALTER TABLE `ebd_respostas_alunos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ebd_turmas`
--

DROP TABLE IF EXISTS `ebd_turmas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ebd_turmas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `faixa_etaria` varchar(255) DEFAULT NULL,
  `cor` varchar(255) NOT NULL DEFAULT '#3b82f6',
  `capacidade_maxima` int(11) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ebd_turmas`
--

LOCK TABLES `ebd_turmas` WRITE;
/*!40000 ALTER TABLE `ebd_turmas` DISABLE KEYS */;
INSERT INTO `ebd_turmas` VALUES (1,'Berçário','Turma para crianças de 0 a 2 anos','0-2 anos','#FF6B6B',15,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(2,'Maternal','Turma para crianças de 3 a 4 anos','3-4 anos','#4ECDC4',20,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(3,'Jardim','Turma para crianças de 5 a 6 anos','5-6 anos','#45B7D1',25,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(4,'Primários','Turma para crianças de 7 a 9 anos','7-9 anos','#96CEB4',30,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(5,'Juniores','Turma para crianças de 10 a 12 anos','10-12 anos','#FFEAA7',25,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(6,'Adolescentes','Turma para adolescentes de 13 a 17 anos','13-17 anos','#DDA0DD',30,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(7,'Jovens','Turma para jovens de 18 a 25 anos','18-25 anos','#98D8C8',35,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(8,'Adultos','Turma para adultos de 26 a 59 anos','26-59 anos','#3b82f6',40,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(9,'Senhores','Turma para senhores de 60 anos ou mais','60+ anos','#F7DC6F',30,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,'Novos Convertidos','Turma especial para novos convertidos','Todas as idades','#BB8FCE',20,1,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ebd_turmas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento_inscricoes`
--

DROP TABLE IF EXISTS `evento_inscricoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento_inscricoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('pendente','confirmada','cancelada','presente','ausente') NOT NULL DEFAULT 'pendente',
  `forma_pagamento` enum('pix','stripe','mercadopago','dinheiro','transferencia','outro') DEFAULT NULL,
  `valor_pago` decimal(10,2) DEFAULT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `comprovante_pagamento` varchar(255) DEFAULT NULL,
  `presenca_confirmada` tinyint(1) NOT NULL DEFAULT 0,
  `data_presenca` timestamp NULL DEFAULT NULL,
  `certificado_emitido` tinyint(1) NOT NULL DEFAULT 0,
  `data_certificado` timestamp NULL DEFAULT NULL,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_evento_email` (`evento_id`,`email`),
  UNIQUE KEY `unique_evento_user` (`evento_id`,`user_id`),
  KEY `evento_inscricoes_evento_id_status_index` (`evento_id`,`status`),
  KEY `evento_inscricoes_user_id_status_index` (`user_id`,`status`),
  KEY `evento_inscricoes_email_index` (`email`),
  KEY `evento_inscricoes_status_index` (`status`),
  CONSTRAINT `evento_inscricoes_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evento_inscricoes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento_inscricoes`
--

LOCK TABLES `evento_inscricoes` WRITE;
/*!40000 ALTER TABLE `evento_inscricoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento_inscricoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento_pagamentos`
--

DROP TABLE IF EXISTS `evento_pagamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento_pagamentos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) unsigned NOT NULL,
  `inscricao_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `forma_pagamento` enum('pix','stripe','mercadopago','dinheiro','transferencia','outro') NOT NULL,
  `status` enum('pendente','processando','aprovado','rejeitado','cancelado') NOT NULL DEFAULT 'pendente',
  `gateway_id` varchar(255) DEFAULT NULL,
  `gateway_transaction_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `data_confirmacao` timestamp NULL DEFAULT NULL,
  `comprovante_url` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evento_pagamentos_evento_id_status_index` (`evento_id`,`status`),
  KEY `evento_pagamentos_inscricao_id_status_index` (`inscricao_id`,`status`),
  KEY `evento_pagamentos_user_id_status_index` (`user_id`,`status`),
  KEY `evento_pagamentos_status_index` (`status`),
  KEY `evento_pagamentos_gateway_transaction_id_index` (`gateway_transaction_id`),
  CONSTRAINT `evento_pagamentos_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evento_pagamentos_inscricao_id_foreign` FOREIGN KEY (`inscricao_id`) REFERENCES `evento_inscricoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evento_pagamentos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento_pagamentos`
--

LOCK TABLES `evento_pagamentos` WRITE;
/*!40000 ALTER TABLE `evento_pagamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento_pagamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `descricao_curta` varchar(255) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `local` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `coordenadas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`coordenadas`)),
  `tipo_publico` enum('membros','publico','ambos') NOT NULL DEFAULT 'ambos',
  `tipo_evento` enum('culto','estudo','reuniao','conferencia','outro') NOT NULL DEFAULT 'outro',
  `status` enum('rascunho','ativo','cancelado','finalizado') NOT NULL DEFAULT 'rascunho',
  `gratuito` tinyint(1) NOT NULL DEFAULT 1,
  `valor_inscricao` decimal(10,2) DEFAULT NULL,
  `vagas_disponiveis` int(11) DEFAULT NULL,
  `vagas_totais` int(11) DEFAULT NULL,
  `inscricao_obrigatoria` tinyint(1) NOT NULL DEFAULT 0,
  `inscricao_ate` timestamp NULL DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `galeria_fotos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`galeria_fotos`)),
  `regulamento` text DEFAULT NULL,
  `informacoes_adicionais` text DEFAULT NULL,
  `organizador_id` bigint(20) unsigned DEFAULT NULL,
  `ministerio_id` bigint(20) unsigned DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_por` bigint(20) unsigned DEFAULT NULL,
  `atualizado_por` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eventos_organizador_id_foreign` (`organizador_id`),
  KEY `eventos_ministerio_id_foreign` (`ministerio_id`),
  KEY `eventos_criado_por_foreign` (`criado_por`),
  KEY `eventos_atualizado_por_foreign` (`atualizado_por`),
  KEY `eventos_ativo_status_index` (`ativo`,`status`),
  KEY `eventos_data_inicio_data_fim_index` (`data_inicio`,`data_fim`),
  KEY `eventos_tipo_publico_ativo_index` (`tipo_publico`,`ativo`),
  KEY `eventos_destaque_ativo_index` (`destaque`,`ativo`),
  CONSTRAINT `eventos_atualizado_por_foreign` FOREIGN KEY (`atualizado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `eventos_criado_por_foreign` FOREIGN KEY (`criado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `eventos_ministerio_id_foreign` FOREIGN KEY (`ministerio_id`) REFERENCES `ministerios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `eventos_organizador_id_foreign` FOREIGN KEY (`organizador_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (1,'Conferência de Jovens 2024','Uma conferência especial para jovens com palestras, workshops e momentos de adoração. Venha participar de um evento que vai transformar sua vida!','Conferência especial para jovens com palestras e workshops','2025-09-05','2025-09-05','19:00:00','22:00:00','Auditório Principal da Igreja','Rua das Flores, 123 - Centro',NULL,NULL,NULL,NULL,'ambos','conferencia','ativo',0,25.00,NULL,100,1,'2025-08-31 15:01:35',NULL,NULL,'1. Chegar com 30 minutos de antecedência\n2. Trazer documento de identificação\n3. Respeitar os horários estabelecidos\n4. Participar de todas as atividades\n5. Manter o ambiente limpo e organizado','O evento inclui:\n- Coffee break\n- Material didático\n- Certificado de participação\n- Networking entre jovens\n- Momento de oração e adoração',1,1,NULL,1,1,1,1,'2025-08-06 15:01:35','2025-08-06 15:47:25'),(2,'Culto de Celebração','Um culto especial de celebração e agradecimento. Venha louvar e adorar a Deus em comunidade!','Culto especial de celebração e agradecimento','2025-08-13','2025-08-13','18:30:00','20:30:00','Templo Principal','Rua das Flores, 123 - Centro',NULL,NULL,NULL,NULL,'ambos','culto','rascunho',1,0.00,NULL,200,0,'2025-08-12 15:01:35',NULL,NULL,'1. Chegar com antecedência\n2. Manter silêncio durante o culto\n3. Participar com reverência\n4. Respeitar o próximo','Traga sua família e amigos para participar deste momento especial de adoração!',1,1,NULL,0,1,1,1,'2025-08-06 15:01:35','2025-08-06 15:01:35'),(3,'Reunião de Líderes','Reunião mensal dos líderes de ministérios para alinhamento e planejamento das atividades.','Reunião mensal dos líderes de ministérios','2025-08-20','2025-08-20','20:00:00','21:30:00','Sala de Reuniões','Rua das Flores, 123 - Centro',NULL,NULL,NULL,NULL,'membros','reuniao','rascunho',1,0.00,NULL,50,1,'2025-08-18 15:01:35',NULL,NULL,'1. Apenas líderes de ministérios\n2. Chegar pontualmente\n3. Trazer material de anotações\n4. Participar ativamente','Traga suas ideias e sugestões para o planejamento dos próximos meses!',1,1,NULL,0,1,1,1,'2025-08-06 15:01:35','2025-08-06 15:01:35');
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `igrejas`
--

DROP TABLE IF EXISTS `igrejas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `igrejas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pastor_responsavel` varchar(255) DEFAULT NULL,
  `data_fundacao` date DEFAULT NULL,
  `tipo_entidade` varchar(255) NOT NULL DEFAULT 'IGREJA',
  `situacao_cadastral` varchar(255) NOT NULL DEFAULT 'ATIVA',
  `inscricao_estadual` varchar(255) DEFAULT NULL,
  `inscricao_municipal` varchar(255) DEFAULT NULL,
  `certificado_digital` varchar(64) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `igrejas_cnpj_unique` (`cnpj`),
  KEY `igrejas_cnpj_index` (`cnpj`),
  KEY `igrejas_tipo_entidade_index` (`tipo_entidade`),
  KEY `igrejas_situacao_cadastral_index` (`situacao_cadastral`),
  KEY `igrejas_estado_index` (`estado`),
  KEY `igrejas_cidade_index` (`cidade`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `igrejas`
--

LOCK TABLES `igrejas` WRITE;
/*!40000 ALTER TABLE `igrejas` DISABLE KEYS */;
INSERT INTO `igrejas` VALUES (1,'Congregação Batista Avenida','12.345.678/0001-90','Rua da Avenida, 123','São Paulo','SP','01234-567','(11) 99999-9999','contato@cbav.com','Pr. João Silva','1990-01-15','IGREJA','ATIVA','123456789','987654321','44445f9d285bd1e708a016689ac5189a7d878b165b036eb01673e15ac0cbe0f3','Igreja demonstrativa para o sistema CBAV','2025-08-04 22:31:09','2025-08-06 02:35:19'),(2,'Igreja Presbiteriana do Brasil','98765432000102','Av. Principal, 456','Rio de Janeiro','RJ','20000-000','(21) 88888-8888','contato@igrejapresbiteriana.com','Pr. Pedro Santos','1985-03-20','IGREJA','ATIVA',NULL,NULL,'e71dfc1d63e8e5065e13fd320f8c325a7c0651bb464f0c3ae61228dcb370359d',NULL,'2025-08-04 22:31:09','2025-08-04 22:31:09'),(3,'Igreja Metodista Wesleyana','45678912000103','Rua da Paz, 789','Belo Horizonte','MG','30000-000','(31) 77777-7777','contato@igrejametodista.com','Pr. Carlos Oliveira','1995-07-10','IGREJA','ATIVA',NULL,NULL,'804c3ebbd02728d143d2c7b0869b64fd14cce389cd10051cf7caadc8bf97aaab',NULL,'2025-08-04 22:31:09','2025-08-04 22:31:09'),(4,'Igreja Presbiteriana do Brasil','98.765.432/0001-02','Av. Principal, 456','Rio de Janeiro','RJ','20000-000','(21) 88888-8888','contato@igrejapresbiteriana.com','Pr. Pedro Santos',NULL,'IGREJA','ATIVA',NULL,NULL,'e4820dc8e8af0f949bd7756c38872bb955d2bdc170eb06c5136b09c60a9a1222',NULL,'2025-08-04 23:35:10','2025-08-04 23:35:10');
/*!40000 ALTER TABLE `igrejas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intercessoes`
--

DROP TABLE IF EXISTS `intercessoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intercessoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `tipo_oracao` enum('individual','grupo','igreja') NOT NULL DEFAULT 'individual',
  `tempo_oracao` int(11) NOT NULL DEFAULT 0 COMMENT 'Tempo em minutos',
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intercessoes`
--

LOCK TABLES `intercessoes` WRITE;
/*!40000 ALTER TABLE `intercessoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `intercessoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intercessor_oracaos`
--

DROP TABLE IF EXISTS `intercessor_oracaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intercessor_oracaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `data_oracao` datetime NOT NULL,
  `observacoes` text DEFAULT NULL,
  `tempo_oracao` int(11) DEFAULT NULL,
  `tipo_oracao` enum('individual','grupo','igreja') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `intercessor_oracaos_pedido_id_foreign` (`pedido_id`),
  KEY `intercessor_oracaos_user_id_foreign` (`user_id`),
  CONSTRAINT `intercessor_oracaos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedido_oracaos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercessor_oracaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intercessor_oracaos`
--

LOCK TABLES `intercessor_oracaos` WRITE;
/*!40000 ALTER TABLE `intercessor_oracaos` DISABLE KEYS */;
INSERT INTO `intercessor_oracaos` VALUES (1,1,11,'2025-08-02 00:14:59','Orei por este pedido com fé e confiança.',11,'igreja','2025-08-05 03:14:59','2025-08-05 03:14:59'),(2,3,12,'2025-08-01 00:14:59','Orei por este pedido com fé e confiança.',26,'grupo','2025-08-05 03:14:59','2025-08-05 03:14:59'),(3,5,11,'2025-07-30 00:14:59','Orei por este pedido com fé e confiança.',21,'grupo','2025-08-05 03:14:59','2025-08-05 03:14:59'),(4,6,12,'2025-08-03 00:14:59','Orei por este pedido com fé e confiança.',19,'grupo','2025-08-05 03:14:59','2025-08-05 03:14:59'),(5,8,11,'2025-07-31 00:14:59','Orei por este pedido com fé e confiança.',5,'igreja','2025-08-05 03:14:59','2025-08-05 03:14:59'),(6,10,12,'2025-07-30 00:14:59','Orei por este pedido com fé e confiança.',25,'individual','2025-08-05 03:14:59','2025-08-05 03:14:59'),(7,12,11,'2025-08-03 00:14:59','Orei por este pedido com fé e confiança.',8,'grupo','2025-08-05 03:14:59','2025-08-05 03:14:59'),(8,14,12,'2025-07-30 00:14:59','Orei por este pedido com fé e confiança.',15,'igreja','2025-08-05 03:14:59','2025-08-05 03:14:59'),(9,1,1,'2025-08-04 23:39:43','Orei pela ação social. Que Deus nos ajude a ajudar!',40,'grupo','2025-08-06 02:39:43','2025-08-06 02:39:43'),(10,1,1,'2025-08-05 11:39:43','Orei pela cura da pressão alta. Deus tem poder para curar!',45,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(11,1,1,'2025-08-05 17:39:43','Orei pela conversão. Que Deus toque o coração dela!',60,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(12,1,1,'2025-08-05 15:39:43','Orei pela proteção da gravidez. Que Deus guarde mãe e bebê!',35,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(13,1,1,'2025-08-05 19:39:43','Orei pela saúde mental. Que Deus dê paz e conforto!',50,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(14,1,1,'2025-08-05 21:39:43','Orei pela cura dos pais. Deus tem poder para curar!',25,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(15,1,1,'2025-08-03 23:39:43','Orei pelo evangelismo. Que Deus abra portas!',45,'igreja','2025-08-06 02:39:43','2025-08-06 02:39:43'),(16,1,1,'2025-08-02 23:39:43','Orei pelos estudos. Que Deus dê força e sabedoria!',30,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43'),(17,1,1,'2025-08-01 23:39:43','Orei pela família e pelo filho na escola. Que Deus ajude!',35,'individual','2025-08-06 02:39:43','2025-08-06 02:39:43');
/*!40000 ALTER TABLE `intercessor_oracaos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"393928fa-695d-4d8f-8486-f9a810413e60\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447721,\"delay\":null}',0,NULL,1754447721,1754447721),(2,'default','{\"uuid\":\"d668be93-41c5-42ec-ba4f-4a418a39bc5e\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447721,\"delay\":null}',0,NULL,1754447721,1754447721),(3,'default','{\"uuid\":\"95354507-f032-45a7-acd8-6559ee689ba2\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447865,\"delay\":null}',0,NULL,1754447865,1754447865),(4,'default','{\"uuid\":\"6a9bb576-63ce-4bca-a73b-74e2716d97bf\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447865,\"delay\":null}',0,NULL,1754447865,1754447865),(5,'default','{\"uuid\":\"b452cae8-db44-44ae-b1be-cc06ffc52d40\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:240;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447865,\"delay\":null}',0,NULL,1754447865,1754447865),(6,'default','{\"uuid\":\"a039dfb0-0887-4a4b-bff9-b153b778bf7d\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:80;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447865,\"delay\":null}',0,NULL,1754447865,1754447865),(7,'default','{\"uuid\":\"03af0f25-e5f5-454d-8c57-d1b8fe549525\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(8,'default','{\"uuid\":\"ffc1a150-b0aa-4bf9-96aa-0b136f7d3de6\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:90;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(9,'default','{\"uuid\":\"5e5c9450-168e-477b-85cc-0cae6445213d\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:240;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(10,'default','{\"uuid\":\"f9a8e451-99ae-4bf8-b00b-28359c4e595c\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:80;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(11,'default','{\"uuid\":\"2f8ed724-4861-4eaa-be5d-fa63af43577f\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:420;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(12,'default','{\"uuid\":\"7c455f37-19cb-42fb-a500-b512e1257cfd\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:70;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(13,'default','{\"uuid\":\"964cd850-0f20-488c-9876-3067c752d186\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:420;s:15:\\\"isRecordeGlobal\\\";b:0;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(14,'default','{\"uuid\":\"c3497ffc-786e-49f7-b97b-c73a878cfee4\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:80;s:15:\\\"isRecordeGlobal\\\";b:0;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(15,'default','{\"uuid\":\"6fde7011-4583-49c4-9460-1d46f48fd7b3\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:200;s:15:\\\"isRecordeGlobal\\\";b:0;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(16,'default','{\"uuid\":\"f9e04ba7-ce17-4368-9066-67ff462e8830\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:330;s:15:\\\"isRecordeGlobal\\\";b:0;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(17,'default','{\"uuid\":\"66a06f17-42c6-48a9-9f1b-fef0a1b78b05\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:87.5;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(18,'default','{\"uuid\":\"7a3d8908-257f-495e-92e8-888c820b4373\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:86.67;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(19,'default','{\"uuid\":\"6f900b98-7901-4fb2-8bea-66275c0cbe84\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:480;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(20,'default','{\"uuid\":\"bc532e47-5f07-460c-a14b-f67490e2da50\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:480;s:15:\\\"isRecordeGlobal\\\";b:0;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(21,'default','{\"uuid\":\"778695ac-fefb-4699-a310-2d481f5d32a0\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:100;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(22,'default','{\"uuid\":\"05d1f89c-3f6a-4cab-a373-a596179d974d\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:86.67;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(23,'default','{\"uuid\":\"92bb1d13-66cd-4920-b252-6696674b1fd8\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:9:\\\"pontuacao\\\";s:5:\\\"valor\\\";d:450;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920),(24,'default','{\"uuid\":\"d2269d17-eb34-417b-81f4-f3cb58aa54e7\",\"displayName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\",\"command\":\"O:37:\\\"App\\\\Jobs\\\\EnviarNotificacaoQuizRecorde\\\":4:{s:6:\\\"sessao\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:24:\\\"App\\\\Models\\\\EbdQuizSessao\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"tipoRecorde\\\";s:10:\\\"percentual\\\";s:5:\\\"valor\\\";d:91.67;s:15:\\\"isRecordeGlobal\\\";b:1;}\"},\"createdAt\":1754447920,\"delay\":null}',0,NULL,1754447920,1754447920);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membro_cargo`
--

DROP TABLE IF EXISTS `membro_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membro_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned NOT NULL,
  `cargo_id` bigint(20) unsigned NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membro_cargo_membro_id_ativo_index` (`membro_id`,`ativo`),
  KEY `membro_cargo_cargo_id_ativo_index` (`cargo_id`,`ativo`),
  CONSTRAINT `membro_cargo_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membro_cargo_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membro_cargo`
--

LOCK TABLES `membro_cargo` WRITE;
/*!40000 ALTER TABLE `membro_cargo` DISABLE KEYS */;
INSERT INTO `membro_cargo` VALUES (1,1,30,'2025-08-02',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(2,1,39,'2024-07-05',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,1,41,'2024-09-04',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,2,25,'2024-05-06',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,2,31,'2025-03-03',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,3,27,'2025-02-10',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(7,3,29,'2025-01-08',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,3,33,'2024-06-04',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,4,27,'2024-09-03',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(10,4,36,'2024-05-12',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(11,4,39,'2023-10-17',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(12,5,21,'2025-02-27',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(13,6,28,'2024-04-11',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(14,7,34,'2023-12-22',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(15,8,26,'2025-06-24',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(16,8,33,'2024-07-21',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(17,9,22,'2025-03-09',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(18,9,24,'2024-09-07',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(19,9,25,'2023-08-22',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(20,10,41,'2023-09-08',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(21,22,42,'2024-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(22,12,44,'2024-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(23,33,46,'2024-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(24,19,46,'2024-03-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(25,28,46,'2023-08-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(26,31,48,'2025-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(27,37,21,'2024-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(28,27,49,'2025-01-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(29,29,25,'2024-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(30,36,56,'2023-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(31,32,23,'2024-03-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(32,24,53,'2024-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(33,25,54,'2024-06-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(34,24,55,'2025-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(35,25,55,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(36,1,51,'2024-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(37,2,51,'2024-02-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(38,3,51,'2024-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(39,4,51,'2024-01-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(40,5,51,'2023-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(41,6,51,'2023-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(42,7,51,'2025-03-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(43,8,51,'2024-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(44,9,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(45,10,51,'2024-06-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(46,11,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(47,12,51,'2025-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(48,13,51,'2024-09-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(49,14,51,'2024-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(50,16,51,'2025-03-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(51,17,51,'2025-01-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(52,18,51,'2024-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(53,19,51,'2023-08-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(54,20,51,'2025-06-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(55,21,51,'2023-11-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(56,22,51,'2025-03-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(57,24,51,'2024-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(58,25,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(59,26,51,'2023-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(60,27,51,'2024-06-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(61,28,51,'2023-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(62,29,51,'2024-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(63,30,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(64,31,51,'2024-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(65,32,51,'2023-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(66,33,51,'2024-08-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(67,36,51,'2024-11-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(68,37,51,'2024-07-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(69,41,51,'2023-09-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(70,42,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(71,43,51,'2024-08-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(72,44,51,'2024-02-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(73,45,51,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(74,46,51,'2024-12-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(75,47,51,'2024-07-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(76,15,50,'2024-11-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(77,23,50,'2024-10-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(78,34,50,'2024-08-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(79,35,50,'2025-04-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(80,38,50,'2025-05-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(81,39,51,'2025-02-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14'),(82,40,51,'2025-07-05',NULL,1,'2025-08-06 02:39:14','2025-08-06 02:39:14');
/*!40000 ALTER TABLE `membro_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membros`
--

DROP TABLE IF EXISTS `membros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membros` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `cep` varchar(255) DEFAULT NULL,
  `estado_civil` enum('solteiro','casado','divorciado','viuvo') DEFAULT NULL,
  `profissao` varchar(255) DEFAULT NULL,
  `escolaridade` varchar(255) DEFAULT NULL,
  `data_batismo` date DEFAULT NULL,
  `data_membro` date DEFAULT NULL,
  `data_ingresso` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `receber_notificacoes` tinyint(1) NOT NULL DEFAULT 1,
  `receber_newsletter` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membros_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membros`
--

LOCK TABLES `membros` WRITE;
/*!40000 ALTER TABLE `membros` DISABLE KEYS */;
INSERT INTO `membros` VALUES (1,'João Silva Santos','joao.silva@email.com','(11) 99999-1111','1985-03-15','M','Rua das Flores, 123','Centro','São Paulo','SP','01234-567','casado',NULL,NULL,'2010-06-20',NULL,'2008-09-15','Membro ativo, participa do ministério de louvor',NULL,1,1,1,'2025-08-04 21:49:17','2025-08-06 02:38:53'),(2,'Maria Santos Costa','maria.costa@email.com','(11) 99999-8888','1990-07-22',NULL,'Av. Paulista, 456 - São Paulo, SP',NULL,NULL,NULL,NULL,'solteiro',NULL,NULL,'2012-09-15',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,'Pedro Costa Oliveira','intercessor@teste.com','(11) 99999-9999','1988-11-10',NULL,'Rua Augusta, 789 - São Paulo, SP',NULL,NULL,NULL,NULL,'casado',NULL,NULL,'2011-03-08',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-05 04:17:09'),(4,'Ana Oliveira Lima','ana.lima@email.com','(11) 99999-0000','1992-04-05',NULL,'Rua Consolação, 321 - São Paulo, SP',NULL,NULL,NULL,NULL,'solteiro',NULL,NULL,'2013-12-01',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,'Carlos Lima Ferreira','carlos.ferreira@email.com','(11) 99999-1111','1987-09-18',NULL,'Av. Brigadeiro Faria Lima, 654 - São Paulo, SP',NULL,NULL,NULL,NULL,'casado',NULL,NULL,'2009-05-12',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,'Lucia Ferreira Silva','lucia.silva@email.com','(11) 99999-2222','1995-01-30',NULL,'Rua Oscar Freire, 987 - São Paulo, SP',NULL,NULL,NULL,NULL,'solteiro',NULL,NULL,'2014-08-25',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(7,'Roberto Almeida Santos','roberto.santos@email.com','(11) 99999-3333','1983-12-08',NULL,'Rua Pamplona, 147 - São Paulo, SP',NULL,NULL,NULL,NULL,'casado',NULL,NULL,'2008-11-03',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,'Fernanda Costa Almeida','fernanda.almeida@email.com','(11) 99999-4444','1991-06-14',NULL,'Av. Jabaquara, 258 - São Paulo, SP',NULL,NULL,NULL,NULL,'solteiro',NULL,NULL,'2012-04-20',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,'Marcos Oliveira Costa','marcos.costa@email.com','(11) 99999-5555','1986-02-25',NULL,'Rua Vergueiro, 369 - São Paulo, SP',NULL,NULL,NULL,NULL,'casado',NULL,NULL,'2010-09-10',NULL,NULL,NULL,NULL,1,1,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(10,'Patricia Santos Almeida','patricia.santos@email.com','(11) 99999-0000','1989-04-22','F','Rua Oscar Freire, 567','Jardins','São Paulo','SP','01234-012','solteiro',NULL,NULL,'2013-10-12',NULL,'2011-07-05','Líder do ministério de hospitalidade',NULL,1,1,1,'2025-08-04 21:49:17','2025-08-06 02:38:53'),(11,'João Silva','membro@teste.com','(11) 99999-9999','1985-05-15',NULL,'Rua das Flores, 123',NULL,'São Paulo','SP','01234-567',NULL,NULL,NULL,'2010-06-20',NULL,NULL,NULL,NULL,1,1,1,'2025-08-05 03:14:29','2025-08-05 03:14:29'),(12,'Maria Santos','maria@teste.com','(11) 88888-8888','1990-03-10',NULL,'Av. Paulista, 456',NULL,'São Paulo','SP','01310-000',NULL,NULL,NULL,'2015-08-15',NULL,NULL,NULL,NULL,1,1,1,'2025-08-05 03:14:58','2025-08-05 03:14:58'),(13,'Administrador Sistema','admin@cbav.com','(11) 99999-0000','1980-01-01','M','Rua do Sistema, 1','Centro','São Paulo','SP',NULL,'casado',NULL,NULL,'1990-01-01',NULL,'1985-01-01','Usuário administrador do sistema','users/fotos/foto_1_1754448917_6892c41511425.png',1,1,1,'2025-08-05 04:46:38','2025-08-06 02:55:17'),(14,'Pastor João Silva','pastor@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(15,'Maria Santos','maria@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(16,'Pedro Costa','pedro@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(17,'Ana Oliveira','ana@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(18,'Carlos Lima','carlos@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(19,'Lucia Ferreira','lucia@igreja.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(20,'Reinan Rodrigues','admin@teste.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(21,'Ana Intercessora','ana.intercessor@teste.com','','2000-08-05',NULL,'',NULL,'','','',NULL,NULL,NULL,NULL,NULL,'2025-08-05','Perfil criado automaticamente pelo comando.',NULL,1,1,1,'2025-08-05 04:49:38','2025-08-05 04:49:38'),(22,'Pr. João Silva','joao.silva@cbav.com','(11) 99999-0001','1980-05-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(23,'Maria Santos','maria.santos@cbav.com','(11) 99999-0002','1985-08-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(24,'Carlos Eduardo Almeida','carlos.almeida@cbav.com','(11) 99999-0003','1975-12-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(25,'Lucia Helena Rodrigues','lucia.rodrigues@cbav.com','(11) 99999-0004','1982-03-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(26,'Pedro Oliveira','pedro.oliveira@cbav.com','(11) 99999-0005','1978-07-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(27,'Ana Paula Ferreira','ana.ferreira@cbav.com','(11) 99999-0006','1990-01-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(28,'Roberto Lima','roberto.lima@cbav.com','(11) 99999-0007','1988-11-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(29,'Fernanda Lima Santos','fernanda.santos@cbav.com','(11) 99999-0008','1987-04-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(30,'Patricia Costa Silva','patricia.silva@cbav.com','(11) 99999-0009','1983-09-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(31,'Roberto Silva Costa','roberto.costa@cbav.com','(11) 99999-0010','1970-06-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(32,'Ricardo Almeida Oliveira','ricardo.oliveira@cbav.com','(11) 99999-0011','1985-02-28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(33,'Ana Costa','ana.costa@cbav.com','(11) 99999-0012','1992-10-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(34,'Pedro Costa','pedro.costa@cbav.com','(11) 99999-0013','1965-12-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(35,'Lucia Ferreira','lucia.ferreira@cbav.com','(11) 99999-0014','1972-08-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(36,'Maria Santos Oliveira','maria.santos@email.com','(11) 99999-2222','1990-07-22','F','Avenida Principal, 456','Vila Nova','São Paulo','SP','01234-789','casado',NULL,NULL,'2012-12-10',NULL,'2010-03-25','Líder do ministério infantil',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(37,'Pedro Costa Lima','pedro.costa@email.com','(11) 99999-3333','1988-11-08','M','Rua do Comércio, 789','Centro','São Paulo','SP','01234-012','solteiro',NULL,NULL,'2015-04-15',NULL,'2013-08-30','Músico do ministério de louvor',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(38,'Ana Paula Ferreira','ana.ferreira@email.com','(11) 99999-4444','1992-05-12','F','Rua das Palmeiras, 321','Jardim São Paulo','São Paulo','SP','01234-345','casado',NULL,NULL,'2018-09-20',NULL,'2016-12-05','Cantora do ministério de louvor',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(39,'Carlos Eduardo Almeida','carlos.almeida@email.com','(11) 99999-5555','1983-12-03','M','Avenida das Indústrias, 654','Vila Industrial','São Paulo','SP','01234-678','casado',NULL,NULL,'2005-11-08',NULL,'2003-06-15','Diácono da igreja',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(40,'Lucia Helena Rodrigues','lucia.rodrigues@email.com','(11) 99999-6666','1975-08-25','F','Rua dos Ipês, 987','Jardim das Flores','São Paulo','SP','01234-901','casado',NULL,NULL,'2000-03-12',NULL,'1998-09-20','Presbítera da igreja',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(41,'Roberto Alves Silva','roberto.alves@email.com','(11) 99999-7777','1980-01-30','M','Rua das Acácias, 456','Vila Madalena','São Paulo','SP','01234-234','casado',NULL,NULL,'2008-07-18',NULL,'2006-11-10','Líder do ministério de evangelismo',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(42,'Fernanda Costa Santos','fernanda.costa@email.com','(11) 99999-8888','1987-09-14','F','Avenida Paulista, 1234','Bela Vista','São Paulo','SP','01234-456','casado',NULL,NULL,'2011-05-25',NULL,'2009-02-15','Líder do ministério de ação social',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(43,'Ricardo Oliveira Lima','ricardo.oliveira@email.com','(11) 99999-9999','1982-06-08','M','Rua Augusta, 789','Consolação','São Paulo','SP','01234-789','casado',NULL,NULL,'2007-12-03',NULL,'2005-08-20','Líder do ministério de ensino',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(44,'Marcelo Ferreira Costa','marcelo.ferreira@email.com','(11) 99999-1111','1984-11-18','M','Rua Haddock Lobo, 890','Cerqueira César','São Paulo','SP','01234-345','casado',NULL,NULL,'2009-03-30',NULL,'2007-01-15','Líder do ministério de tecnologia',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(45,'Camila Rodrigues Silva','camila.rodrigues@email.com','(11) 99999-2222','1991-12-05','F','Rua Pamplona, 123','Jardins','São Paulo','SP','01234-678','casado',NULL,NULL,'2014-08-15',NULL,'2012-05-20','Líder do ministério de comunicação',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(46,'Thiago Alves Costa','thiago.alves@email.com','(11) 99999-3333','1986-07-12','M','Rua Bela Cintra, 456','Consolação','São Paulo','SP','01234-901','casado',NULL,NULL,'2010-11-08',NULL,'2008-06-25','Líder do ministério de jovens',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(47,'Juliana Lima Santos','juliana.lima@email.com','(11) 99999-4444','1993-02-28','F','Rua Estados Unidos, 789','Jardim América','São Paulo','SP','01234-234','solteiro',NULL,NULL,'2016-04-10',NULL,'2014-09-15','Líder do ministério de crianças',NULL,1,1,1,'2025-08-06 02:38:53','2025-08-06 02:38:53'),(48,'Pr. João Silva','pastor@cbav.com','(11) 99999-8888','1975-05-15','M','Rua do Pastor, 123','Centro','São Paulo','SP','01234-123','casado',NULL,NULL,'1990-06-20',NULL,'1988-03-15','Pastor da igreja',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(49,'Maria Santos','tesoureiro@cbav.com','(11) 99999-7777','1982-08-20','F','Rua da Tesoureira, 456','Centro','São Paulo','SP','01234-456','casado',NULL,NULL,'1995-12-10',NULL,'1992-07-05','Tesoureira da igreja',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(50,'Carlos Oliveira','membro@cbav.com','(11) 99999-6666','1990-12-25','M','Rua do Membro, 789','Centro','São Paulo','SP','01234-789','solteiro',NULL,NULL,'2010-04-15',NULL,'2008-09-20','Membro da igreja',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(51,'Ana Costa','ana@cbav.com','(11) 99999-5555','1988-03-10','F','Rua da Ana, 321','Centro','São Paulo','SP','01234-321','casado',NULL,NULL,'2005-08-12',NULL,'2003-11-30','Líder do ministério de louvor',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(52,'Pedro Santos','pedro@cbav.com','(11) 99999-4444','1985-07-18','M','Rua do Pedro, 654','Centro','São Paulo','SP','01234-654','casado',NULL,NULL,'2007-03-25',NULL,'2005-06-10','Líder do ministério de evangelismo',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(53,'Lucia Ferreira','lucia@cbav.com','(11) 99999-3333','1978-11-05','F','Rua da Lucia, 987','Centro','São Paulo','SP','01234-987','casado',NULL,NULL,'2000-09-15',NULL,'1998-12-20','Líder do ministério de ação social',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(54,'Roberto Lima','roberto@cbav.com','(11) 99999-2222','1983-04-22','M','Rua do Roberto, 147','Centro','São Paulo','SP','01234-147','casado',NULL,NULL,'2003-05-30',NULL,'2001-08-15','Líder do ministério de ensino',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(55,'Fernanda Costa','fernanda@cbav.com','(11) 99999-1111','1992-09-08','F','Rua da Fernanda, 258','Centro','São Paulo','SP','01234-258','solteiro',NULL,NULL,'2012-07-14',NULL,'2010-10-25','Líder do ministério de hospitalidade',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(56,'Ricardo Almeida','ricardo@cbav.com','(11) 99999-0000','1987-12-03','M','Rua do Ricardo, 369','Centro','São Paulo','SP','01234-369','casado',NULL,NULL,'2009-11-08',NULL,'2007-02-12','Líder do ministério de tecnologia',NULL,1,1,1,'2025-08-06 02:39:49','2025-08-06 02:39:49');
/*!40000 ALTER TABLE `membros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_15_000001_create_template_pautas_table',1),(5,'2025_01_15_000002_create_template_item_pautas_table',1),(6,'2025_01_27_000000_create_ebd_quiz_perguntas_table',1),(7,'2025_01_27_000001_create_ebd_quiz_sessoes_table',1),(8,'2025_01_27_000002_create_ebd_quiz_respostas_table',1),(9,'2025_07_27_010218_create_permission_tables',1),(10,'2025_07_27_010221_create_personal_access_tokens_table',1),(11,'2025_07_27_010223_create_ministerios_table',1),(12,'2025_07_27_010225_create_departamentos_table',1),(13,'2025_07_27_010227_create_cargos_table',1),(14,'2025_07_27_010239_create_membros_table',1),(15,'2025_07_27_010240_create_ebd_tables',1),(16,'2025_07_27_010241_create_campanhas_table',1),(17,'2025_07_27_010247_create_transacoes_table',1),(18,'2025_07_27_010249_create_pagamentos_table',1),(19,'2025_07_27_010321_create_membro_cargo_table',1),(20,'2025_07_27_010350_create_configuracoes_table',1),(21,'2025_07_27_022239_add_profile_fields_to_users_table',1),(22,'2025_07_27_022500_add_sistema_field_to_cargos_table',1),(23,'2025_07_27_040007_create_notificacaos_table',1),(24,'2025_07_27_040906_create_user_cargo_table',1),(25,'2025_07_27_043731_add_entrada_to_transacoes_tipo_enum',1),(26,'2025_07_27_050034_add_dados_extras_to_transacoes_table',1),(27,'2025_07_27_112159_create_solicitacoes_ministerio_table',1),(28,'2025_07_28_111118_add_advanced_fields_to_notificacaos_table',1),(29,'2025_07_28_130652_create_devocionais_table',1),(30,'2025_07_30_000001_create_conselhos_table',1),(31,'2025_07_30_000002_create_pauta_conselhos_table',1),(32,'2025_07_30_000003_create_votacao_conselhos_table',1),(33,'2025_07_30_000004_create_participante_conselhos_table',1),(34,'2025_07_30_000005_create_voto_conselhos_table',1),(35,'2025_08_01_160654_add_missing_fields_to_membros_table',1),(36,'2025_08_01_160718_fix_data_membro_field_in_membros_table',1),(37,'2025_08_01_172356_add_tipo_to_campanhas_table',1),(38,'2025_08_02_031827_add_texto_versiculo_to_devocionais_table',1),(39,'2025_08_02_042633_add_template_id_to_conselhos_table',1),(40,'2025_08_02_134600_add_bible_fields_to_users_table',1),(41,'2025_08_02_135501_add_notification_settings_to_users_table',1),(42,'2025_08_04_013108_add_ativo_to_ebd_avaliacoes_table',1),(43,'2025_08_04_020100_unify_configurations_table',1),(44,'2025_08_04_020200_optimize_relationship_tables',1),(46,'2024_01_15_000000_create_documentos_baixa_table',2),(47,'2025_01_15_000003_create_igrejas_table',3),(48,'2025_01_15_000004_create_documentos_declaracao_anual_table',4),(49,'2025_01_27_000001_create_pedido_oracaos_table',5),(50,'2025_01_27_000002_create_intercessor_oracaos_table',5),(51,'2025_08_05_112707_add_categoria_metodo_pagamento_observacoes_to_transacoes_table',6),(52,'2025_08_05_235008_add_notification_preferences_to_users_table',7),(53,'2024_01_01_000001_create_eventos_table',8),(54,'2024_01_01_000002_create_evento_inscricoes_table',8),(55,'2024_01_01_000003_create_evento_pagamentos_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ministerios`
--

DROP TABLE IF EXISTS `ministerios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ministerios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `cor` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `icone` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ministerios`
--

LOCK TABLES `ministerios` WRITE;
/*!40000 ALTER TABLE `ministerios` DISABLE KEYS */;
INSERT INTO `ministerios` VALUES (1,'Louvor e Adoração','Ministério responsável pela música e adoração nos cultos','#3b82f6','music',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(2,'Jovens','Ministério voltado para jovens e adolescentes','#f59e0b','users',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(3,'Crianças','Ministério infantil e escola bíblica','#f59e0b',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,'Intercessão','Ministério de oração e intercessão pela igreja','#8b5cf6','pray',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(5,'Ação Social','Ministério de assistência social e caridade','#06b6d4','heart',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(6,'Ensino','Ministério responsável pela Escola Dominical e estudos bíblicos','#84cc16','book',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(7,'Evangelismo','Ministério de evangelização e missões','#ef4444','globe',1,'2025-08-04 21:49:17','2025-08-06 02:35:20'),(8,'Família','Ministério de aconselhamento familiar','#ec4899',NULL,1,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,'Infantil','Ministério dedicado ao cuidado e ensino das crianças','#10b981','child',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(10,'Hospitalidade','Ministério de recepção e acolhimento','#f97316','smile',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(11,'Tecnologia','Ministério de tecnologia e mídia','#6366f1','monitor',1,'2025-08-06 02:35:20','2025-08-06 02:35:20'),(12,'Finanças','Ministério de gestão financeira da igreja','#22c55e','dollar-sign',1,'2025-08-06 02:35:20','2025-08-06 02:35:20');
/*!40000 ALTER TABLE `ministerios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (18,'App\\Models\\User',1),(19,'App\\Models\\User',13),(20,'App\\Models\\User',14),(22,'App\\Models\\User',15),(22,'App\\Models\\User',16),(22,'App\\Models\\User',17),(22,'App\\Models\\User',18),(22,'App\\Models\\User',19),(22,'App\\Models\\User',20),(22,'App\\Models\\User',21);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacaos`
--

DROP TABLE IF EXISTS `notificacaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `acao_url` varchar(255) DEFAULT NULL,
  `acao_texto` varchar(255) DEFAULT NULL,
  `lida` tinyint(1) NOT NULL DEFAULT 0,
  `lida_em` timestamp NULL DEFAULT NULL,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `prioridade` enum('baixa','normal','alta','urgente') NOT NULL DEFAULT 'normal',
  `categoria` varchar(255) NOT NULL DEFAULT 'sistema',
  `destinatario_tipo` enum('usuario','membro','ministerio','todos') DEFAULT NULL,
  `destinatario_id` bigint(20) unsigned DEFAULT NULL,
  `enviada_por` bigint(20) unsigned DEFAULT NULL,
  `agendada_para` timestamp NULL DEFAULT NULL,
  `enviada_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notificacaos_user_id_foreign` (`user_id`),
  KEY `notificacaos_prioridade_categoria_index` (`prioridade`,`categoria`),
  KEY `notificacaos_destinatario_tipo_destinatario_id_index` (`destinatario_tipo`,`destinatario_id`),
  KEY `notificacaos_agendada_para_index` (`agendada_para`),
  KEY `notificacaos_enviada_em_index` (`enviada_em`),
  KEY `notificacaos_lida_created_at_index` (`lida`,`created_at`),
  CONSTRAINT `notificacaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacaos`
--

LOCK TABLES `notificacaos` WRITE;
/*!40000 ALTER TABLE `notificacaos` DISABLE KEYS */;
INSERT INTO `notificacaos` VALUES (2,2,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,1,'2025-08-06 21:19:15',NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-06 21:19:15'),(3,3,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,0,NULL,NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(4,4,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,0,NULL,NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(5,5,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,0,NULL,NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(6,6,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,0,NULL,NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(7,7,'sistema','Bem-vindo ao Sistema CBAV','Seja bem-vindo ao sistema de gestão ministerial CBAV!',NULL,NULL,NULL,0,NULL,NULL,'normal','boas_vindas',NULL,NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(20,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":76,\"membro_id\":13}','normal','financeiro','usuario',2,NULL,NULL,'2025-08-06 21:16:17','2025-08-05 05:54:57','2025-08-06 21:16:17'),(21,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":76,\"membro_id\":13}','normal','financeiro','usuario',8,NULL,NULL,'2025-08-06 21:16:17','2025-08-05 05:54:57','2025-08-06 21:16:17'),(23,4,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":77,\"membro_id\":13}','normal','financeiro','usuario',4,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 06:00:40','2025-08-06 21:16:01'),(24,6,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":77,\"membro_id\":13}','normal','financeiro','usuario',6,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 06:00:40','2025-08-06 21:16:01'),(25,3,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":77,\"membro_id\":13}','normal','financeiro','usuario',3,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 06:00:40','2025-08-06 21:16:01'),(27,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":77,\"membro_id\":13}','normal','financeiro','usuario',2,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 06:00:40','2025-08-06 21:16:01'),(28,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":77,\"membro_id\":13}','normal','financeiro','usuario',8,NULL,NULL,'2025-08-06 21:16:17','2025-08-05 06:00:40','2025-08-06 21:16:17'),(30,4,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":78,\"membro_id\":13}','normal','financeiro','usuario',4,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 13:43:56','2025-08-06 21:15:40'),(31,6,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":78,\"membro_id\":13}','normal','financeiro','usuario',6,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 13:43:56','2025-08-06 21:16:01'),(32,3,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":78,\"membro_id\":13}','normal','financeiro','usuario',3,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 13:43:56','2025-08-06 21:16:01'),(34,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":78,\"membro_id\":13}','normal','financeiro','usuario',2,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 13:43:56','2025-08-06 21:16:01'),(35,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":78,\"membro_id\":13}','normal','financeiro','usuario',8,NULL,NULL,'2025-08-06 21:16:01','2025-08-05 13:43:56','2025-08-06 21:16:01'),(36,NULL,'success','Doação Registrada','Sua doação de R$ 10,00 foi registrada com sucesso.',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"valor\":\"10.00\",\"tipo\":\"doacao\",\"membro_id\":3}','normal','financeiro','membro',3,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(37,4,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"membro_id\":3}','normal','financeiro','usuario',4,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(38,6,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"membro_id\":3}','normal','financeiro','usuario',6,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(39,3,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"membro_id\":3}','normal','financeiro','usuario',3,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(41,2,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"membro_id\":3}','normal','financeiro','usuario',2,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(42,8,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,1,NULL,'{\"categoria\":\"financeiro\",\"transacao_id\":79,\"membro_id\":3}','normal','financeiro','usuario',8,NULL,NULL,'2025-08-06 21:15:40','2025-08-05 14:17:23','2025-08-06 21:15:40'),(45,2,'quiz_recorde_pessoal','🎉 Parabéns! Novo Recorde Pessoal!','Você estabeleceu um novo recorde pessoal de pontuação total: 200 pontos',NULL,NULL,NULL,0,NULL,'{\"sessao_id\":8,\"tipo_recorde\":\"pontuacao\",\"valor\":200,\"is_global\":false}','normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 02:38:40','2025-08-06 21:15:40'),(46,2,'quiz_recorde_pessoal','🎉 Parabéns! Novo Recorde Pessoal!','Você estabeleceu um novo recorde pessoal de pontuação total: 330 pontos',NULL,NULL,NULL,0,NULL,'{\"sessao_id\":9,\"tipo_recorde\":\"pontuacao\",\"valor\":330,\"is_global\":false}','normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 02:38:40','2025-08-06 21:15:40'),(47,2,'quiz_recorde_pessoal','🎉 Parabéns! Novo Recorde Pessoal!','Você estabeleceu um novo recorde pessoal de pontuação total: 480 pontos',NULL,NULL,NULL,0,NULL,'{\"sessao_id\":12,\"tipo_recorde\":\"pontuacao\",\"valor\":480,\"is_global\":false}','normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 02:38:40','2025-08-06 21:15:40'),(103,1,'info','Bem-vindo ao Sistema','Seja bem-vindo ao sistema de gerenciamento da igreja!',NULL,NULL,NULL,1,'2025-08-06 21:09:32',NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 20:48:51','2025-08-06 20:38:44','2025-08-06 21:09:32'),(104,1,'success','Cadastro Realizado','Seu cadastro foi realizado com sucesso no sistema.',NULL,NULL,NULL,1,'2025-08-06 21:09:32',NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 20:49:23','2025-08-06 20:38:44','2025-08-06 21:09:32'),(105,1,'warning','Lembrete de Evento','Você tem um evento agendado para amanhã às 19h.',NULL,NULL,NULL,1,'2025-08-06 21:09:32',NULL,'alta','eventos',NULL,NULL,NULL,NULL,'2025-08-06 20:55:11','2025-08-06 20:38:44','2025-08-06 21:09:32'),(106,1,'error','Erro no Sistema','Ocorreu um erro no sistema. Entre em contato com o administrador.',NULL,NULL,NULL,1,'2025-08-06 21:09:32',NULL,'urgente','sistema',NULL,NULL,NULL,NULL,'2025-08-06 20:55:21','2025-08-06 20:38:44','2025-08-06 21:09:32'),(107,1,'info','Nova Campanha','Uma nova campanha de doação foi criada. Participe!',NULL,NULL,NULL,1,'2025-08-06 21:09:32',NULL,'normal','financeiro',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 20:38:44','2025-08-06 21:15:40'),(112,4,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(113,11,'success','Doação Registrada','Sua doação de R$ 10,00 foi registrada com sucesso.',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(114,4,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(115,6,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(116,3,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(117,2,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(118,8,'info','Nova Doação','Pedro Costa Oliveira fez uma doação de R$ 10,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:15:40','2025-08-06 21:15:40','2025-08-06 21:15:40'),(119,4,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(120,6,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(121,3,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(122,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(123,6,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(124,3,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(125,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(126,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 10.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:01','2025-08-06 21:16:01','2025-08-06 21:16:01'),(127,2,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:17','2025-08-06 21:16:17','2025-08-06 21:16:17'),(128,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:17','2025-08-06 21:16:17','2025-08-06 21:16:17'),(129,8,'info','Nova Doação','Admin Vertex fez uma doação de R$ 20.000,00',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema',NULL,NULL,NULL,NULL,'2025-08-06 21:16:17','2025-08-06 21:16:17','2025-08-06 21:16:17'),(130,NULL,'info','Tete do admin','mensagem teste',NULL,NULL,NULL,0,NULL,NULL,'normal','sistema','usuario',15,NULL,NULL,'2025-08-06 21:24:06','2025-08-06 21:23:52','2025-08-06 21:28:14');
/*!40000 ALTER TABLE `notificacaos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamentos`
--

DROP TABLE IF EXISTS `pagamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamentos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transacao_id` bigint(20) unsigned NOT NULL,
  `gateway` enum('stripe','mercadopago','pix') NOT NULL,
  `gateway_id` varchar(255) DEFAULT NULL,
  `gateway_status` varchar(255) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `moeda` varchar(255) NOT NULL DEFAULT 'BRL',
  `dados_gateway` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_gateway`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagamentos_transacao_id_foreign` (`transacao_id`),
  CONSTRAINT `pagamentos_transacao_id_foreign` FOREIGN KEY (`transacao_id`) REFERENCES `transacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamentos`
--

LOCK TABLES `pagamentos` WRITE;
/*!40000 ALTER TABLE `pagamentos` DISABLE KEYS */;
INSERT INTO `pagamentos` VALUES (1,1,'stripe','demo_fa6130ab-1adb-30a3-ae6b-bdd3b58a757f','confirmed',301.94,'BRL','{\"payment_id\":\"demo_70af76fc-20c3-3a60-8a7d-66e40766a8f0\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(2,2,'stripe','demo_988f11c8-1c95-3e1b-9c8a-87cedd7e0906','confirmed',288.46,'BRL','{\"payment_id\":\"demo_61c73656-1300-3c18-a56a-80982619a337\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,3,'pix','demo_83b3ceb2-e882-3714-9ad5-b67389513d98','confirmed',360.02,'BRL','{\"payment_id\":\"demo_fd652e8f-eee8-31c8-abb9-f33a154ee308\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,4,'pix','demo_218cc523-f1b9-3f4e-b668-d55640fd0cd6','confirmed',199.38,'BRL','{\"payment_id\":\"demo_24d9db5e-bb27-347a-aa05-cb63d1f97e7a\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,5,'stripe','demo_82255f34-6e83-3ecb-98fd-74126b4bc6ec','confirmed',317.54,'BRL','{\"payment_id\":\"demo_f3b55abb-016a-3a10-8b90-aafc4ff619bd\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,6,'mercadopago','demo_ff9a8410-c217-3ea1-bf4e-44289fc214b8','confirmed',373.09,'BRL','{\"payment_id\":\"demo_7e42d30e-eeee-3b1a-a14b-3a46d3195779\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(7,7,'mercadopago','demo_6b740c53-758b-3519-9d56-64b0abc4edbe','confirmed',445.47,'BRL','{\"payment_id\":\"demo_0467c2d1-5404-3bc2-852f-f02f2e5658f3\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,8,'mercadopago','demo_82df60c3-1530-3678-8856-9377e90cbb3a','confirmed',370.53,'BRL','{\"payment_id\":\"demo_fdc82dd8-3410-342d-9fd7-0db8c4033cdb\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,9,'mercadopago','demo_161e519c-0f27-3324-8b2c-d2630448fc3f','confirmed',364.74,'BRL','{\"payment_id\":\"demo_3205e74b-a086-3c3a-9a74-fccafc08120b\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(10,10,'stripe','demo_c794f409-e224-3926-a973-66ca472b9a94','confirmed',278.38,'BRL','{\"payment_id\":\"demo_ba89338b-8091-3a84-b9f7-59f010920269\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(11,11,'pix','demo_5d75b642-e658-3d0f-8534-1bf9684429ac','confirmed',223.74,'BRL','{\"payment_id\":\"demo_a01d0173-5db9-3853-8360-8a84edfd550c\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(12,12,'stripe','demo_125d829c-18fc-3b1d-9009-d10e2384da5a','confirmed',36.79,'BRL','{\"payment_id\":\"demo_44be5a50-e01e-3e10-8885-d91e8ce25533\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(13,13,'stripe','demo_ecc2c651-5d33-3997-a1f7-aef001201bad','confirmed',424.73,'BRL','{\"payment_id\":\"demo_f8e3ac20-085c-347a-a1cc-c588a0ee875c\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(14,14,'mercadopago','demo_0324bef1-8d9d-30e2-87a4-edb0621d8c3c','confirmed',357.33,'BRL','{\"payment_id\":\"demo_dacaa005-e79a-3939-99ae-f7d50dd6f6cf\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(15,15,'mercadopago','demo_22db3400-7545-3af7-a24a-a219e3a0eb31','confirmed',401.94,'BRL','{\"payment_id\":\"demo_38e94a82-6e6f-3655-b0a8-4e09c44f75b8\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(16,16,'pix','demo_66381780-4109-3642-9d31-4d39715ff397','confirmed',405.18,'BRL','{\"payment_id\":\"demo_641786cb-5399-34f0-aa12-d0c2717e5d84\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(17,17,'pix','demo_590dc8df-86ac-39cc-9f65-547e8db585c1','confirmed',424.50,'BRL','{\"payment_id\":\"demo_6dbb6431-bc72-3462-8dc6-bed781ef0b86\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(18,18,'stripe','demo_cf9451f1-6b94-38d3-9ed2-29899c7b06a4','confirmed',317.00,'BRL','{\"payment_id\":\"demo_e1f4b1e3-60b2-3272-8569-0d3583d8bfa7\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(19,19,'pix','demo_30b55a9b-aaa8-3f44-bc5b-6b957f6f2d72','confirmed',96.20,'BRL','{\"payment_id\":\"demo_24f23d07-830a-3caf-b8e7-33a4a1eacc0d\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(20,20,'pix','demo_d33f9266-33b2-3735-84d4-e7ee9394ecfe','confirmed',269.21,'BRL','{\"payment_id\":\"demo_46709732-3565-3e3f-83b7-0c634da462ac\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(21,21,'stripe','demo_cfaadeae-43a8-3eeb-8197-831d1d4b5ee1','confirmed',355.35,'BRL','{\"payment_id\":\"demo_b76c31f1-d3b2-3a59-84b0-36739a691561\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(22,22,'mercadopago','demo_12c74053-0556-3faf-89c5-7e7f644f2b8e','confirmed',71.72,'BRL','{\"payment_id\":\"demo_ac2824c1-4d4e-3ffe-ac3b-fcea3f85fa3e\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(23,23,'stripe','demo_45ed7a05-a20c-31f2-9a09-3ec23b264746','confirmed',356.49,'BRL','{\"payment_id\":\"demo_3f0dc732-ee7b-3202-b19e-7a3ac2d10c22\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(24,24,'pix','demo_f97d3f66-88df-3c71-9b27-fb42c42b3a7b','confirmed',263.83,'BRL','{\"payment_id\":\"demo_1385385a-32d9-31eb-920b-888e69c190fb\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(25,25,'stripe','demo_0d87166c-4b3e-3323-80ba-92a0120e3674','confirmed',100.53,'BRL','{\"payment_id\":\"demo_72b25b0f-e8f2-315c-9d8c-393982fdcd39\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(26,26,'mercadopago','demo_f945e8f4-a515-3d35-a714-cdbcd722f422','confirmed',110.04,'BRL','{\"payment_id\":\"demo_3d61977e-29b3-3070-8ba7-95a6733211d6\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(27,27,'pix','demo_3f49a98c-def2-3fdf-9d20-db4c02b921dd','confirmed',153.10,'BRL','{\"payment_id\":\"demo_27a481a3-0812-3c99-ad84-04a31b7835e5\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(28,28,'mercadopago','demo_9d1944ba-d188-3889-9430-81d2062424e4','confirmed',400.19,'BRL','{\"payment_id\":\"demo_58c1f1b4-f7c8-3d0f-a869-1303ff6f6341\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(29,29,'pix','demo_23f6fd89-8d69-3902-8f43-72a8b90d9b44','confirmed',197.55,'BRL','{\"payment_id\":\"demo_11e0b298-c0ac-3e8f-bcdc-16620dade41b\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(30,30,'pix','demo_345d026e-818f-3f75-9ee1-05c4563bfa4d','confirmed',250.98,'BRL','{\"payment_id\":\"demo_2e470348-9198-348b-a676-22471959489e\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(31,31,'stripe','demo_5e3c6a07-8f2f-3c47-af5c-d3289912e85d','confirmed',181.30,'BRL','{\"payment_id\":\"demo_66dc5260-c610-364b-8aa8-5244f0223211\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(32,32,'mercadopago','demo_61736ef9-74ed-3d10-b144-108208f51eeb','confirmed',406.90,'BRL','{\"payment_id\":\"demo_60f928a5-9782-339d-abde-600bd8483e4d\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(33,33,'pix','demo_0088bdc9-f12d-3c8f-b786-6b9e8239dd89','confirmed',284.46,'BRL','{\"payment_id\":\"demo_d2c77c55-80e9-3147-ad42-097dd5a8be54\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(34,34,'pix','demo_0fbbf627-6025-3c44-ac4f-19d14cc1f29e','confirmed',245.60,'BRL','{\"payment_id\":\"demo_584f2e40-2bec-35e2-ba3e-8b82cb942651\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(35,35,'pix','demo_71fb4ba0-748d-34fb-8f91-931b6eca103d','confirmed',262.78,'BRL','{\"payment_id\":\"demo_59012994-150f-36be-ab54-15a1f0f9d354\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(36,36,'stripe','demo_71723fa9-c111-3e32-b1f3-e6d420e212d8','confirmed',287.95,'BRL','{\"payment_id\":\"demo_48e39791-bd14-350c-8b55-c51ffe2dd974\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(37,37,'mercadopago','demo_95515b3c-72f6-3a68-a3ba-b90438a44f0d','confirmed',324.65,'BRL','{\"payment_id\":\"demo_481fbc1c-0a25-31a9-a380-8d04da4865b9\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(38,38,'stripe','demo_20fb1fdf-255e-3a36-8474-79fd165b0439','confirmed',248.72,'BRL','{\"payment_id\":\"demo_846ceddb-ddd4-3080-b7de-a6beb304c45b\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(39,39,'pix','demo_1e4a3806-76b6-31da-be34-664f940f7901','confirmed',293.33,'BRL','{\"payment_id\":\"demo_0074b518-31aa-38f8-a294-800b701ce0ad\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(40,40,'pix','demo_9b45bfe5-5fea-3a70-abeb-71571df74882','confirmed',340.97,'BRL','{\"payment_id\":\"demo_cd03e250-5338-3384-a568-cc720db62665\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(41,41,'stripe','demo_4d58326b-e7db-37b8-acea-5b09f9196383','confirmed',443.94,'BRL','{\"payment_id\":\"demo_bf978175-10c6-3b52-b3aa-0232f31bb64f\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(42,42,'mercadopago','demo_89808130-7975-3094-839d-936266cbb436','confirmed',442.31,'BRL','{\"payment_id\":\"demo_c8703a29-cac0-333f-9b49-117a2d11ba60\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(43,43,'mercadopago','demo_639bc49d-023a-31d0-af34-95e0dd4079d2','confirmed',75.17,'BRL','{\"payment_id\":\"demo_0a250d07-1af2-3280-8678-754208b90c12\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(44,44,'stripe','demo_29ad7959-42ea-3d88-b149-53edfda9ae9f','confirmed',17.84,'BRL','{\"payment_id\":\"demo_faa05eaa-b314-3aec-bc0f-5803cb948750\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(45,45,'pix','demo_adf872ec-6127-3bdf-ac43-a73e16295458','confirmed',182.51,'BRL','{\"payment_id\":\"demo_ee18a916-cb89-3a35-8a12-0e8940e47548\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(46,46,'pix','demo_98bf8e33-b182-3ba3-a408-d0b9b6377539','confirmed',94.06,'BRL','{\"payment_id\":\"demo_97d3aeee-1ca3-3cac-924a-7aeb3ae6531e\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(47,47,'mercadopago','demo_269cad7b-72b0-3664-bb15-71fdeeed8dbe','confirmed',450.09,'BRL','{\"payment_id\":\"demo_b12d8aa5-7daf-3610-9a36-993a50ae2a4c\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(48,48,'mercadopago','demo_e8d79287-054e-366e-843b-09eb29bb5376','confirmed',27.07,'BRL','{\"payment_id\":\"demo_02de2d1f-5d81-32c3-9fe5-200515e05d8c\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(49,49,'pix','demo_b4d05a60-ed41-3528-964e-e865bbe9d3f8','confirmed',286.47,'BRL','{\"payment_id\":\"demo_9a3f8350-a405-39f3-a677-b45513f1d0bf\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(50,50,'pix','demo_19e8ee05-aa6e-32b8-a5a3-f88a81000045','confirmed',223.14,'BRL','{\"payment_id\":\"demo_17cf4880-be1a-32b6-a797-66bab5ce90e7\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18');
/*!40000 ALTER TABLE `pagamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participante_conselhos`
--

DROP TABLE IF EXISTS `participante_conselhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participante_conselhos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conselho_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `funcao` enum('presidente','secretario','membro','convidado') NOT NULL DEFAULT 'membro',
  `presente` tinyint(1) NOT NULL DEFAULT 0,
  `hora_chegada` timestamp NULL DEFAULT NULL,
  `hora_saida` timestamp NULL DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participante_conselhos_conselho_id_user_id_unique` (`conselho_id`,`user_id`),
  KEY `participante_conselhos_conselho_id_presente_index` (`conselho_id`,`presente`),
  KEY `participante_conselhos_user_id_presente_index` (`user_id`,`presente`),
  CONSTRAINT `participante_conselhos_conselho_id_foreign` FOREIGN KEY (`conselho_id`) REFERENCES `conselhos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participante_conselhos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participante_conselhos`
--

LOCK TABLES `participante_conselhos` WRITE;
/*!40000 ALTER TABLE `participante_conselhos` DISABLE KEYS */;
/*!40000 ALTER TABLE `participante_conselhos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pauta_conselhos`
--

DROP TABLE IF EXISTS `pauta_conselhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pauta_conselhos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conselho_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('informativo','deliberativo','votacao') NOT NULL DEFAULT 'informativo',
  `prioridade` enum('baixa','media','alta','urgente') NOT NULL DEFAULT 'media',
  `ordem` int(11) NOT NULL DEFAULT 0,
  `tempo_estimado` int(11) DEFAULT NULL,
  `responsavel_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pendente','em_discussao','aprovado','rejeitado','adiado') NOT NULL DEFAULT 'pendente',
  `observacoes` text DEFAULT NULL,
  `decisao_final` text DEFAULT NULL,
  `data_decisao` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pauta_conselhos_responsavel_id_foreign` (`responsavel_id`),
  KEY `pauta_conselhos_conselho_id_status_index` (`conselho_id`,`status`),
  KEY `pauta_conselhos_ordem_index` (`ordem`),
  CONSTRAINT `pauta_conselhos_conselho_id_foreign` FOREIGN KEY (`conselho_id`) REFERENCES `conselhos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pauta_conselhos_responsavel_id_foreign` FOREIGN KEY (`responsavel_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pauta_conselhos`
--

LOCK TABLES `pauta_conselhos` WRITE;
/*!40000 ALTER TABLE `pauta_conselhos` DISABLE KEYS */;
INSERT INTO `pauta_conselhos` VALUES (1,6,'Aprovação do Orçamento Anual 2024','Discussão e aprovação do orçamento anual da igreja para 2024','deliberativo','alta',1,30,1,'aprovado','Orçamento aprovado por unanimidade','Aprovado por unanimidade','2024-01-15 23:30:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(2,6,'Eleição de Líderes de Ministérios','Eleição de novos líderes para os ministérios da igreja','deliberativo','alta',2,45,1,'aprovado','Todos os líderes eleitos e aprovados','Todos os líderes eleitos e aprovados','2024-01-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(3,6,'Discussão sobre Reforma do Templo','Discussão sobre a necessidade de reforma do templo e possíveis soluções','informativo','media',3,20,1,'pendente','Necessário mais estudos técnicos',NULL,NULL,'2025-08-06 02:39:37','2025-08-06 02:39:37'),(4,7,'Campanha de Reforma do Templo','Aprovação e planejamento da campanha para reforma do templo','deliberativo','alta',1,40,1,'aprovado','Campanha aprovada e iniciada','Campanha aprovada e iniciada','2024-02-10 18:30:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(5,7,'Aprovação de Novos Membros','Aprovação de novos membros que solicitaram adesão à igreja','deliberativo','media',2,25,1,'aprovado','Todos os membros aprovados','Todos os membros aprovados','2024-02-10 19:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(6,8,'Relatório Financeiro Mensal','Apresentação e análise do relatório financeiro do mês de fevereiro','informativo','alta',1,20,1,'aprovado','Relatório aprovado','Relatório aprovado','2024-03-15 23:15:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(7,8,'Aprovação de Eventos','Aprovação de eventos programados para o próximo trimestre','deliberativo','media',2,30,1,'aprovado','Todos os eventos aprovados','Todos os eventos aprovados','2024-03-15 23:45:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(8,8,'Discussão sobre Escola Dominical','Discussão sobre melhorias na Escola Dominical e novos professores','deliberativo','media',3,25,1,'aprovado','Melhorias aprovadas','Melhorias aprovadas','2024-03-16 00:00:00','2025-08-06 02:39:37','2025-08-06 02:39:37'),(9,9,'Avaliação dos Ministérios','Avaliação do desempenho dos ministérios no primeiro trimestre','informativo','alta',1,30,1,'pendente','Aguardando reunião',NULL,NULL,'2025-08-06 02:39:37','2025-08-06 02:39:37'),(10,9,'Planejamento para o Segundo Trimestre','Planejamento de atividades e eventos para o segundo trimestre','deliberativo','alta',2,40,1,'pendente','Aguardando reunião',NULL,NULL,'2025-08-06 02:39:37','2025-08-06 02:39:37'),(11,9,'Discussão sobre Evangelismo','Discussão sobre estratégias de evangelismo e missões','deliberativo','media',3,25,1,'pendente','Aguardando reunião',NULL,NULL,'2025-08-06 02:39:37','2025-08-06 02:39:37');
/*!40000 ALTER TABLE `pauta_conselhos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_oracaos`
--

DROP TABLE IF EXISTS `pedido_oracaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido_oracaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` enum('saude','familia','trabalho','espiritual','outros') NOT NULL,
  `prioridade` enum('baixa','media','alta','urgente') NOT NULL,
  `status` enum('pendente','em_oracao','atendido','arquivado') NOT NULL DEFAULT 'pendente',
  `data_pedido` datetime NOT NULL,
  `data_atendimento` datetime DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `anonimo` tinyint(1) NOT NULL DEFAULT 0,
  `pode_compartilhar` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_oracaos_membro_id_foreign` (`membro_id`),
  CONSTRAINT `pedido_oracaos_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_oracaos`
--

LOCK TABLES `pedido_oracaos` WRITE;
/*!40000 ALTER TABLE `pedido_oracaos` DISABLE KEYS */;
INSERT INTO `pedido_oracaos` VALUES (1,11,'Cura para minha mãe','Minha mãe está com problemas de saúde e precisa de oração para se recuperar.','saude','alta','em_oracao','2025-07-25 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(2,12,'Reconciliação familiar','Preciso de oração para reconciliação com minha família.','familia','urgente','pendente','2025-07-24 00:14:59',NULL,NULL,1,0,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(3,11,'Novo emprego','Estou desempregado e preciso de um novo trabalho.','trabalho','media','em_oracao','2025-07-22 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(4,12,'Crescimento espiritual','Quero crescer mais na fé e ter uma vida mais próxima de Deus.','espiritual','media','pendente','2025-07-06 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(5,11,'Prova da faculdade','Tenho uma prova importante na faculdade e preciso de sabedoria.','outros','baixa','atendido','2025-07-07 00:14:59','2025-07-30 00:14:59',NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(6,12,'Cura do diabetes','Preciso de oração para cura do diabetes.','saude','alta','em_oracao','2025-07-08 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(7,11,'Casamento em crise','Meu casamento está passando por dificuldades.','familia','urgente','pendente','2025-07-31 00:14:59',NULL,NULL,1,0,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(8,12,'Promoção no trabalho','Estou concorrendo a uma promoção no trabalho.','trabalho','media','em_oracao','2025-07-30 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(9,11,'Libertação de vícios','Preciso de oração para vencer vícios.','espiritual','alta','pendente','2025-07-06 00:14:59',NULL,NULL,1,0,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(10,12,'Viagem missionária','Vou participar de uma viagem missionária e preciso de oração.','outros','media','em_oracao','2025-07-19 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(11,11,'Cirurgia do pai','Meu pai vai fazer uma cirurgia cardíaca.','saude','urgente','pendente','2025-08-04 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(12,12,'Filho rebelde','Meu filho está com problemas de comportamento.','familia','alta','em_oracao','2025-07-07 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(13,11,'Mudança de carreira','Estou pensando em mudar de carreira.','trabalho','media','pendente','2025-07-14 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(14,12,'Batismo do Espírito Santo','Quero receber o batismo do Espírito Santo.','espiritual','alta','em_oracao','2025-07-15 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(15,11,'Compra de casa','Estou tentando comprar uma casa.','outros','media','pendente','2025-07-28 00:14:59',NULL,NULL,0,1,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(16,1,'Oração pela Família','Peço oração pela minha família. Estamos passando por dificuldades financeiras e precisamos da provisão de Deus.','familia','alta','pendente','2025-08-03 23:39:42',NULL,'Membro ativo, muito fiel',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(17,36,'Oração pela Saúde','Peço oração pela minha saúde. Tenho um problema de pressão alta e preciso de cura.','saude','alta','em_oracao','2025-08-04 23:39:42',NULL,'Membro dedicada, líder do ministério infantil',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(18,37,'Oração pelo Trabalho','Peço oração pelo meu trabalho. Estou buscando uma nova oportunidade profissional.','trabalho','media','pendente','2025-08-02 23:39:42',NULL,'Músico do ministério de louvor',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(19,27,'Oração pela Conversão','Peço oração pela minha conversão. Quero entregar minha vida a Jesus.','espiritual','alta','pendente','2025-08-05 11:39:42',NULL,'Visitante, muito interessada',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(20,24,'Oração pela Família','Peço oração pela minha família. Meu filho está com problemas na escola.','familia','media','atendido','2025-07-31 23:39:42','2025-08-03 23:39:42','Diácono da igreja',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(21,25,'Oração pela Igreja','Peço oração pela igreja. Que Deus abençoe todos os ministérios e líderes.','espiritual','alta','em_oracao','2025-08-04 23:39:42',NULL,'Presbítera da igreja',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(22,41,'Oração pelo Evangelismo','Peço oração pelo evangelismo. Que Deus abra portas para compartilhar o evangelho.','espiritual','media','pendente','2025-08-02 23:39:42',NULL,'Líder do ministério de evangelismo',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(23,47,'Oração pela Família','Peço oração pela minha família. Meus pais estão doentes e precisamos de cura.','familia','alta','pendente','2025-08-04 23:39:42',NULL,'Auxiliar do ministério infantil',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(24,42,'Oração pela Igreja','Peço oração pela igreja. Que Deus envie mais pessoas para conhecer a Cristo.','espiritual','media','pendente','2025-08-03 23:39:42',NULL,'Líder do ministério de intercessão',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(25,45,'Oração pelos Estudos','Peço oração pelos meus estudos. Estou no último ano da faculdade.','trabalho','media','pendente','2025-08-01 23:39:42',NULL,'Membro novo, muito animada',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42'),(26,46,'Oração pela Ação Social','Peço oração pela ação social. Que Deus nos ajude a ajudar mais pessoas.','outros','media','pendente','2025-08-04 23:39:42',NULL,'Líder do ministério de ação social',0,1,'2025-08-06 02:39:42','2025-08-06 02:39:42');
/*!40000 ALTER TABLE `pedido_oracaos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=409 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (292,'admin master','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(293,'acesso admin','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(294,'acesso membro','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(295,'acesso tesoureiro','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(296,'acesso lider','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(297,'people.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(298,'finance.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(299,'system.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(300,'devotionals.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(301,'council.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(302,'ebd.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(303,'ebd.turmas.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(304,'ebd.professores.access','web','2025-08-06 02:37:26','2025-08-06 02:37:26'),(305,'ebd.alunos.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(306,'ebd.licoes.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(307,'ebd.aulas.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(308,'ebd.avaliacoes.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(309,'ebd.relatorios.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(310,'ebd.quiz.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(311,'ver membros','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(312,'criar membros','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(313,'editar membros','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(314,'excluir membros','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(315,'members.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(316,'members.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(317,'members.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(318,'members.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(319,'ver ministerios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(320,'criar ministerios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(321,'editar ministerios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(322,'excluir ministerios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(323,'ministries.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(324,'ministries.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(325,'ministries.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(326,'ministries.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(327,'ver departamentos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(328,'criar departamentos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(329,'editar departamentos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(330,'excluir departamentos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(331,'departments.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(332,'departments.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(333,'departments.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(334,'departments.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(335,'ver cargos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(336,'criar cargos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(337,'editar cargos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(338,'excluir cargos','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(339,'ver campanhas','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(340,'criar campanhas','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(341,'editar campanhas','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(342,'excluir campanhas','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(343,'campaigns.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(344,'campaigns.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(345,'campaigns.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(346,'campaigns.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(347,'ver transacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(348,'criar transacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(349,'editar transacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(350,'excluir transacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(351,'transactions.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(352,'transactions.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(353,'transactions.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(354,'transactions.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(355,'ver relatorios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(356,'exportar relatorios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(357,'reports.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(358,'reports.export','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(359,'ver configuracoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(360,'editar configuracoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(361,'settings.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(362,'settings.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(363,'ver usuarios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(364,'criar usuarios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(365,'editar usuarios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(366,'excluir usuarios','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(367,'users.access','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(368,'users.create','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(369,'users.edit','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(370,'users.delete','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(371,'ver devocionais','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(372,'criar devocionais','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(373,'editar devocionais','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(374,'excluir devocionais','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(375,'ver notificacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(376,'criar notificacoes','web','2025-08-06 02:37:27','2025-08-06 02:37:27'),(377,'gerenciar notificacoes','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(378,'notifications.access','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(379,'notifications.create','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(380,'notifications.edit','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(381,'acesso sistema','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(382,'backup sistema','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(383,'ver logs','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(384,'logs sistema','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(385,'logs.access','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(386,'logs.edit','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(387,'ver conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(388,'criar conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(389,'editar conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(390,'excluir conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(391,'votar conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(392,'gerenciar conselho','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(393,'intercessor.access','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(394,'intercessor.dashboard','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(395,'intercessor.view_pedidos','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(396,'intercessor.registrar_intercessao','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(397,'intercessor.atualizar_status','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(398,'intercessor.view_relatorios','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(399,'eventos.access','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(400,'eventos.create','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(401,'eventos.edit','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(402,'eventos.delete','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(403,'eventos.view','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(404,'eventos.manage','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(405,'eventos.inscricoes.view','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(406,'eventos.inscricoes.manage','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(407,'eventos.pagamentos.view','web','2025-08-06 14:49:39','2025-08-06 14:49:39'),(408,'eventos.pagamentos.manage','web','2025-08-06 14:49:39','2025-08-06 14:49:39');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_index` (`role_id`),
  KEY `role_has_permissions_permission_id_index` (`permission_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (292,18),(293,18),(293,19),(294,18),(294,19),(294,20),(294,21),(294,22),(295,18),(295,19),(295,20),(296,18),(296,19),(296,21),(297,18),(297,19),(297,20),(297,21),(298,18),(298,19),(298,20),(299,18),(299,19),(300,18),(300,19),(301,18),(301,19),(302,18),(302,19),(303,18),(303,19),(304,18),(304,19),(305,18),(305,19),(306,18),(306,19),(307,18),(307,19),(308,18),(308,19),(309,18),(309,19),(310,18),(310,19),(310,22),(311,18),(311,19),(311,20),(311,21),(312,18),(312,19),(312,21),(313,18),(313,19),(313,21),(314,18),(315,18),(315,19),(315,20),(315,21),(316,18),(316,19),(316,21),(317,18),(317,19),(317,21),(318,18),(319,18),(319,19),(319,20),(319,21),(320,18),(320,19),(321,18),(321,19),(322,18),(323,18),(323,19),(323,20),(323,21),(324,18),(324,19),(325,18),(325,19),(326,18),(327,18),(327,19),(327,20),(327,21),(328,18),(328,19),(329,18),(329,19),(330,18),(331,18),(331,19),(331,20),(331,21),(332,18),(332,19),(333,18),(333,19),(334,18),(335,18),(335,19),(335,20),(335,21),(336,18),(336,19),(337,18),(337,19),(338,18),(339,18),(339,19),(339,20),(339,21),(340,18),(340,19),(340,20),(341,18),(341,19),(341,20),(342,18),(343,18),(343,19),(343,20),(343,21),(344,18),(344,19),(344,20),(345,18),(345,19),(345,20),(346,18),(347,18),(347,19),(347,20),(347,21),(348,18),(348,19),(348,20),(349,18),(349,19),(349,20),(350,18),(351,18),(351,19),(351,20),(351,21),(352,18),(352,19),(352,20),(353,18),(353,19),(353,20),(354,18),(355,18),(355,19),(355,20),(355,21),(356,18),(356,19),(356,20),(357,18),(357,19),(357,20),(357,21),(358,18),(358,19),(358,20),(359,18),(359,19),(360,18),(360,19),(361,18),(361,19),(362,18),(362,19),(363,18),(363,19),(364,18),(364,19),(365,18),(365,19),(366,18),(367,18),(367,19),(368,18),(368,19),(369,18),(369,19),(370,18),(371,18),(371,19),(372,18),(372,19),(373,18),(373,19),(374,18),(375,18),(375,19),(375,20),(375,21),(375,22),(376,18),(376,19),(377,18),(377,19),(378,18),(378,19),(378,20),(378,21),(378,22),(379,18),(379,19),(380,18),(380,19),(381,18),(382,18),(383,18),(383,19),(384,18),(385,18),(385,19),(386,18),(387,18),(387,19),(388,18),(388,19),(389,18),(389,19),(390,18),(391,18),(391,19),(392,18),(393,18),(393,19),(393,21),(394,18),(394,19),(394,21),(395,18),(395,19),(395,21),(396,18),(396,19),(396,21),(397,18),(397,19),(397,21),(398,18),(398,19),(398,21),(399,18),(400,18),(401,18),(402,18),(403,18),(404,18),(405,18),(406,18),(407,18),(408,18);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (18,'Super Admin','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(19,'Pastor','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(20,'Tesoureiro','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(21,'Líder','web','2025-08-06 02:37:28','2025-08-06 02:37:28'),(22,'Membro','web','2025-08-06 02:37:28','2025-08-06 02:37:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('AP5FhRNMsRO9Sjdmu0nUjt2szBPLBxdHnzrQIHO3',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoidHpMckhFT3VSMjhkNE9qTXRaV1lqQ3RiOWRTMklJZDFlak90Y1JGOCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZXZlbnRvcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1754515758),('Cs0cWv5IhTn0ZLkcA31SA24WNVkdxBwRHqCnvmua',15,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlBLVGRFZzltMktLaGtMN1ZGWGpUYU00TTVXd2c4QkJxWDh2dUw4TyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYWNvZXMvY291bnQtbmFvLWxpZGFzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTU7fQ==',1754515713),('hbT2qEU3iNgucCOZvj3RPl6sod4ULVfR18T7sE0R',NULL,'127.0.0.1','curl/8.0.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXJybFo0R3B0bEt4RnZ3blpGZjk2dUxEWW4wVzI1bEM4SkFVcEp4SiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21lbWJlci9ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW1iZXIvbm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1754512577),('hjDGk4HVFluDG9C7wYPVmNxWSK0ijhxBGeM9LnUC',NULL,'127.0.0.1','curl/8.0.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1hqWE9tZzhPanNuR3ZwNjc4MzhsdXI1Z1Q1VjU0Y0xTd2tDN2luNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1754515821),('IEWu18ZcBiTzfcNuu8hMBXMh3eQsp1XqW93O0T7u',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicmlvNVBDZWdpcDZWdEliVVZzS1JjWDlNOXJRZ0p1M0syZUs4RnhzMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1754514248),('moZia57MJosZw40C3eP0rxSr8LIDPzKXuw3lWHIX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRlBMS3hoRmdzdVFSYUFZRGFidDVRb0tGanVoNmJJd25IVHpyb0FZTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21lbWJlci9ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW1iZXIvbm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1754514248),('QFjFpgREShtVatpol76QpxlIExKqPpPajY0rhKQL',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQzRkUWhGekhDdllSeXl2aFRGd0hDVW9kcGNRb1hEb0h1ZWRUM0RhSSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbm90aWZpY2Fjb2VzL2NvdW50LW5hby1saWRhcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1754526675),('RQkdRhQa1VexlpPkxEmKWZ01skI31untS5awMkd9',NULL,'127.0.0.1','curl/8.0.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSkppNUdJVUJiaW9jcVlXM1drWnJRV1FDZEJxbHQ5MHBHbmFtVEh0ZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N5c3RlbS9ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zeXN0ZW0vbm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1754513315),('s4fM86lrc0Yl6ZYyrbaC22Sw9y03TvpCsQEn6Lhr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZmJNOW9aMmJMenlYVXBhaXpDZ3NGWGNyOTlpcDhNaGtydkEzb0RqNSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1754528594);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitacoes_ministerio`
--

DROP TABLE IF EXISTS `solicitacoes_ministerio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitacoes_ministerio` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned NOT NULL,
  `ministerio_id` bigint(20) unsigned NOT NULL,
  `cargo_id` bigint(20) unsigned NOT NULL,
  `motivo` text NOT NULL,
  `status` enum('pendente','aprovada','rejeitada') NOT NULL DEFAULT 'pendente',
  `resposta` text DEFAULT NULL,
  `respondido_por` bigint(20) unsigned DEFAULT NULL,
  `data_resposta` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitacoes_ministerio_cargo_id_foreign` (`cargo_id`),
  KEY `solicitacoes_ministerio_respondido_por_foreign` (`respondido_por`),
  KEY `solicitacoes_ministerio_membro_id_status_index` (`membro_id`,`status`),
  KEY `solicitacoes_ministerio_ministerio_id_status_index` (`ministerio_id`,`status`),
  CONSTRAINT `solicitacoes_ministerio_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitacoes_ministerio_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitacoes_ministerio_ministerio_id_foreign` FOREIGN KEY (`ministerio_id`) REFERENCES `ministerios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitacoes_ministerio_respondido_por_foreign` FOREIGN KEY (`respondido_por`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitacoes_ministerio`
--

LOCK TABLES `solicitacoes_ministerio` WRITE;
/*!40000 ALTER TABLE `solicitacoes_ministerio` DISABLE KEYS */;
INSERT INTO `solicitacoes_ministerio` VALUES (1,1,8,40,'Saepe rerum eveniet voluptatem modi minus aut. Aperiam quibusdam voluptate ab inventore sed amet numquam. Sed quas tempora hic sed aliquid officia.','aprovada',NULL,4,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(2,1,6,36,'Quia enim mollitia eaque eaque voluptatum aut recusandae. Possimus blanditiis vitae minima. Corporis cum laboriosam et ab totam molestiae blanditiis. Dolores est labore atque molestiae.','pendente',NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(3,9,1,21,'Ut excepturi nihil voluptatem dolorem. Reiciendis qui quis voluptas cumque. Corrupti aspernatur aut a aperiam consequuntur nostrum. Quo blanditiis iure harum non saepe et eaque ratione.','aprovada',NULL,6,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(4,5,8,40,'Quas voluptatem doloribus expedita sunt. Tenetur excepturi incidunt molestias sed cumque consequatur. Molestiae sed consequuntur doloremque.','aprovada',NULL,5,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(5,9,1,21,'Facilis esse sed et quo cumque in quia. Ipsum nihil in impedit voluptas explicabo sit temporibus. Qui tempora eaque asperiores eum. Veritatis maxime reiciendis est tempora quis.','rejeitada','Sed commodi nesciunt facilis aliquid itaque.',6,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(6,8,4,31,'Voluptatem nam voluptatibus totam quo dicta est. Eaque voluptates qui quia est tempore minus. Delectus et quidem sit quisquam necessitatibus beatae repellat.','pendente',NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(7,7,5,33,'Distinctio modi soluta reprehenderit placeat. Rem voluptatem et et quod. Velit pariatur et quo aut.','aprovada',NULL,1,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(8,2,6,36,'Enim sit ut aliquid. Doloremque eum ut excepturi impedit omnis molestiae delectus. Aut modi modi magni sunt dolor quia. Voluptas sit deserunt debitis excepturi.','rejeitada','Repellendus repellendus blanditiis deserunt et dolorum praesentium.',4,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(9,1,1,21,'Rerum velit laboriosam aspernatur expedita consectetur. Consectetur iure debitis repellendus quia tenetur. Id et nam quo accusantium qui quidem modi. Rerum odit libero praesentium maiores placeat quas.','aprovada',NULL,3,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(10,6,1,21,'Debitis aspernatur quis deserunt debitis ipsa sint. Illum enim ducimus et autem veniam necessitatibus. Id repudiandae deleniti occaecati.','rejeitada','Distinctio reiciendis veniam facilis et recusandae.',4,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(11,5,8,40,'Earum sint hic placeat neque beatae laudantium. Provident nisi dignissimos cupiditate molestiae. Qui et quas qui exercitationem.','pendente',NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(12,6,7,38,'Odit et placeat adipisci quod id ad explicabo sed. Atque repellat amet velit. Ratione nisi dolore sit voluptatem.','rejeitada','Magni tenetur rerum vitae suscipit necessitatibus blanditiis.',1,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(13,2,5,33,'Alias expedita et molestiae consequatur dolorem modi. Nisi nulla nam ullam eum nulla. Atque eos aspernatur delectus aspernatur. Doloremque sint est animi.','pendente',NULL,NULL,NULL,'2025-08-04 21:49:18','2025-08-04 21:49:18'),(14,7,3,28,'Libero et ipsam ab est velit doloremque voluptas quo. Eligendi vel maxime nam odio magnam. Harum laudantium fuga fuga ipsum.','rejeitada','Fuga consectetur sint nostrum rerum vel quia consequatur.',5,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(15,9,5,33,'Voluptatibus accusamus ducimus in aliquid. Cumque impedit adipisci facilis quisquam qui unde et. Aut et at quos fuga laborum expedita accusantium id. Ex illum ipsam vel ullam error facere voluptatem.','aprovada',NULL,5,'2025-08-04 21:49:18','2025-08-04 21:49:18','2025-08-04 21:49:18'),(16,1,1,21,'Tenho dom musical e quero servir no ministério de louvor. Toco violão há 10 anos e canto desde criança. Disponível domingos e ensaios durante a semana.','aprovada','Aprovado! Muito talentoso, já participa dos ensaios',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(17,36,9,48,'Tenho paixão por trabalhar com crianças e gostaria de servir neste ministério. Sou professora de educação infantil e tenho experiência com crianças. Disponível domingos e sábados para preparação.','aprovada','Aprovado! Formação em pedagogia, muito qualificada',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(18,37,1,21,'Sou músico e gostaria de usar meu dom para glorificar a Deus. Toco bateria há 8 anos e já participei de bandas. Disponível todos os ensaios e cultos.','aprovada','Aprovado! Excelente músico, muito comprometido',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(19,27,1,49,'Tenho o dom do canto e quero usar para adorar ao Senhor. Canto desde criança e já participei de corais. Disponível domingos e ensaios.','aprovada','Aprovado! Voz muito bonita, muito dedicada',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(20,24,4,31,'Tenho o dom da intercessão e gostaria de orar pela igreja. Já participei de grupos de oração em outras igrejas. Disponível quartas-feiras e outros horários.','aprovada','Aprovado! Muito espiritual, dedicado à oração',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(21,25,6,48,'Gostaria de ensinar na Escola Dominical. Sou professora aposentada e tenho experiência em ensino. Disponível domingos pela manhã.','aprovada','Aprovado! Muita experiência em ensino, muito qualificada',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(22,41,6,48,'Quero ensinar a Palavra de Deus na Escola Dominical. Estudo a Bíblia há muitos anos e tenho formação teológica. Disponível domingos e preparação durante a semana.','aprovada','Aprovado! Conhecimento bíblico sólido',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(23,42,2,46,'Sou jovem e quero liderar outros jovens para Cristo. Já participei de ministérios de jovens em outras igrejas. Disponível sábados e eventos especiais.','aprovada','Aprovado! Líder nata, muito carismática',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(24,43,11,23,'Tenho conhecimento em tecnologia e quero ajudar na transmissão. Trabalho com TI e tenho experiência com equipamentos. Disponível domingos e manutenção durante a semana.','aprovada','Aprovado! Muito técnico, conhecimento avançado',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(25,10,10,50,'Gosto de receber pessoas e quero servir neste ministério. Trabalho com atendimento ao cliente. Disponível domingos e eventos especiais.','aprovada','Aprovado! Muito simpática e atenciosa',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(26,44,7,39,'Tenho paixão por evangelizar e ganhar almas para Cristo. Já participei de evangelismo de rua e visitas. Disponível domingos e durante a semana.','aprovada','Aprovado! Muito evangelista, coração missionário',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(27,47,9,50,'Amo crianças e quero servir neste ministério. Sou babá e tenho experiência com crianças. Disponível domingos e preparação.','pendente',NULL,NULL,NULL,'2025-08-06 02:39:18','2025-08-06 02:39:18'),(28,46,4,31,'Tenho o dom da oração e quero interceder pela igreja. Participei de grupos de oração há anos. Disponível quartas-feiras e outros horários.','aprovada','Aprovado! Muito espiritual e dedicado',1,'2025-08-06 02:39:18','2025-08-06 02:39:18','2025-08-06 02:39:18'),(29,45,2,50,'Sou jovem e quero participar do ministério de jovens. Nova convertida, muito animada. Disponível sábados e eventos.','pendente',NULL,NULL,NULL,'2025-08-06 02:39:18','2025-08-06 02:39:18');
/*!40000 ALTER TABLE `solicitacoes_ministerio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_item_pautas`
--

DROP TABLE IF EXISTS `template_item_pautas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_item_pautas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('informativo','deliberativo','votacao','discussao','apresentacao') NOT NULL DEFAULT 'informativo',
  `prioridade` enum('baixa','media','alta','urgente') NOT NULL DEFAULT 'media',
  `ordem` int(11) NOT NULL DEFAULT 1,
  `tempo_estimado` int(11) DEFAULT NULL,
  `responsavel_id` bigint(20) unsigned DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `configuracoes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracoes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_item_pautas_responsavel_id_foreign` (`responsavel_id`),
  KEY `template_item_pautas_template_id_ordem_index` (`template_id`,`ordem`),
  KEY `template_item_pautas_tipo_prioridade_index` (`tipo`,`prioridade`),
  CONSTRAINT `template_item_pautas_responsavel_id_foreign` FOREIGN KEY (`responsavel_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `template_item_pautas_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `template_pautas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_item_pautas`
--

LOCK TABLES `template_item_pautas` WRITE;
/*!40000 ALTER TABLE `template_item_pautas` DISABLE KEYS */;
/*!40000 ALTER TABLE `template_item_pautas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_pautas`
--

DROP TABLE IF EXISTS `template_pautas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_pautas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` enum('reuniao_ordinaria','reuniao_extraordinaria','votacao','evento','geral') NOT NULL DEFAULT 'geral',
  `status` enum('ativo','inativo','rascunho') NOT NULL DEFAULT 'rascunho',
  `criado_por` bigint(20) unsigned NOT NULL,
  `itens_pauta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`itens_pauta`)),
  `configuracoes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracoes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_pautas_status_categoria_index` (`status`,`categoria`),
  KEY `template_pautas_criado_por_index` (`criado_por`),
  CONSTRAINT `template_pautas_criado_por_foreign` FOREIGN KEY (`criado_por`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_pautas`
--

LOCK TABLES `template_pautas` WRITE;
/*!40000 ALTER TABLE `template_pautas` DISABLE KEYS */;
INSERT INTO `template_pautas` VALUES (1,'Reunião Ordinária','Template padrão para reuniões ordinárias','reuniao_ordinaria','ativo',1,'\"[\\\"Ora\\\\u00e7\\\\u00e3o inicial\\\",\\\"Leitura da ata anterior\\\",\\\"Relat\\\\u00f3rios ministeriais\\\",\\\"Assuntos gerais\\\",\\\"Ora\\\\u00e7\\\\u00e3o final\\\"]\"',NULL,'2025-08-04 21:19:41','2025-08-04 21:19:41'),(2,'Reunião Extraordinária','Template para reuniões extraordinárias','reuniao_extraordinaria','ativo',1,'\"[\\\"Ora\\\\u00e7\\\\u00e3o inicial\\\",\\\"Assunto espec\\\\u00edfico\\\",\\\"Discuss\\\\u00e3o e vota\\\\u00e7\\\\u00e3o\\\",\\\"Ora\\\\u00e7\\\\u00e3o final\\\"]\"',NULL,'2025-08-04 21:20:54','2025-08-04 21:20:54'),(3,'Votação Especial','Template para votações especiais','votacao','ativo',1,'\"[\\\"Abertura da sess\\\\u00e3o\\\",\\\"Apresenta\\\\u00e7\\\\u00e3o da proposta\\\",\\\"Esclarecimentos\\\",\\\"Vota\\\\u00e7\\\\u00e3o\\\",\\\"Resultado da vota\\\\u00e7\\\\u00e3o\\\",\\\"Encerramento\\\"]\"',NULL,'2025-08-04 21:23:34','2025-08-04 21:23:34');
/*!40000 ALTER TABLE `template_pautas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacoes`
--

DROP TABLE IF EXISTS `transacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transacoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `membro_id` bigint(20) unsigned DEFAULT NULL,
  `campanha_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` enum('dizimo','oferta','doacao','saida','entrada') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `metodo_pagamento` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data` date NOT NULL,
  `status` enum('pendente','confirmado','cancelado') NOT NULL DEFAULT 'pendente',
  `comprovante` varchar(255) DEFAULT NULL,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacoes_membro_id_foreign` (`membro_id`),
  KEY `transacoes_campanha_id_foreign` (`campanha_id`),
  CONSTRAINT `transacoes_campanha_id_foreign` FOREIGN KEY (`campanha_id`) REFERENCES `campanhas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transacoes_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `membros` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacoes`
--

LOCK TABLES `transacoes` WRITE;
/*!40000 ALTER TABLE `transacoes` DISABLE KEYS */;
INSERT INTO `transacoes` VALUES (1,3,5,'entrada',301.94,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-07-16','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_ae8c0103-619e-3310-a191-44d9a899f0e1\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(2,4,4,'entrada',288.46,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-08-03','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_855c9e67-cb6f-3cfe-93e6-e6534a06610b\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,5,1,'entrada',360.02,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-06-19','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_808d0e11-ed20-309f-968d-995a87247952\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,10,4,'entrada',199.38,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-07-21','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_6c6e9814-0a3f-3412-b064-2a9337091df0\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,3,5,'entrada',317.54,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-07-03','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_751427e3-5345-34b6-ad53-fd7f10ee149c\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,6,2,'entrada',373.09,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-09','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_9984484f-7136-3b1a-b2c2-712b022d7098\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(7,6,5,'entrada',445.47,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-06-25','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_577fc065-bec2-3b74-8bd9-ba2a239e6fc4\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,1,4,'entrada',370.53,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-06-15','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_f0decc57-2e04-303c-b038-d3ab9141d7de\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(9,2,5,'entrada',364.74,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-06-09','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_4305bc11-791b-37e8-9001-7d1753287afb\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(10,2,3,'entrada',278.38,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-12','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_94f8f135-fe8d-35ba-853c-eb6c2bf4588e\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(11,4,3,'entrada',223.74,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-24','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_089791f5-7509-3460-9c0f-13f4de8be7c8\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(12,10,5,'entrada',36.79,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-06-04','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_ce468d18-c25d-307e-93eb-9b65b4465966\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(13,6,1,'entrada',424.73,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-05-17','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_eaf1289b-6f5d-3493-b510-3a5857d13f42\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(14,10,5,'entrada',357.33,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-05-10','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_52bbb301-561b-3ae8-a583-bbd28238b450\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(15,1,2,'entrada',401.94,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-05-25','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_3c7c7246-3e23-337e-8af7-4bd2285d8f7b\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(16,5,2,'entrada',405.18,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-06-03','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_3715860d-6f4e-3e71-ae26-7e0422c58a9e\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(17,2,2,'entrada',424.50,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-28','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_fbb7491c-1a73-36ea-a091-0fae0a0b505a\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(18,10,2,'entrada',317.00,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-21','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_a24b1e22-2100-3f98-be04-905180a8422a\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(19,1,4,'entrada',96.20,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-06-30','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_9af753d6-4dc6-34b9-bb3b-d83aec8b93b3\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(20,3,2,'entrada',269.21,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-06-02','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_bb739895-b101-32e3-aada-f9fd1945aacb\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(21,4,4,'entrada',355.35,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-05-12','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_bfc3b759-889a-3a27-bc9b-54b445322545\",\"nome_doador\":\"Ana Oliveira Lima\",\"email_doador\":\"ana.lima@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(22,10,1,'entrada',71.72,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-05-30','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_c0ed81be-d7f4-3c53-8902-7ca208adc7e8\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:17','2025-08-04 21:49:17'),(23,10,3,'entrada',356.49,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-05-22','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_eafb9ad8-3346-3cce-aebd-55625dfaeab3\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(24,6,4,'entrada',263.83,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-06-24','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_c3d6e475-5578-3b5a-9370-ffafb6fad352\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(25,1,3,'entrada',100.53,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-30','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_fe831ec3-097b-395f-b702-08c5189319ba\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(26,7,4,'entrada',110.04,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-07-18','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_fd412151-b205-3877-bce8-0c43235176e7\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(27,10,1,'entrada',153.10,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-07-05','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_1322d4a2-d56f-3d0c-9209-2fe01acf3df1\",\"nome_doador\":\"Patricia Lima Santos\",\"email_doador\":\"patricia.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(28,7,1,'entrada',400.19,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-06-08','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_3fd48a24-7b69-39f2-ba35-5f84086e0bc5\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(29,9,1,'entrada',197.55,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-06-04','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_f7aac7d4-1162-3fbc-b87e-abdd1b3c0105\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(30,1,1,'entrada',250.98,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-05-28','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_bf83ee4f-1680-37e0-b641-75fc0fb162db\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(31,5,5,'entrada',181.30,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-06-27','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_9ede2b58-cc30-3b6e-adf9-ee5e2c82eb06\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(32,9,4,'entrada',406.90,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-07-02','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_bb9e71eb-391c-3203-93a1-ee9406e1962f\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(33,7,3,'entrada',284.46,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-07-04','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_a1d688e6-8bda-391f-93d9-44230babe287\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(34,1,3,'entrada',245.60,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-05-22','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_2be606aa-9be9-3f93-9fa2-3ee298f8de52\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(35,3,1,'entrada',262.78,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-05-26','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_4204765e-d51a-3a32-b367-2c96383b4083\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(36,9,3,'entrada',287.95,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-05-18','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_f7db3ba5-6c36-3ab8-a232-1f1f768ba0a6\",\"nome_doador\":\"Marcos Oliveira Costa\",\"email_doador\":\"marcos.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(37,7,1,'entrada',324.65,'Doação para Reforma do Templo',NULL,NULL,NULL,'2025-07-29','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_ee2badc0-df61-37ce-8100-7e3e6b2fcb16\",\"nome_doador\":\"Roberto Almeida Santos\",\"email_doador\":\"roberto.santos@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(38,3,5,'entrada',248.72,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-05-28','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_e42b39c0-d69f-35fc-9158-1a7b16b2c352\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(39,1,5,'entrada',293.33,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-07-28','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_f08ee6eb-fe60-3d50-979d-8fd0c880293a\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(40,1,2,'entrada',340.97,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-03','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_d2332780-55bc-35a7-94f4-f32764b71aa3\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(41,2,2,'entrada',443.94,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-10','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_5ce2fe4e-46ba-39f8-bbb4-6abf47e84791\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(42,8,3,'entrada',442.31,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-25','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_db5e72dc-dc99-3a18-aad3-c8b3be88d4ed\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(43,3,2,'entrada',75.17,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-25','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_0eecb898-3f0a-341f-b108-33e1faad4bb5\",\"nome_doador\":\"Pedro Costa Oliveira\",\"email_doador\":\"pedro.oliveira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(44,1,2,'entrada',17.84,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-05-12','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_d2bfc55d-b75c-3ce5-9146-f7dbb9b27933\",\"nome_doador\":\"Jo\\u00e3o Silva Santos\",\"email_doador\":\"joao.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(45,8,2,'entrada',182.51,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-07-26','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_f5a3a429-162f-354d-8245-3cf47158ac9c\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(46,2,3,'entrada',94.06,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-08','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_b495ec14-b6c1-3487-835b-c77807cf1cd8\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(47,8,4,'entrada',450.09,'Doação para Veículo para Missões',NULL,NULL,NULL,'2025-07-27','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_a1c84073-86b5-32dd-9a8d-b51cf5abbdce\",\"nome_doador\":\"Fernanda Costa Almeida\",\"email_doador\":\"fernanda.almeida@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(48,6,5,'entrada',27.07,'Doação para Sistema de Som',NULL,NULL,NULL,'2025-07-10','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_d2ea3a6a-44a1-3206-a461-0d8016432ff1\",\"nome_doador\":\"Lucia Ferreira Silva\",\"email_doador\":\"lucia.silva@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(49,5,2,'entrada',286.47,'Doação para Ação Social - Natal',NULL,NULL,NULL,'2025-06-16','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_39b8dc0c-2a26-388f-b654-5bab82eeb72c\",\"nome_doador\":\"Carlos Lima Ferreira\",\"email_doador\":\"carlos.ferreira@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(50,2,3,'entrada',223.14,'Doação para Missões Internacionais',NULL,NULL,NULL,'2025-06-02','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_802f9f72-66eb-3cdb-bb92-6822a9651743\",\"nome_doador\":\"Maria Santos Costa\",\"email_doador\":\"maria.costa@email.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(51,NULL,2,'entrada',110.59,'Doação anônima para Ação Social - Natal',NULL,NULL,NULL,'2025-06-28','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_31e55874-f71b-3a8d-97ac-cc62e6c1197a\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(52,NULL,5,'entrada',137.82,'Doação anônima para Sistema de Som',NULL,NULL,NULL,'2025-05-21','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_899054cd-04cb-3a6d-ad05-c5ce92334609\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(53,NULL,3,'entrada',40.42,'Doação anônima para Missões Internacionais',NULL,NULL,NULL,'2025-05-12','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_384051fe-fcd0-32f2-a688-440da798470e\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(54,NULL,4,'entrada',21.18,'Doação anônima para Veículo para Missões',NULL,NULL,NULL,'2025-06-05','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_63d02ee4-6d79-33af-8f04-86d7a9df449a\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(55,NULL,4,'entrada',12.04,'Doação anônima para Veículo para Missões',NULL,NULL,NULL,'2025-06-16','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_d242ef39-fc50-3984-b98e-4a2e828af990\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(56,NULL,4,'entrada',40.66,'Doação anônima para Veículo para Missões',NULL,NULL,NULL,'2025-07-07','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_74b988a3-11df-37c7-a54a-bcea3ff26cef\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(57,NULL,2,'entrada',172.26,'Doação anônima para Ação Social - Natal',NULL,NULL,NULL,'2025-06-13','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_2d9b1cfc-4242-38b8-97b6-b5a2a2f8941f\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(58,NULL,4,'entrada',24.07,'Doação anônima para Veículo para Missões',NULL,NULL,NULL,'2025-07-14','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_7e16259d-a806-3085-b4a8-f157f71d974f\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(59,NULL,5,'entrada',10.20,'Doação anônima para Sistema de Som',NULL,NULL,NULL,'2025-07-07','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_bd12f95d-0733-3a45-9091-077384bf55e9\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(60,NULL,5,'entrada',92.40,'Doação anônima para Sistema de Som',NULL,NULL,NULL,'2025-07-01','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_f23fe682-5161-313a-934f-374e76515484\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(61,NULL,5,'entrada',142.09,'Doação anônima para Sistema de Som',NULL,NULL,NULL,'2025-07-16','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_ed33221b-4648-3c00-8b65-004e6eae02ea\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(62,NULL,5,'entrada',13.63,'Doação anônima para Sistema de Som',NULL,NULL,NULL,'2025-05-19','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_7a7f8ea8-6068-3d17-85f5-bf88dfc76eac\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(63,NULL,2,'entrada',36.35,'Doação anônima para Ação Social - Natal',NULL,NULL,NULL,'2025-06-12','confirmado',NULL,'{\"gateway\":\"mercadopago\",\"payment_id\":\"demo_7dbf18b4-7dee-3beb-b21e-0ab2fb06c2b8\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(64,NULL,4,'entrada',105.02,'Doação anônima para Veículo para Missões',NULL,NULL,NULL,'2025-06-23','confirmado',NULL,'{\"gateway\":\"pix\",\"payment_id\":\"demo_4062b070-bd90-3f51-a5f0-ddf34f7f511a\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(65,NULL,3,'entrada',105.91,'Doação anônima para Missões Internacionais',NULL,NULL,NULL,'2025-06-18','confirmado',NULL,'{\"gateway\":\"stripe\",\"payment_id\":\"demo_a8d6ff1e-f8a0-381a-9f53-0aad5c031dc1\",\"nome_doador\":\"An\\u00f4nimo\",\"email_doador\":\"anonimo@igreja.com\"}','2025-08-04 21:49:18','2025-08-04 21:49:18'),(66,NULL,NULL,'entrada',100.00,'Doação pública',NULL,NULL,NULL,'2025-08-05','pendente',NULL,'{\"nome_doador\":null,\"email_doador\":null,\"tipo_doador\":\"publico\",\"gateway\":\"pix\",\"tipo_destino\":\"igreja\",\"destino_id\":null}','2025-08-05 05:00:30','2025-08-05 05:00:30'),(67,NULL,NULL,'entrada',100.00,'Doação pública',NULL,NULL,NULL,'2025-08-05','confirmado',NULL,'{\"nome_doador\":null,\"email_doador\":null,\"tipo_doador\":\"publico\",\"gateway\":\"pix\",\"tipo_destino\":\"igreja\",\"destino_id\":null,\"data_confirmacao\":\"2025-08-05T05:20:45.098854Z\",\"verificado_em\":\"2025-08-05T05:20:45.099566Z\",\"confirmado_por\":\"verificacao_automatica\"}','2025-08-05 05:02:50','2025-08-05 05:20:45'),(68,NULL,NULL,'entrada',100.00,'Doação pública',NULL,NULL,NULL,'2025-08-05','pendente',NULL,'{\"nome_doador\":null,\"email_doador\":null,\"tipo_doador\":\"publico\",\"gateway\":\"pix\",\"tipo_destino\":\"igreja\",\"destino_id\":null}','2025-08-05 05:04:07','2025-08-05 05:04:07'),(70,NULL,NULL,'entrada',50.00,'Doação pública',NULL,NULL,NULL,'2025-08-05','confirmado',NULL,'{\"nome_doador\":null,\"email_doador\":null,\"tipo_doador\":\"publico\",\"gateway\":\"pix\",\"tipo_destino\":\"igreja\",\"destino_id\":null,\"data_confirmacao\":\"2025-08-05T05:20:45.112961Z\",\"verificado_em\":\"2025-08-05T05:20:45.113056Z\",\"confirmado_por\":\"verificacao_automatica\"}','2025-08-05 05:17:28','2025-08-05 05:20:45'),(79,3,NULL,'entrada',10.00,'Doação de membro','oferta','pix',NULL,'2025-08-05','confirmado',NULL,'{\"tipo_doador\":\"membro\",\"gateway\":\"pix\",\"valor_original\":10}','2025-08-05 14:17:23','2025-08-05 14:30:46'),(80,1,NULL,'dizimo',500.00,'Dízimo mensal de João Silva',NULL,NULL,NULL,'2025-07-31','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(81,36,NULL,'dizimo',300.00,'Dízimo mensal de Maria Santos',NULL,NULL,NULL,'2025-08-01','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(82,37,NULL,'dizimo',200.00,'Dízimo mensal de Pedro Costa',NULL,NULL,NULL,'2025-08-02','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(83,39,NULL,'oferta',50.00,'Oferta especial para manutenção',NULL,NULL,NULL,'2025-08-03','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(84,6,NULL,'oferta',100.00,'Oferta para projetos especiais',NULL,NULL,NULL,'2025-08-04','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(85,44,1,'doacao',100.00,'Doação para reforma do templo',NULL,NULL,NULL,'2025-08-02','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(86,1,7,'doacao',150.00,'Doação para compra de instrumentos',NULL,NULL,NULL,'2025-07-28','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(87,18,2,'doacao',75.00,'Doação para ação social',NULL,NULL,NULL,'2025-07-23','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(88,45,10,'doacao',200.00,'Doação para tecnologia',NULL,NULL,NULL,'2025-08-04','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(89,45,11,'doacao',300.00,'Doação para missões',NULL,NULL,NULL,'2025-07-30','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(90,38,9,'doacao',120.00,'Doação para evangelismo',NULL,NULL,NULL,'2025-08-01','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(91,45,12,'doacao',80.00,'Doação pendente para jovens',NULL,NULL,NULL,'2025-08-05','pendente',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(92,17,NULL,'dizimo',150.00,'Dízimo pendente',NULL,NULL,NULL,'2025-08-05','pendente',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(93,19,13,'doacao',250.00,'Doação cancelada',NULL,NULL,NULL,'2025-07-16','cancelado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(94,23,14,'doacao',1000.00,'Doação para intercessão',NULL,NULL,NULL,'2025-08-04','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(95,19,14,'doacao',500.00,'Doação para intercessão',NULL,NULL,NULL,'2025-08-03','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(96,12,15,'doacao',5000.00,'Doação para hospitalidade',NULL,NULL,NULL,'2025-07-31','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32'),(97,19,15,'doacao',3000.00,'Doação para hospitalidade',NULL,NULL,NULL,'2025-07-26','confirmado',NULL,NULL,'2025-08-06 02:39:32','2025-08-06 02:39:32');
/*!40000 ALTER TABLE `transacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cargo`
--

DROP TABLE IF EXISTS `user_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `cargo_id` bigint(20) unsigned NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_cargo_user_id_cargo_id_unique` (`user_id`,`cargo_id`),
  KEY `user_cargo_user_id_ativo_index` (`user_id`,`ativo`),
  KEY `user_cargo_cargo_id_ativo_index` (`cargo_id`,`ativo`),
  CONSTRAINT `user_cargo_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_cargo_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cargo`
--

LOCK TABLES `user_cargo` WRITE;
/*!40000 ALTER TABLE `user_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `versiculos_favoritos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`versiculos_favoritos`)),
  `historico_leitura` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`historico_leitura`)),
  `configuracoes_notificacao` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracoes_notificacao`)),
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `push_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `public_profile` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@cbav.com','2025-08-06 02:39:49','$2y$12$dNp9GjW3NUu.1smBNqpQeeDRnOkaEoRb0ZieSYRyvz2u.KCfSD99u','users/fotos/apL7vvNlgBwkzxWUwl0S7uC3paDltte1jdmNnMw8.png',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'[{\"referencia\":\"Ef\\u00e9sios 5:24\",\"texto\":\"Mas, assim como a igreja est\\u00e1 sujeita a Cristo, assim tamb\\u00e9m as mulheres o sejam em tudo a seus maridos.\",\"versao\":\"almeida_ra\",\"data\":\"2025-08-06T03:43:57.536704Z\"}]',NULL,NULL,0,0,1,NULL,'2025-08-04 20:38:57','2025-08-06 03:43:57'),(2,'Pastor João Silva','pastor@igreja.com',NULL,'$2y$12$dNp9GjW3NUu.1smBNqpQeeDRnOkaEoRb0ZieSYRyvz2u.KCfSD99u',NULL,'(11) 99999-1111',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(3,'Maria Santos','maria@igreja.com',NULL,'$2y$12$VP17MdCBmpsWkiCYlmNqLerYnZBpG7GDPozLGsPz.tjlas6ixNpVu',NULL,'(11) 99999-2222',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(4,'Pedro Costa','pedro@igreja.com',NULL,'$2y$12$h1YDKI/URH77ZENzTOWUxee.D9qkWd5SXJnpaJZmB2ifxLvTTWeLq',NULL,'(11) 99999-3333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(5,'Ana Oliveira','ana@igreja.com',NULL,'$2y$12$8M6IVqZQTMkLgcbsbn3kHexZN/HZuvdGZMurRKccRiIGI6CTCuuHm',NULL,'(11) 99999-4444',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(6,'Carlos Lima','carlos@igreja.com',NULL,'$2y$12$SOskh1DpGDucwDb02fV5IOn5VhQpBZhgoFniHI5Y5s4cWMzSbir3G',NULL,'(11) 99999-5555',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(7,'Lucia Ferreira','lucia@igreja.com',NULL,'$2y$12$KUqf2/.u91pAPUkToyOpsed2tnzxER/Sz/Di7kd2.Am8tpLukam8C',NULL,'(11) 99999-6666',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 21:49:17','2025-08-04 21:49:17'),(8,'Reinan Rodrigues','admin@teste.com',NULL,'$2y$12$NtpN9BEom4pAsiZOzOWdoenSaVYJk/j/CVwaqvFbSeV9KFug7mFk.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-04 23:35:10','2025-08-04 23:35:10'),(9,'João Silva','membro@teste.com',NULL,'$2y$12$Mo/HCe4Q5D2mnhl9VXCB3.J3WiWEerj0/bc/BTNDtwTz5u9gJPm/e',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-05 03:14:58','2025-08-05 03:14:58'),(10,'Maria Santos','maria@teste.com',NULL,'$2y$12$mlXBURukUwpHkdAZ.RV1w..8SKdCvsOq0nSQxZruJW4oB8IODx7DS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-05 03:14:58','2025-08-05 03:14:58'),(11,'Pedro Intercessor','intercessor@teste.com',NULL,'$2y$12$bnjE1v36mqN3Ogc6JvSzRuAXsa9h8iaNnRZ8T/YXr97ex00VFiNH.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-05 03:14:58','2025-08-05 03:14:58'),(12,'Ana Intercessora','ana.intercessor@teste.com',NULL,'$2y$12$JBMzqErjPh5puGdCI.CpoOWS7.HOMJSvvL2iIO2ZdcMmENIhZjiMq',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-05 03:14:59','2025-08-05 03:14:59'),(13,'Pastor João','pastor@cbav.com','2025-08-06 02:39:49','$2y$12$nIeBWpCe.BQzZvAhQi6vle/uA8uWjG3mIOpQOB8QjGtDGf1Kcjh.i',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(14,'Tesoureiro Maria','tesoureiro@cbav.com','2025-08-06 02:39:49','$2y$12$M09a894Gyrn8NhbUhvyj.e.Hqac5p7f38BufAj8KwaHQq6Li5E64q',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(15,'Membro Teste','membro@cbav.com','2025-08-06 02:39:49','$2y$12$h5rL6uNlUdI98osFlo.fWuZX6jiyMXuAlUKUefhbXqu2mCHN3nXsS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(16,'Ana Costa','ana@cbav.com','2025-08-06 02:39:49','$2y$12$InKIB102OoVtTcdQsZzXAe2dffKptuoWhSWIe16//HI.1.xbbHg/W',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(17,'Pedro Santos','pedro@cbav.com','2025-08-06 02:39:49','$2y$12$fsA6UzYWSTr5XbeFymzzeevB7XVFwjhAvRZyi0fD389LA1F08ouiG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(18,'Lucia Ferreira','lucia@cbav.com','2025-08-06 02:39:49','$2y$12$lX/t71fn/7yMY6opfNZ29uzvf9mHbaoI7H0HCqYb39.pmlh4j/W/q',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(19,'Roberto Lima','roberto@cbav.com','2025-08-06 02:39:49','$2y$12$HvLZ6ItTWu83Ty6iMbEKXun80eupLSoB6Fx3ZI5DEU/cfQli7M902',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(20,'Fernanda Costa','fernanda@cbav.com','2025-08-06 02:39:49','$2y$12$6ZllbGzo8xKyVMN9IW2PtuI8lBlOi91VkpvipUCfBxJ0YD4FiajvG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49'),(21,'Ricardo Almeida','ricardo@cbav.com','2025-08-06 02:39:49','$2y$12$dq8y5RdZKE6I0mMZUeNEiO5w9dQPdOjSvJxHbDiqbcA9kXQiHDJV6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,0,NULL,'2025-08-06 02:39:49','2025-08-06 02:39:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votacao_conselhos`
--

DROP TABLE IF EXISTS `votacao_conselhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votacao_conselhos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conselho_id` bigint(20) unsigned NOT NULL,
  `pauta_id` bigint(20) unsigned DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo_votacao` enum('aprovacao_rejeicao','multipla_escolha','escala') NOT NULL DEFAULT 'aprovacao_rejeicao',
  `opcoes_votacao` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opcoes_votacao`)),
  `votos_favoraveis` int(11) NOT NULL DEFAULT 0,
  `votos_contrarios` int(11) NOT NULL DEFAULT 0,
  `votos_abstencao` int(11) NOT NULL DEFAULT 0,
  `total_votos` int(11) NOT NULL DEFAULT 0,
  `quorum_necessario` int(11) NOT NULL DEFAULT 5,
  `status` enum('pendente','em_andamento','finalizada','cancelada') NOT NULL DEFAULT 'pendente',
  `resultado` enum('aprovado','rejeitado','empate','sem_quorum') DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_inicio` timestamp NULL DEFAULT NULL,
  `data_fim` timestamp NULL DEFAULT NULL,
  `tempo_limite` int(11) DEFAULT NULL,
  `voto_secreto` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `votacao_conselhos_conselho_id_status_index` (`conselho_id`,`status`),
  KEY `votacao_conselhos_pauta_id_index` (`pauta_id`),
  CONSTRAINT `votacao_conselhos_conselho_id_foreign` FOREIGN KEY (`conselho_id`) REFERENCES `conselhos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `votacao_conselhos_pauta_id_foreign` FOREIGN KEY (`pauta_id`) REFERENCES `pauta_conselhos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votacao_conselhos`
--

LOCK TABLES `votacao_conselhos` WRITE;
/*!40000 ALTER TABLE `votacao_conselhos` DISABLE KEYS */;
INSERT INTO `votacao_conselhos` VALUES (1,1,1,'Aprovação do Orçamento Anual 2024','Votação para aprovação do orçamento anual da igreja para o ano de 2024','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',12,0,0,12,8,'finalizada','aprovado','Orçamento aprovado por unanimidade','2024-01-15 23:00:00','2024-01-15 23:30:00',30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(2,1,1,'Eleição de Líderes de Ministérios','Votação para eleição de novos líderes para os ministérios da igreja','multipla_escolha','\"[\\\"Jo\\\\u00e3o Silva\\\",\\\"Maria Santos\\\",\\\"Pedro Oliveira\\\",\\\"Ana Costa\\\"]\"',10,1,1,12,8,'finalizada','aprovado','Todos os líderes eleitos e aprovados','2024-01-15 23:30:00','2024-01-16 00:00:00',30,1,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(3,1,1,'Discussão sobre Reforma do Templo','Votação sobre a necessidade de reforma do templo e possíveis soluções','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',8,2,2,12,8,'finalizada','aprovado','Aprovado com ressalvas, necessário mais estudos técnicos','2024-01-16 00:00:00','2024-01-16 00:15:00',15,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(4,1,1,'Campanha de Reforma do Templo','Votação para aprovação e planejamento da campanha para reforma do templo','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',14,0,1,15,10,'finalizada','aprovado','Campanha aprovada e iniciada com sucesso','2024-02-10 18:00:00','2024-02-10 18:30:00',30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(5,1,1,'Aprovação de Novos Membros','Votação para aprovação de novos membros que solicitaram adesão à igreja','multipla_escolha','\"[\\\"Aprovar Todos\\\",\\\"Aprovar Parcialmente\\\",\\\"Rejeitar\\\"]\"',15,0,0,15,10,'finalizada','aprovado','Todos os membros aprovados por unanimidade','2024-02-10 18:30:00','2024-02-10 19:00:00',30,1,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(6,1,1,'Relatório Financeiro Mensal','Votação para aprovação do relatório financeiro do mês de fevereiro','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',12,0,0,12,8,'finalizada','aprovado','Relatório aprovado por unanimidade','2024-03-15 23:00:00','2024-03-15 23:15:00',15,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(7,1,1,'Aprovação de Eventos','Votação para aprovação de eventos programados para o próximo trimestre','multipla_escolha','\"[\\\"Aprovar Todos\\\",\\\"Aprovar Parcialmente\\\",\\\"Rejeitar\\\"]\"',11,0,1,12,8,'finalizada','aprovado','Todos os eventos aprovados','2024-03-15 23:15:00','2024-03-15 23:45:00',30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(8,1,1,'Discussão sobre Escola Dominical','Votação sobre melhorias na Escola Dominical e novos professores','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',12,0,0,12,8,'finalizada','aprovado','Melhorias aprovadas por unanimidade','2024-03-15 23:45:00','2024-03-16 00:00:00',15,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(9,1,NULL,'Planejamento para 2024','Votação para aprovação do planejamento estratégico para o ano de 2024','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',12,0,0,12,8,'finalizada','aprovado','Planejamento aprovado por unanimidade','2023-12-20 23:00:00','2023-12-20 23:30:00',30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(10,1,NULL,'Aprovação do Orçamento 2024','Votação para aprovação do orçamento para o ano de 2024','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',11,0,1,12,8,'finalizada','aprovado','Orçamento aprovado','2023-12-20 23:30:00','2023-12-21 00:00:00',30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(11,1,NULL,'Eleição de Novos Conselheiros','Votação para eleição de novos conselheiros para o próximo ano','multipla_escolha','\"[\\\"Jo\\\\u00e3o Silva\\\",\\\"Maria Santos\\\",\\\"Pedro Oliveira\\\",\\\"Ana Costa\\\"]\"',12,0,0,12,8,'finalizada','aprovado','Conselheiros eleitos por unanimidade','2023-12-21 00:00:00','2023-12-21 00:30:00',30,1,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(12,1,NULL,'Avaliação dos Ministérios','Votação para avaliação do desempenho dos ministérios no primeiro trimestre','escala','\"[\\\"1 - Insatisfat\\\\u00f3rio\\\",\\\"2 - Regular\\\",\\\"3 - Bom\\\",\\\"4 - Muito Bom\\\",\\\"5 - Excelente\\\"]\"',0,0,0,0,8,'pendente',NULL,'Aguardando reunião',NULL,NULL,30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38'),(13,1,NULL,'Planejamento para o Segundo Trimestre','Votação para planejamento de atividades e eventos para o segundo trimestre','aprovacao_rejeicao','\"[\\\"Aprovar\\\",\\\"Rejeitar\\\"]\"',0,0,0,0,8,'pendente',NULL,'Aguardando reunião',NULL,NULL,30,0,'2025-08-06 02:39:38','2025-08-06 02:39:38');
/*!40000 ALTER TABLE `votacao_conselhos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voto_conselhos`
--

DROP TABLE IF EXISTS `voto_conselhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voto_conselhos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `votacao_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `voto` varchar(255) NOT NULL,
  `justificativa` text DEFAULT NULL,
  `data_voto` timestamp NULL DEFAULT NULL,
  `voto_anonimo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voto_conselhos_votacao_id_user_id_unique` (`votacao_id`,`user_id`),
  KEY `voto_conselhos_votacao_id_voto_index` (`votacao_id`,`voto`),
  KEY `voto_conselhos_user_id_index` (`user_id`),
  CONSTRAINT `voto_conselhos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `voto_conselhos_votacao_id_foreign` FOREIGN KEY (`votacao_id`) REFERENCES `votacao_conselhos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voto_conselhos`
--

LOCK TABLES `voto_conselhos` WRITE;
/*!40000 ALTER TABLE `voto_conselhos` DISABLE KEYS */;
/*!40000 ALTER TABLE `voto_conselhos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-06 22:47:03
