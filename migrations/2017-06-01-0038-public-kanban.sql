CREATE TABLE tb_kanban_item
(
  id            INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  title         TEXT                     NOT NULL,
  description   TEXT                     NOT NULL,
  `column`      INT UNSIGNED DEFAULT 0   NOT NULL,
  tripulacao_id INT UNSIGNED             NOT NULL,
  CONSTRAINT tb_kanban_item_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
);

CREATE TABLE tb_kanban_rate
(
  id             INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  kanban_item_id INT UNSIGNED,
  conta_id       INT UNSIGNED ZEROFILL    NOT NULL,
  rate           INT                      NOT NULL,
  CONSTRAINT tb_kanban_rate_tb_kanban_item_id_fk FOREIGN KEY (kanban_item_id) REFERENCES tb_kanban_item (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_kanban_rate_tb_conta_conta_id_fk FOREIGN KEY (conta_id) REFERENCES tb_conta (conta_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE UNIQUE INDEX tb_kanban_rate_kanban_item_id_conta_id_uindex
  ON tb_kanban_rate (kanban_item_id, conta_id);

INSERT INTO tb_migrations (cod_migration) VALUE (38);