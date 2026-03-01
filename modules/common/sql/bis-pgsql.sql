--
-- PostgreSQL database dump
--

SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 1 (OID 0)
-- Name: bis; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE bis WITH TEMPLATE = template0 ENCODING = 'SQL_ASCII';


\connect bis postgres

--
-- TOC entry 3 (OID 2200)
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET search_path = public, pg_catalog;

--
-- TOC entry 4 (OID 17144)
-- Name: cmn_access; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_access (
    module character varying(20) NOT NULL,
    login character varying(20) NOT NULL,
    "action" character varying(50) NOT NULL,
    fl_access boolean DEFAULT false
);


--
-- TOC entry 10 (OID 17144)
-- Name: cmn_access; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE cmn_access FROM PUBLIC;
GRANT SELECT ON TABLE cmn_access TO PUBLIC;


--
-- TOC entry 11 (OID 17310)
-- Name: cmn_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_users (
    login character varying(20) NOT NULL,
    name character varying(80),
    email character varying(80),
    "password" character varying(40),
    confirm_hash character varying(40),
    nickname character varying(20),
    theme character varying(20),
    groups character varying
);


--
-- TOC entry 12 (OID 17328)
-- Name: cmn_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_groups (
    id character varying(20) NOT NULL,
    description character varying,
    module character varying(20) NOT NULL
);


--
-- TOC entry 13 (OID 17335)
-- Name: cmn_modules; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_modules (
    name character varying(20) NOT NULL,
    description character varying(120) NOT NULL,
    rights text
);


--
-- TOC entry 14 (OID 17342)
-- Name: cmn_sources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_sources (
    id integer NOT NULL,
    "path" character varying(255) NOT NULL
);


--
-- TOC entry 15 (OID 17346)
-- Name: cmn_classes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_classes (
    id integer NOT NULL,
    ref_source integer NOT NULL,
    name character varying(255) NOT NULL
);


--
-- TOC entry 16 (OID 17350)
-- Name: cmn_functions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cmn_functions (
    id integer NOT NULL,
    ref_source integer NOT NULL,
    ref_class integer NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Data for TOC entry 24 (OID 17144)
-- Name: cmn_access; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_access VALUES ('common', 'miolo', 'develop', true);
INSERT INTO cmn_access VALUES ('common', 'miolo', 'admin', true);
INSERT INTO cmn_access VALUES ('common', 'miolo', 'system', true);


--
-- Data for TOC entry 25 (OID 17310)
-- Name: cmn_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_users VALUES ('miolo', 'Miolo', 'miolo@localhost', 'a2a748c9c53cfc96f750245bdbe69ae9', '', NULL, NULL, NULL);


--
-- Data for TOC entry 26 (OID 17328)
-- Name: cmn_groups; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 27 (OID 17335)
-- Name: cmn_modules; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_modules VALUES ('common', 'MIOLO\\''s common module', 'system');


--
-- Data for TOC entry 28 (OID 17342)
-- Name: cmn_sources; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_sources VALUES (1, '/usr/local/miolo/classes/components/weather.class');
INSERT INTO cmn_sources VALUES (2, '/usr/local/miolo/classes/components/counter.class');
INSERT INTO cmn_sources VALUES (3, '/usr/local/miolo/classes/components/ticker.class');
INSERT INTO cmn_sources VALUES (4, '/usr/local/miolo/classes/autocomplete.class');
INSERT INTO cmn_sources VALUES (5, '/usr/local/miolo/classes/business.class');
INSERT INTO cmn_sources VALUES (6, '/usr/local/miolo/classes/context.class');
INSERT INTO cmn_sources VALUES (7, '/usr/local/miolo/classes/database.class');
INSERT INTO cmn_sources VALUES (8, '/usr/local/miolo/classes/development_time.class');
INSERT INTO cmn_sources VALUES (9, '/usr/local/miolo/classes/error.class');
INSERT INTO cmn_sources VALUES (10, '/usr/local/miolo/classes/login.class');
INSERT INTO cmn_sources VALUES (11, '/usr/local/miolo/classes/lookup.class');
INSERT INTO cmn_sources VALUES (12, '/usr/local/miolo/classes/miolo.class');
INSERT INTO cmn_sources VALUES (13, '/usr/local/miolo/classes/ui.class');
INSERT INTO cmn_sources VALUES (14, '/usr/local/miolo/classes/status.class');
INSERT INTO cmn_sources VALUES (15, '/usr/local/miolo/classes/success.class');
INSERT INTO cmn_sources VALUES (16, '/usr/local/miolo/classes/tree.class');
INSERT INTO cmn_sources VALUES (17, '/usr/local/miolo/classes/types.class');
INSERT INTO cmn_sources VALUES (18, '/usr/local/miolo/classes/util.class');
INSERT INTO cmn_sources VALUES (19, '/usr/local/miolo/classes/contrib/barcode.class');
INSERT INTO cmn_sources VALUES (20, '/usr/local/miolo/classes/pslib/pslib.class');
INSERT INTO cmn_sources VALUES (21, '/usr/local/miolo/classes/ui/theme.class');
INSERT INTO cmn_sources VALUES (22, '/usr/local/miolo/classes/ui/form.class');
INSERT INTO cmn_sources VALUES (23, '/usr/local/miolo/classes/ui/indexedform.class');
INSERT INTO cmn_sources VALUES (24, '/usr/local/miolo/classes/ui/inputgrid.class');
INSERT INTO cmn_sources VALUES (25, '/usr/local/miolo/classes/ui/listing.class');
INSERT INTO cmn_sources VALUES (26, '/usr/local/miolo/classes/ui/lookuptheme.class');
INSERT INTO cmn_sources VALUES (27, '/usr/local/miolo/classes/ui/menu.class');
INSERT INTO cmn_sources VALUES (28, '/usr/local/miolo/classes/ui/pagenavigator.class');
INSERT INTO cmn_sources VALUES (29, '/usr/local/miolo/classes/ui/prompt.class');
INSERT INTO cmn_sources VALUES (30, '/usr/local/miolo/classes/ui/statusbar.class');
INSERT INTO cmn_sources VALUES (31, '/usr/local/miolo/classes/ui/tabbedform.class');
INSERT INTO cmn_sources VALUES (32, '/usr/local/miolo/classes/ui/themepainter.class');
INSERT INTO cmn_sources VALUES (33, '/usr/local/miolo/classes/ui/themetoolkit.class');
INSERT INTO cmn_sources VALUES (34, '/usr/local/miolo/classes/ui/tabbedform2.class');
INSERT INTO cmn_sources VALUES (35, '/usr/local/miolo/classes/ui/calendar.class');
INSERT INTO cmn_sources VALUES (36, '/usr/local/miolo/classes/startup.inc');
INSERT INTO cmn_sources VALUES (37, '/usr/local/miolo/classes/database/postgres_connection.class');
INSERT INTO cmn_sources VALUES (38, '/usr/local/miolo/classes/database/postgres_query.class');
INSERT INTO cmn_sources VALUES (39, '/usr/local/miolo/classes/database/mysql_connection.class');
INSERT INTO cmn_sources VALUES (40, '/usr/local/miolo/classes/database/mysql_query.class');
INSERT INTO cmn_sources VALUES (41, '/usr/local/miolo/classes/database/mssql_connection.class');
INSERT INTO cmn_sources VALUES (42, '/usr/local/miolo/classes/database/mssql_query.class');
INSERT INTO cmn_sources VALUES (43, '/usr/local/miolo/modules/common/db/admin.class');
INSERT INTO cmn_sources VALUES (44, '/usr/local/miolo/modules/common/db/autocomplete.class');
INSERT INTO cmn_sources VALUES (45, '/usr/local/miolo/modules/common/db/documentation.class');
INSERT INTO cmn_sources VALUES (46, '/usr/local/miolo/modules/common/db/lookup.class');
INSERT INTO cmn_sources VALUES (47, '/usr/local/miolo/modules/common/db/main.class');
INSERT INTO cmn_sources VALUES (48, '/usr/local/miolo/modules/common/etc/documentation.class');
INSERT INTO cmn_sources VALUES (49, '/usr/local/miolo/modules/common/types.class');
INSERT INTO cmn_sources VALUES (50, '/usr/local/miolo/modules/common/forms/ModuleForm.class');
INSERT INTO cmn_sources VALUES (51, '/usr/local/miolo/modules/common/forms/UserForm.class');
INSERT INTO cmn_sources VALUES (52, '/usr/local/miolo/modules/common/forms/UserPermissionsForm.class');
INSERT INTO cmn_sources VALUES (53, '/usr/local/miolo/modules/common/forms/GroupForm.class');
INSERT INTO cmn_sources VALUES (54, '/usr/local/miolo/modules/common/forms/GroupPermissionsForm.class');
INSERT INTO cmn_sources VALUES (55, '/usr/local/miolo/modules/common/handlers/admin/users_insert.inc');
INSERT INTO cmn_sources VALUES (56, '/usr/local/miolo/modules/common/handlers/admin/modules_delete.inc');
INSERT INTO cmn_sources VALUES (57, '/usr/local/miolo/modules/common/handlers/admin/modules_insert.inc');
INSERT INTO cmn_sources VALUES (58, '/usr/local/miolo/modules/common/handlers/admin/modules_list.inc');
INSERT INTO cmn_sources VALUES (59, '/usr/local/miolo/modules/common/handlers/admin/modules_update.inc');
INSERT INTO cmn_sources VALUES (60, '/usr/local/miolo/modules/common/handlers/admin/users_delete.inc');
INSERT INTO cmn_sources VALUES (61, '/usr/local/miolo/modules/common/handlers/admin/users_list.inc');
INSERT INTO cmn_sources VALUES (62, '/usr/local/miolo/modules/common/handlers/admin/users_perms.inc');
INSERT INTO cmn_sources VALUES (63, '/usr/local/miolo/modules/common/handlers/admin/users_update.inc');
INSERT INTO cmn_sources VALUES (64, '/usr/local/miolo/modules/common/handlers/admin/update_passwd_md5.inc');
INSERT INTO cmn_sources VALUES (65, '/usr/local/miolo/modules/common/handlers/admin/groups_delete.inc');
INSERT INTO cmn_sources VALUES (66, '/usr/local/miolo/modules/common/handlers/admin/groups_insert.inc');
INSERT INTO cmn_sources VALUES (67, '/usr/local/miolo/modules/common/handlers/admin/groups_list.inc');
INSERT INTO cmn_sources VALUES (68, '/usr/local/miolo/modules/common/handlers/admin/groups_perms.inc');
INSERT INTO cmn_sources VALUES (69, '/usr/local/miolo/modules/common/handlers/admin/groups_update.inc');
INSERT INTO cmn_sources VALUES (70, '/usr/local/miolo/modules/common/handlers/admin.inc');
INSERT INTO cmn_sources VALUES (71, '/usr/local/miolo/modules/common/handlers/doc.inc');
INSERT INTO cmn_sources VALUES (72, '/usr/local/miolo/modules/common/handlers/login.inc');
INSERT INTO cmn_sources VALUES (73, '/usr/local/miolo/modules/common/handlers/logout.inc');
INSERT INTO cmn_sources VALUES (74, '/usr/local/miolo/modules/common/handlers/lookup.inc');
INSERT INTO cmn_sources VALUES (75, '/usr/local/miolo/modules/common/handlers/main.inc');
INSERT INTO cmn_sources VALUES (76, '/usr/local/miolo/modules/common/handlers/doc/file.inc');
INSERT INTO cmn_sources VALUES (77, '/usr/local/miolo/modules/common/handlers/doc/generate.inc');
INSERT INTO cmn_sources VALUES (78, '/usr/local/miolo/modules/common/handlers/doc/index.inc');
INSERT INTO cmn_sources VALUES (79, '/usr/local/miolo/modules/common/handlers/doc/source.inc');
INSERT INTO cmn_sources VALUES (80, '/usr/local/miolo/modules/common/handlers/doc/topic.inc');
INSERT INTO cmn_sources VALUES (81, '/usr/local/miolo/modules/common/handlers/password.inc');


--
-- Data for TOC entry 29 (OID 17346)
-- Name: cmn_classes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_classes VALUES (1, 1, 'Weather');
INSERT INTO cmn_classes VALUES (2, 2, 'Counter');
INSERT INTO cmn_classes VALUES (3, 3, 'Ticker');
INSERT INTO cmn_classes VALUES (4, 4, 'AutoComplete');
INSERT INTO cmn_classes VALUES (5, 5, 'Business');
INSERT INTO cmn_classes VALUES (6, 6, 'Context');
INSERT INTO cmn_classes VALUES (7, 7, 'Database');
INSERT INTO cmn_classes VALUES (8, 8, 'DevelopmentTime');
INSERT INTO cmn_classes VALUES (9, 9, 'Error');
INSERT INTO cmn_classes VALUES (10, 10, 'Login');
INSERT INTO cmn_classes VALUES (11, 11, 'Lookup');
INSERT INTO cmn_classes VALUES (12, 12, 'MIOLO');
INSERT INTO cmn_classes VALUES (13, 13, 'UI');
INSERT INTO cmn_classes VALUES (14, 14, 'MioloStatus');
INSERT INTO cmn_classes VALUES (15, 15, 'Success');
INSERT INTO cmn_classes VALUES (16, 16, 'Tree');
INSERT INTO cmn_classes VALUES (17, 17, 'QueryRange');
INSERT INTO cmn_classes VALUES (18, 18, 'VarDump');
INSERT INTO cmn_classes VALUES (19, 18, 'InvertDate');
INSERT INTO cmn_classes VALUES (20, 18, 'FormatValue');
INSERT INTO cmn_classes VALUES (21, 19, 'BarcodeI25');
INSERT INTO cmn_classes VALUES (22, 20, 'postscript');
INSERT INTO cmn_classes VALUES (23, 21, 'Theme');
INSERT INTO cmn_classes VALUES (24, 21, 'ThemeElement');
INSERT INTO cmn_classes VALUES (25, 21, 'Container');
INSERT INTO cmn_classes VALUES (26, 21, 'Content');
INSERT INTO cmn_classes VALUES (27, 21, 'FileContent');
INSERT INTO cmn_classes VALUES (28, 21, 'ThemeMenu');
INSERT INTO cmn_classes VALUES (29, 21, 'ThemeBox');
INSERT INTO cmn_classes VALUES (30, 22, 'Form');
INSERT INTO cmn_classes VALUES (31, 22, 'FormData');
INSERT INTO cmn_classes VALUES (32, 22, 'FormField');
INSERT INTO cmn_classes VALUES (33, 22, 'FormButton');
INSERT INTO cmn_classes VALUES (34, 22, 'Separator');
INSERT INTO cmn_classes VALUES (35, 22, 'Text');
INSERT INTO cmn_classes VALUES (36, 22, 'TextLabel');
INSERT INTO cmn_classes VALUES (37, 22, 'HiddenField');
INSERT INTO cmn_classes VALUES (38, 22, 'TextField');
INSERT INTO cmn_classes VALUES (39, 22, 'Validator');
INSERT INTO cmn_classes VALUES (40, 22, 'PasswordField');
INSERT INTO cmn_classes VALUES (41, 22, 'TextArea');
INSERT INTO cmn_classes VALUES (42, 22, 'CheckBox');
INSERT INTO cmn_classes VALUES (43, 22, 'Option');
INSERT INTO cmn_classes VALUES (44, 22, 'CheckBoxGroup');
INSERT INTO cmn_classes VALUES (45, 22, 'RadioButtonGroup');
INSERT INTO cmn_classes VALUES (46, 22, 'Selection');
INSERT INTO cmn_classes VALUES (47, 22, 'MultiSelection');
INSERT INTO cmn_classes VALUES (48, 22, 'ComboBox');
INSERT INTO cmn_classes VALUES (49, 22, 'LookupField');
INSERT INTO cmn_classes VALUES (50, 22, 'MultiTextField');
INSERT INTO cmn_classes VALUES (51, 22, 'MultiTextField2');
INSERT INTO cmn_classes VALUES (52, 22, 'FileField');
INSERT INTO cmn_classes VALUES (53, 22, 'RadioButton');
INSERT INTO cmn_classes VALUES (54, 22, 'DateField');
INSERT INTO cmn_classes VALUES (55, 22, 'MultiChoiceField');
INSERT INTO cmn_classes VALUES (56, 23, 'IndexedForm');
INSERT INTO cmn_classes VALUES (57, 24, 'InputGridColumn');
INSERT INTO cmn_classes VALUES (58, 24, 'InputGrid');
INSERT INTO cmn_classes VALUES (59, 25, 'ListingColumn');
INSERT INTO cmn_classes VALUES (60, 25, 'CellRenderer');
INSERT INTO cmn_classes VALUES (61, 25, 'ActionRenderer');
INSERT INTO cmn_classes VALUES (62, 25, 'ListingAction');
INSERT INTO cmn_classes VALUES (63, 25, 'IconAction');
INSERT INTO cmn_classes VALUES (64, 25, 'TextAction');
INSERT INTO cmn_classes VALUES (65, 25, 'ListFilter');
INSERT INTO cmn_classes VALUES (66, 25, 'Listing');
INSERT INTO cmn_classes VALUES (67, 26, 'LookupTheme');
INSERT INTO cmn_classes VALUES (68, 27, 'BaseThemeMenu');
INSERT INTO cmn_classes VALUES (69, 27, 'ThemeMenu');
INSERT INTO cmn_classes VALUES (70, 28, 'PageNavigator');
INSERT INTO cmn_classes VALUES (71, 29, 'Prompt');
INSERT INTO cmn_classes VALUES (72, 30, 'StatusBar');
INSERT INTO cmn_classes VALUES (73, 31, 'TabbedForm');
INSERT INTO cmn_classes VALUES (74, 32, 'ThemePainter');
INSERT INTO cmn_classes VALUES (75, 33, 'ThemeToolkit');
INSERT INTO cmn_classes VALUES (76, 34, 'TabbedForm2');
INSERT INTO cmn_classes VALUES (77, 35, 'Calendar');
INSERT INTO cmn_classes VALUES (78, 37, 'PostgresConnection');
INSERT INTO cmn_classes VALUES (79, 38, 'PostgresQuery');
INSERT INTO cmn_classes VALUES (80, 39, 'MysqlConnection');
INSERT INTO cmn_classes VALUES (81, 40, 'MysqlQuery');
INSERT INTO cmn_classes VALUES (82, 41, 'MssqlConnection');
INSERT INTO cmn_classes VALUES (83, 42, 'MssqlQuery');
INSERT INTO cmn_classes VALUES (84, 43, 'BusinessCommonAdmin');
INSERT INTO cmn_classes VALUES (85, 44, 'BusinessCommonAutoComplete');
INSERT INTO cmn_classes VALUES (86, 45, 'BusinessCommonDocumentation');
INSERT INTO cmn_classes VALUES (87, 46, 'BusinessCommonLookup');
INSERT INTO cmn_classes VALUES (88, 47, 'BusinessCommonMain');
INSERT INTO cmn_classes VALUES (89, 48, 'SourceDocumentation');
INSERT INTO cmn_classes VALUES (90, 48, 'DocumentationIndex');
INSERT INTO cmn_classes VALUES (91, 48, 'FunctionIndex');
INSERT INTO cmn_classes VALUES (92, 49, 'CommonUser');
INSERT INTO cmn_classes VALUES (93, 49, 'CommonModule');
INSERT INTO cmn_classes VALUES (94, 49, 'CommonGroup');
INSERT INTO cmn_classes VALUES (95, 50, 'CommonModuleForm');
INSERT INTO cmn_classes VALUES (96, 51, 'CommonUserForm');
INSERT INTO cmn_classes VALUES (97, 52, 'CommonUserPermissionsForm');
INSERT INTO cmn_classes VALUES (98, 53, 'CommonGroupForm');
INSERT INTO cmn_classes VALUES (99, 54, 'CommonGroupPermissionsForm');


--
-- Data for TOC entry 30 (OID 17350)
-- Name: cmn_functions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cmn_functions VALUES (1, 1, 1, 'Weather');
INSERT INTO cmn_functions VALUES (2, 1, 1, 'SetProxy');
INSERT INTO cmn_functions VALUES (3, 1, 1, 'SetCode');
INSERT INTO cmn_functions VALUES (4, 1, 1, 'Generate');
INSERT INTO cmn_functions VALUES (5, 2, 2, 'Counter');
INSERT INTO cmn_functions VALUES (6, 2, 2, 'SetRoot');
INSERT INTO cmn_functions VALUES (7, 2, 2, 'SetFormat');
INSERT INTO cmn_functions VALUES (8, 2, 2, 'GetCounter');
INSERT INTO cmn_functions VALUES (9, 2, 2, 'Generate');
INSERT INTO cmn_functions VALUES (10, 3, 3, 'Ticker');
INSERT INTO cmn_functions VALUES (11, 3, 3, 'SetSize');
INSERT INTO cmn_functions VALUES (12, 3, 3, 'AddMessage');
INSERT INTO cmn_functions VALUES (13, 3, 3, 'Generate');
INSERT INTO cmn_functions VALUES (14, 3, 3, 'tickerStart');
INSERT INTO cmn_functions VALUES (15, 3, 3, 'tickerScroll');
INSERT INTO cmn_functions VALUES (16, 4, 4, 'AutoComplete');
INSERT INTO cmn_functions VALUES (17, 4, 4, 'SetContext');
INSERT INTO cmn_functions VALUES (18, 4, 4, 'GetValue');
INSERT INTO cmn_functions VALUES (19, 4, 4, 'GetDatabase');
INSERT INTO cmn_functions VALUES (20, 4, 4, 'GetQuery');
INSERT INTO cmn_functions VALUES (21, 4, 4, 'SetValue');
INSERT INTO cmn_functions VALUES (22, 4, 4, 'SetDatabase');
INSERT INTO cmn_functions VALUES (23, 4, 4, 'SetQuery');
INSERT INTO cmn_functions VALUES (24, 5, 5, 'CheckError');
INSERT INTO cmn_functions VALUES (25, 5, 5, 'GetErrors');
INSERT INTO cmn_functions VALUES (26, 6, 6, 'Context');
INSERT INTO cmn_functions VALUES (27, 6, 6, 'Create');
INSERT INTO cmn_functions VALUES (28, 6, 6, 'GetAction');
INSERT INTO cmn_functions VALUES (29, 6, 6, 'ShiftAction');
INSERT INTO cmn_functions VALUES (30, 6, 6, 'PushAction');
INSERT INTO cmn_functions VALUES (31, 6, 6, 'DEPRECATED_ComposeURL');
INSERT INTO cmn_functions VALUES (32, 7, 7, 'Database');
INSERT INTO cmn_functions VALUES (33, 7, 7, 'Close');
INSERT INTO cmn_functions VALUES (34, 7, 7, 'GetError');
INSERT INTO cmn_functions VALUES (35, 7, 7, 'GetErrors');
INSERT INTO cmn_functions VALUES (36, 7, 7, 'Prepare');
INSERT INTO cmn_functions VALUES (37, 7, 7, 'Execute');
INSERT INTO cmn_functions VALUES (38, 7, 7, 'ExecuteBatch');
INSERT INTO cmn_functions VALUES (39, 7, 7, 'Query');
INSERT INTO cmn_functions VALUES (40, 7, 7, 'QueryRange');
INSERT INTO cmn_functions VALUES (41, 7, 7, 'QueryChunk');
INSERT INTO cmn_functions VALUES (42, 7, 7, 'Assert');
INSERT INTO cmn_functions VALUES (43, 8, 8, 'DefineRights');
INSERT INTO cmn_functions VALUES (44, 9, 9, 'Error');
INSERT INTO cmn_functions VALUES (45, 9, 9, 'Generate');
INSERT INTO cmn_functions VALUES (46, 10, 10, 'Login');
INSERT INTO cmn_functions VALUES (47, 10, 10, 'GetUserData');
INSERT INTO cmn_functions VALUES (48, 10, 10, 'SetUserData');
INSERT INTO cmn_functions VALUES (49, 11, 11, 'GetFilterValue');
INSERT INTO cmn_functions VALUES (50, 11, 11, 'AddFilterField');
INSERT INTO cmn_functions VALUES (51, 11, 11, 'GetDatabase');
INSERT INTO cmn_functions VALUES (52, 11, 11, 'GetQuery');
INSERT INTO cmn_functions VALUES (53, 11, 11, 'GetPageLength');
INSERT INTO cmn_functions VALUES (54, 11, 11, 'GetForm');
INSERT INTO cmn_functions VALUES (55, 11, 11, 'GetFilterFields');
INSERT INTO cmn_functions VALUES (56, 11, 11, 'SetTitle');
INSERT INTO cmn_functions VALUES (57, 11, 11, 'SetFilterTitle');
INSERT INTO cmn_functions VALUES (58, 11, 11, 'SetListingTitle');
INSERT INTO cmn_functions VALUES (59, 11, 11, 'SetDatabase');
INSERT INTO cmn_functions VALUES (60, 11, 11, 'SetQuery');
INSERT INTO cmn_functions VALUES (61, 11, 11, 'SetLabels');
INSERT INTO cmn_functions VALUES (62, 11, 11, 'SetPageLength');
INSERT INTO cmn_functions VALUES (63, 11, 11, 'SetForm');
INSERT INTO cmn_functions VALUES (64, 12, 12, 'MIOLO');
INSERT INTO cmn_functions VALUES (65, 12, 12, 'SetDispatcher');
INSERT INTO cmn_functions VALUES (66, 12, 12, 'SetLog');
INSERT INTO cmn_functions VALUES (67, 12, 12, 'Assert');
INSERT INTO cmn_functions VALUES (68, 12, 12, 'GetThemePainter');
INSERT INTO cmn_functions VALUES (69, 12, 12, 'GetTheme');
INSERT INTO cmn_functions VALUES (70, 12, 12, 'SetTheme');
INSERT INTO cmn_functions VALUES (71, 12, 12, 'GenerateTheme');
INSERT INTO cmn_functions VALUES (72, 12, 12, 'GetActionURL');
INSERT INTO cmn_functions VALUES (73, 12, 12, 'Scramble');
INSERT INTO cmn_functions VALUES (74, 12, 12, 'UnScramble');
INSERT INTO cmn_functions VALUES (75, 12, 12, 'CheckLogin');
INSERT INTO cmn_functions VALUES (76, 12, 12, 'CheckAccess');
INSERT INTO cmn_functions VALUES (77, 12, 12, 'GetRights');
INSERT INTO cmn_functions VALUES (78, 12, 12, 'GetUsersAllowed');
INSERT INTO cmn_functions VALUES (79, 12, 12, 'Authenticate');
INSERT INTO cmn_functions VALUES (80, 12, 12, 'GetLogin');
INSERT INTO cmn_functions VALUES (81, 12, 12, 'SetLogin');
INSERT INTO cmn_functions VALUES (82, 12, 12, 'Uses');
INSERT INTO cmn_functions VALUES (83, 12, 12, 'GetDatabase');
INSERT INTO cmn_functions VALUES (84, 12, 12, 'GetBusiness');
INSERT INTO cmn_functions VALUES (85, 12, 12, 'GetClass');
INSERT INTO cmn_functions VALUES (86, 12, 12, 'GetUI');
INSERT INTO cmn_functions VALUES (87, 12, 12, 'GetAbsolutePath');
INSERT INTO cmn_functions VALUES (88, 12, 12, 'GetAbsoluteURL');
INSERT INTO cmn_functions VALUES (89, 12, 12, 'GetThemeURL');
INSERT INTO cmn_functions VALUES (90, 12, 12, 'GetModulePath');
INSERT INTO cmn_functions VALUES (91, 12, 12, 'AutoComplete');
INSERT INTO cmn_functions VALUES (92, 12, 12, 'Error');
INSERT INTO cmn_functions VALUES (93, 12, 12, 'Information');
INSERT INTO cmn_functions VALUES (94, 12, 12, 'Confirmation');
INSERT INTO cmn_functions VALUES (95, 12, 12, 'Question');
INSERT INTO cmn_functions VALUES (96, 12, 12, 'Prompt');
INSERT INTO cmn_functions VALUES (97, 12, 12, 'LogSQL');
INSERT INTO cmn_functions VALUES (98, 12, 12, 'LogError');
INSERT INTO cmn_functions VALUES (99, 12, 12, 'ProfileTime');
INSERT INTO cmn_functions VALUES (100, 12, 12, 'ProfileEnter');
INSERT INTO cmn_functions VALUES (101, 12, 12, 'ProfileExit');
INSERT INTO cmn_functions VALUES (102, 12, 12, 'ProfileDump');
INSERT INTO cmn_functions VALUES (103, 12, 12, 'GetProfileDump');
INSERT INTO cmn_functions VALUES (104, 12, 12, 'UsesDump');
INSERT INTO cmn_functions VALUES (105, 12, 12, 'Dump');
INSERT INTO cmn_functions VALUES (106, 12, 12, 'IsLogging');
INSERT INTO cmn_functions VALUES (107, 12, 12, 'LogMessage');
INSERT INTO cmn_functions VALUES (108, 12, 12, 'Deprecate');
INSERT INTO cmn_functions VALUES (109, 12, 12, 'Trace');
INSERT INTO cmn_functions VALUES (110, 12, 12, 'TraceDump');
INSERT INTO cmn_functions VALUES (111, 12, 12, 'InvokeHandler');
INSERT INTO cmn_functions VALUES (112, 12, 12, 'GetThemes');
INSERT INTO cmn_functions VALUES (113, 12, 12, 'ListFiles');
INSERT INTO cmn_functions VALUES (114, 12, 12, 'GetCurrentURL');
INSERT INTO cmn_functions VALUES (115, 12, 12, 'CheckValidIP');
INSERT INTO cmn_functions VALUES (116, 12, 12, 'IsHostAllowed');
INSERT INTO cmn_functions VALUES (117, 12, 12, '_REQUEST');
INSERT INTO cmn_functions VALUES (118, 13, 13, 'UI');
INSERT INTO cmn_functions VALUES (119, 13, 13, 'Alert');
INSERT INTO cmn_functions VALUES (120, 13, 13, 'CreateForm');
INSERT INTO cmn_functions VALUES (121, 13, 13, 'GetForm');
INSERT INTO cmn_functions VALUES (122, 13, 13, 'GetMenu');
INSERT INTO cmn_functions VALUES (123, 13, 13, 'GetImage');
INSERT INTO cmn_functions VALUES (124, 14, 14, 'Generate');
INSERT INTO cmn_functions VALUES (125, 15, 15, 'Success');
INSERT INTO cmn_functions VALUES (126, 15, 15, 'Generate');
INSERT INTO cmn_functions VALUES (127, 16, 16, 'Tree');
INSERT INTO cmn_functions VALUES (128, 16, 16, 'AddItem');
INSERT INTO cmn_functions VALUES (129, 16, 16, 'FindNode');
INSERT INTO cmn_functions VALUES (130, 16, 16, 'Generate');
INSERT INTO cmn_functions VALUES (131, 17, 17, 'QueryRange');
INSERT INTO cmn_functions VALUES (132, 18, 18, 'VarDump');
INSERT INTO cmn_functions VALUES (133, 18, 18, 'Generate');
INSERT INTO cmn_functions VALUES (134, 18, 19, 'InvertDate');
INSERT INTO cmn_functions VALUES (135, 18, 19, 'ymd2dmy');
INSERT INTO cmn_functions VALUES (136, 18, 19, 'dmy2ymd');
INSERT INTO cmn_functions VALUES (137, 18, 20, 'FormatValue');
INSERT INTO cmn_functions VALUES (138, 19, 21, 'BarcodeI25');
INSERT INTO cmn_functions VALUES (139, 19, 21, 'SetCode');
INSERT INTO cmn_functions VALUES (140, 19, 21, 'GetCode');
INSERT INTO cmn_functions VALUES (141, 19, 21, 'Generate');
INSERT INTO cmn_functions VALUES (142, 19, 21, 'MixCode');
INSERT INTO cmn_functions VALUES (143, 20, 22, 'postscript');
INSERT INTO cmn_functions VALUES (144, 20, 22, '_add_font');
INSERT INTO cmn_functions VALUES (145, 20, 22, 'insert_line');
INSERT INTO cmn_functions VALUES (146, 20, 22, 'include_resource');
INSERT INTO cmn_functions VALUES (147, 20, 22, 'encode_ISOLatin1');
INSERT INTO cmn_functions VALUES (148, 20, 22, 'begin_page');
INSERT INTO cmn_functions VALUES (149, 20, 22, 'end_page');
INSERT INTO cmn_functions VALUES (150, 20, 22, 'close');
INSERT INTO cmn_functions VALUES (151, 20, 22, 'line');
INSERT INTO cmn_functions VALUES (152, 20, 22, 'moveto');
INSERT INTO cmn_functions VALUES (153, 20, 22, 'moveto_font');
INSERT INTO cmn_functions VALUES (154, 20, 22, 'open_ps');
INSERT INTO cmn_functions VALUES (155, 20, 22, 'circle');
INSERT INTO cmn_functions VALUES (156, 20, 22, 'circle_fill');
INSERT INTO cmn_functions VALUES (157, 20, 22, 'arc');
INSERT INTO cmn_functions VALUES (158, 20, 22, 'arc_fill');
INSERT INTO cmn_functions VALUES (159, 20, 22, 'rect');
INSERT INTO cmn_functions VALUES (160, 20, 22, 'rect_fill');
INSERT INTO cmn_functions VALUES (161, 20, 22, 'rotate');
INSERT INTO cmn_functions VALUES (162, 20, 22, 'set_font');
INSERT INTO cmn_functions VALUES (163, 20, 22, 'show');
INSERT INTO cmn_functions VALUES (164, 20, 22, 'show_eval');
INSERT INTO cmn_functions VALUES (165, 20, 22, 'show_xy');
INSERT INTO cmn_functions VALUES (166, 20, 22, 'show_xy_font');
INSERT INTO cmn_functions VALUES (167, 20, 22, 'set_color');
INSERT INTO cmn_functions VALUES (168, 21, 23, 'Theme');
INSERT INTO cmn_functions VALUES (169, 21, 23, 'AddStyle');
INSERT INTO cmn_functions VALUES (170, 21, 23, 'AddScript');
INSERT INTO cmn_functions VALUES (171, 21, 23, 'AddMeta');
INSERT INTO cmn_functions VALUES (172, 21, 23, 'AddHttpEquiv');
INSERT INTO cmn_functions VALUES (173, 21, 23, 'GetStyles');
INSERT INTO cmn_functions VALUES (174, 21, 23, 'GetScripts');
INSERT INTO cmn_functions VALUES (175, 21, 23, 'GetMetas');
INSERT INTO cmn_functions VALUES (176, 21, 23, 'GetNavigationBar');
INSERT INTO cmn_functions VALUES (177, 21, 23, 'SetNavigationBar');
INSERT INTO cmn_functions VALUES (178, 21, 23, 'ClearMenus');
INSERT INTO cmn_functions VALUES (179, 21, 23, 'GetMenus');
INSERT INTO cmn_functions VALUES (180, 21, 23, 'GetMainMenu');
INSERT INTO cmn_functions VALUES (181, 21, 23, 'GetMenu');
INSERT INTO cmn_functions VALUES (182, 21, 23, 'GetStatusBar');
INSERT INTO cmn_functions VALUES (183, 21, 23, 'SetStatusBar');
INSERT INTO cmn_functions VALUES (184, 21, 23, 'GetTitle');
INSERT INTO cmn_functions VALUES (185, 21, 23, 'SetTitle');
INSERT INTO cmn_functions VALUES (186, 21, 23, 'ClearContent');
INSERT INTO cmn_functions VALUES (187, 21, 23, 'GetContent');
INSERT INTO cmn_functions VALUES (188, 21, 23, 'SetContent');
INSERT INTO cmn_functions VALUES (189, 21, 23, 'InsertContent');
INSERT INTO cmn_functions VALUES (190, 21, 23, 'AppendContent');
INSERT INTO cmn_functions VALUES (191, 21, 23, 'AddElement');
INSERT INTO cmn_functions VALUES (192, 21, 23, 'UseComponent');
INSERT INTO cmn_functions VALUES (193, 21, 23, 'Content');
INSERT INTO cmn_functions VALUES (194, 21, 23, 'Status');
INSERT INTO cmn_functions VALUES (195, 21, 23, 'Generate');
INSERT INTO cmn_functions VALUES (196, 21, 23, 'Commands');
INSERT INTO cmn_functions VALUES (197, 21, 23, 'Title');
INSERT INTO cmn_functions VALUES (198, 21, 23, 'News');
INSERT INTO cmn_functions VALUES (199, 21, 23, 'Help');
INSERT INTO cmn_functions VALUES (200, 21, 23, 'CreateMenu');
INSERT INTO cmn_functions VALUES (201, 21, 24, 'ThemeElement');
INSERT INTO cmn_functions VALUES (202, 21, 24, 'Generate');
INSERT INTO cmn_functions VALUES (203, 21, 25, 'AddElement');
INSERT INTO cmn_functions VALUES (204, 21, 25, 'Generate');
INSERT INTO cmn_functions VALUES (205, 21, 25, 'GetRowCount');
INSERT INTO cmn_functions VALUES (206, 21, 25, 'GetColumnCount');
INSERT INTO cmn_functions VALUES (207, 21, 25, 'GenerateElements');
INSERT INTO cmn_functions VALUES (208, 21, 26, 'Content');
INSERT INTO cmn_functions VALUES (209, 21, 26, 'Generate');
INSERT INTO cmn_functions VALUES (210, 21, 27, 'FileContent');
INSERT INTO cmn_functions VALUES (211, 21, 27, 'SetFile');
INSERT INTO cmn_functions VALUES (212, 21, 27, 'Generate');
INSERT INTO cmn_functions VALUES (213, 21, 28, 'ThemeMenu');
INSERT INTO cmn_functions VALUES (214, 21, 28, 'GetTitle');
INSERT INTO cmn_functions VALUES (215, 21, 28, 'SetTitle');
INSERT INTO cmn_functions VALUES (216, 21, 28, 'GetBase');
INSERT INTO cmn_functions VALUES (217, 21, 28, 'SetBase');
INSERT INTO cmn_functions VALUES (218, 21, 28, 'GetOptions');
INSERT INTO cmn_functions VALUES (219, 21, 28, 'AddLink');
INSERT INTO cmn_functions VALUES (220, 21, 28, 'AddOption');
INSERT INTO cmn_functions VALUES (221, 21, 28, 'AddUserOption');
INSERT INTO cmn_functions VALUES (222, 21, 28, 'AddGroupOption');
INSERT INTO cmn_functions VALUES (223, 21, 28, 'AddSeparator');
INSERT INTO cmn_functions VALUES (224, 21, 28, 'AddMenu');
INSERT INTO cmn_functions VALUES (225, 21, 28, 'Clear');
INSERT INTO cmn_functions VALUES (226, 21, 28, 'Generate');
INSERT INTO cmn_functions VALUES (227, 21, 29, 'ThemeBox');
INSERT INTO cmn_functions VALUES (228, 21, 29, 'Generate');
INSERT INTO cmn_functions VALUES (229, 22, 30, 'Form');
INSERT INTO cmn_functions VALUES (230, 22, 30, 'AddValidation');
INSERT INTO cmn_functions VALUES (231, 22, 30, 'GetName');
INSERT INTO cmn_functions VALUES (232, 22, 30, 'IsSubmitted');
INSERT INTO cmn_functions VALUES (233, 22, 30, 'GetTitle');
INSERT INTO cmn_functions VALUES (234, 22, 30, 'SetTitle');
INSERT INTO cmn_functions VALUES (235, 22, 30, 'GetFooter');
INSERT INTO cmn_functions VALUES (236, 22, 30, 'SetFooter');
INSERT INTO cmn_functions VALUES (237, 22, 30, 'GetFormValue');
INSERT INTO cmn_functions VALUES (238, 22, 30, 'SetFormValue');
INSERT INTO cmn_functions VALUES (239, 22, 30, 'EscapeValue');
INSERT INTO cmn_functions VALUES (240, 22, 30, 'OnSubmit');
INSERT INTO cmn_functions VALUES (241, 22, 30, 'SetAction');
INSERT INTO cmn_functions VALUES (242, 22, 30, 'SetHelp');
INSERT INTO cmn_functions VALUES (243, 22, 30, 'GetFields');
INSERT INTO cmn_functions VALUES (244, 22, 30, 'SetFields');
INSERT INTO cmn_functions VALUES (245, 22, 30, '_RegisterField');
INSERT INTO cmn_functions VALUES (246, 22, 30, 'AddField');
INSERT INTO cmn_functions VALUES (247, 22, 30, 'AddButton');
INSERT INTO cmn_functions VALUES (248, 22, 30, 'SetButtonLabel');
INSERT INTO cmn_functions VALUES (249, 22, 30, 'ShowReturn');
INSERT INTO cmn_functions VALUES (250, 22, 30, 'ShowReset');
INSERT INTO cmn_functions VALUES (251, 22, 30, 'GetShowHints');
INSERT INTO cmn_functions VALUES (252, 22, 30, 'ShowHints');
INSERT INTO cmn_functions VALUES (253, 22, 30, 'GetFieldList');
INSERT INTO cmn_functions VALUES (254, 22, 30, '_GetFieldList');
INSERT INTO cmn_functions VALUES (255, 22, 30, 'ValidateAll');
INSERT INTO cmn_functions VALUES (256, 22, 30, 'Validate');
INSERT INTO cmn_functions VALUES (257, 22, 30, 'Error');
INSERT INTO cmn_functions VALUES (258, 22, 30, 'AddError');
INSERT INTO cmn_functions VALUES (259, 22, 30, 'HasErrors');
INSERT INTO cmn_functions VALUES (260, 22, 30, 'CollectInput');
INSERT INTO cmn_functions VALUES (261, 22, 30, 'GetData');
INSERT INTO cmn_functions VALUES (262, 22, 30, 'SetData');
INSERT INTO cmn_functions VALUES (263, 22, 30, '_SetData');
INSERT INTO cmn_functions VALUES (264, 22, 30, 'GetFieldValue');
INSERT INTO cmn_functions VALUES (265, 22, 30, 'SetFieldValue');
INSERT INTO cmn_functions VALUES (266, 22, 30, 'GetField');
INSERT INTO cmn_functions VALUES (267, 22, 30, 'GetBody');
INSERT INTO cmn_functions VALUES (268, 22, 30, 'Generate');
INSERT INTO cmn_functions VALUES (269, 22, 30, 'GenerateErrors');
INSERT INTO cmn_functions VALUES (270, 22, 30, 'GenerateBody');
INSERT INTO cmn_functions VALUES (271, 22, 30, 'GenerateScript');
INSERT INTO cmn_functions VALUES (272, 22, 30, 'LayoutFormFields');
INSERT INTO cmn_functions VALUES (273, 22, 32, 'FormField');
INSERT INTO cmn_functions VALUES (274, 22, 32, 'GetHint');
INSERT INTO cmn_functions VALUES (275, 22, 32, 'SetHint');
INSERT INTO cmn_functions VALUES (276, 22, 32, 'AddAttribute');
INSERT INTO cmn_functions VALUES (277, 22, 32, 'Generate');
INSERT INTO cmn_functions VALUES (278, 22, 32, 'Attributes');
INSERT INTO cmn_functions VALUES (279, 22, 33, 'FormButton');
INSERT INTO cmn_functions VALUES (280, 22, 33, 'Generate');
INSERT INTO cmn_functions VALUES (281, 22, 34, 'Separator');
INSERT INTO cmn_functions VALUES (282, 22, 34, 'Generate');
INSERT INTO cmn_functions VALUES (283, 22, 35, 'Text');
INSERT INTO cmn_functions VALUES (284, 22, 35, 'Generate');
INSERT INTO cmn_functions VALUES (285, 22, 36, 'TextLabel');
INSERT INTO cmn_functions VALUES (286, 22, 36, 'Generate');
INSERT INTO cmn_functions VALUES (287, 22, 37, 'HiddenField');
INSERT INTO cmn_functions VALUES (288, 22, 37, 'Generate');
INSERT INTO cmn_functions VALUES (289, 22, 38, 'TextField');
INSERT INTO cmn_functions VALUES (290, 22, 38, 'SetAutoSubmit');
INSERT INTO cmn_functions VALUES (291, 22, 38, 'Generate');
INSERT INTO cmn_functions VALUES (292, 22, 39, 'Validator');
INSERT INTO cmn_functions VALUES (293, 22, 39, 'MASKValidator');
INSERT INTO cmn_functions VALUES (294, 22, 39, 'EMAILValidator');
INSERT INTO cmn_functions VALUES (295, 22, 39, 'CEPValidator');
INSERT INTO cmn_functions VALUES (296, 22, 39, 'PHONEValidator');
INSERT INTO cmn_functions VALUES (297, 22, 39, 'TIMEValidator');
INSERT INTO cmn_functions VALUES (298, 22, 39, 'CPFValidator');
INSERT INTO cmn_functions VALUES (299, 22, 39, 'CNPJValidator');
INSERT INTO cmn_functions VALUES (300, 22, 39, 'DATEDMYValidator');
INSERT INTO cmn_functions VALUES (301, 22, 39, 'DATEYMDValidator');
INSERT INTO cmn_functions VALUES (302, 22, 39, 'Generate');
INSERT INTO cmn_functions VALUES (303, 22, 40, 'PasswordField');
INSERT INTO cmn_functions VALUES (304, 22, 41, 'TextArea');
INSERT INTO cmn_functions VALUES (305, 22, 41, 'Generate');
INSERT INTO cmn_functions VALUES (306, 22, 42, 'CheckBox');
INSERT INTO cmn_functions VALUES (307, 22, 42, 'Generate');
INSERT INTO cmn_functions VALUES (308, 22, 43, 'Option');
INSERT INTO cmn_functions VALUES (309, 22, 44, 'CheckBoxGroup');
INSERT INTO cmn_functions VALUES (310, 22, 44, 'Generate');
INSERT INTO cmn_functions VALUES (311, 22, 45, 'RadioButtonGroup');
INSERT INTO cmn_functions VALUES (312, 22, 45, 'SetVerticalLayout');
INSERT INTO cmn_functions VALUES (313, 22, 45, 'SetHorizontalLayout');
INSERT INTO cmn_functions VALUES (314, 22, 45, 'Generate');
INSERT INTO cmn_functions VALUES (315, 22, 46, 'Selection');
INSERT INTO cmn_functions VALUES (316, 22, 46, 'SetAutoSubmit');
INSERT INTO cmn_functions VALUES (317, 22, 46, 'Generate');
INSERT INTO cmn_functions VALUES (318, 22, 47, 'MultiSelection');
INSERT INTO cmn_functions VALUES (319, 22, 47, 'Generate');
INSERT INTO cmn_functions VALUES (320, 22, 48, 'SetAutoSubmit');
INSERT INTO cmn_functions VALUES (321, 22, 48, 'ComboBox');
INSERT INTO cmn_functions VALUES (322, 22, 48, 'Generate');
INSERT INTO cmn_functions VALUES (323, 22, 49, 'LookupField');
INSERT INTO cmn_functions VALUES (324, 22, 49, 'Generate');
INSERT INTO cmn_functions VALUES (325, 22, 50, 'MultiTextField');
INSERT INTO cmn_functions VALUES (326, 22, 50, 'Generate');
INSERT INTO cmn_functions VALUES (327, 22, 51, 'MultiTextField2');
INSERT INTO cmn_functions VALUES (328, 22, 51, 'Generate');
INSERT INTO cmn_functions VALUES (329, 22, 52, 'FileField');
INSERT INTO cmn_functions VALUES (330, 22, 52, 'Generate');
INSERT INTO cmn_functions VALUES (331, 22, 53, 'RadioButton');
INSERT INTO cmn_functions VALUES (332, 22, 53, 'Generate');
INSERT INTO cmn_functions VALUES (333, 22, 54, 'DateField');
INSERT INTO cmn_functions VALUES (334, 22, 54, 'Generate');
INSERT INTO cmn_functions VALUES (335, 22, 55, 'MultiChoiceField');
INSERT INTO cmn_functions VALUES (336, 22, 55, 'Generate');
INSERT INTO cmn_functions VALUES (337, 23, 56, 'IndexedForm');
INSERT INTO cmn_functions VALUES (338, 23, 56, 'AddPage');
INSERT INTO cmn_functions VALUES (339, 23, 56, 'GenerateIndexPage');
INSERT INTO cmn_functions VALUES (340, 23, 56, 'GenerateBody');
INSERT INTO cmn_functions VALUES (341, 24, 57, 'InputGridColumn');
INSERT INTO cmn_functions VALUES (342, 24, 58, 'InputGrid');
INSERT INTO cmn_functions VALUES (343, 24, 58, 'GetRowCount');
INSERT INTO cmn_functions VALUES (344, 24, 58, 'SetRowCount');
INSERT INTO cmn_functions VALUES (345, 24, 58, 'AddColumn');
INSERT INTO cmn_functions VALUES (346, 24, 58, 'Generate');
INSERT INTO cmn_functions VALUES (347, 24, 58, 'GetValue');
INSERT INTO cmn_functions VALUES (348, 24, 58, 'GetGridValue');
INSERT INTO cmn_functions VALUES (349, 25, 59, 'ListingColumn');
INSERT INTO cmn_functions VALUES (350, 25, 59, 'SetRenderer');
INSERT INTO cmn_functions VALUES (351, 25, 59, 'Generate');
INSERT INTO cmn_functions VALUES (352, 25, 60, 'Generate');
INSERT INTO cmn_functions VALUES (353, 25, 61, 'ActionRenderer');
INSERT INTO cmn_functions VALUES (354, 25, 61, 'AddAction');
INSERT INTO cmn_functions VALUES (355, 25, 61, 'SetActions');
INSERT INTO cmn_functions VALUES (356, 25, 61, 'Generate');
INSERT INTO cmn_functions VALUES (357, 25, 61, 'GetImage');
INSERT INTO cmn_functions VALUES (358, 25, 62, 'ListingAction');
INSERT INTO cmn_functions VALUES (359, 25, 63, 'IconAction');
INSERT INTO cmn_functions VALUES (360, 25, 64, 'TextAction');
INSERT INTO cmn_functions VALUES (361, 25, 65, 'ListFilter');
INSERT INTO cmn_functions VALUES (362, 25, 65, 'Generate');
INSERT INTO cmn_functions VALUES (363, 25, 66, 'Listing');
INSERT INTO cmn_functions VALUES (364, 25, 66, 'GetTitle');
INSERT INTO cmn_functions VALUES (365, 25, 66, 'GetFooter');
INSERT INTO cmn_functions VALUES (366, 25, 66, 'GetLabel');
INSERT INTO cmn_functions VALUES (367, 25, 66, 'SetTitle');
INSERT INTO cmn_functions VALUES (368, 25, 66, 'SetCellRenderer');
INSERT INTO cmn_functions VALUES (369, 25, 66, 'SetFilter');
INSERT INTO cmn_functions VALUES (370, 25, 66, 'SetFooter');
INSERT INTO cmn_functions VALUES (371, 25, 66, 'SetLabels');
INSERT INTO cmn_functions VALUES (372, 25, 66, 'SetData');
INSERT INTO cmn_functions VALUES (373, 25, 66, 'GetValue');
INSERT INTO cmn_functions VALUES (374, 25, 66, 'SetValue');
INSERT INTO cmn_functions VALUES (375, 25, 66, 'GetColumnCount');
INSERT INTO cmn_functions VALUES (376, 25, 66, 'GetRowCount');
INSERT INTO cmn_functions VALUES (377, 25, 66, 'AddAction');
INSERT INTO cmn_functions VALUES (378, 25, 66, 'AddActionIcon');
INSERT INTO cmn_functions VALUES (379, 25, 66, 'AddActionText');
INSERT INTO cmn_functions VALUES (380, 25, 66, 'AddError');
INSERT INTO cmn_functions VALUES (381, 25, 66, 'ShowID');
INSERT INTO cmn_functions VALUES (382, 25, 66, 'QueryData');
INSERT INTO cmn_functions VALUES (383, 25, 66, 'HasErrors');
INSERT INTO cmn_functions VALUES (384, 25, 66, 'GenerateErrors');
INSERT INTO cmn_functions VALUES (385, 25, 66, 'GenerateBody');
INSERT INTO cmn_functions VALUES (386, 25, 66, 'GetBody');
INSERT INTO cmn_functions VALUES (387, 25, 66, 'Generate');
INSERT INTO cmn_functions VALUES (388, 26, 67, 'LookupTheme');
INSERT INTO cmn_functions VALUES (389, 26, 67, 'Lookup');
INSERT INTO cmn_functions VALUES (390, 27, 68, 'BaseThemeMenu');
INSERT INTO cmn_functions VALUES (391, 27, 68, 'ClearOptions');
INSERT INTO cmn_functions VALUES (392, 27, 68, 'SetTitle');
INSERT INTO cmn_functions VALUES (393, 27, 68, 'SetStyle');
INSERT INTO cmn_functions VALUES (394, 27, 68, 'SetBase');
INSERT INTO cmn_functions VALUES (395, 27, 68, 'AddLink');
INSERT INTO cmn_functions VALUES (396, 27, 68, 'AddOption');
INSERT INTO cmn_functions VALUES (397, 27, 68, 'AddUserOption');
INSERT INTO cmn_functions VALUES (398, 27, 68, 'AddGroupOption');
INSERT INTO cmn_functions VALUES (399, 27, 68, 'AddSeparator');
INSERT INTO cmn_functions VALUES (400, 27, 68, 'AddMenu');
INSERT INTO cmn_functions VALUES (401, 27, 68, 'Generate');
INSERT INTO cmn_functions VALUES (402, 27, 69, 'ThemeMenu');
INSERT INTO cmn_functions VALUES (403, 27, 69, 'GenerateOptionList');
INSERT INTO cmn_functions VALUES (404, 27, 69, 'GenerateDefaultMenu');
INSERT INTO cmn_functions VALUES (405, 27, 69, 'GenerateNavigationMenu');
INSERT INTO cmn_functions VALUES (406, 28, 70, 'PageNavigator');
INSERT INTO cmn_functions VALUES (407, 28, 70, 'SetAction');
INSERT INTO cmn_functions VALUES (408, 28, 70, 'SetShowTotals');
INSERT INTO cmn_functions VALUES (409, 28, 70, 'GetQueryRange');
INSERT INTO cmn_functions VALUES (410, 28, 70, 'GetTotalRows');
INSERT INTO cmn_functions VALUES (411, 28, 70, 'GetCurrentPage');
INSERT INTO cmn_functions VALUES (412, 28, 70, 'SetCurrentPage');
INSERT INTO cmn_functions VALUES (413, 28, 70, 'GetTotalPages');
INSERT INTO cmn_functions VALUES (414, 28, 70, 'Generate');
INSERT INTO cmn_functions VALUES (415, 29, 71, 'Prompt');
INSERT INTO cmn_functions VALUES (416, 29, 71, 'Error');
INSERT INTO cmn_functions VALUES (417, 29, 71, 'Information');
INSERT INTO cmn_functions VALUES (418, 29, 71, 'Confirmation');
INSERT INTO cmn_functions VALUES (419, 29, 71, 'Question');
INSERT INTO cmn_functions VALUES (420, 29, 71, 'SetType');
INSERT INTO cmn_functions VALUES (421, 29, 71, 'AddButton');
INSERT INTO cmn_functions VALUES (422, 29, 71, 'Generate');
INSERT INTO cmn_functions VALUES (423, 30, 72, 'StatuBar');
INSERT INTO cmn_functions VALUES (424, 30, 72, 'AddInfo');
INSERT INTO cmn_functions VALUES (425, 30, 72, 'Generate');
INSERT INTO cmn_functions VALUES (426, 31, 73, 'TabbedForm');
INSERT INTO cmn_functions VALUES (427, 31, 73, 'AddField');
INSERT INTO cmn_functions VALUES (428, 31, 73, 'AddPage');
INSERT INTO cmn_functions VALUES (429, 31, 73, 'GetFieldValue');
INSERT INTO cmn_functions VALUES (430, 31, 73, 'SetFormValue');
INSERT INTO cmn_functions VALUES (431, 31, 73, 'GenerateFormTabs');
INSERT INTO cmn_functions VALUES (432, 31, 73, 'GetCurrentPage');
INSERT INTO cmn_functions VALUES (433, 31, 73, 'GetFieldList');
INSERT INTO cmn_functions VALUES (434, 31, 73, 'GenerateBody');
INSERT INTO cmn_functions VALUES (435, 32, 74, 'HasMenuOptions');
INSERT INTO cmn_functions VALUES (436, 32, 74, 'GenerateTheme');
INSERT INTO cmn_functions VALUES (437, 32, 74, 'GenerateHeader');
INSERT INTO cmn_functions VALUES (438, 32, 74, 'GenerateBody');
INSERT INTO cmn_functions VALUES (439, 32, 74, 'GenerateStyles');
INSERT INTO cmn_functions VALUES (440, 32, 74, 'GenerateScripts');
INSERT INTO cmn_functions VALUES (441, 32, 74, 'GenerateMetas');
INSERT INTO cmn_functions VALUES (442, 32, 74, 'GenerateTop');
INSERT INTO cmn_functions VALUES (443, 32, 74, 'GenerateContent');
INSERT INTO cmn_functions VALUES (444, 32, 74, 'GenerateBottom');
INSERT INTO cmn_functions VALUES (445, 32, 74, 'GenerateMenuBar');
INSERT INTO cmn_functions VALUES (446, 32, 74, 'GenerateForm');
INSERT INTO cmn_functions VALUES (447, 32, 74, 'GenerateListing');
INSERT INTO cmn_functions VALUES (448, 32, 74, 'GenerateElements');
INSERT INTO cmn_functions VALUES (449, 32, 74, 'GenerateBox');
INSERT INTO cmn_functions VALUES (450, 32, 74, 'GenerateMenu');
INSERT INTO cmn_functions VALUES (451, 32, 74, 'GenerateNavigationBar');
INSERT INTO cmn_functions VALUES (452, 32, 74, 'GenerateTraceStatus');
INSERT INTO cmn_functions VALUES (453, 32, 74, 'GenerateOptionList');
INSERT INTO cmn_functions VALUES (454, 32, 74, 'GenerateToString');
INSERT INTO cmn_functions VALUES (455, 33, 75, 'GenerateImageBorderBox');
INSERT INTO cmn_functions VALUES (456, 33, 75, 'GenerateSimpleBox');
INSERT INTO cmn_functions VALUES (457, 33, 75, 'GenerateFormBox');
INSERT INTO cmn_functions VALUES (458, 34, 76, 'TabbedForm2');
INSERT INTO cmn_functions VALUES (459, 34, 76, 'SetCSS');
INSERT INTO cmn_functions VALUES (460, 34, 76, 'AddPage');
INSERT INTO cmn_functions VALUES (461, 34, 76, 'GetFieldList');
INSERT INTO cmn_functions VALUES (462, 34, 76, 'GenerateBody');
INSERT INTO cmn_functions VALUES (463, 35, 77, 'Calendar');
INSERT INTO cmn_functions VALUES (464, 35, 77, 'SetURL');
INSERT INTO cmn_functions VALUES (465, 35, 77, 'Setdata');
INSERT INTO cmn_functions VALUES (466, 35, 77, 'GetData');
INSERT INTO cmn_functions VALUES (467, 35, 77, 'Generate');
INSERT INTO cmn_functions VALUES (468, 36, 0, '_M');
INSERT INTO cmn_functions VALUES (469, 37, 78, 'PostgresConnection');
INSERT INTO cmn_functions VALUES (470, 37, 78, 'Open');
INSERT INTO cmn_functions VALUES (471, 37, 78, 'Close');
INSERT INTO cmn_functions VALUES (472, 37, 78, 'Begin');
INSERT INTO cmn_functions VALUES (473, 37, 78, 'Finish');
INSERT INTO cmn_functions VALUES (474, 37, 78, 'GetError');
INSERT INTO cmn_functions VALUES (475, 37, 78, 'GetErrors');
INSERT INTO cmn_functions VALUES (476, 37, 78, 'GetErrorCount');
INSERT INTO cmn_functions VALUES (477, 37, 78, 'CheckError');
INSERT INTO cmn_functions VALUES (478, 37, 78, 'Execute');
INSERT INTO cmn_functions VALUES (479, 37, 78, 'ExecuteAffect');
INSERT INTO cmn_functions VALUES (480, 37, 78, 'CreateQuery');
INSERT INTO cmn_functions VALUES (481, 38, 79, 'PostgresQuery');
INSERT INTO cmn_functions VALUES (482, 38, 79, 'GetError');
INSERT INTO cmn_functions VALUES (483, 38, 79, 'Open');
INSERT INTO cmn_functions VALUES (484, 38, 79, 'Close');
INSERT INTO cmn_functions VALUES (485, 38, 79, 'MovePrev');
INSERT INTO cmn_functions VALUES (486, 38, 79, 'MoveNext');
INSERT INTO cmn_functions VALUES (487, 38, 79, 'GetRowCount');
INSERT INTO cmn_functions VALUES (488, 38, 79, 'GetColumnCount');
INSERT INTO cmn_functions VALUES (489, 38, 79, 'GetColumnName');
INSERT INTO cmn_functions VALUES (490, 38, 79, 'GetValue');
INSERT INTO cmn_functions VALUES (491, 38, 79, 'GetRowValues');
INSERT INTO cmn_functions VALUES (492, 38, 79, 'SetConnection');
INSERT INTO cmn_functions VALUES (493, 38, 79, 'SetSQL');
INSERT INTO cmn_functions VALUES (494, 39, 80, 'MysqlConnection');
INSERT INTO cmn_functions VALUES (495, 39, 80, 'Open');
INSERT INTO cmn_functions VALUES (496, 39, 80, 'Close');
INSERT INTO cmn_functions VALUES (497, 39, 80, 'Begin');
INSERT INTO cmn_functions VALUES (498, 39, 80, 'Finish');
INSERT INTO cmn_functions VALUES (499, 39, 80, 'GetError');
INSERT INTO cmn_functions VALUES (500, 39, 80, 'GetErrors');
INSERT INTO cmn_functions VALUES (501, 39, 80, 'GetErrorCount');
INSERT INTO cmn_functions VALUES (502, 39, 80, 'CheckError');
INSERT INTO cmn_functions VALUES (503, 39, 80, 'Execute');
INSERT INTO cmn_functions VALUES (504, 39, 80, 'CreateQuery');
INSERT INTO cmn_functions VALUES (505, 40, 81, 'GetError');
INSERT INTO cmn_functions VALUES (506, 40, 81, 'MysqlQuery');
INSERT INTO cmn_functions VALUES (507, 40, 81, 'Open');
INSERT INTO cmn_functions VALUES (508, 40, 81, 'Close');
INSERT INTO cmn_functions VALUES (509, 40, 81, 'MovePrev');
INSERT INTO cmn_functions VALUES (510, 40, 81, 'MoveNext');
INSERT INTO cmn_functions VALUES (511, 40, 81, 'GetRowCount');
INSERT INTO cmn_functions VALUES (512, 40, 81, 'GetColumnCount');
INSERT INTO cmn_functions VALUES (513, 40, 81, 'GetColumnName');
INSERT INTO cmn_functions VALUES (514, 40, 81, 'GetValue');
INSERT INTO cmn_functions VALUES (515, 40, 81, 'GetRowValues');
INSERT INTO cmn_functions VALUES (516, 40, 81, 'SetConnection');
INSERT INTO cmn_functions VALUES (517, 40, 81, 'SetSQL');
INSERT INTO cmn_functions VALUES (518, 41, 82, 'MssqlConnection');
INSERT INTO cmn_functions VALUES (519, 41, 82, 'Open');
INSERT INTO cmn_functions VALUES (520, 41, 82, 'Close');
INSERT INTO cmn_functions VALUES (521, 41, 82, 'Begin');
INSERT INTO cmn_functions VALUES (522, 41, 82, 'Finish');
INSERT INTO cmn_functions VALUES (523, 41, 82, 'GetError');
INSERT INTO cmn_functions VALUES (524, 41, 82, 'GetErrors');
INSERT INTO cmn_functions VALUES (525, 41, 82, 'GetErrorCount');
INSERT INTO cmn_functions VALUES (526, 41, 82, 'CheckError');
INSERT INTO cmn_functions VALUES (527, 41, 82, 'Execute');
INSERT INTO cmn_functions VALUES (528, 41, 82, 'CreateQuery');
INSERT INTO cmn_functions VALUES (529, 41, 82, 'treat_mssql_error');
INSERT INTO cmn_functions VALUES (530, 42, 83, 'MssqlQuery');
INSERT INTO cmn_functions VALUES (531, 42, 83, 'GetError');
INSERT INTO cmn_functions VALUES (532, 42, 83, 'Open');
INSERT INTO cmn_functions VALUES (533, 42, 83, 'Close');
INSERT INTO cmn_functions VALUES (534, 42, 83, 'MovePrev');
INSERT INTO cmn_functions VALUES (535, 42, 83, 'MoveNext');
INSERT INTO cmn_functions VALUES (536, 42, 83, 'GetRowCount');
INSERT INTO cmn_functions VALUES (537, 42, 83, 'GetColumnCount');
INSERT INTO cmn_functions VALUES (538, 42, 83, 'GetColumnName');
INSERT INTO cmn_functions VALUES (539, 42, 83, 'GetValue');
INSERT INTO cmn_functions VALUES (540, 42, 83, 'GetRowValues');
INSERT INTO cmn_functions VALUES (541, 42, 83, 'SetConnection');
INSERT INTO cmn_functions VALUES (542, 42, 83, 'SetSQL');
INSERT INTO cmn_functions VALUES (543, 43, 84, 'GetDatabase');
INSERT INTO cmn_functions VALUES (544, 43, 84, 'GetModule');
INSERT INTO cmn_functions VALUES (545, 43, 84, 'GetModules');
INSERT INTO cmn_functions VALUES (546, 43, 84, 'InsertModule');
INSERT INTO cmn_functions VALUES (547, 43, 84, 'UpdateModule');
INSERT INTO cmn_functions VALUES (548, 43, 84, 'DeleteModule');
INSERT INTO cmn_functions VALUES (549, 43, 84, 'GetUser');
INSERT INTO cmn_functions VALUES (550, 43, 84, 'GetUsers');
INSERT INTO cmn_functions VALUES (551, 43, 84, 'InsertUser');
INSERT INTO cmn_functions VALUES (552, 43, 84, 'UpdateUser');
INSERT INTO cmn_functions VALUES (553, 43, 84, 'DeleteUser');
INSERT INTO cmn_functions VALUES (554, 43, 84, 'DeleteUserPermissions');
INSERT INTO cmn_functions VALUES (555, 43, 84, 'UpdateUserPermissions');
INSERT INTO cmn_functions VALUES (556, 43, 84, 'UpdateUserPassword');
INSERT INTO cmn_functions VALUES (557, 43, 84, 'SetConfirm_Hash');
INSERT INTO cmn_functions VALUES (558, 43, 84, 'GetUserIdFromHash');
INSERT INTO cmn_functions VALUES (559, 43, 84, 'GetGroups');
INSERT INTO cmn_functions VALUES (560, 43, 84, 'InsertGroup');
INSERT INTO cmn_functions VALUES (561, 43, 84, 'UpdateGroup');
INSERT INTO cmn_functions VALUES (562, 43, 84, 'DeleteGroup');
INSERT INTO cmn_functions VALUES (563, 43, 84, 'GetGroup');
INSERT INTO cmn_functions VALUES (564, 43, 84, 'DeleteGroupPermissions');
INSERT INTO cmn_functions VALUES (565, 43, 84, 'UpdateGroupPermissions');
INSERT INTO cmn_functions VALUES (566, 44, 85, 'Query');
INSERT INTO cmn_functions VALUES (567, 44, 85, 'GetCampus');
INSERT INTO cmn_functions VALUES (568, 44, 85, 'GetPessoa');
INSERT INTO cmn_functions VALUES (569, 45, 86, 'GetDatabase');
INSERT INTO cmn_functions VALUES (570, 45, 86, 'ClearAll');
INSERT INTO cmn_functions VALUES (571, 45, 86, 'RegisterSource');
INSERT INTO cmn_functions VALUES (572, 45, 86, 'RegisterClass');
INSERT INTO cmn_functions VALUES (573, 45, 86, 'RegisterFunction');
INSERT INTO cmn_functions VALUES (574, 45, 86, 'QueryFunctionList');
INSERT INTO cmn_functions VALUES (575, 46, 87, 'LookupPessoa');
INSERT INTO cmn_functions VALUES (576, 46, 87, 'LookupInstituicoes');
INSERT INTO cmn_functions VALUES (577, 47, 88, 'GetDatabase');
INSERT INTO cmn_functions VALUES (578, 47, 88, 'GetUser');
INSERT INTO cmn_functions VALUES (579, 47, 88, 'GetRights');
INSERT INTO cmn_functions VALUES (580, 48, 89, 'SourceDocumentation');
INSERT INTO cmn_functions VALUES (581, 48, 89, 'Generate');
INSERT INTO cmn_functions VALUES (582, 48, 89, 'Update');
INSERT INTO cmn_functions VALUES (583, 48, 90, 'DocumentationIndex');
INSERT INTO cmn_functions VALUES (584, 48, 90, 'Generate');
INSERT INTO cmn_functions VALUES (585, 48, 90, 'ListFiles');
INSERT INTO cmn_functions VALUES (586, 48, 91, 'FunctionIndex');
INSERT INTO cmn_functions VALUES (587, 48, 91, 'Generate');
INSERT INTO cmn_functions VALUES (588, 50, 95, 'CommonModuleForm');
INSERT INTO cmn_functions VALUES (589, 50, 95, 'GetData');
INSERT INTO cmn_functions VALUES (590, 51, 96, 'CommonUserForm');
INSERT INTO cmn_functions VALUES (591, 51, 96, 'GetData');
INSERT INTO cmn_functions VALUES (592, 52, 97, 'CommonUserPermissionsForm');
INSERT INTO cmn_functions VALUES (593, 52, 97, 'GetData');
INSERT INTO cmn_functions VALUES (594, 53, 98, 'CommonGroupForm');
INSERT INTO cmn_functions VALUES (595, 53, 98, 'GetData');
INSERT INTO cmn_functions VALUES (596, 54, 99, 'CommonGroupPermissionsForm');
INSERT INTO cmn_functions VALUES (597, 54, 99, 'GetData');
INSERT INTO cmn_functions VALUES (598, 77, 0, 'ProcessDirectory');


--
-- TOC entry 17 (OID 17309)
-- Name: cmn_access_user_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX cmn_access_user_key ON cmn_access USING btree (login, module, "action");


--
-- TOC entry 18 (OID 17312)
-- Name: cmn_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_users
    ADD CONSTRAINT cmn_users_pkey PRIMARY KEY (login);


--
-- TOC entry 19 (OID 17333)
-- Name: cmn_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_groups
    ADD CONSTRAINT cmn_groups_pkey PRIMARY KEY (id, module);


--
-- TOC entry 20 (OID 17340)
-- Name: cmn_modules_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_modules
    ADD CONSTRAINT cmn_modules_pkey PRIMARY KEY (name);


--
-- TOC entry 21 (OID 17344)
-- Name: cmn_sources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_sources
    ADD CONSTRAINT cmn_sources_pkey PRIMARY KEY (id);


--
-- TOC entry 22 (OID 17348)
-- Name: cmn_classes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_classes
    ADD CONSTRAINT cmn_classes_pkey PRIMARY KEY (id);


--
-- TOC entry 23 (OID 17352)
-- Name: cmn_functions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cmn_functions
    ADD CONSTRAINT cmn_functions_pkey PRIMARY KEY (id);


--
-- TOC entry 2 (OID 2200)
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


--
-- TOC entry 5 (OID 17144)
-- Name: TABLE cmn_access; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE cmn_access IS 'Cadastro de Direitos de Acessos dos Usuários';


--
-- TOC entry 6 (OID 17144)
-- Name: COLUMN cmn_access.module; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cmn_access.module IS 'Cadastro de Direitos de Acessos dos Usuários';


--
-- TOC entry 7 (OID 17144)
-- Name: COLUMN cmn_access.login; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cmn_access.login IS 'Cadastro de Direitos de Acessos dos Usuários';


--
-- TOC entry 8 (OID 17144)
-- Name: COLUMN cmn_access."action"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cmn_access."action" IS 'Cadastro de Direitos de Acessos dos Usuários';


--
-- TOC entry 9 (OID 17144)
-- Name: COLUMN cmn_access.fl_access; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cmn_access.fl_access IS 'Cadastro de Direitos de Acessos dos Usuários';


