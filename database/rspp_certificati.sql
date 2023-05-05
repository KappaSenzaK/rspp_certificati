-- personale(tipo,mail,nome,cognome,cod_fiscale,note,stato,pw,in_servizio)
-- attestato(mail, tipo, data_scadenza, descrizione)

-- DATABASE
DROP DATABASE IF EXISTS rspp_certificati;

CREATE DATABASE IF NOT EXISTS rspp_certificati;
USE rspp_certificati;

CREATE TABLE personale (
	-- nel campo mail sarà inserita solo la prima parte dell'indirizzo
	-- (es. "vittorio.zhang.stud@tulliobuzzi.edu.it" -> solo "vittorio.zhang.stud")
	-- si da quindi per scontato che il dominio sia "@tulliobuzzi.edu.it"
	mail         VARCHAR(64) PRIMARY KEY NOT NULL, -- CHECK(mail NOT LIKE '%.stud')
	tipo         ENUM('ata','docente')   NOT NULL,
	nome         VARCHAR(32)             NOT NULL,
	cognome      VARCHAR(32)             NOT NULL,
	-- il codice fiscale è diverso per ogni paese, perciò ho messo VARCHAR
	cod_fiscale  VARCHAR(32)             NOT NULL,
	note         VARCHAR(128)            NOT NULL DEFAULT '',
    stato 	     ENUM('Da compilare', 'Da validare', 'Validato', 'Richiesta modifica') DEFAULT 'Da compilare',
	pw           VARCHAR(32)             NOT NULL DEFAULT '1234', 
	in_servizio  ENUM('s', 'n')			 NOT NULL DEFAULT 's' 
);

CREATE TABLE attestato (
	mail VARCHAR(64) NOT NULL,
	tipo ENUM('Attestato di formazione generale', -- 4h
		  'Attestato di formazione specifica - rischio medio', -- 8h
		  'Attestato di formazione - rischio alto', -- 12h
		  'Attestato di formazione sicurezza per il preposto', -- 8h
		  'Attestato di aggiornamento sicurezza per il preposto', -- 6h 5anni
		  'Attestato di aggiornamento sicurezza', -- 6h 5anni
		  'Attestato di formazione per RLS', -- 32h
		  'Attestato di aggiornamento per RLS', -- 8h 1anno
		  'Attestato di formazione aggiornamento RSPP', -- 40h 5anni
		  'Attestato di formazione per rischio di incendio - rischio medio', -- 12h
		  'Attestato di formazione per rischio di incendio - rischio alto', -- 16h
		  'Attestato di formazione per il primo soccorso', -- 12h
		  'Attestato di aggiornamento per il primo soccorso', -- 4h 3anni
		  'Attestato di formazione BLSD', -- 5h
		  'Attestato di aggiornamento BLSD', -- 3h 2anni
		  'Altro') NOT NULL	   DEFAULT 'Altro',
	descrizione   VARCHAR(128) DEFAULT '',
	data_scadenza DATE 		   DEFAULT NULL,
	PRIMARY KEY(mail,tipo,descrizione),
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

CREATE VIEW personale_richiesta_modifica AS
	SELECT p.*
	FROM personale p
	WHERE p.stato = 'Richiesta modifica';


CREATE VIEW personale_in_scadenza AS
	SELECT p.*
	FROM personale p
	WHERE p.mail IN (
		SELECT p1.mail
		FROM personale p1
		WHERE (
			SELECT COUNT(1)
			FROM personale p2
				INNER JOIN attestato a_s ON a_s.data_scadenza IS NOT NULL AND a_s.mail = p2.mail
			WHERE p2.mail = p1.mail
				AND
				(
				 ((MONTH(a_s.data_scadenza) >= (MONTH(CURRENT_DATE)-2) AND MONTH(a_s.data_scadenza) > 2) AND YEAR(a_s.data_scadenza) = YEAR(CURRENT_DATE)) OR
				 (((MONTH(CURRENT_DATE) = 12) AND MONTH(a_s.data_scadenza) = 2) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza)) OR
				 (((MONTH(CURRENT_DATE) = 11) AND MONTH(a_s.data_scadenza) = 1) AND YEAR(CURRENT_DATE) + 1 = YEAR(a_s.data_scadenza))
				)
				AND a_s.data_scadenza >= CURRENT_DATE
		) > 0
	);
	
CREATE VIEW personale_scaduto AS
	SELECT p.*
	FROM personale p
	WHERE p.mail IN (
		SELECT p1.mail
		FROM personale p1
		WHERE (
			SELECT COUNT(1)
			FROM personale p2
				INNER JOIN attestato a_s ON a_s.data_scadenza IS NOT NULL AND a_s.mail = p2.mail
			WHERE p2.mail = p1.mail
				AND a_s.data_scadenza < CURRENT_DATE
		) > 0
	);
	
-- INSERIMENTO DI DATI a caso
INSERT INTO personale (mail, tipo, nome, cognome, cod_fiscale)
VALUES  ('ettore.franchi', 'docente', 'Ettore', 'Franchi', 'FRNTTR04R27D612A'),
	('zhario.zhang', 'ata', 'Zhario', 'Zhang', 'ZHRZNG420CHINA11'),
	('stefano.hu', 'docente', 'Stefano', 'Hu', 'SFNHU12UU74'),
	('marco.carricato', 'ata', 'Marco', 'Carricato', 'MRCCRR696969'),
	('dio.brando', 'docente', 'Dio', 'Brando', 'DIOBRN6629012F');

INSERT INTO attestato (mail, tipo)
VALUES  ('ettore.franchi', 'formazione_specifica_medio'),
	('ettore.franchi', 'formazione_blsd'),
	('ettore.franchi', 'formazione_generale'),
	('zhario.zhang', 'formazione_generale'),
	('dio.brando', 'formazione_generale'),
	('dio.brando', 'formazione_incendio_medio');

INSERT INTO attestato (mail, tipo, data_scadenza)
VALUES  ('stefano.hu', 'aggiornamento_sicurezza_preposto', '2024-02-01'),
	('dio.brando', 'aggiornamento_rls', '1903-11-09');

INSERT INTO attestato (mail, tipo, descrizione)
VALUES  ('stefano.hu', 'formazione_generale', '現在我有冰淇淋但是');
