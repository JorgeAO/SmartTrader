INSERT INTO `tb_seg_dba` (`dba_codigo`, `dba_id`, `dba_ejecutada`) VALUES (NULL, '20201120_0704', '0');

ALTER TABLE `tb_pre_cuotas` ADD `prcu_vlr_saldo` DECIMAL(10,0) NOT NULL AFTER `prcu_valor`;

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_id` = '20201120_0704';