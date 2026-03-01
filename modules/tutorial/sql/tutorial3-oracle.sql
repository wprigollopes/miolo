CREATE TABLE depto (
	IdDepto character(9) NOT NULL,
	nome character varying(80) NOT NULL,
	Constraint depto_pk Primary Key (IdDepto)
);

CREATE TABLE disciplina (
	IdDisciplina character(9) NOT NULL,
	nome character varying(80) NOT NULL,
	IdDepto character(9), 
	Constraint disciplina_pk Primary Key (IdDisciplina)
);

CREATE TABLE aluno (
	IdAluno character varying(9) NOT NULL,
	nome character varying(80) NOT NULL,
	fone character varying(30) DEFAULT '',
	email character varying(40) DEFAULT '',
	datanasc character varying(10) DEFAULT '',
	Constraint aluno_pk Primary Key (IdAluno)
);

CREATE TABLE Matricula (
	IdAluno character varying(9) NOT NULL,
	IdDisciplina character varying(9) NOT NULL
);

