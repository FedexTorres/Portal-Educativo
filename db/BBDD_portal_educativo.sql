-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS portal_educativo;
USE portal_educativo;

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT current_timestamp()
);

-- Estructura de tabla para la tabla `permisos`
CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permiso_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Roles
CREATE TABLE roles (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    UNIQUE KEY rol_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `roles_permisos`
CREATE TABLE `roles_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rol_permiso` (`id_rol`,`id_permiso`),
  KEY `id_permiso` (`id_permiso`),
  CONSTRAINT `roles_permisos_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`),
  CONSTRAINT `roles_permisos_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de roles_usuarios (relación entre usuarios y roles)
CREATE TABLE roles_usuarios (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_rol INT(11) NOT NULL,
    id_usuario INT(11) NOT NULL,
    UNIQUE KEY rol_usuario (id_rol, id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Cursos
CREATE TABLE cursos (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    programa_estudios TEXT,
    vacantes INT DEFAULT 0,
    imagen_url VARCHAR(255),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Tabla de Inscripciones (solicitudes de inscripción de estudiantes)
CREATE TABLE inscripciones (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(11) NOT NULL,
    id_curso INT(11) NOT NULL,
    fecha_inscripcion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE,
    UNIQUE (id_usuario, id_curso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Cursos_Usuarios (confirmación de inscripción de estudiantes en cursos)
CREATE TABLE cursos_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE,
    UNIQUE (id_usuario, id_curso)
);

-- Tabla de Material de Estudio
CREATE TABLE materiales_de_estudio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    id_curso INT NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ruta_archivo VARCHAR(255),
    FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabla de Trabajos Prácticos
CREATE TABLE actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    consigna TEXT NOT NULL,
    fecha_limite DATE,
    CONSTRAINT actividad_ibfk_1 FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabla de Entregas (registro de entregas de trabajos prácticos)
CREATE TABLE entregas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_actividad INT NOT NULL,
    numero_entrega INT NOT NULL AUTO_INCREMENT,
    fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ruta_archivo VARCHAR(255),
    CONSTRAINT entrega_usuario_fk FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT entrega_actividad_fk FOREIGN KEY (id_actividad) REFERENCES actividades(id) ON DELETE CASCADE,
    UNIQUE (id_usuario, id_actividad, numero_entrega)
);

-- Tabla de Calificaciones
CREATE TABLE calificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_actividad INT NOT NULL,
    id_usuario INT NOT NULL,
    id_entrega INT NOT NULL,
    calificacion DECIMAL(5, 2),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id`) ON DELETE CASCADE,
    CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_entrega`) REFERENCES `entregas` (`id`) ON DELETE CASCADE,
    UNIQUE (id_entrega, id_usuario)
);

-- Estructura de tabla para la tabla mensajes (con campo booleano 'leido')
CREATE TABLE mensajes (
  id_mensaje INT(11) NOT NULL AUTO_INCREMENT,
  contenido TEXT NOT NULL,
  fecha TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  id_remitente INT(11) NOT NULL,
  id_destinatario INT(11) NOT NULL,
  leido TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id_mensaje),
  KEY id_remitente (id_remitente),
  KEY id_destinatario (id_destinatario),
  CONSTRAINT mensajes_ibfk_1 FOREIGN KEY (id_remitente) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT mensajes_ibfk_2 FOREIGN KEY (id_destinatario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para `asistencias_fechas`
CREATE TABLE `asistencias_fechas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `asistencias_fechas_curso_fk` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para `asistencias_usuarios`
CREATE TABLE `asistencias_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_asistencia_fecha` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` ENUM('Presente', 'Ausente') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_asistencia_fecha` (`id_asistencia_fecha`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `asistencias_usuarios_fecha_fk` FOREIGN KEY (`id_asistencia_fecha`) REFERENCES `asistencias_fechas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asistencias_usuarios_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
