CREATE TABLE IF NOT EXISTS `chat` (
  `id_message` INT(11)   NOT NULL AUTO_INCREMENT,
  `conta_id`   INT(11)            DEFAULT NULL,
  `capitao`    VARCHAR(255)       DEFAULT NULL,
  `message`    VARCHAR(255)       DEFAULT NULL,
  `canal`      VARCHAR(255)       DEFAULT NULL,
  `date`       TIMESTAMP NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1
  AUTO_INCREMENT = 1;

INSERT INTO tb_migrations (cod_migration) VALUE (23);