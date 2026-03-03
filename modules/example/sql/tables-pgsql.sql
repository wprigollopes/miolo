--
-- Vilson Cristiano GÃ¤rtner - vgartner@univates.br
--
-- $Id: tables-pgsql.sql,v 1.1 2005/05/17 03:23:25 vgartner Exp $
--

--
--  Tables' SQL
--
--  tut_ identifies the module's name: TUTorial
--  tut_ identifica o nome do modulo: TUTorial
--

create table tut_cities
(
    zip_code char(9)        primary key,
    state    char(2)        not null,
    name     varchar(80)    not null
);

create table tut_people
(
    cpf      varchar(14)    primary key,
    name     varchar(80)    not null,
    address  varchar(80)    not null,
    address1 varchar(40)    default '',
    zip_city char(9)        references tut_cities(zip_code),
    phone    varchar(30)    default '',
    email    varchar(40)    default ''
);

