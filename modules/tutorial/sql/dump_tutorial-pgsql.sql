--
-- PostgreSQL database dump
--

SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 3 (OID 2200)
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET search_path = public, pg_catalog;

--
-- TOC entry 4 (OID 62849)
-- Name: tut_cities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tut_cities (
    zip_code character(9) NOT NULL,
    state character(2) NOT NULL,
    name character varying(80) NOT NULL
);


--
-- TOC entry 5 (OID 62853)
-- Name: tut_people; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tut_people (
    cpf character varying(12) NOT NULL,
    name character varying(80) NOT NULL,
    address character varying(80) NOT NULL,
    address1 character varying(40) DEFAULT ''::character varying,
    zip_city character(9),
    phone character varying(30) DEFAULT ''::character varying,
    email character varying(40) DEFAULT ''::character varying
);


--
-- Data for TOC entry 8 (OID 62849)
-- Name: tut_cities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tut_cities (zip_code, state, name) FROM stdin;
95880-000	RS	Estrela
95885-000	RS	Imigrante
95920-000	RS	Boqueirão do Leão
95900-000	RS	Lajeado
\.


--
-- Data for TOC entry 9 (OID 62853)
-- Name: tut_people; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tut_people (cpf, name, address, address1, zip_city, phone, email) FROM stdin;
00000000001	User One	Rua Example, 100	Centro	95880-000	0000-0000	user1@example.com
00000000002	User Two	Rua Example, 200	Centro	95900-000	0000-0000	user2@example.com
\.


--
-- TOC entry 6 (OID 62851)
-- Name: tut_cities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tut_cities
    ADD CONSTRAINT tut_cities_pkey PRIMARY KEY (zip_code);


--
-- TOC entry 7 (OID 62858)
-- Name: tut_people_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tut_people
    ADD CONSTRAINT tut_people_pkey PRIMARY KEY (cpf);


--
-- TOC entry 12 (OID 62866)
-- Name: RI_ConstraintTrigger_62866; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>"
    AFTER INSERT OR UPDATE ON tut_people
    FROM tut_cities
    NOT DEFERRABLE INITIALLY IMMEDIATE
    FOR EACH ROW
    EXECUTE PROCEDURE "RI_FKey_check_ins"('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');


--
-- TOC entry 10 (OID 62867)
-- Name: RI_ConstraintTrigger_62867; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>"
    AFTER DELETE ON tut_cities
    FROM tut_people
    NOT DEFERRABLE INITIALLY IMMEDIATE
    FOR EACH ROW
    EXECUTE PROCEDURE "RI_FKey_noaction_del"('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');


--
-- TOC entry 11 (OID 62868)
-- Name: RI_ConstraintTrigger_62868; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE CONSTRAINT TRIGGER "<unnamed>"
    AFTER UPDATE ON tut_cities
    FROM tut_people
    NOT DEFERRABLE INITIALLY IMMEDIATE
    FOR EACH ROW
    EXECUTE PROCEDURE "RI_FKey_noaction_upd"('<unnamed>', 'tut_people', 'tut_cities', 'UNSPECIFIED', 'zip_city', 'zip_code');


--
-- TOC entry 2 (OID 2200)
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


