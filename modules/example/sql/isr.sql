// Interbase/Firebird/Oracle

create table ISR_WORD (
   IDWORD  integer NOT NULL,
   WORD    char(50)
);

alter table ISR_WORD add constraint ISR_WORD_PK primary key (IDWORD);
create index IDX_ISR_WORD_WORD on ISR_WORD(WORD);

create table ISR_TABLE (
   IDTABLE      integer NOT NULL,
   TABLENAME    char(50)
);

alter table ISR_TABLE add constraint ISR_TABLE_PK primary key (IDTABLE);
create index IDX_ISR_TABLE_TABLENAME on ISR_TABLE(TABLENAME);

create table ISR_FIELD (
   IDFIELD      integer NOT NULL,
   IDTABLE      integer NOT NULL,
   FIELDNAME    char(50)
);

alter table ISR_FIELD add constraint ISR_FIELD_PK primary key (IDFIELD);
create index IDX_ISR_FIELD_FIELDNAME on ISR_FIELD(IDTABLE, FIELDNAME);

create table ISR_INDEX (
   IDINDEX  integer NOT NULL,
   IDWORD   integer NOT NULL,
   IDFIELD  integer NOT NULL,
   LEN      integer NOT NULL,
   BLOCK    varchar2(2400)
);

alter table ISR_INDEX add constraint ISR_INDEX_PK primary key (IDINDEX);
create index IDX_ISR_INDEX_WORDFIELD on ISR_INDEX(IDWORD,IDFIELD);

create table ISR_WORDFONO (
   IDWORD  integer NOT NULL,
   WORD    char(50)
);

alter table ISR_WORDFONO add constraint ISR_WORDFONO_PK primary key (IDWORD);
create index IDX_ISR_WORDFONO_WORD on ISR_WORDFONO(WORD);

create table ISR_INDEXFONO (
   IDINDEX  integer NOT NULL,
   IDWORD   integer NOT NULL,
   IDFIELD  integer NOT NULL,
   LEN      integer NOT NULL,
   BLOCK    varchar2(2400)
);

alter table ISR_INDEXFONO add constraint ISR_INDEXFONO_PK primary key (IDINDEX);
create index IDX_ISR_INDEXFONO_WORDFIELD on ISR_INDEXFONO(IDWORD,IDFIELD);

create table ISR_KEY (
   IDKEY    integer NOT NULL,
   IDTABLE  integer NOT NULL,
   KEY      char(6),
   BLOCK    varchar2(3600)
);

alter table ISR_KEY add constraint ISR_KEY_PK primary key (IDKEY);
create index IDX_ISR_KEY_KEY on ISR_KEY(IDTABLE,KEY);

create table ISR_KEYFONO (
   IDKEY    integer NOT NULL,
   IDTABLE  integer NOT NULL,
   KEY      char(6),
   BLOCK    varchar2(3600)
);

alter table ISR_KEYFONO add constraint ISR_KEYFONO_PK primary key (IDKEY);
create index IDX_ISR_KEYFONO_KEY on ISR_KEYFONO(IDTABLE,KEY);


create sequence SEQ_ISR_WORD minvalue 1;
create sequence SEQ_ISR_FIELD minvalue 1;
create sequence SEQ_ISR_INDEX minvalue 1;
create sequence SEQ_ISR_TABLE minvalue 1;
create sequence SEQ_ISR_KEY minvalue 1;

//
delete from ISR_WORD;
delete from ISR_TABLE;
delete from ISR_FIELD;
delete from ISR_INDEX;
delete from ISR_WORDFONO;
delete from ISR_INDEXFONO;
delete from ISR_KEY;
delete from ISR_KEYFONO;

//
drop sequence SEQ_ISR_WORD;
drop sequence SEQ_ISR_FIELD;
drop sequence SEQ_ISR_INDEX;
drop sequence SEQ_ISR_TABLE;
drop sequence SEQ_ISR_KEY;
drop table ISR_WORD;
drop table ISR_TABLE;
drop table ISR_FIELD;
drop table ISR_INDEX;
drop table ISR_WORDFONO;
drop table ISR_INDEXFONO;
drop table ISR_KEY;
drop table ISR_KEYFONO;

// Interbase
create generator SEQ_ISR_WORD;
set generator SEQ_ISR_WORD to 1;

create generator SEQ_ISR_FIELD;
set generator SEQ_ISR_FIELD to 1;

