SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `gordianatlas` ;
CREATE SCHEMA IF NOT EXISTS `gordianatlas` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `gordianatlas` ;

-- -----------------------------------------------------
-- Table `gordianatlas`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`User` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`User` (
  `IdUser` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Email` VARCHAR(255) NULL COMMENT 'A valid email address must be provided by users.' ,
  `Nickname` VARCHAR(45) NULL COMMENT 'The value to display if not for an email address for the user' ,
  `Pass` CHAR(64) NULL COMMENT 'SHA1()\'ed cryptographically generated password.' ,
  `Salt` CHAR(64) NULL COMMENT 'The Length of the plaintext password initially generating the cryptographically generated password.' ,
  `LoginAttempts` TINYINT(1) NULL DEFAULT 0 ,
  `LastAttempt` TIMESTAMP NULL ,
  `Created` TIMESTAMP NULL DEFAULT Now() ,
  `IsAdministrator` TINYINT(1) NULL DEFAULT 0 ,
  PRIMARY KEY (`IdUser`) ,
  UNIQUE INDEX `unique_email` (`Email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Group` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Group` (
  `IdGroup` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(255) NULL ,
  `Content` TINYTEXT NULL ,
  `Status` ENUM('COMPULSORY_PUBLIC', 'DEFAULT', 'PUBLIC', 'MODERATED', 'PRIVATE', 'COMPULSORY_PRIVATE') NULL DEFAULT 'DEFAULT' ,
  PRIMARY KEY (`IdGroup`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Timeline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Timeline` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Timeline` (
  `IdTimeline` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(255) NULL ,
  `Content` TINYTEXT NULL ,
  PRIMARY KEY (`IdTimeline`) ,
  UNIQUE INDEX `Name_UNIQUE` (`Title` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Ip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Ip` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Ip` (
  `Ip` INT(4) UNSIGNED NOT NULL ,
  `LastSeen` TIMESTAMP NULL DEFAULT NOW() ,
  PRIMARY KEY (`Ip`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ModerationIp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ModerationIp` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ModerationIp` (
  `IdModerationIp` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Ip_Ip` INT(4) UNSIGNED NOT NULL ,
  `IsMuted` VARCHAR(45) NULL ,
  `IsBanned` VARCHAR(45) NULL ,
  `AdministrativeNotice` VARCHAR(45) NULL ,
  `OccuredOn` TIMESTAMP NULL ,
  PRIMARY KEY (`IdModerationIp`, `Ip_Ip`) ,
  INDEX `fk_ModerationIp_Ip` (`Ip_Ip` ASC) ,
  CONSTRAINT `fk_ModerationIp_Ip1`
    FOREIGN KEY (`Ip_Ip` )
    REFERENCES `gordianatlas`.`Ip` (`Ip` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`UserHasIp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`UserHasIp` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`UserHasIp` (
  `Ip_Ip` INT(4) UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Ip_Ip`, `User_IdUser`) ,
  INDEX `fk_IpHasUser_User` (`User_IdUser` ASC) ,
  INDEX `fk_IpHasUser_Ip` (`Ip_Ip` ASC) ,
  CONSTRAINT `fk_IpHasUser_Ip1`
    FOREIGN KEY (`Ip_Ip` )
    REFERENCES `gordianatlas`.`Ip` (`Ip` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_IpHasUser_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Icon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Icon` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Icon` (
  `IdIcon` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Path` VARCHAR(255) NULL ,
  `Color` CHAR(6) NULL ,
  PRIMARY KEY (`IdIcon`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Event` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Event` (
  `IdEvent` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Icon_idIcon` INT UNSIGNED NOT NULL ,
  `OccuredOn` CHAR(11) NULL ,
  `OccuredRange` TINYINT NULL ,
  `OccuredDuration` TINYINT UNSIGNED NULL DEFAULT 0 ,
  `OccuredUnit` ENUM('MINUTE', 'HOUR', 'DAY', 'WEEK', 'MONTH', 'YEAR', 'DECADE', 'CENTURY', 'MILLENIA') NULL DEFAULT 'MINUTE' ,
  `CreatedOn` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `EditedOn` TIMESTAMP NULL ,
  PRIMARY KEY (`IdEvent`) ,
  INDEX `fk_Event_Icon` (`Icon_idIcon` ASC) ,
  CONSTRAINT `fk_Event_Icon1`
    FOREIGN KEY (`Icon_idIcon` )
    REFERENCES `gordianatlas`.`Icon` (`IdIcon` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PublicEvent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PublicEvent` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PublicEvent` (
  `IdPublicEvent` INT UNSIGNED NOT NULL ,
  `EventName` TINYTEXT NULL ,
  `OccuredOn` TIMESTAMP NULL ,
  `Description` TEXT NULL ,
  PRIMARY KEY (`IdPublicEvent`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Location` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Location` (
  `IdLocation` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Lat` FLOAT(7,2) NOT NULL ,
  `Lng` FLOAT(7,2) NOT NULL ,
  `Icon_idIcon` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`IdLocation`) ,
  INDEX `fk_Location_Icon` (`Icon_idIcon` ASC) ,
  CONSTRAINT `fk_Location_Icon1`
    FOREIGN KEY (`Icon_idIcon` )
    REFERENCES `gordianatlas`.`Icon` (`IdIcon` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Person` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Person` (
  `IdPerson` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `BirthLocation` INT UNSIGNED NULL ,
  `BirthEvent` DATE NULL ,
  `DeathLocation` INT UNSIGNED NULL ,
  `DeathEvent` DATE NULL ,
  PRIMARY KEY (`IdPerson`) ,
  INDEX `fk_Person_Location_01` (`BirthLocation` ASC) ,
  INDEX `fk_Person_Location_02` (`DeathLocation` ASC) ,
  CONSTRAINT `fk_Person_Location_fk1`
    FOREIGN KEY (`BirthLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Person_Location_fk2`
    FOREIGN KEY (`DeathLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PersonAlias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PersonAlias` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PersonAlias` (
  `IdPersonAlias` INT NOT NULL AUTO_INCREMENT ,
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  `Title` VARCHAR(255) NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`IdPersonAlias`, `Person_IdPerson`) ,
  INDEX `fk_PersonAliases_Person` (`Person_IdPerson` ASC) ,
  CONSTRAINT `fk_PersonAliases_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`GalleryObject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`GalleryObject` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`GalleryObject` (
  `IdGalleryObject` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(255) NULL ,
  `Content` TINYTEXT NULL ,
  `Type` ENUM('Image', 'Audio', 'Video', 'Href') NULL ,
  PRIMARY KEY (`IdGalleryObject`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PersonHasGalleryObject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PersonHasGalleryObject` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PersonHasGalleryObject` (
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  `GalleryObject_idGalleryObject` INT UNSIGNED NOT NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`Person_IdPerson`, `GalleryObject_idGalleryObject`) ,
  INDEX `fk_PersonHasGalleryObject_GalleryObject` (`GalleryObject_idGalleryObject` ASC) ,
  INDEX `fk_PersonHasGalleryObject_Person` (`Person_IdPerson` ASC) ,
  CONSTRAINT `fk_PersonHasGalleryObject_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PersonHasGalleryObject_GalleryObject1`
    FOREIGN KEY (`GalleryObject_idGalleryObject` )
    REFERENCES `gordianatlas`.`GalleryObject` (`IdGalleryObject` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasGalleryObject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasGalleryObject` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasGalleryObject` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `GalleryObject_IdGalleryObject` INT UNSIGNED NOT NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`Event_IdEvent`, `GalleryObject_IdGalleryObject`) ,
  INDEX `fk_EventHasGalleryObject_GalleryObject` (`GalleryObject_IdGalleryObject` ASC) ,
  INDEX `fk_EventHasGalleryObject_Event` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_EventHasGalleryObject_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EventHasGalleryObject_GalleryObject1`
    FOREIGN KEY (`GalleryObject_IdGalleryObject` )
    REFERENCES `gordianatlas`.`GalleryObject` (`IdGalleryObject` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`LocationHasGalleryObject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`LocationHasGalleryObject` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`LocationHasGalleryObject` (
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `GalleryObject_idGalleryObject` INT UNSIGNED NOT NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`Location_IdLocation`, `GalleryObject_idGalleryObject`) ,
  INDEX `fk_LocationHasGalleryObject_GalleryObject` (`GalleryObject_idGalleryObject` ASC) ,
  INDEX `fk_LocationHasGalleryObject_Location` (`Location_IdLocation` ASC) ,
  CONSTRAINT `fk_LocationHasGalleryObject_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LocationHasGalleryObject_GalleryObject1`
    FOREIGN KEY (`GalleryObject_idGalleryObject` )
    REFERENCES `gordianatlas`.`GalleryObject` (`IdGalleryObject` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineHasEvent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineHasEvent` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineHasEvent` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Event_IdEvent`) ,
  INDEX `fk_Timeline_has_Event_Event` (`Event_IdEvent` ASC) ,
  INDEX `fk_Timeline_has_Event_Timeline` (`Timeline_IdTimeline` ASC) ,
  CONSTRAINT `fk_Timeline_has_Event_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Timeline_has_Event_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventAlias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventAlias` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventAlias` (
  `IdEventAlias` INT NOT NULL AUTO_INCREMENT ,
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Title` VARCHAR(255) NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`IdEventAlias`, `Event_IdEvent`) ,
  INDEX `fk_EventAlias_Event` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_EventAlias_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`LocationAlias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`LocationAlias` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`LocationAlias` (
  `IdLocationAlias` INT NOT NULL AUTO_INCREMENT ,
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `Title` VARCHAR(255) NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`IdLocationAlias`) ,
  INDEX `fk_LocationAlias_Location` (`Location_IdLocation` ASC) ,
  CONSTRAINT `fk_LocationAlias_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Concept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Concept` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Concept` (
  `IdConcept` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `CreatedOn` DATETIME NULL ,
  `ModifiedOn` TIMESTAMP NULL ,
  PRIMARY KEY (`IdConcept`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ConceptAlias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ConceptAlias` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ConceptAlias` (
  `IdConceptAlias` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  `Content` VARCHAR(255) NULL ,
  `Ordering` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`IdConceptAlias`, `Concept_IdConcept`) ,
  INDEX `fk_ConceptAlias_Concept` (`Concept_IdConcept` ASC) ,
  CONSTRAINT `fk_ConceptAlias_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`LocationHasConcept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`LocationHasConcept` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`LocationHasConcept` (
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Location_IdLocation`, `Concept_IdConcept`) ,
  INDEX `fk_LocationHasConcept_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `fk_LocationHasConcept_Location` (`Location_IdLocation` ASC) ,
  CONSTRAINT `fk_LocationHasConcept_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LocationHasConcept_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasConcept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasConcept` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasConcept` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Event_IdEvent`, `Concept_IdConcept`) ,
  INDEX `fk_EvenHasConcept_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `fk_EventHasConcept_Event` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_EventHasConcept_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EvenHasConcept_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PersonHasConcept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PersonHasConcept` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PersonHasConcept` (
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Person_IdPerson`, `Concept_IdConcept`) ,
  INDEX `fk_PersonHasConcept_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `fk_PersonHasConcept_Person` (`Person_IdPerson` ASC) ,
  CONSTRAINT `fk_PersonHasConcept_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PersonHasConcept_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`GroupHasTimeline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`GroupHasTimeline` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`GroupHasTimeline` (
  `Group_IdGroup` INT UNSIGNED NOT NULL ,
  `Timeline_TimelineId` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Group_IdGroup`, `Timeline_TimelineId`) ,
  INDEX `fk_Group_has_Timeline_Timeline` (`Timeline_TimelineId` ASC) ,
  INDEX `fk_Group_has_Timeline_Group` (`Group_IdGroup` ASC) ,
  CONSTRAINT `fk_Group_has_Timeline_Group1`
    FOREIGN KEY (`Group_IdGroup` )
    REFERENCES `gordianatlas`.`Group` (`IdGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Group_has_Timeline_Timeline1`
    FOREIGN KEY (`Timeline_TimelineId` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Tag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Tag` (
  `IdTag` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(255) NULL ,
  PRIMARY KEY (`IdTag`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`LocationHasTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`LocationHasTag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`LocationHasTag` (
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Location_IdLocation`, `Tag_IdTag`, `User_IdUser`) ,
  INDEX `fk_LocationHasTag_Tag` (`Tag_IdTag` ASC) ,
  INDEX `fk_LocationHasTag_Location` (`Location_IdLocation` ASC) ,
  INDEX `fk_LocationHasTag_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_LocationHasTag_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LocationHasTag_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LocationHasTag_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasTag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasTag` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Event_IdEvent`, `Tag_IdTag`, `User_IdUser`) ,
  INDEX `fk_EventHasTag_Tag` (`Tag_IdTag` ASC) ,
  INDEX `fk_EventHasTag_Event` (`Event_IdEvent` ASC) ,
  INDEX `fk_EventHasTag_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_EventHasTag_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EventHasTag_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EventHasTag_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ConceptHasTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ConceptHasTag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ConceptHasTag` (
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Concept_IdConcept`, `Tag_IdTag`, `User_IdUser`) ,
  INDEX `fk_ConceptHasTag_Tag` (`Tag_IdTag` ASC) ,
  INDEX `fk_ConceptHasTag_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `fk_ConceptHasTag_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_ConceptHasTag_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ConceptHasTag_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ConceptHasTag_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PersonHasTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PersonHasTag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PersonHasTag` (
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Person_IdPerson`, `Tag_IdTag`, `User_IdUser`) ,
  INDEX `fk_PersonHasTag_Tag` (`Tag_IdTag` ASC) ,
  INDEX `fk_PersonHasTag_Person` (`Person_IdPerson` ASC) ,
  INDEX `fk_PersonHasTag_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_PersonHasTag_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PersonHasTag_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PersonHasTag_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasLocation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasLocation` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasLocation` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Event_IdEvent`, `Location_IdLocation`) ,
  INDEX `fk_EventHasLocation_Location` (`Location_IdLocation` ASC) ,
  INDEX `fk_EventHasLocation_Event` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_EventHasLocation_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EventHasLocation_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`WikiPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`WikiPage` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`WikiPage` (
  `IdWikiPage` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(255) NULL ,
  PRIMARY KEY (`IdWikiPage`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineHasTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineHasTag` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineHasTag` (
  `Timeline_TimelineId` INT UNSIGNED NOT NULL ,
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `User_IdUser` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_TimelineId`, `Tag_IdTag`, `User_IdUser`) ,
  INDEX `fk_TimelineHasTag_Tag` (`Tag_IdTag` ASC) ,
  INDEX `fk_TimelineHasTag_Timeline` (`Timeline_TimelineId` ASC) ,
  INDEX `fk_TimelineHasTag_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_TimelineHasTag_Timeline1`
    FOREIGN KEY (`Timeline_TimelineId` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TimelineHasTag_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TimelineHasTag_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`WikiPageRevision`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`WikiPageRevision` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`WikiPageRevision` (
  `IdWikiPageRevision` INT NOT NULL AUTO_INCREMENT ,
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  `Content` TEXT NULL ,
  PRIMARY KEY (`IdWikiPageRevision`, `WikiPage_IdWikiPage`) ,
  INDEX `fk_WikiPageRevision_WikiPage` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `fk_WikiPageRevision_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineConceptHasWikiPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineConceptHasWikiPage` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineConceptHasWikiPage` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Concept_IdConcept`, `WikiPage_IdWikiPage`) ,
  INDEX `TimelineConceptHasWikiPage_Timeline` (`Timeline_IdTimeline` ASC) ,
  INDEX `TimelineConceptHasWikiPage_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `TimelineConceptHasWikiPage_WikiPage` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `FK_TimelineConceptHasWikiPage_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelineConceptHasWikiPage_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelineConceptHasWikiPage_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelinePersonHasWikiPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelinePersonHasWikiPage` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelinePersonHasWikiPage` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Person_IdPerson`, `WikiPage_IdWikiPage`) ,
  INDEX `TimelineConceptHasWikiPage_Timeline` (`Timeline_IdTimeline` ASC) ,
  INDEX `TimelineConceptHasWikiPage_Concept` (`Person_IdPerson` ASC) ,
  INDEX `TimelineConceptHasWikiPage_WikiPage` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `FK_TimelinePersonHasWikiPage_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelinePersonHasWikiPage_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelinePersonHasWikiPage_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineEventHasWikiPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineEventHasWikiPage` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineEventHasWikiPage` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Event_IdEvent`, `WikiPage_IdWikiPage`) ,
  INDEX `FK_TimelineConceptHasWikiPage_Timeline` (`Timeline_IdTimeline` ASC) ,
  INDEX `FK_TimelineConceptHasWikiPage_Concept` (`Event_IdEvent` ASC) ,
  INDEX `FK_TimelineConceptHasWikiPage_WikiPage` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `FK_TimelineEventHasWikiPage_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelineEventHasWikiPage_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_TimelineEventHasWikiPage_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineLocationHasWikiPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineLocationHasWikiPage` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineLocationHasWikiPage` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Location_IdLocation`, `WikiPage_IdWikiPage`) ,
  INDEX `TimelineLocationHasWikiPage_Timeline` (`Timeline_IdTimeline` ASC) ,
  INDEX `TimelineLocationHasWikiPage_Location` (`Location_IdLocation` ASC) ,
  INDEX `TimelineLocationHasWikiPage_WikiPage` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `fk_TimelineLocationHasWikiPage_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TimelineLocationHasWikiPage_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TimelineLocationHasWikiPage_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`View`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`View` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`View` (
  `IdView` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Group_IdGroup` INT UNSIGNED NOT NULL ,
  `Title` VARCHAR(255) NULL ,
  `Content` TINYTEXT NULL ,
  PRIMARY KEY (`IdView`, `Group_IdGroup`) ,
  INDEX `fk_View_GroupId` (`Group_IdGroup` ASC) ,
  CONSTRAINT `fk_View_GroupId`
    FOREIGN KEY (`Group_IdGroup` )
    REFERENCES `gordianatlas`.`Group` (`IdGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ViewHasTimeline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ViewHasTimeline` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ViewHasTimeline` (
  `View_IdView` INT UNSIGNED NOT NULL ,
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`View_IdView`, `Timeline_IdTimeline`) ,
  INDEX `fk_View_has_Timeline_Timeline` (`Timeline_IdTimeline` ASC) ,
  CONSTRAINT `fk_View_has_Timeline_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`Permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`Permissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`Permissions` (
  `IdPermissions` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `UserUpdatedBy` INT NULL ,
  `OccuredOn` TIMESTAMP NULL ,
  `IsArchived` TINYINT(1) NULL DEFAULT 0 ,
  `IsLocked` TINYINT(1) NULL ,
  `IsGalleryLocked` TINYINT(1) NULL ,
  `Journal` TINYTEXT NULL ,
  PRIMARY KEY (`IdPermissions`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`UserRoleInGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`UserRoleInGroup` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`UserRoleInGroup` (
  `User_IdUser` INT UNSIGNED NOT NULL ,
  `Group_IdGroup` INT UNSIGNED NOT NULL ,
  `Role` ENUM('BANNED','MUZZLED','VIEW','EDIT','OWN') NULL ,
  PRIMARY KEY (`User_IdUser`, `Group_IdGroup`) ,
  INDEX `fk_User_has_Group_Group` (`Group_IdGroup` ASC) ,
  INDEX `fk_User_has_Group_User` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_User_has_Group_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Group_Group1`
    FOREIGN KEY (`Group_IdGroup` )
    REFERENCES `gordianatlas`.`Group` (`IdGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`UserHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`UserHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`UserHasPermissions` (
  `User_IdUser` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`User_IdUser`, `Permissions_IdPermissions`) ,
  INDEX `fk_User_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_User_has_Permissions_User1` (`User_IdUser` ASC) ,
  CONSTRAINT `fk_User_has_Permissions_User1`
    FOREIGN KEY (`User_IdUser` )
    REFERENCES `gordianatlas`.`User` (`IdUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TagHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TagHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TagHasPermissions` (
  `Tag_IdTag` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Tag_IdTag`, `Permissions_IdPermissions`) ,
  INDEX `fk_Tag_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_Tag_has_Permissions_Tag1` (`Tag_IdTag` ASC) ,
  CONSTRAINT `fk_Tag_has_Permissions_Tag1`
    FOREIGN KEY (`Tag_IdTag` )
    REFERENCES `gordianatlas`.`Tag` (`IdTag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tag_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`LocationHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`LocationHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`LocationHasPermissions` (
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Location_IdLocation`, `Permissions_IdPermissions`) ,
  INDEX `fk_Location_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_Location_has_Permissions_Location1` (`Location_IdLocation` ASC) ,
  CONSTRAINT `fk_Location_has_Permissions_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Location_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasPermissions` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Event_IdEvent`, `Permissions_IdPermissions`) ,
  INDEX `fk_Event_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_Event_has_Permissions_Event1` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_Event_has_Permissions_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Event_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`PersonHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`PersonHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`PersonHasPermissions` (
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Permissions_IdPermissions`, `Person_IdPerson`) ,
  INDEX `fk_Permissions_has_Person_Person1` (`Person_IdPerson` ASC) ,
  INDEX `fk_Permissions_has_Person_Permissions1` (`Permissions_IdPermissions` ASC) ,
  CONSTRAINT `fk_Permissions_has_Person_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Permissions_has_Person_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ConceptHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ConceptHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ConceptHasPermissions` (
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Permissions_IdPermissions`, `Concept_IdConcept`) ,
  INDEX `fk_Permissions_has_Concept_Concept1` (`Concept_IdConcept` ASC) ,
  INDEX `fk_Permissions_has_Concept_Permissions1` (`Permissions_IdPermissions` ASC) ,
  CONSTRAINT `fk_Permissions_has_Concept_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Permissions_has_Concept_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`WikiPageHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`WikiPageHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`WikiPageHasPermissions` (
  `WikiPage_IdWikiPage` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`WikiPage_IdWikiPage`, `Permissions_IdPermissions`) ,
  INDEX `fk_WikiPage_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_WikiPage_has_Permissions_WikiPage1` (`WikiPage_IdWikiPage` ASC) ,
  CONSTRAINT `fk_WikiPage_has_Permissions_WikiPage1`
    FOREIGN KEY (`WikiPage_IdWikiPage` )
    REFERENCES `gordianatlas`.`WikiPage` (`IdWikiPage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WikiPage_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineHasPermissions` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Permissions_IdPermissions`) ,
  INDEX `fk_Timeline_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_Timeline_has_Permissions_Timeline1` (`Timeline_IdTimeline` ASC) ,
  CONSTRAINT `fk_Timeline_has_Permissions_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Timeline_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ViewHasPermissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ViewHasPermissions` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ViewHasPermissions` (
  `View_IdView` INT UNSIGNED NOT NULL ,
  `Permissions_IdPermissions` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`View_IdView`, `Permissions_IdPermissions`) ,
  INDEX `fk_View_has_Permissions_Permissions1` (`Permissions_IdPermissions` ASC) ,
  INDEX `fk_View_has_Permissions_View1` (`View_IdView` ASC) ,
  CONSTRAINT `fk_View_has_Permissions_View1`
    FOREIGN KEY (`View_IdView` )
    REFERENCES `gordianatlas`.`View` (`IdView` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_View_has_Permissions_Permissions1`
    FOREIGN KEY (`Permissions_IdPermissions` )
    REFERENCES `gordianatlas`.`Permissions` (`IdPermissions` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`ConceptHasGalleryObject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`ConceptHasGalleryObject` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`ConceptHasGalleryObject` (
  `Concept_IdConcept` INT UNSIGNED NOT NULL ,
  `GalleryObject_IdGalleryObject` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Concept_IdConcept`, `GalleryObject_IdGalleryObject`) ,
  INDEX `ConceptHasGalleryObject_Concept` (`Concept_IdConcept` ASC) ,
  INDEX `ConceptHasGalleryObject_GalleryObject` (`GalleryObject_IdGalleryObject` ASC) ,
  CONSTRAINT `fk_ConceptHasGalleryObject_GalleryObject1`
    FOREIGN KEY (`GalleryObject_IdGalleryObject` )
    REFERENCES `gordianatlas`.`GalleryObject` (`IdGalleryObject` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ConceptHasGalleryObject_Concept1`
    FOREIGN KEY (`Concept_IdConcept` )
    REFERENCES `gordianatlas`.`Concept` (`IdConcept` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`TimelineHasLocation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`TimelineHasLocation` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`TimelineHasLocation` (
  `Timeline_IdTimeline` INT UNSIGNED NOT NULL ,
  `Location_IdLocation` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Timeline_IdTimeline`, `Location_IdLocation`) ,
  INDEX `fk_Timeline_has_Location_Location1` (`Location_IdLocation` ASC) ,
  INDEX `fk_Timeline_has_Location_Timeline1` (`Timeline_IdTimeline` ASC) ,
  CONSTRAINT `fk_Timeline_has_Location_Timeline1`
    FOREIGN KEY (`Timeline_IdTimeline` )
    REFERENCES `gordianatlas`.`Timeline` (`IdTimeline` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Timeline_has_Location_Location1`
    FOREIGN KEY (`Location_IdLocation` )
    REFERENCES `gordianatlas`.`Location` (`IdLocation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gordianatlas`.`EventHasPerson`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gordianatlas`.`EventHasPerson` ;

CREATE  TABLE IF NOT EXISTS `gordianatlas`.`EventHasPerson` (
  `Event_IdEvent` INT UNSIGNED NOT NULL ,
  `Person_IdPerson` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Event_IdEvent`, `Person_IdPerson`) ,
  INDEX `fk_Event_has_Person_Person1` (`Person_IdPerson` ASC) ,
  INDEX `fk_Event_has_Person_Event1` (`Event_IdEvent` ASC) ,
  CONSTRAINT `fk_Event_has_Person_Event1`
    FOREIGN KEY (`Event_IdEvent` )
    REFERENCES `gordianatlas`.`Event` (`IdEvent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Event_has_Person_Person1`
    FOREIGN KEY (`Person_IdPerson` )
    REFERENCES `gordianatlas`.`Person` (`IdPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
