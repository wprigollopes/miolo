--
-- TOC Entry ID 2 (OID 5703485)
--
-- Name: tut_cities Type: TABLE Owner: postgres
--

CREATE TABLE "tut_cities" (
	"zip_code" character(9) NOT NULL,
	"state" character(2) NOT NULL,
	"name" character varying(80) NOT NULL,
	Constraint "tut_cities_pkey" Primary Key ("zip_code")
);

--
-- TOC Entry ID 3 (OID 5703500)
--
-- Name: tut_people Type: TABLE Owner: postgres
--

CREATE TABLE "tut_people" (
	"cpf" character varying(14) NOT NULL,
	"name" character varying(80) NOT NULL,
	"address" character varying(80) NOT NULL,
	"address1" character varying(40) DEFAULT '',
	"zip_city" character(9),
	"phone" character varying(30) DEFAULT '',
	"email" character varying(40) DEFAULT '',
	Constraint "tut_people_pkey" Primary Key ("cpf")
);

--
-- Data for TOC Entry ID 4 (OID 5703485)
--
-- Name: tut_cities Type: TABLE DATA Owner: postgres
--


INSERT INTO "tut_cities" VALUES ('95880-000','RS','Estrela');
INSERT INTO "tut_cities" VALUES ('95885-000','RS','Imigrante');
INSERT INTO "tut_cities" VALUES ('95920-000','RS','BoqueirÃ£o do LeÃ£o');
INSERT INTO "tut_cities" VALUES ('95900-000','RS','Lajeado');

--
-- Data for TOC Entry ID 5 (OID 5703500)
--
-- Name: tut_people Type: TABLE DATA Owner: postgres
--

INSERT INTO "tut_people" VALUES ('11111111111','Vilson','Dr. Tostes, 295 - Apto. 1201','Centro','95880-000','9187-3700','vgartner@univates.br');
INSERT INTO "tut_people" VALUES ('12345','Thomas Spriestersbach','Rua Mario Catoi, 10','Centro','95900-000','3748-3735','ts@interact2000.com.br');

--
-- TOC Entry ID 8 (OID 5703529)
--
-- Name: "RI_ConstraintTrigger_5703528" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>" AFTER INSERT OR UPDATE ON "tut_people"  FROM "tut_cities" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_check_ins" ('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');

--
-- TOC Entry ID 6 (OID 5703531)
--
-- Name: "RI_ConstraintTrigger_5703530" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>" AFTER DELETE ON "tut_cities"  FROM "tut_people" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_del" ('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');

--
-- TOC Entry ID 7 (OID 5703533)
--
-- Name: "RI_ConstraintTrigger_5703532" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>" AFTER UPDATE ON "tut_cities"  FROM "tut_people" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_upd" ('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');

