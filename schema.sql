drop table "paste";
create table "paste" (
	"id" serial,
	"hash" text UNIQUE NOT NULL,
	"text" text NOT NULL,
	"user" int,
	"time_left" timestamptz,
	"public" bool NOT NULL
);

drop table "user";
create table "user"(
	"id" serial,
	"login" varchar(40) UNIQUE NOT NULL,
	"password" varchar(256) NOT NULL,
	"email" text NOT NULL,
	"blocked" bool NOT NULL,
	"session" text
);
