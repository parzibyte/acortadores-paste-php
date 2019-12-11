CREATE TABLE IF NOT EXISTS sesiones
(
    id            VARCHAR(255)    NOT NULL PRIMARY KEY,
    datos         TEXT            NOT NULL,
    ultimo_acceso BIGINT UNSIGNED NOT NULL
);


CREATE TABLE IF NOT EXISTS sesiones_usuarios
(
    id_sesion  VARCHAR(255)    NOT NULL UNIQUE,
    id_usuario BIGINT UNSIGNED NOT NULL
);

CREATE TABLE usuarios
(
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    administrador   BOOLEAN         NOT NULL DEFAULT FALSE,
    correo          VARCHAR(255)    NOT NULL UNIQUE,
    palabra_secreta VARCHAR(255)    NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE usuarios_no_verificados
(
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    correo          VARCHAR(255)    NOT NULL UNIQUE,
    palabra_secreta VARCHAR(255)    NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE verificaciones_pendientes_usuarios
(
    id                       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    token                    VARCHAR(20)     NOT NULL UNIQUE,
    id_usuario_no_verificado BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_usuario_no_verificado)
        REFERENCES usuarios_no_verificados (id)
        ON DELETE CASCADE
);

CREATE TABLE restablecimientos_passwords_usuarios
(
    token      VARCHAR(20)     NOT NULL UNIQUE,
    id_usuario BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE
);


CREATE TABLE subidas
(
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo      VARCHAR(255)    NOT NULL,
    descripcion TEXT            NOT NULL,
    fecha       DATETIME        NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE enlaces_subidas
(
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_subida       BIGINT UNSIGNED NOT NULL,
    leyenda         VARCHAR(255)    NOT NULL,
    enlace_original VARCHAR(1024)   NOT NULL,
    enlace_acortado VARCHAR(1024)   NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_subida) REFERENCES subidas (id) ON DELETE CASCADE
);

CREATE TABLE acortadores_subidas
(
    id_subida    BIGINT UNSIGNED NOT NULL,
    id_acortador INT(8)          NOT NULL,
    FOREIGN KEY (id_subida) REFERENCES subidas (id) ON DELETE CASCADE
);