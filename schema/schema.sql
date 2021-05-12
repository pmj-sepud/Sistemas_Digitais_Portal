/*
 Navicat Premium Data Transfer

 Source Server         : SEPUD-Neogrid
 Source Server Type    : PostgreSQL
 Source Server Version : 100004
 Source Host           : 177.67.89.21:5432
 Source Catalog        : waze_data
 Source Schema         : sepud

 Target Server Type    : PostgreSQL
 Target Server Version : 100004
 File Encoding         : 65001

 Date: 30/04/2021 08:23:17
*/


-- ----------------------------
-- Sequence structure for company_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."company_id_seq";
CREATE SEQUENCE "sepud"."company_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_parking_id_seq";
CREATE SEQUENCE "sepud"."eri_parking_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_parking_type_id_seq";
CREATE SEQUENCE "sepud"."eri_parking_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_schedule_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_schedule_parking_id_seq";
CREATE SEQUENCE "sepud"."eri_schedule_parking_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for gsec_callcenter_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."gsec_callcenter_id_seq";
CREATE SEQUENCE "sepud"."gsec_callcenter_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for gsec_callcenter_id_seq1
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."gsec_callcenter_id_seq1";
CREATE SEQUENCE "sepud"."gsec_callcenter_id_seq1" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for gsec_request_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."gsec_request_type_id_seq";
CREATE SEQUENCE "sepud"."gsec_request_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for hospital_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."hospital_id_seq";
CREATE SEQUENCE "sepud"."hospital_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for logs_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."logs_id_seq";
CREATE SEQUENCE "sepud"."logs_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for neighborhood_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."neighborhood_id_seq";
CREATE SEQUENCE "sepud"."neighborhood_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_addressbook_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_addressbook_id_seq";
CREATE SEQUENCE "sepud"."oct_addressbook_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_administrative_events_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_administrative_events_id_seq";
CREATE SEQUENCE "sepud"."oct_administrative_events_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_conditions_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_event_conditions_id_seq";
CREATE SEQUENCE "sepud"."oct_event_conditions_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_event_type_id_seq";
CREATE SEQUENCE "sepud"."oct_event_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_events_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_events_id_seq";
CREATE SEQUENCE "sepud"."oct_events_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_fleet_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_fleet_id_seq";
CREATE SEQUENCE "sepud"."oct_fleet_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_garrison_history_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_garrison_history_id_seq";
CREATE SEQUENCE "sepud"."oct_garrison_history_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_garrison_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_garrison_id_seq";
CREATE SEQUENCE "sepud"."oct_garrison_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_providence_id_seq";
CREATE SEQUENCE "sepud"."oct_providence_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_images_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_events_images_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_events_images_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_events_providence_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_events_providence_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_garrison_persona_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_garrison_persona_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_garrison_persona_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_garrison_vehicle_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_garrison_vehicle_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_garrison_vehicle_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_workshift_persona_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_workshift_persona_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_workshift_persona_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicle_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_vehicle_type_id_seq";
CREATE SEQUENCE "sepud"."oct_vehicle_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_vehicles_id_seq";
CREATE SEQUENCE "sepud"."oct_vehicles_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_victim_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_victim_id_seq";
CREATE SEQUENCE "sepud"."oct_victim_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_workshift_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_workshift_id_seq";
CREATE SEQUENCE "sepud"."oct_workshift_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for od_origem_destino_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."od_origem_destino_id_seq";
CREATE SEQUENCE "sepud"."od_origem_destino_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_data_sensors_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."rot_data_sensors_id_seq";
CREATE SEQUENCE "sepud"."rot_data_sensors_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_equipments_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."rot_equipments_id_seq";
CREATE SEQUENCE "sepud"."rot_equipments_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sas_request_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."sas_request_id_seq";
CREATE SEQUENCE "sepud"."sas_request_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sas_users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."sas_users_id_seq";
CREATE SEQUENCE "sepud"."sas_users_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sas_vars_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."sas_vars_id_seq";
CREATE SEQUENCE "sepud"."sas_vars_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sau_pncd_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."sau_pncd_id_seq";
CREATE SEQUENCE "sepud"."sau_pncd_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for ses_pe_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."ses_pe_id_seq";
CREATE SEQUENCE "sepud"."ses_pe_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for ses_pncd_registro_diario_atividade_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."ses_pncd_registro_diario_atividade_id_seq";
CREATE SEQUENCE "sepud"."ses_pncd_registro_diario_atividade_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for ses_trap_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."ses_trap_id_seq";
CREATE SEQUENCE "sepud"."ses_trap_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for streets_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."streets_id_seq";
CREATE SEQUENCE "sepud"."streets_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for user_permission_submodules_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."user_permission_submodules_id_seq";
CREATE SEQUENCE "sepud"."user_permission_submodules_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."users_id_seq";
CREATE SEQUENCE "sepud"."users_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for users_perm_modules_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."users_perm_modules_id_seq";
CREATE SEQUENCE "sepud"."users_perm_modules_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."company";
CREATE TABLE "sepud"."company" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".company_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default" NOT NULL,
  "acron" text COLLATE "pg_catalog"."default",
  "workshift_groups_repetition" int2,
  "workshift_groups" json,
  "workshift_subgroups_repetition" varchar(255) COLLATE "pg_catalog"."default",
  "workshift_subgroups" json,
  "workshift_rel_config" json,
  "active" bool DEFAULT true,
  "is_external" bool DEFAULT false,
  "id_user_contact" int4,
  "observations" text COLLATE "pg_catalog"."default",
  "phone" text COLLATE "pg_catalog"."default",
  "secretary" text COLLATE "pg_catalog"."default",
  "id_father" int4
)
;
ALTER TABLE "sepud"."company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for company_configs
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."company_configs";
CREATE TABLE "sepud"."company_configs" (
  "id_company" int4,
  "name" text COLLATE "pg_catalog"."default",
  "value" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."company_configs" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_parking";
CREATE TABLE "sepud"."eri_parking" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_parking_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "active" bool,
  "description" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "id_parking_type" int4,
  "area" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."eri_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_parking_type";
CREATE TABLE "sepud"."eri_parking_type" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_parking_type_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "time" int4,
  "time_warning" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "multi_parking" bool DEFAULT false
)
;
ALTER TABLE "sepud"."eri_parking_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_schedule_parking
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_schedule_parking";
CREATE TABLE "sepud"."eri_schedule_parking" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_schedule_parking_id_seq'::regclass),
  "id_vehicle" int4,
  "id_parking" int4,
  "timestamp" timestamp(0) DEFAULT now(),
  "notified" bool DEFAULT false,
  "notified_timestamp" timestamp(0),
  "closed" bool DEFAULT false,
  "closed_timestamp" timestamp(0),
  "id_user" int4,
  "licence_plate" varchar(255) COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "id_user_notified" int4,
  "id_user_closed" int4,
  "winch_timestamp" timestamp(0),
  "id_user_winch" int4,
  "id_oct_event" int4
)
;
ALTER TABLE "sepud"."eri_schedule_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for gsec_callcenter
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."gsec_callcenter";
CREATE TABLE "sepud"."gsec_callcenter" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".gsec_callcenter_id_seq1'::regclass),
  "date_added" timestamp(6),
  "date_closed" timestamp(0),
  "id_citizen" int4,
  "id_address" int4,
  "address_num" int4,
  "address_complement" text COLLATE "pg_catalog"."default",
  "id_subject" int4,
  "description" text COLLATE "pg_catalog"."default",
  "id_company" int4,
  "id_user_added" int4,
  "call_origin" text COLLATE "pg_catalog"."default",
  "sei_num" text COLLATE "pg_catalog"."default",
  "address_reference" text COLLATE "pg_catalog"."default",
  "id_neighborhood" int4,
  "status" text COLLATE "pg_catalog"."default",
  "origin_type" text COLLATE "pg_catalog"."default",
  "response" text COLLATE "pg_catalog"."default",
  "id_address_corner" int4,
  "active" bool DEFAULT true,
  "coords" text COLLATE "pg_catalog"."default",
  "coords_formattedaddress" text COLLATE "pg_catalog"."default",
  "priority" text COLLATE "pg_catalog"."default",
  "id_company_father" int4,
  "external_protocol" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."gsec_callcenter" OWNER TO "postgres";

-- ----------------------------
-- Table structure for gsec_citizen
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."gsec_citizen";
CREATE TABLE "sepud"."gsec_citizen" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".gsec_callcenter_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "phone1" text COLLATE "pg_catalog"."default",
  "phone2" text COLLATE "pg_catalog"."default",
  "phone3" text COLLATE "pg_catalog"."default",
  "id_residence_address" int4,
  "num_residence_address" int4,
  "complement_residence_address" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "rg" text COLLATE "pg_catalog"."default",
  "date_added" timestamp(6),
  "cnpj" text COLLATE "pg_catalog"."default",
  "observations" text COLLATE "pg_catalog"."default",
  "email" text COLLATE "pg_catalog"."default",
  "id_company" int4,
  "id_user_added" int4,
  "id_neighborhood" int4
)
;
ALTER TABLE "sepud"."gsec_citizen" OWNER TO "postgres";

-- ----------------------------
-- Table structure for gsec_files
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."gsec_files";
CREATE TABLE "sepud"."gsec_files" (
  "id_callcenter" int4,
  "file_path" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."gsec_files" OWNER TO "postgres";

-- ----------------------------
-- Table structure for gsec_request_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."gsec_request_type";
CREATE TABLE "sepud"."gsec_request_type" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".gsec_request_type_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "request" text COLLATE "pg_catalog"."default",
  "id_company" int4,
  "id_company_father" int4
)
;
ALTER TABLE "sepud"."gsec_request_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for hospital
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."hospital";
CREATE TABLE "sepud"."hospital" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".hospital_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."hospital" OWNER TO "postgres";

-- ----------------------------
-- Table structure for iq_pesquisa
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."iq_pesquisa";
CREATE TABLE "sepud"."iq_pesquisa" (
  "iq" text COLLATE "pg_catalog"."default",
  "status" text COLLATE "pg_catalog"."default",
  "ano" int4
)
;
ALTER TABLE "sepud"."iq_pesquisa" OWNER TO "postgres";

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."logs";
CREATE TABLE "sepud"."logs" (
  "ip" varchar(255) COLLATE "pg_catalog"."default",
  "id_user" int4,
  "module" varchar(255) COLLATE "pg_catalog"."default",
  "action" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0),
  "obs" text COLLATE "pg_catalog"."default",
  "id" int4 NOT NULL DEFAULT nextval('"sepud".logs_id_seq'::regclass)
)
;
ALTER TABLE "sepud"."logs" OWNER TO "postgres";

-- ----------------------------
-- Table structure for logs_hist
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."logs_hist";
CREATE TABLE "sepud"."logs_hist" (
  "date" date,
  "archive" text COLLATE "pg_catalog"."default",
  "metadata" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."logs_hist" OWNER TO "postgres";

-- ----------------------------
-- Table structure for neighborhood
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."neighborhood";
CREATE TABLE "sepud"."neighborhood" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".neighborhood_id_seq'::regclass),
  "neighborhood" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."neighborhood" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_addressbook
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_addressbook";
CREATE TABLE "sepud"."oct_addressbook" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_addressbook_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "num_ref" int2,
  "id_street" int4,
  "geoposition" text COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "zipcode" text COLLATE "pg_catalog"."default",
  "neighborhood" text COLLATE "pg_catalog"."default",
  "zone" text COLLATE "pg_catalog"."default",
  "non_mapped_street" text COLLATE "pg_catalog"."default",
  "id_company" varchar(255) COLLATE "pg_catalog"."default",
  "type" text COLLATE "pg_catalog"."default",
  "active" bool DEFAULT true
)
;
ALTER TABLE "sepud"."oct_addressbook" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_administrative_events
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_administrative_events";
CREATE TABLE "sepud"."oct_administrative_events" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_administrative_events_id_seq'::regclass),
  "id_addressbook" int4,
  "id_street" int4,
  "opened_timestamp" timestamp(0),
  "closed_timestamp" timestamp(0),
  "id_company" int4,
  "id_user" int4,
  "id_workshift" int4,
  "description" text COLLATE "pg_catalog"."default",
  "street_ref" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_administrative_events" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_event_conditions";
CREATE TABLE "sepud"."oct_event_conditions" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_event_conditions_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "subtype" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_event_type";
CREATE TABLE "sepud"."oct_event_type" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_event_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "name_acron" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "type" text COLLATE "pg_catalog"."default",
  "active" bool DEFAULT true,
  "priority" bool DEFAULT false
)
;
ALTER TABLE "sepud"."oct_event_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_events
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_events";
CREATE TABLE "sepud"."oct_events" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_events_id_seq'::regclass),
  "date" timestamp(0),
  "description" text COLLATE "pg_catalog"."default",
  "address_reference" varchar(255) COLLATE "pg_catalog"."default",
  "geoposition" text COLLATE "pg_catalog"."default",
  "id_event_type" int2,
  "status" text COLLATE "pg_catalog"."default",
  "victim_inform" int2,
  "victim_found" int2,
  "active" bool DEFAULT true,
  "arrival" timestamp(0),
  "closure" timestamp(0),
  "id_user" int4,
  "address_complement" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "street_number" int4,
  "id_company" int4,
  "id_workshift" int4,
  "requester" text COLLATE "pg_catalog"."default",
  "requester_phone" text COLLATE "pg_catalog"."default",
  "id_addressbook" int4,
  "id_garrison" int4,
  "on_way" timestamp(0),
  "region" text COLLATE "pg_catalog"."default",
  "requester_origin" text COLLATE "pg_catalog"."default",
  "requester_protocol" text COLLATE "pg_catalog"."default",
  "id_street_conner" int4,
  "id_neighborhood" int4,
  "init" timestamp(0),
  "finish" timestamp(0)
)
;
ALTER TABLE "sepud"."oct_events" OWNER TO "postgres";
COMMENT ON COLUMN "sepud"."oct_events"."date" IS 'Data/hora do registro inicial do sistema';
COMMENT ON COLUMN "sepud"."oct_events"."arrival" IS 'Data/hora de chegada no local do atendimento';
COMMENT ON COLUMN "sepud"."oct_events"."closure" IS 'Data/hora do encerramento da ocorrencia';

-- ----------------------------
-- Table structure for oct_fleet
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_fleet";
CREATE TABLE "sepud"."oct_fleet" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_fleet_id_seq'::regclass),
  "id_company" int4,
  "plate" varchar(255) COLLATE "pg_catalog"."default",
  "type" varchar(255) COLLATE "pg_catalog"."default",
  "model" varchar(255) COLLATE "pg_catalog"."default",
  "brand" varchar(255) COLLATE "pg_catalog"."default",
  "nickname" text COLLATE "pg_catalog"."default",
  "active" bool,
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_fleet" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_garrison
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_garrison";
CREATE TABLE "sepud"."oct_garrison" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_garrison_id_seq'::regclass),
  "id_fleet" int4,
  "id_workshift" int4,
  "opened" timestamp(0),
  "closed" timestamp(0),
  "initial_fuel" text COLLATE "pg_catalog"."default",
  "final_fuel" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default",
  "initial_km" int4,
  "final_km" int4,
  "name" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_garrison" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_providence
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_providence";
CREATE TABLE "sepud"."oct_providence" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_providence_id_seq'::regclass),
  "table_orgim" varchar(255) COLLATE "pg_catalog"."default",
  "title" varchar(255) COLLATE "pg_catalog"."default",
  "area" varchar(255) COLLATE "pg_catalog"."default",
  "providence" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_event_type_company
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_event_type_company";
CREATE TABLE "sepud"."oct_rel_event_type_company" (
  "id_company" int4,
  "id_event_type" int4
)
;
ALTER TABLE "sepud"."oct_rel_event_type_company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_event_conditions";
CREATE TABLE "sepud"."oct_rel_events_event_conditions" (
  "id_events" int4,
  "id_event_conditions" int2
)
;
ALTER TABLE "sepud"."oct_rel_events_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_images
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_images";
CREATE TABLE "sepud"."oct_rel_events_images" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_events_images_id_seq'::regclass),
  "id_events" int4,
  "image" varchar(255) COLLATE "pg_catalog"."default",
  "path" varchar(255) COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0)
)
;
ALTER TABLE "sepud"."oct_rel_events_images" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_observations
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_observations";
CREATE TABLE "sepud"."oct_rel_events_observations" (
  "id_user" int2,
  "id_event" int8,
  "id_type" int2,
  "text" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0) DEFAULT now()
)
;
ALTER TABLE "sepud"."oct_rel_events_observations" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_providence
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_providence";
CREATE TABLE "sepud"."oct_rel_events_providence" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_events_providence_id_seq'::regclass),
  "opened_date" timestamp(0) DEFAULT now(),
  "closed_date" timestamp(0),
  "id_owner" int4,
  "id_vehicle" int4,
  "id_victim" int4,
  "id_hospital" int4,
  "id_company_requested" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "id_event" int4,
  "id_providence" int4,
  "id_garrison" int4,
  "id_user_resp" int4
)
;
ALTER TABLE "sepud"."oct_rel_events_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_garrison_persona
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_garrison_persona";
CREATE TABLE "sepud"."oct_rel_garrison_persona" (
  "id_garrison" int4,
  "id_user" int4,
  "type" text COLLATE "pg_catalog"."default",
  "id_rel_garrison_vehicle" int4,
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_garrison_persona_id_seq'::regclass)
)
;
ALTER TABLE "sepud"."oct_rel_garrison_persona" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_garrison_vehicle
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_garrison_vehicle";
CREATE TABLE "sepud"."oct_rel_garrison_vehicle" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_garrison_vehicle_id_seq'::regclass),
  "id_fleet" int4,
  "initial_km" int4,
  "final_km" int4,
  "initial_fuel" float8,
  "final_fuel" float8,
  "obs" text COLLATE "pg_catalog"."default",
  "id_garrison" int4
)
;
ALTER TABLE "sepud"."oct_rel_garrison_vehicle" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_workshift_persona
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_workshift_persona";
CREATE TABLE "sepud"."oct_rel_workshift_persona" (
  "id_shift" int4,
  "id_person" int4,
  "opened" timestamp(0),
  "closed" timestamp(0),
  "type" text COLLATE "pg_catalog"."default",
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_workshift_persona_id_seq'::regclass),
  "status" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_rel_workshift_persona" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicle_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_vehicle_type";
CREATE TABLE "sepud"."oct_vehicle_type" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_vehicle_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "desc" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_vehicle_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicles
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_vehicles";
CREATE TABLE "sepud"."oct_vehicles" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_vehicles_id_seq'::regclass),
  "description" text COLLATE "pg_catalog"."default",
  "id_events" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "licence_plate" text COLLATE "pg_catalog"."default",
  "color" text COLLATE "pg_catalog"."default",
  "renavam" text COLLATE "pg_catalog"."default",
  "chassi" text COLLATE "pg_catalog"."default",
  "id_vehicle_type" int2,
  "ait" text COLLATE "pg_catalog"."default",
  "cod_infra" text COLLATE "pg_catalog"."default",
  "data_rec_auto" timestamp(0),
  "auto_id_user" int4
)
;
ALTER TABLE "sepud"."oct_vehicles" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_victim
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_victim";
CREATE TABLE "sepud"."oct_victim" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_victim_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "age" int2,
  "genre" text COLLATE "pg_catalog"."default",
  "id_vehicle" int4,
  "id_events" int4,
  "position_in_vehicle" text COLLATE "pg_catalog"."default",
  "state" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "forwarded_to" text COLLATE "pg_catalog"."default",
  "refuse_help" bool DEFAULT true,
  "rg" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "mother_name" text COLLATE "pg_catalog"."default",
  "conducted" bool,
  "conducted_by_id_user" int4,
  "death" bool,
  "refuse_redir" bool
)
;
ALTER TABLE "sepud"."oct_victim" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_workshift
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_workshift";
CREATE TABLE "sepud"."oct_workshift" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_workshift_id_seq'::regclass),
  "opened" timestamp(0),
  "closed" timestamp(0),
  "id_company" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "workshift_group" text COLLATE "pg_catalog"."default",
  "status" text COLLATE "pg_catalog"."default",
  "is_populate" bool DEFAULT false,
  "create_date" timestamp(0) DEFAULT now()
)
;
ALTER TABLE "sepud"."oct_workshift" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_workshift_history
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_workshift_history";
CREATE TABLE "sepud"."oct_workshift_history" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_garrison_history_id_seq'::regclass),
  "id_garrison" int4,
  "id_vehicle" int4,
  "id_user" int4,
  "obs" text COLLATE "pg_catalog"."default",
  "id_workshift" int4,
  "km_initial" int4,
  "km_final" int4,
  "type" text COLLATE "pg_catalog"."default",
  "origin" text COLLATE "pg_catalog"."default",
  "opened" timestamp(0),
  "closed" timestamp(0)
)
;
ALTER TABLE "sepud"."oct_workshift_history" OWNER TO "postgres";

-- ----------------------------
-- Table structure for od_origem_destino
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."od_origem_destino";
CREATE TABLE "sepud"."od_origem_destino" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".od_origem_destino_id_seq'::regclass),
  "matricula" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "idade" int2,
  "sexo" varchar(255) COLLATE "pg_catalog"."default",
  "o_endereco" text COLLATE "pg_catalog"."default",
  "o_numero" text COLLATE "pg_catalog"."default",
  "o_complemento" text COLLATE "pg_catalog"."default",
  "o_ bairro" text COLLATE "pg_catalog"."default",
  "o_cep" text COLLATE "pg_catalog"."default",
  "o_cidade" text COLLATE "pg_catalog"."default",
  "o_uf" text COLLATE "pg_catalog"."default",
  "modal" text COLLATE "pg_catalog"."default",
  "tipo" text COLLATE "pg_catalog"."default",
  "o_descricao" text COLLATE "pg_catalog"."default",
  "d_endereco" text COLLATE "pg_catalog"."default",
  "d_numero" text COLLATE "pg_catalog"."default",
  "d_complemento" text COLLATE "pg_catalog"."default",
  "d_bairro" text COLLATE "pg_catalog"."default",
  "d_cep" text COLLATE "pg_catalog"."default",
  "d_cidade" text COLLATE "pg_catalog"."default",
  "d_uf" text COLLATE "pg_catalog"."default",
  "d_descricao" text COLLATE "pg_catalog"."default",
  "d_geocode" text COLLATE "pg_catalog"."default",
  "o_geocode" text COLLATE "pg_catalog"."default",
  "fonte" text COLLATE "pg_catalog"."default",
  "data_importacao" timestamp(0) DEFAULT now(),
  "usa_vale_transporte" bool DEFAULT false,
  "o_censitario" text COLLATE "pg_catalog"."default",
  "d_sensitario" text COLLATE "pg_catalog"."default",
  "o_id_bairro" text COLLATE "pg_catalog"."default",
  "d_id_bairro" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."od_origem_destino" OWNER TO "postgres";
COMMENT ON COLUMN "sepud"."od_origem_destino"."modal" IS 'Onibus, carro, bicicleta, carona, etc…';
COMMENT ON COLUMN "sepud"."od_origem_destino"."tipo" IS '(profissional, estudo, laser, saúde...)';
COMMENT ON COLUMN "sepud"."od_origem_destino"."fonte" IS 'Saude, RH Empresas, Educação...';

-- ----------------------------
-- Table structure for resume
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."resume";
CREATE TABLE "sepud"."resume" (
  "field" varchar COLLATE "pg_catalog"."default",
  "int_value" int8,
  "str_value" varchar COLLATE "pg_catalog"."default",
  "type" varchar COLLATE "pg_catalog"."default",
  "module" varchar COLLATE "pg_catalog"."default",
  "ref_month" int2,
  "ref_year" int2
)
;
ALTER TABLE "sepud"."resume" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_data_sensors
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."rot_data_sensors";
CREATE TABLE "sepud"."rot_data_sensors" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".rot_data_sensors_id_seq'::regclass),
  "event_id" int4,
  "equipment_id" int4,
  "datetime" timestamp(0),
  "geo_position" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "value" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."rot_data_sensors" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_equipments
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."rot_equipments";
CREATE TABLE "sepud"."rot_equipments" (
  "name" text COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "last_activity" timestamp(6),
  "id" int4 NOT NULL DEFAULT nextval('"sepud".rot_equipments_id_seq'::regclass),
  "active" bool DEFAULT true
)
;
ALTER TABLE "sepud"."rot_equipments" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sas_citizen
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sas_citizen";
CREATE TABLE "sepud"."sas_citizen" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".sas_users_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "gmas" text COLLATE "pg_catalog"."default",
  "cadunico" text COLLATE "pg_catalog"."default",
  "address_complement" text COLLATE "pg_catalog"."default",
  "id_neighborhood" int4,
  "birth" date,
  "mother_name" text COLLATE "pg_catalog"."default",
  "phone" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "nis" text COLLATE "pg_catalog"."default",
  "sas_monitor" bool,
  "id_street" int4,
  "address_number" int4,
  "rg" text COLLATE "pg_catalog"."default",
  "date" timestamp(0),
  "id_user_register" int4,
  "id_company_register" int4,
  "observations" text COLLATE "pg_catalog"."default",
  "address_reference" text COLLATE "pg_catalog"."default",
  "cep" text COLLATE "pg_catalog"."default",
  "phone1" text COLLATE "pg_catalog"."default",
  "phone2" text COLLATE "pg_catalog"."default",
  "obs_contatos_adicionais" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."sas_citizen" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sas_rel_request_vars
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sas_rel_request_vars";
CREATE TABLE "sepud"."sas_rel_request_vars" (
  "id_request" int4,
  "id_var" int4
)
;
ALTER TABLE "sepud"."sas_rel_request_vars" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sas_request
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sas_request";
CREATE TABLE "sepud"."sas_request" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".sas_request_id_seq'::regclass),
  "demand" json,
  "food_aid" bool,
  "id_user" int4,
  "id_company" int4,
  "date" timestamp(0),
  "observations" text COLLATE "pg_catalog"."default",
  "food_count" int4,
  "food_size" text COLLATE "pg_catalog"."default",
  "delivery_date" timestamp(0),
  "delivery_observations" text COLLATE "pg_catalog"."default",
  "delivery_status" text COLLATE "pg_catalog"."default",
  "status" text COLLATE "pg_catalog"."default",
  "id_citizen" int4,
  "vars" json,
  "schedule_date" timestamp(0),
  "count_people" int4,
  "active_search" bool,
  "active_search_date" date,
  "active_search_observations" text COLLATE "pg_catalog"."default",
  "delivery_type" text COLLATE "pg_catalog"."default",
  "demand_status" json,
  "date_closed" timestamp(0),
  "average_income" float8
)
;
ALTER TABLE "sepud"."sas_request" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sas_vars
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sas_vars";
CREATE TABLE "sepud"."sas_vars" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".sas_vars_id_seq'::regclass),
  "description" text COLLATE "pg_catalog"."default",
  "subgroup" text COLLATE "pg_catalog"."default",
  "status" bool DEFAULT true
)
;
ALTER TABLE "sepud"."sas_vars" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sepud_cadastro_imoveis
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sepud_cadastro_imoveis";
CREATE TABLE "sepud"."sepud_cadastro_imoveis" (
  "iq" varchar(255) COLLATE "pg_catalog"."default",
  "uso" varchar(255) COLLATE "pg_catalog"."default",
  "ano" int2
)
;
ALTER TABLE "sepud"."sepud_cadastro_imoveis" OWNER TO "postgres";

-- ----------------------------
-- Table structure for ses_pe
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."ses_pe";
CREATE TABLE "sepud"."ses_pe" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".ses_pe_id_seq'::regclass),
  "semana" text COLLATE "pg_catalog"."default",
  "dia_semana" text COLLATE "pg_catalog"."default",
  "agente" text COLLATE "pg_catalog"."default",
  "endereco" text COLLATE "pg_catalog"."default",
  "ns" text COLLATE "pg_catalog"."default",
  "num" text COLLATE "pg_catalog"."default",
  "quarteirao" text COLLATE "pg_catalog"."default",
  "bairro" text COLLATE "pg_catalog"."default",
  "estabelecimento" text COLLATE "pg_catalog"."default",
  "rota" text COLLATE "pg_catalog"."default",
  "georeferencia" text COLLATE "pg_catalog"."default",
  "historico" text COLLATE "pg_catalog"."default",
  "ativo" bool DEFAULT true,
  "id_street" int4,
  "id_user" int4
)
;
ALTER TABLE "sepud"."ses_pe" OWNER TO "postgres";

-- ----------------------------
-- Table structure for ses_pncd_registro_diario
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."ses_pncd_registro_diario";
CREATE TABLE "sepud"."ses_pncd_registro_diario" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".sau_pncd_id_seq'::regclass),
  "municipio" text COLLATE "pg_catalog"."default",
  "codigo_e_nome_localidade" int4,
  "categoria_localidade" text COLLATE "pg_catalog"."default",
  "zona" text COLLATE "pg_catalog"."default",
  "tipo" text COLLATE "pg_catalog"."default",
  "concluido" bool,
  "data_atividade" date,
  "ciclo_ano" text COLLATE "pg_catalog"."default",
  "atividade" text COLLATE "pg_catalog"."default",
  "id_user" int4,
  "observacao" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."ses_pncd_registro_diario" OWNER TO "postgres";

-- ----------------------------
-- Table structure for ses_pncd_registro_diario_atividade
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."ses_pncd_registro_diario_atividade";
CREATE TABLE "sepud"."ses_pncd_registro_diario_atividade" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".ses_pncd_registro_diario_atividade_id_seq'::regclass),
  "id_ses_pncd_registro_diario" int4,
  "num_quarteirao" int4,
  "sequencia_lado" text COLLATE "pg_catalog"."default",
  "id_logradouro" int4,
  "num_sequencia" text COLLATE "pg_catalog"."default",
  "complemento" text COLLATE "pg_catalog"."default",
  "tipo_imovel" text COLLATE "pg_catalog"."default",
  "hora_entrada" time(6),
  "visita" text COLLATE "pg_catalog"."default",
  "pendencia" text COLLATE "pg_catalog"."default",
  "inspecao_a1" int4,
  "inspecao_a2" int4,
  "inspecao_b" int4,
  "inspecao_c" int4,
  "inspecao_d1" int4,
  "inspecao_d2" int4,
  "inspecao_e" int4,
  "eliminado" int4,
  "imovel_inspec_li" int4,
  "num_amostra_inicial" text COLLATE "pg_catalog"."default",
  "num_amostra_final" text COLLATE "pg_catalog"."default",
  "qtd_tubitos" int4,
  "lm_trat" text COLLATE "pg_catalog"."default",
  "larvicida_1_tipo" text COLLATE "pg_catalog"."default",
  "larvicida_1_qtd" text COLLATE "pg_catalog"."default",
  "larvicida_1_qtd_dep_trat" text COLLATE "pg_catalog"."default",
  "larvicida_2_tipo" text COLLATE "pg_catalog"."default",
  "larvicida_2_qtd" text COLLATE "pg_catalog"."default",
  "larvicida_2_qtd_dep_trat" text COLLATE "pg_catalog"."default",
  "trat_perifocal_tipo" text COLLATE "pg_catalog"."default",
  "trat_perifocal_qtd_cargas" text COLLATE "pg_catalog"."default",
  "id_trap" int4,
  "id_pe" int4
)
;
ALTER TABLE "sepud"."ses_pncd_registro_diario_atividade" OWNER TO "postgres";

-- ----------------------------
-- Table structure for ses_trap
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."ses_trap";
CREATE TABLE "sepud"."ses_trap" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".ses_trap_id_seq'::regclass),
  "seq_dia" int4,
  "dia_semana" text COLLATE "pg_catalog"."default",
  "agente" text COLLATE "pg_catalog"."default",
  "rua" text COLLATE "pg_catalog"."default",
  "quart" text COLLATE "pg_catalog"."default",
  "tipo_imov" text COLLATE "pg_catalog"."default",
  "num_imov" int4,
  "complemento" text COLLATE "pg_catalog"."default",
  "num_armadilha" int4,
  "bairro" text COLLATE "pg_catalog"."default",
  "estabelecimento" text COLLATE "pg_catalog"."default",
  "local_armadilha" text COLLATE "pg_catalog"."default",
  "insc_imob" text COLLATE "pg_catalog"."default",
  "novo_dia" int4,
  "nova_rota" int4,
  "obs" text COLLATE "pg_catalog"."default",
  "georef" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "id_neighborhood" int4,
  "id_user" int4,
  "ativo" bool DEFAULT true
)
;
ALTER TABLE "sepud"."ses_trap" OWNER TO "postgres";

-- ----------------------------
-- Table structure for streets
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."streets";
CREATE TABLE "sepud"."streets" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".streets_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "is_rotate_parking" bool DEFAULT false
)
;
ALTER TABLE "sepud"."streets" OWNER TO "postgres";

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."users";
CREATE TABLE "sepud"."users" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".users_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "email" text COLLATE "pg_catalog"."default",
  "password" text COLLATE "pg_catalog"."default",
  "nickname" text COLLATE "pg_catalog"."default",
  "area" text COLLATE "pg_catalog"."default",
  "job" text COLLATE "pg_catalog"."default",
  "active" bool,
  "in_activation" bool DEFAULT false,
  "company_acron" text COLLATE "pg_catalog"."default",
  "id_company" int4,
  "phone" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "date_of_birth" date,
  "registration" int4,
  "workshift_group_time_init" time(0),
  "workshift_group_time_finish" time(0),
  "workshift_group" text COLLATE "pg_catalog"."default",
  "workshift_subgroup_time_init" time(0),
  "workshift_subgroup_time_finish" time(0),
  "workshift_subgroup" text COLLATE "pg_catalog"."default",
  "initial_workshift_position" text COLLATE "pg_catalog"."default",
  "work_status" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."users" OWNER TO "postgres";
COMMENT ON COLUMN "sepud"."users"."id" IS 'unique index';
COMMENT ON COLUMN "sepud"."users"."name" IS 'Fullname';
COMMENT ON COLUMN "sepud"."users"."email" IS 'E-mail address, use to auth';
COMMENT ON COLUMN "sepud"."users"."password" IS 'MD5 password';
COMMENT ON COLUMN "sepud"."users"."registration" IS 'Matricula do funcionario';

-- ----------------------------
-- Table structure for users_perm_modules
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."users_perm_modules";
CREATE TABLE "sepud"."users_perm_modules" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".users_perm_modules_id_seq'::regclass),
  "module" text COLLATE "pg_catalog"."default",
  "descrition" text COLLATE "pg_catalog"."default",
  "show_order" int2
)
;
ALTER TABLE "sepud"."users_perm_modules" OWNER TO "postgres";

-- ----------------------------
-- Table structure for users_perm_modules_subgroup
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."users_perm_modules_subgroup";
CREATE TABLE "sepud"."users_perm_modules_subgroup" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".user_permission_submodules_id_seq'::regclass),
  "id_module" int4,
  "permission" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "type" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."users_perm_modules_subgroup" OWNER TO "postgres";

-- ----------------------------
-- Table structure for users_rel_perm_user
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."users_rel_perm_user";
CREATE TABLE "sepud"."users_rel_perm_user" (
  "id_user" int4 NOT NULL,
  "value" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."users_rel_perm_user" OWNER TO "postgres";

-- ----------------------------
-- Table structure for weather
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."weather";
CREATE TABLE "sepud"."weather" (
  "main" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "temp" float4,
  "feels_like" float4,
  "temp_min" float4,
  "temp_max" float4,
  "pressure" int4,
  "humidity" int4,
  "visibility" int4,
  "wind_speed" float4,
  "wind_dir" int4,
  "date" timestamp(0),
  "rain" float4
)
;
ALTER TABLE "sepud"."weather" OWNER TO "postgres";

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "sepud"."company_id_seq"
OWNED BY "sepud"."company"."id";
SELECT setval('"sepud"."company_id_seq"', 46, true);
ALTER SEQUENCE "sepud"."eri_parking_id_seq"
OWNED BY "sepud"."eri_parking"."id";
SELECT setval('"sepud"."eri_parking_id_seq"', 1002, true);
ALTER SEQUENCE "sepud"."eri_parking_type_id_seq"
OWNED BY "sepud"."eri_parking_type"."id";
SELECT setval('"sepud"."eri_parking_type_id_seq"', 17, true);
ALTER SEQUENCE "sepud"."eri_schedule_parking_id_seq"
OWNED BY "sepud"."eri_schedule_parking"."id";
SELECT setval('"sepud"."eri_schedule_parking_id_seq"', 414286, true);
ALTER SEQUENCE "sepud"."gsec_callcenter_id_seq"
OWNED BY "sepud"."gsec_citizen"."id";
SELECT setval('"sepud"."gsec_callcenter_id_seq"', 547, true);
ALTER SEQUENCE "sepud"."gsec_callcenter_id_seq1"
OWNED BY "sepud"."gsec_callcenter"."id";
SELECT setval('"sepud"."gsec_callcenter_id_seq1"', 643, true);
ALTER SEQUENCE "sepud"."gsec_request_type_id_seq"
OWNED BY "sepud"."gsec_request_type"."id";
SELECT setval('"sepud"."gsec_request_type_id_seq"', 53, true);
ALTER SEQUENCE "sepud"."hospital_id_seq"
OWNED BY "sepud"."hospital"."id";
SELECT setval('"sepud"."hospital_id_seq"', 11, true);
ALTER SEQUENCE "sepud"."logs_id_seq"
OWNED BY "sepud"."logs"."id";
SELECT setval('"sepud"."logs_id_seq"', 2043413, true);
ALTER SEQUENCE "sepud"."neighborhood_id_seq"
OWNED BY "sepud"."neighborhood"."id";
SELECT setval('"sepud"."neighborhood_id_seq"', 2, false);
ALTER SEQUENCE "sepud"."oct_addressbook_id_seq"
OWNED BY "sepud"."oct_addressbook"."id";
SELECT setval('"sepud"."oct_addressbook_id_seq"', 1052, true);
ALTER SEQUENCE "sepud"."oct_administrative_events_id_seq"
OWNED BY "sepud"."oct_administrative_events"."id";
SELECT setval('"sepud"."oct_administrative_events_id_seq"', 2131, true);
ALTER SEQUENCE "sepud"."oct_event_conditions_id_seq"
OWNED BY "sepud"."oct_event_conditions"."id";
SELECT setval('"sepud"."oct_event_conditions_id_seq"', 18, true);
ALTER SEQUENCE "sepud"."oct_event_type_id_seq"
OWNED BY "sepud"."oct_event_type"."id";
SELECT setval('"sepud"."oct_event_type_id_seq"', 647, true);
ALTER SEQUENCE "sepud"."oct_events_id_seq"
OWNED BY "sepud"."oct_events"."id";
SELECT setval('"sepud"."oct_events_id_seq"', 60254, true);
ALTER SEQUENCE "sepud"."oct_fleet_id_seq"
OWNED BY "sepud"."oct_fleet"."id";
SELECT setval('"sepud"."oct_fleet_id_seq"', 83, true);
ALTER SEQUENCE "sepud"."oct_garrison_history_id_seq"
OWNED BY "sepud"."oct_workshift_history"."id";
SELECT setval('"sepud"."oct_garrison_history_id_seq"', 7197, true);
ALTER SEQUENCE "sepud"."oct_garrison_id_seq"
OWNED BY "sepud"."oct_garrison"."id";
SELECT setval('"sepud"."oct_garrison_id_seq"', 17623, true);
ALTER SEQUENCE "sepud"."oct_providence_id_seq"
OWNED BY "sepud"."oct_providence"."id";
SELECT setval('"sepud"."oct_providence_id_seq"', 53, true);
ALTER SEQUENCE "sepud"."oct_rel_events_images_id_seq"
OWNED BY "sepud"."oct_rel_events_images"."id";
SELECT setval('"sepud"."oct_rel_events_images_id_seq"', 14780, true);
ALTER SEQUENCE "sepud"."oct_rel_events_providence_id_seq"
OWNED BY "sepud"."oct_rel_events_providence"."id";
SELECT setval('"sepud"."oct_rel_events_providence_id_seq"', 25818, true);
ALTER SEQUENCE "sepud"."oct_rel_garrison_persona_id_seq"
OWNED BY "sepud"."oct_rel_garrison_persona"."id";
SELECT setval('"sepud"."oct_rel_garrison_persona_id_seq"', 34787, true);
ALTER SEQUENCE "sepud"."oct_rel_garrison_vehicle_id_seq"
OWNED BY "sepud"."oct_rel_garrison_vehicle"."id";
SELECT setval('"sepud"."oct_rel_garrison_vehicle_id_seq"', 19171, true);
ALTER SEQUENCE "sepud"."oct_rel_workshift_persona_id_seq"
OWNED BY "sepud"."oct_rel_workshift_persona"."id";
SELECT setval('"sepud"."oct_rel_workshift_persona_id_seq"', 54901, true);
ALTER SEQUENCE "sepud"."oct_vehicle_type_id_seq"
OWNED BY "sepud"."oct_vehicle_type"."id";
SELECT setval('"sepud"."oct_vehicle_type_id_seq"', 18, true);
ALTER SEQUENCE "sepud"."oct_vehicles_id_seq"
OWNED BY "sepud"."oct_vehicles"."id";
SELECT setval('"sepud"."oct_vehicles_id_seq"', 7283, true);
ALTER SEQUENCE "sepud"."oct_victim_id_seq"
OWNED BY "sepud"."oct_victim"."id";
SELECT setval('"sepud"."oct_victim_id_seq"', 3748, true);
ALTER SEQUENCE "sepud"."oct_workshift_id_seq"
OWNED BY "sepud"."oct_workshift"."id";
SELECT setval('"sepud"."oct_workshift_id_seq"', 1480, true);
ALTER SEQUENCE "sepud"."od_origem_destino_id_seq"
OWNED BY "sepud"."od_origem_destino"."id";
SELECT setval('"sepud"."od_origem_destino_id_seq"', 2, false);
ALTER SEQUENCE "sepud"."rot_data_sensors_id_seq"
OWNED BY "sepud"."rot_data_sensors"."id";
SELECT setval('"sepud"."rot_data_sensors_id_seq"', 52072, true);
ALTER SEQUENCE "sepud"."rot_equipments_id_seq"
OWNED BY "sepud"."rot_equipments"."id";
SELECT setval('"sepud"."rot_equipments_id_seq"', 7, true);
ALTER SEQUENCE "sepud"."sas_request_id_seq"
OWNED BY "sepud"."sas_request"."id";
SELECT setval('"sepud"."sas_request_id_seq"', 2510, true);
ALTER SEQUENCE "sepud"."sas_users_id_seq"
OWNED BY "sepud"."sas_citizen"."id";
SELECT setval('"sepud"."sas_users_id_seq"', 2502, true);
ALTER SEQUENCE "sepud"."sas_vars_id_seq"
OWNED BY "sepud"."sas_vars"."id";
SELECT setval('"sepud"."sas_vars_id_seq"', 22, true);
ALTER SEQUENCE "sepud"."sau_pncd_id_seq"
OWNED BY "sepud"."ses_pncd_registro_diario"."id";
SELECT setval('"sepud"."sau_pncd_id_seq"', 25, true);
ALTER SEQUENCE "sepud"."ses_pe_id_seq"
OWNED BY "sepud"."ses_pe"."id";
SELECT setval('"sepud"."ses_pe_id_seq"', 576, true);
ALTER SEQUENCE "sepud"."ses_pncd_registro_diario_atividade_id_seq"
OWNED BY "sepud"."ses_pncd_registro_diario_atividade"."id";
SELECT setval('"sepud"."ses_pncd_registro_diario_atividade_id_seq"', 12, true);
ALTER SEQUENCE "sepud"."ses_trap_id_seq"
OWNED BY "sepud"."ses_trap"."id";
SELECT setval('"sepud"."ses_trap_id_seq"', 1452, true);
ALTER SEQUENCE "sepud"."streets_id_seq"
OWNED BY "sepud"."streets"."id";
SELECT setval('"sepud"."streets_id_seq"', 4167, true);
ALTER SEQUENCE "sepud"."user_permission_submodules_id_seq"
OWNED BY "sepud"."users_perm_modules_subgroup"."id";
SELECT setval('"sepud"."user_permission_submodules_id_seq"', 27, true);
ALTER SEQUENCE "sepud"."users_id_seq"
OWNED BY "sepud"."users"."id";
SELECT setval('"sepud"."users_id_seq"', 2564, true);
ALTER SEQUENCE "sepud"."users_perm_modules_id_seq"
OWNED BY "sepud"."users_perm_modules"."id";
SELECT setval('"sepud"."users_perm_modules_id_seq"', 9, true);

-- ----------------------------
-- Primary Key structure for table company
-- ----------------------------
ALTER TABLE "sepud"."company" ADD CONSTRAINT "company_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_parking_type
-- ----------------------------
ALTER TABLE "sepud"."eri_parking_type" ADD CONSTRAINT "eri_parking_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_schedule_parking
-- ----------------------------
ALTER TABLE "sepud"."eri_schedule_parking" ADD CONSTRAINT "eri_schedule_parking_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table gsec_callcenter
-- ----------------------------
ALTER TABLE "sepud"."gsec_callcenter" ADD CONSTRAINT "gsec_callcenter_pkey1" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table gsec_citizen
-- ----------------------------
ALTER TABLE "sepud"."gsec_citizen" ADD CONSTRAINT "gsec_callcenter_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table gsec_request_type
-- ----------------------------
ALTER TABLE "sepud"."gsec_request_type" ADD CONSTRAINT "gsec_request_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table hospital
-- ----------------------------
ALTER TABLE "sepud"."hospital" ADD CONSTRAINT "hospital_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table logs
-- ----------------------------
CREATE INDEX "idx_logs_0" ON "sepud"."logs" USING btree (
  "timestamp" "pg_catalog"."timestamp_ops" DESC NULLS FIRST
);

-- ----------------------------
-- Primary Key structure for table logs
-- ----------------------------
ALTER TABLE "sepud"."logs" ADD CONSTRAINT "logs_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table neighborhood
-- ----------------------------
ALTER TABLE "sepud"."neighborhood" ADD CONSTRAINT "neighborhood_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_conditions
-- ----------------------------
ALTER TABLE "sepud"."oct_event_conditions" ADD CONSTRAINT "oct_event_conditions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_type
-- ----------------------------
ALTER TABLE "sepud"."oct_event_type" ADD CONSTRAINT "oct_event_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_events
-- ----------------------------
ALTER TABLE "sepud"."oct_events" ADD CONSTRAINT "oct_events_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_fleet
-- ----------------------------
ALTER TABLE "sepud"."oct_fleet" ADD CONSTRAINT "oct_fleet_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_providence
-- ----------------------------
ALTER TABLE "sepud"."oct_providence" ADD CONSTRAINT "oct_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_images
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_images" ADD CONSTRAINT "oct_rel_events_images_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_providence
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_providence" ADD CONSTRAINT "oct_rel_events_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicle_type
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicle_type" ADD CONSTRAINT "oct_vehicle_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicles" ADD CONSTRAINT "oct_vehicles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_victim
-- ----------------------------
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "oct_victim_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_workshift
-- ----------------------------
ALTER TABLE "sepud"."oct_workshift" ADD CONSTRAINT "oct_workshift_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "sepud"."rot_data_sensors" ADD CONSTRAINT "rot_data_sensors_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_equipments
-- ----------------------------
ALTER TABLE "sepud"."rot_equipments" ADD CONSTRAINT "rot_equipments_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table sas_citizen
-- ----------------------------
ALTER TABLE "sepud"."sas_citizen" ADD CONSTRAINT "unique_idx" UNIQUE ("name", "date", "id_user_register");

-- ----------------------------
-- Primary Key structure for table sas_citizen
-- ----------------------------
ALTER TABLE "sepud"."sas_citizen" ADD CONSTRAINT "sas_users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table sas_request
-- ----------------------------
ALTER TABLE "sepud"."sas_request" ADD CONSTRAINT "uniquekey" UNIQUE ("id_user", "date", "id_citizen");

-- ----------------------------
-- Primary Key structure for table sas_request
-- ----------------------------
ALTER TABLE "sepud"."sas_request" ADD CONSTRAINT "sas_request_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table sas_vars
-- ----------------------------
ALTER TABLE "sepud"."sas_vars" ADD CONSTRAINT "sas_vars_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table ses_pe
-- ----------------------------
ALTER TABLE "sepud"."ses_pe" ADD CONSTRAINT "ses_pe_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table ses_pncd_registro_diario
-- ----------------------------
ALTER TABLE "sepud"."ses_pncd_registro_diario" ADD CONSTRAINT "sau_pncd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table ses_pncd_registro_diario_atividade
-- ----------------------------
ALTER TABLE "sepud"."ses_pncd_registro_diario_atividade" ADD CONSTRAINT "ses_pncd_registro_diario_atividade_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table ses_trap
-- ----------------------------
ALTER TABLE "sepud"."ses_trap" ADD CONSTRAINT "ses_trap_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table streets
-- ----------------------------
ALTER TABLE "sepud"."streets" ADD CONSTRAINT "streets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "sepud"."users" ADD CONSTRAINT "idx0" UNIQUE ("email");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "sepud"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table users_perm_modules_subgroup
-- ----------------------------
ALTER TABLE "sepud"."users_perm_modules_subgroup" ADD CONSTRAINT "users_perm_modules_subgroup_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table users_rel_perm_user
-- ----------------------------
ALTER TABLE "sepud"."users_rel_perm_user" ADD CONSTRAINT "idx_unique" PRIMARY KEY ("id_user");

-- ----------------------------
-- Foreign Keys structure for table oct_events
-- ----------------------------
ALTER TABLE "sepud"."oct_events" ADD CONSTRAINT "fk_id_event_type" FOREIGN KEY ("id_event_type") REFERENCES "sepud"."oct_event_type" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_event_conditions
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_event_conditions" ADD CONSTRAINT "fk_event_conditions" FOREIGN KEY ("id_event_conditions") REFERENCES "sepud"."oct_event_conditions" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_rel_events_event_conditions" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_observations
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_observations" ADD CONSTRAINT "fk01" FOREIGN KEY ("id_user") REFERENCES "sepud"."users" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_rel_events_observations" ADD CONSTRAINT "fk02" FOREIGN KEY ("id_event") REFERENCES "sepud"."oct_events" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicles" ADD CONSTRAINT "fk_id_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_victim
-- ----------------------------
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "fk_vehicle" FOREIGN KEY ("id_vehicle") REFERENCES "sepud"."oct_vehicles" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "sepud"."rot_data_sensors" ADD CONSTRAINT "fk_rot_equipments" FOREIGN KEY ("equipment_id") REFERENCES "sepud"."rot_equipments" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
