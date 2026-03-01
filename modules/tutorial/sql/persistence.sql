/* criada anteriormente 
create table cm_sequence ( 
    sequence                    character(20),
    value                       integer
);
*/

insert into cm_sequence values('seq_cm_pessoa',50);
insert into cm_sequence values('seq_cm_usuario',50);
insert into cm_sequence values('seq_cm_grupoacesso',50);
insert into cm_sequence values('seq_cm_setor',50);

create table cm_pessoa ( 
       idpessoa                      integer,
       nome                          character(55)     not null
);

create table cm_usuario ( 
       idusuario                     integer,
       login                         character(20)     not null,
       password                      character(20)     not null,
       nick                          character(20),
       idpessoa                      integer,
       idsetor                       character(4)
);

create table cm_grupoacesso ( 
       idgrupo                       integer,
       grupo                         character(50)
);

create table cm_setor ( 
       idsetor                       character(4),
       siglasetor                    character(15),
       nomesetor                     character(50),
       tiposetor                     character(20),
       parent                        character(4)
);

create table cm_aluno ( 
       matricula                     character(10),
       situacao                      character(15),
       idpessoa                      integer
);

create table cm_grpusuario ( 
       idgrupo                       integer,
       idusuario                     integer
);

insert into cm_pessoa values (1,'Scott Ambler');
insert into cm_pessoa values (2,'Erich Gamma');
insert into cm_pessoa values (3,'Martin Fowler');
insert into cm_pessoa values (4,'Kent Beck');
insert into cm_pessoa values (5,'Leon Atkinson');
insert into cm_pessoa values (6,'Zeev Suraski');
insert into cm_pessoa values (7,'Craig Larman');
insert into cm_pessoa values (8,'Ralph Johnson');

insert into cm_setor values (1,'ADM','Administration','A',1);
insert into cm_setor values (2,'TEC','Technical','T',2);
insert into cm_setor values (3,'MKT','Marketing','A',1);
insert into cm_setor values (4,'FIN','Financial','A',1);
insert into cm_setor values (5,'HR' ,'Human Resource','A',1);
insert into cm_setor values (6,'MKT','Marketing','A',1);
insert into cm_setor values (7,'DEV','Developer','T',2);
insert into cm_setor values (8,'DB','DataBase','T',2);
insert into cm_setor values (9,'DSG','Design','T',2);



insert into cm_grupoacesso values (1,'OO');
insert into cm_grupoacesso values (2,'Patterns');
insert into cm_grupoacesso values (3,'PHP');

insert into cm_usuario values (1,'Ambler',   'none', 'Scott',   1, 2);
insert into cm_usuario values (2,'EGamma',   'none', 'Erich',   2, 1);
insert into cm_usuario values (3,'MFowler',  'none', 'Martin',  3, 2);
insert into cm_usuario values (4,'KBeck',    'none', 'Kent',    4, 2);
insert into cm_usuario values (5,'Leon',     'none', 'Leon',    5, 1);
insert into cm_usuario values (6,'Zeev',     'none', 'Zeev',    6, 1);
insert into cm_usuario values (7,'Larman',   'none', 'Craig',   7, 2);
insert into cm_usuario values (8,'RJohnson', 'none', 'Ralph',   8, 1);

insert into cm_aluno values ('20051234','Matriculado', 1);
insert into cm_aluno values ('20051237','Matriculado', 2);
insert into cm_aluno values ('20051256','Matriculado', 3);
insert into cm_aluno values ('20051259','Matriculado', 4);
insert into cm_aluno values ('20051276','Matriculado', 5);
insert into cm_aluno values ('20051287','Matriculado', 6);

insert into cm_grpusuario values (1,1);
insert into cm_grpusuario values (1,2);
insert into cm_grpusuario values (1,7);
insert into cm_grpusuario values (2,2);
insert into cm_grpusuario values (2,3);
insert into cm_grpusuario values (2,4);
insert into cm_grpusuario values (2,8);
insert into cm_grpusuario values (3,5);
insert into cm_grpusuario values (3,6);
