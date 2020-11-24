INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20201116_1007', '0');

ALTER TABLE `tb_pre_prestamos` ADD `pres_tipo` CHAR(1) NOT NULL AFTER `fk_par_clientes`;

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201116_1007';