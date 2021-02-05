ALTER TABLE tb_item_reagents
  ADD method VARCHAR(255) NULL;

ALTER TABLE tb_item_reagents
  ADD img_format VARCHAR(5) DEFAULT 'png' NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (66);