/*
 Target Server Type    : PostgreSQL
 Target Server Version : 100004
 File Encoding         : 65001

 Date: 01/08/2019 09:42:00
*/


-- ----------------------------
-- Sequence structure for company_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "company_id_seq";
CREATE SEQUENCE "company_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "eri_parking_id_seq";
CREATE SEQUENCE "eri_parking_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "eri_parking_type_id_seq";
CREATE SEQUENCE "eri_parking_type_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_schedule_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "eri_schedule_parking_id_seq";
CREATE SEQUENCE "eri_schedule_parking_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for hospital_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "hospital_id_seq";
CREATE SEQUENCE "hospital_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_addressbook_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_addressbook_id_seq";
CREATE SEQUENCE "oct_addressbook_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_administrative_events_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_administrative_events_id_seq";
CREATE SEQUENCE "oct_administrative_events_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_conditions_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_event_conditions_id_seq";
CREATE SEQUENCE "oct_event_conditions_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_event_type_id_seq";
CREATE SEQUENCE "oct_event_type_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_events_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_events_id_seq";
CREATE SEQUENCE "oct_events_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_fleet_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_fleet_id_seq";
CREATE SEQUENCE "oct_fleet_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_garrison_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_garrison_id_seq";
CREATE SEQUENCE "oct_garrison_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_providence_id_seq";
CREATE SEQUENCE "oct_providence_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_images_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_rel_events_images_id_seq";
CREATE SEQUENCE "oct_rel_events_images_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_rel_events_providence_id_seq";
CREATE SEQUENCE "oct_rel_events_providence_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_workshift_persona_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_rel_workshift_persona_id_seq";
CREATE SEQUENCE "oct_rel_workshift_persona_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicle_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_vehicle_type_id_seq";
CREATE SEQUENCE "oct_vehicle_type_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_vehicles_id_seq";
CREATE SEQUENCE "oct_vehicles_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_victim_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_victim_id_seq";
CREATE SEQUENCE "oct_victim_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_workshift_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "oct_workshift_id_seq";
CREATE SEQUENCE "oct_workshift_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for od_origem_destino_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "od_origem_destino_id_seq";
CREATE SEQUENCE "od_origem_destino_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_data_sensors_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "rot_data_sensors_id_seq";
CREATE SEQUENCE "rot_data_sensors_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_equipments_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "rot_equipments_id_seq";
CREATE SEQUENCE "rot_equipments_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for streets_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "streets_id_seq";
CREATE SEQUENCE "streets_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "users_id_seq";
CREATE SEQUENCE "users_id_seq"
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS "company";
CREATE TABLE "company" (
  "id" int4 NOT NULL DEFAULT nextval('company_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "acron" text COLLATE "pg_catalog"."default",
  "workshift_groups_repetition" int2,
  "workshift_groups" json,
  "workshift_subgroups_repetition" varchar(255) COLLATE "pg_catalog"."default",
  "workshift_subgroups" json,
  "workshift_rel_config" json
)
;
ALTER TABLE "company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking
-- ----------------------------
DROP TABLE IF EXISTS "eri_parking";
CREATE TABLE "eri_parking" (
  "id" int4 NOT NULL DEFAULT nextval('eri_parking_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "active" bool,
  "description" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "id_parking_type" int4
)
;
ALTER TABLE "eri_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking_type
-- ----------------------------
DROP TABLE IF EXISTS "eri_parking_type";
CREATE TABLE "eri_parking_type" (
  "id" int4 NOT NULL DEFAULT nextval('eri_parking_type_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "time" int4,
  "time_warning" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "multi_parking" bool DEFAULT false
)
;
ALTER TABLE "eri_parking_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_schedule_parking
-- ----------------------------
DROP TABLE IF EXISTS "eri_schedule_parking";
CREATE TABLE "eri_schedule_parking" (
  "id" int4 NOT NULL DEFAULT nextval('eri_schedule_parking_id_seq'::regclass),
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
  "id_user_winch" int4
)
;
ALTER TABLE "eri_schedule_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for hospital
-- ----------------------------
DROP TABLE IF EXISTS "hospital";
CREATE TABLE "hospital" (
  "id" int2 NOT NULL DEFAULT nextval('hospital_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "hospital" OWNER TO "postgres";

-- ----------------------------
-- Table structure for iq_pesquisa
-- ----------------------------
DROP TABLE IF EXISTS "iq_pesquisa";
CREATE TABLE "iq_pesquisa" (
  "iq" text COLLATE "pg_catalog"."default",
  "status" text COLLATE "pg_catalog"."default",
  "ano" int4
)
;
ALTER TABLE "iq_pesquisa" OWNER TO "postgres";

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS "logs";
CREATE TABLE "logs" (
  "ip" varchar(255) COLLATE "pg_catalog"."default",
  "id_user" int4,
  "module" varchar(255) COLLATE "pg_catalog"."default",
  "action" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0),
  "obs" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "logs" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_addressbook
-- ----------------------------
DROP TABLE IF EXISTS "oct_addressbook";
CREATE TABLE "oct_addressbook" (
  "id" int2 NOT NULL DEFAULT nextval('oct_addressbook_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "numRef" int2,
  "id_street" int4,
  "geoposition" text COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "zipcode" text COLLATE "pg_catalog"."default",
  "neighborhood" text COLLATE "pg_catalog"."default",
  "zone" text COLLATE "pg_catalog"."default",
  "nonMappedStreet" text COLLATE "pg_catalog"."default",
  "id_company" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_addressbook" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_administrative_events
-- ----------------------------
DROP TABLE IF EXISTS "oct_administrative_events";
CREATE TABLE "oct_administrative_events" (
  "id" int4 NOT NULL DEFAULT nextval('oct_administrative_events_id_seq'::regclass),
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
ALTER TABLE "oct_administrative_events" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "oct_event_conditions";
CREATE TABLE "oct_event_conditions" (
  "id" int2 NOT NULL DEFAULT nextval('oct_event_conditions_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "subtype" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_type
-- ----------------------------
DROP TABLE IF EXISTS "oct_event_type";
CREATE TABLE "oct_event_type" (
  "id" int2 NOT NULL DEFAULT nextval('oct_event_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "name_acron" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "type" text COLLATE "pg_catalog"."default",
  "active" bool DEFAULT true
)
;
ALTER TABLE "oct_event_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_events
-- ----------------------------
DROP TABLE IF EXISTS "oct_events";
CREATE TABLE "oct_events" (
  "id" int4 NOT NULL DEFAULT nextval('oct_events_id_seq'::regclass),
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
  "id_garrison" int4
)
;
ALTER TABLE "oct_events" OWNER TO "postgres";
COMMENT ON COLUMN "oct_events"."date" IS 'Data/hora do registro inicial do sistema';
COMMENT ON COLUMN "oct_events"."arrival" IS 'Data/hora de chegada no local do atendimento';
COMMENT ON COLUMN "oct_events"."closure" IS 'Data/hora do encerramento da ocorrencia';

-- ----------------------------
-- Table structure for oct_fleet
-- ----------------------------
DROP TABLE IF EXISTS "oct_fleet";
CREATE TABLE "oct_fleet" (
  "id" int4 NOT NULL DEFAULT nextval('oct_fleet_id_seq'::regclass),
  "id_company" int4,
  "plate" varchar(255) COLLATE "pg_catalog"."default",
  "type" varchar(255) COLLATE "pg_catalog"."default",
  "model" varchar(255) COLLATE "pg_catalog"."default",
  "brand" varchar(255) COLLATE "pg_catalog"."default",
  "nickname" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_fleet" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_garrison
-- ----------------------------
DROP TABLE IF EXISTS "oct_garrison";
CREATE TABLE "oct_garrison" (
  "id" int4 NOT NULL DEFAULT nextval('oct_garrison_id_seq'::regclass),
  "id_fleet" int4,
  "id_workshift" int4,
  "opened" timestamp(0),
  "closed" timestamp(0),
  "initial_fuel" text COLLATE "pg_catalog"."default",
  "final_fuel" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default",
  "initial_km" int4,
  "final_km" int4
)
;
ALTER TABLE "oct_garrison" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_providence
-- ----------------------------
DROP TABLE IF EXISTS "oct_providence";
CREATE TABLE "oct_providence" (
  "id" int4 NOT NULL DEFAULT nextval('oct_providence_id_seq'::regclass),
  "table_orgim" varchar(255) COLLATE "pg_catalog"."default",
  "title" varchar(255) COLLATE "pg_catalog"."default",
  "area" varchar(255) COLLATE "pg_catalog"."default",
  "providence" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_event_type_company
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_event_type_company";
CREATE TABLE "oct_rel_event_type_company" (
  "id_company" int4,
  "id_event_type" int4
)
;
ALTER TABLE "oct_rel_event_type_company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_events_event_conditions";
CREATE TABLE "oct_rel_events_event_conditions" (
  "id_events" int4,
  "id_event_conditions" int2
)
;
ALTER TABLE "oct_rel_events_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_images
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_events_images";
CREATE TABLE "oct_rel_events_images" (
  "id" int4 NOT NULL DEFAULT nextval('oct_rel_events_images_id_seq'::regclass),
  "id_events" int4,
  "image" varchar(255) COLLATE "pg_catalog"."default",
  "path" varchar(255) COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0)
)
;
ALTER TABLE "oct_rel_events_images" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_observations
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_events_observations";
CREATE TABLE "oct_rel_events_observations" (
  "id_user" int2,
  "id_event" int8,
  "id_type" int2,
  "text" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0) DEFAULT now()
)
;
ALTER TABLE "oct_rel_events_observations" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_providence
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_events_providence";
CREATE TABLE "oct_rel_events_providence" (
  "id" int4 NOT NULL DEFAULT nextval('oct_rel_events_providence_id_seq'::regclass),
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
ALTER TABLE "oct_rel_events_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_garrison_persona
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_garrison_persona";
CREATE TABLE "oct_rel_garrison_persona" (
  "id_garrison" int4,
  "id_user" int4,
  "type" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_rel_garrison_persona" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_workshift_persona
-- ----------------------------
DROP TABLE IF EXISTS "oct_rel_workshift_persona";
CREATE TABLE "oct_rel_workshift_persona" (
  "id_shift" int4,
  "id_person" int4,
  "opened" timestamp(0),
  "closed" timestamp(0),
  "type" text COLLATE "pg_catalog"."default",
  "id" int4 NOT NULL DEFAULT nextval('oct_rel_workshift_persona_id_seq'::regclass),
  "status" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_rel_workshift_persona" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicle_type
-- ----------------------------
DROP TABLE IF EXISTS "oct_vehicle_type";
CREATE TABLE "oct_vehicle_type" (
  "id" int2 NOT NULL DEFAULT nextval('oct_vehicle_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "desc" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "oct_vehicle_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicles
-- ----------------------------
DROP TABLE IF EXISTS "oct_vehicles";
CREATE TABLE "oct_vehicles" (
  "id" int4 NOT NULL DEFAULT nextval('oct_vehicles_id_seq'::regclass),
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
ALTER TABLE "oct_vehicles" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_victim
-- ----------------------------
DROP TABLE IF EXISTS "oct_victim";
CREATE TABLE "oct_victim" (
  "id" int4 NOT NULL DEFAULT nextval('oct_victim_id_seq'::regclass),
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
  "conducted_by_id_user" int4
)
;
ALTER TABLE "oct_victim" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_workshift
-- ----------------------------
DROP TABLE IF EXISTS "oct_workshift";
CREATE TABLE "oct_workshift" (
  "id" int4 NOT NULL DEFAULT nextval('oct_workshift_id_seq'::regclass),
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
ALTER TABLE "oct_workshift" OWNER TO "postgres";

-- ----------------------------
-- Table structure for od_origem_destino
-- ----------------------------
DROP TABLE IF EXISTS "od_origem_destino";
CREATE TABLE "od_origem_destino" (
  "id" int4 NOT NULL DEFAULT nextval('od_origem_destino_id_seq'::regclass),
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
ALTER TABLE "od_origem_destino" OWNER TO "postgres";
COMMENT ON COLUMN "od_origem_destino"."modal" IS 'Onibus, carro, bicicleta, carona, etc…';
COMMENT ON COLUMN "od_origem_destino"."tipo" IS '(profissional, estudo, laser, saúde...)';
COMMENT ON COLUMN "od_origem_destino"."fonte" IS 'Saude, RH Empresas, Educação...';

-- ----------------------------
-- Table structure for resume
-- ----------------------------
DROP TABLE IF EXISTS "resume";
CREATE TABLE "resume" (
  "field" varchar COLLATE "pg_catalog"."default",
  "int_value" int8,
  "str_value" varchar COLLATE "pg_catalog"."default",
  "type" varchar COLLATE "pg_catalog"."default",
  "module" varchar COLLATE "pg_catalog"."default",
  "ref_month" int2,
  "ref_year" int2
)
;
ALTER TABLE "resume" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_data_sensors
-- ----------------------------
DROP TABLE IF EXISTS "rot_data_sensors";
CREATE TABLE "rot_data_sensors" (
  "id" int4 NOT NULL DEFAULT nextval('rot_data_sensors_id_seq'::regclass),
  "event_id" int4,
  "equipment_id" int4,
  "datetime" timestamp(0),
  "geo_position" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "value" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "rot_data_sensors" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_equipments
-- ----------------------------
DROP TABLE IF EXISTS "rot_equipments";
CREATE TABLE "rot_equipments" (
  "name" text COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "last_activity" timestamp(6),
  "id" int4 NOT NULL DEFAULT nextval('rot_equipments_id_seq'::regclass),
  "active" bool DEFAULT true
)
;
ALTER TABLE "rot_equipments" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sepud_cadastro_imoveis
-- ----------------------------
DROP TABLE IF EXISTS "sepud_cadastro_imoveis";
CREATE TABLE "sepud_cadastro_imoveis" (
  "iq" varchar(255) COLLATE "pg_catalog"."default",
  "uso" varchar(255) COLLATE "pg_catalog"."default",
  "ano" int2
)
;
ALTER TABLE "sepud_cadastro_imoveis" OWNER TO "postgres";

-- ----------------------------
-- Table structure for streets
-- ----------------------------
DROP TABLE IF EXISTS "streets";
CREATE TABLE "streets" (
  "id" int2 NOT NULL DEFAULT nextval('streets_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "is_rotate_parking" bool DEFAULT false
)
;
ALTER TABLE "streets" OWNER TO "postgres";

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
  "id" int4 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "email" text COLLATE "pg_catalog"."default",
  "password" text COLLATE "pg_catalog"."default",
  "nickname" text COLLATE "pg_catalog"."default",
  "area" text COLLATE "pg_catalog"."default",
  "job" text COLLATE "pg_catalog"."default",
  "active" bool,
  "in_ativaction" bool DEFAULT false,
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
  "workshift_subgroup" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "users" OWNER TO "postgres";
COMMENT ON COLUMN "users"."id" IS 'unique index';
COMMENT ON COLUMN "users"."name" IS 'Fullname';
COMMENT ON COLUMN "users"."email" IS 'E-mail address, use to auth';
COMMENT ON COLUMN "users"."password" IS 'MD5 password';
COMMENT ON COLUMN "users"."registration" IS 'Matricula do funcionario';

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "company_id_seq"
OWNED BY "company"."id";
SELECT setval('"company_id_seq"', 11, true);
ALTER SEQUENCE "eri_parking_id_seq"
OWNED BY "eri_parking"."id";
SELECT setval('"eri_parking_id_seq"', 1002, true);
ALTER SEQUENCE "eri_parking_type_id_seq"
OWNED BY "eri_parking_type"."id";
SELECT setval('"eri_parking_type_id_seq"', 17, true);
ALTER SEQUENCE "eri_schedule_parking_id_seq"
OWNED BY "eri_schedule_parking"."id";
SELECT setval('"eri_schedule_parking_id_seq"', 110256, true);
ALTER SEQUENCE "hospital_id_seq"
OWNED BY "hospital"."id";
SELECT setval('"hospital_id_seq"', 11, true);
ALTER SEQUENCE "oct_addressbook_id_seq"
OWNED BY "oct_addressbook"."id";
SELECT setval('"oct_addressbook_id_seq"', 1020, true);
ALTER SEQUENCE "oct_administrative_events_id_seq"
OWNED BY "oct_administrative_events"."id";
SELECT setval('"oct_administrative_events_id_seq"', 162, true);
ALTER SEQUENCE "oct_event_conditions_id_seq"
OWNED BY "oct_event_conditions"."id";
SELECT setval('"oct_event_conditions_id_seq"', 15, true);
ALTER SEQUENCE "oct_event_type_id_seq"
OWNED BY "oct_event_type"."id";
SELECT setval('"oct_event_type_id_seq"', 560, true);
ALTER SEQUENCE "oct_events_id_seq"
OWNED BY "oct_events"."id";
SELECT setval('"oct_events_id_seq"', 14109, true);
ALTER SEQUENCE "oct_fleet_id_seq"
OWNED BY "oct_fleet"."id";
SELECT setval('"oct_fleet_id_seq"', 60, true);
ALTER SEQUENCE "oct_garrison_id_seq"
OWNED BY "oct_garrison"."id";
SELECT setval('"oct_garrison_id_seq"', 558, true);
ALTER SEQUENCE "oct_providence_id_seq"
OWNED BY "oct_providence"."id";
SELECT setval('"oct_providence_id_seq"', 26, true);
ALTER SEQUENCE "oct_rel_events_images_id_seq"
OWNED BY "oct_rel_events_images"."id";
SELECT setval('"oct_rel_events_images_id_seq"', 2286, true);
ALTER SEQUENCE "oct_rel_events_providence_id_seq"
OWNED BY "oct_rel_events_providence"."id";
SELECT setval('"oct_rel_events_providence_id_seq"', 4820, true);
ALTER SEQUENCE "oct_rel_workshift_persona_id_seq"
OWNED BY "oct_rel_workshift_persona"."id";
SELECT setval('"oct_rel_workshift_persona_id_seq"', 2191, true);
ALTER SEQUENCE "oct_vehicle_type_id_seq"
OWNED BY "oct_vehicle_type"."id";
SELECT setval('"oct_vehicle_type_id_seq"', 18, true);
ALTER SEQUENCE "oct_vehicles_id_seq"
OWNED BY "oct_vehicles"."id";
SELECT setval('"oct_vehicles_id_seq"', 987, true);
ALTER SEQUENCE "oct_victim_id_seq"
OWNED BY "oct_victim"."id";
SELECT setval('"oct_victim_id_seq"', 510, true);
ALTER SEQUENCE "oct_workshift_id_seq"
OWNED BY "oct_workshift"."id";
SELECT setval('"oct_workshift_id_seq"', 166, true);
ALTER SEQUENCE "od_origem_destino_id_seq"
OWNED BY "od_origem_destino"."id";
SELECT setval('"od_origem_destino_id_seq"', 2, false);
ALTER SEQUENCE "rot_data_sensors_id_seq"
OWNED BY "rot_data_sensors"."id";
SELECT setval('"rot_data_sensors_id_seq"', 52072, true);
ALTER SEQUENCE "rot_equipments_id_seq"
OWNED BY "rot_equipments"."id";
SELECT setval('"rot_equipments_id_seq"', 7, true);
ALTER SEQUENCE "streets_id_seq"
OWNED BY "streets"."id";
SELECT setval('"streets_id_seq"', 4150, true);
ALTER SEQUENCE "users_id_seq"
OWNED BY "users"."id";
SELECT setval('"users_id_seq"', 2145, true);

-- ----------------------------
-- Primary Key structure for table company
-- ----------------------------
ALTER TABLE "company" ADD CONSTRAINT "company_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_parking_type
-- ----------------------------
ALTER TABLE "eri_parking_type" ADD CONSTRAINT "eri_parking_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_schedule_parking
-- ----------------------------
ALTER TABLE "eri_schedule_parking" ADD CONSTRAINT "eri_schedule_parking_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table hospital
-- ----------------------------
ALTER TABLE "hospital" ADD CONSTRAINT "hospital_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_conditions
-- ----------------------------
ALTER TABLE "oct_event_conditions" ADD CONSTRAINT "oct_event_conditions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_type
-- ----------------------------
ALTER TABLE "oct_event_type" ADD CONSTRAINT "oct_event_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_events
-- ----------------------------
ALTER TABLE "oct_events" ADD CONSTRAINT "oct_events_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_fleet
-- ----------------------------
ALTER TABLE "oct_fleet" ADD CONSTRAINT "oct_fleet_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_providence
-- ----------------------------
ALTER TABLE "oct_providence" ADD CONSTRAINT "oct_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_images
-- ----------------------------
ALTER TABLE "oct_rel_events_images" ADD CONSTRAINT "oct_rel_events_images_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_providence
-- ----------------------------
ALTER TABLE "oct_rel_events_providence" ADD CONSTRAINT "oct_rel_events_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicle_type
-- ----------------------------
ALTER TABLE "oct_vehicle_type" ADD CONSTRAINT "oct_vehicle_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "oct_vehicles" ADD CONSTRAINT "oct_vehicles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_victim
-- ----------------------------
ALTER TABLE "oct_victim" ADD CONSTRAINT "oct_victim_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_workshift
-- ----------------------------
ALTER TABLE "oct_workshift" ADD CONSTRAINT "oct_workshift_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "rot_data_sensors" ADD CONSTRAINT "rot_data_sensors_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_equipments
-- ----------------------------
ALTER TABLE "rot_equipments" ADD CONSTRAINT "rot_equipments_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table streets
-- ----------------------------
ALTER TABLE "streets" ADD CONSTRAINT "streets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "users" ADD CONSTRAINT "idx0" UNIQUE ("email");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table oct_events
-- ----------------------------
ALTER TABLE "oct_events" ADD CONSTRAINT "fk_id_event_type" FOREIGN KEY ("id_event_type") REFERENCES "oct_event_type" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_event_conditions
-- ----------------------------
ALTER TABLE "oct_rel_events_event_conditions" ADD CONSTRAINT "fk_event_conditions" FOREIGN KEY ("id_event_conditions") REFERENCES "oct_event_conditions" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "oct_rel_events_event_conditions" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_observations
-- ----------------------------
ALTER TABLE "oct_rel_events_observations" ADD CONSTRAINT "fk01" FOREIGN KEY ("id_user") REFERENCES "users" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "oct_rel_events_observations" ADD CONSTRAINT "fk02" FOREIGN KEY ("id_event") REFERENCES "oct_events" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "oct_vehicles" ADD CONSTRAINT "fk_id_events" FOREIGN KEY ("id_events") REFERENCES "oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_victim
-- ----------------------------
ALTER TABLE "oct_victim" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "oct_victim" ADD CONSTRAINT "fk_vehicle" FOREIGN KEY ("id_vehicle") REFERENCES "oct_vehicles" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "rot_data_sensors" ADD CONSTRAINT "fk_rot_equipments" FOREIGN KEY ("equipment_id") REFERENCES "rot_equipments" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
