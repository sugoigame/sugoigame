ALTER TABLE tb_usuarios
  ADD x INT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_usuarios
  ADD y INT UNSIGNED DEFAULT 0 NOT NULL;

CREATE INDEX tb_usuarios_x_y_index
  ON tb_usuarios (x, y);
ALTER TABLE tb_usuarios
  ADD mar_visivel TINYINT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_usuarios
  ADD navegacao_destino VARCHAR(255) NULL;
ALTER TABLE tb_usuarios
  ADD navegacao_inicio DOUBLE NULL;
ALTER TABLE tb_usuarios
  ADD navegacao_fim DOUBLE NULL;
ALTER TABLE tb_usuario_navio
  ADD ultima_cura BIGINT DEFAULT 0 NULL;
ALTER TABLE tb_usuario_navio
  ADD ultimo_disparo BIGINT DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (100);