SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Structure of table `hs_test`
--

DROP TABLE IF EXISTS `hs_test`;
CREATE TABLE `hs_test` (
  `key`     INT(11)             NOT NULL,
  `date`    DATE                NOT NULL,
  `float`   FLOAT               NOT NULL,
  `varchar` VARCHAR(40)         NOT NULL,
  `text`    TEXT                NOT NULL,
  `set`     SET('a', 'b', 'c')  NOT NULL,
  `union`   ENUM('a', 'b', 'c') NOT NULL,
  `null`    INT(11) DEFAULT NULL,
  PRIMARY KEY (`key`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Data of table `hs_test`
--

INSERT INTO `hs_test` (`key`, `date`, `float`, `varchar`, `text`, `set`, `union`, `null`) VALUES
  (1, '0000-00-00', 1, '', '', '', '', NULL),
  (2, '0000-00-00', 2, '', '', '', '', NULL),
  (3, '0000-00-00', 3, '', '', '', '', NULL),
  (4, '0000-00-00', 4, '', '', '', '', NULL),
  (12, '0000-00-00', 12345, '', '', '', '', NULL),
  (42, '2010-10-29', 3.14159, 'variable length', 'some\r\nbig\r\ntext', 'a,c', 'b', NULL),
  (100, '0000-00-00', 0, '', '', '', '', NULL),
  (10001, '2012-01-20', 1, 'text with special chars',
   CONCAT(CHAR(0), CHAR(1), CHAR(2), CHAR(3), CHAR(4), CHAR(5), CHAR(6), CHAR(7), CHAR(8), CHAR(9), CHAR(10), CHAR(11),
          CHAR(12), CHAR(13), CHAR(14), CHAR(15)), '', '', NULL);