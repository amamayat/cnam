DELETE sejour;
DELETE client;
DELETE activite;
DELETE station;

CREATE TABLE station (
	nomStation VARCHAR2 (12),
	capacite NUMBER (4) NOT NULL,
	lieu VARCHAR2  (12) NOT NULL,
	region VARCHAR2 (12),
	tarif NUMBER (8,2) DEFAULT 0,
	CONSTRAINT pk_station PRIMARY KEY (nomStation),
	CONSTRAINT une_station UNIQUE (lieu,region),
	CONSTRAINT c_station CHECK (LOWER (region) IN ('ocean indien', 'antilles','europe','ameriques','extreme orient'))
);
CREATE TABLE activite (
	nomStation VARCHAR2(12),
	libelle VARCHAR2(12),
	prix NUMBER (8,2) DEFAULT 0,
	CONSTRAINT pk_activite PRIMARY KEY (nomStation, libelle),
	CONSTRAINT fk_activite FOREIGN KEY (nomStation) REFERENCES station ON DELETE CASCADE
);
CREATE TABLE client(
	id NUMBER (6),
	nom VARCHAR2 (12) NOT NULL,
	prenom VARCHAR2 (12),
	ville VARCHAR2 (12) NOT NULL,
	region VARCHAR (12),
	solde NUMBER (8,2) DEFAULT 0 NOT NULL,
	CONSTRAINT pk_client PRIMARY KEY (id)
);

CREATE TABLE sejour(
	id NUMBER (6),
	station VARCHAR2 (12),
	debut DATE DEFAULT SYSDATE,
	nbPlaces NUMBER (4) NOT NULL,
	CONSTRAINT pk_sejour PRIMARY KEY (id, station, debut),
	CONSTRAINT fk_sejour_station FOREIGN KEY (station) REFERENCES station ON DELETE CASCADE,
	CONSTRAINT fk_sejour_client FOREIGN KEY (id) REFERENCES client(id)
);
--Insertion des données
--*******TABLE STATION
INSERT INTO station (nomStation, capacite, lieu, region, tarif)
VALUES
('Venusa', '350', 'Guadeloupe', 'Antilles', '1200');

INSERT INTO station (nomStation, capacite, lieu, region, tarif)
VALUES
('Farniente', '200', 'Seychelles', 'Ocean Indien', '1500');

INSERT INTO station (nomStation, capacite, lieu, region, tarif)
VALUES
('Santalba', '150', 'Martinique', 'Antilles', '2000');

INSERT INTO station (nomStation, capacite, lieu, region, tarif)
VALUES
('Passac', '400', 'Alpes', 'Europe', '1000');

--*******TABLE ACTIVITE
INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Venusa', 'Voile', '150');

INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Venusa', 'Plongee', '120');

INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Farniente', 'Plongee', '130');

INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Passac', 'Ski', '200');

INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Passac', 'Piscine', '20');

INSERT INTO activite (nomStation, libelle, prix)
VALUES
('Santalba', 'Kayac', '50');

--*******TABLE CLIENT
INSERT INTO client VALUES('10','Fogg','Phileas','Londres', 'Europe', '12465');
INSERT INTO client VALUES('20', 'Pascal', 'Blaise', 'Paris', 'Europe', '6763');
INSERT INTO client VALUES('30', 'Kerouac', 'Jack', 'NewYork', 'Amérique', '9812');
--*******TABLE sejour
INSERT INTO client VALUES('10','Passac','01-JUL-1998','2');
--TRIGGERS//Declencheurs

CREATE OR REPLACE TRIGGER verif_prix_activite
BEFORE INSERT OR UPDATE ON activite
FOR EACH ROW
DECLARE tarif_station NUMBER (8,2);
BEGIN
	SELECT tarif INTO tarif_station
	FROM station s
	WHERE s.nomStation = :NEW.nomStation;
	
	IF((:NEW.prix > tarif_station) OR (:NEW.prix<0)) 
		THEN RAISE_APPLICATION_ERROR (-20000, 'Prix negatif ou superieur au tarif de la station');
	END IF;
END;
/


