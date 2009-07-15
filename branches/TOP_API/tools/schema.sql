create table cat (
   cat_id int not null,
   cat_name varchar(100),
   cat_desc varchar(255),
   primary key(cat_id)
);

create table api (
   api_id int not null,
   cat_id int not null,
   api_name varchar(100),
   primary key(api_id)
);

create table param (
   param_id int not null,
   api_id int not null,
   param_name varchar(100),
   param_type varchar(100),
   param_classname varchar(100),
   param_value text,
   param_desc text,
   primary key(param_id),
   unique key(api_id, param_name)
);

