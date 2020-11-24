INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20201115_1048', '0');

ALTER TABLE `tb_pre_prestamos` 
    ADD `pres_vlr_saldo` DECIMAL(10,0) NOT NULL AFTER `pres_vlr_pago`;

INSERT INTO `tb_par_estados` (`esta_codigo`, `esta_descripcion`) VALUES (NULL, 'Pagada');

ALTER TABLE `tb_pre_cuotas` CHANGE `fk_par_estados` `fk_par_estados` INT(11) NOT NULL DEFAULT '3';

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201115_1048';