-- Insertar roles
INSERT INTO `curso_registro`.`roles` (`nombre`, `descripcion`) VALUES
('administrador', 'Administrador del sistema'),
('instructor', 'Encargado de impartir cursos'),
('secretaria', 'Encargada de la administración general'),
('cliente', 'Persona que se inscribe en los cursos');

-- Insertar usuarios
INSERT INTO `curso_registro`.`usuarios` (`nombre`, `email`, `contraseña`, `id_Rol`) VALUES
('Carla Vaca', 'admin1@example.com', MD5('admin123'), 1), -- Administrador
('Susana Guerra', 'secretaria1@example.com', MD5('secretaria123'), 3); -- Secretaria

-- Insertar instructores
INSERT INTO `curso_registro`.`instructores` (`nombre`, `email`, `telefono`, `roles_id_Rol`) 
VALUES
('Juan Pérez', 'juan.perez@example.com', '1234567890', 2),
('María López', 'maria.lopez@example.com', '2345678901', 2),
('Carlos Gómez', 'carlos.gomez@example.com', '3456789012', 2),
('Ana Martínez', 'ana.martinez@example.com', '4567890123', 2),
('Luis Fernández', 'luis.fernandez@example.com', '5678901234', 2),
('Marta Sánchez', 'marta.sanchez@example.com', '6789012345', 2),
('Roberto Díaz', 'roberto.diaz@example.com', '7890123456', 2),
('Laura Ortiz', 'laura.ortiz@example.com', '8901234567', 2),
('Jorge Ramírez', 'jorge.ramirez@example.com', '9012345678', 2),
('Isabel Torres', 'isabel.torres@example.com', '1123456789', 2),
('Fernando Herrera', 'fernando.herrera@example.com', '2234567890', 2),
('Claudia Moreno', 'claudia.moreno@example.com', '3345678901', 2),
('Ricardo Cruz', 'ricardo.cruz@example.com', '4456789012', 2),
('Patricia Vargas', 'patricia.vargas@example.com', '5567890123', 2),
('Miguel Navarro', 'miguel.navarro@example.com', '6678901234', 2),
('Lucía Flores', 'lucia.flores@example.com', '7789012345', 2),
('Andrés Reyes', 'andres.reyes@example.com', '8890123456', 2),
('Carmen Méndez', 'carmen.mendez@example.com', '9901234567', 2),
('Daniel Soto', 'daniel.soto@example.com', '1234509876', 2),
('Adriana Ríos', 'adriana.rios@example.com', '2345610987', 2);

-- Insertar cursos
INSERT INTO `curso_registro`.`cursos` (`nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `numero_plazas`) 
VALUES
('Introducción a la Programación', 'Curso básico para aprender los fundamentos de la programación.', '2024-01-15', '2024-03-15', 30),
('Inteligencia Artificial (IA)', 'Conceptos básicos y aplicaciones prácticas de la IA.', '2024-02-01', '2024-04-01', 25),
('Diseño Gráfico', 'Fundamentos del diseño gráfico y uso de herramientas como Photoshop e Illustrator.', '2024-01-20', '2024-03-20', 20),
('Desarrollo Web', 'Aprende a crear páginas web con HTML, CSS y JavaScript.', '2024-02-10', '2024-04-10', 40),
('Ciberseguridad Básica', 'Conoce cómo proteger sistemas y redes frente a amenazas digitales.', '2024-03-01', '2024-05-01', 15),
('Bases de Datos', 'Diseño y manejo de bases de datos relacionales con SQL.', '2024-01-25', '2024-03-25', 35),
('Machine Learning', 'Principios de aprendizaje automático y creación de modelos predictivos.', '2024-03-10', '2024-05-10', 20),
('Redes de Computadoras', 'Conceptos y protocolos fundamentales para configurar redes.', '2024-02-15', '2024-04-15', 25),
('Marketing Digital', 'Estrategias para promover productos y servicios en plataformas digitales.', '2024-01-30', '2024-03-30', 30),
('Edición de Video', 'Técnicas y herramientas para editar videos de forma profesional.', '2024-02-05', '2024-04-05', 20),
('Desarrollo de Videojuegos', 'Crea videojuegos con motores como Unity o Unreal Engine.', '2024-03-15', '2024-05-15', 15),
('Python para Ciencia de Datos', 'Uso de Python en el análisis y visualización de datos.', '2024-02-20', '2024-04-20', 30),
('Diseño UX/UI', 'Aprende a diseñar interfaces de usuario intuitivas y atractivas.', '2024-01-15', '2024-03-15', 20),
('Robótica Básica', 'Introducción a la construcción y programación de robots.', '2024-02-25', '2024-04-25', 10),
('Gestión de Proyectos', 'Herramientas y metodologías para liderar proyectos de éxito.', '2024-01-20', '2024-03-20', 25),
('Introducción a la Estadística', 'Conceptos básicos de estadística aplicada.', '2024-02-01', '2024-04-01', 30),
('Realidad Virtual y Aumentada', 'Explora el desarrollo de experiencias inmersivas.', '2024-03-05', '2024-05-05', 15),
('Administración de Servidores', 'Configura y gestiona servidores Linux y Windows.', '2024-02-10', '2024-04-10', 20),
('Contabilidad Básica', 'Aprende los fundamentos de la contabilidad y finanzas.', '2024-01-25', '2024-03-25', 35),
('Análisis Financiero', 'Interpreta datos financieros para la toma de decisiones.', '2024-02-15', '2024-04-15', 30);

-- Asignar cursos a instructores
INSERT INTO `curso_registro`.`cursos_has_instructores` (`id_CursoInstructor`, `id_Curso`, `id_Instructores`) 
VALUES
('CI1', 1, 1),
('CI2', 2, 2),
('CI3', 3, 3),
('CI4', 4, 4),
('CI5', 5, 5),
('CI6', 6, 6),
('CI7', 7, 7),
('CI8', 8, 8),
('CI9', 9, 9),
('CI10', 10, 10),
('CI11', 11, 11),
('CI12', 12, 12),
('CI13', 13, 13),
('CI14', 14, 14),
('CI15', 15, 15),
('CI16', 16, 16),
('CI17', 17, 17),
('CI18', 18, 18),
('CI19', 19, 19),
('CI20', 20, 20);

-- Insertar personas
INSERT INTO `curso_registro`.`personas` (`nombre`, `email`, `telefono`,`id_Rol`) 
VALUES
('Alejandro Silva', 'alejandro.silva@example.com', '3456712345', 4),
('Beatriz Jiménez', 'beatriz.jimenez@example.com', '4567812345', 4),
('Camilo Castro', 'camilo.castro@example.com', '5678912345', 4),
('Diana Paredes', 'diana.paredes@example.com', '6789012345', 4),
('Eduardo Rojas', 'eduardo.rojas@example.com', '7890123456', 4),
('Gabriela Peña', 'gabriela.pena@example.com', '8901234567', 4),
('Hugo García', 'hugo.garcia@example.com', '9012345678', 4),
('Irene Ávila', 'irene.avila@example.com', '1123456789', 4),
('Joaquín Aguilar', 'joaquin.aguilar@example.com', '2234567890', 4),
('Karina Valle', 'karina.valle@example.com', '3345678901', 4),
('Leonardo Vega', 'leonardo.vega@example.com', '4456789012', 4),
('Marina Salinas', 'marina.salinas@example.com', '5567890123', 4),
('Nicolás Estrada', 'nicolas.estrada@example.com', '6678901234', 4),
('Olivia Fuentes', 'olivia.fuentes@example.com', '7789012345', 4),
('Pablo Maldonado', 'pablo.maldonado@example.com', '8890123456', 4),
('Renata Carrillo', 'renata.carrillo@example.com', '9901234567', 4),
('Samuel Espinoza', 'samuel.espinoza@example.com', '1234509876', 4),
('Tatiana León', 'tatiana.leon@example.com', '2345610987', 4),
('Ulises Montes', 'ulises.montes@example.com', '3456712345', 4),
('Valeria Cárdenas', 'valeria.cardenas@example.com', '4567812345', 4);

-- Registrar personas en cursos
INSERT INTO `curso_registro`.`inscripciones_has_persona` (`fecha_inscripcion`, `id_CursoInstructor`, `id_Persona`) 
VALUES
('2024-01-01 10:00:00', 'CI1', 1),
('2024-01-02 11:00:00', 'CI2', 2),
('2024-01-03 12:00:00', 'CI3', 3),
('2024-01-04 13:00:00', 'CI4', 4),
('2024-01-05 14:00:00', 'CI5', 5),
('2024-01-06 15:00:00', 'CI6', 6),
('2024-01-07 16:00:00', 'CI7', 7),
('2024-01-08 17:00:00', 'CI8', 8),
('2024-01-09 18:00:00', 'CI9', 9),
('2024-01-10 19:00:00', 'CI10', 10),

-- Registro adicional para una persona en múltiples cursos
('2024-01-11 10:30:00', 'CI2', 1),
('2024-01-12 11:30:00', 'CI4', 2),
('2024-01-13 12:30:00', 'CI6', 3),
('2024-01-14 13:30:00', 'CI8', 4),
('2024-01-15 14:30:00', 'CI10', 5),

-- Inscripciones aleatorias para otros usuarios
('2024-01-16 10:00:00', 'CI3', 11),
('2024-01-17 11:00:00', 'CI5', 12),
('2024-01-18 12:00:00', 'CI7', 13),
('2024-01-19 13:00:00', 'CI9', 14),
('2024-01-20 14:00:00', 'CI1', 15),

-- Más registros para completar
('2024-01-21 15:00:00', 'CI2', 16),
('2024-01-22 16:00:00', 'CI4', 17),
('2024-01-23 17:00:00', 'CI6', 18),
('2024-01-24 18:00:00', 'CI8', 19),
('2024-01-25 19:00:00', 'CI10', 20);


