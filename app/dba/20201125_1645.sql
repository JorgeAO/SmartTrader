INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20201125_1645', '0');

INSERT INTO `tb_par_estados` (`esta_codigo`, `esta_descripcion`) VALUES ('5', 'Atrasada');

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201125_1645';