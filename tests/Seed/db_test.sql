DROP TABLE IF EXISTS `usuari`;
CREATE TABLE `usuari` (
    `userId` int(11) NOT NULL PRIMARY KEY,
    `nom` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL
);