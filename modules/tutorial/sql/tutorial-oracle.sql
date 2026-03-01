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
INSERT INTO tut_cities VALUES ('95920-000','RS','Boqueir�o do Le�o');
INSERT INTO tut_cities VALUES ('95900-000','RS','Lajeado');

INSERT INTO tut_people VALUES ('00000000001','User One','Rua Example, 100','Centro','95880-000','0000-0000','user1@example.com');
INSERT INTO tut_people VALUES ('00000000002','User Two','Rua Example, 200','Centro','95900-000','0000-0000','user2@example.com');

