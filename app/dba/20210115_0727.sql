INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20210115_0727', '0');

INSERT INTO `tb_seg_modulos` (`modu_codigo`, `modu_descripcion`, `modu_prefijo`, `modu_icono`, `fk_par_estados`) VALUES ('5', 'Reportes', 'rep', 'bar-chart', '1')
INSERT INTO `tb_seg_opciones` (`opci_codigo`, `fk_seg_modulos`, `opci_nombre`, `opci_enlace`, `fk_par_estados`) VALUES ('5001', '5', 'Préstamos Nuevos', 'reportes/nuevos', '1')

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20210115_0727';