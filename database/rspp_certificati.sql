-- personale(tipo,mail,nome,cognome,cod_fiscale,data_nascita,note)
-- attestato_gen(mail)
-- attestato_spec(mail,data_scadenza)

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
	stato 	     ENUM('Da compilare', 'Da revisionare', 'Revisionato') DEFAULT 'Da compilare'
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


# INSERIMENTO DI DATI a caso

INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('ettore.franchi', 'docente', 'Ettore', 'Franchi', 'FRNTTR04R27D612A', '1980-01-01');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('marco.carricato', 'docente', 'Marco', 'Carricato', 'DIO242SQ1', '1923-01-01');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('jonathan.joestar', 'ata', 'Jonathan', 'Joestar', 'DIO242SQ1SDF2', '1980-04-05');
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, data_nascita)
VALUES ('dio.brando', 'ata', 'Dio', 'Brando', 'SDF2SDFG23SD', '1803-04-01');

INSERT INTO attestato_generico( mail) VALUES ('ettore.franchi');
INSERT INTO attestato_generico( mail) VALUES ('dio.brando');

INSERT INTO attestato_specifico (mail, data_scadenza) VALUES ('marco.carricato', '2004-04-04');
INSERT INTO attestato_specifico (mail, data_scadenza) VALUES ('jonathan.joestar', '2009-01-30');