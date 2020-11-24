ALTER TABLE `tb_pre_prestamos` 
    ADD `pres_frecuencia` CHAR(1) NOT NULL DEFAULT 'M' AFTER `pres_vlr_monto`, 
    ADD `pres_nro_cuotas` INT NOT NULL DEFAULT '1' AFTER `pres_frecuencia`;

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201114_1006';