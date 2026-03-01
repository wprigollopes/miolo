--
-- Updated: 2004-03-02
-- Nasair Júnior da Silva <njunior@solis.coop.br>
--
ALTER TABLE cmn_users ADD nickname varchar(20);
ALTER TABLE cmn_users ADD theme varchar(20);
ALTER TABLE cmn_users ADD groups varchar;


CREATE TABLE cmn_groups (
    id varchar(20) NOT NULL,
    description varchar,
    module varchar(20),
    primary key(id, module)
);

CREATE TABLE cmn_modules (
    name varchar(20) NOT NULL,
    description varchar(120) NOT NULL,
    rights text,
    primary key(name)
);


CREATE TABLE cmn_sources (
    id integer NOT NULL,
    "path" varchar(255) NOT NULL,
    primary key (id)
);

CREATE TABLE cmn_classes (
    id integer NOT NULL,
    ref_source integer NOT NULL,
    name varchar(255) NOT NULL,
    primary key (id)
);

CREATE TABLE cmn_functions (
    id integer NOT NULL,
    ref_source integer NOT NULL,
    ref_class integer NOT NULL,
    name varchar(255) NOT NULL,
    primary key (id)
);
