CREATE TABLE tut_cities (
	zip_code character(9) NOT NULL,
	state character(2) NOT NULL,
	name character varying(80) NOT NULL,
	Constraint tut_cities_pkey Primary Key (zip_code)
);

CREATE TABLE tut_people (
	cpf character varying(14) NOT NULL,
	name character varying(80) NOT NULL,
	address character varying(80) NOT NULL,
	address1 character varying(40) DEFAULT '',
	zip_city character(9),
	phone character varying(30) DEFAULT '',
	email character varying(40) DEFAULT '',
	Constraint tut_people_pkey Primary Key (cpf)
);

INSERT INTO tut_cities VALUES ('95880-000','RS','Estrela');
INSERT INTO tut_cities VALUES ('95885-000','RS','Imigrante');
INSERT INTO tut_cities VALUES ('95920-000','RS','BoqueirÃ£o do LeÃ£o');
INSERT INTO tut_cities VALUES ('95900-000','RS','Lajeado');

INSERT INTO tut_people VALUES ('11111111111','Vilson','Dr. Tostes, 295 - Apto. 1201','Centro','95880-000','9187-3700','vgartner@univates.br');
INSERT INTO tut_people VALUES ('12345','Thomas Spriestersbach','Rua Mario Catoi, 10','Centro','95900-000','3748-3735','ts@interact2000.com.br');

