connect tutorial;
--
-- TOC Entry ID 2 (OID 5703485)
--
-- Name: tut_cities Type: TABLE Owner: postgres
--

CREATE TABLE tut_cities (
	zip_code char(9) NOT NULL,
	state char(2) NOT NULL,
	name varchar(80) NOT NULL,
	Constraint tut_cities_pkey Primary Key (zip_code)
);

--
-- TOC Entry ID 3 (OID 5703500)
--
-- Name: tut_people Type: TABLE Owner: postgres
--

CREATE TABLE tut_people (
	cpf varchar(14) NOT NULL,
	name varchar(80) NOT NULL,
	address varchar(80) NOT NULL,
	address1 varchar(40) DEFAULT '',
	zip_city char(9),
	phone varchar(30) DEFAULT '',
	email varchar(40) DEFAULT '',
	Constraint tut_people_pkey Primary Key (cpf)
);

--
-- Data for TOC Entry ID 4 (OID 5703485)
--
-- Name: tut_cities Type: TABLE DATA Owner: postgres
--


INSERT INTO tut_cities VALUES ('95880-000','RS','Estrela');
INSERT INTO tut_cities VALUES ('95885-000','RS','Imigrante');
INSERT INTO tut_cities VALUES ('95920-000','RS','BoqueirÃ£o do LeÃ£o');
INSERT INTO tut_cities VALUES ('95900-000','RS','Lajeado');

--
-- Data for TOC Entry ID 5 (OID 5703500)
--
-- Name: tut_people Type: TABLE DATA Owner: postgres
--

INSERT INTO tut_people VALUES ('11111111111','Vilson','Dr. Tostes, 295 - Apto. 1201','Centro','95880-000','9187-3700','vgartner@univates.br');
INSERT INTO tut_people VALUES ('12345','Thomas Spriestersbach','Rua Mario Catoi, 10','Centro','95900-000','3748-3735','ts@interact2000.com.br');
