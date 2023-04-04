-- personale(tipo,mail,nome,cognome,cod_fiscale,note,stato,pw)
-- attestato(mail, tipo, data_scadenza, descrizione)

-- DATABASE
DROP DATABASE IF EXISTS rspp_certificati;

CREATE DATABASE IF NOT EXISTS rspp_certificati;
USE rspp_certificati;

CREATE TABLE personale (
	-- nel campo mail sarà inserita solo la prima parte dell'indirizzo
	-- (es. "vittorio.zhang.stud@tulliobuzzi.edu.it" -> solo "vittorio.zhang.stud")
	-- si da quindi per scontato che il dominio sia "@tulliobuzzi.edu.it"
	mail         VARCHAR(64) PRIMARY KEY NOT NULL, --CHECK(mail NOT LIKE '%.stud')
	tipo         ENUM('ata','docente')   NOT NULL,
	nome         VARCHAR(32)             NOT NULL,
	cognome      VARCHAR(32)             NOT NULL,
	-- il codice fiscale è diverso per ogni paese, perciò ho messo VARCHAR
	cod_fiscale  VARCHAR(32)             NOT NULL,
	note         VARCHAR(128)            NOT NULL DEFAULT '',
        stato 	     ENUM('Da compilare', 'Da validare', 'Validato', 'Richiesta modifica') DEFAULT 'Da compilare',
	pw           VARCHAR(32)             NOT NULL DEFAULT '1234'
);

CREATE TABLE attestato (
	mail VARCHAR(64) NOT NULL,
	tipo ENUM('formazione_generale',
		  'formazione_specifica_medio'
		  'formazione_alto',
		  'formazione_sicurezza_preposto',
		  'aggiornamento_sicurezza_preposto',
		  'aggiornamento_sicurezza',
		  'formazione_rls',
		  'aggiornamento_rls',
		  'formazione_rspp',
		  'aggiornamento_rspp',
		  'formazione_incendio_medio',
		  'formazione_incendio_alto',
		  'formazione_primo_soccorso'
		  'aggiornamento_primo_soccorso',
		  'formazione_blsd',
		  'aggiornamento_blsd',
		  'altro') NOT NULL,
	descrizione   VARCHAR(128),
	data_scadenza DATE,
	PRIMARY KEY(mail,tipo),
	CONSTRAINT attestato_generico_mail
		FOREIGN KEY (mail)
		REFERENCES personale(mail)
);

-- VIEW
CREATE VIEW personale_docente AS
	SELECT p.*
	FROM personale p
	WHERE p.tipo = 'docente';

CREATE VIEW personale_ata AS
	SELECT p.*
	FROM personale p
	WHERE p.tipo = 'ata';

CREATE VIEW personale_da_compilare AS
	SELECT p.*
	FROM personale p
	WHERE p.stato = 'Da compilare';

CREATE VIEW personale_da_validare AS
	SELECT p.*
	FROM personale p
	WHERE p.stato = 'Da validare';
	
CREATE VIEW personale_validato AS
	SELECT p.*
	FROM personale p
	WHERE p.stato = 'Validato';

CREATE VIEW personale_richiesta modifica AS
	SELECT p.*
	FROM personale p
	WHERE p.stato = 'Richiesta modifica';

-- SCADUTO
-- a_s.data_scadenza < CURRENT_DATE;

-- IN SCADENZA			
-- (
-- ((MONTH(a_s.data_scadenza) >= (MONTH(CURRENT_DATE)-2) AND MONTH(a_s.data_scadenza) > 2) AND YEAR(a_s.data_scadenza) = YEAR(CURRENT_DATE)) OR
-- (((MONTH(CURRENT_DATE) = 12) AND MONTH(a_s.data_scadenza) = 2) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza)) OR
-- (((MONTH(CURRENT_DATE) = 11) AND MONTH(a_s.data_scadenza) = 1) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza))
-- )
-- AND a_s.data_scadenza >= CURRENT_DATE;


-- INSERIMENTO DI DATI a caso

INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale, pw)
VALUES  ('ettore.franchi', 'docente', 'Ettore', 'Franchi', 'FRNTTR04R27D612A', '1234'),
	('zhario.zhang', 'ata', 'Zhario', 'Zhang', 'ZHRZNG420CHINA11', '1234'),
	('stefano.hu', 'docente', 'Stefano', 'Hu', 'SFNHU12UU74', '1234'),
	('marco.carricato', 'ata', 'Marco', 'Carricato', 'MRCCRR696969', '1234'),
	('dio.brando', 'docente', 'Dio', 'Brando', 'DIOBRN6629012F', '1234');
