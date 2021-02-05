CREATE TABLE tb_gold_log
(
  id      INT UNSIGNED ZEROFILL PRIMARY KEY   NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED ZEROFILL               NOT NULL,
  quant   INT DEFAULT 0                       NOT NULL,
  quando  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  script  VARCHAR(255)                        NOT NULL,
  CONSTRAINT tb_gold_log_tb_usuarios_id_fk FOREIGN KEY (user_id) REFERENCES tb_usuarios (id)
    ON UPDATE CASCADE
);