-- seb.balconies definition

CREATE TABLE `balconies` (
  `number` int(11) unsigned NOT NULL,
  `attendant_name` varchar(45) NOT NULL,
  `status` enum('not in service','in service','inactive') NOT NULL,
  PRIMARY KEY (`number`,`attendant_name`),
  UNIQUE KEY `balconies_un` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- seb.tickets definition

CREATE TABLE `tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(11) unsigned NOT NULL,
  `emition_moment` datetime NOT NULL,
  `status` enum('pending','in service','attended') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emition_moment_unq` (`emition_moment`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;


-- seb.services definition

CREATE TABLE `services` (
  `ticket_id` int(11) unsigned NOT NULL,
  `balcony_number` int(11) unsigned NOT NULL,
  `start_moment` datetime DEFAULT NULL,
  `end_moment` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_id`,`balcony_number`),
  UNIQUE KEY `services_un` (`ticket_id`),
  KEY `idx_services_balconies` (`balcony_number`),
  KEY `idx_services_ticket` (`ticket_id`),
  CONSTRAINT `fk_balconies_ticket` FOREIGN KEY (`balcony_number`) REFERENCES `balconies` (`number`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_services_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
