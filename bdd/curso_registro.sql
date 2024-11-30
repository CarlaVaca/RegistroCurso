CREATE SCHEMA IF NOT EXISTS `curso_registro`
-- -----------------------------------------------------
-- Table `curso_registro`.`cursos`
-- -----------------------------------------------------


CREATE TABLE  `curso_registro`.`cursos` (
  `id_Curso` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `descripcion` TEXT NULL DEFAULT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `numero_plazas` INT NOT NULL,
  PRIMARY KEY (`id_Curso`)
);

-- -----------------------------------------------------
-- Table `curso_registro`.`roles`
-- -----------------------------------------------------


CREATE TABLE  `curso_registro`.`roles` (
  `id_Rol` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_Rol`),
  UNIQUE INDEX `nombre` (`nombre`)
) ;

-- -----------------------------------------------------
-- Table `curso_registro`.`empleados`
-- -----------------------------------------------------


CREATE TABLE  `curso_registro`.`empleados` (
  `id_Empleado` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `contrasena` VARCHAR(10) NOT NULL,
  `roles_id_Rol` INT NOT NULL,
  PRIMARY KEY (`id_Empleado`, `roles_id_Rol`),
  INDEX `fk_instructores_roles1_idx` (`roles_id_Rol`),
  CONSTRAINT `fk_instructores_roles1`
    FOREIGN KEY (`roles_id_Rol`)
    REFERENCES `curso_registro`.`roles` (`id_Rol`)
) ;

-- -----------------------------------------------------
-- Table `curso_registro`.`cursos_has_instructores`
-- -----------------------------------------------------


CREATE TABLE  `curso_registro`.`cursos_has_instructores` (
  `id_CursoInstructor` VARCHAR(45) NOT NULL,
  `id_Curso` INT NOT NULL,
  `id_Empleado` INT NOT NULL,
  PRIMARY KEY (`id_CursoInstructor`, `id_Curso`, `id_Empleado`),
  INDEX `fk_cursos_has_instructores_cursos_idx` (`id_Curso`),
  INDEX `fk_cursos_has_instructores_instructores1_idx` (`id_Empleado`),
  CONSTRAINT `fk_cursos_has_instructores_cursos`
    FOREIGN KEY (`id_Curso`)
    REFERENCES `curso_registro`.`cursos` (`id_Curso`),
  CONSTRAINT `fk_cursos_has_instructores_instructores1`
    FOREIGN KEY (`id_Empleado`)
    REFERENCES `curso_registro`.`empleados` (`id_Empleado`)
) ;

-- -----------------------------------------------------
-- Table `curso_registro`.`registro_personas`
-- -----------------------------------------------------


CREATE TABLE `curso_registro`.`registro_personas` (
  `id_Persona` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `roles_id_Rol` INT NOT NULL,
  `contrasena` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_Persona`, `roles_id_Rol`),
  UNIQUE INDEX `email` (`email`),
  INDEX `fk_personas_roles1_idx` (`roles_id_Rol`),
  CONSTRAINT `fk_personas_roles1`
    FOREIGN KEY (`roles_id_Rol`)
    REFERENCES `curso_registro`.`roles` (`id_Rol`)
) ;

-- -----------------------------------------------------
-- Table `curso_registro`.`inscripciones_has_persona`
-- -----------------------------------------------------


CREATE TABLE `curso_registro`.`inscripciones_has_persona` (
  `id_Inscripcion` INT NOT NULL AUTO_INCREMENT,
  `fecha_inscripcion` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `id_CursoInstructor` VARCHAR(45) NOT NULL,
  `id_Persona` INT NOT NULL,
  PRIMARY KEY (`id_Inscripcion`, `id_CursoInstructor`, `id_Persona`),
  INDEX `fk_inscripciones_cursos_has_instructores1_idx` (`id_CursoInstructor`),
  INDEX `fk_inscripciones_has_persona_personas1_idx` (`id_Persona`),
  CONSTRAINT `fk_inscripciones_cursos_has_instructores1`
    FOREIGN KEY (`id_CursoInstructor`)
    REFERENCES `curso_registro`.`cursos_has_instructores` (`id_CursoInstructor`),
  CONSTRAINT `fk_inscripciones_has_persona_personas1`
    FOREIGN KEY (`id_Persona`)
    REFERENCES `curso_registro`.`registro_personas` (`id_Persona`)
) ;
