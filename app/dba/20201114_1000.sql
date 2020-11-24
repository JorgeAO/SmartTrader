CREATE TABLE `bd_smarttrader`.`tb_seg_dba` ( 
    `dba_codigo` INT NOT NULL AUTO_INCREMENT , 
    `dba_id` VARCHAR(20) NOT NULL , 
    `dba_ejecutada` INT NOT NULL DEFAULT '0' , 
    PRIMARY KEY (`dba_codigo`), 
    UNIQUE (`dba_id`)
) ENGINE = InnoDB;

INSERT INTO `tb_seg_dba` 
    (`dba_codigo`, `dba_id`, `dba_ejecutada`) 
VALUES 
    (NULL, '20201114_1000', '0'), 
    (NULL, '20201114_1006', '0');

UPDATE `tb_seg_dba` SET `dba_ejecutada` = '1' WHERE `tb_seg_dba`.`dba_codigo` = 1;