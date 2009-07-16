drop table cat;
drop table api;
drop table fields;
drop table param;

create table cat (
   cat_id int not null auto_increment,
   cat_name varchar(100) comment 'api 分类名',
   cat_desc varchar(255) comment '分类说明',
   primary key(cat_id),
   unique key(cat_name)
) DEFAULT CHARSET utf8;

create table api (
   api_id int not null auto_increment,
   cat_id int not null             comment 'api 分类',
   api_name varchar(100)           comment 'api 方法名',
   is_secure boolean default false comment '是否需要用户登录',
   list_tags text                  comment 'xml 中列表字段标签名',
   primary key(api_id),
   unique key(api_name)
) DEFAULT CHARSET utf8;

create table param (
   param_id int not null auto_increment,
   api_id int not null          comment 'api 方法',
   param_name varchar(100)      comment '参数名',
   param_type varchar(100)      comment '参数类型：string 或 file',
   param_classname varchar(100) comment '参数属性：isMust 必需参数; mSelect 参数中必选其一',
   param_value text             comment '参数值示例',
   param_desc text              comment '参数说明',
   primary key(param_id),
   unique key(api_id, param_name)
) DEFAULT CHARSET utf8;

create table fields (
   fields_id int not null auto_increment,
   api_id int not null      comment 'api 方法',
   fields_name varchar(100) comment 'fields 分组名',
   fields_value text        comment 'fields 值',
   primary key(fields_id),
   unique key(api_id, fields_name)
) DEFAULT CHARSET utf8;
