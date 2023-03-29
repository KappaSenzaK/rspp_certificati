-- personale(tipo,mail,nome,cognome,cod_fiscale,data_nascita,note,stato,password)
-- attestato_gen(mail)
-- attestato_spec(mail,data_scadenza)

-- DATABASE
DROP DATABASE IF EXISTS rspp_certificati;

CREATE DATABASE IF NOT EXISTS rspp_certificati;
USE rspp_certificati;

CREATE TABLE personale (
	-- nel campo mail sarà inserita solo la prima parte dell'indirizzo
	-- (es. "vittorio.zhang.stud@tulliobuzzi.edu.it" -> solo "vittorio.zhang.stud")
	-- si da quindi per scontato che il dominio sia "@tulliobuzzi.edu.it"
	mail         VARCHAR(64) PRIMARY KEY NOT NULL,
	tipo         ENUM('ata','docente')   NOT NULL,
	nome         VARCHAR(32)             NOT NULL,
	cognome      VARCHAR(32)             NOT NULL,
	-- il codice fiscale è diverso per ogni paese, perciò ho messo VARCHAR
	cod_fiscale  VARCHAR(32)             NOT NULL,
	data_nascita DATE                    NOT NULL,
	note         VARCHAR(128)            NOT NULL,
	stato 	     ENUM('Da compilare', 'Da revisionare', 'Revisionato', 'Richiesta modifica') DEFAULT 'Da compilare',
	pw           VARCHAR(32)             NOT NULL DEFAULT '1234'
);

CREATE TABLE attestato_generico (
	mail 	VARCHAR(64) PRIMARY KEY NOT NULL,
	CONSTRAINT attestato_generico_mail
		FOREIGN KEY (mail)
		REFERENCES personale(mail)
);

CREATE TABLE attestato_specifico (
	mail 	      VARCHAR(64) PRIMARY KEY NOT NULL,
	data_scadenza DATE,
	CONSTRAINT attestato_specifico_mail
		FOREIGN KEY (mail)
		REFERENCES personale(mail)
);

-- VIEW
CREATE VIEW personale_generale AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT JOIN attestato_generico  a_g ON a_g.mail = p.mail
		LEFT JOIN attestato_specifico a_s ON a_s.mail = p.mail;

CREATE VIEW personale_docente AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT JOIN attestato_generico  a_g ON a_g.mail = p.mail
		LEFT JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE p.tipo = 'docente';

CREATE VIEW personale_ata AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT JOIN attestato_generico  a_g ON a_g.mail = p.mail
		LEFT JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE p.tipo = 'ata';

CREATE VIEW personale_generale_da_compilare AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT JOIN attestato_generico  a_g ON a_g.mail = p.mail
		LEFT JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE p.stato = 'Da compilare';

CREATE VIEW personale_generale_da_revisionare AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT JOIN attestato_generico  a_g ON a_g.mail = p.mail
		LEFT JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE p.stato = 'Da revisionare';

CREATE VIEW personale_generale_attestato_specifico_scaduto AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT  JOIN attestato_generico  a_g ON a_g.mail = p.mail
		INNER JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE a_s.data_scadenza < CURRENT_DATE;

CREATE VIEW personale_generale_attestato_specifico_in_scadenza AS
	SELECT p.*, a_g.mail AS attestato_generico, a_s.mail AS attestato_specifico, a_s.data_scadenza
	FROM personale p
		LEFT  JOIN attestato_generico  a_g ON a_g.mail = p.mail
		INNER JOIN attestato_specifico a_s ON a_s.mail = p.mail
	WHERE (
			((MONTH(a_s.data_scadenza) >= (MONTH(CURRENT_DATE)-2) AND MONTH(a_s.data_scadenza) > 2) AND YEAR(a_s.data_scadenza) = YEAR(CURRENT_DATE)) OR
		  	(((MONTH(CURRENT_DATE) = 12) AND MONTH(a_s.data_scadenza) = 2) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza)) OR
		  	(((MONTH(CURRENT_DATE) = 11) AND MONTH(a_s.data_scadenza) = 1) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza))
		)
			AND a_s.data_scadenza >= CURRENT_DATE;

-- INSERIMENTO DI DATI a caso

INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('ettore.franchi', 'docente', 'Ettore', 'Franchi', 'FRNTTR04R27D612A', '1980-01-01');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('marco.carricato', 'docente', 'Marco', 'Carricato', 'DIO242SQ1', '1923-01-01');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('jonathan.joestar', 'ata', 'Jonathan', 'Joestar', 'DIO242SQ1SDF2', '1980-04-05');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('dio.brando', 'ata', 'Dio', 'Brando', 'SDF2SDFG23SD', '1803-04-01');
INSERT INTO `personale` (`mail`, `tipo`, `nome`, `cognome`, `cod_fiscale`, `data_nascita`, `stato`)
VALUES ('zhario.zhang', 'ata', 'Zhario', 'Zhang', 'ZHRZHN04CHINA420', '2003-07-09', 'Da compilare');

INSERT INTO attestato_generico(mail) VALUES ('ettore.franchi');
INSERT INTO attestato_generico(mail) VALUES ('dio.brando');
INSERT INTO attestato_generico(mail) VALUES ('zhario.zhang');

INSERT INTO attestato_specifico (mail, data_scadenza) VALUES ('marco.carricato',  '2004-04-04');
INSERT INTO attestato_specifico (mail, data_scadenza) VALUES ('jonathan.joestar', '2009-01-30');
INSERT INTO attestato_specifico (mail, data_scadenza) VALUES ('zhario.zhang', '2023-04-30');
