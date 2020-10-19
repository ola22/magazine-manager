DROP TABLE produkty_zlecen;
DROP TABLE produkty;
DROP TABLE zlecenia;
DROP TABLE kontakty;
DROP TABLE adresy;
DROP TABLE klienci;
DROP TABLE dual;



CREATE TABLE klienci (
    id INTEGER PRIMARY KEY,
    nazwa_firmy VARCHAR(30) NOT NULL UNIQUE,
    imie VARCHAR(20) NOT NULL,
    nazwisko VARCHAR(20) NOT NULL,
    email VARCHAR(30) NOT NULL
);

CREATE TABLE kontakty (
    nr_tel VARCHAR(16) PRIMARY KEY,
    klient INTEGER NOT NULL,
    FOREIGN KEY(klient) REFERENCES klienci(id)
);

CREATE TABLE adresy (
    kraj VARCHAR(20) NOT NULL,
    miasto VARCHAR(20) NOT NULL,
    kod_pocztowy VARCHAR(6) NOT NULL,
    ulica VARCHAR(20),
    nr_domu_mieszkania VARCHAR(8) NOT NULL,
    komentarz VARCHAR(80),
    klient INTEGER NOT NULL,
    FOREIGN KEY(klient) REFERENCES klienci(id)
);

CREATE TABLE produkty (
    nazwa VARCHAR(12) PRIMARY KEY,
    ilosc INTEGER NOT NULL,
    cena REAL NOT NULL
);

CREATE TABLE zlecenia (
    id INTEGER NOT NULL PRIMARY KEY,
    data DATE NOT NULL,
    zysk REAL NOT NULL,
    klient_id INTEGER NOT NULL,
    FOREIGN KEY(klient_id) REFERENCES klienci(id)
);

CREATE TABLE produkty_zlecen (
    ilosc INTEGER NOT NULL,
    id_zlecenia INTEGER NOT NULL,
    produkt VARCHAR(12) NOT NULL,
    FOREIGN KEY(id_zlecenia) REFERENCES zlecenia(id),
    FOREIGN KEY(produkt) REFERENCES produkty(nazwa),
    PRIMARY KEY(id_zlecenia, produkt)
);

CREATE TABLE dual (
 id INTEGER NOT NULL PRIMARY KEY
);

INSERT INTO dual VALUES (1);




-----------------------------------------------------------------




CREATE OR replace TRIGGER usun_klienta_trigger
  BEFORE DELETE ON klienci
  FOR EACH ROW
BEGIN
    DELETE FROM kontakty
    WHERE klient = :old.id;

    DELETE FROM adresy
    WHERE klient = :old.id;

    DELETE FROM zlecenia
    WHERE klient_id = :old.id;
END;
/


CREATE OR replace TRIGGER usun_zlecenie_trigger
  BEFORE DELETE ON zlecenia
  FOR EACH ROW
BEGIN
    UPDATE produkty
    SET ilosc = (ilosc - (SELECT COALESCE(SUM(ilosc), 0)
			 FROM produkty_zlecen
			 WHERE id_zlecenia = :old.id AND produkt = produkty.nazwa));
    
    DELETE FROM produkty_zlecen
    WHERE produkty_zlecen.id_zlecenia = :old.id;
END;
/





DROP VIEW nowe_id_aux;
CREATE VIEW nowe_id_aux AS
    SELECT (t.id + 1) AS nowe_id
    FROM zlecenia t LEFT JOIN zlecenia s ON s.id = (t.id + 1)
    WHERE s.id IS NULL
    ORDER BY t.id;

DROP VIEW nowe_id;
CREATE VIEW nowe_id AS
    SELECT A.* FROM nowe_id_aux A LEFT JOIN nowe_id_aux B ON A.nowe_id > B.nowe_id
    WHERE B.nowe_id IS NULL;

DROP FUNCTION daj_nowe_id;
CREATE OR REPLACE FUNCTION daj_nowe_id RETURN NUMBER IS 
nowe NUMBER;
BEGIN
   SELECT * INTO nowe FROM nowe_id;
   return nowe;
END; 
/




DROP FUNCTION licz_zysk;
CREATE or replace FUNCTION licz_zysk(produkt VARCHAR2, ilosc INTEGER) RETURN REAL IS 
zysk REAL;
cena_ REAL;
BEGIN
    SELECT cena INTO cena_ FROM produkty WHERE produkty.nazwa = produkt;
    zysk := ilosc * cena_;
    RETURN zysk;   
END;
/


DROP FUNCTION ile_brakuje;
CREATE or replace FUNCTION ile_brakuje(produkt VARCHAR2, ilosc INTEGER) RETURN INTEGER IS 
stan_magazynu INTEGER;
brak INTEGER;
BEGIN
    SELECT ilosc INTO stan_magazynu FROM produkty WHERE produkty.nazwa = produkt;
    IF stan_magazynu < ilosc THEN
      brak := ilosc - stan_magazynu;
    ELSE
      brak := 0;
    END IF;
    RETURN brak;   
END;
/



INSERT INTO klienci VALUES (1, 'Tomot', 'Jan', 'Kowalski', 'kowalski@wp.pl');
INSERT INTO klienci VALUES (2, 'Pokot', 'Ewelina', 'Nowak', 'e.nowak@wp.pl');
INSERT INTO klienci VALUES (3, 'Bednar', 'Jan', 'Bednarski', 'j.bednar@wp.pl');
INSERT INTO klienci VALUES (4, 'Ropokot', 'Kamil', 'Kwiatkowski', 'kwiatek@wp.pl');
INSERT INTO klienci VALUES (5, 'Elektroplast', 'Tomasz', 'Wilkowski', 'wilko@wp.pl');

INSERT INTO adresy VALUES ('Polska', 'Warka', '30-001', 'ul.Bosacka', '20/45', 'drugie pietro', 1);
INSERT INTO adresy VALUES ('Polska', 'Radom', '26-600', 'ul.Sportowa', '15', 'brak', 2);
INSERT INTO adresy VALUES ('Polska', 'Jedlnia', '26-624', '', '9', 'brak', 4);
INSERT INTO adresy VALUES ('Polska', 'Kiedrzyn', '26-613', '', '15', 'brak', 3);
INSERT INTO adresy VALUES ('Polska', 'Warszawa', '00-221', 'ul.Belgradzka', '4/30', 'drugie pietro', 5);
INSERT INTO adresy VALUES ('Polska', 'Lublin', '50-001', 'ul.Sucha', '22/3', 'klatka schodowa z przodu budynku', 2);

INSERT INTO produkty VALUES ('FLOP 6', 20000, 2);
INSERT INTO produkty VALUES ('FLOP 7', 100000, 2.10);
INSERT INTO produkty VALUES ('FLOP 8/6', 3000000, 2.19);
INSERT INTO produkty VALUES ('FLOP 9', 24000, 2.11);
INSERT INTO produkty VALUES ('FLOP 12', 1000, 2.13);
INSERT INTO produkty VALUES ('FLOP 13/7', 800, 3.14);

INSERT INTO kontakty VALUES ('+48 502 363 132', 2);
INSERT INTO kontakty VALUES ('+48 444 555 786', 3);
INSERT INTO kontakty VALUES ('+48 789 546 123', 3);
INSERT INTO kontakty VALUES ('+48 212 898 777', 4);
INSERT INTO kontakty VALUES ('+48 503 132 099', 5);
INSERT INTO kontakty VALUES ('+48 513 849 450', 1);
INSERT INTO kontakty VALUES ('+48 881 320 302', 3);

INSERT INTO zlecenia VALUES (1, '20-JAN-19', 2000, 1);
INSERT INTO zlecenia VALUES (2, '25-JAN-19', 2000, 2);
INSERT INTO zlecenia VALUES (3, '22-JAN-19', 2000, 2);
INSERT INTO zlecenia VALUES (4, '22-JAN-19', 2000, 3);

INSERT INTO produkty_zlecen VALUES (10, 1, 'FLOP 6');
INSERT INTO produkty_zlecen VALUES (200, 1, 'FLOP 12');
INSERT INTO produkty_zlecen VALUES (2000, 2, 'FLOP 12');
INSERT INTO produkty_zlecen VALUES (400, 3, 'FLOP 8/6');
INSERT INTO produkty_zlecen VALUES (25000, 4, 'FLOP 9');
INSERT INTO produkty_zlecen VALUES (4, 3, 'FLOP 6');


