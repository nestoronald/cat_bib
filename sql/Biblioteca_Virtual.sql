SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `biblioteca` ;
CREATE SCHEMA IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `biblioteca` ;

-- -----------------------------------------------------
-- Table `biblioteca`.`author`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`author` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`author` (
  `idauthor` INT(12) NOT NULL ,
  `author_name` CHAR(120) NULL ,
  `author_surname` CHAR(120) NULL ,
  `author_enabled` INT(1) NULL ,
  PRIMARY KEY (`idauthor`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`editorial`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`editorial` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`editorial` (
  `ideditorial` INT(12) NOT NULL ,
  `editorial_name` CHAR(120) NULL ,
  `editorial_enabled` INT(1) NULL ,
  PRIMARY KEY (`ideditorial`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`theme`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`theme` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`theme` (
  `idtheme` INT(12) NOT NULL ,
  `theme_description` CHAR(120) NULL ,
  `theme_enabled` INT(1) NULL ,
  PRIMARY KEY (`idtheme`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`book`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`book` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`book` (
  `idbook` INT(12) NOT NULL ,
  `book_data` TEXT NULL ,
  `book_enabled` INT(1) NULL ,
  PRIMARY KEY (`idbook`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`exemplary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`exemplary` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`exemplary` (
  `idexemplary` INT(12) NOT NULL ,
  `exemplary_number` INT(6) NULL ,
  `exemplary_status` INT(1) NULL ,
  `idbook` INT(12) NOT NULL ,
  PRIMARY KEY (`idexemplary`, `idbook`) ,
  INDEX `fk_exemplary_book1` (`idbook` ASC) ,
  CONSTRAINT `fk_exemplary_book1`
    FOREIGN KEY (`idbook` )
    REFERENCES `biblioteca`.`book` (`idbook` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`users` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`users` (
  `idusers` INT(12) NOT NULL ,
  `users_name` CHAR(100) NULL ,
  `users_password` CHAR(100) NULL ,
  `users_email` CHAR(210) NULL ,
  `users_dni` CHAR(8) NULL ,
  `users_telefono` CHAR(21) NULL ,
  `users_domicilio` CHAR(210) NULL ,
  `users_state` INT(1) NULL ,
  PRIMARY KEY (`idusers`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`loan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`loan` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`loan` (
  `idloan` INT(12) NOT NULL ,
  `loan_date` DATETIME NULL ,
  `loan_time` TIME NULL ,
  `loan_theoretical` DATE NULL ,
  `loan_real` DATE NULL ,
  `loan_datereturn` DATE NULL ,
  `loan_datemaxim` DATE NULL ,
  `idusers` INT(12) NOT NULL ,
  `idexemplary` INT(12) NOT NULL ,
  PRIMARY KEY (`idloan`, `idusers`, `idexemplary`) ,
  INDEX `fk_loan_users` (`idusers` ASC) ,
  INDEX `fk_loan_exemplary1` (`idexemplary` ASC) ,
  CONSTRAINT `fk_loan_users`
    FOREIGN KEY (`idusers` )
    REFERENCES `biblioteca`.`users` (`idusers` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_loan_exemplary1`
    FOREIGN KEY (`idexemplary` )
    REFERENCES `biblioteca`.`exemplary` (`idexemplary` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblioteca`.`languaje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `biblioteca`.`languaje` ;

CREATE  TABLE IF NOT EXISTS `biblioteca`.`languaje` (
  `idlanguaje` INT(12) NOT NULL ,
  `languaje_description` CHAR(120) NULL ,
  PRIMARY KEY (`idlanguaje`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
