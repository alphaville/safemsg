DROP TABLE `message`;
CREATE TABLE `message` (
    `id` VARCHAR(64) NOT NULL COMMENT 'ID of the message',
    `safemsg` MEDIUMTEXT NOT NULL COMMENT 'Safe message',
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation timestamp',
    `bind_ip` TEXT COMMENT 'array of IPs in JSON-Array format',
    PRIMARY KEY (`id`)
)  ENGINE=MYISAM DEFAULT CHARSET=LATIN1 COMMENT='secret messages';

ALTER TABLE `message`
    PARTITION BY KEY()
    PARTITIONS 8;
