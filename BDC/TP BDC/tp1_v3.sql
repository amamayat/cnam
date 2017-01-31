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
INSERT INTO client VALUES('30', 'Kerouac', 'Jack', 'NewYork', 'Amerique', '9812');
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

--e)Donnez les différentes activités.
SELECT DISTINCT libelle AS activite
FROM activite;

--f)Donnez le nom des stations qui se trouvent aux Antilles.
SELECT nomStation
FROM station
WHERE UPPER(region) = 'ANTILLES';

--g)Donnez le libellé et le prix en euros de toutes les activités de la station Santalba.
SELECT libelle, prix
FROM activite
WHERE nomStation='Santalba';

--h)Donnez le nom de chaque station avec pour chacune le nom de ses activités
SELECT nomStation, libelle AS activites
FROM activite;

--i)Les noms des stations où ont séjourné des clients parisiens
SELECT station
FROM sejour s, client c
WHERE s.id=c.id AND c.ville='Paris';

--j)Quelle station pratique le tarif le plus élevé ?
SELECT NomStation
FROM station
WHERE tarif=(Select MAX(tarif) FROM station);

--k) Donnez le nombre de stations, la moyenne des tarifs ainsi que le tarif max et min
SELECT COUNT(nomStation), AVG(tarif),MIN(tarif) AS Minimumm, MAX(tarif) Maximum
FROM station;

--l) Nom des clients qui sont allés à Santalba.
SELECT nom
FROM client c, sejour s
WHERE c.id=s.id and s.station='Santalba';

--m) Donnez les couples de clients qui habitent dans la même région (Attention : un couple doit apparaître une seule fois).
SELECT c1.nom,c2.nom 
FROM client c1, client c2
WHERE c1.id > c2.id AND c1.region = c2.region;

--n) Donnez les noms des régions qu’a visité Mr Pascal.
SELECT DISTINCT s.region
FROM client c, sejour sej, station s
WHERE c.id = sej.id AND s.nomStation = sej.station AND c.nom = 'Pascal';

--o)Donnez les noms des stations visitées par des européens.
SELECT station
FROM sejour s, client c
WHERE s.id=c.id AND s.nomStation<>'Farniente';

--p) Qui n’est pas allé dans la station Farniente ?

SELECT c.nom
FROM client c
WHERE NOT EXISTS (SELECT 'x' FROM sejour s WHERE s.id=c.id AND s.station='Farniente');

--q) Quelles stations ne proposent pas de la plongée ?
SELECT nomStation
FROM activite
WHERE libelle <>'Plongee'
MINUS SELECT nomStation
FROM activite
WHERE libelle='Plongee';

--r) Combien de séjours ont eu lieu à Passac ?
SELECT COUNT(*)
FROM sejour
WHERE station='Passac';

--s) Donnez, pour chaque station, le nombre de séjours qui s’y sont déroulés.

SELECT COUNT(*)NBsejour, station
FROM sejour
GROUP BY station;

--t) Donnez les stations où se sont déroulés au moins 3 séjours.
SELECT COUNT(*)NBsejour, station
FROM sejour
GROUP BY station
HAVING COUNT(*) >2; 

--u) Donnez le nombre de places réservées par client. On intéresse uniquement aux clients ayant réservés plus de 10 places
SELECT id, SUM(Nbplaces)
FROM sejour
GROUP BY id
HAVING SUM(Nbplaces) >10;
 
--v) Donnez la liste des clients qui sont allés dans toutes les stations.
SELECT s.id, c.nom
FROM sejour s, client c 
WHERE s.id = c.id 
GROUP BY s.id,c.nom
HAVING COUNT(DISTINCT station) = (SELECT COUNT(*) FROM station);
--
update activite set prix = 100 
where prix = 150;
update client set region = 'Amerique'
where id=30;
select * from activitesModiques
--hammou.fadili@cnam.fr -->cv