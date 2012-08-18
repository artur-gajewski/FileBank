CREATE TABLE filebank (
  id int(11) NOT NULL auto_increment,
  name varchar(250) NOT NULL,
  size int(11) NOT NULL,
  mimetype varchar(250) NOT NULL,
  isactive int(11) NOT NULL,
  savepath varchar(250) NOT NULL,
  keywords varchar(500),
  PRIMARY KEY (id)
);

CREATE TABLE filebank_keyword (
  id int(11) NOT NULL auto_increment,
  fileid int(11) NOT NULL,
  value varchar(250),
  PRIMARY KEY (id)
);