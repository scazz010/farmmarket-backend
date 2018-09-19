create table users (
  id varchar(255) primary key,
  username varchar(255) not null,
  username_canonical varchar(180) not null unique,
  email varchar(180) not null,
  email_canonical varchar(180) not null unique,
  enabled tinyint(1),
  password varchar(255) null,
  salt varchar(255) null,
  last_login datetime null,
  confirmation_token varchar(255) null,
  password_requested_at datetime null,
  roles longtext not null,
  given_name varchar(255) null,
  family_name varchar (255) null
);


drop table _4228e4a00331b5d5e751db0481828e22a2c3c8ef;
create table _4228e4a00331b5d5e751db0481828e22a2c3c8ef (
  no bigint(20) primary key,
  event_id char(36) unique,
  event_name varchar(100),
  payload json,
  metadata json,
  created_at datetime,
  aggregate_version int(11),
  aggregate_id char(36),
  aggregate_type varchar(15)
);

drop table event_streams;
create table event_streams (
    no bigint(20) primary key,
  real_stream_name varchar(150) unique,
  stream_name char(41),
  metadata json,
  category varchar(150)
);
insert into event_streams(no, real_stream_name, stream_name, metadata, category)
values(1, "event_stream", "_4228e4a00331b5d5e751db0481828e22a2c3c8ef", "[]", null);

