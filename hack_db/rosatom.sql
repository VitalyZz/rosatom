/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     05.06.2020 22:24:16                          */
/*==============================================================*/


alter table Enterprises 
   drop foreign key FK_ENTERPRI_RELATIONS_INFO;

alter table User 
   drop foreign key FK_USER_USER_TO_I_INFO;


alter table Enterprises 
   drop foreign key FK_ENTERPRI_RELATIONS_INFO;

drop table if exists Enterprises;

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
   id_info              char(10) not null  comment '',
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

alter table Enterprises add constraint FK_ENTERPRI_RELATIONS_INFO foreign key (id_info)
      references Info (id_info) on delete restrict on update restrict;

alter table User add constraint FK_USER_USER_TO_I_INFO foreign key (id_info)
      references Info (id_info) on delete restrict on update restrict;

