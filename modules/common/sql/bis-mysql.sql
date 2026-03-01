#
# Contributed by Marcelo Steidl - marcbrs@hotmail.com
# Contributed by Nasair Júnior da Silva - njunior@solis.coop.br
#
# Updated by Vilson Cristiano Gärtner - vilson@miolo.org.br


#
# Estrutura da tabela `cmn_access`
#

use bis;

CREATE TABLE cmn_access (
  module varchar(20) NOT NULL default '',
  login varchar(20) NOT NULL default '',
  action varchar(50) NOT NULL default '',
  fl_access enum('t','f') default 'f',
  PRIMARY KEY  (login,module,action)
) TYPE=MyISAM;

#
# Extraindo dados da tabela `cmn_access`
#

INSERT INTO cmn_access VALUES ('common', 'miolo', 'develop', 't');
INSERT INTO cmn_access VALUES ('common', 'miolo', 'admin', 't');
INSERT INTO cmn_access VALUES ('common', 'miolo', 'system', 't');

# --------------------------------------------------------


#
# Estrutura da tabela `cmn_users`
#

CREATE TABLE cmn_users (
  login varchar(20) NOT NULL default '',
  password varchar(40) NOT NULL default '',
  name varchar(80) default NULL,
  email varchar(80) default NULL,
  nickname varchar(20),
  theme varchar(20),
  groups text,
  PRIMARY KEY  (login)
) TYPE=MyISAM;

#
# Extraindo dados da tabela `cmn_users`
#

INSERT INTO cmn_users VALUES ('miolo', 'a2a748c9c53cfc96f750245bdbe69ae9', 'Miolo', 'miolo@localhost',  NULL, NULL, NULL);


CREATE TABLE cmn_modules (
    name varchar(20) NOT NULL,
    description varchar(120) NOT NULL,
    rights text,
    primary key(name)
);

CREATE TABLE cmn_groups (
    id varchar(20) NOT NULL,
    description text,
    module varchar(20),
    primary key(id, module) 
);
