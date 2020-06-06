/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     06.06.2020 0:01:13                           */
/*==============================================================*/


alter table Info 
   drop foreign key FK_INFO_RELATIONS_ENTERPRI;

alter table User 
   drop foreign key FK_USER_USER_TO_I_INFO;

drop table if exists Enterprises;


alter table Info 
   drop foreign key FK_INFO_RELATIONS_ENTERPRI;

drop table if exists Info;


alter table User 
   drop foreign key FK_USER_USER_TO_I_INFO;

drop table if exists User;

/*==============================================================*/
/* Table: Enterprises                                           */
/*==============================================================*/
create table Enterprises
(
   subdivision          varchar(200) not null  comment '',
   id_sub               bigint not null  comment '',
   primary key (id_sub)
);

/*==============================================================*/
/* Table: Info                                                  */
/*==============================================================*/
create table Info
(
   surname              varchar(200) not null  comment '',
   name                 varchar(200) not null  comment '',
   "mid-name"           varchar(200) not null  comment '',
   phone                varchar(200) not null  comment '',
   id_info              char(10) not null  comment '',
   id_sub               bigint not null  comment '',
   primary key (id_info)
);

/*==============================================================*/
/* Table: User                                                  */
/*==============================================================*/
create table User
(
   "p-number"           bigint not null  comment '',
   id_info              char(10) not null  comment '',
   email                varchar(200) not null  comment '',
   pass                 varchar(200) not null  comment '',
   primary key ("p-number")
);

alter table Info add constraint FK_INFO_RELATIONS_ENTERPRI foreign key (id_sub)
      references Enterprises (id_sub) on delete restrict on update restrict;

alter table User add constraint FK_USER_USER_TO_I_INFO foreign key (id_info)
      references Info (id_info) on delete restrict on update restrict;

