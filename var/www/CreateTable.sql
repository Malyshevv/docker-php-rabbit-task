CREATE TABLE `Consumers` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `msg` varchar(1080) NOT NULL,
                             `code` varchar(108) DEFAULT NULL,
                             `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                             `createdAt` datetime NOT NULL,
                             UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
