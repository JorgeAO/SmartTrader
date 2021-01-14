INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20201126_1750', '0');

INSERT INTO `tb_seg_opciones` (`opci_codigo`, `fk_seg_modulos`, `opci_nombre`, `opci_enlace`, `fk_par_estados`) VALUES ('1005', '1', 'DBA', 'seguridad/dba', '1');

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201126_1750';