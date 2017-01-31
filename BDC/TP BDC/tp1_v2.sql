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
--Insertion des données-----------------------------------------------------------------------------
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
INSERT INTO sejour VALUES('10','Passac','01-JUL-1998','2');
INSERT INTO sejour VALUES('30', 'Santalba', '14-AUG-1996','5');
INSERT INTO sejour VALUES('20','Santalba','03-AUG-1998', '4');
INSERT INTO sejour VALUES('30', 'Passac', '15-AUG-1998','3');
INSERT INTO sejour VALUES('30','Venusa','03-AUG-03','3');
INSERT INTO sejour VALUES('20', 'Venusa', '03-AUG-03', '6');
INSERT INTO sejour VALUES('30','Farniente', '03-JUN-24','5');
INSERT INTO sejour VALUES('10','Farniente', '05-SEP-1998','3');

--TRIGGERS//Declencheurs 6 --------------------------------------------------------------------------

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

------7 -------------------------
CREATE OR REPLACE TRIGGER verif_capacite
BEFORE INSERT OR UPDATE OF nbPlaces ON sejour
FOR EACH ROW
DECLARE total_res NUMBER(6); 
		capacite_station NUMBER(6);
BEGIN	
	SELECT CASE WHEN SUM(nbPlaces) is NULL 
				THEN 0
				ELSE SUM(nbPlaces)
			END
	INTO total_res
	FROM sejour s
	WHERE s.station=:NEW.station AND s.debut=:NEW.debut AND
	s.nbPlaces!=:OLD.nbPlaces;
	
	SELECT
	s.capacite INTO capacite_station
	FROM station s 
	WHERE s.nomStation=:NEW.station;
	
	IF((total_res+:NEW.nbPlaces)>=capacite_station)
		THEN RAISE_APPLICATION_ERROR (-20001,'Il ne reste pas assez des places');
	END IF;
END;
/
ALTER TRIGGER verif_capacite DISABLE;
ALTER TRIGGER verif_capacite ENABLE;
DROP TRIGGER verif_capacite;

--Trigger 5.1-----------------------

CREATE OR REPLACE TRIGGER baisse_activite
BEFORE UPDATE ON activite
FOR EACH ROW
WHEN (NEW.prix < OLD.prix)
BEGIN
	UPDATE station SET tarif=tarif+:OLD.prix-:NEW.prix
	WHERE nomStation =:NEW.nomStation;
END;
/

-- 5.3 (a) ALTER TABLE station-----
ALTER TABLE station
ADD nbactivites NUMBER(6) DEFAULT(0);

---TRIGGER 5.3 (b)
CREATE OR REPLACE TRIGGER maj_activites
AFTER INSERT OR UPDATE ON activite
BEGIN 
	UPDATE station s
	SET nbactivites=(SELECT COUNT(*) FROM activite a 
	WHERE s.nomStation = a.nomStation);
END;
/

----VIEWS-----------------------------------------------------------------------------------------
--(a)Represente une partie du schéma
CREATE OR REPLACE VIEW activitesModiques(station, activite) AS
SELECT nomStation, libelle
FROM activite
WHERE prix<140
WITH CHECK OPTION;
/


--(b) VIEW--------
CREATE OR REPLACE VIEW tarifs(station, tarif, optionMin, optionMax) AS
SELECT s.nomStation, s.tarif, MIN(a.prix), MAX(a.prix)
FROM station s, activite a
WHERE s.nomStation=a.nomStation
GROUP BY s.nomStation,s.tarif;

--(c)-------------
CREATE OR REPLACE VIEW reservation (nomStation, PlacesReservees, debut) AS
SELECT station, SUM(nbPlaces), debut
FROM sejour
GROUP BY station, debut;



----3 Manipulation---------------------------------------------------------------------
--a)Nom des stations ayant strictement plus de 200 places. 

SELECT nomStation 
FROM station
WHERE capacite > 200;

--b) Noms des clients dont le nom commence par ’P’ ou dont le solde est supérieur à 10 000. 
SELECT nom 
FROM client
WHERE solde>10000 or nom LIKE 'P%';

--c) Quelles sont les régions dont l’intitulé comprend au moins deux mots ? 
SELECT region 
FROM station
WHERE region LIKE '% %';

--d) Nom des stations qui proposent de la plongée. 
SELECT nomstation
FROM activite
WHERE UPPER(libelle) = 'PLONGEE';
--
update activite set prix = 100 
where prix = 150;
update client set region = 'Amerique'
where id=30;
select * from activitesModiques