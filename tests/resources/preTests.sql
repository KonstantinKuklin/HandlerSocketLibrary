SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Structure of table `handlersocket`
--

DROP TABLE IF EXISTS `hs`;
CREATE TABLE `hs` (
  `key`     INT(11)             NOT NULL,
  `date`    DATE                NOT NULL,
  `float`   FLOAT               NOT NULL,
  `varchar` VARCHAR(40)         NOT NULL,
  `text`    TEXT                NOT NULL,
  `set`     SET('a', 'b', 'c')  NOT NULL,
  `union`   ENUM('a', 'b', 'c') NOT NULL,
  `null`    INT(11) DEFAULT NULL,
  `num`     INT(11) DEFAULT 0,
  PRIMARY KEY (`key`, `num`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Data of table `handlersocket`
--

INSERT INTO `hs` (`key`, `date`, `float`, `varchar`, `text`, `set`, `union`, `null`, `num`) VALUES
  (1, '0000-00-00', 1, '', '', '', '', NULL, 0),
  (2, '0000-00-00', 2, '', '', '', '', NULL, 0),
  (3, '0000-00-00', 3, '', '', '', '', NULL, 0),
  (4, '0000-00-00', 4, '', '', '', '', NULL, 0),
  (5, '0000-00-00', 5, '', '', '', '', NULL, 0),
  (12, '0000-00-00', 12345, '', '', '', '', NULL, 0),
  (42, '2010-10-29', 3.14159, 'variable length', 'some\r\nbig\r\ntext', 'a,c', 'b', NULL, 0),
  (100, '0000-00-00', 0, '', '', '', '', NULL, 1),
  (101, '0000-00-00', 0, '', 'text101', '', '', null, 3),
  (102, '0000-00-00', 0, '', 'text102', '', '', null, 3),
  (103, '0000-00-00', 0, '', 'text103', '', '', null, 3),
  (104, '0000-00-00', 0, '', 'text104', '', '', null, 10),
  (105, '0000-00-00', 0, '', 'text105', '', '', null, 10),
  (106, '0000-00-00', 0, '', 'text106', '', '', null, 15),
  (107, '0000-00-00', 0, '', 'text107', '', '', null, 15),
  (10001, '2012-01-20', 1, 'text with special chars',
   CONCAT(CHAR(0), CHAR(1), CHAR(2), CHAR(3), CHAR(4), CHAR(5), CHAR(6), CHAR(7), CHAR(8), CHAR(9), CHAR(10), CHAR(11),
          CHAR(12), CHAR(13), CHAR(14), CHAR(15)), '', '', NULL, 0);