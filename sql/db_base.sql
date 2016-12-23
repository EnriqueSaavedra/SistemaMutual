-- Database generated with pgModeler (PostgreSQL Database Modeler).
-- PostgreSQL version: 9.2
-- Project Site: pgmodeler.com.br
-- Model Author: ---

SET check_function_bodies = false;
-- ddl-end --


-- Database creation must be done outside an multicommand file.
-- These commands were put in this file only for convenience.
-- -- object: new_database | type: DATABASE --
-- CREATE DATABASE new_database
-- ;
-- -- ddl-end --
-- 

-- object: public.curso | type: TABLE --
CREATE TABLE public.curso(
	id serial,
	tipo_curso integer NOT NULL,
	fecha_inicio date NOT NULL,
	participantes integer DEFAULT 0 NOT NULL,
	empresa integer NOT NULL,
	numero_calle integer NOT NULL,
	direccion character varying(255) NOT NULL,
	direccion_adicional character varying(64),
	comuna integer NOT NULL,
	relator integer NOT NULL,
	contacto_nombre character varying(64),
	contacto_email character varying(255),
	fecha_proceso timestamp without time zone NOT NULL DEFAULT now(),
	origen integer NOT NULL,
	usuario integer NOT NULL,
	adicionales json,
	CONSTRAINT curso_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.rechazos | type: TABLE --
CREATE TABLE public.rechazos(
	id_curso_referencia integer,
	tipo_curso integer,
	fecha_inicio date,
	comuna integer,
        numero_calle integer,
	participantes integer,
	empresa integer,
	direccion character varying(255),
	direccion_adicional character varying(64),
	relator integer,
	contacto_nombre character varying(64),
	contacto_email character varying(255),
	fecha_proceso date,
	origen integer,
	usuario integer
);
-- ddl-end --
-- object: public.empresa | type: TABLE --
CREATE TABLE public.empresa(
	id serial,
	rut integer NOT NULL,
	dv character varying NOT NULL,
	adherente integer NOT NULL,
	CONSTRAINT empresa_pk PRIMARY KEY (id),
	CONSTRAINT empresa_regla_adherente UNIQUE (adherente)

);
-- ddl-end --
-- object: public.relator | type: TABLE --
CREATE TABLE public.relator(
	id serial,
	nombre character varying(64) NOT NULL,
	rut integer NOT NULL,
	dv character varying NOT NULL,
	CONSTRAINT relator_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.comuna | type: TABLE --
CREATE TABLE public.comuna(
	id serial,
	nombre character varying(64) NOT NULL,
	CONSTRAINT comuna_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.tipo_curso | type: TABLE --
CREATE TABLE public.tipo_curso(
	id serial,
	nombre text NOT NULL,
	alias_curso text,
        objetivo text,
	CONSTRAINT tipo_curso_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.origen | type: TABLE --
CREATE TABLE public.origen(
	id serial,
	nombre character varying(64) NOT NULL,
	prioridad integer NOT NULL DEFAULT 3,
	CONSTRAINT origen_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.participante | type: TABLE --
CREATE TABLE public.participante(
	id serial,
	rut integer NOT NULL,
	dv character varying NOT NULL,
	nombre character varying(64) NOT NULL,
	email character varying(255) NOT NULL,
	edad integer NOT NULL,
	sexo character varying NOT NULL,
	CONSTRAINT participante_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.planilla | type: TABLE --
CREATE TABLE public.planilla(
	id serial,
	curso integer,
	participante integer,
	estado integer NOT NULL,
	create_time date NOT NULL DEFAULT now(),
	update_time date NOT NULL DEFAULT now(),
	CONSTRAINT planilla_pk PRIMARY KEY (id),
	CONSTRAINT regla_unica UNIQUE (curso,participante)

);
-- ddl-end --
-- object: public.usuario | type: TABLE --
CREATE TABLE public.usuario(
	id serial,
	nombre character varying(64) NOT NULL,
	email character varying(255) NOT NULL,
	clave character varying(255) NOT NULL,
	token_usuario character varying(255),
	tipo_usuario integer NOT NULL,
        activo boolean NOT NULL DEFAULT 't',
	CONSTRAINT usuario_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.grupo_usuario | type: TABLE --
CREATE TABLE public.grupo_usuario(
	id serial,
	nombre character varying(64) NOT NULL,
	CONSTRAINT grupo_usuario_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.mutual_documento | type: TABLE --
CREATE TABLE public.mutual_documento(
	id serial,
	listado_planillas json,
	fecha_emision date NOT NULL DEFAULT now(),
	planilla_respaldo text,
	CONSTRAINT mutual_documento_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: public.grupo_permisos | type: TABLE --
CREATE TABLE public.grupo_permisos(
	id integer,
	ruta_permiso text NOT NULL,
	grupo_usuario integer NOT NULL,
	CONSTRAINT grupo_permisos_pk PRIMARY KEY (id)

);
-- ddl-end --
-- object: curso_usuario | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_usuario FOREIGN KEY (usuario)
REFERENCES public.usuario (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: curso_relator | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_relator FOREIGN KEY (relator)
REFERENCES public.relator (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: curso_empresa | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_empresa FOREIGN KEY (empresa)
REFERENCES public.empresa (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: curso_tipo_curso | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_tipo_curso FOREIGN KEY (tipo_curso)
REFERENCES public.tipo_curso (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: curso_origen | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_origen FOREIGN KEY (origen)
REFERENCES public.origen (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: curso_comuna | type: CONSTRAINT --
ALTER TABLE public.curso ADD CONSTRAINT curso_comuna FOREIGN KEY (comuna)
REFERENCES public.comuna (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: planilla_participante | type: CONSTRAINT --
ALTER TABLE public.planilla ADD CONSTRAINT planilla_participante FOREIGN KEY (participante)
REFERENCES public.participante (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: planilla_curso | type: CONSTRAINT --
ALTER TABLE public.planilla ADD CONSTRAINT planilla_curso FOREIGN KEY (curso)
REFERENCES public.curso (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: usuario_grupo_usuario | type: CONSTRAINT --
ALTER TABLE public.usuario ADD CONSTRAINT usuario_grupo_usuario FOREIGN KEY (tipo_usuario)
REFERENCES public.grupo_usuario (id) MATCH FULL
ON DELETE CASCADE ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --


-- object: grupo_usuario_permisos | type: CONSTRAINT --
ALTER TABLE public.grupo_permisos ADD CONSTRAINT grupo_usuario_permisos FOREIGN KEY (grupo_usuario)
REFERENCES public.grupo_usuario (id) MATCH FULL
ON DELETE NO ACTION ON UPDATE NO ACTION NOT DEFERRABLE;
-- ddl-end --



