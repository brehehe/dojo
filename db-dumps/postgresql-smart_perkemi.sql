--
-- PostgreSQL database dump
--

\restrict FLAqSxPdVZkgQxw32uRvrFKZ9S4FiDQXXQAILRgn6qGKaP3xqhTAqj8cueiGtTY

-- Dumped from database version 16.13 (Ubuntu 16.13-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.13 (Ubuntu 16.13-0ubuntu0.24.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: active_court_referees; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.active_court_referees (
    id bigint NOT NULL,
    court_id bigint NOT NULL,
    referee_id bigint NOT NULL,
    judge_index integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.active_court_referees OWNER TO postgres;

--
-- Name: active_court_referees_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.active_court_referees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.active_court_referees_id_seq OWNER TO postgres;

--
-- Name: active_court_referees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.active_court_referees_id_seq OWNED BY public.active_court_referees.id;


--
-- Name: age_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.age_groups (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    price numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.age_groups OWNER TO postgres;

--
-- Name: age_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.age_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.age_groups_id_seq OWNER TO postgres;

--
-- Name: age_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.age_groups_id_seq OWNED BY public.age_groups.id;


--
-- Name: athlete_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.athlete_category (
    id bigint NOT NULL,
    athlete_id bigint NOT NULL,
    category_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    registration_id bigint NOT NULL
);


ALTER TABLE public.athlete_category OWNER TO postgres;

--
-- Name: athlete_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.athlete_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.athlete_category_id_seq OWNER TO postgres;

--
-- Name: athlete_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.athlete_category_id_seq OWNED BY public.athlete_category.id;


--
-- Name: athlete_contingent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.athlete_contingent (
    id bigint NOT NULL,
    athlete_id bigint NOT NULL,
    contingent_id bigint NOT NULL,
    is_primary boolean DEFAULT true NOT NULL,
    joined_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.athlete_contingent OWNER TO postgres;

--
-- Name: athlete_contingent_histories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.athlete_contingent_histories (
    id bigint NOT NULL,
    athlete_id bigint NOT NULL,
    contingent_id bigint NOT NULL,
    moved_at timestamp(0) without time zone NOT NULL,
    notes character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.athlete_contingent_histories OWNER TO postgres;

--
-- Name: athlete_contingent_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.athlete_contingent_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.athlete_contingent_histories_id_seq OWNER TO postgres;

--
-- Name: athlete_contingent_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.athlete_contingent_histories_id_seq OWNED BY public.athlete_contingent_histories.id;


--
-- Name: athlete_contingent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.athlete_contingent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.athlete_contingent_id_seq OWNER TO postgres;

--
-- Name: athlete_contingent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.athlete_contingent_id_seq OWNED BY public.athlete_contingent.id;


--
-- Name: athlete_match_number; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.athlete_match_number (
    id bigint NOT NULL,
    athlete_id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    registration_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    technique_ids json
);


ALTER TABLE public.athlete_match_number OWNER TO postgres;

--
-- Name: athlete_match_number_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.athlete_match_number_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.athlete_match_number_id_seq OWNER TO postgres;

--
-- Name: athlete_match_number_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.athlete_match_number_id_seq OWNED BY public.athlete_match_number.id;


--
-- Name: athletes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.athletes (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    gender character varying(255) NOT NULL,
    birth_date date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    achievement_history text,
    nik character varying(255),
    bpjs_number character varying(255),
    bpjs_status character varying(255) NOT NULL,
    bpjs_card_path character varying(255),
    identity_document_path character varying(255),
    birth_place character varying(255),
    blood_type character varying(5),
    address text,
    phone character varying(255),
    photo_path character varying(255),
    dojo_origin character varying(255),
    nik_kenshi character varying(255),
    CONSTRAINT athletes_gender_check CHECK (((gender)::text = ANY ((ARRAY['Male'::character varying, 'Female'::character varying])::text[])))
);


ALTER TABLE public.athletes OWNER TO postgres;

--
-- Name: athletes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.athletes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.athletes_id_seq OWNER TO postgres;

--
-- Name: athletes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.athletes_id_seq OWNED BY public.athletes.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    gender character varying(255) NOT NULL,
    age_group character varying(255) NOT NULL,
    weight_class character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    match_type character varying(255),
    CONSTRAINT categories_gender_check CHECK (((gender)::text = ANY ((ARRAY['Male'::character varying, 'Female'::character varying, 'Mixed'::character varying])::text[]))),
    CONSTRAINT categories_type_check CHECK (((type)::text = ANY ((ARRAY['Kata'::character varying, 'Kumite'::character varying, 'Festival'::character varying])::text[])))
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: contingents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contingents (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    leader_name character varying(255) NOT NULL,
    leader_phone character varying(255),
    email character varying(255),
    address text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kab_kota character varying(255),
    verified_at timestamp(0) without time zone,
    verified_by bigint,
    user_id bigint
);


ALTER TABLE public.contingents OWNER TO postgres;

--
-- Name: contingents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contingents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.contingents_id_seq OWNER TO postgres;

--
-- Name: contingents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contingents_id_seq OWNED BY public.contingents.id;


--
-- Name: courts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.courts (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    active_match_id bigint,
    active_registration_id bigint,
    active_bracket_node character varying(255),
    active_drawing_id bigint
);


ALTER TABLE public.courts OWNER TO postgres;

--
-- Name: courts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.courts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.courts_id_seq OWNER TO postgres;

--
-- Name: courts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.courts_id_seq OWNED BY public.courts.id;


--
-- Name: drawing_match_numbers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.drawing_match_numbers (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    registration_id bigint,
    pool_id bigint,
    court_id bigint,
    round character varying(255) DEFAULT 'Penyisihan'::character varying NOT NULL,
    sequence_number integer DEFAULT 0 NOT NULL,
    draft_type character varying(255),
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    schedule_date date,
    rundown_id bigint,
    session_time_id bigint,
    CONSTRAINT drawing_match_numbers_draft_type_check CHECK (((draft_type)::text = ANY ((ARRAY['embu'::character varying, 'randori'::character varying])::text[])))
);


ALTER TABLE public.drawing_match_numbers OWNER TO postgres;

--
-- Name: drawing_match_numbers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.drawing_match_numbers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.drawing_match_numbers_id_seq OWNER TO postgres;

--
-- Name: drawing_match_numbers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.drawing_match_numbers_id_seq OWNED BY public.drawing_match_numbers.id;


--
-- Name: embu_champions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.embu_champions (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    registration_id bigint NOT NULL,
    rank smallint NOT NULL,
    penyisihan_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    final_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    accumulated_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    drawing_id bigint
);


ALTER TABLE public.embu_champions OWNER TO postgres;

--
-- Name: embu_champions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.embu_champions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.embu_champions_id_seq OWNER TO postgres;

--
-- Name: embu_champions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.embu_champions_id_seq OWNED BY public.embu_champions.id;


--
-- Name: embu_scores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.embu_scores (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    registration_id bigint NOT NULL,
    judge_1 numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    judge_2 numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    judge_3 numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    judge_4 numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    judge_5 numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    total_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    rank integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    tiebreak_round smallint DEFAULT '0'::smallint NOT NULL,
    denda numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    nilai_akhir numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    round_label character varying(255) DEFAULT 'Penyisihan'::character varying NOT NULL,
    drawing_id bigint,
    waktu character varying(255)
);


ALTER TABLE public.embu_scores OWNER TO postgres;

--
-- Name: embu_scores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.embu_scores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.embu_scores_id_seq OWNER TO postgres;

--
-- Name: embu_scores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.embu_scores_id_seq OWNED BY public.embu_scores.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: galleries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.galleries (
    id bigint NOT NULL,
    title character varying(255),
    image_url character varying(255) NOT NULL,
    category character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.galleries OWNER TO postgres;

--
-- Name: galleries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.galleries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.galleries_id_seq OWNER TO postgres;

--
-- Name: galleries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.galleries_id_seq OWNED BY public.galleries.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: kyu_levels; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kyu_levels (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255),
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.kyu_levels OWNER TO postgres;

--
-- Name: kyu_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.kyu_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.kyu_levels_id_seq OWNER TO postgres;

--
-- Name: kyu_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.kyu_levels_id_seq OWNED BY public.kyu_levels.id;


--
-- Name: match_number_merge_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.match_number_merge_details (
    id bigint NOT NULL,
    match_number_merge_id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.match_number_merge_details OWNER TO postgres;

--
-- Name: match_number_merge_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.match_number_merge_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.match_number_merge_details_id_seq OWNER TO postgres;

--
-- Name: match_number_merge_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.match_number_merge_details_id_seq OWNED BY public.match_number_merge_details.id;


--
-- Name: match_number_merges; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.match_number_merges (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    age_group_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT match_number_merges_type_check CHECK (((type)::text = ANY ((ARRAY['randori'::character varying, 'embu'::character varying])::text[])))
);


ALTER TABLE public.match_number_merges OWNER TO postgres;

--
-- Name: match_number_merges_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.match_number_merges_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.match_number_merges_id_seq OWNER TO postgres;

--
-- Name: match_number_merges_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.match_number_merges_id_seq OWNED BY public.match_number_merges.id;


--
-- Name: match_numbers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.match_numbers (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    max_athletes integer DEFAULT 0 NOT NULL,
    age_group_id bigint NOT NULL,
    draft_type character varying(255) DEFAULT 'embu'::character varying NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    gender character varying(255) DEFAULT 'Male'::character varying NOT NULL,
    drawing_data json,
    drawing_generated_at timestamp(0) without time zone,
    is_active boolean DEFAULT false NOT NULL,
    active_bracket_node character varying(255),
    active_registration_id bigint,
    match_id character varying(255),
    CONSTRAINT match_numbers_draft_type_check CHECK (((draft_type)::text = ANY ((ARRAY['embu'::character varying, 'randori'::character varying])::text[]))),
    CONSTRAINT match_numbers_gender_check CHECK (((gender)::text = ANY ((ARRAY['Male'::character varying, 'Female'::character varying, 'Mix'::character varying])::text[])))
);


ALTER TABLE public.match_numbers OWNER TO postgres;

--
-- Name: match_numbers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.match_numbers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.match_numbers_id_seq OWNER TO postgres;

--
-- Name: match_numbers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.match_numbers_id_seq OWNED BY public.match_numbers.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO postgres;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO postgres;

--
-- Name: officials; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.officials (
    id bigint NOT NULL,
    contingent_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    phone character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.officials OWNER TO postgres;

--
-- Name: officials_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.officials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.officials_id_seq OWNER TO postgres;

--
-- Name: officials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.officials_id_seq OWNED BY public.officials.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: payment_methods; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payment_methods (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    account_number character varying(255),
    bank character varying(255),
    logo character varying(255),
    "order" integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.payment_methods OWNER TO postgres;

--
-- Name: payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.payment_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.payment_methods_id_seq OWNER TO postgres;

--
-- Name: payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.payment_methods_id_seq OWNED BY public.payment_methods.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: pools; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pools (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.pools OWNER TO postgres;

--
-- Name: pools_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pools_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pools_id_seq OWNER TO postgres;

--
-- Name: pools_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pools_id_seq OWNED BY public.pools.id;


--
-- Name: posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.posts (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    content text NOT NULL,
    image_url character varying(255),
    published_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.posts OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.posts_id_seq OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


--
-- Name: randori_judge_scores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.randori_judge_scores (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    bracket_node character varying(255) NOT NULL,
    judge_index smallint NOT NULL,
    waza_ari_aka integer DEFAULT 0 NOT NULL,
    ippon_aka integer DEFAULT 0 NOT NULL,
    hansoku_aka integer DEFAULT 0 NOT NULL,
    waza_ari_shiro integer DEFAULT 0 NOT NULL,
    ippon_shiro integer DEFAULT 0 NOT NULL,
    hansoku_shiro integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.randori_judge_scores OWNER TO postgres;

--
-- Name: COLUMN randori_judge_scores.bracket_node; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.randori_judge_scores.bracket_node IS 'e.g., ub_0_0';


--
-- Name: COLUMN randori_judge_scores.judge_index; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.randori_judge_scores.judge_index IS '1 to 5';


--
-- Name: randori_judge_scores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.randori_judge_scores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.randori_judge_scores_id_seq OWNER TO postgres;

--
-- Name: randori_judge_scores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.randori_judge_scores_id_seq OWNED BY public.randori_judge_scores.id;


--
-- Name: randori_match_results; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.randori_match_results (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    bracket_node_index integer NOT NULL,
    winner_athlete_id bigint,
    winner_color character varying(255),
    score_red numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    score_blue numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    bracket_node character varying(255),
    bracket_section character varying(255) DEFAULT 'ub'::character varying NOT NULL,
    metadata json
);


ALTER TABLE public.randori_match_results OWNER TO postgres;

--
-- Name: randori_match_results_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.randori_match_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.randori_match_results_id_seq OWNER TO postgres;

--
-- Name: randori_match_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.randori_match_results_id_seq OWNED BY public.randori_match_results.id;


--
-- Name: referee_observations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.referee_observations (
    id bigint NOT NULL,
    contingent_id bigint NOT NULL,
    referee_id bigint,
    observer_name character varying(255) NOT NULL,
    observation_date date NOT NULL,
    court character varying(255) NOT NULL,
    round character varying(255) NOT NULL,
    match_time character varying(255) NOT NULL,
    referee_number character varying(255),
    contingent_away character varying(255),
    contingent_home character varying(255),
    total_score numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    category character varying(255),
    kepada character varying(255),
    dari character varying(255),
    tanggal_laporan date,
    kelebihan text,
    area_perbaikan text,
    rekomendasi text,
    data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.referee_observations OWNER TO postgres;

--
-- Name: referee_observations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.referee_observations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.referee_observations_id_seq OWNER TO postgres;

--
-- Name: referee_observations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.referee_observations_id_seq OWNED BY public.referee_observations.id;


--
-- Name: referee_score_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.referee_score_details (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    referee_id bigint NOT NULL,
    judge_index integer NOT NULL,
    scorable_type character varying(255) NOT NULL,
    scorable_id bigint NOT NULL,
    details json NOT NULL,
    total_calculated_score numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    signature text
);


ALTER TABLE public.referee_score_details OWNER TO postgres;

--
-- Name: referee_score_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.referee_score_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.referee_score_details_id_seq OWNER TO postgres;

--
-- Name: referee_score_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.referee_score_details_id_seq OWNED BY public.referee_score_details.id;


--
-- Name: referees; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.referees (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    nik character varying(255),
    phone character varying(255),
    gender character varying(255),
    birth_place character varying(255),
    birth_date date,
    address text,
    certification_level character varying(255),
    license_number character varying(255),
    province character varying(255),
    city character varying(255),
    photo character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT referees_gender_check CHECK (((gender)::text = ANY ((ARRAY['L'::character varying, 'P'::character varying])::text[])))
);


ALTER TABLE public.referees OWNER TO postgres;

--
-- Name: referees_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.referees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.referees_id_seq OWNER TO postgres;

--
-- Name: referees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.referees_id_seq OWNED BY public.referees.id;


--
-- Name: registration_athlete; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registration_athlete (
    id bigint NOT NULL,
    registration_id bigint NOT NULL,
    athlete_id bigint NOT NULL,
    weight numeric(8,2),
    kyu character varying(255),
    age_group character varying(255),
    rank character varying(255),
    match_type character varying(255),
    dojo_origin character varying(255),
    city character varying(255),
    age integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    weight_group_id bigint
);


ALTER TABLE public.registration_athlete OWNER TO postgres;

--
-- Name: registration_athlete_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registration_athlete_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registration_athlete_id_seq OWNER TO postgres;

--
-- Name: registration_athlete_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registration_athlete_id_seq OWNED BY public.registration_athlete.id;


--
-- Name: registration_official; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registration_official (
    id bigint NOT NULL,
    registration_id bigint NOT NULL,
    official_id bigint NOT NULL,
    role character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.registration_official OWNER TO postgres;

--
-- Name: registration_official_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registration_official_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registration_official_id_seq OWNER TO postgres;

--
-- Name: registration_official_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registration_official_id_seq OWNED BY public.registration_official.id;


--
-- Name: registrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registrations (
    id bigint NOT NULL,
    contingent_id bigint NOT NULL,
    total_cost numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    final_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    unique_code integer DEFAULT 0 NOT NULL,
    payment_method character varying(255),
    referral_code character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    transfer_proof_path character varying(255),
    sim_perkemi_confirm character varying(255) DEFAULT 'Ya'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    draft_data json,
    athlete_status character varying(255) DEFAULT 'pending'::character varying NOT NULL
);


ALTER TABLE public.registrations OWNER TO postgres;

--
-- Name: registrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registrations_id_seq OWNER TO postgres;

--
-- Name: registrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registrations_id_seq OWNED BY public.registrations.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO postgres;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: rundowns; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rundowns (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) DEFAULT 'pertandingan'::character varying NOT NULL,
    description text,
    date timestamp(0) without time zone,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.rundowns OWNER TO postgres;

--
-- Name: rundowns_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rundowns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rundowns_id_seq OWNER TO postgres;

--
-- Name: rundowns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rundowns_id_seq OWNED BY public.rundowns.id;


--
-- Name: schedule_paniteras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.schedule_paniteras (
    id bigint NOT NULL,
    rundown_id bigint NOT NULL,
    session_time_id bigint NOT NULL,
    court_id bigint NOT NULL,
    user_id bigint NOT NULL,
    role_type character varying(255) NOT NULL,
    slot_index smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.schedule_paniteras OWNER TO postgres;

--
-- Name: schedule_paniteras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.schedule_paniteras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.schedule_paniteras_id_seq OWNER TO postgres;

--
-- Name: schedule_paniteras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.schedule_paniteras_id_seq OWNED BY public.schedule_paniteras.id;


--
-- Name: schedule_referees; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.schedule_referees (
    id bigint NOT NULL,
    rundown_id bigint NOT NULL,
    session_time_id bigint NOT NULL,
    court_id bigint,
    referee_id bigint NOT NULL,
    judge_index integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.schedule_referees OWNER TO postgres;

--
-- Name: schedule_referees_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.schedule_referees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.schedule_referees_id_seq OWNER TO postgres;

--
-- Name: schedule_referees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.schedule_referees_id_seq OWNED BY public.schedule_referees.id;


--
-- Name: session_times; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.session_times (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    start_time time(0) without time zone,
    end_time time(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.session_times OWNER TO postgres;

--
-- Name: session_times_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.session_times_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.session_times_id_seq OWNER TO postgres;

--
-- Name: session_times_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.session_times_id_seq OWNED BY public.session_times.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: techniques; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.techniques (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kyu_level_id bigint,
    description character varying(255)
);


ALTER TABLE public.techniques OWNER TO postgres;

--
-- Name: techniques_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.techniques_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.techniques_id_seq OWNER TO postgres;

--
-- Name: techniques_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.techniques_id_seq OWNED BY public.techniques.id;


--
-- Name: tournament_results; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tournament_results (
    id bigint NOT NULL,
    match_number_id bigint NOT NULL,
    draft_type character varying(255) NOT NULL,
    rank smallint NOT NULL,
    registration_id bigint,
    athlete_names text,
    contingent_name text,
    penyisihan_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    final_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    accumulated_score numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    bracket_section text,
    generated_by text,
    confirmed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tournament_results OWNER TO postgres;

--
-- Name: COLUMN tournament_results.draft_type; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.tournament_results.draft_type IS 'Embu or Randori';


--
-- Name: COLUMN tournament_results.rank; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.tournament_results.rank IS '1=Juara 1, 2=Juara 2, 3=Juara 3, 4=Juara 3 Bersama';


--
-- Name: tournament_results_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tournament_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tournament_results_id_seq OWNER TO postgres;

--
-- Name: tournament_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tournament_results_id_seq OWNED BY public.tournament_results.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    court_id bigint,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    judge_index smallint
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: weight_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.weight_groups (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.weight_groups OWNER TO postgres;

--
-- Name: weight_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.weight_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.weight_groups_id_seq OWNER TO postgres;

--
-- Name: weight_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.weight_groups_id_seq OWNED BY public.weight_groups.id;


--
-- Name: active_court_referees id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.active_court_referees ALTER COLUMN id SET DEFAULT nextval('public.active_court_referees_id_seq'::regclass);


--
-- Name: age_groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.age_groups ALTER COLUMN id SET DEFAULT nextval('public.age_groups_id_seq'::regclass);


--
-- Name: athlete_category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_category ALTER COLUMN id SET DEFAULT nextval('public.athlete_category_id_seq'::regclass);


--
-- Name: athlete_contingent id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent ALTER COLUMN id SET DEFAULT nextval('public.athlete_contingent_id_seq'::regclass);


--
-- Name: athlete_contingent_histories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent_histories ALTER COLUMN id SET DEFAULT nextval('public.athlete_contingent_histories_id_seq'::regclass);


--
-- Name: athlete_match_number id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_match_number ALTER COLUMN id SET DEFAULT nextval('public.athlete_match_number_id_seq'::regclass);


--
-- Name: athletes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athletes ALTER COLUMN id SET DEFAULT nextval('public.athletes_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: contingents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contingents ALTER COLUMN id SET DEFAULT nextval('public.contingents_id_seq'::regclass);


--
-- Name: courts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courts ALTER COLUMN id SET DEFAULT nextval('public.courts_id_seq'::regclass);


--
-- Name: drawing_match_numbers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers ALTER COLUMN id SET DEFAULT nextval('public.drawing_match_numbers_id_seq'::regclass);


--
-- Name: embu_champions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_champions ALTER COLUMN id SET DEFAULT nextval('public.embu_champions_id_seq'::regclass);


--
-- Name: embu_scores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_scores ALTER COLUMN id SET DEFAULT nextval('public.embu_scores_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: galleries id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.galleries ALTER COLUMN id SET DEFAULT nextval('public.galleries_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: kyu_levels id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kyu_levels ALTER COLUMN id SET DEFAULT nextval('public.kyu_levels_id_seq'::regclass);


--
-- Name: match_number_merge_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merge_details ALTER COLUMN id SET DEFAULT nextval('public.match_number_merge_details_id_seq'::regclass);


--
-- Name: match_number_merges id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merges ALTER COLUMN id SET DEFAULT nextval('public.match_number_merges_id_seq'::regclass);


--
-- Name: match_numbers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_numbers ALTER COLUMN id SET DEFAULT nextval('public.match_numbers_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: officials id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.officials ALTER COLUMN id SET DEFAULT nextval('public.officials_id_seq'::regclass);


--
-- Name: payment_methods id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment_methods ALTER COLUMN id SET DEFAULT nextval('public.payment_methods_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: pools id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pools ALTER COLUMN id SET DEFAULT nextval('public.pools_id_seq'::regclass);


--
-- Name: posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


--
-- Name: randori_judge_scores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_judge_scores ALTER COLUMN id SET DEFAULT nextval('public.randori_judge_scores_id_seq'::regclass);


--
-- Name: randori_match_results id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_match_results ALTER COLUMN id SET DEFAULT nextval('public.randori_match_results_id_seq'::regclass);


--
-- Name: referee_observations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_observations ALTER COLUMN id SET DEFAULT nextval('public.referee_observations_id_seq'::regclass);


--
-- Name: referee_score_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_score_details ALTER COLUMN id SET DEFAULT nextval('public.referee_score_details_id_seq'::regclass);


--
-- Name: referees id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referees ALTER COLUMN id SET DEFAULT nextval('public.referees_id_seq'::regclass);


--
-- Name: registration_athlete id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_athlete ALTER COLUMN id SET DEFAULT nextval('public.registration_athlete_id_seq'::regclass);


--
-- Name: registration_official id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_official ALTER COLUMN id SET DEFAULT nextval('public.registration_official_id_seq'::regclass);


--
-- Name: registrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations ALTER COLUMN id SET DEFAULT nextval('public.registrations_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: rundowns id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rundowns ALTER COLUMN id SET DEFAULT nextval('public.rundowns_id_seq'::regclass);


--
-- Name: schedule_paniteras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras ALTER COLUMN id SET DEFAULT nextval('public.schedule_paniteras_id_seq'::regclass);


--
-- Name: schedule_referees id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees ALTER COLUMN id SET DEFAULT nextval('public.schedule_referees_id_seq'::regclass);


--
-- Name: session_times id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session_times ALTER COLUMN id SET DEFAULT nextval('public.session_times_id_seq'::regclass);


--
-- Name: techniques id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.techniques ALTER COLUMN id SET DEFAULT nextval('public.techniques_id_seq'::regclass);


--
-- Name: tournament_results id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tournament_results ALTER COLUMN id SET DEFAULT nextval('public.tournament_results_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: weight_groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.weight_groups ALTER COLUMN id SET DEFAULT nextval('public.weight_groups_id_seq'::regclass);


--
-- Data for Name: active_court_referees; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.active_court_referees (id, court_id, referee_id, judge_index, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: age_groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.age_groups (id, name, "order", price, created_at, updated_at) FROM stdin;
1	Pemula	1	400000.00	2026-05-16 15:47:44	2026-05-16 15:47:44
2	Remaja A	2	500000.00	2026-05-16 15:47:44	2026-05-16 15:47:44
3	Remaja B	3	500000.00	2026-05-16 15:47:44	2026-05-16 15:47:44
4	Dewasa	4	500000.00	2026-05-16 15:47:44	2026-05-16 15:47:44
\.


--
-- Data for Name: athlete_category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.athlete_category (id, athlete_id, category_id, created_at, updated_at, registration_id) FROM stdin;
\.


--
-- Data for Name: athlete_contingent; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.athlete_contingent (id, athlete_id, contingent_id, is_primary, joined_at, created_at, updated_at) FROM stdin;
1186	1213	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1187	1214	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1188	1215	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1189	1216	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1190	1217	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1191	1218	18	t	2026-05-20 11:13:14	2026-05-20 11:13:14	2026-05-20 11:13:14
1197	1224	24	t	2026-05-20 13:21:17	2026-05-20 13:21:17	2026-05-20 13:21:17
1206	1233	24	t	2026-05-20 14:00:29	2026-05-20 14:00:29	2026-05-20 14:00:29
1213	1240	16	t	2026-05-24 00:16:55	2026-05-24 00:16:55	2026-05-24 00:16:55
1214	1241	16	t	2026-05-24 00:16:55	2026-05-24 00:16:55	2026-05-24 00:16:55
1220	1247	33	t	2026-05-24 06:23:43	2026-05-24 06:23:43	2026-05-24 06:23:43
1226	1253	33	t	2026-05-24 09:23:41	2026-05-24 09:23:41	2026-05-24 09:23:41
1232	1259	34	t	2026-05-24 22:59:53	2026-05-24 22:59:53	2026-05-24 22:59:53
1238	1265	34	t	2026-05-24 23:24:02	2026-05-24 23:24:02	2026-05-24 23:24:02
1244	1271	22	t	2026-05-25 10:36:26	2026-05-25 10:36:26	2026-05-25 10:36:26
1250	1277	22	t	2026-05-25 11:36:17	2026-05-25 11:36:17	2026-05-25 11:36:17
1256	1283	22	t	2026-05-25 13:25:43	2026-05-25 13:25:43	2026-05-25 13:25:43
1268	1295	30	t	2026-05-26 04:36:56	2026-05-26 04:36:56	2026-05-26 04:36:56
1274	1301	29	t	2026-05-26 04:54:20	2026-05-26 04:54:20	2026-05-26 04:54:20
1280	1307	28	t	2026-05-26 05:02:44	2026-05-26 05:02:44	2026-05-26 05:02:44
1286	1313	28	t	2026-05-26 05:11:02	2026-05-26 05:11:02	2026-05-26 05:11:02
1298	1325	28	t	2026-05-26 09:45:09	2026-05-26 09:45:09	2026-05-26 09:45:09
1316	1345	29	t	2026-05-27 03:39:31	2026-05-27 03:39:31	2026-05-27 03:39:31
1322	1351	35	t	2026-06-03 04:56:49	2026-06-03 04:56:49	2026-06-03 04:56:49
1329	1358	16	t	2026-06-03 09:52:06	2026-06-03 09:52:06	2026-06-03 09:52:06
1192	1219	24	t	2026-05-20 12:35:42	2026-05-20 12:35:42	2026-05-20 12:35:42
1198	1225	24	t	2026-05-20 13:29:03	2026-05-20 13:29:03	2026-05-20 13:29:03
1215	1242	33	t	2026-05-24 06:09:38	2026-05-24 06:09:38	2026-05-24 06:09:38
1221	1248	33	t	2026-05-24 07:09:45	2026-05-24 07:09:45	2026-05-24 07:09:45
1227	1254	33	t	2026-05-24 13:29:55	2026-05-24 13:29:55	2026-05-24 13:29:55
1233	1260	34	t	2026-05-24 23:04:54	2026-05-24 23:04:54	2026-05-24 23:04:54
1239	1266	34	t	2026-05-24 23:26:48	2026-05-24 23:26:48	2026-05-24 23:26:48
1245	1272	22	t	2026-05-25 10:41:05	2026-05-25 10:41:05	2026-05-25 10:41:05
1251	1278	22	t	2026-05-25 11:43:55	2026-05-25 11:43:55	2026-05-25 11:43:55
1257	1284	22	t	2026-05-25 13:29:22	2026-05-25 13:29:22	2026-05-25 13:29:22
1269	1296	30	t	2026-05-26 04:43:24	2026-05-26 04:43:24	2026-05-26 04:43:24
1275	1302	29	t	2026-05-26 04:57:26	2026-05-26 04:57:26	2026-05-26 04:57:26
1281	1308	29	t	2026-05-26 05:05:11	2026-05-26 05:05:11	2026-05-26 05:05:11
1287	1314	29	t	2026-05-26 05:14:11	2026-05-26 05:14:11	2026-05-26 05:14:11
1293	1320	28	t	2026-05-26 09:28:26	2026-05-26 09:28:26	2026-05-26 09:28:26
1299	1326	27	t	2026-05-26 09:52:18	2026-05-26 09:52:18	2026-05-26 09:52:18
1305	1332	27	t	2026-05-26 10:25:39	2026-05-26 10:25:39	2026-05-26 10:25:39
1311	1340	27	t	2026-05-26 10:34:43	2026-05-26 10:34:43	2026-05-26 10:34:43
1317	1346	30	t	2026-05-27 03:41:41	2026-05-27 03:41:41	2026-05-27 03:41:41
1193	1220	24	t	2026-05-20 12:45:38	2026-05-20 12:45:38	2026-05-20 12:45:38
1199	1226	18	t	2026-05-20 13:29:46	2026-05-20 13:29:46	2026-05-20 13:29:46
1200	1227	18	t	2026-05-20 13:29:46	2026-05-20 13:29:46	2026-05-20 13:29:46
1201	1228	18	t	2026-05-20 13:29:46	2026-05-20 13:29:46	2026-05-20 13:29:46
1202	1229	18	t	2026-05-20 13:29:46	2026-05-20 13:29:46	2026-05-20 13:29:46
1208	1235	24	t	2026-05-20 14:06:36	2026-05-20 14:06:36	2026-05-20 14:06:36
1216	1243	33	t	2026-05-24 06:12:07	2026-05-24 06:12:07	2026-05-24 06:12:07
1222	1249	33	t	2026-05-24 07:18:17	2026-05-24 07:18:17	2026-05-24 07:18:17
1228	1255	34	t	2026-05-24 22:41:36	2026-05-24 22:41:36	2026-05-24 22:41:36
1234	1261	34	t	2026-05-24 23:07:41	2026-05-24 23:07:41	2026-05-24 23:07:41
1240	1267	34	t	2026-05-24 23:29:56	2026-05-24 23:29:56	2026-05-24 23:29:56
1246	1273	22	t	2026-05-25 10:43:56	2026-05-25 10:43:56	2026-05-25 10:43:56
1252	1279	22	t	2026-05-25 12:02:27	2026-05-25 12:02:27	2026-05-25 12:02:27
1258	1285	22	t	2026-05-25 13:31:49	2026-05-25 13:31:49	2026-05-25 13:31:49
1270	1297	30	t	2026-05-26 04:46:55	2026-05-26 04:46:55	2026-05-26 04:46:55
1276	1303	28	t	2026-05-26 04:58:06	2026-05-26 04:58:06	2026-05-26 04:58:06
1282	1309	28	t	2026-05-26 05:05:36	2026-05-26 05:05:36	2026-05-26 05:05:36
1288	1315	29	t	2026-05-26 05:15:52	2026-05-26 05:15:52	2026-05-26 05:15:52
1294	1321	28	t	2026-05-26 09:32:00	2026-05-26 09:32:00	2026-05-26 09:32:00
1300	1327	27	t	2026-05-26 09:56:01	2026-05-26 09:56:01	2026-05-26 09:56:01
1306	1335	27	t	2026-05-26 10:28:27	2026-05-26 10:28:27	2026-05-26 10:28:27
1312	1341	27	t	2026-05-26 10:37:17	2026-05-26 10:37:17	2026-05-26 10:37:17
1318	1347	28	t	2026-05-28 12:54:08	2026-05-28 12:54:08	2026-05-28 12:54:08
1324	1353	35	t	2026-06-03 04:58:20	2026-06-03 04:58:20	2026-06-03 04:58:20
1333	1234	40	t	2026-06-08 14:06:49	2026-06-08 14:06:49	2026-06-08 14:06:49
1194	1221	24	t	2026-05-20 13:03:32	2026-05-20 13:03:32	2026-05-20 13:03:32
1203	1230	24	t	2026-05-20 13:44:12	2026-05-20 13:44:12	2026-05-20 13:44:12
1209	1236	24	t	2026-05-20 14:15:05	2026-05-20 14:15:05	2026-05-20 14:15:05
1217	1244	33	t	2026-05-24 06:14:43	2026-05-24 06:14:43	2026-05-24 06:14:43
1223	1250	33	t	2026-05-24 07:45:05	2026-05-24 07:45:05	2026-05-24 07:45:05
1229	1256	34	t	2026-05-24 22:44:24	2026-05-24 22:44:25	2026-05-24 22:44:25
1235	1262	34	t	2026-05-24 23:10:27	2026-05-24 23:10:27	2026-05-24 23:10:27
1241	1268	22	t	2026-05-25 10:26:33	2026-05-25 10:26:33	2026-05-25 10:26:33
1247	1274	22	t	2026-05-25 10:46:09	2026-05-25 10:46:09	2026-05-25 10:46:09
1253	1280	22	t	2026-05-25 12:38:38	2026-05-25 12:38:38	2026-05-25 12:38:38
1259	1286	22	t	2026-05-25 14:44:34	2026-05-25 14:44:34	2026-05-25 14:44:34
1271	1298	30	t	2026-05-26 04:49:29	2026-05-26 04:49:29	2026-05-26 04:49:29
1277	1304	29	t	2026-05-26 05:00:09	2026-05-26 05:00:09	2026-05-26 05:00:09
1283	1310	28	t	2026-05-26 05:08:12	2026-05-26 05:08:12	2026-05-26 05:08:12
1289	1316	29	t	2026-05-26 05:17:23	2026-05-26 05:17:23	2026-05-26 05:17:23
1295	1322	28	t	2026-05-26 09:36:37	2026-05-26 09:36:37	2026-05-26 09:36:37
1301	1328	27	t	2026-05-26 09:58:55	2026-05-26 09:58:55	2026-05-26 09:58:55
1307	1336	27	t	2026-05-26 10:29:42	2026-05-26 10:29:42	2026-05-26 10:29:42
1313	1342	27	t	2026-05-26 10:37:25	2026-05-26 10:37:25	2026-05-26 10:37:25
1319	1348	27	t	2026-05-28 14:59:04	2026-05-28 14:59:04	2026-05-28 14:59:04
1334	1203	40	t	2026-06-08 14:07:08	2026-06-08 14:07:08	2026-06-08 14:07:08
1195	1222	24	t	2026-05-20 13:08:13	2026-05-20 13:08:13	2026-05-20 13:08:13
1204	1231	24	t	2026-05-20 13:51:04	2026-05-20 13:51:04	2026-05-20 13:51:04
1210	1237	24	t	2026-05-20 14:22:46	2026-05-20 14:22:46	2026-05-20 14:22:46
1218	1245	33	t	2026-05-24 06:17:58	2026-05-24 06:17:58	2026-05-24 06:17:58
1224	1251	33	t	2026-05-24 08:50:55	2026-05-24 08:50:55	2026-05-24 08:50:55
1230	1257	34	t	2026-05-24 22:50:06	2026-05-24 22:50:06	2026-05-24 22:50:06
1236	1263	34	t	2026-05-24 23:18:04	2026-05-24 23:18:04	2026-05-24 23:18:04
1242	1269	22	t	2026-05-25 10:30:56	2026-05-25 10:30:56	2026-05-25 10:30:56
1248	1275	22	t	2026-05-25 10:50:02	2026-05-25 10:50:02	2026-05-25 10:50:02
1254	1281	22	t	2026-05-25 13:20:34	2026-05-25 13:20:34	2026-05-25 13:20:34
1260	1287	22	t	2026-05-25 14:54:05	2026-05-25 14:54:05	2026-05-25 14:54:05
1272	1299	29	t	2026-05-26 04:51:02	2026-05-26 04:51:02	2026-05-26 04:51:02
1278	1305	28	t	2026-05-26 05:00:40	2026-05-26 05:00:40	2026-05-26 05:00:40
1284	1311	29	t	2026-05-26 05:08:50	2026-05-26 05:08:50	2026-05-26 05:08:50
1290	1317	29	t	2026-05-26 05:19:24	2026-05-26 05:19:24	2026-05-26 05:19:24
1296	1323	28	t	2026-05-26 09:38:48	2026-05-26 09:38:48	2026-05-26 09:38:48
1302	1329	27	t	2026-05-26 10:05:38	2026-05-26 10:05:38	2026-05-26 10:05:38
1308	1337	27	t	2026-05-26 10:30:30	2026-05-26 10:30:30	2026-05-26 10:30:30
1314	1343	27	t	2026-05-26 10:39:00	2026-05-26 10:39:00	2026-05-26 10:39:00
1320	1349	29	t	2026-05-31 11:30:11	2026-05-31 11:30:11	2026-05-31 11:30:11
1326	1355	35	t	2026-06-03 05:00:10	2026-06-03 05:00:10	2026-06-03 05:00:10
1335	1201	40	t	2026-06-08 14:07:27	2026-06-08 14:07:27	2026-06-08 14:07:27
1196	1223	24	t	2026-05-20 13:14:27	2026-05-20 13:14:27	2026-05-20 13:14:27
1205	1232	24	t	2026-05-20 13:54:51	2026-05-20 13:54:51	2026-05-20 13:54:51
1219	1246	33	t	2026-05-24 06:20:58	2026-05-24 06:20:58	2026-05-24 06:20:58
1225	1252	33	t	2026-05-24 09:02:49	2026-05-24 09:02:49	2026-05-24 09:02:49
1231	1258	34	t	2026-05-24 22:52:34	2026-05-24 22:52:34	2026-05-24 22:52:34
1237	1264	34	t	2026-05-24 23:20:48	2026-05-24 23:20:48	2026-05-24 23:20:48
1243	1270	22	t	2026-05-25 10:33:41	2026-05-25 10:33:41	2026-05-25 10:33:41
1249	1276	22	t	2026-05-25 11:33:38	2026-05-25 11:33:38	2026-05-25 11:33:38
1255	1282	22	t	2026-05-25 13:23:37	2026-05-25 13:23:37	2026-05-25 13:23:37
1261	1288	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1262	1289	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1263	1290	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1264	1291	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1265	1292	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1266	1293	31	t	2026-05-25 15:32:35	2026-05-25 15:32:35	2026-05-25 15:32:35
1273	1300	30	t	2026-05-26 04:52:06	2026-05-26 04:52:06	2026-05-26 04:52:06
1279	1306	29	t	2026-05-26 05:02:13	2026-05-26 05:02:13	2026-05-26 05:02:13
1285	1312	29	t	2026-05-26 05:10:16	2026-05-26 05:10:16	2026-05-26 05:10:16
1291	1318	28	t	2026-05-26 05:25:05	2026-05-26 05:25:05	2026-05-26 05:25:05
1297	1324	28	t	2026-05-26 09:42:39	2026-05-26 09:42:39	2026-05-26 09:42:39
1303	1330	27	t	2026-05-26 10:07:55	2026-05-26 10:07:55	2026-05-26 10:07:55
1309	1338	27	t	2026-05-26 10:33:02	2026-05-26 10:33:02	2026-05-26 10:33:02
1315	1344	27	t	2026-05-26 10:39:20	2026-05-26 10:39:20	2026-05-26 10:39:20
1321	1350	35	t	2026-06-03 04:55:58	2026-06-03 04:55:58	2026-06-03 04:55:58
1327	1356	35	t	2026-06-03 05:15:09	2026-06-03 05:15:09	2026-06-03 05:15:09
1328	1357	35	t	2026-06-03 05:15:09	2026-06-03 05:15:09	2026-06-03 05:15:09
1336	1202	40	t	2026-06-08 14:07:51	2026-06-08 14:07:51	2026-06-08 14:07:51
1151	1156	16	t	2026-05-18 01:57:59	2026-05-18 01:57:59	2026-05-18 01:57:59
1153	1166	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1154	1167	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1155	1168	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1156	1169	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1157	1170	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1158	1171	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1159	1172	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1160	1173	17	t	2026-05-18 09:28:17	2026-05-18 09:28:17	2026-05-18 09:28:17
1161	1175	16	t	2026-05-18 09:34:28	2026-05-18 09:34:28	2026-05-18 09:34:28
1164	1188	16	t	2026-05-18 09:53:42	2026-05-18 09:53:42	2026-05-18 09:53:42
1165	1190	16	t	2026-05-18 09:56:04	2026-05-18 09:56:04	2026-05-18 09:56:04
1171	1198	16	t	2026-05-18 10:35:24	2026-05-18 10:35:24	2026-05-18 10:35:24
1172	1199	16	t	2026-05-18 10:35:24	2026-05-18 10:35:24	2026-05-18 10:35:24
1173	1200	16	t	2026-05-18 10:39:46	2026-05-18 10:39:46	2026-05-18 10:39:46
1177	1204	22	t	2026-05-18 16:26:56	2026-05-18 16:26:56	2026-05-18 16:26:56
1179	1206	22	t	2026-05-18 17:37:33	2026-05-18 17:37:33	2026-05-18 17:37:33
1180	1207	25	t	2026-05-19 09:11:57	2026-05-19 09:11:57	2026-05-19 09:11:57
1181	1208	25	t	2026-05-19 09:38:25	2026-05-19 09:38:25	2026-05-19 09:38:25
1182	1209	25	t	2026-05-19 09:49:28	2026-05-19 09:49:28	2026-05-19 09:49:28
1183	1210	25	t	2026-05-19 10:00:07	2026-05-19 10:00:07	2026-05-19 10:00:07
\.


--
-- Data for Name: athlete_contingent_histories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.athlete_contingent_histories (id, athlete_id, contingent_id, moved_at, notes, created_at, updated_at) FROM stdin;
3	1166	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
4	1167	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
5	1168	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
6	1169	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
7	1170	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
8	1171	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
9	1172	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
10	1173	17	2026-05-18 09:28:17	Pendaftaran Turnamen Kabupaten Pasuruan	2026-05-18 09:28:17	2026-05-18 09:28:17
11	1198	16	2026-05-18 10:35:24	Pendaftaran Turnamen KOTA KEDIRI	2026-05-18 10:35:24	2026-05-18 10:35:24
12	1199	16	2026-05-18 10:35:24	Pendaftaran Turnamen KOTA KEDIRI	2026-05-18 10:35:24	2026-05-18 10:35:24
13	1200	16	2026-05-18 10:39:46	Pendaftaran Turnamen KOTA KEDIRI	2026-05-18 10:39:46	2026-05-18 10:39:46
14	1206	22	2026-05-18 17:37:33	Pendaftaran Turnamen JOMBANG	2026-05-18 17:37:33	2026-05-18 17:37:33
15	1213	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
16	1214	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
17	1215	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
18	1216	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
19	1217	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
20	1218	18	2026-05-20 11:13:14	Pendaftaran Turnamen BANGKALAN A	2026-05-20 11:13:14	2026-05-20 11:13:14
21	1226	18	2026-05-20 13:29:46	Pendaftaran Turnamen BANGKALAN A	2026-05-20 13:29:46	2026-05-20 13:29:46
22	1227	18	2026-05-20 13:29:46	Pendaftaran Turnamen BANGKALAN A	2026-05-20 13:29:46	2026-05-20 13:29:46
23	1228	18	2026-05-20 13:29:46	Pendaftaran Turnamen BANGKALAN A	2026-05-20 13:29:46	2026-05-20 13:29:46
24	1229	18	2026-05-20 13:29:46	Pendaftaran Turnamen BANGKALAN A	2026-05-20 13:29:46	2026-05-20 13:29:46
27	1240	16	2026-05-24 00:16:55	Pendaftaran Turnamen KOTA KEDIRI	2026-05-24 00:16:55	2026-05-24 00:16:55
28	1241	16	2026-05-24 00:16:55	Pendaftaran Turnamen KOTA KEDIRI	2026-05-24 00:16:55	2026-05-24 00:16:55
29	1251	33	2026-05-24 08:50:55	Pendaftaran Turnamen TUBAN	2026-05-24 08:50:55	2026-05-24 08:50:55
30	1252	33	2026-05-24 09:02:49	Pendaftaran Turnamen TUBAN	2026-05-24 09:02:49	2026-05-24 09:02:49
31	1253	33	2026-05-24 09:23:41	Pendaftaran Turnamen TUBAN	2026-05-24 09:23:41	2026-05-24 09:23:41
32	1288	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
33	1289	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
34	1290	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
35	1291	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
36	1292	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
37	1293	31	2026-05-25 15:32:35	Pendaftaran Turnamen Kontingen Jember	2026-05-25 15:32:35	2026-05-25 15:32:35
39	1347	28	2026-05-28 12:54:08	Pendaftaran Turnamen Surabaya B	2026-05-28 12:54:08	2026-05-28 12:54:08
40	1348	27	2026-05-28 14:59:04	Pendaftaran Turnamen Surabaya A	2026-05-28 14:59:04	2026-05-28 14:59:04
41	1356	35	2026-06-03 05:15:09	Pendaftaran Turnamen Sidoarjo	2026-06-03 05:15:09	2026-06-03 05:15:09
42	1357	35	2026-06-03 05:15:09	Pendaftaran Turnamen Sidoarjo	2026-06-03 05:15:09	2026-06-03 05:15:09
43	1358	16	2026-06-03 09:52:06	Pendaftaran Turnamen KOTA KEDIRI	2026-06-03 09:52:06	2026-06-03 09:52:06
47	1234	40	2026-06-08 14:06:49	Perpindahan kontingen via panel admin.	2026-06-08 14:06:49	2026-06-08 14:06:49
48	1203	40	2026-06-08 14:07:08	Perpindahan kontingen via panel admin.	2026-06-08 14:07:08	2026-06-08 14:07:08
49	1201	40	2026-06-08 14:07:27	Perpindahan kontingen via panel admin.	2026-06-08 14:07:27	2026-06-08 14:07:27
50	1202	40	2026-06-08 14:07:51	Perpindahan kontingen via panel admin.	2026-06-08 14:07:51	2026-06-08 14:07:51
\.


--
-- Data for Name: athlete_match_number; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.athlete_match_number (id, athlete_id, match_number_id, registration_id, created_at, updated_at, technique_ids) FROM stdin;
2669	1248	7	65	2026-06-12 13:58:13	2026-06-12 13:58:13	\N
2670	1248	12	65	2026-06-12 13:58:13	2026-06-12 13:58:13	\N
2678	1254	35	65	2026-06-12 14:00:29	2026-06-12 14:00:29	\N
2679	1254	41	65	2026-06-12 14:00:29	2026-06-12 14:00:29	\N
2419	1288	42	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[7,5,183,4,186,28]
2420	1289	37	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[]
2421	1290	25	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[7,4,183,10,5,186]
2422	1290	28	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[]
2423	1291	36	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[7,4,183,10,5,186]
2424	1291	39	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[]
2425	1292	15	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[7,5,183,4,186,28]
2426	1292	18	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[]
2427	1293	6	66	2026-06-05 02:12:26	2026-06-05 02:12:26	[5,4,9,6,3,10]
2469	1303	1	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[253,254,255,256,257,258]
2470	1305	5	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[245,323,251,39,23,252]
2471	1307	5	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[245,323,251,39,23,252]
2472	1309	6	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[253,286,255,256,257,258]
2473	1310	7	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[253,254,14,256,257,258]
2474	1310	9	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[245,246,247,39,23,252]
2475	1313	9	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[245,246,247,39,23,252]
2476	1318	22	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2477	1347	23	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2478	1320	17	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2479	1320	31	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[148,287,288,49,286,151]
2480	1321	31	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[148,287,288,49,286,151]
2481	1321	25	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[7,147,253,255,257,258]
2482	1322	27	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2483	1323	29	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2484	1323	26	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[289,163,290,282,291,292]
2485	1324	26	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[289,163,290,282,291,292]
2486	1324	30	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2487	1325	48	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[]
2488	1325	43	70	2026-06-07 05:40:34	2026-06-07 05:40:34	[7,147,253,255,257,258]
2509	1156	17	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2510	1175	21	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2511	1199	37	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2512	1200	18	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2513	1240	20	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2514	1241	40	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2515	1188	28	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2516	1190	27	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2517	1358	38	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2518	1198	41	59	2026-06-08 01:33:47	2026-06-08 01:33:47	[]
2354	1235	6	73	2026-06-04 16:43:52	2026-06-04 16:43:52	[4,10,5,8,3,9]
2433	1225	26	73	2026-06-05 08:22:19	2026-06-05 08:22:19	[306,312,313,309,314,315]
2434	1225	29	73	2026-06-05 08:22:19	2026-06-05 08:22:19	[]
2440	1221	43	73	2026-06-05 08:51:33	2026-06-05 08:51:33	[5,10,9,7,8,4]
2441	1221	50	73	2026-06-05 08:51:33	2026-06-05 08:51:33	[316,317,322,319,320,321]
2603	1168	27	60	2026-06-11 11:15:28	2026-06-11 11:15:28	\N
2608	1173	39	60	2026-06-11 11:16:12	2026-06-11 11:16:12	\N
2621	1213	51	62	2026-06-12 12:36:53	2026-06-12 12:36:53	\N
2622	1213	45	62	2026-06-12 12:36:53	2026-06-12 12:36:53	\N
2630	1227	20	62	2026-06-12 13:36:06	2026-06-12 13:36:06	\N
2654	1244	8	65	2026-06-12 13:57:03	2026-06-12 13:57:03	\N
2655	1244	10	65	2026-06-12 13:57:03	2026-06-12 13:57:03	\N
2656	1244	13	65	2026-06-12 13:57:03	2026-06-12 13:57:03	\N
2671	1249	6	65	2026-06-12 13:58:29	2026-06-12 13:58:29	\N
2583	1350	6	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[335,336,337,338,339,340]
2584	1351	27	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[]
2585	1351	31	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[330,331,332,327,328,329]
2586	1353	20	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[]
2587	1353	16	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[330,331,332,341,328,329]
2588	1353	32	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[344,342,343,345,346,347]
2589	1355	17	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[]
2590	1355	32	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[344,342,343,345,346,347]
2591	1356	22	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[]
1645	1250	2	65	2026-05-24 14:09:27	2026-05-24 14:09:27	[5,10,8,6,9,4]
1647	1251	40	65	2026-05-24 14:09:27	2026-05-24 14:09:27	[]
1648	1251	35	65	2026-05-24 14:09:27	2026-05-24 14:09:27	[8,186,5,6,10,4]
2592	1356	32	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[344,342,343,345,346,347]
2593	1356	31	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[330,331,332,327,328,329]
2594	1357	19	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[]
2595	1357	16	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[330,331,332,341,328,329]
2596	1357	32	72	2026-06-09 12:58:09	2026-06-09 12:58:09	[344,342,343,345,346,347]
2547	1232	18	73	2026-06-08 14:57:54	2026-06-08 14:57:54	[]
2428	1230	16	73	2026-06-05 08:14:32	2026-06-05 08:14:32	[306,307,308,309,310,311]
2355	1237	40	73	2026-06-04 16:46:00	2026-06-04 16:46:00	[]
2604	1169	42	60	2026-06-11 11:15:38	2026-06-11 11:15:38	\N
2623	1214	36	62	2026-06-12 12:37:05	2026-06-12 12:37:05	\N
2624	1214	39	62	2026-06-12 12:37:05	2026-06-12 12:37:05	\N
2631	1228	2	62	2026-06-12 13:36:19	2026-06-12 13:36:19	\N
2372	1204	21	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2373	1204	16	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[231,232,233,234,223,235]
2374	1204	32	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,208,209,210,211,4]
2375	1206	33	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,171,206,173,174,28]
2376	1206	34	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,175,207,173,174,28]
2377	1279	6	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,8,3,10,9,4]
2378	1271	7	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[4,8,10,3,9,5]
2379	1281	1	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[28,14,27,32,8,4]
2380	1281	4	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[218,213,214,215,216,217]
2381	1283	1	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,10,8,6,9,4]
2382	1283	3	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[212,213,214,215,216,217]
2383	1283	4	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[218,213,214,215,216,217]
2384	1276	2	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,10,8,6,9,4]
2385	1276	3	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[212,213,214,215,216,217]
2386	1270	27	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2387	1270	24	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[4,8,28,27,9,5]
2388	1280	14	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,10,8,6,9,4]
2672	1252	41	65	2026-06-12 13:58:47	2026-06-12 13:58:47	\N
2673	1252	35	65	2026-06-12 13:58:47	2026-06-12 13:58:47	\N
2032	1326	1	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[253,254,255,256,257,258]
2033	1326	4	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[293,294,282,295,289,285]
2034	1327	2	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[253,254,255,256,257,258]
2035	1327	3	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[293,294,282,295,289,285]
2036	1328	4	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[293,294,282,295,289,285]
2037	1328	3	71	2026-05-31 11:23:02	2026-05-31 11:23:02	[293,294,282,295,289,285]
2038	1329	6	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,286,255,256,257,258]
2039	1329	8	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2040	1329	13	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,226,39,281,50,258]
2279	1236	2	73	2026-06-04 05:57:09	2026-06-04 05:57:09	[4,10,5,8,3,9]
2389	1280	34	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,175,207,173,174,28]
2390	1275	24	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[4,8,6,9,3,5]
2287	1222	37	73	2026-06-04 06:22:14	2026-06-04 06:22:14	[]
2356	1233	35	73	2026-06-04 16:49:42	2026-06-04 16:49:42	[4,10,3,8,5,6]
2357	1233	38	73	2026-06-04 16:49:42	2026-06-04 16:49:42	[]
2605	1170	45	60	2026-06-11 11:15:45	2026-06-11 11:15:45	\N
2625	1217	27	62	2026-06-12 13:35:26	2026-06-12 13:35:26	\N
2626	1217	24	62	2026-06-12 13:35:26	2026-06-12 13:35:26	\N
2632	1229	35	62	2026-06-12 13:36:27	2026-06-12 13:36:27	\N
2633	1229	41	62	2026-06-12 13:36:27	2026-06-12 13:36:27	\N
2041	1330	7	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,286,255,256,257,258]
2042	1330	9	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2043	1330	13	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,226,39,281,50,258]
2044	1332	8	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2045	1332	10	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2046	1332	13	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,226,39,281,50,258]
2047	1348	9	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2048	1348	10	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[226,296,282,283,297,285]
2049	1348	13	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,226,39,281,50,258]
2050	1335	14	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,286,255,256,257,258]
2051	1337	19	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2052	1337	16	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[152,287,153,154,286,151]
2053	1338	21	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2054	1338	16	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[152,287,153,154,286,151]
2055	1338	15	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[6,147,255,253,286,258]
2056	1341	22	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2057	1343	27	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2058	1343	26	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[289,303,290,282,291,292]
2059	1336	28	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2060	1336	24	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[253,286,256,257,258,255]
2061	1336	26	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[289,303,290,282,291,292]
2062	1340	23	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2063	1342	40	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2064	1342	36	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[6,147,255,253,286,258]
2065	1342	51	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[298,299,300,301,154,302]
2066	1344	51	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[298,299,300,301,154,302]
2067	1344	48	71	2026-05-31 11:23:03	2026-05-31 11:23:03	[]
2660	1245	7	65	2026-06-12 13:57:28	2026-06-12 13:57:28	\N
2661	1245	10	65	2026-06-12 13:57:28	2026-06-12 13:57:28	\N
2662	1245	12	65	2026-06-12 13:57:28	2026-06-12 13:57:28	\N
2391	1275	33	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,171,206,173,174,28]
2392	1275	26	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[225,226,227,228,229,230]
2393	1277	17	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2394	1277	14	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,8,28,6,9,4]
2395	1277	34	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,175,207,173,174,28]
2396	1285	25	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[7,8,5,10,186,4]
2397	1285	33	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,171,206,173,174,28]
2398	1269	34	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,175,207,173,174,28]
2399	1269	33	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,171,206,173,174,28]
2400	1272	15	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[7,8,5,10,186,4]
2401	1272	16	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[231,232,233,234,223,235]
2402	1272	32	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,208,209,210,211,4]
2403	1278	30	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2404	1278	25	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[7,8,5,10,186,4]
2405	1286	20	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2406	1286	15	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[7,8,5,10,186,4]
2407	1284	18	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2408	1284	32	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,208,209,210,211,4]
1879	1295	3	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[13,242,243,39,23,244]
1880	1296	3	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[13,242,243,39,23,244]
1881	1346	4	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[245,246,247,248,249,250]
1882	1297	4	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[245,246,247,248,249,250]
1883	1298	22	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[]
1884	1300	23	68	2026-05-27 14:08:38	2026-05-27 14:08:38	[]
2674	1253	35	65	2026-06-12 13:59:05	2026-06-12 13:59:05	\N
2557	1234	2	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,337,186,7,335,183]
2558	1234	3	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[52,180,116,103,53,99]
2559	1234	13	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,116,180,99,103,335]
2560	1202	6	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,337,186,7,335,183]
2561	1202	10	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[]
2562	1202	13	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,116,180,99,103,335]
2563	1201	2	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,337,186,7,335,183]
2409	1282	19	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2410	1282	31	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[237,238,222,240,241,223]
2411	1282	32	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[5,208,209,210,211,4]
2412	1273	29	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2413	1273	31	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[237,238,222,240,241,223]
2414	1274	28	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2415	1274	26	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[225,226,227,228,229,230]
2416	1268	46	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2417	1268	43	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[7,8,5,10,186,4]
2418	1287	40	61	2026-06-05 01:27:47	2026-06-05 01:27:47	[]
2068	1299	4	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,246,251,248,23,252]
2069	1299	1	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,254,255,256,257,258]
2070	1301	4	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,246,251,248,23,252]
2071	1302	3	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,259,260,248,23,252]
2072	1302	2	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,254,261,256,262,263]
2073	1345	3	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,259,260,248,23,252]
2074	1304	12	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,226,39,281,50,258]
2075	1306	12	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,226,39,281,50,258]
2076	1308	12	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,226,39,281,50,258]
2077	1308	9	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[226,233,282,283,284,285]
2078	1311	12	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,226,39,281,50,258]
2079	1311	9	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[226,233,282,283,284,285]
2080	1311	7	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,286,255,256,257,258]
2081	1312	10	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,246,251,39,23,252]
2082	1314	10	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[245,246,251,39,23,252]
2083	1315	14	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,286,255,256,257,258]
2084	1315	22	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[]
2085	1316	24	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[253,286,256,257,258,255]
2086	1316	31	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[289,303,290,282,291,292]
2087	1317	43	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[7,147,253,255,257,258]
2088	1349	20	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[]
2089	1349	31	69	2026-05-31 11:33:27	2026-05-31 11:33:27	[289,303,290,282,291,292]
2675	1253	40	65	2026-06-12 13:59:05	2026-06-12 13:59:05	\N
2564	1201	5	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[52,180,116,103,54,99]
2565	1201	13	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,116,180,99,103,335]
2566	1203	5	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[52,180,116,103,54,99]
2567	1203	13	88	2026-06-08 16:34:52	2026-06-08 16:34:52	[255,116,180,99,103,335]
2280	1219	42	73	2026-06-04 06:06:27	2026-06-04 06:06:27	[4,8,3,10,5,6]
2281	1219	47	73	2026-06-04 06:06:27	2026-06-04 06:06:27	[]
2429	1230	19	73	2026-06-05 08:14:32	2026-06-05 08:14:32	[]
2435	1220	24	73	2026-06-05 08:33:05	2026-06-05 08:33:05	[4,10,3,8,5,9]
2436	1220	26	73	2026-06-05 08:33:05	2026-06-05 08:33:05	[306,312,313,309,314,315]
2437	1220	28	73	2026-06-05 08:33:05	2026-06-05 08:33:05	[]
2606	1171	46	60	2026-06-11 11:15:57	2026-06-11 11:15:57	\N
2619	1215	17	62	2026-06-12 12:36:09	2026-06-12 12:36:09	\N
2627	1218	6	62	2026-06-12 13:35:43	2026-06-12 13:35:43	\N
2649	1242	15	65	2026-06-12 13:56:37	2026-06-12 13:56:37	\N
2650	1242	21	65	2026-06-12 13:56:37	2026-06-12 13:56:37	\N
2663	1246	9	65	2026-06-12 13:57:35	2026-06-12 13:57:35	\N
2664	1246	12	65	2026-06-12 13:57:35	2026-06-12 13:57:35	\N
2665	1246	13	65	2026-06-12 13:57:35	2026-06-12 13:57:35	\N
1318	1210	16	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[60,55,57,52,87,17,14,20,13,64,16]
1319	1210	19	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[]
1320	1207	26	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[34,40,91,72,41,85,73,17,14,20,13,64]
1321	1207	30	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[]
1322	1208	28	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[]
1323	1208	24	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[147,10,27,8,20,4]
1324	1208	26	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[34,40,91,72,41,85,73,17,14,20,13,64]
1325	1209	16	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[60,55,57,52,87,17,14,20,13,64,16]
1326	1209	21	63	2026-05-20 14:47:14	2026-05-20 14:47:14	[]
1918	1255	4	67	2026-05-27 23:35:36	2026-06-10 14:46:56	[264,348,278,274,275,276]
1919	1256	4	67	2026-05-27 23:35:36	2026-06-10 14:46:56	[264,348,278,274,275,276]
1921	1260	10	67	2026-05-27 23:35:36	2026-06-10 14:48:27	[34,348,278,279,280,277]
1923	1261	10	67	2026-05-27 23:35:36	2026-06-10 14:48:27	[34,348,278,279,280,277]
2282	1224	42	73	2026-06-04 06:10:10	2026-06-04 06:10:10	[4,10,3,8,5,6]
2283	1224	45	73	2026-06-04 06:10:10	2026-06-04 06:10:10	[]
2430	1231	14	73	2026-06-05 08:18:51	2026-06-05 08:18:51	[4,10,3,8,5,6]
2459	1167	28	60	2026-06-05 10:10:09	2026-06-05 10:10:09	[]
2460	1167	26	60	2026-06-05 10:10:09	2026-06-05 10:10:09	[160,162,163,164,165,166]
1915	1259	1	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[270,271,256,10,3,4]
1916	1257	2	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[270,271,8,10,3,4]
1917	1258	2	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[270,271,8,10,3,4]
1920	1260	6	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[8,10,3,5,6,4]
1922	1261	7	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[8,10,3,5,6,4]
1924	1262	22	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[]
1925	1264	18	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[]
1926	1263	23	67	2026-05-27 23:35:36	2026-05-27 23:35:36	[]
1927	1265	28	67	2026-05-27 23:35:37	2026-05-27 23:35:37	[]
1928	1266	29	67	2026-05-27 23:35:37	2026-05-27 23:35:37	[]
1929	1267	41	67	2026-05-27 23:35:37	2026-05-27 23:35:37	[]
2431	1231	16	73	2026-06-05 08:18:51	2026-06-05 08:18:51	[306,312,313,309,314,311]
2432	1231	20	73	2026-06-05 08:18:51	2026-06-05 08:18:51	[]
2438	1223	50	73	2026-06-05 08:48:34	2026-06-05 08:48:34	[316,317,318,319,320,321]
2439	1223	46	73	2026-06-05 08:48:34	2026-06-05 08:48:34	[]
2601	1166	29	60	2026-06-11 11:15:19	2026-06-11 11:15:19	\N
2602	1166	26	60	2026-06-11 11:15:19	2026-06-11 11:15:19	\N
2607	1172	38	60	2026-06-11 11:16:06	2026-06-11 11:16:06	\N
2620	1216	24	62	2026-06-12 12:36:37	2026-06-12 12:36:37	\N
2628	1226	37	62	2026-06-12 13:35:54	2026-06-12 13:35:54	\N
2629	1226	51	62	2026-06-12 13:35:54	2026-06-12 13:35:54	\N
2651	1243	6	65	2026-06-12 13:56:51	2026-06-12 13:56:51	\N
2652	1243	8	65	2026-06-12 13:56:51	2026-06-12 13:56:51	\N
2653	1243	13	65	2026-06-12 13:56:51	2026-06-12 13:56:51	\N
2666	1247	9	65	2026-06-12 13:58:05	2026-06-12 13:58:05	\N
2667	1247	12	65	2026-06-12 13:58:05	2026-06-12 13:58:05	\N
2668	1247	13	65	2026-06-12 13:58:05	2026-06-12 13:58:05	\N
\.


--
-- Data for Name: athletes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.athletes (id, name, gender, birth_date, created_at, updated_at, achievement_history, nik, bpjs_number, bpjs_status, bpjs_card_path, identity_document_path, birth_place, blood_type, address, phone, photo_path, dojo_origin, nik_kenshi) FROM stdin;
1215	SYAIFUL ROHMAN 	Male	2009-04-14	2026-05-20 11:13:14	2026-05-20 11:13:14	\N	3526031404090001	00000000000	Aktif	\N	\N	BANGKALAN	A	JL.KH.ACH.MUNIF NO 03		athlete_photos/TfBjHgBzqS7OGu1X9PyKeg8nqrADxoyhHIrj9Qhl.jpg	GALIS BANGKALAN	25.2.13.13.03.005
1216	DIANA OCTHA VELA	Female	2008-10-31	2026-05-20 11:13:14	2026-05-20 11:13:14	\N	3526017110080002	00000000000	Aktif	\N	\N	BANGKALAN	B	JL.KH.MOH TOHA GG 2		athlete_photos/ATQTw9IcvWVTJbTk4DIKNkb7kUqEEvomiCUZvFza.jpg	TRUNOJOYO	24.3.13.13.03.003
1257	Alisha Ramadhina Rheva	Female	2013-11-05	2026-05-24 22:50:06	2026-05-25 16:10:10	\N	3521124511130001	26067758180	Aktif	\N	\N	Gresik	-	Jl Klangka Rt01 Rw06 Perum Garden Palace, Peganden, Manyar, Kab. Gresik	08161154446	athlete_photos/pM2996mL6TSr8eN8F85fiIDdbhs3WBD2tSRYTxQY.jpg	Semen Gresik	25.2.13.06.01.003
1274	arum shelvia fitri	Female	2010-09-09	2026-05-25 10:46:09	2026-05-25 16:56:04	\N	3172024909101013	6325364537457	Aktif	\N	\N	jakarta	-	dsn.kepuhrejo, rt04/rw02, ds. sukopinggir, kec. gudo	089512258640	athlete_photos/KpWY5IkRnAxkngukScONHzNjX2ZSgcbOU4xGxtmZ.jpg	gudo	23.2.13.09.04.004
1347	Delon Nathanael Tandio	Male	2009-12-16	2026-05-28 12:54:08	2026-05-28 12:54:08	\N	3515181612090003	0000000000000	Aktif	\N	\N	Surabaya	A	Jalan Durian 2 e474	082132027478	\N	Dojo Petra	24.3.13.01.08.010
1329	ADHIYASTHA YAHYA MAULANA	Male	2012-08-21	2026-05-26 10:05:38	2026-05-28 14:59:04	\N	3578082108120002	0000000000000	Aktif	\N	\N	Surabaya	O	Jl. Gubeng Kertajaya 8D/17 Surabaya\n	 081230386800	athlete_photos/vjPV88qpcxq5J0XXYnzbVg1NfM6aYhgv4Ybbdnem.png	Dojo UWK	21.1.13.01.20.001
1295	Lucky Adhyastha Alviando	Male	2015-05-01	2026-05-26 04:36:56	2026-05-27 14:08:38	\N	3578120105150003	0000000000000	Aktif	\N	\N	Surabaya	O	Jl. Kalimas Baru 2 gang buntu RT 011 RW 009 no.24	081330046015	athlete_photos/RUCIf5TgoZ5dkIB6mBfglLjirwNHyA4pKYCgKRos.jpg	Dojo Perak 	23.3.13.01.24.006
1346	R. Ahmad Arvie Firdaus	Male	2015-12-07	2026-05-27 03:41:41	2026-05-27 14:08:38	\N	3509200712150002	0000000000000	Aktif	\N	\N	Jember	B	Jalan Taman Indah VI no 23, Sepanjang, Taman, Sidoarjo	08123480480	athlete_photos/SWILGfT4YCLq7zDIK6rihKB0FBPriQCAlGqyFcNs.png	UWK	21.3.18.01.09.012
1312	Intan Renia Anggraini	Female	2013-04-25	2026-05-26 05:10:16	2026-05-28 05:45:32	\N	3515166504130001	0000000000000	Aktif	\N	\N	Sidoarjo	-	Tambak Wedi Baru Utara 18B No 28	085105017208	athlete_photos/1nlcIspdOzW1Y6AV1dhPN703roOjPIO4O94zUeOW.jpg	Perak	22.3.13.01.24.007
1213	ASTRIT RABECA YOLANDA	Female	2005-06-21	2026-05-20 11:13:14	2026-06-05 09:45:33	\N	3526036106050002	00000000000	Aktif	\N	\N	BANGKALAN 	AB+	JL. NAGASARI NO 13 BURNEH 		athlete_photos/7xzRq8htjTs9e0BoODcStK5inHj4h9mloE2TIjec.jpg	GALIS BANGKALAN 	24.1.13.13.02.007
1214	OCTA FIANDAR	Male	2007-10-27	2026-05-20 11:13:14	2026-06-05 09:45:33	\N	3526032708070002	00000000000	Aktif	\N	\N	SURABAYA	O+	JL. GEMBIRA NO 37		athlete_photos/TfYegSpyaK7xgdcnCkAMkc7OzKfSzPs4TE0pTvB9.jpg	GALIS BANGKALAN 	24.1.13.13.02.005
1217	NAILA	Female	2008-10-29	2026-05-20 11:13:14	2026-06-05 09:45:33	\N	3526016910080001	00000000000	Aktif	\N	\N	BANGKALAN	O	JL. RAYA BANCARAN 		athlete_photos/1WBxmBBQJk3hdhtTJ06OTp46UIzFEQ7n34PEKU5l.jpg	TRUNOJOYO	24.3.13.13.03.010
1218	MOHAMAD RIZQI WIRA PUTRA	Male	2011-07-01	2026-05-20 11:13:14	2026-06-05 09:45:33	\N	3515130107110004	00000000000	Aktif	\N	\N	SIDOARJO	O	PERUM GRAHA MENTARI BLOCK C 8		athlete_photos/jlGtA1K5DkMH2NfCPZQjJrWd2HHbTq3H69r7fLOl.jpg	GALIS	25.3.13..13.02.001
1240	M. ARGA ADYASTA RESWARA	Male	2011-04-01	2026-05-24 00:16:55	2026-05-24 00:16:55	\N	3506140104110001	3506140104110001	Aktif	\N	\N	KEDIRI	-	MOJOROTO KOTA KEDIRI	-	\N	JAYABAYA	17.3.13.02.02.001
1241	Yohanes Anselmo Mau	Male	2006-06-25	2026-05-24 00:16:55	2026-05-24 00:16:55	\N	3571025060600006	3571025060600006	Aktif	\N	\N	KEDIRI	-	MOJOROTO KOTA KEDIRI	!	\N	JAYABAYA	18.1.13.02.02.004
1275	APRILLIA EKA NURAINI	Female	2009-04-20	2026-05-25 10:50:02	2026-05-25 16:17:16	\N	3517096004090003	351704510912	Aktif	\N	\N	JOMBANG	AB	JL. KAPTEN TANDEAN NO. 151\nRT/RW:004/005\nDESA:PULO LOR\nKECAMATAN:JOMBANG	081333098319	athlete_photos/n83JEIebAXC5MOU27FTgJq4bTxj7ChvbYL1NcPOp.png	DOJO SMPN 1 JOMBANG	24.3.13.09.07.005
1313	Athalia Betaria Samosir	Female	2012-06-06	2026-05-26 05:11:02	2026-05-28 12:54:08	\N	3578230101088891	0000000000000	Aktif	\N	\N	Surabaya	O	Ketintang Madya II No. 36, Surabaya	082131433193	athlete_photos/USKiRfXXucLQaZeAUmxcorLoXFCDKMBcSVl5JVFN.jpg	Dojo Petra	25.3.13.01.08.003
1296	Maria Gunthildis Betan Wuwur	Female	2014-10-16	2026-05-26 04:43:24	2026-05-26 04:43:24	\N	1807145610140001	0003938468139	Aktif	\N	\N	Surabaya	-	Kalimas baru 3 lebar tengah no 51 RT 08 RW 06 kelurahan tanjung perak kecamatan pabean cantian	081235718783	athlete_photos/CsLZqTEDlUcxDrkTR8YtCmTYuZ2nldrvcEHZcOcW.jpg	Dojo Perak	23.3.13.01.24.002
1330	Kirana Bellvania Ramadhani 	Female	2011-09-01	2026-05-26 10:07:55	2026-05-26 10:07:55	\N	3578084109110004	0626220600151	Aktif	\N	\N	Surabaya	-	JL. Gunung Anyar Jaya Safira No. 5 kav 21	081959736585	athlete_photos/jLeG7nvSbA4ZwYIU2sPObIQA6hTyXLcJtcWSwio1.jpg	Dojo UWK	22.3.13.01.20.018
1258	Belvania Rahmadina Rabbani	Female	2016-11-10	2026-05-24 22:52:34	2026-05-27 22:40:00	\N	3510135011160003	26067758164	Aktif	\N	\N	Lumajang	-	Jl Malik Ibrahim Gang Iv No.11a Gresik, Kab. Gresik	0811347812	athlete_photos/SV7tQxP3j5b0MAdx3Js0OqkBcw6d4woHQM4Xw5Yi.jpg	Semen Gresik	25.2.13.06.01.005
1348	Anabel Sarah Maheswari	Female	2011-10-19	2026-05-28 14:59:04	2026-05-28 14:59:04	\N	3173071401160007	0002287135326	Aktif	\N	\N	Jakarta	O	Jl. Semolowaru Tengah I No 34	085888388211	\N	Petra	24.3.13.01.08.007
1219	Andini Masayu Mustikaning Ratri	Female	2007-06-19	2026-05-20 12:35:42	2026-06-04 06:06:27	\N	3510035906070003	12345	Aktif	\N	\N	Banyuwangi	B	Dusun Curahpecak RT/RW 05/03 Desa Purwoharjo Kec. Purwoharjo	085232072602	athlete_photos/bd4xkfh70XCzEy5oEhtg7FvjlItqUoBl5df4dXKi.jpg	SMA PGRI Purwoharjo	23.3.13.16.01.005
1242	BUSHIDO AJIE SAHPUTRA	Male	2010-06-07	2026-05-24 06:09:38	2026-05-24 06:41:09	\N	3523150706090002	000000000000	Aktif	\N	\N	TUBAN	-	DESA PRUNGGAHAN WETAN KECAMATAN SEMANDING KABUPATEN TUBAN		athlete_photos/f7ABb5tIQEWjVpH4stDKo1Zod4RjLJyGM4YYVBL4.png	DOJO RONGGOLAWE TUBAN	15.3.13.12.01.006
1276	Meysa Ayu Riswanti	Female	2014-05-31	2026-05-25 11:33:38	2026-05-25 15:44:24	\N	3578307105140004	35170451091	Aktif	\N	\N	Surabaya	-	Pondok Benowo indah blok fh no 4 Surabaya 	083847840828	athlete_photos/AZRJ2teyRkYJ7x9T3HFZsVmF361qDRIs7pqvW8hQ.jpg	DOJO MOJOWARNO 	24.3.13.09.09.008
1259	Ahmad Luthfi Raihan	Male	2014-12-25	2026-05-24 22:59:53	2026-05-25 16:10:10	\N	3525142512140004	26067758172	Aktif	\N	\N	Jombang	-	Jl Kptn Darmosugondo 12d/74 Indro, Gresik, Kab. Gresik	089560388060	athlete_photos/D38CJsPZFWz28f4LYzcJDZSLNEoe2WggHzQ0ptUI.jpg	Semen Gresik	25.2.13.06.01.001
1314	Andrean dwi rekso jovani	Male	2013-06-19	2026-05-26 05:14:11	2026-05-26 05:14:11	\N	3318051906130003	0002841231172	Aktif	\N	\N	Pati	-	Kalimas baru2 buntu no264 Rt 13 Rw 09	082136264651	athlete_photos/1z2nZKbY2Ane5QGJ9mRSyj1fHNvLGiTR8THpvDVo.jpg	Perak	25.3.13.01.24.007
1297	Kaindra Aldan Limantoro	Male	2017-05-30	2026-05-26 04:46:55	2026-05-27 14:08:38	\N	3578083005170004	0000000000000	Aktif	\N	\N	Surabaya	-	Jl. Baratajaya 21/41	087850550931	athlete_photos/hy0aaqgSYqDpjvtVWa68lJub9ED4hM7ma3m6r0a3.jpg	Dojo UWK	23.1.13.01.20.001
1349	Bisma Ali Kumara	Male	2009-04-25	2026-05-31 11:30:11	2026-05-31 11:30:11	\N	3578122804090001	2407301975	Aktif	\N	\N	Surabaya	-	Jl. Teluk Nibung Timur 4 no 48 	085843233477	athlete_photos/0lffibiWObp1kiHPSo48MJhX80AgVALArGw5KTLW.jpg	Perak	16.2.13.01.24.002
1220	Naysila Cinta Aulia	Female	2009-07-17	2026-05-20 12:45:38	2026-06-04 17:13:54	\N	3510035707090001	12345	Aktif	\N	\N	Banyuwangi	-	Dusun Gumukrejo RT/RW 01/06 Desa Purwoharjo Kec. Purwoharjo	082335321668	athlete_photos/MO7cQkEBjZqds0GVGX9aCUl7WcxCmgnxjzKAoh6z.jpg	SMA PGRI Purwoharjo	23.3.13.16.01.003
1332	Raung Laksono	Male	2011-10-04	2026-05-26 10:25:39	2026-05-28 14:59:04	\N	3578180410110002	0000000000000	Aktif	\N	\N	Surabaya	-	LIDAH WETAN GG 7/58 A/KECAMATAN LAKARSANTRI SBY 	082334412507	athlete_photos/0VbYJIPfakk3TPfb6eUlQo5zl61iS3L5uRzyM1HL.jpg	Dojo UNESA	22.3.13.01.09.007
1243	LUTFI RIZKI WAHYU UTAMA	Male	2011-09-25	2026-05-24 06:12:07	2026-05-24 07:07:05	\N	3523152409110002	000000000000	Aktif	\N	\N	TUBAN	-	DESA TEGALAGUNG RT 02 RW 03\nSEMANDING - TUBAN		athlete_photos/a7aXm5YQ3Hh78iXKGsQGoiL8ZRwQAT7E6n2DrDXT.png	DOJO RONGGOLAWE TUBAN	21.1.13.12.03.008
1277	Ganendra Waradana Prayuda	Male	2010-05-25	2026-05-25 11:36:17	2026-05-25 16:17:16	\N	3517022506100002	3517045109	Aktif	\N	\N	jombang	-	dusun legundi desa gempolegundi RT.008 RW.003 kec.gudo kab.jombang	085645781048	athlete_photos/osyO5ml8DjQe6IChUnVKzzjBEwWJbZBZqNy5yk2v.jpg	dojo smpn 1 gudo	23.3.13.09.04.005
1298	Alaire Tedy Prasetyo	Male	2011-08-27	2026-05-26 04:49:29	2026-05-27 14:08:38	\N	3578092708110002	0000000000000	Aktif	\N	\N	Surabaya	O	Kedung Tomas 4 No. 39	089687291089	athlete_photos/6G8S0MnalEzeqHvyEXGxswyNmw9MARWBbRt1cKye.jpg	Dojo Narotama	23.3.13.01.19.001
1260	Muhammad El Syirazi Ariansyah 	Male	2013-04-19	2026-05-24 23:04:54	2026-05-27 22:40:00	\N	3525141904130002	26067758123	Aktif	\N	\N	Gresik	-	Jl. Raya Brantas No.52 Rt : 01 Rw : 05, Randuagung, Kab. Gresik	081231406735	athlete_photos/XP4gR0n9SHRENSorYuKmcHPL22wC3EB1Uicanau9.jpg	Semen Gresik	24.1.13.06.01.004
1315	Ahmad Maulana Ibrahim 	Male	2012-03-27	2026-05-26 05:15:52	2026-05-28 05:45:32	\N	6402162703120003	0000000000000	Aktif	\N	\N	Jember	O	Sidomulyo 2-A/26, Sidotopo wetan - Surabaya 	085852004611	athlete_photos/NIE5PjsyCvUqxuIYVgYeGqAjtnZOt4uhRgcKtCfw.jpg	Perak	20.1.15.04.03.003
1350	Nashiful Fatih Abinawa	Male	2013-04-15	2026-06-03 04:55:58	2026-06-03 05:15:09	\N	3175031504131005	0000000000000	Aktif	\N	\N	Jakarta	A	Pondok Nirwana Anggaswangi, Cluster Amaryllis Blok L no.5, Sidoarjo	0878-5448-8881	athlete_photos/IFRVe04OGX697ICpNSjDPRMSVLeiNbgZQsMR09yV.jpg	Pagerwojo, Sidoarjo	 25.3.13.08.05.003
1221	Rohma Anggun Oktaviandini	Female	2006-10-12	2026-05-20 13:03:32	2026-06-04 17:33:57	\N	3510075210060003	12345	Aktif	\N	\N	Banyuwangi	O	Dusun Tempurejo Desa Purwodadi Kec. Gambiran	085748497734	athlete_photos/o1rR3uqisMnW3xTbddFWraOqUjmMzJI1ycXfkVmM.jpg	SMA PGRI Purwoharjo	19.1.13.16.01.038
1244	TAHFAQUL MANAN RAMADHANI	Male	2011-08-25	2026-05-24 06:14:43	2026-05-24 07:07:05	\N	3523152508110004	000000000000	Aktif	\N	\N	TUBAN	-	DESA TEGALAGUNG RT 01 RW 03\nSEMANDING-TUBAN		athlete_photos/tZXcKrmaivFMOkpGESp8EVCQYD7HyUBeP7jboU4S.png	DOJO RONGGOLAWE TUBAN	21.1.13.12.03.015
1278	AISYAH ZASKIANDRA JASMINE	Female	2009-10-19	2026-05-25 11:43:55	2026-05-25 11:43:55	\N	3517095910090001	001288164925	Aktif	\N	\N	JOMBANG	-	DSN : DENANYAR\nRT/RW:14/07\nDESA:DENANYAR \nKECAMATAN:JOMBANG	085730826675	athlete_photos/50LzHD652raBmYsUufSJdc4YLtGLfzx5JnmkI9Lx.png	DOJO GMK 	21.3.13.09.07.001
1299	As'ad Tegar Ballan Firmansyah	Male	2013-11-25	2026-05-26 04:51:01	2026-05-27 14:27:12	\N	6473032511130001	0000000000000	Aktif	\N	\N	Tarakan	A	Sidosermo 2 Blok A/4 Surabaya	082139130427	athlete_photos/zPyE9DVYljOLUnhmUACvFX7s5V2dOQsfVgZzpNfS.jpg	UWK	25.1.13.01.08.001
1261	Dewi Lembayung	Female	2012-04-05	2026-05-24 23:07:41	2026-05-27 22:40:00	\N	3525144504120003	26067758131	Aktif	\N	\N	Gresik	-	Jl Veteran II / 33 Sidomoro, Kab. Gresik	081332478777	athlete_photos/T85oO0d39qddJFF9lXQhqNeQxEHwS8j6RprwgE0u.jpg	Semen Gresik	24.3.13.06.01.002
1316	Farah azalia salshabila ilham	Female	2010-03-26	2026-05-26 05:17:23	2026-05-28 05:45:32	\N	3578122503250002	0000000000000	Aktif	\N	\N	Surabaya	-	jl. Teluk nibung timur 4 no 43	082230014230	athlete_photos/3U0Ss8E5Hl7TLDAqWfGm7ggLscihQ2UpoT6ngTmZ.jpg	Perak	18.1.13.01.24.002
1351	Endyta Salsabilla	Female	2011-11-11	2026-06-03 04:56:49	2026-06-03 05:15:09	\N	3515145111111002	0000000000000	Aktif	\N	\N	Sidoarjo	-	Perum Permata Sukodono Raya Blok C1 No33, Sidoarjo\n	085198353732	athlete_photos/MwAA8pnRgrYIbHbsy5wWFNAEtRZJ5Q4Oh9HCFeWI.jpg	Pagerwojo, Sidoarjo	25.1.13.08.05.002
1222	Ditya Roni Saputra	Male	2007-12-12	2026-05-20 13:08:13	2026-06-04 06:22:14	\N	3510031212070003	12345	Aktif	\N	\N	Banyuwangi	O	Dusun Curahpalung RT/RW 03/01 Desa Kradenan Kec. Purwoharjo	081529423084	athlete_photos/NUX4shXLPDAtExwDWXTp0Q70qFnH23wKTu20TYXZ.jpg	SMA PGRI Purwoharjo	25.3.13.16.01.008
1245	SABRINA NASIKHATUL HUSNA	Female	2011-04-21	2026-05-24 06:17:58	2026-05-24 07:07:05	\N	3523156104110002	000000000000	Aktif	\N	\N	TUBAN	-	DESA TEGALAGUNG RT 01 RW 03\nSEMANDING - TUBAN		athlete_photos/JSDvncbg7VXH6tSEJGle5XCsztIRSZaAvvfL3qmH.png	DOJO RONGGOLAWE TUBAN	21.1.13.12.03.013
1279	Titus Althea Marhaendra	Male	2013-03-26	2026-05-25 12:02:27	2026-05-25 15:08:21	\N	3517042603130001	3517042603130001	Aktif	\N	\N	Jombang	-	RT.002/RW.002 Dsn. Kayen Ds. Mojotengah kec. Bareng kab. Jombang 	085755122018	athlete_photos/LS42ycKgL5JbaEwMiCPhchkiCoucu7evAUNLycwr.jpg	Koramil Mojowarno 	24.3.13.09.09 015
1300	JADON DAFFIN JANUADRI	Male	2010-09-05	2026-05-26 04:52:06	2026-05-26 04:52:06	\N	3515180509100001	0001471130741	Aktif	\N	\N	Surabaya	O	JL.Waru Indah No.1	081216569326	athlete_photos/u2He2PNmzhio4uDUOHloFz3OmmVQ11pCtUuBlS6s.jpg	Dojo Petra	24.3.13.01.08.020
1262	M. Qhalid Eka Wahyu Putra	Male	2011-11-02	2026-05-24 23:10:27	2026-05-27 22:40:00	\N	3525160211110003	25164211465	Aktif	\N	\N	Gresik	-	Jl Dewi Sekardadu 004/001 Gresik, Kab. Gresik	085735126787	athlete_photos/Fo3pJ1AxWGrKYmnCGvQOubvjrC0M8RmSfxHjQ3cN.jpg	Semen Gresik	25.2.13.06.01.009
1317	Bilqis Ammardivia G 	Female	2006-02-09	2026-05-26 05:19:24	2026-05-28 05:45:32	\N	3578124902060001	0000000000000	Aktif	\N	\N	Surabaya	B	Jl. Teluk Penanjung no 37	085746517955	athlete_photos/l9odh4lga9LV5N7U85akN6RimGMhiwoAZkHVz5TS.jpg	Perak	13.3.13.01.24.004
1223	Dinda Nofitasari	Female	2005-11-22	2026-05-20 13:14:27	2026-06-04 17:19:52	\N	3510066201050002	123456	Aktif	\N	\N	Banyuwangi	O	Dusun Wringinpitu RT/RW 01/01 Desa Plampangrejo Kec.Cluring	081249414369	athlete_photos/tBNvLJwN23bXQWIqXm73P80uBRkWcfcdevPAZcme.jpg	SMA PGRI Purwoharjo	17.1.13.16.01.019
1246	KAYSHA CAHYANI SYAM SORAYA	Female	2013-12-24	2026-05-24 06:20:58	2026-05-24 07:07:05	\N	3523156412110001	000000000000	Aktif	\N	\N	TUBAN	-	DUSUN GEMPOL\nDESA GENAHARJO, SEMANDING - TUBAN		athlete_photos/ipMW0NDKVFbVAUqQbEfR3SrWZwCj8xjlqngK74Uz.png	DOJO RONGGOLAWE	25.1.13.12.03.002
1280	Daniswara Gaozan	Male	2009-11-29	2026-05-25 12:38:38	2026-05-25 16:17:16	\N	3517192911090002	3517045109122	Aktif	\N	\N	Denpasar	AB	Rt.17 Rw.5 Dsn.Mayangan Ds.Mayangan Kec.Jogoroto Kab.jombang	087755819593	athlete_photos/2qBt3EgnQ7sQVnhUvablfKNLC4iE2r49ftxnHAtD.jpg	Dojo SMANSA	24.3.13.09.10.017
1318	Reinhard Henokh Stardani Panambunan 	Male	2010-10-07	2026-05-26 05:25:05	2026-05-28 12:54:08	\N	3578120710100001	0000000000000	Aktif	\N	\N	Surabaya	A	Taman Rivera Regency blok N no-18 	085331107048	athlete_photos/mNXpEw5CZ6W02wIXr3eWUaDLImJRWjPwlgcL3iiH.jpg	Dojo UWK	24.3.13.01.08.002
1301	Muhammad Hanif Abrar Rivandra	Male	2014-02-27	2026-05-26 04:54:20	2026-05-27 14:27:13	\N	3578262702140001	0000000000000	Aktif	\N	\N	Surabaya	B	Jl. Semolowaru Selatan XI No.10 Surabaya	0896-7111-7111	athlete_photos/R5cl0wfnIvEsnFtnqbs8vvTaDRLXJr36jJ3y7ADh.jpg	PLN Nusantara Power	23.3.13.01.20.003
1263	Muhammad Fernando Alamsyah 	Male	2011-12-27	2026-05-24 23:18:04	2026-05-27 22:40:00	\N	3525142712110003	25164211507	Aktif	\N	\N	Gresik	-	Perum Bukit Randuagung Indah, Gresik	085101823235	athlete_photos/HuYluqn58873b2wioTCJbLx32GwCxtB5Z6TQBUO2.jpg	Semen Gresik	19.1.13.06.01.021
1335	Fais Pratama Nugroho 	Male	2010-09-07	2026-05-26 10:28:27	2026-05-28 14:59:04	\N	3578220709100001	0000000000000	Aktif	\N	\N	Sidoarjo	-	Jln. Ketintang Madya No. 35	087864087070	athlete_photos/WEvKmro6lDgFB0gREd4zOBB9ULB3zu2sGZ2Jhsyv.jpg	PLN Nusantara Power	18.3.13.01.20.005
1353	Louis Bintang Dirgantara Lazaro	Male	2009-03-07	2026-06-03 04:58:20	2026-06-03 05:15:09	\N	3578110703090001	0000000000000	Aktif	\N	\N	Surabaya	-	Citra Fajar Golf AT 2000 / B2030, Sidoarjo\n	 088989083257	athlete_photos/TvC1e6Zz7HxjtAq4WWo0WTfVCW6eJkBZDyAJ57cm.jpg	Pagerwojo, Sidoarjo	25.1.13.08.05.004
1224	Bellia Aprilia	Female	2007-04-08	2026-05-20 13:21:17	2026-06-04 06:10:10	\N	3510064804070004	5678	Aktif	\N	\N	Banyuwangi	A	Dusun Tanjungrejo RT/02 RW/03, Desa Sembulung Kec. Cluring	083895480108	athlete_photos/kkC5L4eJBgk3Y6mwDH6NnpBgYdJn19riTZ0Jwkm2.jpg	SMA PGRI Purwoharjo	22.1.13.16.01.003
1247	SHAFA HERMAWAN	Female	2013-04-15	2026-05-24 06:23:43	2026-05-24 07:07:05	\N	3523155504110001	000000000000	Aktif	\N	\N	TUBAN	-	DESA PRUNGGAHAN WETAN RT 03 RW 01\nKECAMATAN SEMANDING- TUBAN		athlete_photos/vrLYKXnQZaGOOy1Wv1v6eD37dm0fTScsYPr3MNyr.png	DOJO RONGGOLAWE TUBAN	25.1.13.12.03.004
1264	Achmad Muflich Rojabi	Male	2010-09-03	2026-05-24 23:20:48	2026-05-24 23:20:48	\N	3525050309100002	26067758115	Aktif	\N	\N	Gresik	-	 Jl Sumari Kec Duduk Sampeyan, Kabupaten Gresik	085784420136	athlete_photos/3bzRtCp1NM2hY2ywgfUzHaOyoeh4ruEFt30Slx5U.jpg	Semen Gresik	26.1.13.06.01.002
1281	AKHMAD WAKHID IBRAHIM	Male	2013-08-08	2026-05-25 13:20:34	2026-05-25 15:44:24	\N	3517110808130002	351704510912	Aktif	\N	\N	JOMBANG	-	Dsn. Nglele rt. 02 rw. 01 ds. Nglele kecamatan sumobito jombang	082336425598	athlete_photos/fO3KAYPsfBDAHWeyj74F6Wnugq7cx55E5k5inMZr.jpg	Al - fatih	25.2.13.09.10.006
1302	Beby Naura Ozyllia Janeeta 	Female	2014-01-11	2026-05-26 04:57:26	2026-05-27 14:27:13	\N	3578025101140003	0000000000000	Aktif	\N	\N	Surabaya	-	Jln.sidosermo 4 GG 5 no 12	082227665580 / 081938141499	athlete_photos/8s7afuGrUF85i0uqZZckZ4vL8ld79NM2ILaeg2cH.jpg	UWK	24.1.13.01.20.002
1336	Azzizah Lucita Zaviera	Female	2011-03-31	2026-05-26 10:29:42	2026-05-28 14:59:04	\N	3578127103110001	0000000000000	Aktif	\N	\N	Surabaya	-	JL. Teluk Tomini No 18	087861306300	athlete_photos/MKRDt40b4DAdogJhRILFmGRXkPogaSITBLY5iaxO.png	Dojo Perak	23.1.13.01.24.001
1225	Yetania Vita Zabrina	Female	2010-04-02	2026-05-20 13:29:03	2026-06-04 17:05:53	\N	3510034204100003	123456	Aktif	\N	\N	Banyuwangi	O	Dusun Curahpecak Desa Purwoharjo Kec. Purwoharjo	082339816176	athlete_photos/6fOCJ6W04NYfYGE5yxYybPkhlOAyu9Qim3y5OeiE.jpg	SMA PGRI Purwoharjo	23.3.13.16.01.004
1355	Rafka Adrian Ichiro Sunyoto 	Male	2009-05-19	2026-06-03 05:00:10	2026-06-03 05:15:09	\N	3515161905090002	0000000000000	Aktif	\N	\N	Sidoarjo	-	Punggul rt03/rw02 no.11 Gedangan Sidoarjo	081334677743	athlete_photos/XGcyzW8wDncGQtCII6QcMeU4zJZQ1fbn7lQMicCH.jpg	Pagerwojo, Sidoarjo	25.1.13.08.05.006
1248	AURORA FELICIA HERMA PUTRI	Female	2013-05-20	2026-05-24 07:09:45	2026-05-24 07:27:51	\N	3523166005110002	000000000000	Aktif	\N	\N	TUBAN	-	JL. BASUKI RAHMAT GG. SERUT NO. 27\n RONGGOMULYO, TUBAN		athlete_photos/rn56Tp9XOpFMOny6mb7bYSotNnSA3FiW2XiwoRPC.png	DOJO RONGGOLAWE TUBAN	25.1.13.12.03.001
1282	MUHAMMAD SAMUDERA GENETIKA SULFAN	Male	2009-04-01	2026-05-25 13:23:37	2026-05-25 16:56:04	\N	3517020104090002	6539892762	Aktif	\N	\N	Jombang	-	Dsn. Mojongapit, Kab. Jombang	08990764442	athlete_photos/idNpj9jnydNqPUZ1amkIgpFPWmUz445zkEQ3MrJ9.jpg	SMPN 1 Jombang	23.2.13.09.07.005
1303	Mikael Eko Grestrianto Aji	Male	2013-09-08	2026-05-26 04:58:06	2026-05-26 04:58:20	\N	3316050809130003	23066900616	Aktif	\N	\N	Surabaya	B	Kutisari Townhouse 1, Jalan Kutisari Utara 3A No.10	0811310552	athlete_photos/qEDuBRNhBdSoIBqWxIdSqci9mg1YLmP5VTFOMw8Q.jpg	Dojo PJB - PLN Nusantara Power	22.3.13.01.20.009
1320	Fariz Fernando	Male	2009-05-19	2026-05-26 09:28:26	2026-05-26 09:28:26	\N	3578231910090001	24073019127	Aktif	\N	\N	Surabaya	-	Jl.Kebonsari Tengah 47-B	0881027728257	athlete_photos/ziVhaGQkPhRXE1A1rw6VDNNNG5PPbJilRwzcOLdf.jpg	Dojo PLN-NP (PJB)	21.1 13.01.20.003
1337	GIOVANNI PUTRA HARMAWAN	Male	2010-02-27	2026-05-26 10:30:30	2026-05-26 10:30:30	\N	3578062702100001	24073019143	Aktif	\N	\N	Surabaya	B	Bulu Jaya4/9	081358894606	athlete_photos/YiPtSl9BRFpUidv48ppeGSNcActvmSsZo0ldZHXm.jpg	PLN Nusantara Power	22.3.13.01.20.016
1265	Hana Bhakitah Fadiyah	Female	2011-02-18	2026-05-24 23:24:02	2026-05-27 22:40:00	\N	3525145802120001	26067758149	Aktif	\N	\N	Gresik	-	Jl Veteran No.206 Rt07 Rw01 Segoromadu, Gresik, Gresik	085645819227	athlete_photos/SKTSPKx8LE2HriBTUEymgBu9pbPElu0t4bSulQgq.jpg	Semen Gresik	26.2.13.06.01.001
1226	ACH.FAIZ PUTRA PRATAMA JIANZAH 	Male	2008-04-30	2026-05-20 13:29:46	2026-06-05 09:45:33	\N	3526033004080001	00000000000	Aktif	\N	\N	BANGKALAN 	B+	JL. PAHLAWAN NO 242 		athlete_photos/2D2a8T8FnOiGrJKKvslDCvvuuWHYDvMz8shXmHCq.jpg	GALIS BANGKALAN 	24.1.13.13.02.001
1227	WIRA ALFI QOLBI NURAN	Male	2009-09-18	2026-05-20 13:29:46	2026-06-05 09:45:33	\N	3526181809090003	00000000000	Aktif	\N	\N	BANGKALAN 	B+	JL.ASRAMA KODIM		athlete_photos/LmqYFV0QWjZhGVno2o3cuZxQwvfHivJsHU1fremi.jpg	GALIS BANGKALAN	25.2.13.13.02.002
1228	NISRINA KHAYLA ZHAFIRAH	Female	2015-01-07	2026-05-20 13:29:46	2026-06-05 09:45:33	\N	3526044701150001	00000000000	Aktif	\N	\N	BANGKALAN 	B	KARSON REGENCY BLOK D NO 09		athlete_photos/tqDeiNXYjQsdlGeKzRw3ZdiuSV67840kEbEHbiPp.jpg	GALIS BANGKALAN 	25.2.13.13.02.001
1229	DIMAS DZAKY 	Male	2008-10-01	2026-05-20 13:29:46	2026-06-05 09:45:33	\N	3573021001080003	00000000000	Aktif	\N	\N	MALANG	O	JL. PONDOK HALIM 2 BLOK CI / 27		athlete_photos/sVL1yMC2XVBuc4ONsbP2JdM1ufhcPyZ5WYdKwuc2.jpg	GALIS BANGKALAN 	24.1.13.13.02.002
1321	Bilvina Aqila Saumifathiyah	Female	2010-09-09	2026-05-26 09:32:00	2026-05-28 12:54:08	\N	3578124909100003	000000000000	Aktif	\N	\N	Surabaya	B	Jl. Teluk Penanjung No. 37	085806989699	athlete_photos/sAR9D6bmF0PYVw0bDIv1OPNvsoEm7W2h2mIBhe1K.png	Dojo Perak	16.2.13.01.24.001
1249	RAFAEL MILANO ARDITIA	Male	2013-09-20	2026-05-24 07:18:17	2026-05-24 08:33:23	\N	3523152009120001	000000000000	Aktif	\N	\N	TUBAN	-	DESA PRUNGGAHAN WETAN RT 01 RW 02\nSEMANDING, TUBAN		athlete_photos/2SbE9JmzCJW11iFpwxpKqxukuUz3F3SJm9R5DdJk.png	DOJO RONGGOLAWE TUBAN	24.3.13.12.03.006
1283	MUHAMMAD RAKA GARADHIKA SULFAN	Male	2013-09-16	2026-05-25 13:25:43	2026-05-25 15:44:24	\N	3517021609130002	3517045109	Aktif	\N	\N	Jombang	-	Dsn. Mojongapit, Kab. Jombang	08990764442	athlete_photos/ZpVpowX9R9slaUVMUBTCbNTbNuCX1Na7dokhimTQ.jpg	Al-Fatih Jombang	25.2.13.09.10.005
1266	Siti Aisyah Suhebi	Female	2010-03-19	2026-05-24 23:26:48	2026-05-27 22:40:00	\N	3525165903100002	26067758156	Aktif	\N	\N	Gresik	-	Jl Jaksa Agung Suprapto Gg 8 No.87, Gresik	085735137425	athlete_photos/bEIO4nuQXB74hBIOiR5biyR5NYQGkMBhK4hcML0S.jpg	Semen Gresik	26.2.13.06.01.002
1304	Farzana Delisha Wicaksono 	Female	2012-07-08	2026-05-26 05:00:09	2026-05-28 05:45:32	\N	3578024807120002	0000000000000	Aktif	\N	\N	Surabaya	O	Jl Wisma Menanggal 3 no 14 	085755497473	athlete_photos/RAI4LnOCAkgRNsm8yG4EaR1YpeZNPGZuhAw64sAZ.png	PLN Nusantara Power	21.1.13.01.20.004
1338	Desta Rizky Syahputra	Male	2009-12-22	2026-05-26 10:33:02	2026-05-28 14:59:04	\N	3578182212090004	0000000000000	Aktif	\N	\N	Surabaya	AB	Jl. Wisma Lidah Kulon Blok C109	082331258090	athlete_photos/kWKZbd6tl7oq2HhoJmxDfMt7AUd9dXj3D4k43EVn.jpg	Unesa	18.3.13.01.09.003
1356	Muhammad Fattan Altaf	Male	2010-04-13	2026-06-03 05:15:09	2026-06-03 05:15:09	\N	3515141304100004	0000000000000	Aktif	\N	\N	Sidoarjo	-	Perum Permata Sukodono Raya Blok C1 No33, Sidoarjo	081217228679	athlete_photos/J5nTqub8YtlmqeyKPiFN45oy2XPUNpyNvIlBrl30.jpg	Pagerwojo, Sidoarjo	25.1.13.08.05.005
1357	William Tranggana Samudra Lazaro	Male	2011-05-15	2026-06-03 05:15:09	2026-06-03 05:15:09	\N	3578111505110001	0000000000000	Aktif	\N	\N	Surabaya	-	Citra Fajar Golf AT 2000 / B2030, Sidoarjo	088989452277	athlete_photos/YMpN9lt7ndIJeWBJURFjRPJ54FUwIreTBzH157om.jpg	Pagerwojo, Sidoarjo	25.1.13.08.05.008
1230	David Wisnu Sudarsono 	Male	2009-02-04	2026-05-20 13:44:12	2026-06-04 06:19:51	\N	3510040402090004	123456	Aktif	\N	\N	Banyuwangi	B	Dusun Ringenasri,RT/RW 018/005, Desa Wringenpitu Kec. Tegaldlimo	083822887870	athlete_photos/1FWbzDX9kBCaY6CNvvt0CzeW4faz9gZw99nYDAim.jpg	SMA PGRI Purrwoharjo	22.1.13.16.01.004
1250	SALWA AGUSTIN ASSYIFA'	Female	2016-06-28	2026-05-24 07:45:05	2026-05-24 08:26:04	\N	3523156608140001	000000000000	Aktif	\N	\N	TUBAN	-	DESA BEJAGUNG RT 01 RW 01 \nSEMANDING-TUBAN		athlete_photos/bef4V1Sub7RmvxswmQ94E5kyMXHC12ptzIpDzw5w.png	DOJO RONGGOLAWE TUBAN	25.1.13.12.03.003
1284	RISQI EKA ERNADI	Male	2008-07-02	2026-05-25 13:29:22	2026-05-25 13:29:22	\N	3517020207080002	25065699297	Aktif	\N	\N	Jombang	-	Dsn Gempol kerep, des krembangan , kec gudo, kab Jombang	085755742818	athlete_photos/DHztKpd6Je7i6b7QlMPYKOVTYqVjyM3Z0g7T6BeQ.jpg	Dojo Gudo	22.2.13.09.04.022
1322	Abygael Kanaia Aditomo	Female	2010-02-27	2026-05-26 09:36:37	2026-05-26 09:36:37	\N	3404066702100003	0001542965196	Aktif	\N	\N	Surabaya	A	Griya Taman Asri Blok.DD-04, Tawangsari, Taman, Sidoarjo	081548326000	athlete_photos/8GcQbJWMXAF8pTk4fnZ3QYnrlufPIfgFvYZxqpRM.jpg	Dojo Petra	 25.3.13.01.08.001
1267	Nur Ahmad Teguh	Male	2009-12-27	2026-05-24 23:29:56	2026-05-27 22:40:00	\N	3525162712090003	25064110411	Aktif	\N	\N	Gresik	-	Jl Jaksa Agung Suprapto No.79 Gresik	08132628724	athlete_photos/1c1xB5ujt9R4v0e2Bnbe2U4iY6tlDCyWV1Y9aecv.jpg	Semen Gresik	23.3.13.06.01.004
1305	Alyssa Putri Djumaidah	Female	2014-04-24	2026-05-26 05:00:40	2026-05-28 12:09:44	\N	3578126404140002	0000000000000	Aktif	\N	\N	Surabaya	-	Jl. Teluk Nibung Timur 4 no 48 	-	athlete_photos/aICBj8NjCv3j8kYWbrBQXsyxo7E6KpLTxSoXUPmm.png	Dojo Perak	   \t 22.3.13.01.24.012
1358	Muhammad Faza Nabhani	Male	2009-04-24	2026-06-03 09:52:06	2026-06-03 09:52:06	\N	3506062404090001	3506062404090001	Aktif	\N	\N	KEDIRI	-	KOTA KEDIRI	3	\N	JAYABAYA	24.2.13.02.02.006
1231	Devid Wisnu Sudarsono 	Male	2009-02-04	2026-05-20 13:51:04	2026-06-04 16:58:17	\N	3510040402090005	123456	Aktif	\N	\N	Banyuwangi	B	Dusun Ringinasri Desa Wringinpitu Kec. tegaldlimo	083853760657	athlete_photos/JftgVv4C8njlk2HKnnGOanKTAh16a40T1LC61NGQ.jpg	SMA PGRI Purwoharjo	22.1.13.16.01.006
1251	WAHYUDIN SAPUTRA UMAR	Male	2006-01-17	2026-05-24 08:50:55	2026-05-24 08:50:55	\N	5308181701060001	000000000000	Aktif	\N	\N	ENDE		JALAN WOLOARE KOTA RATU KAB. ENDE		athlete_photos/R6DuD9NfRMetfI7Lv6ZA4UZxdNkdKKwkyoR60ilo.png	DOJO RONGGOLAWE	
1285	AZZAHRA ADINDA PUTRI 	Female	2009-10-01	2026-05-25 13:31:49	2026-05-25 16:17:16	\N	3517094110090004	3517045109	Aktif	\N	\N	10	-	JL.PATIMURA II NO.13/ A DUSUN SENGON 1\nDESA: SENGON\nKECAMATAN: JOMBANG 	083830504420	athlete_photos/ogjxvsYhQa7qmF8K0NgkJiBhrKYtQXPv21GAEqoa.png	DOJO SMPN 1 JOMBANG 	23.2.13.09.07.002
1268	ZULVA AINUN ZASKIA 	Female	2008-03-27	2026-05-25 10:26:33	2026-05-25 16:56:05	\N	3517026703080001	542526336	Aktif	\N	\N	JOMBANG	-	DSN : SEKARU \nRT/RW:002/001\nDESA:SUKOPINGGIR \nKECAMATAN:GUDO 	081958466795	athlete_photos/T2hMPH2XdSwm3MhaNX3l3ieXNEdt8bd43UeyceEY.jpg	Dojo Gudo	22.2.13.09.04.027
1323	QUENASHA GENDHIS GUPITA	Female	2010-06-19	2026-05-26 09:38:48	2026-05-26 09:38:48	\N	3578125906100003	24073019168	Aktif	\N	\N	Surabaya	-	PRAPAT KURUNG TEGAL TEGAL NO.3B	082131628785	athlete_photos/U7dGB4F4DlbmtNQMf06PEgzTEAiwxIhGmeYDCrqK.jpg	Dojo Perak	23.3.13.01.24.003
1306	Kaysha Aretha Radwa Almeiraza	Female	2012-05-22	2026-05-26 05:02:13	2026-05-28 05:45:32	\N	3578226205120003	0000000000000	Aktif	\N	\N	Surabaya	B	Ketintang Madya No. 35 Surabaya	082142033926	athlete_photos/kBpj3sQTxqJr66VvO2zW7ZlBgZOYxSAzZPtSfMNK.jpg	PL Nusantara Power	18.3.13.01.20.004
1340	Alexander Troy Moeljono 	Male	2009-12-30	2026-05-26 10:34:43	2026-05-28 14:59:04	\N	3578023012090002	0000000000000	Aktif	\N	\N	Surabaya	-	Siwalankerto selatan I/24	081231793750	athlete_photos/FiMLwUJehFoYMTItzmwc5mg5EIFLRdrKfI9EKvts.png	Dojo Petra	25.3.13.01.08.002
1232	Daniel Marsenda	Male	2009-06-30	2026-05-20 13:54:51	2026-06-04 06:14:22	\N	3510033006090003	123456	Aktif	\N	\N	Banyuwangi	-	Dusun Grajagan pantai Desa Grajagan Kecamatan purwoharjo	081529822959	athlete_photos/nHoE7vpbKDvQIk7N76pQLAr9b8oFxjOnJDxXyW7q.jpg	SMA PGRI Purwoharjo	25.3.13.16.01.001
1252	MUHAMMAD RHELZA ARIVIRGA	Male	2004-09-01	2026-05-24 09:02:49	2026-05-24 13:33:11	\N	3517190109040004	000000000000	Aktif	\N	\N	BANDUNG		DESA SUMBERMULYO KEC. JOGOROTO KAB. JOMBANG		athlete_photos/11kg3n9swGMH8Bji3cGTfHslABHNE09D9mpSajNM.png	DOJO RONGGOLAWE	16.1.13.09.05.018
1269	ARIFAH DWI ARDIYANTI	Female	2009-08-07	2026-05-25 10:30:56	2026-05-25 10:30:56	\N	3517024708090003	0002809020508	Aktif	\N	\N	JOMBANG,JAWA TIMUR	-	DSN PESANTREN, DS KREMBANGAN,KC GUDO, KBP JOMBANG	085648235223	athlete_photos/lhq3R83HzQFVMJw4ov41jGMq4dwgYZ0QdUPeQ9Va.jpg	DOJO SMPN 1 GUDO	23.2.13.09.04.003
1307	Aisyah Putri Jamiah	Female	2014-04-24	2026-05-26 05:02:44	2026-05-28 12:09:44	\N	3578126404140001	0000000000000	Aktif	\N	\N	Surabaya	-	Jl. Teluk Nibung Timur 4 no 48 	-	athlete_photos/CJ7IXRLfezdrr9ZKZgiL8sc4R6Nx14kmsmyfg1aD.png	Dojo Perak	22.3.13.01.24.011
1286	Muhammad Ervin Abigail	Male	2009-04-24	2026-05-25 14:44:34	2026-05-25 14:44:52	\N	3517032404090001	0002971118722	Aktif	\N	\N	Jombang	-	Dsn.Badang Kec. Ngoro Jombang 	085335995774	athlete_photos/t5TAGYB5UeBvAEXTvsYNV0mroZ9X5geNWtSaJHBt.jpg	SMANERO	21.1.13.09.03.025
1324	Jasmine Nur Erika	Female	2010-03-06	2026-05-26 09:42:39	2026-05-26 09:42:39	\N	3578154603100001	24073019150	Aktif	\N	\N	Sidoarjo	-	Jl. Tambak Wedi Baru Utara 18B/28	081231818808	athlete_photos/JbWDjVprnya27YiuhA0y3J527wRGzhSmfqBZYYvN.jpg	Dojo Perak	22.3.13.01.24.008
1341	DAVE AARON ELZABATH	Male	2009-11-21	2026-05-26 10:37:17	2026-05-28 14:59:04	\N	3518102111090001	0000000000000	Aktif	\N	\N	nganjuk	-	Jl. Ketintang baru selatan IV kav. 83	085784248283	athlete_photos/ZNJADfqYwJEmuxeEJ1mf8IYy4HikmpwrYN7ubKqi.jpg	petra	24.3.13.01.08.016
1233	Mohammad Agung Adi Saputra	Male	2006-06-08	2026-05-20 14:00:29	2026-06-04 16:49:42	\N	3510030806060005	12345	Aktif	\N	\N	Banyuwangi	-	Dusun Pekiringan RT/RW 003/001 Desa Sumbersari Kecamatan Srono	085704009480	athlete_photos/mdGqTX03O1SyAqN20ytOxlLQEnuZDcvAtDo44MGL.jpg	SMA PGRI Purwoharjo	22.3.13.16.01.001
1253	EGA PRAMUDYA	Male	2005-07-06	2026-05-24 09:23:41	2026-05-24 13:35:00	\N	1371110607050009	000000000000	Aktif	\N	\N	BALAI GADANG		BALAI GADANG KECAMATAN KOTO TANGAH KOTA PADANG		athlete_photos/LcDLnWyJOFAWxvEDYdvd0L6XNLtYUCMkzvLnjO2l.jpg	DOJO RONGGOLAWE	17.3.04.01.09.056
1287	Mokhamad Ilyas Rosyid 	Male	2008-07-01	2026-05-25 14:54:05	2026-05-25 14:54:05	\N	3517080701080005	0001043962086	Aktif	\N	\N	Jombang	-	DSN BALONGBESUK \nRT/RW:01/04\nDESA:BALONGBESUK\nKECAMATAN:DIWEK	085702418293	athlete_photos/NY4AuLPuZMeU9RvZ7XcQhWuLbCgPXmohURGxJF98.png	DOJO SMADA 	24.3.13.09.01.010
1270	Meysha Aulia Josephine Mahardika	Female	2011-05-12	2026-05-25 10:33:41	2026-05-25 15:44:24	\N	3517044512110002	351704510	Aktif	\N	\N	jombang	-	Dusun nglebak rt 7 rw 4 kecamatan bareng kab jombang	0895428464612	athlete_photos/lXF3LTgn5c6u5228K143NrQyPbfe6JTReSQcAObk.jpg	koramil mojowarno	21.1.13.09.03.022
1325	Anantya Okazzahra Santoso	Female	2004-10-02	2026-05-26 09:45:09	2026-05-28 12:54:09	\N	3578094210040001	0000000000000	Aktif	\N	\N	Surabaya	O	Jl. Semolowaru Elok Blok G/8 Surabaya	0816904281	athlete_photos/dWCfjBpwt3K5z1GdwwF4xxHdTKQanb4WCQIayEex.png	Dojo ITS	12.2.13.01.13.003
1342	Ramadhani Arta Pradipta	Male	2004-11-04	2026-05-26 10:37:25	2026-05-26 10:37:25	\N	3578120111040001	23108625742	Aktif	\N	\N	Surabaya	-	Teluk Aru Utara 65.A	085806619665	athlete_photos/3NrfYyiGzJ0s14M5XLRzeWkgo2umzNT5cHOrsdQs.png	Dojo Perak	12.1.13.01.24.021
1308	Dinda Cintani Nugroho	Female	2013-09-07	2026-05-26 05:05:11	2026-05-28 05:45:32	\N	3578224706130002	0000000000000	Aktif	\N	\N	Sidoarjo	-	Jln. Ketintang Madya No.35 Surabaya Jawa Timur	087853096622	athlete_photos/4hMCgNxNIg3VjvNj6f4pLIVdffMc7P9pbOE4AdS2.jpg	PLN Nusantara Power	23.1.13.01.20.004
1234	Almajesta Azalea Divone 	Female	2014-02-27	2026-05-20 14:04:43	2026-06-08 14:06:49	[]	3506046702140001	0002434289005	Aktif	\N	\N	Kediri	-	Perumahan Griya Shanta Eksekutif blok M 313 RT 10 RW 04 Jatimulyo, Lowokwaru kota Malang 		athlete_photos/ukrFF7kaqgHiQqgRYn8MoHEyJrK2IcJBnZXW0Rlm.jpg	Stiba 	23.1.13.03.06.001
1254	ANTONIUS DWIYANTO AGUN	Male	2006-01-17	2026-05-24 13:29:55	2026-05-24 13:29:55	\N	6408041701060008	000000000000	Aktif	\N	\N	RENTUNG	-	PONG LALE KECAMATAN RUTENG KAB. MANGGARAI		athlete_photos/yGImswBiLX6rWeCb3bCRVICr8EC5mIfktzUbvO8p.png	DOJO RONGGOLAWE TUBAN	
1271	PUTRI SEFINA NILA SARI	Female	2012-11-09	2026-05-25 10:36:26	2026-05-25 15:25:41	\N	3517045109120002	3517045109120002	Aktif	\N	\N	KEDIRI	-	DSN : jenisgelaran RT/RW: 001/001\ndesa : jenisgelaran\nkecamatan : bareng	0895428464612	athlete_photos/kbwhWPrWZXnfJJIMmxJXUlIEhRfGWdODMSxavrhr.jpg	Mojowarno	24.3.13.09.09.011
1288	FARADIBA QOTRUNADA FATQUROCHIM	Female	2007-04-07	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3509194407070003	22050376361	Aktif	\N	\N	JEMBER	O	PERUM BUMI MANGLI PERMAI BLOK DB-06		athlete_photos/aLM2iO1mcwgJZuxxqmuM44y8lKDIYbCObu8rL6ig.jpg	JEMBER	19.2.13.10.03.002
1289	Zulfa Deo Ananda Putra	Male	2004-04-14	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3510051404040013	20015574401	Aktif	\N	\N	BANYUWANGI		Dsn. Stoplas, Rt2 Rw3, Kec. Muncar, Kab. Banyuwangi		athlete_photos/gKDABYHTFd0ORc3ksxq6eFaVmareehTPrHEAEi0a.jpg	JEMBER	23.3.13.10.02.006
1290	ALAYSA NADIA HAQQ	Female	2010-11-10	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3509195011100004	22050376445	Aktif	\N	\N	JEMBER		Jl. Udang Windu no.31		athlete_photos/t6QrzZgWqcbqnhJApN3pLM8S9CSvBvA3U6rdLkU0.jpg	JEMBER	24.1.13.10.03.001
1291	Daffa Rayya Muhammad Athallah	Male	2004-01-31	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3509193101040001	20015574401	Aktif	\N	\N	JEMBER		Jl. Tanjung Lingk. Krajan Jember		athlete_photos/uMH6RLOkfeoaVqek5SwEt5gn64EEoHeJRE6TgosV.jpg	JEMBER	10.1.13.10.02.007
1292	Pramadi wijoyo	Male	2008-12-05	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3509190512080006	24147411664	Aktif	\N	\N	JEMBER		JL.MUJAHIR SUKORAMBI JEMBER		athlete_photos/d0fi0npNtm2W7t1yppFbHYWrj2R82HgwlkVcIc1p.jpg	JEMBER	22.2.13.10.03.005
1293	Muhammad Affan Nur Ihsan	Male	2011-11-13	2026-05-25 15:32:35	2026-05-25 15:32:35	\N	3509191311110002	22050376361	Aktif	\N	\N	JEMBER		perumahan bumi Mangli permai blok DG12A, Kaliwates jember		athlete_photos/LQW74Z9NhAq3IAlV34fLy4zqIbVtr7KnUCJZy8Fd.png	JEMBER	22.2.13.10.03.002
1309	Javier Zaidan Kahar	Male	2012-05-17	2026-05-26 05:05:36	2026-05-28 12:09:44	\N	3578121705120002	000000000000	Aktif	\N	\N	Sidoarjo	-	Jl. Teluk Bone Tengah No.9A	0821-4385-2756	athlete_photos/l6VBwDRFC9Mtvnsy2W6PnT4BIgPdjJDTWJqLlcfE.jpg	Dojo Perak 	23.3.13.01.24.005
1326	FAKHRY NAUFAL PRAMUDYA	Male	2014-01-22	2026-05-26 09:52:18	2026-05-28 14:59:04	\N	3578082201140001	0000000000000	Aktif	\N	\N	Surabaya	O	Jl. Gubeng Kertajaya 8D/7 Surabaya	822-3126-2244	athlete_photos/szEa50ajXpWjpGZ2PTmn5MrrLca0jfeBiqmX8SMa.png	Dojo UWK	21.1.13.01.20.002
1343	Annisa Khansa Jamiah	Female	2010-02-19	2026-05-26 10:39:00	2026-05-28 14:59:04	\N	3578125902100001	0000000000000	Aktif	\N	\N	surabaya	-	Jl. Teluk Nibung Timur 4 no. 48	0881036839070	athlete_photos/4WrduWGjQbCgkbFHFX8JNAK1EpiIWXj2Wkb8ByIF.jpg	perak	18.3.13.01.24.010
1235	Windu Al Ghifari	Male	2013-06-15	2026-05-20 14:06:36	2026-06-04 16:43:52	\N	3510031506130001	123456	Aktif	\N	\N	Banyuwangi	O	Dusun Jatimulyo RT/RW 04/01 Desa Glagahagung Kec. Purwoharjo	0881026621577	athlete_photos/1rwel5lD7XOXwDMl7OvFCTC0GuhmYedGCeFmbj6A.jpg	SMA PGRI Purwoharjo	19.1.13.16.01.033
1190	Anisa Zhafira Ilma	Female	2013-04-02	2026-05-18 09:56:04	2026-05-24 04:51:02	\N	3571024204130001	3571024204130001	Aktif	\N	\N	KEDIRI	-	Jln.balowerti V Kediri 	1	\N	JAYABAYA	23.2.13.02.02.004
1188	Agatha Natania Lituhayu	Female	2010-11-12	2026-05-18 09:53:42	2026-05-24 04:51:54	\N	3571035211100002	3571035211100002	Aktif	\N	\N	KEDIRI	-	Jl. Pamenang Vii No: B-5 Katang Kediri	0	\N	JAYABAYA	25.3.13.02.02.001
1310	Aura Salsabilla	Female	2012-02-23	2026-05-26 05:08:12	2026-05-28 12:54:08	\N	3578186302120002	000000000000	Aktif	\N	\N	Surabaya	-	Lidah wetan RT 0003/RW 003	085717966264	athlete_photos/TsIPwh9BqotrpBVh69DVHVkYqIFo9FfciWpj2IrA.jpg	Dojo UNESA	25.3.13.01.09.002
1272	Umar Alfaruq	Male	2011-04-17	2026-05-25 10:41:05	2026-05-25 16:38:54	\N	3517091704110002	3517045109	Aktif	\N	\N	Jombang	A	Griya kencana Mulya G-4, RT 002/RW 013, Candimulyo, Jombang	085648781950	athlete_photos/9hdYn1iU1xY7a6It90pj2aYBjcwM28HgfJcZffdM.jpg	Al-Fatih Jombang	24.3.13.09.10.013
1156	RADITYA KRESNA WAHYU WISNU SAPUTRA	Male	2011-07-11	2026-05-18 01:57:59	2026-05-18 01:57:59	\N	3506111107770001	3506111107770001	Aktif	\N	\N	KEDIRI	-	Dsn. Tanjung RT 004 RW 003 Tanjung	-	\N	JAYABAYA 	25.3.13.02.02.004
1344	Laverda Javier Tonda	Female	2007-12-05	2026-05-26 10:39:20	2026-05-26 10:39:20	\N	3578034512070003	24073019234	Aktif	\N	\N	Surabaya	-	Pandugo 6-A/10	085102890262	athlete_photos/VeYZU74kqpy7OsqwGgihlXCJjoHX6ROSNGSeLLAm.jpg	Dojo PJB-PLN Nusantara Power	23.2.13.01.20.003
1255	Muhammad Hanif Albaihaqi Handika	Male	2015-05-02	2026-05-24 22:41:36	2026-05-27 22:40:00	\N	3525140205150008	26067758198	Aktif	\N	\N	Gresik	-	Margoroto Rt04/rw01 Ngargosari Kebomas, Kab. Gresik	085731795333	athlete_photos/3sunJnN1kgwptZI9N1jSRpN8XHk3dkOLxeU2UDUZ.jpg	Semen Gresik	23.3.13.06.01.003
1327	BILIZZATI ASYIFA HUMAIRA SALAVIA	Female	2013-08-27	2026-05-26 09:56:01	2026-05-28 14:59:04	\N	3578126708130002	0000000000000	Aktif	\N	\N	Surabaya	B	Jl. Teluk Penanjung No.37	085806989737	athlete_photos/jFvUJIHRE1fcLFrCHHwD7aWFYTyqrFWLWuTtRaJX.jpg	Dojo Perak 	18.3.13.01.24.006
1236	Ananda Epril Prawesti	Female	2016-04-11	2026-05-20 14:15:05	2026-06-04 05:57:09	\N	3510035104160001	1234	Aktif	\N	\N	Banyuwangi	B	Dusun Jatimulyo RT/RW 04/01 Desa Glagahagung Kec. Purwoharjo	08139331571	athlete_photos/PBrQVaky0VzItSK6I54YYZX6gsbL5DFUfI87wQ6Y.jpg	SMA PGRI Purwoharjo	21.2.13.16.01.002
1203	Tiara Anisa Yuniarto	Female	2012-09-21	2026-05-18 14:14:20	2026-06-08 16:00:36	[]	3573015109120004	0001537952185	Aktif	\N	\N	Kota Malang 	-	Jln. Hamid Rusdi K-153\nRT 001 RW  006 Kesatrian Blimbing Kota Malang 		athlete_photos/nZSEdxgRaM2c5NcCAE3d062B68uWeIiocinHmEao.jpg	Stiba 	23.3.13.03.06.001
1201	Rachel Bertha Kenshi Nauli Simbolon 	Female	2012-06-02	2026-05-18 12:23:28	2026-06-10 05:30:14	[]	3573054206120005	0001336940212	Aktif	\N	\N	Malang	O	Perumahan Griya Shanta Eksekutif blok M 366 RT 10 RW 04 Jatimulyo, Lowowaru Kota Malang 		athlete_photos/KQpoqpyI6B0bjhqMw2R9p9EDzmKuDvFVgHpum4ZM.jpg	Stiba 	15.3.13.03.06.004
1166	Icha Sazira Fitriani 	Female	2009-10-02	2026-05-18 09:28:16	2026-05-18 09:28:16	\N	3514114210090003	24154264519	Aktif	\N	\N	Sdioarjo	B+	jalan lukman hakim rt 01 rw 01 lingkungan jogonalan kecamatan Pandaan kabupaten Pasuruan 		athlete_photos/Fi8OaC1WWMVDX36DCNWYCRlfuMquQocuWSPYsq4N.jpg	SMP MAARIF PANDAAN	22.3.13.21.01.004
1167	Kamila Farha Ilmi	Female	2010-06-16	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3514115606100002	24154264493	Aktif	\N	\N	Pasuruan		Ling.Dukuh RT/RW 002/006 kutorejo kecamatan pandaan kabupaten pasuruan		athlete_photos/oh1mnDv41DN2NqljY9WYPvjbPQkMYP0Q3wVEKYyg.jpg	SMP MAARIF PANDAAN	22.3.13.21.01.008
1168	Devina Wahyu Ramadhani 	Female	2009-08-29	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3514116908090002	24154264501	Aktif	\N	\N	Pasuruan 		dusun jonggan RT.02 RW.07 desa durensewu kecamatan Pandaan 		athlete_photos/S0CdbVX4n6tusPELSvan8V8ez2Ah5Y5hq2oH55mp.jpg	SMP MAARIF PANDAAN	22.3.13.21.01.001
1169	Nawrah Filzah Cameela	Female	2006-03-30	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3514117003060001	0000112686028	Aktif	\N	\N	Pasuruan	O	Ling. Plumbon RT 005 / RW 005		athlete_photos/Nb6SMsuYHCoYqldpX3FafFFJhCWvosCpxfTsIGVg.jpg	SMP MAARIF PANDAAN	18.3.13.02.07.013
1170	Amanda Aisyah Ramadanni 	Female	2004-10-17	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3514115710040003	0002051713067	Aktif	\N	\N	Malang	B	Dusun Kulak RT 001/ RW 012 Desa Nogosari Kecamatan Pandaan Kabupaten Pasuruan		athlete_photos/KD5yBrDe4cCPWHeHK17nWsAC8xiBHYplNlVHzhqF.jpg	SMP MAARIF PANDAAN	19.2.13.21.01.001
1171	Dzurotul Aini	Female	2005-11-13	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3514105311050001	23113032157	Aktif	\N	\N	Pasuruan		usun Bulu krajan, desa Bulukandang, kecamatan Prigen, kabupaten Pasuruan 		athlete_photos/1UKT6z9LS8qbAHP9aVdIO9SjaJiMSTsswS15kNkh.jpg	SMP MAARIF PANDAAN 	18.3.13.04.07.005
1172	Prayogie Al Dino	Male	2003-01-17	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3578131701030003	21061418162	Aktif	\N	\N	Surabaya		 jln. apel III/529, kiduldalem, bangil, kab. pasuruan		athlete_photos/zd7h2cQWL2FiCgBVeCPqGBjDNQdXhQI172TMCf9o.jpg	SMP MAARIF PANDAAN	19.2.13.21.01.006
1173	Tegar Pribadi Putra	Male	2004-10-07	2026-05-18 09:28:17	2026-05-18 09:28:17	\N	3603300710040002	26029363665	Aktif	\N	\N	Tangerang	O	Dsn. Tambakrejo RT.03/RW.02, Ds. Tanjung arum, Kec. Sukorejo		athlete_photos/2N0LTSqHTHWwDgo1TGwkdogKoy1pP7P8T9hIrOzK.jpg	SMP MAARIF PANDAAN	18.3.13.04.07.017
1202	Wayne Matthew Axel Parulian Simbolon 	Male	2015-08-26	2026-05-18 13:07:22	2026-06-10 05:27:41	[]	3573052608150003	0002727892743	Aktif	\N	\N	Kota Malang 	-	Perumahan Griya Shanta Eksekutif blok M 366 RT 10 RW 04 Jatimulyo Lowokwaru, Kota Malang 		athlete_photos/0Owjx8KVGwjZznelcnnoHK8EGkZVqUPehNVRWtXi.jpg	Stiba	22.3.13.03.06.001
1175	FAWWAZ AGRIYA PUTRA	Male	2011-01-18	2026-05-18 09:34:28	2026-05-18 10:25:37	\N	3506061801110002	3506061801110002	Aktif	\N	\N	Kediri	-	JLN PAHLAWAN KUSUMA BANGSA NO 114	-	\N	Jayabaya	23.1.13.02.02.002
1198	M. Revaldo Alfino Pratama	Male	2009-04-22	2026-05-18 10:35:24	2026-05-18 10:35:24	\N	3506042204090002	3506042204090002	Aktif	\N	\N	KEDIRI	-	JLN PATIMURA PAGORA KOTA KEDIRI	A	\N	JAYABAYA	19.2.13.02.02.005
1199	Muhammad Wenpy Abdi Vannorin	Male	2009-04-06	2026-05-18 10:35:24	2026-05-18 10:35:24	\N	3571010709170006	3571010709170006	Aktif	\N	\N	KEDIRI	-	JLN KADEMANG MOJOROTO KOTA KEDIRI	@	\N	JAYABAYA	19.3.13.02.02.005
1200	M Qaishar Mirza Athariz	Male	2013-05-01	2026-05-18 10:39:46	2026-05-18 10:39:46	\N	3506140105130003	3506140105130003	Aktif	\N	\N	Kediri	-	JLN PAHLAWAN KUSUMA BANGSA NO 114		\N	Jayabaya	19.1.13.02.02.002
1204	Arga Kusuma R.S.M.Y	Male	2009-07-30	2026-05-18 16:26:56	2026-05-18 17:22:54	\N	3517033007090002	212121454354354545	Aktif	\N	\N	Jombang	O	Dsn. Kertorejo, Ds. Kertorejo, Kec. Ngoro	085748155515	athlete_photos/J7nvCviEBFdkpiNt7umv2IHXH1TeVTw5p7ywFEYY.jpg	Koramil Mojowarno 	22.3.13.09.03.009
1210	ANDHIKA RIDHO MUTTAQIN 	Male	2008-03-06	2026-05-19 10:00:07	2026-05-20 14:39:30	\N	3526010603080001	3526010603080001	Aktif	\N	\N	BANGKALAN	A	JL.RA KARTINI RT/RW 003/003 BANGKALAN 	085815652681	athlete_photos/fS3zkvuf3PmMxVsWTj3tghupDptTGXVkQydEtwi8.jpg	BANGKALAN B	24.3.13.13.03.002
1237	Rendi Setyawan	Male	1997-06-27	2026-05-20 14:22:46	2026-06-04 16:46:00	\N	3510062706970002	123456	Aktif	\N	\N	Banyuwangi	-	Dusun Tanjungrejo RT/RW 02/03 Desa Sembulung Kec. Cluring	085213037460	athlete_photos/o5N0CpbPXNAd0FSEc60UrbvaTH5SxZY1ZPvBTVL1.jpg	SMA PGRI Purwoharjo	14.1.13.16.01.015
1207	DESVITA EKA PUTRI	Female	2009-11-24	2026-05-19 09:11:57	2026-05-20 14:39:30	\N	7371126411090003	7371126411090003	Aktif	\N	\N	MAKASSAR	A	PERUM GRAHA MENTARI BLOK C1/21	082131726661	athlete_photos/2vL0wxSfydPUvKeeiTvHUXqmpNW32lq7YZXEscMl.jpg	BANGKALAN B	25.2.13.13.03.002
1208	ZUHRUFFINE CALISTA PUTRI INDI 	Female	2009-12-04	2026-05-19 09:38:25	2026-05-20 14:39:30	\N	3375034412090001	3375034412090001	Aktif	\N	\N	PEKALONGAN	A	JL KINIBALU RT/RW 001/001 MLAJAH BANGKALAN 	085850833250	athlete_photos/3PZlMN4otzEzQ52vniC9L03MMSkHKlreaIOTdwTu.jpg	BANGKALAN B	25.2.13.13.03.001
1209	MUHARROR MADANI 	Male	2010-03-23	2026-05-19 09:49:28	2026-05-20 14:39:30	\N	3526182303100001	3526182303100001	Aktif	\N	\N	BANGKALAN	O	DSN SEDDANG  KEC GALIS KAB BANGKALAN 	082337180997	athlete_photos/i08R5cHopJhjadIxDEY8ckps7jRI4BfM9GgbYAq0.jpg	BANGKALAN B	25.2.13.13.03.007
1273	Ochie Dila Aurelia 	Female	2010-05-07	2026-05-25 10:43:56	2026-05-25 10:43:56	\N	3517194507100003	3517194507100003	Aktif	\N	\N	Jombang	-	JL. Sentot Prawirodirjo GG IV	085708855774	athlete_photos/W7tiby5ckmIZY39DN8oPtDVF3B8Az58HdX9TPFn2.png	SMPN 1 Jombang	23.2.13.09.07.009
1206	AIRA SYIFA RISTA	Female	2009-05-13	2026-05-18 17:37:33	2026-05-25 15:08:21	\N	3517025305090001	0000727907253	Aktif	\N	\N	JOMBANG	-	DSN :BUMIARJO \nRT/RW:002/006\nDESA:GUDO\nKECAMATAN:GUDO 	085785810504	athlete_photos/A08vixp6bxuKbUVcfZFde7nhc0KbtsByoRn3Jt2l.jpg	DOJO GUDO 	23.2.13.09.04.002
1328	Muhammad Ilham	Male	2013-12-28	2026-05-26 09:58:55	2026-05-26 09:58:55	\N	3515082812130002	24073019416	Aktif	\N	\N	Surabaya	O	Taman Indah V/38 	081232080519	athlete_photos/JWS4QhbetQXuQXsqlnU6fgIAIjO8Y1wWcLbiKw4S.jpg	Dojo UWK	23.2.13.01.20.002
1256	Muhammad Aidan Nurfatah	Male	2014-02-18	2026-05-24 22:44:24	2026-05-27 22:40:00	\N	3525161802140001	22145606749	Aktif	\N	\N	Gresik	-	Jl. Mh Tamrin No.12, Kab. Gresik	081231273381	athlete_photos/tjfHYvbnUItFbhvNeSWZzjZwpMr0spranPeP44Ur.jpg	Semen Gresik	24.1.13.06.01.002
1345	R. Muhammad Rifqi Ainurrafa	Male	2014-11-24	2026-05-27 03:39:31	2026-05-28 05:45:32	\N	3509202411140003	0000000000000	Aktif	\N	\N	Jember	B	Jalan Taman Indah VI no 23, Sepanjang, Taman, Sidoarjo	08123480480	athlete_photos/cBYF2EbTzC0J2MzVNeDSxZft9LomzJFLoFka4gcF.png	UWK	21.3.18.01.09.002
1311	Naisya Ayatul Khusna 	Female	2012-04-22	2026-05-26 05:08:50	2026-05-28 05:45:32	\N	3578186204120001	0000000000000	Aktif	\N	\N	Surabaya	-	lidah wetan gang 8B no 56 Surabaya 	088217249098	athlete_photos/xUVy4ptz6saaeI2vBCebiPH8puyIEjyei3Jux5VD.jpg	Unesa	22.3.13.01.09.004
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
smart-perkemi-cache-welcome_stats	a:3:{s:7:"peserta";i:160;s:5:"nomor";i:52;s:9:"kontingen";i:15;}	1781276532
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, type, gender, age_group, weight_class, created_at, updated_at, match_type) FROM stdin;
1	Embu Tandoku Kyu 6 (Eksibisi)	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
2	Embu Pasangan Kyu 6 (Eksibisi)	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
3	Embu Tandoku Kyu 5-4	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
4	Embu Tandoku Kyu 3	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
5	Embu Tandoku Kyu 2	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
6	Embu Tandoku Kyu 1	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
7	Embu Pasangan Kyu 5-4	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
8	Embu Pasangan Kyu 3	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
9	Embu Pasangan Kyu 2	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
10	Embu Pasangan Kyu 1	Kata	Male	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
11	Embu Tandoku Kyu 6 (Eksibisi)	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
12	Embu Pasangan Kyu 6 (Eksibisi)	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
13	Embu Tandoku Kyu 5-4	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
14	Embu Tandoku Kyu 3	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
15	Embu Tandoku Kyu 2	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
16	Embu Tandoku Kyu 1	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
17	Embu Pasangan Kyu 5-4	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
18	Embu Pasangan Kyu 3	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
19	Embu Pasangan Kyu 2	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
20	Embu Pasangan Kyu 1	Kata	Female	Pemula	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
21	Embu Tandoku Kyu 5-4	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
22	Embu Tandoku Kyu 3	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
23	Embu Tandoku Kyu 2	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
24	Embu Tandoku Kyu 1	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
25	Embu Pasangan Kyu 5-4	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
26	Embu Pasangan Kyu 3	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
27	Embu Pasangan Kyu 2	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
28	Embu Pasangan Kyu 1	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
29	Embu Beregu	Kata	Male	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
30	Embu Tandoku Kyu 5-4	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
31	Embu Tandoku Kyu 3	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
32	Embu Tandoku Kyu 2	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
33	Embu Tandoku Kyu 1	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
34	Embu Pasangan Kyu 5-4	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
35	Embu Pasangan Kyu 3	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
36	Embu Pasangan Kyu 2	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
37	Embu Pasangan Kyu 1	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
38	Embu Beregu	Kata	Female	Remaja A	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
39	Embu Tandoku Kyu 5-4	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
40	Embu Tandoku Kyu 3	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
41	Embu Tandoku Kyu 2	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
42	Embu Tandoku Kyu 1	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
43	Embu Pasangan Kyu 5-4	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
44	Embu Pasangan Kyu 3	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
45	Embu Pasangan Kyu 2	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
46	Embu Pasangan Kyu 1	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
47	Embu Beregu	Kata	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
48	Randori 45Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
49	Randori 50Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
50	Randori 55Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
51	Randori 60Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
52	Randori 65Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
53	Randori 70Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
54	Randori >70Kg	Kumite	Male	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
55	Embu Tandoku Kyu 5-4	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
56	Embu Tandoku Kyu 3	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
57	Embu Tandoku Kyu 2	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
58	Embu Tandoku Kyu 1	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
59	Embu Pasangan Kyu 5-4	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
60	Embu Pasangan Kyu 3	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
61	Embu Pasangan Kyu 2	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
62	Embu Pasangan Kyu 1	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
63	Embu Beregu	Kata	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
64	Randori 45Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
65	Randori 50Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
66	Randori 55Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
67	Randori 60Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
68	Randori 65Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
69	Randori 70Kg	Kumite	Female	Remaja B	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
70	Embu Tandoku Kyu 3	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
71	Embu Tandoku Kyu 2	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
72	Embu Tandoku Kyu 1	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
73	Embu Tandoku Yudansa	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
74	Embu Pasangan Kyu 3	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
75	Embu Pasangan Kyu 2	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
76	Embu Pasangan Kyu 1	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
77	Embu Beregu	Kata	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
78	Randori 50Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
79	Randori 55Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
80	Randori 60Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
81	Randori 65Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
82	Randori 70Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
83	Randori >70Kg	Kumite	Male	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
84	Embu Tandoku Kyu 3	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
85	Embu Tandoku Kyu 2	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
86	Embu Tandoku Kyu 1	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
87	Embu Tandoku Yudansa	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
88	Embu Pasangan Kyu 3	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
89	Embu Pasangan Kyu 2	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
90	Embu Pasangan Kyu 1	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
91	Embu Beregu	Kata	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
92	Randori 50Kg	Kumite	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
93	Randori 55Kg	Kumite	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
94	Randori 60Kg	Kumite	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
95	Randori 65Kg	Kumite	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
96	Randori 70Kg	Kumite	Female	Dewasa	\N	2026-05-16 15:47:24	2026-05-16 15:47:24	Kempo
\.


--
-- Data for Name: contingents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contingents (id, name, leader_name, leader_phone, email, address, created_at, updated_at, kab_kota, verified_at, verified_by, user_id) FROM stdin;
16	KOTA KEDIRI	 Cecep Sunariya Ketua	085790399330	cecepsunariya@gmail.com	Jln. Veteran No 8 Mojoroto Kota Kediri	2026-05-17 07:40:35	2026-05-17 07:40:35	KOTA KEDIRI	\N	\N	82
17	Kabupaten Pasuruan	Ahmad Muqaffi Alaq	081553054313	lia555yul@gmail.com	Jl. Raya A. Yani No. 92, Pandaan, Jogonalan, Jogo Nalain, Petungasri, Kec. Pandaan, Pasuruan, Jawa Timur 67156	2026-05-18 04:39:50	2026-05-18 04:39:50	Kabupaten Pasuruan	\N	\N	83
18	BANGKALAN A	MANAGER 	08135367233	astritrabeca21@gmail.com	jln. trunojoyo no 16, pejagan , kec : bangkalan, kab : bangkalan, 	2026-05-18 05:38:36	2026-05-18 05:38:36	KABUPATEN BANGKALAN	\N	\N	84
22	JOMBANG	RIZKA ADHI PRABAWA	081216801237	acapoeirista@yahoo.com	JL Jayapura No 014 Ngoro Jombang	2026-05-18 15:07:06	2026-05-18 15:07:06	KABUPATEN JOMBANG	\N	\N	87
25	BANGKALAN B	ISNAINI RAHMAN 	082229223414	isnainirahman93@gmail.com	Graha trunojoyo f4 burneh bangkalan 	2026-05-19 08:27:20	2026-05-19 08:27:20	KAB BANGKALAN 	\N	\N	90
24	Banyuwangi	Santoso	085336059460	nurimama948@gmail.com	Jl. Jajag No.7 Kradenan Purwoharjo banyuwangi	2026-05-19 03:00:18	2026-05-20 14:42:22	Kabupaten Banyuwangi	\N	\N	89
31	Kontingen Jember	Navra Najma Alfurrohmah 	082141812154	kempo.universitas.jember@gmail.com	jln. udang windu no.31 kel. Mangli, kec. Kaliwates, kab. Jember	2026-05-20 14:49:09	2026-05-20 14:49:09	Jember	\N	\N	96
33	TUBAN	APRILIA HANA PRATIWI	081335338336	apriliahana10pr@gmail.com	Ds. Prunggahan Wetan 	2026-05-22 11:20:24	2026-05-22 11:20:24	KABUPATEN	\N	\N	97
34	Gresik	Afrizal Hardiansyah	087789552607	perkemigresik@gmail.com	Perum Regency Mayjend Sungkono Blok F-12 Kedanyang Gresik	2026-05-24 22:34:36	2026-05-24 22:34:36	Kabupaten 	\N	\N	141
30	Surabaya D	Manager Surabaya D	082264444221	surabaya.d@smart-perkemi.id	Jl. Jambangan Baru no 3	2026-05-20 04:40:03	2026-05-26 04:38:33	Surabaya	\N	\N	95
27	Surabaya A	Manager Surabaya A	081938877507	surabaya.a@smart-perkemi.id	Jl. Jambangan Baru no 3	2026-05-20 04:33:43	2026-05-26 04:40:37	Surabaya	\N	\N	92
28	Surabaya B	Manager Surabaya B	081231375198	surabaya.b@smart-perkemi.id	Jl. Jambangan Baru no 3	2026-05-20 04:37:32	2026-05-26 04:42:08	Surabaya	\N	\N	93
29	Surabaya C	Manager Surabaya C	087701918534	surabaya.c@smart-perkemi.id	Jl. Jambangan Baru no 3	2026-05-20 04:38:41	2026-05-26 04:43:21	Surabaya	\N	\N	94
35	Sidoarjo	I Ketut Pramantara	082141470867	perkemisidoarjo@gmail.com	Sidoarjo	2026-06-03 04:54:40	2026-06-03 04:54:40	Sidoarjo	\N	\N	143
40	Kota  Malang 3 (STIBA-GSE)	S. Simbolon	081931887871	leonardocastello26@gmail.com	Perun Griya Shanta Eksekutif M366 Jatimulyo-Lowokwaru	2026-06-06 13:27:58	2026-06-08 14:41:44	Kota Malang	\N	\N	86
\.


--
-- Data for Name: courts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.courts (id, name, "order", created_at, updated_at, active_match_id, active_registration_id, active_bracket_node, active_drawing_id) FROM stdin;
4	Court 4	0	2026-06-11 07:13:03	2026-06-11 17:30:52	\N	\N	\N	\N
2	Court 2	2	2026-05-16 15:47:43	2026-06-11 23:53:08	\N	\N	\N	\N
1	Court 1	1	2026-05-16 15:47:43	2026-06-12 14:41:43	\N	\N	\N	\N
3	Court 3	3	2026-05-16 15:47:43	2026-06-11 13:54:47	\N	\N	\N	\N
\.


--
-- Data for Name: drawing_match_numbers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.drawing_match_numbers (id, match_number_id, registration_id, pool_id, court_id, round, sequence_number, draft_type, metadata, created_at, updated_at, schedule_date, rundown_id, session_time_id) FROM stdin;
16776	1	61	5	4	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","sheila wardatul jannah","Inggar Cahya Wahyu Putri ","dyah nur islami putri nisyam"]},"match_id_code":"P-E-TK-01-X","start_time":"07:30","end_time":"07:40","duration":10,"athlete_name":"MUHAMMAD RAKA GARADHIKA SULFAN","athlete_ids":{"1":1283},"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16777	1	69	5	4	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","sheila wardatul jannah","Inggar Cahya Wahyu Putri ","dyah nur islami putri nisyam"]},"match_id_code":"P-E-TK-02-X","start_time":"07:40","end_time":"07:50","duration":10,"athlete_name":"As'ad Tegar Ballan Firmansyah","athlete_ids":[1299],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16778	1	70	5	4	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","sheila wardatul jannah","Inggar Cahya Wahyu Putri ","dyah nur islami putri nisyam"]},"match_id_code":"P-E-TK-03-X","start_time":"07:50","end_time":"08:00","duration":10,"athlete_name":"Mikael Eko Grestrianto Aji","athlete_ids":[1303],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16779	1	71	5	4	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","sheila wardatul jannah","Inggar Cahya Wahyu Putri ","dyah nur islami putri nisyam"]},"match_id_code":"P-E-TK-04-X","start_time":"08:00","end_time":"08:10","duration":10,"athlete_name":"FAKHRY NAUFAL PRAMUDYA","athlete_ids":[1326],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16780	1	61	5	4	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","sheila wardatul jannah","Inggar Cahya Wahyu Putri ","dyah nur islami putri nisyam"]},"match_id_code":"P-E-TK-05-X","start_time":"08:10","end_time":"08:20","duration":10,"athlete_name":"AKHMAD WAKHID IBRAHIM","athlete_ids":[1281],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16781	2	61	5	1	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-01-X","start_time":"07:30","end_time":"07:40","duration":10,"athlete_name":"Meysa Ayu Riswanti","athlete_ids":[1276],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16782	2	62	5	1	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-02-X","start_time":"07:40","end_time":"07:50","duration":10,"athlete_name":"NISRINA KHAYLA ZHAFIRAH","athlete_ids":[1228],"contingent":"BANGKALAN A","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16783	2	73	5	1	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-03-X","start_time":"07:50","end_time":"08:00","duration":10,"athlete_name":"Ananda Epril Prawesti","athlete_ids":[1236],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16784	2	71	5	1	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-04-X","start_time":"08:00","end_time":"08:10","duration":10,"athlete_name":"BILIZZATI ASYIFA HUMAIRA SALAVIA","athlete_ids":[1327],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16785	2	69	5	1	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-05-X","start_time":"08:10","end_time":"08:20","duration":10,"athlete_name":"Beby Naura Ozyllia Janeeta ","athlete_ids":[1302],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16786	2	65	5	1	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Velina","aisyah nurillah","ayumi trinarita wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"P-E-TK-06-X","start_time":"08:20","end_time":"08:30","duration":10,"athlete_name":"SALWA AGUSTIN ASSYIFA'","athlete_ids":[1250],"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:32	2026-06-12 14:20:32	2026-06-15	2	1
16787	3	71	5	2	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","sagita suryani anwar","revan catur cakti putra jatayu","alyssa rahma pradipta"]},"match_id_code":"P-E-PS-01-X","start_time":"07:30","end_time":"07:40","duration":10,"athlete_name":"BILIZZATI ASYIFA HUMAIRA SALAVIA, Muhammad Ilham","athlete_ids":[1327,1328],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16788	3	61	5	2	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","sagita suryani anwar","revan catur cakti putra jatayu","alyssa rahma pradipta"]},"match_id_code":"P-E-PS-02-X","start_time":"07:40","end_time":"07:50","duration":10,"athlete_name":"Meysa Ayu Riswanti, MUHAMMAD RAKA GARADHIKA SULFAN","athlete_ids":[1283,1276],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16789	3	68	5	2	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","sagita suryani anwar","revan catur cakti putra jatayu","alyssa rahma pradipta"]},"match_id_code":"P-E-PS-03-X","start_time":"07:50","end_time":"08:00","duration":10,"athlete_name":"Lucky Adhyastha Alviando, Maria Gunthildis Betan Wuwur","athlete_ids":[1295,1296],"contingent":"Surabaya D","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16790	3	69	5	2	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","sagita suryani anwar","revan catur cakti putra jatayu","alyssa rahma pradipta"]},"match_id_code":"P-E-PS-04-X","start_time":"08:00","end_time":"08:10","duration":10,"athlete_name":"Beby Naura Ozyllia Janeeta , R. Muhammad Rifqi Ainurrafa","athlete_ids":[1302,1345],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16887	3	\N	\N	4	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-07-X","start_time":"13:10","end_time":"13:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16791	4	69	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","saddam bintang hermawan","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"P-E-PS-01-X","start_time":"07:30","end_time":"07:40","duration":10,"athlete_name":"As'ad Tegar Ballan Firmansyah, Muhammad Hanif Abrar Rivandra","athlete_ids":[1299,1301],"contingent":"Surabaya C","merge_id":1}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16792	4	71	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","saddam bintang hermawan","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"P-E-PS-02-X","start_time":"07:40","end_time":"07:50","duration":10,"athlete_name":"FAKHRY NAUFAL PRAMUDYA, Muhammad Ilham","athlete_ids":[1326,1328],"contingent":"Surabaya A","merge_id":1}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16793	5	70	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","saddam bintang hermawan","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"P-E-PS-03-X","start_time":"07:50","end_time":"08:00","duration":10,"athlete_name":"Alyssa Putri Djumaidah, Aisyah Putri Jamiah","athlete_ids":[1305,1307],"contingent":"Surabaya B","merge_id":1}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16794	4	68	5	3	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","saddam bintang hermawan","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"P-E-PS-04-X","start_time":"08:00","end_time":"08:10","duration":10,"athlete_name":"R. Ahmad Arvie Firdaus, Kaindra Aldan Limantoro","athlete_ids":[1346,1297],"contingent":"Surabaya D","merge_id":1}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16795	4	61	5	3	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","saddam bintang hermawan","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"P-E-PS-05-X","start_time":"08:10","end_time":"08:20","duration":10,"athlete_name":"AKHMAD WAKHID IBRAHIM, MUHAMMAD RAKA GARADHIKA SULFAN","athlete_ids":[1281,1283],"contingent":"JOMBANG","merge_id":1}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16796	7	70	5	2	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-01-X","start_time":"08:10","end_time":"08:20","duration":10,"athlete_name":"Aura Salsabilla","athlete_ids":[1310],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16797	7	65	5	2	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-02-X","start_time":"08:20","end_time":"08:30","duration":10,"athlete_name":"SABRINA NASIKHATUL HUSNA","athlete_ids":[1245],"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16798	7	69	5	2	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-03-X","start_time":"08:30","end_time":"08:40","duration":10,"athlete_name":"Naisya Ayatul Khusna ","athlete_ids":[1311],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16799	7	61	5	2	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-04-X","start_time":"08:40","end_time":"08:50","duration":10,"athlete_name":"PUTRI SEFINA NILA SARI","athlete_ids":[1271],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16800	7	71	5	2	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-05-X","start_time":"08:50","end_time":"09:00","duration":10,"athlete_name":"Kirana Bellvania Ramadhani ","athlete_ids":[1330],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16801	7	65	5	2	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","Alvita Debby Marcella","dyah nur islami putri nisyam","Agus Ifan  Riyadi"]},"match_id_code":"RA-E-TK-06-X","start_time":"09:00","end_time":"09:10","duration":10,"athlete_name":"AURORA FELICIA HERMA PUTRI","athlete_ids":{"1":1248},"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16802	9	71	5	4	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-01-X","start_time":"08:20","end_time":"08:30","duration":10,"athlete_name":"Kirana Bellvania Ramadhani , Anabel Sarah Maheswari","athlete_ids":[1330,1348],"contingent":"Surabaya A","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16803	10	69	5	4	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-02-X","start_time":"08:30","end_time":"08:40","duration":10,"athlete_name":"Intan Renia Anggraini, Andrean dwi rekso jovani","athlete_ids":[1312,1314],"contingent":"Surabaya C","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16804	10	65	5	4	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-03-X","start_time":"08:40","end_time":"08:50","duration":10,"athlete_name":"TAHFAQUL MANAN RAMADHANI, SABRINA NASIKHATUL HUSNA","athlete_ids":[1244,1245],"contingent":"TUBAN","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16805	8	71	5	4	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-04-X","start_time":"08:50","end_time":"09:00","duration":10,"athlete_name":"ADHIYASTHA YAHYA MAULANA, Raung Laksono","athlete_ids":[1329,1332],"contingent":"Surabaya A","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16806	10	71	5	4	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-05-X","start_time":"09:00","end_time":"09:10","duration":10,"athlete_name":"Anabel Sarah Maheswari, Raung Laksono","athlete_ids":[1332,1348],"contingent":"Surabaya A","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16807	9	70	5	4	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-06-X","start_time":"09:10","end_time":"09:20","duration":10,"athlete_name":"Athalia Betaria Samosir, Aura Salsabilla","athlete_ids":[1310,1313],"contingent":"Surabaya B","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16808	9	65	5	4	Penyisihan	7	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-07-X","start_time":"09:20","end_time":"09:30","duration":10,"athlete_name":"KAYSHA CAHYANI SYAM SORAYA, SHAFA HERMAWAN","athlete_ids":[1246,1247],"contingent":"TUBAN","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16809	8	65	5	4	Penyisihan	8	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-08-X","start_time":"09:30","end_time":"09:40","duration":10,"athlete_name":"LUTFI RIZKI WAHYU UTAMA, TAHFAQUL MANAN RAMADHANI","athlete_ids":[1243,1244],"contingent":"TUBAN","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16810	9	69	5	4	Penyisihan	9	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","sheila wardatul jannah","aisyah nurillah"]},"match_id_code":"RA-E-PS-09-X","start_time":"09:40","end_time":"09:50","duration":10,"athlete_name":"Dinda Cintani Nugroho, Naisya Ayatul Khusna ","athlete_ids":[1308,1311],"contingent":"Surabaya C","merge_id":2}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16811	13	71	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["dyah nur islami putri nisyam","nadia septia wardani","nadifa amira","ayumi trinarita wardani"]},"match_id_code":"RA-E-BG-01-X","start_time":"08:20","end_time":"08:30","duration":10,"athlete_name":"ADHIYASTHA YAHYA MAULANA, Kirana Bellvania Ramadhani , Anabel Sarah Maheswari, Raung Laksono","athlete_ids":[1329,1330,1332,1348],"contingent":"Surabaya A","merge_id":3}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16812	12	69	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["dyah nur islami putri nisyam","nadia septia wardani","nadifa amira","ayumi trinarita wardani"]},"match_id_code":"RA-E-BG-02-X","start_time":"08:30","end_time":"08:40","duration":10,"athlete_name":"Farzana Delisha Wicaksono , Kaysha Aretha Radwa Almeiraza, Dinda Cintani Nugroho, Naisya Ayatul Khusna ","athlete_ids":[1304,1306,1308,1311],"contingent":"Surabaya C","merge_id":3}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16813	12	65	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["dyah nur islami putri nisyam","nadia septia wardani","nadifa amira","ayumi trinarita wardani"]},"match_id_code":"RA-E-BG-03-X","start_time":"08:40","end_time":"08:50","duration":10,"athlete_name":"SABRINA NASIKHATUL HUSNA, KAYSHA CAHYANI SYAM SORAYA, SHAFA HERMAWAN, AURORA FELICIA HERMA PUTRI","athlete_ids":[1245,1246,1247,1248],"contingent":"TUBAN","merge_id":3}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16814	13	65	5	3	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["dyah nur islami putri nisyam","nadia septia wardani","nadifa amira","ayumi trinarita wardani"]},"match_id_code":"RA-E-BG-04-X","start_time":"08:50","end_time":"09:00","duration":10,"athlete_name":"LUTFI RIZKI WAHYU UTAMA, TAHFAQUL MANAN RAMADHANI, KAYSHA CAHYANI SYAM SORAYA, SHAFA HERMAWAN","athlete_ids":[1243,1244,1246,1247],"contingent":"TUBAN","merge_id":3}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16815	6	65	5	1	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-01-X","start_time":"08:30","end_time":"08:40","duration":10,"athlete_name":"RAFAEL MILANO ARDITIA","athlete_ids":{"1":1249},"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16816	6	70	5	1	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-02-X","start_time":"08:40","end_time":"08:50","duration":10,"athlete_name":"Javier Zaidan Kahar","athlete_ids":[1309],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16817	6	61	5	1	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-03-X","start_time":"08:50","end_time":"09:00","duration":10,"athlete_name":"Titus Althea Marhaendra","athlete_ids":[1279],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16818	6	73	5	1	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-04-X","start_time":"09:00","end_time":"09:10","duration":10,"athlete_name":"Windu Al Ghifari","athlete_ids":[1235],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16819	6	62	5	1	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-05-X","start_time":"09:10","end_time":"09:20","duration":10,"athlete_name":"MOHAMAD RIZQI WIRA PUTRA","athlete_ids":[1218],"contingent":"BANGKALAN A","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16820	6	71	5	1	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-06-X","start_time":"09:20","end_time":"09:30","duration":10,"athlete_name":"ADHIYASTHA YAHYA MAULANA","athlete_ids":[1329],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16821	6	65	5	1	Penyisihan	7	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","ayumi trinarita wardani","Raya Silmina","aisyah nurillah"]},"match_id_code":"RA-E-XX-07-X","start_time":"09:30","end_time":"09:40","duration":10,"athlete_name":"LUTFI RIZKI WAHYU UTAMA","athlete_ids":[1243],"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16822	31	70	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sagita suryani anwar","ayumi trinarita wardani","nadia septia wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-E-PS-01-X","start_time":"09:00","end_time":"09:10","duration":10,"athlete_name":"Fariz Fernando, Bilvina Aqila Saumifathiyah","athlete_ids":[1320,1321],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16823	31	69	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sagita suryani anwar","ayumi trinarita wardani","nadia septia wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-E-PS-02-X","start_time":"09:10","end_time":"09:20","duration":10,"athlete_name":"Bisma Ali Kumara, Farah azalia salshabila ilham","athlete_ids":[1316,1349],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16824	31	61	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sagita suryani anwar","ayumi trinarita wardani","nadia septia wardani","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-E-PS-03-X","start_time":"09:20","end_time":"09:30","duration":10,"athlete_name":"MUHAMMAD SAMUDERA GENETIKA SULFAN, Ochie Dila Aurelia ","athlete_ids":[1282,1273],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16825	34	61	5	2	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["nadia septia wardani","Agus Ifan  Riyadi","sheila wardatul jannah","Alvita Debby Marcella"]},"match_id_code":"RB-E-BG-01-X","start_time":"09:10","end_time":"09:20","duration":10,"athlete_name":"Ganendra Waradana Prayuda, Daniswara Gaozan, ARIFAH DWI ARDIYANTI, AIRA SYIFA RISTA","athlete_ids":[1206,1280,1277,1269],"contingent":"JOMBANG","merge_id":4}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16826	33	61	5	2	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["nadia septia wardani","Agus Ifan  Riyadi","sheila wardatul jannah","Alvita Debby Marcella"]},"match_id_code":"RB-E-BG-02-X","start_time":"09:20","end_time":"09:30","duration":10,"athlete_name":"APRILLIA EKA NURAINI, AZZAHRA ADINDA PUTRI , ARIFAH DWI ARDIYANTI, AIRA SYIFA RISTA","athlete_ids":[1206,1275,1285,1269],"contingent":"JOMBANG","merge_id":4}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16827	32	61	5	2	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["nadia septia wardani","Agus Ifan  Riyadi","sheila wardatul jannah","Alvita Debby Marcella"]},"match_id_code":"RB-E-BG-03-X","start_time":"09:30","end_time":"09:40","duration":10,"athlete_name":"MUHAMMAD SAMUDERA GENETIKA SULFAN, RISQI EKA ERNADI, Umar Alfaruq, Arga Kusuma R.S.M.Y","athlete_ids":[1204,1272,1284,1282],"contingent":"JOMBANG","merge_id":4}	2026-06-12 14:20:33	2026-06-12 14:20:33	2026-06-15	2	1
16828	14	71	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","surya sajid","dyah nur islami putri nisyam"]},"match_id_code":"RB-E-XX-01-X","start_time":"09:30","end_time":"09:40","duration":10,"athlete_name":"Fais Pratama Nugroho ","athlete_ids":[1335],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16829	14	61	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","surya sajid","dyah nur islami putri nisyam"]},"match_id_code":"RB-E-XX-02-X","start_time":"09:40","end_time":"09:50","duration":10,"athlete_name":"Daniswara Gaozan","athlete_ids":[1280],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16830	14	73	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","surya sajid","dyah nur islami putri nisyam"]},"match_id_code":"RB-E-XX-03-X","start_time":"09:50","end_time":"10:00","duration":10,"athlete_name":"Devid Wisnu Sudarsono ","athlete_ids":[1231],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16831	14	61	5	3	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","surya sajid","dyah nur islami putri nisyam"]},"match_id_code":"RB-E-XX-04-X","start_time":"10:00","end_time":"10:10","duration":10,"athlete_name":"Ganendra Waradana Prayuda","athlete_ids":{"1":1277},"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16832	14	69	5	3	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","surya sajid","dyah nur islami putri nisyam"]},"match_id_code":"RB-E-XX-05-X","start_time":"10:10","end_time":"10:20","duration":10,"athlete_name":"Ahmad Maulana Ibrahim ","athlete_ids":[1315],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16833	15	61	5	1	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","surya sajid","saddam bintang hermawan","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-01-X","start_time":"09:40","end_time":"09:50","duration":10,"athlete_name":"Umar Alfaruq","athlete_ids":[1272],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16834	15	71	5	1	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","surya sajid","saddam bintang hermawan","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-02-X","start_time":"09:50","end_time":"10:00","duration":10,"athlete_name":"Desta Rizky Syahputra","athlete_ids":[1338],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16835	15	65	5	1	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","surya sajid","saddam bintang hermawan","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-03-X","start_time":"10:00","end_time":"10:10","duration":10,"athlete_name":"BUSHIDO AJIE SAHPUTRA","athlete_ids":[1242],"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16836	15	61	5	1	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","surya sajid","saddam bintang hermawan","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-04-X","start_time":"10:10","end_time":"10:20","duration":10,"athlete_name":"Muhammad Ervin Abigail","athlete_ids":{"1":1286},"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16837	16	71	5	2	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Inggar Cahya Wahyu Putri ","Velina","revan catur cakti putra jatayu","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-01-X","start_time":"09:40","end_time":"09:50","duration":10,"athlete_name":"GIOVANNI PUTRA HARMAWAN, Desta Rizky Syahputra","athlete_ids":[1337,1338],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16838	16	61	5	2	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Inggar Cahya Wahyu Putri ","Velina","revan catur cakti putra jatayu","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-02-X","start_time":"09:50","end_time":"10:00","duration":10,"athlete_name":"Umar Alfaruq, Arga Kusuma R.S.M.Y","athlete_ids":[1204,1272],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16839	16	63	5	2	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Inggar Cahya Wahyu Putri ","Velina","revan catur cakti putra jatayu","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-03-X","start_time":"10:00","end_time":"10:10","duration":10,"athlete_name":"MUHARROR MADANI , ANDHIKA RIDHO MUTTAQIN ","athlete_ids":[1210,1209],"contingent":"BANGKALAN B","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16840	16	73	5	2	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Inggar Cahya Wahyu Putri ","Velina","revan catur cakti putra jatayu","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-04-X","start_time":"10:10","end_time":"10:20","duration":10,"athlete_name":"David Wisnu Sudarsono , Devid Wisnu Sudarsono ","athlete_ids":[1230,1231],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16841	24	63	5	4	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-01-X","start_time":"09:50","end_time":"10:00","duration":10,"athlete_name":"ZUHRUFFINE CALISTA PUTRI INDI ","athlete_ids":[1208],"contingent":"BANGKALAN B","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16842	24	71	5	4	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-02-X","start_time":"10:00","end_time":"10:10","duration":10,"athlete_name":"Azzizah Lucita Zaviera","athlete_ids":[1336],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16843	24	61	5	4	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-03-X","start_time":"10:10","end_time":"10:20","duration":10,"athlete_name":"APRILLIA EKA NURAINI","athlete_ids":{"1":1275},"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16844	24	73	5	4	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-04-X","start_time":"10:20","end_time":"10:30","duration":10,"athlete_name":"Naysila Cinta Aulia","athlete_ids":[1220],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16845	24	69	5	4	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-05-X","start_time":"10:30","end_time":"10:40","duration":10,"athlete_name":"Farah azalia salshabila ilham","athlete_ids":[1316],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16846	24	62	5	4	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-06-X","start_time":"10:40","end_time":"10:50","duration":10,"athlete_name":"DIANA OCTHA VELA","athlete_ids":[1216],"contingent":"BANGKALAN A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16847	24	61	5	4	Penyisihan	7	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-07-X","start_time":"10:50","end_time":"11:00","duration":10,"athlete_name":"Meysha Aulia Josephine Mahardika","athlete_ids":[1270],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16848	24	62	5	4	Penyisihan	8	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Alvita Debby Marcella","alyssa rahma pradipta","nadifa amira","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-08-X","start_time":"11:00","end_time":"11:10","duration":10,"athlete_name":"NAILA","athlete_ids":{"1":1217},"contingent":"BANGKALAN A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16849	25	61	5	1	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","nadifa amira","sophie nabila aekidya","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-01-X","start_time":"10:20","end_time":"10:30","duration":10,"athlete_name":"AISYAH ZASKIANDRA JASMINE","athlete_ids":{"1":1278},"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16850	25	70	5	1	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","nadifa amira","sophie nabila aekidya","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-02-X","start_time":"10:30","end_time":"10:40","duration":10,"athlete_name":"Bilvina Aqila Saumifathiyah","athlete_ids":[1321],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16888	3	\N	\N	4	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-08-X","start_time":"13:20","end_time":"13:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16851	25	61	5	1	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["saddam bintang hermawan","nadifa amira","sophie nabila aekidya","Alvita Debby Marcella"]},"match_id_code":"RB-E-XX-03-X","start_time":"10:40","end_time":"10:50","duration":10,"athlete_name":"AZZAHRA ADINDA PUTRI ","athlete_ids":[1285],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16852	26	73	5	2	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-01-X","start_time":"10:20","end_time":"10:30","duration":10,"athlete_name":"Naysila Cinta Aulia, Yetania Vita Zabrina","athlete_ids":[1225,1220],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16853	26	60	5	2	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-02-X","start_time":"10:30","end_time":"10:40","duration":10,"athlete_name":"Icha Sazira Fitriani , Kamila Farha Ilmi","athlete_ids":[1167,1166],"contingent":"Kabupaten Pasuruan","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16854	26	71	5	2	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-03-X","start_time":"10:40","end_time":"10:50","duration":10,"athlete_name":"Azzizah Lucita Zaviera, Annisa Khansa Jamiah","athlete_ids":[1343,1336],"contingent":"Surabaya A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16855	26	63	5	2	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-04-X","start_time":"10:50","end_time":"11:00","duration":10,"athlete_name":"DESVITA EKA PUTRI, ZUHRUFFINE CALISTA PUTRI INDI ","athlete_ids":[1207,1208],"contingent":"BANGKALAN B","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16856	26	61	5	2	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-05-X","start_time":"11:00","end_time":"11:10","duration":10,"athlete_name":"arum shelvia fitri, APRILLIA EKA NURAINI","athlete_ids":[1275,1274],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16857	26	70	5	2	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","alyssa rahma pradipta","nadia septia wardani","revan catur cakti putra jatayu"]},"match_id_code":"RB-E-XX-06-X","start_time":"11:10","end_time":"11:20","duration":10,"athlete_name":"QUENASHA GENDHIS GUPITA, Jasmine Nur Erika","athlete_ids":[1323,1324],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16858	51	62	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Raya Silmina","ayumi trinarita wardani","dyah nur islami putri nisyam"]},"match_id_code":"D-E-PS-01-X","start_time":"10:20","end_time":"10:30","duration":10,"athlete_name":"ASTRIT RABECA YOLANDA, ACH.FAIZ PUTRA PRATAMA JIANZAH ","athlete_ids":[1213,1226],"contingent":"BANGKALAN A","merge_id":5}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16859	50	73	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Raya Silmina","ayumi trinarita wardani","dyah nur islami putri nisyam"]},"match_id_code":"D-E-PS-02-X","start_time":"10:30","end_time":"10:40","duration":10,"athlete_name":"Rohma Anggun Oktaviandini, Dinda Nofitasari","athlete_ids":[1223,1221],"contingent":"Banyuwangi","merge_id":5}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16860	51	71	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Raya Silmina","ayumi trinarita wardani","dyah nur islami putri nisyam"]},"match_id_code":"D-E-PS-03-X","start_time":"10:40","end_time":"10:50","duration":10,"athlete_name":"Ramadhani Arta Pradipta, Laverda Javier Tonda","athlete_ids":[1342,1344],"contingent":"Surabaya A","merge_id":5}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16861	35	65	5	1	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-01-X","start_time":"10:50","end_time":"11:00","duration":10,"athlete_name":"MUHAMMAD RHELZA ARIVIRGA","athlete_ids":{"1":1252},"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16862	35	65	5	1	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-02-X","start_time":"11:00","end_time":"11:10","duration":10,"athlete_name":"ANTONIUS DWIYANTO AGUN","athlete_ids":{"3":1254},"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16863	35	65	5	1	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-03-X","start_time":"11:10","end_time":"11:20","duration":10,"athlete_name":"EGA PRAMUDYA","athlete_ids":{"2":1253},"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16864	35	73	5	1	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-04-X","start_time":"11:20","end_time":"11:30","duration":10,"athlete_name":"Mohammad Agung Adi Saputra","athlete_ids":[1233],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16865	35	62	5	1	Penyisihan	5	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-05-X","start_time":"11:30","end_time":"11:40","duration":10,"athlete_name":"DIMAS DZAKY ","athlete_ids":[1229],"contingent":"BANGKALAN A","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16866	35	65	5	1	Penyisihan	6	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["ayumi trinarita wardani","sophie nabila aekidya","surya sajid","sheila wardatul jannah"]},"match_id_code":"D-E-XX-06-X","start_time":"11:40","end_time":"11:50","duration":10,"athlete_name":"WAHYUDIN SAPUTRA UMAR","athlete_ids":[1251],"contingent":"TUBAN","merge_id":null}	2026-06-12 14:20:34	2026-06-12 14:20:34	2026-06-15	2	1
16867	42	73	5	3	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","sagita suryani anwar","sheila wardatul jannah"]},"match_id_code":"D-E-XX-01-X","start_time":"10:50","end_time":"11:00","duration":10,"athlete_name":"Bellia Aprilia","athlete_ids":{"1":1224},"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16868	42	73	5	3	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","sagita suryani anwar","sheila wardatul jannah"]},"match_id_code":"D-E-XX-02-X","start_time":"11:00","end_time":"11:10","duration":10,"athlete_name":"Andini Masayu Mustikaning Ratri","athlete_ids":[1219],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16869	42	60	5	3	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","sagita suryani anwar","sheila wardatul jannah"]},"match_id_code":"D-E-XX-03-X","start_time":"11:10","end_time":"11:20","duration":10,"athlete_name":"Nawrah Filzah Cameela","athlete_ids":[1169],"contingent":"Kabupaten Pasuruan","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16870	43	73	5	4	Penyisihan	1	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["nadifa amira","aisyah nurillah","Alvita Debby Marcella","Velina"]},"match_id_code":"D-E-XX-01-X","start_time":"11:10","end_time":"11:20","duration":10,"athlete_name":"Rohma Anggun Oktaviandini","athlete_ids":[1221],"contingent":"Banyuwangi","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16871	43	61	5	4	Penyisihan	2	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["nadifa amira","aisyah nurillah","Alvita Debby Marcella","Velina"]},"match_id_code":"D-E-XX-02-X","start_time":"11:20","end_time":"11:30","duration":10,"athlete_name":"ZULVA AINUN ZASKIA ","athlete_ids":[1268],"contingent":"JOMBANG","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16872	43	69	5	4	Penyisihan	3	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["nadifa amira","aisyah nurillah","Alvita Debby Marcella","Velina"]},"match_id_code":"D-E-XX-03-X","start_time":"11:30","end_time":"11:40","duration":10,"athlete_name":"Bilqis Ammardivia G ","athlete_ids":[1317],"contingent":"Surabaya C","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16873	43	70	5	4	Penyisihan	4	embu	{"pool_label":"POOL 1","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["nadifa amira","aisyah nurillah","Alvita Debby Marcella","Velina"]},"match_id_code":"D-E-XX-04-X","start_time":"11:40","end_time":"11:50","duration":10,"athlete_name":"Anantya Okazzahra Santoso","athlete_ids":[1325],"contingent":"Surabaya B","merge_id":null}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16874	1	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-06-X","start_time":"11:20","end_time":"11:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16875	1	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-07-X","start_time":"11:30","end_time":"11:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16876	1	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-08-X","start_time":"11:40","end_time":"11:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16877	1	\N	\N	2	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-09-X","start_time":"11:50","end_time":"12:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16878	1	\N	\N	2	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-10-X","start_time":"13:00","end_time":"13:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16879	2	\N	\N	3	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-07-X","start_time":"11:20","end_time":"11:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16880	2	\N	\N	3	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-08-X","start_time":"11:30","end_time":"11:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16881	2	\N	\N	3	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-09-X","start_time":"11:40","end_time":"11:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16882	2	\N	\N	3	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-10-X","start_time":"11:50","end_time":"12:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16883	2	\N	\N	3	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-11-X","start_time":"13:00","end_time":"13:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16884	2	\N	\N	3	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-TK-12-X","start_time":"13:10","end_time":"13:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16885	3	\N	\N	4	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-05-X","start_time":"11:50","end_time":"12:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16886	3	\N	\N	4	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-06-X","start_time":"13:00","end_time":"13:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16889	4	\N	\N	1	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-06-X","start_time":"11:50","end_time":"12:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	1
16890	4	\N	\N	1	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-07-X","start_time":"13:00","end_time":"13:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16891	4	\N	\N	1	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-08-X","start_time":"13:10","end_time":"13:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16892	4	\N	\N	1	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-09-X","start_time":"13:20","end_time":"13:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16893	4	\N	\N	1	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"P-E-PS-10-X","start_time":"13:30","end_time":"13:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16894	7	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-07-X","start_time":"13:10","end_time":"13:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16895	7	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-08-X","start_time":"13:20","end_time":"13:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16896	7	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-09-X","start_time":"13:30","end_time":"13:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16897	7	\N	\N	2	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-10-X","start_time":"13:40","end_time":"13:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16898	7	\N	\N	2	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-11-X","start_time":"13:50","end_time":"14:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16899	7	\N	\N	2	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-TK-12-X","start_time":"14:00","end_time":"14:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16900	10	\N	\N	3	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-10-X","start_time":"13:20","end_time":"13:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16901	10	\N	\N	3	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-11-X","start_time":"13:30","end_time":"13:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:35	2026-06-12 14:20:35	2026-06-15	2	2
16902	10	\N	\N	3	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-12-X","start_time":"13:40","end_time":"13:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16903	10	\N	\N	3	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-13-X","start_time":"13:50","end_time":"14:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16904	10	\N	\N	3	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-14-X","start_time":"14:00","end_time":"14:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16905	10	\N	\N	3	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-15-X","start_time":"14:10","end_time":"14:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16906	10	\N	\N	3	Final	7	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-16-X","start_time":"14:20","end_time":"14:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16907	10	\N	\N	3	Final	8	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-17-X","start_time":"14:30","end_time":"14:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16908	10	\N	\N	3	Final	9	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-PS-18-X","start_time":"14:40","end_time":"14:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16909	11	\N	\N	4	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-BG-05-X","start_time":"13:30","end_time":"13:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16910	11	\N	\N	4	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-BG-06-X","start_time":"13:40","end_time":"13:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16911	11	\N	\N	4	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-BG-07-X","start_time":"13:50","end_time":"14:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16912	11	\N	\N	4	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-BG-08-X","start_time":"14:00","end_time":"14:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16913	6	\N	\N	1	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-08-X","start_time":"13:40","end_time":"13:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16914	6	\N	\N	1	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-09-X","start_time":"13:50","end_time":"14:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16915	6	\N	\N	1	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-10-X","start_time":"14:00","end_time":"14:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16916	6	\N	\N	1	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-11-X","start_time":"14:10","end_time":"14:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16917	6	\N	\N	1	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-12-X","start_time":"14:20","end_time":"14:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16918	6	\N	\N	1	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-13-X","start_time":"14:30","end_time":"14:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16919	6	\N	\N	1	Final	7	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RA-E-XX-14-X","start_time":"14:40","end_time":"14:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16920	31	\N	\N	4	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-PS-04-X","start_time":"14:10","end_time":"14:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16921	31	\N	\N	4	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-PS-05-X","start_time":"14:20","end_time":"14:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16922	31	\N	\N	4	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-PS-06-X","start_time":"14:30","end_time":"14:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16923	34	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-BG-04-X","start_time":"14:10","end_time":"14:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16924	34	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-BG-05-X","start_time":"14:20","end_time":"14:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16925	34	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-BG-06-X","start_time":"14:30","end_time":"14:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16926	14	\N	\N	4	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-06-X","start_time":"14:40","end_time":"14:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16927	14	\N	\N	4	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-07-X","start_time":"14:50","end_time":"15:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16928	14	\N	\N	4	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-08-X","start_time":"15:00","end_time":"15:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16929	14	\N	\N	4	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-09-X","start_time":"15:10","end_time":"15:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16930	14	\N	\N	4	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-10-X","start_time":"15:20","end_time":"15:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16931	15	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-05-X","start_time":"14:40","end_time":"14:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16932	15	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-06-X","start_time":"14:50","end_time":"15:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16933	15	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-07-X","start_time":"15:00","end_time":"15:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16934	15	\N	\N	2	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-08-X","start_time":"15:10","end_time":"15:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16935	16	\N	\N	1	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-05-X","start_time":"14:50","end_time":"15:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16936	16	\N	\N	1	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-06-X","start_time":"15:00","end_time":"15:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16937	16	\N	\N	1	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-07-X","start_time":"15:10","end_time":"15:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16938	16	\N	\N	1	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-08-X","start_time":"15:20","end_time":"15:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:36	2026-06-12 14:20:36	2026-06-15	2	2
16939	24	\N	\N	3	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-09-X","start_time":"14:50","end_time":"15:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16940	24	\N	\N	3	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-10-X","start_time":"15:00","end_time":"15:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16941	24	\N	\N	3	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-11-X","start_time":"15:10","end_time":"15:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16942	24	\N	\N	3	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-12-X","start_time":"15:20","end_time":"15:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16943	24	\N	\N	3	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-13-X","start_time":"15:30","end_time":"15:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16944	24	\N	\N	3	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-14-X","start_time":"15:40","end_time":"15:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16945	24	\N	\N	3	Final	7	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-15-X","start_time":"15:50","end_time":"16:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16946	24	\N	\N	3	Final	8	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-16-X","start_time":"16:00","end_time":"16:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16947	25	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-04-X","start_time":"15:20","end_time":"15:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16948	25	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-05-X","start_time":"15:30","end_time":"15:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16949	25	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-06-X","start_time":"15:40","end_time":"15:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16950	26	\N	\N	4	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-07-X","start_time":"15:30","end_time":"15:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16951	26	\N	\N	4	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-08-X","start_time":"15:40","end_time":"15:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16952	26	\N	\N	4	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-09-X","start_time":"15:50","end_time":"16:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16953	26	\N	\N	4	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-10-X","start_time":"16:00","end_time":"16:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16954	26	\N	\N	4	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-11-X","start_time":"16:10","end_time":"16:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16955	26	\N	\N	4	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"RB-E-XX-12-X","start_time":"16:20","end_time":"16:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16956	51	\N	\N	1	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-PS-04-X","start_time":"15:30","end_time":"15:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16957	51	\N	\N	1	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-PS-05-X","start_time":"15:40","end_time":"15:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16958	51	\N	\N	1	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-PS-06-X","start_time":"15:50","end_time":"16:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16959	35	\N	\N	2	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-07-X","start_time":"15:50","end_time":"16:00","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16960	35	\N	\N	2	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-08-X","start_time":"16:00","end_time":"16:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16961	35	\N	\N	2	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-09-X","start_time":"16:10","end_time":"16:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16962	35	\N	\N	2	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-10-X","start_time":"16:20","end_time":"16:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16963	35	\N	\N	2	Final	5	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-11-X","start_time":"16:30","end_time":"16:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16964	35	\N	\N	2	Final	6	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-12-X","start_time":"16:40","end_time":"16:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16965	42	\N	\N	1	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-04-X","start_time":"16:00","end_time":"16:10","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16966	42	\N	\N	1	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-05-X","start_time":"16:10","end_time":"16:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16967	42	\N	\N	1	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-06-X","start_time":"16:20","end_time":"16:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16968	43	\N	\N	3	Final	1	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-05-X","start_time":"16:10","end_time":"16:20","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16969	43	\N	\N	3	Final	2	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-06-X","start_time":"16:20","end_time":"16:30","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16970	43	\N	\N	3	Final	3	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-07-X","start_time":"16:30","end_time":"16:40","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16971	43	\N	\N	3	Final	4	embu	{"pool_label":"FINAL","officials":[],"match_id_code":"D-E-XX-08-X","start_time":"16:40","end_time":"16:50","duration":10,"contingent":"Lolos Final","athlete_name":"TBD"}	2026-06-12 14:20:37	2026-06-12 14:20:37	2026-06-15	2	2
16972	17	59	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1156,"athlete_name":"RADITYA KRESNA WAHYU WISNU SAPUTRA","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16973	17	61	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1277,"athlete_name":"Ganendra Waradana Prayuda","contingent":"JOMBANG","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16974	17	62	\N	2	Penyisihan (UB R1)	2	randori	{"athlete_id":1215,"athlete_name":"SYAIFUL ROHMAN ","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16975	17	70	\N	2	Penyisihan (UB R1)	2	randori	{"athlete_id":1320,"athlete_name":"Fariz Fernando","contingent":"Surabaya B","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16976	17	\N	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16977	17	\N	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16978	17	\N	\N	2	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16979	17	\N	\N	2	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16980	17	\N	\N	2	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-05-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16981	17	\N	\N	2	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Alvita Debby Marcella","ayumi trinarita wardani","aisyah nurillah"]},"match_id_code":"RB-R-45-05-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16982	18	59	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1200,"athlete_name":"M Qaishar Mirza Athariz","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16983	18	61	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1284,"athlete_name":"RISQI EKA ERNADI","contingent":"JOMBANG","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16984	18	73	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":1232,"athlete_name":"Daniel Marsenda","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16985	18	\N	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16986	18	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16987	18	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16988	18	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16989	18	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","surya sajid","sophie nabila aekidya","nadia septia wardani"]},"match_id_code":"RB-R-50-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16990	19	63	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1210,"athlete_name":"ANDHIKA RIDHO MUTTAQIN ","contingent":"BANGKALAN B","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16991	19	71	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1337,"athlete_name":"GIOVANNI PUTRA HARMAWAN","contingent":"Surabaya A","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-01-X","start_time":"07:30","end_time":"07:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16992	19	73	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1230,"athlete_name":"David Wisnu Sudarsono ","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16993	19	61	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1282,"athlete_name":"MUHAMMAD SAMUDERA GENETIKA SULFAN","contingent":"JOMBANG","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-02-X","start_time":"07:40","end_time":"07:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16994	19	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16995	19	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-03-X","start_time":"07:50","end_time":"08:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16996	19	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16997	19	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-04-X","start_time":"08:00","end_time":"08:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16998	19	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-05-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
16999	19	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["dyah nur islami putri nisyam","sagita suryani anwar","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-55-05-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:41	2026-06-12 14:30:41	2026-06-16	3	1
17000	20	73	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1231,"athlete_name":"Devid Wisnu Sudarsono ","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-01-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17001	20	61	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1286,"athlete_name":"Muhammad Ervin Abigail","contingent":"JOMBANG","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-01-X","start_time":"08:10","end_time":"08:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17002	20	59	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":1240,"athlete_name":"M. ARGA ADYASTA RESWARA","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-02-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17003	20	\N	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-02-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17004	20	62	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":1227,"athlete_name":"WIRA ALFI QOLBI NURAN","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-03-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17005	20	69	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":1349,"athlete_name":"Bisma Ali Kumara","contingent":"Surabaya C","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","sheila wardatul jannah","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-60-03-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17006	21	71	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1338,"athlete_name":"Desta Rizky Syahputra","contingent":"Surabaya A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-01-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17007	21	65	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1242,"athlete_name":"BUSHIDO AJIE SAHPUTRA","contingent":"TUBAN","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-01-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17008	21	63	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":1209,"athlete_name":"MUHARROR MADANI ","contingent":"BANGKALAN B","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-02-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17009	21	\N	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-02-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17010	21	61	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":1204,"athlete_name":"Arga Kusuma R.S.M.Y","contingent":"JOMBANG","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-03-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17011	21	59	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":1175,"athlete_name":"FAWWAZ AGRIYA PUTRA","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"SUGIONO","panitera":["saddam bintang hermawan","Agus Ifan  Riyadi","aisyah nurillah","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-65-03-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17012	22	69	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1315,"athlete_name":"Ahmad Maulana Ibrahim ","contingent":"Surabaya C","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-01-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17013	22	70	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1318,"athlete_name":"Reinhard Henokh Stardani Panambunan ","contingent":"Surabaya B","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-01-X","start_time":"08:20","end_time":"08:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17014	22	68	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1298,"athlete_name":"Alaire Tedy Prasetyo","contingent":"Surabaya D","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-02-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17015	22	71	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1341,"athlete_name":"DAVE AARON ELZABATH","contingent":"Surabaya A","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-02-X","start_time":"08:30","end_time":"08:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17016	22	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-03-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17017	22	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-03-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17018	22	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-04-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17019	22	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-04-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17020	22	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-05-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17021	22	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["alyssa rahma pradipta","Raya Silmina","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-05-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17022	23	71	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1340,"athlete_name":"Alexander Troy Moeljono ","contingent":"Surabaya A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-01-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17023	23	68	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1300,"athlete_name":"JADON DAFFIN JANUADRI","contingent":"Surabaya D","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-01-X","start_time":"08:40","end_time":"08:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17024	23	70	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":1347,"athlete_name":"Delon Nathanael Tandio","contingent":"Surabaya B","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-02-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17025	23	\N	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-02-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17026	23	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-03-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17027	23	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-03-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17028	23	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-04-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17029	23	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["sheila wardatul jannah","Raya Silmina","Alvita Debby Marcella","surya sajid"]},"match_id_code":"RB-R-GT70-04-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17030	27	62	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1217,"athlete_name":"NAILA","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-01-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17031	27	60	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1168,"athlete_name":"Devina Wahyu Ramadhani ","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-01-X","start_time":"08:50","end_time":"09:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17032	27	59	\N	2	Penyisihan (UB R1)	2	randori	{"athlete_id":1190,"athlete_name":"Anisa Zhafira Ilma","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-02-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17033	27	71	\N	2	Penyisihan (UB R1)	2	randori	{"athlete_id":1343,"athlete_name":"Annisa Khansa Jamiah","contingent":"Surabaya A","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-02-X","start_time":"09:00","end_time":"09:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17034	27	70	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":1322,"athlete_name":"Abygael Kanaia Aditomo","contingent":"Surabaya B","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-03-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17035	27	\N	\N	2	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-03-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17036	27	61	\N	2	Penyisihan (UB R2)	4	randori	{"athlete_id":1270,"athlete_name":"Meysha Aulia Josephine Mahardika","contingent":"JOMBANG","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-04-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17037	27	\N	\N	2	Penyisihan (UB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sophie nabila aekidya","Raya Silmina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-45-04-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17038	28	59	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1188,"athlete_name":"Agatha Natania Lituhayu","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-01-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17039	28	63	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1208,"athlete_name":"ZUHRUFFINE CALISTA PUTRI INDI ","contingent":"BANGKALAN B","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-01-X","start_time":"09:10","end_time":"09:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17040	28	71	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1336,"athlete_name":"Azzizah Lucita Zaviera","contingent":"Surabaya A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-02-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17041	28	73	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1220,"athlete_name":"Naysila Cinta Aulia","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-02-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17042	28	61	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":1274,"athlete_name":"arum shelvia fitri","contingent":"JOMBANG","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-03-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17043	28	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-03-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17044	28	60	\N	1	Penyisihan (UB R2)	4	randori	{"athlete_id":1167,"athlete_name":"Kamila Farha Ilmi","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-04-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17045	28	\N	\N	1	Penyisihan (UB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadia septia wardani","sagita suryani anwar","Alvita Debby Marcella","aisyah nurillah"]},"match_id_code":"RB-R-50-04-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:42	2026-06-12 14:30:42	2026-06-16	3	1
17046	29	61	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1273,"athlete_name":"Ochie Dila Aurelia ","contingent":"JOMBANG","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-01-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17047	29	70	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1323,"athlete_name":"QUENASHA GENDHIS GUPITA","contingent":"Surabaya B","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-01-X","start_time":"09:20","end_time":"09:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17048	29	73	\N	4	Penyisihan (UB R1)	2	randori	{"athlete_id":1225,"athlete_name":"Yetania Vita Zabrina","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-02-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17049	29	60	\N	4	Penyisihan (UB R1)	2	randori	{"athlete_id":1166,"athlete_name":"Icha Sazira Fitriani ","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-02-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17050	29	\N	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-03-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17051	29	\N	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-03-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17052	29	\N	\N	4	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-04-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17053	29	\N	\N	4	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-04-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17054	29	\N	\N	4	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-05-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17055	29	\N	\N	4	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","Velina","sophie nabila aekidya","revan catur cakti putra jatayu"]},"match_id_code":"RB-R-55-05-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17056	30	70	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1324,"athlete_name":"Jasmine Nur Erika","contingent":"Surabaya B","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-01-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17057	30	61	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1278,"athlete_name":"AISYAH ZASKIANDRA JASMINE","contingent":"JOMBANG","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-01-X","start_time":"09:30","end_time":"09:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17058	30	63	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":1207,"athlete_name":"DESVITA EKA PUTRI","contingent":"BANGKALAN B","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-02-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17059	30	\N	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-02-X","start_time":"09:40","end_time":"09:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17060	30	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-03-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17061	30	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-03-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17062	30	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-04-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17063	30	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","Alvita Debby Marcella","saddam bintang hermawan","dyah nur islami putri nisyam"]},"match_id_code":"RB-R-70-04-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17064	37	73	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1222,"athlete_name":"Ditya Roni Saputra","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-01-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17065	37	59	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1199,"athlete_name":"Muhammad Wenpy Abdi Vannorin","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-01-X","start_time":"09:50","end_time":"10:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17066	37	62	\N	1	Penyisihan (UB R2)	2	randori	{"athlete_id":1226,"athlete_name":"ACH.FAIZ PUTRA PRATAMA JIANZAH ","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-02-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17067	37	\N	\N	1	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-02-X","start_time":"10:00","end_time":"10:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17068	37	\N	\N	1	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-03-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17069	37	\N	\N	1	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-03-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17070	37	\N	\N	1	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-04-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17071	37	\N	\N	1	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Agus Ifan  Riyadi","saddam bintang hermawan"]},"match_id_code":"D-R-50-04-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17072	38	60	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1172,"athlete_name":"Prayogie Al Dino","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-01-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17073	38	59	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1358,"athlete_name":"Muhammad Faza Nabhani","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-01-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17074	38	73	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":1233,"athlete_name":"Mohammad Agung Adi Saputra","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-02-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17075	38	\N	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-02-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17076	38	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-03-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17077	38	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-03-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17078	38	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-04-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17079	38	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","alyssa rahma pradipta","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-55-04-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17080	40	65	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1251,"athlete_name":"WAHYUDIN SAPUTRA UMAR","contingent":"TUBAN","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-01-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17081	40	73	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1237,"athlete_name":"Rendi Setyawan","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-01-X","start_time":"10:10","end_time":"10:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17082	40	59	\N	4	Penyisihan (UB R1)	2	randori	{"athlete_id":1241,"athlete_name":"Yohanes Anselmo Mau","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-02-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17083	40	65	\N	4	Penyisihan (UB R1)	2	randori	{"athlete_id":1253,"athlete_name":"EGA PRAMUDYA","contingent":"TUBAN","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-02-X","start_time":"10:20","end_time":"10:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_3"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17084	40	71	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":1342,"athlete_name":"Ramadhani Arta Pradipta","contingent":"Surabaya A","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-03-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17085	40	\N	\N	4	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-03-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:43	2026-06-12 14:30:43	2026-06-16	3	1
17086	40	61	\N	4	Penyisihan (UB R2)	4	randori	{"athlete_id":1287,"athlete_name":"Mokhamad Ilyas Rosyid ","contingent":"JOMBANG","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-04-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17087	40	\N	\N	4	Penyisihan (UB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["aisyah nurillah","Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-65-04-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17088	41	62	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1229,"athlete_name":"DIMAS DZAKY ","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-01-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17089	41	65	\N	1	Penyisihan (UB R1)	1	randori	{"athlete_id":1254,"athlete_name":"ANTONIUS DWIYANTO AGUN","contingent":"TUBAN","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-01-X","start_time":"10:30","end_time":"10:40","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17090	41	59	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1198,"athlete_name":"M. Revaldo Alfino Pratama","contingent":"KOTA KEDIRI","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-02-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17091	41	65	\N	1	Penyisihan (UB R1)	2	randori	{"athlete_id":1252,"athlete_name":"MUHAMMAD RHELZA ARIVIRGA","contingent":"TUBAN","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-02-X","start_time":"10:40","end_time":"10:50","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17092	41	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-03-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17093	41	\N	\N	1	Penyisihan (UB R2)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-03-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17094	41	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-04-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17095	41	\N	\N	1	Penyisihan (LB R1)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-04-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17096	41	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-05-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17097	41	\N	\N	1	Penyisihan (LB R2)	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["aisyah nurillah","dyah nur islami putri nisyam","ayumi trinarita wardani","Raya Silmina"]},"match_id_code":"D-R-70-05-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17098	45	73	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1224,"athlete_name":"Bellia Aprilia","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-01-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17099	45	62	\N	2	Penyisihan (UB R1)	1	randori	{"athlete_id":1213,"athlete_name":"ASTRIT RABECA YOLANDA","contingent":"BANGKALAN A","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-01-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17100	45	60	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":1170,"athlete_name":"Amanda Aisyah Ramadanni ","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-02-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17101	45	\N	\N	2	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-02-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17102	45	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-03-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17103	45	\N	\N	2	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-03-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17104	45	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-04-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17105	45	\N	\N	2	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","Velina","Maulana Syaif Ridho Lil Firdaus","sheila wardatul jannah"]},"match_id_code":"D-R-50-04-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17106	46	61	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1268,"athlete_name":"ZULVA AINUN ZASKIA ","contingent":"JOMBANG","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-01-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"RED","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17107	46	73	\N	4	Penyisihan (UB R1)	1	randori	{"athlete_id":1223,"athlete_name":"Dinda Nofitasari","contingent":"Banyuwangi","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-01-X","start_time":"10:50","end_time":"11:00","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R1)","merge_id":null,"node_key":"ub_0_1"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17108	46	60	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":1171,"athlete_name":"Dzurotul Aini","contingent":"Kabupaten Pasuruan","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-02-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"RED","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17109	46	\N	\N	4	Penyisihan (UB R2)	2	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-02-X","start_time":"11:00","end_time":"11:10","duration":10,"side":"BLUE","pool_label":"Penyisihan (UB R2)","merge_id":null,"node_key":"ub_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17110	46	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":"BYE","athlete_name":"BYE","contingent":"-","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-03-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"RED","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17111	46	\N	\N	4	Penyisihan (LB R1)	3	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-03-X","start_time":"11:10","end_time":"11:20","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R1)","merge_id":null,"node_key":"lb_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17112	46	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-04-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"RED","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17113	46	\N	\N	4	Penyisihan (LB R2)	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Inggar Cahya Wahyu Putri ","revan catur cakti putra jatayu","Agus Ifan  Riyadi","dyah nur islami putri nisyam"]},"match_id_code":"D-R-55-04-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"BLUE","pool_label":"Penyisihan (LB R2)","merge_id":null,"node_key":"lb_1_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17114	17	\N	\N	1	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","alyssa rahma pradipta","ayumi trinarita wardani","saddam bintang hermawan"]},"match_id_code":"RB-R-45-06-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17115	17	\N	\N	1	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["nadifa amira","alyssa rahma pradipta","ayumi trinarita wardani","saddam bintang hermawan"]},"match_id_code":"RB-R-45-06-X","start_time":"11:20","end_time":"11:30","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:44	2026-06-12 14:30:44	2026-06-16	3	1
17116	18	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["revan catur cakti putra jatayu","sheila wardatul jannah","sophie nabila aekidya","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-50-05-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17117	18	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["revan catur cakti putra jatayu","sheila wardatul jannah","sophie nabila aekidya","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"RB-R-50-05-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17118	19	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","Alvita Debby Marcella","dyah nur islami putri nisyam","nadifa amira"]},"match_id_code":"RB-R-55-06-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17119	19	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Raya Silmina","Alvita Debby Marcella","dyah nur islami putri nisyam","nadifa amira"]},"match_id_code":"RB-R-55-06-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17120	20	\N	\N	1	Final	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","sophie nabila aekidya","Agus Ifan  Riyadi","surya sajid"]},"match_id_code":"RB-R-60-04-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"RED","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17121	20	\N	\N	1	Final	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Alvita Debby Marcella","sophie nabila aekidya","Agus Ifan  Riyadi","surya sajid"]},"match_id_code":"RB-R-60-04-X","start_time":"11:30","end_time":"11:40","duration":10,"side":"BLUE","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17122	21	\N	\N	2	Final	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","Raya Silmina"]},"match_id_code":"RB-R-65-04-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"RED","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17123	21	\N	\N	2	Final	4	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Maulana Syaif Ridho Lil Firdaus","alyssa rahma pradipta","Inggar Cahya Wahyu Putri ","Raya Silmina"]},"match_id_code":"RB-R-65-04-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"BLUE","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17124	22	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Agus Ifan  Riyadi","aisyah nurillah","Velina","sagita suryani anwar"]},"match_id_code":"RB-R-70-06-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17125	22	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Agus Ifan  Riyadi","aisyah nurillah","Velina","sagita suryani anwar"]},"match_id_code":"RB-R-70-06-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:45	2026-06-12 14:30:45	2026-06-16	3	1
17126	23	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","ayumi trinarita wardani","dyah nur islami putri nisyam","saddam bintang hermawan"]},"match_id_code":"RB-R-GT70-05-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17127	23	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["Maulana Syaif Ridho Lil Firdaus","ayumi trinarita wardani","dyah nur islami putri nisyam","saddam bintang hermawan"]},"match_id_code":"RB-R-GT70-05-X","start_time":"11:40","end_time":"11:50","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17128	27	\N	\N	2	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["saddam bintang hermawan","Alvita Debby Marcella","Agus Ifan  Riyadi","ayumi trinarita wardani"]},"match_id_code":"RB-R-45-05-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"RED","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17129	27	\N	\N	2	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["saddam bintang hermawan","Alvita Debby Marcella","Agus Ifan  Riyadi","ayumi trinarita wardani"]},"match_id_code":"RB-R-45-05-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"BLUE","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17130	28	\N	\N	4	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["sheila wardatul jannah","ayumi trinarita wardani","revan catur cakti putra jatayu","sophie nabila aekidya"]},"match_id_code":"RB-R-50-05-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"RED","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17131	28	\N	\N	4	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["sheila wardatul jannah","ayumi trinarita wardani","revan catur cakti putra jatayu","sophie nabila aekidya"]},"match_id_code":"RB-R-50-05-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"BLUE","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17132	29	\N	\N	1	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Raya Silmina","revan catur cakti putra jatayu","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"RB-R-55-06-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17133	29	\N	\N	1	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["Raya Silmina","revan catur cakti putra jatayu","Agus Ifan  Riyadi","nadia septia wardani"]},"match_id_code":"RB-R-55-06-X","start_time":"11:50","end_time":"12:00","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	1
17134	30	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["ayumi trinarita wardani","nadifa amira","aisyah nurillah","Inggar Cahya Wahyu Putri "]},"match_id_code":"RB-R-70-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	2
17135	30	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["ayumi trinarita wardani","nadifa amira","aisyah nurillah","Inggar Cahya Wahyu Putri "]},"match_id_code":"RB-R-70-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	2
17136	37	\N	\N	4	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sheila wardatul jannah","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-50-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	2
17137	37	\N	\N	4	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"DAUD TATENGKENG","panitera":["dyah nur islami putri nisyam","sheila wardatul jannah","Velina","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-50-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:46	2026-06-12 14:30:46	2026-06-16	3	2
17138	38	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","revan catur cakti putra jatayu","Agus Ifan  Riyadi","Alvita Debby Marcella"]},"match_id_code":"D-R-55-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17139	38	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["sophie nabila aekidya","revan catur cakti putra jatayu","Agus Ifan  Riyadi","Alvita Debby Marcella"]},"match_id_code":"D-R-55-05-X","start_time":"13:00","end_time":"13:10","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17140	40	\N	\N	2	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","nadia septia wardani","Raya Silmina","sagita suryani anwar"]},"match_id_code":"D-R-65-05-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"RED","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17141	40	\N	\N	2	Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["surya sajid","nadia septia wardani","Raya Silmina","sagita suryani anwar"]},"match_id_code":"D-R-65-05-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"BLUE","pool_label":"Final","merge_id":null,"node_key":"ub_2_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17142	41	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","saddam bintang hermawan","sagita suryani anwar","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-70-06-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17143	41	\N	\N	4	Grand Final	6	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["alyssa rahma pradipta","saddam bintang hermawan","sagita suryani anwar","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-70-06-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17144	45	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["ayumi trinarita wardani","nadifa amira","surya sajid","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-50-05-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17145	45	\N	\N	1	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"SUGIONO","panitera":["ayumi trinarita wardani","nadifa amira","surya sajid","Maulana Syaif Ridho Lil Firdaus"]},"match_id_code":"D-R-50-05-X","start_time":"13:10","end_time":"13:20","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17146	46	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","Alvita Debby Marcella","ayumi trinarita wardani","alyssa rahma pradipta"]},"match_id_code":"D-R-55-05-X","start_time":"13:20","end_time":"13:30","duration":10,"side":"RED","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
17147	46	\N	\N	2	Grand Final	5	randori	{"athlete_id":null,"athlete_name":"TBD","contingent":"TBD","officials":{"koordinator_lapangan":"BAMBANG ANJAR SOEPENO","panitera":["Raya Silmina","Alvita Debby Marcella","ayumi trinarita wardani","alyssa rahma pradipta"]},"match_id_code":"D-R-55-05-X","start_time":"13:20","end_time":"13:30","duration":10,"side":"BLUE","pool_label":"Grand Final","merge_id":null,"node_key":"gf_0_0"}	2026-06-12 14:30:47	2026-06-12 14:30:47	2026-06-16	3	2
\.


--
-- Data for Name: embu_champions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.embu_champions (id, match_number_id, registration_id, rank, penyisihan_score, final_score, accumulated_score, created_at, updated_at, drawing_id) FROM stdin;
\.


--
-- Data for Name: embu_scores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.embu_scores (id, match_number_id, registration_id, judge_1, judge_2, judge_3, judge_4, judge_5, total_score, rank, created_at, updated_at, tiebreak_round, denda, nilai_akhir, round_label, drawing_id, waktu) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.galleries (id, title, image_url, category, created_at, updated_at) FROM stdin;
1	et quo neque	https://picsum.photos/seed/65/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
2	reprehenderit vel ut	https://picsum.photos/seed/41/600/600	Highlight	2026-05-16 15:47:25	2026-05-16 15:47:25
3	voluptatum suscipit tenetur	https://picsum.photos/seed/29/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
4	fuga facere non	https://picsum.photos/seed/95/600/600	Awarding	2026-05-16 15:47:25	2026-05-16 15:47:25
5	quo doloribus sequi	https://picsum.photos/seed/65/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
6	commodi accusantium eveniet	https://picsum.photos/seed/81/600/600	Awarding	2026-05-16 15:47:25	2026-05-16 15:47:25
7	facere quis magnam	https://picsum.photos/seed/51/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
8	omnis id a	https://picsum.photos/seed/38/600/600	Highlight	2026-05-16 15:47:25	2026-05-16 15:47:25
9	quidem ipsum quis	https://picsum.photos/seed/33/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
10	velit accusantium laboriosam	https://picsum.photos/seed/35/600/600	Highlight	2026-05-16 15:47:25	2026-05-16 15:47:25
11	accusamus labore perferendis	https://picsum.photos/seed/41/600/600	Awarding	2026-05-16 15:47:25	2026-05-16 15:47:25
12	amet beatae aut	https://picsum.photos/seed/53/600/600	Competition	2026-05-16 15:47:25	2026-05-16 15:47:25
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: kyu_levels; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.kyu_levels (id, name, color, "order", created_at, updated_at) FROM stdin;
1	Kyu 5	Standard	1	2026-05-16 15:47:24	2026-05-16 15:47:24
2	Kyu 4	Standard	2	2026-05-16 15:47:24	2026-05-16 15:47:24
3	Kyu 3	Standard	3	2026-05-16 15:47:24	2026-05-16 15:47:24
4	Kyu 2	Standard	4	2026-05-16 15:47:24	2026-05-16 15:47:24
5	Kyu 1	Standard	5	2026-05-16 15:47:24	2026-05-16 15:47:24
6	Dan 1	Standard	6	2026-05-16 15:47:24	2026-05-16 15:47:24
7	Dan 2	Standard	7	2026-05-16 15:47:24	2026-05-16 15:47:24
\.


--
-- Data for Name: match_number_merge_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.match_number_merge_details (id, match_number_merge_id, match_number_id, created_at, updated_at) FROM stdin;
7	3	11	2026-06-08 13:42:36	2026-06-08 13:42:36
8	3	12	2026-06-08 13:42:36	2026-06-08 13:42:36
9	3	13	2026-06-08 13:42:36	2026-06-08 13:42:36
10	4	34	2026-06-08 13:43:07	2026-06-08 13:43:07
11	4	32	2026-06-08 13:43:07	2026-06-08 13:43:07
12	4	33	2026-06-08 13:43:07	2026-06-08 13:43:07
13	5	51	2026-06-08 13:44:13	2026-06-08 13:44:13
14	5	50	2026-06-08 13:44:13	2026-06-08 13:44:13
15	5	49	2026-06-08 13:44:13	2026-06-08 13:44:13
16	1	4	2026-06-10 03:21:55	2026-06-10 03:21:55
17	1	5	2026-06-10 03:21:55	2026-06-10 03:21:55
18	2	10	2026-06-12 14:15:30	2026-06-12 14:15:30
19	2	8	2026-06-12 14:15:30	2026-06-12 14:15:30
20	2	9	2026-06-12 14:15:30	2026-06-12 14:15:30
\.


--
-- Data for Name: match_number_merges; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.match_number_merges (id, name, age_group_id, type, created_at, updated_at) FROM stdin;
1	EMBU PASANGAN PUTRA/PUTRI/CAMPURAN PEMULA KYU KENSHI	1	embu	2026-06-08 09:10:42	2026-06-08 09:10:42
3	Embu Beregu eksebisi (Putra/Putri/Campuran)	2	embu	2026-06-08 13:42:36	2026-06-08 13:42:36
4	Embu Beregu eksebisi (Putra/Putri/Campuran)	3	embu	2026-06-08 13:43:07	2026-06-08 13:43:07
5	Embu Pasangan Kyu kenshi (Putra/Putri/Campuran)	4	embu	2026-06-08 13:44:13	2026-06-08 13:44:13
2	Embu Pasangan eksebisi (Putra/Putri/Campuran)	2	embu	2026-06-08 13:41:45	2026-06-12 14:15:30
\.


--
-- Data for Name: match_numbers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.match_numbers (id, name, max_athletes, age_group_id, draft_type, "order", created_at, updated_at, gender, drawing_data, drawing_generated_at, is_active, active_bracket_node, active_registration_id, match_id) FROM stdin;
3	Embu Pasangan campuran Kyu kenshi	2	1	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":68,"contingent":"Surabaya D"},{"order":4,"registration_id":69,"contingent":"Surabaya C"}]}}	2026-06-12 14:20:33	f	\N	\N	P3
43	embu tandoku kyu 1	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":73,"contingent":"Banyuwangi"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":69,"contingent":"Surabaya C"},{"order":4,"registration_id":70,"contingent":"Surabaya B"}]}}	2026-06-12 14:20:35	f	\N	\N	D9
2	Embu Tandoku kyu kenshi	1	1	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":6,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":62,"contingent":"BANGKALAN A"},{"order":3,"registration_id":73,"contingent":"Banyuwangi"},{"order":4,"registration_id":71,"contingent":"Surabaya A"},{"order":5,"registration_id":69,"contingent":"Surabaya C"},{"order":6,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:32	f	\N	\N	P2
20	Randori 60Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_size":8,"upper_bracket":{"rounds":[[{"athlete1":{"id":1240,"name":"M. ARGA ADYASTA RESWARA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":20},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1240,"name":"M. ARGA ADYASTA RESWARA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":20},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1231,"name":"Devid Wisnu Sudarsono ","contingent":"Banyuwangi","registration_id":73,"match_number_id":20},"athlete2":{"id":1286,"name":"Muhammad Ervin Abigail","contingent":"JOMBANG","registration_id":61,"match_number_id":20},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false},{"athlete1":{"id":1227,"name":"WIRA ALFI QOLBI NURAN","contingent":"BANGKALAN A","registration_id":62,"match_number_id":20},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1227,"name":"WIRA ALFI QOLBI NURAN","contingent":"BANGKALAN A","registration_id":62,"match_number_id":20},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1349,"name":"Bisma Ali Kumara","contingent":"Surabaya C","registration_id":69,"match_number_id":20},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1349,"name":"Bisma Ali Kumara","contingent":"Surabaya C","registration_id":69,"match_number_id":20},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":true}],[{"athlete1":{"id":1240,"name":"M. ARGA ADYASTA RESWARA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":20},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"ranked","rank":3},"is_bye":false},{"athlete1":{"id":1227,"name":"WIRA ALFI QOLBI NURAN","contingent":"BANGKALAN A","registration_id":62,"match_number_id":20},"athlete2":{"id":1349,"name":"Bisma Ali Kumara","contingent":"Surabaya C","registration_id":69,"match_number_id":20},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"ranked","rank":4},"is_bye":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ranked","rank":1},"loser_next":{"bracket":"ranked","rank":2},"is_bye":false}]]},"lower_bracket":{"rounds":[]},"grand_final":null,"juara":[],"type":"single_elimination","total_athletes":5}	2026-06-12 14:30:41	f	\N	\N	RB7
37	Randori 50Kg eksebisi	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1226,"name":"ACH.FAIZ PUTRA PRATAMA JIANZAH ","contingent":"BANGKALAN A","registration_id":62,"match_number_id":37},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1226,"name":"ACH.FAIZ PUTRA PRATAMA JIANZAH ","contingent":"BANGKALAN A","registration_id":62,"match_number_id":37},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1222,"name":"Ditya Roni Saputra","contingent":"Banyuwangi","registration_id":73,"match_number_id":37},"athlete2":{"id":1199,"name":"Muhammad Wenpy Abdi Vannorin","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":37},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1226,"name":"ACH.FAIZ PUTRA PRATAMA JIANZAH ","contingent":"BANGKALAN A","registration_id":62,"match_number_id":37},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:43	f	\N	\N	D3
40	Randori 65Kg	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_size":8,"upper_bracket":{"rounds":[[{"athlete1":{"id":1342,"name":"Ramadhani Arta Pradipta","contingent":"Surabaya A","registration_id":71,"match_number_id":40},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1342,"name":"Ramadhani Arta Pradipta","contingent":"Surabaya A","registration_id":71,"match_number_id":40},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1251,"name":"WAHYUDIN SAPUTRA UMAR","contingent":"TUBAN","registration_id":65,"match_number_id":40},"athlete2":{"id":1237,"name":"Rendi Setyawan","contingent":"Banyuwangi","registration_id":73,"match_number_id":40},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false},{"athlete1":{"id":1287,"name":"Mokhamad Ilyas Rosyid ","contingent":"JOMBANG","registration_id":61,"match_number_id":40},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1287,"name":"Mokhamad Ilyas Rosyid ","contingent":"JOMBANG","registration_id":61,"match_number_id":40},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1241,"name":"Yohanes Anselmo Mau","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":40},"athlete2":{"id":1253,"name":"EGA PRAMUDYA","contingent":"TUBAN","registration_id":65,"match_number_id":40},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false}],[{"athlete1":{"id":1342,"name":"Ramadhani Arta Pradipta","contingent":"Surabaya A","registration_id":71,"match_number_id":40},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"ranked","rank":3},"is_bye":false},{"athlete1":{"id":1287,"name":"Mokhamad Ilyas Rosyid ","contingent":"JOMBANG","registration_id":61,"match_number_id":40},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"ranked","rank":4},"is_bye":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ranked","rank":1},"loser_next":{"bracket":"ranked","rank":2},"is_bye":false}]]},"lower_bracket":{"rounds":[]},"grand_final":null,"juara":[],"type":"single_elimination","total_athletes":6}	2026-06-12 14:30:43	f	\N	\N	D6
41	Randori 70Kg eksebisi	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":4,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1229,"name":"DIMAS DZAKY ","contingent":"BANGKALAN A","registration_id":62,"match_number_id":41},"athlete2":{"id":1254,"name":"ANTONIUS DWIYANTO AGUN","contingent":"TUBAN","registration_id":65,"match_number_id":41},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":false,"is_prelim":false},{"athlete1":{"id":1198,"name":"M. Revaldo Alfino Pratama","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":41},"athlete2":{"id":1252,"name":"MUHAMMAD RHELZA ARIVIRGA","contingent":"TUBAN","registration_id":65,"match_number_id":41},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:44	f	\N	\N	D7
30	Randori 70Kg eksebisi	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1207,"name":"DESVITA EKA PUTRI","contingent":"BANGKALAN B","registration_id":63,"match_number_id":30},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1207,"name":"DESVITA EKA PUTRI","contingent":"BANGKALAN B","registration_id":63,"match_number_id":30},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1324,"name":"Jasmine Nur Erika","contingent":"Surabaya B","registration_id":70,"match_number_id":30},"athlete2":{"id":1278,"name":"AISYAH ZASKIANDRA JASMINE","contingent":"JOMBANG","registration_id":61,"match_number_id":30},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1207,"name":"DESVITA EKA PUTRI","contingent":"BANGKALAN B","registration_id":63,"match_number_id":30},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:43	f	\N	\N	RB17
5	Embu Pasangan Kyu kenshi eksebisi (Putri)	2	1	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":5,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":69,"contingent":"Surabaya C"},{"order":2,"registration_id":71,"contingent":"Surabaya A"},{"order":3,"registration_id":70,"contingent":"Surabaya B"},{"order":4,"registration_id":68,"contingent":"Surabaya D"},{"order":5,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	P4-F
32	Embu Beregu eksebisi (Putra)	4	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	RB19-M
16	embu pasangan kyu kenshi eksebisi	2	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":63,"contingent":"BANGKALAN B"},{"order":4,"registration_id":73,"contingent":"Banyuwangi"}]}}	2026-06-12 14:20:34	f	\N	\N	RB3
25	embu tandoku kyu 2/1	1	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":70,"contingent":"Surabaya B"},{"order":3,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:34	f	\N	\N	RB12
50	Embu Pasangan Kyu kenshi (Putri)	2	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":62,"contingent":"BANGKALAN A"},{"order":2,"registration_id":73,"contingent":"Banyuwangi"},{"order":3,"registration_id":71,"contingent":"Surabaya A"}]}}	2026-06-12 14:20:34	f	\N	\N	D15-F
22	Randori 70Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":4,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1315,"name":"Ahmad Maulana Ibrahim ","contingent":"Surabaya C","registration_id":69,"match_number_id":22},"athlete2":{"id":1318,"name":"Reinhard Henokh Stardani Panambunan ","contingent":"Surabaya B","registration_id":70,"match_number_id":22},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":false,"is_prelim":false},{"athlete1":{"id":1298,"name":"Alaire Tedy Prasetyo","contingent":"Surabaya D","registration_id":68,"match_number_id":22},"athlete2":{"id":1341,"name":"DAVE AARON ELZABATH","contingent":"Surabaya A","registration_id":71,"match_number_id":22},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:42	f	\N	\N	RB9
24	embu tandoku kyu 4/3	1	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":8,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":63,"contingent":"BANGKALAN B"},{"order":2,"registration_id":71,"contingent":"Surabaya A"},{"order":3,"registration_id":61,"contingent":"JOMBANG"},{"order":4,"registration_id":73,"contingent":"Banyuwangi"},{"order":5,"registration_id":69,"contingent":"Surabaya C"},{"order":6,"registration_id":62,"contingent":"BANGKALAN A"},{"order":7,"registration_id":61,"contingent":"JOMBANG"},{"order":8,"registration_id":62,"contingent":"BANGKALAN A"}]}}	2026-06-12 14:20:34	f	\N	\N	RB11
35	embu tandoku kyu 3/2 eksebisi	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":6,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":65,"contingent":"TUBAN"},{"order":2,"registration_id":65,"contingent":"TUBAN"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":73,"contingent":"Banyuwangi"},{"order":5,"registration_id":62,"contingent":"BANGKALAN A"},{"order":6,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:34	f	\N	\N	D1
28	Randori 50Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_size":8,"upper_bracket":{"rounds":[[{"athlete1":{"id":1274,"name":"arum shelvia fitri","contingent":"JOMBANG","registration_id":61,"match_number_id":28},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1274,"name":"arum shelvia fitri","contingent":"JOMBANG","registration_id":61,"match_number_id":28},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1188,"name":"Agatha Natania Lituhayu","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":28},"athlete2":{"id":1208,"name":"ZUHRUFFINE CALISTA PUTRI INDI ","contingent":"BANGKALAN B","registration_id":63,"match_number_id":28},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false},{"athlete1":{"id":1167,"name":"Kamila Farha Ilmi","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":28},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1167,"name":"Kamila Farha Ilmi","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":28},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1336,"name":"Azzizah Lucita Zaviera","contingent":"Surabaya A","registration_id":71,"match_number_id":28},"athlete2":{"id":1220,"name":"Naysila Cinta Aulia","contingent":"Banyuwangi","registration_id":73,"match_number_id":28},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false}],[{"athlete1":{"id":1274,"name":"arum shelvia fitri","contingent":"JOMBANG","registration_id":61,"match_number_id":28},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"ranked","rank":3},"is_bye":false},{"athlete1":{"id":1167,"name":"Kamila Farha Ilmi","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":28},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"ranked","rank":4},"is_bye":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ranked","rank":1},"loser_next":{"bracket":"ranked","rank":2},"is_bye":false}]]},"lower_bracket":{"rounds":[]},"grand_final":null,"juara":[],"type":"single_elimination","total_athletes":6}	2026-06-12 14:30:42	f	\N	\N	RB15
38	Randori 55Kg eksebisi	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1233,"name":"Mohammad Agung Adi Saputra","contingent":"Banyuwangi","registration_id":73,"match_number_id":38},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1233,"name":"Mohammad Agung Adi Saputra","contingent":"Banyuwangi","registration_id":73,"match_number_id":38},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1172,"name":"Prayogie Al Dino","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":38},"athlete2":{"id":1358,"name":"Muhammad Faza Nabhani","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":38},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1233,"name":"Mohammad Agung Adi Saputra","contingent":"Banyuwangi","registration_id":73,"match_number_id":38},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:43	f	\N	\N	D4
21	Randori 65Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_size":8,"upper_bracket":{"rounds":[[{"athlete1":{"id":1209,"name":"MUHARROR MADANI ","contingent":"BANGKALAN B","registration_id":63,"match_number_id":21},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1209,"name":"MUHARROR MADANI ","contingent":"BANGKALAN B","registration_id":63,"match_number_id":21},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1338,"name":"Desta Rizky Syahputra","contingent":"Surabaya A","registration_id":71,"match_number_id":21},"athlete2":{"id":1242,"name":"BUSHIDO AJIE SAHPUTRA","contingent":"TUBAN","registration_id":65,"match_number_id":21},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false},{"athlete1":{"id":1204,"name":"Arga Kusuma R.S.M.Y","contingent":"JOMBANG","registration_id":61,"match_number_id":21},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1204,"name":"Arga Kusuma R.S.M.Y","contingent":"JOMBANG","registration_id":61,"match_number_id":21},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1175,"name":"FAWWAZ AGRIYA PUTRA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":21},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1175,"name":"FAWWAZ AGRIYA PUTRA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":21},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":true}],[{"athlete1":{"id":1209,"name":"MUHARROR MADANI ","contingent":"BANGKALAN B","registration_id":63,"match_number_id":21},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"ranked","rank":3},"is_bye":false},{"athlete1":{"id":1204,"name":"Arga Kusuma R.S.M.Y","contingent":"JOMBANG","registration_id":61,"match_number_id":21},"athlete2":{"id":1175,"name":"FAWWAZ AGRIYA PUTRA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":21},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"ranked","rank":4},"is_bye":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ranked","rank":1},"loser_next":{"bracket":"ranked","rank":2},"is_bye":false}]]},"lower_bracket":{"rounds":[]},"grand_final":null,"juara":[],"type":"single_elimination","total_athletes":5}	2026-06-12 14:30:42	f	\N	\N	RB8
46	Randori 55Kg	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1171,"name":"Dzurotul Aini","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":46},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1171,"name":"Dzurotul Aini","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":46},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1268,"name":"ZULVA AINUN ZASKIA ","contingent":"JOMBANG","registration_id":61,"match_number_id":46},"athlete2":{"id":1223,"name":"Dinda Nofitasari","contingent":"Banyuwangi","registration_id":73,"match_number_id":46},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1171,"name":"Dzurotul Aini","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":46},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:44	f	\N	\N	D12
53	Embu tandoku yudansha eksebisi (Putri)	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	\N	\N	f	\N	\N	D16-F
47	Randori 60Kg	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	\N	\N	f	\N	\N	D13
48	Randori 65Kg	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	\N	\N	f	\N	\N	D14
10	Embu Pasangan Kyu kenshi eksebisi (Campuran)	2	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":9,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":71,"contingent":"Surabaya A"},{"order":5,"registration_id":71,"contingent":"Surabaya A"},{"order":6,"registration_id":70,"contingent":"Surabaya B"},{"order":7,"registration_id":65,"contingent":"TUBAN"},{"order":8,"registration_id":65,"contingent":"TUBAN"},{"order":9,"registration_id":69,"contingent":"Surabaya C"}]}}	2026-06-12 14:20:33	f	\N	\N	RA3-M
12	Embu Beregu eksebisi (Putri)	4	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:33	f	\N	\N	RA4-F
7	Embu Tandoku Kyu kenshi eksebisi	1	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":6,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":70,"contingent":"Surabaya B"},{"order":2,"registration_id":65,"contingent":"TUBAN"},{"order":3,"registration_id":69,"contingent":"Surabaya C"},{"order":4,"registration_id":61,"contingent":"JOMBANG"},{"order":5,"registration_id":71,"contingent":"Surabaya A"},{"order":6,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:33	f	\N	\N	RA2
9	Embu Pasangan Kyu kenshi eksebisi (Putri)	2	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":9,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":71,"contingent":"Surabaya A"},{"order":5,"registration_id":71,"contingent":"Surabaya A"},{"order":6,"registration_id":70,"contingent":"Surabaya B"},{"order":7,"registration_id":65,"contingent":"TUBAN"},{"order":8,"registration_id":65,"contingent":"TUBAN"},{"order":9,"registration_id":69,"contingent":"Surabaya C"}]}}	2026-06-12 14:20:33	f	\N	\N	RA3-F
11	Embu Beregu eksebisi (Putra)	4	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:33	f	\N	\N	RA4-M
13	Embu Beregu eksebisi (Campuran)	8	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:33	f	\N	\N	RA4-M
6	embu tandoku kyu kenshi eksebisi	1	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":7,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":65,"contingent":"TUBAN"},{"order":2,"registration_id":70,"contingent":"Surabaya B"},{"order":3,"registration_id":61,"contingent":"JOMBANG"},{"order":4,"registration_id":73,"contingent":"Banyuwangi"},{"order":5,"registration_id":62,"contingent":"BANGKALAN A"},{"order":6,"registration_id":71,"contingent":"Surabaya A"},{"order":7,"registration_id":65,"contingent":"TUBAN"}]}}	2026-06-12 14:20:33	f	\N	\N	RA1
1	Embu Tandoku kyu kenshi eksebisi	1	1	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":5,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":70,"contingent":"Surabaya B"},{"order":4,"registration_id":71,"contingent":"Surabaya A"},{"order":5,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:32	f	\N	\N	P1
4	Embu Pasangan Kyu kenshi eksebisi (Putra)	2	1	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":5,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":69,"contingent":"Surabaya C"},{"order":2,"registration_id":71,"contingent":"Surabaya A"},{"order":3,"registration_id":70,"contingent":"Surabaya B"},{"order":4,"registration_id":68,"contingent":"Surabaya D"},{"order":5,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	P4-M
51	Embu Pasangan Kyu kenshi (Campuran)	2	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":62,"contingent":"BANGKALAN A"},{"order":2,"registration_id":73,"contingent":"Banyuwangi"},{"order":3,"registration_id":71,"contingent":"Surabaya A"}]}}	2026-06-12 14:20:34	f	\N	\N	D15-M
18	Randori 50Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1232,"name":"Daniel Marsenda","contingent":"Banyuwangi","registration_id":73,"match_number_id":18},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1232,"name":"Daniel Marsenda","contingent":"Banyuwangi","registration_id":73,"match_number_id":18},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1200,"name":"M Qaishar Mirza Athariz","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":18},"athlete2":{"id":1284,"name":"RISQI EKA ERNADI","contingent":"JOMBANG","registration_id":61,"match_number_id":18},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1232,"name":"Daniel Marsenda","contingent":"Banyuwangi","registration_id":73,"match_number_id":18},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:41	f	\N	\N	RB5
19	Randori 55Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":4,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1210,"name":"ANDHIKA RIDHO MUTTAQIN ","contingent":"BANGKALAN B","registration_id":63,"match_number_id":19},"athlete2":{"id":1337,"name":"GIOVANNI PUTRA HARMAWAN","contingent":"Surabaya A","registration_id":71,"match_number_id":19},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":false,"is_prelim":false},{"athlete1":{"id":1230,"name":"David Wisnu Sudarsono ","contingent":"Banyuwangi","registration_id":73,"match_number_id":19},"athlete2":{"id":1282,"name":"MUHAMMAD SAMUDERA GENETIKA SULFAN","contingent":"JOMBANG","registration_id":61,"match_number_id":19},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:41	f	\N	\N	RB6
27	Randori 45Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_size":8,"upper_bracket":{"rounds":[[{"athlete1":{"id":1322,"name":"Abygael Kanaia Aditomo","contingent":"Surabaya B","registration_id":70,"match_number_id":27},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1322,"name":"Abygael Kanaia Aditomo","contingent":"Surabaya B","registration_id":70,"match_number_id":27},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1217,"name":"NAILA","contingent":"BANGKALAN A","registration_id":62,"match_number_id":27},"athlete2":{"id":1168,"name":"Devina Wahyu Ramadhani ","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":27},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false},{"athlete1":{"id":1270,"name":"Meysha Aulia Josephine Mahardika","contingent":"JOMBANG","registration_id":61,"match_number_id":27},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1270,"name":"Meysha Aulia Josephine Mahardika","contingent":"JOMBANG","registration_id":61,"match_number_id":27},"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":true},{"athlete1":{"id":1190,"name":"Anisa Zhafira Ilma","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":27},"athlete2":{"id":1343,"name":"Annisa Khansa Jamiah","contingent":"Surabaya A","registration_id":71,"match_number_id":27},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":1,"slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false}],[{"athlete1":{"id":1322,"name":"Abygael Kanaia Aditomo","contingent":"Surabaya B","registration_id":70,"match_number_id":27},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"ranked","rank":3},"is_bye":false},{"athlete1":{"id":1270,"name":"Meysha Aulia Josephine Mahardika","contingent":"JOMBANG","registration_id":61,"match_number_id":27},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":2,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"ranked","rank":4},"is_bye":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"ranked","rank":1},"loser_next":{"bracket":"ranked","rank":2},"is_bye":false}]]},"lower_bracket":{"rounds":[]},"grand_final":null,"juara":[],"type":"single_elimination","total_athletes":6}	2026-06-12 14:30:42	f	\N	\N	RB14
17	Randori 45Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":4,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1156,"name":"RADITYA KRESNA WAHYU WISNU SAPUTRA","contingent":"KOTA KEDIRI","registration_id":59,"match_number_id":17},"athlete2":{"id":1277,"name":"Ganendra Waradana Prayuda","contingent":"JOMBANG","registration_id":61,"match_number_id":17},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":false,"is_prelim":false},{"athlete1":{"id":1215,"name":"SYAIFUL ROHMAN ","contingent":"BANGKALAN A","registration_id":62,"match_number_id":17},"athlete2":{"id":1320,"name":"Fariz Fernando","contingent":"Surabaya B","registration_id":70,"match_number_id":17},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:41	f	\N	\N	RB4
15	embu tandoku kyu 2/1	1	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":4,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":71,"contingent":"Surabaya A"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:34	f	\N	\N	RB2
26	embu pasangan kyu Kenshi eksebisi	2	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":6,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":73,"contingent":"Banyuwangi"},{"order":2,"registration_id":60,"contingent":"Kabupaten Pasuruan"},{"order":3,"registration_id":71,"contingent":"Surabaya A"},{"order":4,"registration_id":63,"contingent":"BANGKALAN B"},{"order":5,"registration_id":61,"contingent":"JOMBANG"},{"order":6,"registration_id":70,"contingent":"Surabaya B"}]}}	2026-06-12 14:20:34	f	\N	\N	RB13
49	Embu Pasangan Kyu kenshi (Putra)	2	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":62,"contingent":"BANGKALAN A"},{"order":2,"registration_id":73,"contingent":"Banyuwangi"},{"order":3,"registration_id":71,"contingent":"Surabaya A"}]}}	2026-06-12 14:20:34	f	\N	\N	D15-M
52	Embu tandoku yudansha eksebisi (Putra)	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	\N	\N	f	\N	\N	D16-M
23	Randori >70Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1347,"name":"Delon Nathanael Tandio","contingent":"Surabaya B","registration_id":70,"match_number_id":23},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1347,"name":"Delon Nathanael Tandio","contingent":"Surabaya B","registration_id":70,"match_number_id":23},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1340,"name":"Alexander Troy Moeljono ","contingent":"Surabaya A","registration_id":71,"match_number_id":23},"athlete2":{"id":1300,"name":"JADON DAFFIN JANUADRI","contingent":"Surabaya D","registration_id":68,"match_number_id":23},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1347,"name":"Delon Nathanael Tandio","contingent":"Surabaya B","registration_id":70,"match_number_id":23},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:42	f	\N	\N	RB10
14	embu tandoku kyu 4/3	1	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":5,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":73,"contingent":"Banyuwangi"},{"order":4,"registration_id":61,"contingent":"JOMBANG"},{"order":5,"registration_id":69,"contingent":"Surabaya C"}]}}	2026-06-12 14:20:34	f	\N	\N	RB1
45	Randori 50Kg	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":3,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1170,"name":"Amanda Aisyah Ramadanni ","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":45},"athlete2":{"id":"BYE","name":"BYE","contingent":"-"},"winner":"athlete1","winner_data":{"id":1170,"name":"Amanda Aisyah Ramadanni ","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":45},"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":true,"is_prelim":false},{"athlete1":{"id":1224,"name":"Bellia Aprilia","contingent":"Banyuwangi","registration_id":73,"match_number_id":45},"athlete2":{"id":1213,"name":"ASTRIT RABECA YOLANDA","contingent":"BANGKALAN A","registration_id":62,"match_number_id":45},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":{"id":1170,"name":"Amanda Aisyah Ramadanni ","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":45},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":{"id":"BYE","name":"BYE","contingent":"-"},"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:44	f	\N	\N	D11
8	Embu Pasangan Kyu kenshi eksebisi (Putra)	2	2	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	{"total_entries":9,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":71,"contingent":"Surabaya A"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":65,"contingent":"TUBAN"},{"order":4,"registration_id":71,"contingent":"Surabaya A"},{"order":5,"registration_id":71,"contingent":"Surabaya A"},{"order":6,"registration_id":70,"contingent":"Surabaya B"},{"order":7,"registration_id":65,"contingent":"TUBAN"},{"order":8,"registration_id":65,"contingent":"TUBAN"},{"order":9,"registration_id":69,"contingent":"Surabaya C"}]}}	2026-06-12 14:20:33	f	\N	\N	RA3-M
31	Embu Pasangan Kyu kenshi eksebisi	2	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":70,"contingent":"Surabaya B"},{"order":2,"registration_id":69,"contingent":"Surabaya C"},{"order":3,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	RB18
34	Embu Beregu eksebisi (Campuran)	8	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Mix	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	RB19-M
33	Embu Beregu eksebisi (Putri)	8	3	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":61,"contingent":"JOMBANG"},{"order":2,"registration_id":61,"contingent":"JOMBANG"},{"order":3,"registration_id":61,"contingent":"JOMBANG"}]}}	2026-06-12 14:20:33	f	\N	\N	RB19-F
39	Randori 60Kg eksebisi	1	4	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	\N	\N	f	\N	\N	D5
42	embu tandoku kyu 3/2 eksebisi	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"total_entries":3,"format":"2_babak","pool_count":1,"description":"2 Babak (Penyisihan + Final)","pools":{"POOL 1":[{"order":1,"registration_id":73,"contingent":"Banyuwangi"},{"order":2,"registration_id":73,"contingent":"Banyuwangi"},{"order":3,"registration_id":60,"contingent":"Kabupaten Pasuruan"}]}}	2026-06-12 14:20:35	f	\N	\N	D8
29	Randori 55Kg	1	3	randori	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Female	{"bracket_type":"double_elimination","bracket_size":4,"total_athletes":4,"has_preliminary":false,"upper_bracket":{"rounds":[[{"athlete1":{"id":1273,"name":"Ochie Dila Aurelia ","contingent":"JOMBANG","registration_id":61,"match_number_id":29},"athlete2":{"id":1323,"name":"QUENASHA GENDHIS GUPITA","contingent":"Surabaya B","registration_id":70,"match_number_id":29},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete1"},"is_bye":false,"is_prelim":false},{"athlete1":{"id":1225,"name":"Yetania Vita Zabrina","contingent":"Banyuwangi","registration_id":73,"match_number_id":29},"athlete2":{"id":1166,"name":"Icha Sazira Fitriani ","contingent":"Kabupaten Pasuruan","registration_id":60,"match_number_id":29},"winner":null,"winner_data":null,"winner_next":{"bracket":"ub","round":1,"match":0,"slot":"athlete2"},"loser_next":{"bracket":"lb","round":0,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete1"},"loser_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete2"},"is_bye":false,"is_prelim":false}]]},"lower_bracket":{"rounds":[[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"lb","round":1,"match":0,"slot":"athlete1"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}],[{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null,"winner_next":{"bracket":"gf","slot":"athlete2"},"loser_next":{"bracket":"eliminated"},"is_bye":false,"is_prelim":false}]]},"grand_final":{"athlete1":null,"athlete2":null,"winner":null,"winner_data":null},"juara":[],"type":"double_elimination"}	2026-06-12 14:30:42	f	\N	\N	RB16
36	embu tandoku kyu 1	1	4	embu	1	2026-05-16 15:47:44	2026-06-12 14:41:43	Male	\N	\N	f	\N	\N	D2
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_04_01_042410_create_categories_table	1
5	2026_04_01_042411_create_contingents_table	1
6	2026_04_01_042412_create_athletes_table	1
7	2026_04_01_042421_create_athlete_category_table	1
8	2026_04_01_044622_add_achievement_history_to_athletes_table	1
9	2026_04_01_045145_create_galleries_table	1
10	2026_04_01_045145_create_posts_table	1
11	2026_04_01_060858_add_nik_to_athletes_table	1
12	2026_04_01_065143_add_advanced_fields_to_athletes_table	1
13	2026_04_01_065143_add_payment_fields_to_contingents_table	1
14	2026_04_01_065143_create_officials_table	1
15	2026_04_01_065633_add_match_type_to_athletes_table	1
16	2026_04_01_070338_add_match_type_to_categories_table	1
17	2026_04_01_070338_create_kyu_levels_table	1
18	2026_04_01_085512_update_tables_for_kempo_2026	1
19	2026_04_02_022218_add_missing_fields_to_contingents_table	1
20	2026_04_02_022656_adjust_athletes_table_fields	1
21	2026_04_02_024855_create_permission_tables	1
22	2026_04_02_025714_add_verification_fields_to_contingents_table	1
23	2026_04_09_000000_create_referees_table	1
24	2026_04_09_141602_create_age_groups_table	1
25	2026_04_09_141714_create_weight_groups_table	1
26	2026_04_09_225628_create_techniques_table	1
27	2026_04_09_225820_create_match_numbers_table	1
28	2026_04_11_160439_add_user_id_to_contingents_table	1
29	2026_04_11_160943_create_registrations_table	1
30	2026_04_11_161004_update_athletes_and_officials_to_registrations	1
31	2026_04_11_161021_cleanup_contingents_table	1
32	2026_04_11_161913_create_registration_participation_tables	1
33	2026_04_11_215100_create_athlete_contingent_histories_table	1
34	2026_04_12_192500_create_athlete_contingent_table	1
35	2026_04_12_200000_add_weight_group_id_to_registration_athlete_table	1
36	2026_04_12_212400_add_gender_to_match_numbers_table	1
37	2026_04_12_212500_create_athlete_match_number_table	1
38	2026_04_12_221000_add_technique_ids_to_athlete_match_number_table	1
39	2026_04_13_035301_create_rundowns_table	1
40	2026_04_13_043347_create_courts_table	1
41	2026_04_13_160130_add_biodata_fields_to_athletes_table	1
42	2026_04_13_170458_create_pools_table	1
43	2026_04_14_062113_add_drawing_data_to_match_numbers_table	1
44	2026_04_14_090130_create_match_number_referee_table	1
45	2026_04_14_094806_create_embu_scores_table	1
46	2026_04_14_094806_create_randori_match_results_table	1
47	2026_04_14_102546_add_active_match_fields_to_match_numbers_and_pivot	1
48	2026_04_14_104123_create_referee_score_details_table	1
49	2026_04_14_104833_add_active_registration_id_to_match_numbers	1
50	2026_04_15_163000_create_drawing_match_numbers_table	1
51	2026_04_17_105000_create_session_times_table	1
52	2026_04_17_110000_add_schedule_fields_to_drawing_match_numbers_table	1
53	2026_04_18_122600_create_schedule_referees_table	1
54	2026_04_19_001204_add_active_state_to_courts_table	1
55	2026_04_19_020300_add_tiebreak_fields_to_embu_scores_table	1
56	2026_04_19_021500_add_bracket_node_to_randori_match_results	1
57	2026_04_19_100146_add_active_drawing_id_to_courts_table	1
58	2026_04_20_040041_create_embu_champions_table	1
59	2026_04_20_080908_create_randori_judge_scores_table	1
60	2026_04_25_075725_add_kyu_level_id_to_techniques_table	1
61	2026_04_26_154248_add_metadata_to_randori_match_results	1
62	2026_04_27_092330_create_tournament_results_table	1
63	2026_04_27_092814_alter_tournament_results_text_columns	1
64	2026_04_28_140220_create_payment_methds_table	1
65	2026_04_29_131511_create_active_court_referees_table	1
66	2026_05_07_160746_add_match_id_to_match_numbers_table	1
67	2026_05_11_123903_make_registration_id_nullable_in_drawing_match_numbers_table	1
68	2026_05_12_074940_add_draft_data_to_registrations_table	1
69	2026_05_12_152546_add_dojo_origin_to_athletes_table	1
70	2026_05_13_030311_add_nik_kenshi_to_athletes_table	1
71	2026_05_14_133102_add_unified_match_number_id_to_match_numbers_table	1
72	2026_05_14_134019_create_match_number_merges_table	1
73	2026_05_14_164126_remove_unique_from_embu_champions	1
74	2026_05_15_063745_add_judge_index_to_users_table	1
75	2026_05_16_140455_add_drawing_id_to_embu_scores_table	1
76	2026_05_16_143451_add_drawing_id_to_embu_champions_table	1
77	2026_06_04_133346_create_referee_observations_table	2
78	2026_06_06_075741_add_signature_to_referee_score_details_table	3
79	2026_06_07_081024_create_schedule_paniteras_table	4
80	2026_06_09_034238_add_athlete_status_to_registrations_table	5
81	2026_06_09_151911_add_waktu_to_embu_scores_table	6
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	1
5	App\\Models\\User	6
5	App\\Models\\User	7
5	App\\Models\\User	8
5	App\\Models\\User	9
5	App\\Models\\User	10
5	App\\Models\\User	11
5	App\\Models\\User	12
5	App\\Models\\User	13
5	App\\Models\\User	14
5	App\\Models\\User	15
5	App\\Models\\User	16
5	App\\Models\\User	17
5	App\\Models\\User	18
5	App\\Models\\User	19
5	App\\Models\\User	20
5	App\\Models\\User	21
5	App\\Models\\User	22
5	App\\Models\\User	23
5	App\\Models\\User	24
5	App\\Models\\User	25
8	App\\Models\\User	28
9	App\\Models\\User	30
9	App\\Models\\User	31
9	App\\Models\\User	32
9	App\\Models\\User	33
9	App\\Models\\User	34
9	App\\Models\\User	35
9	App\\Models\\User	36
9	App\\Models\\User	37
9	App\\Models\\User	38
9	App\\Models\\User	39
10	App\\Models\\User	40
1	App\\Models\\User	2
10	App\\Models\\User	42
10	App\\Models\\User	43
10	App\\Models\\User	44
8	App\\Models\\User	45
8	App\\Models\\User	46
8	App\\Models\\User	47
8	App\\Models\\User	48
8	App\\Models\\User	49
8	App\\Models\\User	50
8	App\\Models\\User	51
8	App\\Models\\User	52
8	App\\Models\\User	53
8	App\\Models\\User	54
8	App\\Models\\User	55
8	App\\Models\\User	56
8	App\\Models\\User	57
8	App\\Models\\User	58
8	App\\Models\\User	59
3	App\\Models\\User	3
4	App\\Models\\User	4
6	App\\Models\\User	26
7	App\\Models\\User	27
11	App\\Models\\User	41
11	App\\Models\\User	61
11	App\\Models\\User	62
11	App\\Models\\User	63
11	App\\Models\\User	64
11	App\\Models\\User	65
11	App\\Models\\User	66
11	App\\Models\\User	67
11	App\\Models\\User	68
11	App\\Models\\User	69
11	App\\Models\\User	70
11	App\\Models\\User	71
11	App\\Models\\User	72
11	App\\Models\\User	73
11	App\\Models\\User	74
11	App\\Models\\User	75
8	App\\Models\\User	82
8	App\\Models\\User	83
8	App\\Models\\User	84
8	App\\Models\\User	85
8	App\\Models\\User	86
8	App\\Models\\User	87
8	App\\Models\\User	88
8	App\\Models\\User	89
8	App\\Models\\User	90
8	App\\Models\\User	91
8	App\\Models\\User	92
8	App\\Models\\User	93
8	App\\Models\\User	94
8	App\\Models\\User	95
8	App\\Models\\User	96
8	App\\Models\\User	97
6	App\\Models\\User	111
8	App\\Models\\User	141
8	App\\Models\\User	143
5	App\\Models\\User	145
5	App\\Models\\User	146
5	App\\Models\\User	147
5	App\\Models\\User	148
5	App\\Models\\User	149
5	App\\Models\\User	150
5	App\\Models\\User	151
5	App\\Models\\User	152
5	App\\Models\\User	153
5	App\\Models\\User	154
5	App\\Models\\User	155
5	App\\Models\\User	156
5	App\\Models\\User	157
5	App\\Models\\User	158
5	App\\Models\\User	159
5	App\\Models\\User	160
5	App\\Models\\User	161
7	App\\Models\\User	98
7	App\\Models\\User	99
7	App\\Models\\User	100
9	App\\Models\\User	101
9	App\\Models\\User	105
6	App\\Models\\User	106
6	App\\Models\\User	107
6	App\\Models\\User	109
6	App\\Models\\User	112
6	App\\Models\\User	113
6	App\\Models\\User	114
6	App\\Models\\User	115
6	App\\Models\\User	116
6	App\\Models\\User	118
6	App\\Models\\User	119
6	App\\Models\\User	120
6	App\\Models\\User	121
6	App\\Models\\User	122
6	App\\Models\\User	123
6	App\\Models\\User	124
6	App\\Models\\User	125
6	App\\Models\\User	126
6	App\\Models\\User	140
6	App\\Models\\User	128
6	App\\Models\\User	129
6	App\\Models\\User	130
6	App\\Models\\User	131
6	App\\Models\\User	132
6	App\\Models\\User	133
6	App\\Models\\User	134
6	App\\Models\\User	135
6	App\\Models\\User	136
6	App\\Models\\User	137
6	App\\Models\\User	164
7	App\\Models\\User	163
9	App\\Models\\User	165
6	App\\Models\\User	166
5	App\\Models\\User	167
10	App\\Models\\User	168
11	App\\Models\\User	169
11	App\\Models\\User	170
11	App\\Models\\User	171
11	App\\Models\\User	172
11	App\\Models\\User	173
\.


--
-- Data for Name: officials; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.officials (id, contingent_id, name, role, phone, created_at, updated_at) FROM stdin;
2	16	Cecep Sunariya	Pelatih	085790399330	2026-05-17 07:44:33	2026-05-17 07:44:33
3	16	Bagus Sapro'in	Pelatih		2026-05-18 02:31:13	2026-05-18 02:31:13
4	17	Yulia Ainur Rahma	Official	089515662704	2026-05-18 09:28:16	2026-05-18 09:28:16
5	17	Khoirul Syah Saputra	Official	082257324758	2026-05-18 09:28:16	2026-05-18 09:28:16
6	22	Asat Musthofa	Official	085785811888	2026-05-18 17:01:40	2026-05-18 17:03:15
7	22	Agus Wahab Suprianto	Official	085706435473	2026-05-18 17:05:32	2026-05-18 17:05:32
8	22	Naufar Wildan Gresika	Official	081266579827	2026-05-18 17:05:32	2026-05-18 17:05:32
11	25	AHMAD SYAIFUDIN	Official	087865609230	2026-05-19 08:29:43	2026-05-19 08:29:43
9	24	Santoso	Manager	085336059460	2026-05-19 03:01:57	2026-05-20 12:24:28
13	18	MALIK	Official	081332718533	2026-05-20 11:13:14	2026-05-20 13:33:01
14	18	RACHMAD	Official	081244283071	2026-05-20 11:13:14	2026-05-20 13:33:01
10	25	ISNAINI RAHMAN	Manajer Tim	082229223414	2026-05-19 08:28:33	2026-05-20 14:47:14
17	33	APRILIA HANA PRATIWI	Manager		2026-05-24 06:15:17	2026-05-24 06:15:17
18	33	APRILIA HANA PRATIWI	Manajer Tim	081335338336	2026-05-24 06:41:09	2026-05-24 06:41:09
19	33	RANDI	Official	082245138237	2026-05-24 06:41:09	2026-05-24 06:41:09
21	31	NAVRA NAJMA ALFURROHMAH	Manajer Tim	082141812154	2026-05-25 15:32:35	2026-05-25 15:32:35
22	31	Solider Rintang Perdana	Official	082132401899	2026-05-25 15:32:35	2026-05-25 15:32:35
20	34	Andik Subakti	Official	085731795333	2026-05-24 23:30:33	2026-05-25 16:10:10
23	30	Fishca Deanita Mabilaka	Official	085895981540	2026-05-26 04:37:49	2026-05-26 04:37:49
26	27	Muhammad Nurrahman Bathik	Official	081252266544	2026-05-26 04:41:36	2026-05-26 04:41:36
28	28	Agung Yongki F	Official	085178433594	2026-05-26 04:42:55	2026-05-26 04:42:55
30	29	Stephanie Natania	Official	082233999319	2026-05-26 04:44:21	2026-05-26 04:44:21
31	27	Esa Jati Wicaksono	Official	081233404190	2026-05-26 10:46:30	2026-05-26 10:46:30
24	30	Cindy Febriyanti	Manajer Tim	082264444221	2026-05-26 04:38:18	2026-05-27 14:08:38
29	29	Rendy Andhika Widyanto	Manajer Tim	087701918534	2026-05-26 04:43:43	2026-05-27 14:27:12
27	28	Yosia C Decky	Manajer Tim	081231375198	2026-05-26 04:42:23	2026-05-28 12:09:44
25	27	Bambang Harmawan	Manajer Tim	081938877507	2026-05-26 04:40:57	2026-05-28 14:59:04
32	35	I Ketut Pramantara	Official	082141470867	2026-06-03 05:00:48	2026-06-03 05:00:48
15	24	Nur Imama	Official	085319201132	2026-05-20 12:25:38	2026-06-04 05:50:56
33	22	Abdulloh Faqih Khoironi	Official	089648357474	2026-06-05 01:27:02	2026-06-05 01:27:02
35	40	Sariman Simbolon	Official	081931887871	2026-06-08 14:23:27	2026-06-08 14:23:27
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payment_methods; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payment_methods (id, name, account_number, bank, logo, "order", is_active, created_at, updated_at) FROM stdin;
1	Tunai	\N	Tunai	\N	0	t	2026-05-16 15:47:57	2026-05-16 15:47:57
2	KONI Kabupaten Bekasi	0705667627	BNI	\N	0	t	2026-05-16 15:47:57	2026-05-16 15:47:57
3	KONI Kabupaten Bekasi	0705667627	BRI	\N	0	t	2026-05-16 15:47:57	2026-05-16 15:47:57
4	KONI Kabupaten Bekasi	0705667627	Mandiri	\N	0	t	2026-05-16 15:47:57	2026-05-16 15:47:57
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: pools; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pools (id, name, "order", created_at, updated_at) FROM stdin;
1	Pool 1	1	2026-05-16 15:47:39	2026-05-16 15:47:39
2	Pool 2	2	2026-05-16 15:47:39	2026-05-16 15:47:39
3	Pool 3	3	2026-05-16 15:47:39	2026-05-16 15:47:39
4	Pool 4	4	2026-05-16 15:47:39	2026-05-16 15:47:39
5	POOL 1	1	2026-05-17 02:42:54	2026-05-17 02:42:54
6	POOL A	1	2026-05-17 02:42:55	2026-05-17 02:42:55
7	POOL B	2	2026-05-17 02:42:55	2026-05-17 02:42:55
8	POOL C	3	2026-06-09 13:29:23	2026-06-09 13:29:23
\.


--
-- Data for Name: posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.posts (id, title, slug, content, image_url, published_at, created_at, updated_at) FROM stdin;
1	Et saepe fugit molestiae labore.	et-saepe-fugit-molestiae-labore	Ut ipsam praesentium consequatur dolorum voluptatem dolorum. Qui omnis id odio dolorem.\n\nId fugiat sequi molestiae pariatur ullam eos sit. Veniam distinctio id voluptas sit rerum aut dolores. Mollitia vero doloremque optio dicta sint assumenda nemo. Totam ducimus minima laudantium laudantium molestiae quod vero.\n\nSuscipit in vel deserunt ipsa. Esse asperiores at dolores occaecati quis est. Et quo dolores molestias autem rerum qui voluptatibus. Qui laudantium alias officia nobis ipsam excepturi est.	https://picsum.photos/seed/92/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
2	Eum neque assumenda laboriosam accusantium explicabo voluptatibus.	eum-neque-assumenda-laboriosam-accusantium-explicabo-voluptatibus	Quae libero quasi facere. Quia officiis placeat voluptatem et est cum. Excepturi dolor et adipisci quod. Et ut sapiente asperiores maxime cupiditate tenetur.\n\nVelit non eaque quo quia ut. Maxime quaerat unde quam quia. Quo vel explicabo laboriosam magnam aliquam officiis similique ex.\n\nMagni occaecati dolorum quo aut assumenda laudantium natus. Ut sunt consequatur sequi sint. Esse eum omnis libero et debitis.	https://picsum.photos/seed/28/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
3	Ex architecto fugit et vel aperiam.	ex-architecto-fugit-et-vel-aperiam	Voluptas quia molestias iure aut beatae facere temporibus. Neque aliquid expedita corporis voluptates. Officia est voluptates quia iste est. Quia dolores eos occaecati quis.\n\nLaboriosam aut maiores in qui deleniti. Soluta quas non ullam ea et sed veritatis. Quis illum ullam esse et id rerum similique magni.\n\nDicta itaque veritatis sit optio. Reprehenderit dignissimos sequi ex qui rerum consequatur. Aliquid dolores voluptatem harum dolor vitae quis minima. Reprehenderit facere expedita earum porro et illo blanditiis nostrum.	https://picsum.photos/seed/90/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
4	Necessitatibus excepturi error non voluptatem nulla.	necessitatibus-excepturi-error-non-voluptatem-nulla	Maiores et officiis eligendi commodi. Vitae dolores dignissimos id dolorem. Aspernatur animi qui facere. Consequuntur ea qui est ad culpa.\n\nPerferendis non minima labore quia. Sint voluptas eum similique enim facere iure labore. Velit illum neque quibusdam tenetur modi mollitia.\n\nIncidunt doloremque ab dolorem aliquam velit. Minus est quis error inventore et iste. Quisquam culpa amet in voluptas. Mollitia aspernatur quisquam quo qui.	https://picsum.photos/seed/71/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
5	Nisi numquam distinctio quam.	nisi-numquam-distinctio-quam	Ut exercitationem at eos quis dolores facilis eum. Asperiores suscipit dolorem similique velit aliquid dolorum. Assumenda at vitae aut porro.\n\nUt sit ipsum hic vel accusamus minus qui laudantium. Animi quibusdam aliquid non in ducimus non et. Numquam qui et officia vitae molestiae cupiditate. Facere quasi similique consequatur consequuntur et aut dolores.\n\nOfficia velit non quos id amet. Est aut occaecati voluptates dolorum. Eaque minus nisi inventore corporis. Rerum quaerat vel doloribus iure.	https://picsum.photos/seed/53/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
6	Voluptas nihil nam qui saepe illo voluptas corporis est.	voluptas-nihil-nam-qui-saepe-illo-voluptas-corporis-est	Et praesentium consectetur recusandae aliquid quam voluptatem. Eius sed veritatis accusantium quia voluptas sit sit. Ab ut id reprehenderit eum quia quod qui provident.\n\nPorro aut tempora officiis maiores voluptatem. Vel blanditiis optio sunt neque inventore officia facere rerum. Quae est veniam accusantium nulla accusantium temporibus similique.\n\nMolestiae earum necessitatibus porro dolorum fugit. Beatae praesentium omnis veritatis sunt. Autem ullam laboriosam et aperiam qui nihil. Laborum alias expedita voluptas quae.	https://picsum.photos/seed/99/800/400	2026-05-16 15:47:25	2026-05-16 15:47:25	2026-05-16 15:47:25
\.


--
-- Data for Name: randori_judge_scores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.randori_judge_scores (id, match_number_id, bracket_node, judge_index, waza_ari_aka, ippon_aka, hansoku_aka, waza_ari_shiro, ippon_shiro, hansoku_shiro, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: randori_match_results; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.randori_match_results (id, match_number_id, bracket_node_index, winner_athlete_id, winner_color, score_red, score_blue, created_at, updated_at, bracket_node, bracket_section, metadata) FROM stdin;
\.


--
-- Data for Name: referee_observations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.referee_observations (id, contingent_id, referee_id, observer_name, observation_date, court, round, match_time, referee_number, contingent_away, contingent_home, total_score, category, kepada, dari, tanggal_laporan, kelebihan, area_perbaikan, rekomendasi, data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: referee_score_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.referee_score_details (id, match_number_id, referee_id, judge_index, scorable_type, scorable_id, details, total_calculated_score, notes, created_at, updated_at, signature) FROM stdin;
\.


--
-- Data for Name: referees; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.referees (id, user_id, nik, phone, gender, birth_place, birth_date, address, certification_level, license_number, province, city, photo, created_at, updated_at) FROM stdin;
20	111	\N	081332037245	\N	\N	\N	Malang Kota\n	WASIT DAERAH	010/WST-DRH/VII/2024	Jawa Timur	Malang Kota	\N	2026-05-24 02:16:15	2026-05-24 02:16:15
7	98	69.1.13.03.99.001	08123399479	L	\N	\N	Malang Kota	WASIT NASIONAL	038/WST-E-NAS/VIII/2015	Jawa Timur	Malang	referees/photos/qB3OyVqHYsEOGGPrsZ8JI2ulYMn1dhHIA0oXGRi5.png	2026-05-24 01:35:01	2026-06-08 14:03:46
8	99	76.1.13.01.04.001	082244450734	L	\N	\N	Surabaya Kota	WASIT NASIONAL	033/WST-NAS/X/2022	Jawa Timur	Surabaya	\N	2026-05-24 01:35:53	2026-06-08 14:03:46
9	100	85.2.13.03.08.001	08123262463	L	\N	\N	Gresik Kab.	WASIT NASIONAL	009/WST-NAS/IX/2025	Jawa Timur	Gresik	\N	2026-05-24 01:36:56	2026-06-08 14:03:46
10	101	69.1.13.03.99.002	08563532994	\N	\N	\N	Malang Kota\n	WASIT DAERAH	015/WST-DRH/VII/2022	Jawa Timur	Malang	referees/photos/E0z5nqQozBDP6yipUIo0UeOA6sNE6tIU5OoTUKyZ.png	2026-05-24 01:38:45	2026-06-08 14:03:46
14	105	70.1.13.01.04.002	081333715634	L	\N	\N	Surabaya Kota\n	WASIT DAERAH	002/WST-E-DRH/VII/2019	Jawa Timur	Surabaya	\N	2026-05-24 02:11:55	2026-06-08 14:03:46
21	112	87.1.13.01.12.002	081233970119	\N	\N	\N	Surabaya Kota\n	WASIT UTAMA	017/WST-DRH/VII/2022	Jawa Timur	Surabaya	referees/photos/nvPsmWmKDaB3c719EP9JAhzJGFIXp0ZD4xnTNzP3.png	2026-05-24 02:16:47	2026-06-09 03:26:18
54	163	74.1.13.01.04.001	081515737182	L	\N	\N	\N	WASIT NASIONAL	012/WST-NAS/VII/2022	Jawa Timur	Surabaya	\N	2026-06-09 03:25:43	2026-06-09 03:25:43
53	165	70.1.13.03.01.001	0818384235	L	\N	\N	Malang Kota\n	WASIT NASIONAL	094/WST-R-NAS/IV/2011	Jawa Timur	Malang	\N	2026-06-08 15:43:40	2026-06-09 03:25:43
15	106	74.1.06.01.01.001	085731065007	L	\N	\N	Surabaya Kota\n	WASIT UTAMA	029/WST-DRH/VII/2022	Jawa Timur	Surabaya	\N	2026-05-24 02:12:40	2026-06-09 03:26:18
16	107	87.2.13.04.04.007	081334119612	\N	\N	\N	Pasuruan Kota\n	WASIT UTAMA	018/WST-DRH/X/2025	Jawa Timur	Kota Pasuruan	\N	2026-05-24 02:13:36	2026-06-09 03:26:18
55	166	88.3.13.03.01.001	081318640007	L	\N	\N	\N	WASIT UTAMA	019/WST-DRH/X/2025	Jawa Timur	Malang	\N	2026-06-09 03:25:43	2026-06-09 03:26:18
18	109	85.1.13.03.01.001	081333586789	\N	\N	\N	Malang Kota\n	WASIT UTAMA	017/WST-DRH/X/2025	Jawa Timur	Kota Malang	\N	2026-05-24 02:15:03	2026-06-09 03:26:18
22	113	90.3.13.01.20.001	087708775440	\N	\N	\N	Surabaya Kota\n	WASIT UTAMA	038/WST-DRH/X/2025	Jawa Timur	Surabaya	referees/photos/wTy39Hjm6H47VF836ISdC5BeckFA6z6XiUmVNEWu.png	2026-05-24 02:17:22	2026-06-09 03:26:18
23	114	90.3.13.11.01.001	081330487143	\N	\N	\N	Bojonegoro Kab.\n	WASIT UTAMA	002/WST-DRH/II/2024	Jawa Timur	Bojonegoro	\N	2026-05-24 02:18:01	2026-06-09 03:26:18
24	115	89.3.13.12.01.001	08113052024	\N	\N	\N	Tuban Kab.\n	WASIT UTAMA	022/WST-DRH/X/2025	Jawa Timur	Tuban	\N	2026-05-24 02:18:49	2026-06-09 03:26:18
25	116	88.3.13.12.01.005	082338152075	\N	\N	\N	Tuban Kab.\n	WASIT UTAMA	026/WST-DRH/VII/2022	Jawa Timur	Tuban	\N	2026-05-24 02:34:35	2026-06-09 03:26:18
28	119	94.2.13.09.01.017	085257111012	\N	\N	\N	Jombang Kab.\n	WASIT	054/WST-DRH/XII/2025	Jawa Timur	Jombang	referees/photos/2LPxqH8fhiCDtdc26QLUrUe2NJkzOqSBsjpY6Zum.png	2026-05-24 02:36:55	2026-06-09 03:26:18
29	120	92.2.13.01.13.004	082234611763	\N	\N	\N	Malang Kota\n	WASIT	023/WST-DRH/X/2025	Jawa Timur	Kota Malang	referees/photos/Hgl0rwuR1PoYIIHx48iKsRAUwihpeVQtO4fEGS1T.png	2026-05-24 02:37:32	2026-06-09 03:26:18
30	121	86.1.13.09.01.003	082141987227	\N	\N	\N	Jombang Kab.\n	WASIT	021/WST-DRH/X/2025	Jawa Timur	Jombang	referees/photos/9LBIji3W9NaP4a8IeJo6aBQ8VHXT5IFP7p5AVsUB.png	2026-05-24 02:38:03	2026-06-09 03:26:18
31	122	87.1.13.01.09.002	082233733490	\N	\N	\N	Surabaya Kota\n	WASIT	001/WST-DRH/II/2024	Jawa Timur	Surabaya	\N	2026-05-24 02:38:35	2026-06-09 03:26:18
33	124	90.3.13.03.01.013	08121710207	\N	\N	\N	Malang Kab.\n	WASIT	032/WST-DRH/X/2025	Jawa Timur	Malang	referees/photos/ek1LQxKO23H1Xlh4gZnumO0Iq2K7gJHJHXgaJDA0.png	2026-05-24 02:39:47	2026-06-09 03:26:18
34	125	85.1.13.01.12.001	08121710207	\N	\N	\N	Surabaya Kota\n	WASIT	035/WST-DRH/X/2025	Jawa Timur	Surabaya	\N	2026-05-24 02:40:21	2026-06-09 03:26:18
27	118	89.3.13.01.12.001	085723231975	L	\N	\N	Surabaya Kota\n	WASIT PEMBANTU	053/WST-R-DRH/III/2001	Jawa Timur	Sidoarjo	\N	2026-05-24 02:36:26	2026-06-09 03:26:18
32	123	04.3.13.08.03.003	081335543353	\N	\N	\N	Sidoarjo Kab.\n	WASIT PEMBANTU	038/WST-R-DRH/X/2016	Jawa Timur	Bangkalan	referees/photos/nbvKOse97l1xEAFCVEp5rY07J9wgxgwhrePIeZRi.png	2026-05-24 02:39:12	2026-06-09 03:26:18
35	126	98.1.13.11.01.001	081949841000	\N	\N	\N	Bojonegoro Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Jombang	\N	2026-05-24 02:40:56	2026-06-09 03:26:18
49	140	17.1.13.03.08.001	082141996307	\N	\N	\N	Kediri Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Kota Kediri	\N	2026-05-24 03:23:18	2026-06-09 03:26:18
37	128	78.1.13.99.99.007	081295221500	\N	\N	\N	Malang Kota\n	WASIT PEMBANTU	-	Jawa Timur	Kota Malang	\N	2026-05-24 02:44:04	2026-06-09 03:26:18
38	129	93.3.13.01.19.012	081247884402	\N	\N	\N	Gresik Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Gresik	\N	2026-05-24 02:44:43	2026-06-09 03:26:18
39	130	07.1.13.10.04.010	085330201169	\N	\N	\N	Jember Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Jember	\N	2026-05-24 03:07:47	2026-06-09 03:26:18
40	131	87.2.13.03.05.001	0818363947	\N	\N	\N	Malang Kota\n	WASIT PEMBANTU	-	Jawa Timur	Sidoarjo	referees/photos/AMqWUj0BrfjgXDs1DpuZZwV2ZBmtIMCndLMdwdFE.png	2026-05-24 03:08:30	2026-06-09 03:26:18
41	132	89.3.13.09.03.001	081388146789	\N	\N	\N	Jombang Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Jombang	referees/photos/BZcB15BSkWq5e2eLZiMRHEBi3GBeWCv0WGE13bBG.png	2026-05-24 03:09:01	2026-06-09 03:26:18
42	133	77.1.02.03.01.003	087849477488	\N	\N	\N	Bangkalan Kab.\n	WASIT PEMBANTU	-	Jawa Timur	Bangkalan	\N	2026-05-24 03:09:28	2026-06-09 03:26:18
43	134	07.1.13.01.24.012	083830248054	\N	\N	\N	Surabaya Kota\n	WASIT PEMBANTU	-	Jawa Timur	Surabaya	referees/photos/HGxkBG16tlQuoVHeE4OPzETv0FMaZk0kDaNRzftJ.png	2026-05-24 03:09:57	2026-06-09 03:26:18
44	135	09.3.13.01.19.002	082142790073	\N	\N	\N	Pasuruan Kota\n	WASIT PEMBANTU	-	Jawa Timur	Kota Pasuruan	\N	2026-05-24 03:10:33	2026-06-09 03:26:18
45	136	98.3.13.01.09.015	085645356694	\N	\N	\N	Surabaya Kota\n	WASIT PEMBANTU	-	Jawa Timur	Surabaya	referees/photos/HaLaVteTILeoMds8k76e8YVTrh4u0SdlqA2NVkmB.png	2026-05-24 03:18:41	2026-06-09 03:26:18
46	137	83.2.13.01.11.001	082141470867	\N	\N	\N	Surabaya Kota\n	WASIT PEMBANTU	-	Jawa Timur	Sidoarjo	\N	2026-05-24 03:19:11	2026-06-09 03:26:18
52	164	90.1.13.11.01.001	085234496587	\N	\N	\N	Bojonegoro Kab.\n	WASIT PEMBANTU	\N	Jawa Timur	Bojonegoro Kab.	\N	2026-06-08 15:42:52	2026-06-09 03:26:18
\.


--
-- Data for Name: registration_athlete; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registration_athlete (id, registration_id, athlete_id, weight, kyu, age_group, rank, match_type, dojo_origin, city, age, created_at, updated_at, weight_group_id) FROM stdin;
1950	59	1156	44.00	Kyu 4	Remaja B	Kyu 4	Tanding	JAYABAYA 		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	1
1951	59	1175	63.00	Kyu 3	Remaja B	Kyu 3	Tanding	Jayabaya		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	5
1952	59	1199	49.00	Kyu 2	Dewasa	Kyu 2	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	2
1953	59	1200	49.00	Kyu 3	Remaja B	Kyu 3	Tanding	Jayabaya		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	2
1954	59	1240	59.00	Kyu 2	Remaja B	Kyu 2	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	4
1955	59	1241	65.00	Kyu 2	Dewasa	Kyu 2	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	5
1956	59	1188	50.00	Kyu 4	Remaja B	Kyu 4	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	2
1957	59	1190	45.00	Kyu 4	Remaja B	Kyu 4	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	1
1958	59	1358	55.00	Kyu 3	Dewasa	Kyu 3	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	3
1959	59	1198	70.00	Kyu 2	Dewasa	Kyu 2	Tanding	JAYABAYA		\N	2026-06-08 01:33:47	2026-06-08 01:33:47	6
1669	69	1299	45.00	Kyu 4	Pemula	Kyu 4	Tanding	UWK		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1670	69	1301	45.00	Kyu 4	Pemula	Kyu 4	Tanding	PLN Nusantara Power		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1671	69	1302	29.90	Kyu 4	Pemula	Kyu 4	Tanding	UWK		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1672	69	1345	40.00	Kyu 4	Pemula	Kyu 4	Tanding	UWK		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1673	69	1304	39.90	Kyu 3	Remaja A	Kyu 3	Tanding	PLN Nusantara Power		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1674	69	1306	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	PL Nusantara Power		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1675	69	1308	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	PLN Nusantara Power		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1676	69	1311	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	Unesa		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1677	69	1312	40.00	Kyu 4	Remaja A	Kyu 4	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1678	69	1314	40.00	Kyu 4	Remaja A	Kyu 4	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	1
1679	69	1315	65.00	Kyu 3	Remaja B	Kyu 3	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	6
1680	69	1316	65.00	Kyu 3	Remaja B	Kyu 3	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	5
1681	69	1317	50.00	Kyu 1	Dewasa	Kyu 1	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	2
1682	69	1349	60.00	Kyu 3	Remaja B	Kyu 3	Tanding	Perak		\N	2026-05-31 11:33:27	2026-05-31 11:33:27	4
1548	68	1295	30.00	Kyu 4	Pemula	Kyu 4	Tanding	Dojo Perak 		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	1
1549	68	1296	30.00	Kyu 4	Pemula	Kyu 4	Tanding	Dojo Perak		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	1
1550	68	1346	30.00	Kyu 4	Pemula	Kyu 4	Tanding	UWK		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	1
1551	68	1297	30.00	Kyu 4	Pemula	Kyu 4	Tanding	Dojo UWK		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	1
1552	68	1298	65.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo Narotama		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	6
1553	68	1300	75.00	Kyu 4	Remaja B	Kyu 4	Tanding	Dojo Petra		\N	2026-05-27 14:08:38	2026-05-27 14:08:38	8
1916	70	1303	40.00	Kyu 3	Pemula	Kyu 3	Tanding	Dojo PJB - PLN Nusantara Power		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1917	70	1305	40.00	Kyu 4	Pemula	Kyu 4	Tanding	Dojo Perak		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1918	70	1307	40.00	Kyu 4	Pemula	Kyu 4	Tanding	Dojo Perak		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1919	70	1309	65.00	Kyu 3	Remaja A	Kyu 3	Tanding	Dojo Perak 		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	5
1920	70	1310	40.00	Kyu 5	Remaja A	Kyu 5	Tanding	Dojo UNESA		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1921	70	1313	40.00	Kyu 4	Remaja A	Kyu 4	Tanding	Dojo Petra		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1922	70	1318	68.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo UWK		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	6
1923	70	1347	75.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo Petra		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	7
1924	70	1320	45.00	Kyu 1	Remaja B	Kyu 1	Tanding	Dojo PLN-NP (PJB)		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1925	70	1321	45.00	Kyu 1	Remaja B	Kyu 1	Tanding	Dojo Perak		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1926	70	1322	45.00	Kyu 4	Remaja B	Kyu 4	Tanding	Dojo Petra		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	1
1927	70	1323	55.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo Perak		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	3
1928	70	1324	65.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo Perak		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	5
1929	70	1325	65.00	Kyu 1	Dewasa	Kyu 1	Tanding	Dojo ITS		\N	2026-06-07 05:40:34	2026-06-07 05:40:34	5
1983	62	1215	44.00	Kyu 4	Remaja B	Kyu 4	Tanding	GALIS BANGKALAN		\N	2026-06-09 03:52:40	2026-06-12 12:36:09	1
1984	62	1216	40.00	Kyu 4	Remaja B	Kyu 4	Tanding	TRUNOJOYO		\N	2026-06-09 03:52:40	2026-06-12 12:36:37	1
1981	62	1213	48.00	Kyu 1	Dewasa	Kyu 1	Tanding	GALIS BANGKALAN 		\N	2026-06-09 03:52:40	2026-06-12 12:36:53	2
1982	62	1214	59.00	Kyu 1	Dewasa	Kyu 1	Tanding	GALIS BANGKALAN 		\N	2026-06-09 03:52:40	2026-06-12 12:37:05	4
1985	62	1217	44.00	Kyu 3	Remaja B	Kyu 3	Tanding	TRUNOJOYO		\N	2026-06-09 03:52:40	2026-06-12 13:35:26	1
1986	62	1218	55.00	Kyu 6	Remaja A	Kyu 6	Tanding	GALIS		\N	2026-06-09 03:52:40	2026-06-12 13:35:43	3
1987	62	1226	46.00	Kyu 1	Dewasa	Kyu 1	Tanding	GALIS BANGKALAN 		\N	2026-06-09 03:52:40	2026-06-12 13:35:54	2
1988	62	1227	59.00	Kyu 4	Remaja B	Kyu 4	Tanding	GALIS BANGKALAN		\N	2026-06-09 03:52:40	2026-06-12 13:36:06	4
1989	62	1228	49.00	Kyu 6	Pemula	Kyu 6	Tanding	GALIS BANGKALAN 		\N	2026-06-09 03:52:40	2026-06-12 13:36:19	2
1990	62	1229	70.00	Kyu 3	Dewasa	Kyu 3	Tanding	GALIS BANGKALAN 		\N	2026-06-09 03:52:40	2026-06-12 13:36:27	6
1991	72	1350	64.00	Kyu 5	Remaja A	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	5
1992	72	1351	45.00	Kyu 5	Remaja B	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	1
1993	72	1353	57.00	Kyu 5	Remaja B	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	4
1994	72	1355	49.00	Kyu 5	Remaja B	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	2
1995	72	1356	69.50	Kyu 5	Remaja B	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	6
1996	72	1357	53.00	Kyu 5	Remaja B	Kyu 5	Tanding	Pagerwojo, Sidoarjo		\N	2026-06-09 12:58:09	2026-06-09 12:58:09	3
1417	65	1250	45.00	Kyu 3	Pemula	Kyu 3	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-05-24 14:09:27	1
1419	65	1251	65.00	Kyu 2	Dewasa	Kyu 2	Tanding	DOJO RONGGOLAWE		\N	2026-05-24 14:09:27	2026-05-24 14:09:27	5
1997	73	1219	56.00	Kyu 3	Dewasa	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
1998	73	1224	49.00	Kyu 2	Dewasa	Kyu 2	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
1999	73	1232	49.00	Kyu 6	Remaja B	Kyu 6	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2000	73	1230	53.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMA PGRI Purrwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2001	73	1222	49.00	Kyu 6	Dewasa	Kyu 6	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2002	73	1235	48.00	Kyu 3	Remaja A	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2003	73	1237	62.00	Dan 2	Dewasa	Dan 2	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2004	73	1233	53.00	Kyu 3	Dewasa	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
1862	61	1204	64.00	Kyu 2	Remaja B	Kyu 2	Tanding	Koramil Mojowarno 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	5
1863	61	1206	56.00	Kyu 3	Remaja B	Kyu 3	Tanding	DOJO GUDO 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	4
1864	61	1279	43.00	Kyu 3	Remaja A	Kyu 3	Tanding	Koramil Mojowarno 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1865	61	1271	44.00	Kyu 4	Remaja A	Kyu 4	Tanding	Mojowarno		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1866	61	1281	44.00	Kyu 5	Pemula	Kyu 5	Tanding	Al - fatih		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1867	61	1283	44.00	Kyu 4	Pemula	Kyu 4	Tanding	Al-Fatih Jombang		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1868	61	1276	44.00	Kyu 4	Pemula	Kyu 4	Tanding	DOJO MOJOWARNO 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1869	61	1270	44.00	Kyu 4	Remaja B	Kyu 4	Tanding	koramil mojowarno		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1870	61	1280	44.00	Kyu 4	Remaja B	Kyu 4	Tanding	Dojo SMANSA		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1871	61	1275	44.00	Kyu 3	Remaja B	Kyu 3	Tanding	DOJO SMPN 1 JOMBANG		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1872	61	1277	44.00	Kyu 3	Remaja B	Kyu 3	Tanding	dojo smpn 1 gudo		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1873	61	1285	44.00	Kyu 2	Remaja B	Kyu 2	Tanding	DOJO SMPN 1 JOMBANG 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1874	61	1269	44.00	Kyu 3	Remaja B	Kyu 3	Tanding	DOJO SMPN 1 GUDO		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1875	61	1272	44.00	Kyu 2	Remaja B	Kyu 2	Tanding	Al-Fatih Jombang		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	1
1876	61	1278	68.00	Kyu 1	Remaja B	Kyu 1	Tanding	DOJO GMK 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	6
1877	61	1286	58.00	Kyu 1	Remaja B	Kyu 1	Tanding	SMANERO		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	4
1878	61	1284	49.00	Kyu 1	Remaja B	Kyu 1	Tanding	Dojo Gudo		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	2
1879	61	1282	53.80	Kyu 1	Remaja B	Kyu 1	Tanding	SMPN 1 Jombang		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	3
1880	61	1273	53.00	Kyu 1	Remaja B	Kyu 1	Tanding	SMPN 1 Jombang		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	3
1881	61	1274	47.00	Kyu 3	Remaja B	Kyu 3	Tanding	gudo		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	2
1882	61	1268	53.00	Kyu 1	Dewasa	Kyu 1	Tanding	Dojo Gudo		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	3
1883	61	1287	63.00	Kyu 4	Dewasa	Kyu 4	Tanding	DOJO SMADA 		\N	2026-06-05 01:27:47	2026-06-05 01:27:47	5
2005	73	1231	56.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2006	73	1225	53.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2007	73	1220	49.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2008	73	1223	54.00	Kyu 1	Dewasa	Kyu 1	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
2009	73	1221	48.00	Kyu 1	Dewasa	Kyu 1	Tanding	SMA PGRI Purwoharjo		\N	2026-06-11 02:31:32	2026-06-11 02:31:32	\N
1415	65	1247	45.00	Kyu 3	Remaja A	Kyu 3	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 13:58:05	1
1412	65	1244	45.00	Kyu 2	Remaja A	Kyu 2	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 13:57:03	1
1416	65	1248	45.00	Kyu 3	Remaja A	Kyu 3	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 13:58:13	1
1418	65	1249	50.00	Kyu 3	Remaja A	Kyu 3	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 13:58:29	2
1413	65	1245	45.00	Kyu 2	Remaja A	Kyu 2	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 13:57:28	1
1414	65	1246	45.00	Kyu 3	Remaja A	Kyu 3	Tanding	DOJO RONGGOLAWE		\N	2026-05-24 14:09:27	2026-06-12 13:57:35	1
1421	65	1253	65.00	Kyu 3	Dewasa	Kyu 3	Tanding	DOJO RONGGOLAWE		\N	2026-05-24 14:09:27	2026-06-12 13:59:05	5
1410	65	1242	63.00	Kyu 1	Remaja B	Kyu 1	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:26	2026-06-12 13:56:37	5
1411	65	1243	45.00	Kyu 2	Remaja A	Kyu 2	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:26	2026-06-12 13:56:51	1
1420	65	1252	67.00	Kyu 2	Dewasa	Kyu 2	Tanding	DOJO RONGGOLAWE		\N	2026-05-24 14:09:27	2026-06-12 13:58:47	6
1422	65	1254	72.00	Kyu 3	Dewasa	Kyu 3	Tanding	DOJO RONGGOLAWE TUBAN		\N	2026-05-24 14:09:27	2026-06-12 14:00:29	6
1252	63	1210	55.00	Kyu 4	Remaja B	Kyu 4	Tanding	BANGKALAN B		\N	2026-05-20 14:47:14	2026-05-20 14:47:14	3
1253	63	1207	70.00	Kyu 4	Remaja B	Kyu 4	Tanding	BANGKALAN B		\N	2026-05-20 14:47:14	2026-05-20 14:47:14	6
1254	63	1208	50.00	Kyu 4	Remaja B	Kyu 4	Tanding	BANGKALAN B		\N	2026-05-20 14:47:14	2026-05-20 14:47:14	2
1255	63	1209	63.00	Kyu 4	Remaja B	Kyu 4	Tanding	BANGKALAN B		\N	2026-05-20 14:47:14	2026-05-20 14:47:14	5
1906	60	1166	54.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:15:19	3
1908	60	1168	44.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:15:28	1
1909	60	1169	50.00	Kyu 2	Dewasa	Kyu 2	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:15:38	2
1910	60	1170	49.00	Dan 1	Dewasa	Dan 1	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:15:45	2
1911	60	1171	54.00	Kyu 1	Dewasa	Kyu 1	Tanding	SMP MAARIF PANDAAN 		\N	2026-06-05 10:10:09	2026-06-11 11:15:57	3
1912	60	1172	54.00	Dan 1	Dewasa	Dan 1	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:16:06	3
1913	60	1173	59.00	Kyu 2	Dewasa	Kyu 2	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-11 11:16:12	4
1884	66	1288	50.00	Kyu 2	Dewasa	Kyu 2	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	2
1885	66	1289	50.00	Kyu 2	Dewasa	Kyu 2	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	2
1886	66	1290	50.00	Kyu 1	Remaja B	Kyu 1	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	2
1887	66	1291	60.00	Kyu 1	Dewasa	Kyu 1	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	4
1888	66	1292	50.00	Kyu 2	Remaja B	Kyu 2	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	2
1889	66	1293	45.00	Kyu 3	Remaja A	Kyu 3	Tanding	JEMBER		\N	2026-06-05 02:12:26	2026-06-05 02:12:26	1
1907	60	1167	49.00	Kyu 3	Remaja B	Kyu 3	Tanding	SMP MAARIF PANDAAN		\N	2026-06-05 10:10:09	2026-06-05 10:10:09	2
1977	88	1234	45.00	Kyu 2	Pemula	Kyu 2	Tanding	Stiba 		\N	2026-06-08 16:34:52	2026-06-08 16:34:52	1
1978	88	1202	45.00	Kyu 2	Pemula	Kyu 2	Tanding	Sriba		\N	2026-06-08 16:34:52	2026-06-08 16:34:52	1
1979	88	1201	50.00	Kyu 2	Remaja A	Kyu 2	Tanding	Stiba 		\N	2026-06-08 16:34:52	2026-06-08 16:34:52	2
1980	88	1203	50.00	Kyu 2	Remaja A	Kyu 2	Tanding	Stiba 		\N	2026-06-08 16:34:52	2026-06-08 16:34:52	1
1816	73	1236	35.00	Kyu 6	Pemula	Kyu 6	Tanding	SMA PGRI Purwoharjo		\N	2026-06-04 05:57:09	2026-06-04 05:57:09	1
1653	71	1326	40.00	Kyu 3	Pemula	Kyu 3	Tanding	Dojo UWK		\N	2026-05-31 11:23:02	2026-05-31 11:23:02	1
1654	71	1327	50.00	Kyu 3	Pemula	Kyu 3	Tanding	Dojo Perak 		\N	2026-05-31 11:23:02	2026-05-31 11:23:02	2
1655	71	1328	40.00	Kyu 3	Pemula	Kyu 3	Tanding	Dojo UWK		\N	2026-05-31 11:23:02	2026-05-31 11:23:02	1
1656	71	1329	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	Dojo UWK		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	1
1657	71	1330	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	Dojo UWK		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	1
1658	71	1332	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	Dojo UNESA		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	1
1659	71	1348	40.00	Kyu 3	Remaja A	Kyu 3	Tanding	Petra		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	1
1660	71	1335	70.00	Kyu 3	Remaja B	Kyu 3	Tanding	PLN Nusantara Power		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	6
1661	71	1337	55.00	Kyu 1	Remaja B	Kyu 1	Tanding	PLN Nusantara Power		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	3
1662	71	1338	65.00	Kyu 1	Remaja B	Kyu 1	Tanding	Unesa		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	5
1663	71	1341	70.00	Kyu 3	Remaja B	Kyu 3	Tanding	petra		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	6
1664	71	1343	45.00	Kyu 3	Remaja B	Kyu 3	Tanding	perak		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	1
1665	71	1336	50.00	Kyu 3	Remaja B	Kyu 3	Tanding	Dojo Perak		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	2
1666	71	1340	71.00	Kyu 4	Remaja B	Kyu 4	Tanding	Dojo Petra		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	7
1667	71	1342	65.00	Kyu 1	Dewasa	Kyu 1	Tanding	Dojo Perak		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	5
1668	71	1344	65.00	Kyu 1	Dewasa	Kyu 1	Tanding	Dojo PJB-PLN Nusantara Power		\N	2026-05-31 11:23:03	2026-05-31 11:23:03	5
1578	67	1259	35.00	Kyu 6	Pemula	Kyu 6	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1579	67	1257	30.00	Kyu 4	Pemula	Kyu 4	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1580	67	1258	30.00	Kyu 6	Pemula	Kyu 6	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1581	67	1255	35.00	Kyu 2	Pemula	Kyu 2	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1582	67	1256	45.20	Kyu 2	Pemula	Kyu 2	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1583	67	1260	48.60	Kyu 3	Remaja A	Kyu 3	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1584	67	1261	42.00	Kyu 3	Remaja A	Kyu 3	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	1
1585	67	1262	68.00	Kyu 4	Remaja B	Kyu 4	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	6
1586	67	1264	50.00	Kyu 6	Remaja B	Kyu 6	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	2
1587	67	1263	74.00	Kyu 3	Remaja B	Kyu 3	Tanding	Semen Gresik		\N	2026-05-27 23:35:36	2026-05-27 23:35:36	7
1588	67	1265	47.00	Kyu 6	Remaja B	Kyu 6	Tanding	Semen Gresik		\N	2026-05-27 23:35:37	2026-05-27 23:35:37	2
1589	67	1266	55.00	Kyu 6	Remaja B	Kyu 6	Tanding	Semen Gresik		\N	2026-05-27 23:35:37	2026-05-27 23:35:37	3
1590	67	1267	70.00	Kyu 2	Dewasa	Kyu 2	Tanding	Semen Gresik		\N	2026-05-27 23:35:37	2026-05-27 23:35:37	6
\.


--
-- Data for Name: registration_official; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registration_official (id, registration_id, official_id, role, created_at, updated_at) FROM stdin;
205	61	6	Official	2026-06-05 01:27:46	2026-06-05 01:27:46
206	61	7	Official	2026-06-05 01:27:46	2026-06-05 01:27:46
207	61	8	Official	2026-06-05 01:27:46	2026-06-05 01:27:46
208	61	33	Official	2026-06-05 01:27:46	2026-06-05 01:27:46
209	66	21	Manajer Tim	2026-06-05 02:12:26	2026-06-05 02:12:26
210	66	22	Official	2026-06-05 02:12:26	2026-06-05 02:12:26
132	68	24	Manajer Tim	2026-05-27 14:08:38	2026-05-27 14:08:38
133	68	23	Official	2026-05-27 14:08:38	2026-05-27 14:08:38
219	60	4	Official	2026-06-05 10:10:09	2026-06-05 10:10:09
220	60	5	Official	2026-06-05 10:10:09	2026-06-05 10:10:09
140	67	20	Official	2026-05-27 23:35:36	2026-05-27 23:35:36
222	70	27	Manajer Tim	2026-06-07 05:40:34	2026-06-07 05:40:34
223	70	28	Official	2026-06-07 05:40:34	2026-06-07 05:40:34
228	59	2	Pelatih	2026-06-08 01:33:47	2026-06-08 01:33:47
229	59	3	Pelatih	2026-06-08 01:33:47	2026-06-08 01:33:47
154	71	25	Manajer Tim	2026-05-31 11:23:02	2026-05-31 11:23:02
155	71	26	Official	2026-05-31 11:23:02	2026-05-31 11:23:02
97	65	18	Manajer Tim	2026-05-24 14:09:26	2026-05-24 14:09:26
98	65	19	Official	2026-05-24 14:09:26	2026-05-24 14:09:26
156	71	31	Official	2026-05-31 11:23:02	2026-05-31 11:23:02
157	69	29	Manajer Tim	2026-05-31 11:33:27	2026-05-31 11:33:27
158	69	30	Official	2026-05-31 11:33:27	2026-05-31 11:33:27
234	88	35	Official	2026-06-08 16:34:52	2026-06-08 16:34:52
235	62	13	Official	2026-06-09 03:52:39	2026-06-09 03:52:39
236	62	14	Official	2026-06-09 03:52:39	2026-06-09 03:52:39
237	72	32	Official	2026-06-09 12:58:09	2026-06-09 12:58:09
58	63	11	Official	2026-05-20 14:47:14	2026-05-20 14:47:14
59	63	10	Manajer Tim	2026-05-20 14:47:14	2026-05-20 14:47:14
183	73	15	Official	2026-06-04 05:57:09	2026-06-04 05:57:09
\.


--
-- Data for Name: registrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registrations (id, contingent_id, total_cost, final_amount, unique_code, payment_method, referral_code, status, transfer_proof_path, sim_perkemi_confirm, created_at, updated_at, draft_data, athlete_status) FROM stdin;
68	30	5100000.00	5100460.00	460	Tunai	KEMPO-CXHAT	verified	\N	Ya	2026-05-27 14:08:38	2026-06-12 14:20:24	\N	verified
60	17	6500000.00	6500447.00	447	Tunai	KEMPO-TXIYU	verified	transfer_proofs/2mND5fFdQBy8MelUAf9WPo6fx26JNAngkGDYmyA8.jpg	Ya	2026-05-18 09:28:16	2026-06-12 14:20:24	{"contingent_name":"Kabupaten Pasuruan","contingent_city":"Kabupaten Pasuruan","leader_name":"Ahmad Muqaffi Alaq","leader_phone":"081553054313","leader_email":"lia555yul@gmail.com","address":"Jl. Raya A. Yani No. 92, Pandaan, Jogonalan, Jogo Nalain, Petungasri, Kec. Pandaan, Pasuruan, Jawa Timur 67156","officials":[{"official_id":4,"name":"Yulia Ainur Rahma","role":"Official","phone":"089515662704"},{"official_id":5,"name":"Khoirul Syah Saputra","role":"Official","phone":"082257324758"}],"athletes":[{"athlete_id":1166,"nik":"3514114210090003","nik_kenshi":"22.3.13.21.01.004","name":"Icha Sazira Fitriani ","gender":"Female","birth_place":"Sdioarjo","blood_type":"B+","birth_date":"2009-10-02","address":"jalan lukman hakim rt 01 rw 01 lingkungan jogonalan kecamatan Pandaan kabupaten Pasuruan ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/Fi8OaC1WWMVDX36DCNWYCRlfuMquQocuWSPYsq4N.jpg","current_weight":"54.00","weight_group_id":3,"age_group":3,"rank":"Kyu 3","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"24154264519","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":29,"event2":26,"event3":""},{"athlete_id":1167,"nik":"3514115606100002","nik_kenshi":"22.3.13.21.01.008","name":"Kamila Farha Ilmi","gender":"Female","birth_place":"Pasuruan","blood_type":"","birth_date":"2010-06-16","address":"Ling.Dukuh RT\\/RW 002\\/006 kutorejo kecamatan pandaan kabupaten pasuruan","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/oh1mnDv41DN2NqljY9WYPvjbPQkMYP0Q3wVEKYyg.jpg","current_weight":"49.00","weight_group_id":2,"age_group":3,"rank":"Kyu 3","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"24154264493","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":28,"event2":26,"event3":""},{"athlete_id":1168,"nik":"3514116908090002","nik_kenshi":"22.3.13.21.01.001","name":"Devina Wahyu Ramadhani ","gender":"Female","birth_place":"Pasuruan ","blood_type":"","birth_date":"2009-08-29","address":"dusun jonggan RT.02 RW.07 desa durensewu kecamatan Pandaan ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/S0CdbVX4n6tusPELSvan8V8ez2Ah5Y5hq2oH55mp.jpg","current_weight":"44.00","weight_group_id":1,"age_group":3,"rank":"Kyu 3","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"24154264501","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":27,"event2":"","event3":""},{"athlete_id":1169,"nik":"3514117003060001","nik_kenshi":"18.3.13.02.07.013","name":"Nawrah Filzah Cameela","gender":"Female","birth_place":"Pasuruan","blood_type":"O","birth_date":"2006-03-30","address":"Ling. Plumbon RT 005 \\/ RW 005","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/Nb6SMsuYHCoYqldpX3FafFFJhCWvosCpxfTsIGVg.jpg","current_weight":"50.00","weight_group_id":2,"age_group":4,"rank":"Kyu 2","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"0000112686028","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":42,"event2":"","event3":""},{"athlete_id":1170,"nik":"3514115710040003","nik_kenshi":"19.2.13.21.01.001","name":"Amanda Aisyah Ramadanni ","gender":"Female","birth_place":"Malang","blood_type":"B","birth_date":"2004-10-17","address":"Dusun Kulak RT 001\\/ RW 012 Desa Nogosari Kecamatan Pandaan Kabupaten Pasuruan","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/KD5yBrDe4cCPWHeHK17nWsAC8xiBHYplNlVHzhqF.jpg","current_weight":"49.00","weight_group_id":2,"age_group":4,"rank":"Dan 1","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"0002051713067","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":45,"event2":"","event3":""},{"athlete_id":1171,"nik":"3514105311050001","nik_kenshi":"18.3.13.04.07.005","name":"Dzurotul Aini","gender":"Female","birth_place":"Pasuruan","blood_type":"","birth_date":"2005-11-13","address":"usun Bulu krajan, desa Bulukandang, kecamatan Prigen, kabupaten Pasuruan ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/1UKT6z9LS8qbAHP9aVdIO9SjaJiMSTsswS15kNkh.jpg","current_weight":"54.00","weight_group_id":3,"age_group":4,"rank":"Kyu 1","dojo_origin":"SMP MAARIF PANDAAN ","city":"","bpjs_number":"23113032157","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":46,"event2":"","event3":""},{"athlete_id":1172,"nik":"3578131701030003","nik_kenshi":"19.2.13.21.01.006","name":"Prayogie Al Dino","gender":"Male","birth_place":"Surabaya","blood_type":"","birth_date":"2003-01-17","address":" jln. apel III\\/529, kiduldalem, bangil, kab. pasuruan","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/zd7h2cQWL2FiCgBVeCPqGBjDNQdXhQI172TMCf9o.jpg","current_weight":"54.00","weight_group_id":3,"age_group":4,"rank":"Dan 1","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"21061418162","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":38,"event2":"","event3":""},{"athlete_id":1173,"nik":"3603300710040002","nik_kenshi":"18.3.13.04.07.017","name":"Tegar Pribadi Putra","gender":"Male","birth_place":"Tangerang","blood_type":"O","birth_date":"2004-10-07","address":"Dsn. Tambakrejo RT.03\\/RW.02, Ds. Tanjung arum, Kec. Sukorejo","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/2N0LTSqHTHWwDgo1TGwkdogKoy1pP7P8T9hIrOzK.jpg","current_weight":"59.00","weight_group_id":4,"age_group":4,"rank":"Kyu 2","dojo_origin":"SMP MAARIF PANDAAN","city":"","bpjs_number":"26029363665","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":39,"event2":"","event3":""}],"matchTechniques":{"29":[],"26":[160,162,163,164,165,166],"28":[],"27":[],"42":[5,4,3,9,8,10],"45":[],"46":[],"38":[],"39":[],"3_42":[5,147,4,9,8,28]},"payment_method":"Tunai"}	verified
88	40	4300000.00	4300735.00	735	Tunai	KEMPO-V4F7B	pending	\N	Ya	2026-06-08 14:59:19	2026-06-12 12:12:19	{"contingent_name":"Kota  Malang 3 (STIBA-GSE)","contingent_city":"Kota Malang","leader_name":"S. Simbolon","leader_phone":"081931887871","leader_email":"leonardocastello26@gmail.com","address":"Perun Griya Shanta Eksekutif M366 Jatimulyo-Lowokwaru","officials":[{"official_id":35,"name":"Sariman Simbolon","role":"Official","phone":"081931887871"}],"athletes":[{"athlete_id":1234,"nik":"3506046702140001","nik_kenshi":"23.1.13.03.06.001","name":"Almajesta Azalea Divone ","gender":"Female","birth_place":"Kediri","blood_type":"-","birth_date":"2014-02-27","address":"Perumahan Griya Shanta Eksekutif blok M 313 RT 10 RW 04 Jatimulyo, Lowokwaru kota Malang ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/ukrFF7kaqgHiQqgRYn8MoHEyJrK2IcJBnZXW0Rlm.jpg","current_weight":"45.00","weight_group_id":1,"age_group":1,"join_other_age_group":true,"event_age_group":"1","rank":"Kyu 2","dojo_origin":"Stiba ","city":"","bpjs_number":"0002434289005","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":"2","event2":"3","event3":13},{"athlete_id":1202,"nik":"3573052608150003","nik_kenshi":"22.3.13.03.06.001","name":"Wayne Matthew Axel Parulian Simbolon ","gender":"Male","birth_place":"Kota Malang ","blood_type":"-","birth_date":"2015-08-26","address":"Perumahan Griya Shanta Eksekutif blok M 366 RT 10 RW 04 Jatimulyo Lowokwaru, Kota Malang ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/0Owjx8KVGwjZznelcnnoHK8EGkZVqUPehNVRWtXi.jpg","current_weight":"45.00","weight_group_id":1,"age_group":1,"join_other_age_group":true,"event_age_group":"1","rank":"Kyu 2","dojo_origin":"Sriba","city":"","bpjs_number":"000000000000000000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":10,"event3":13},{"athlete_id":1201,"nik":"3573054206120005","nik_kenshi":"15.3.13.03.06.004","name":"Rachel Bertha Kenshi Nauli Simbolon ","gender":"Female","birth_place":"Malang","blood_type":"O","birth_date":"2012-06-02","address":"Perumahan Griya Shanta Eksekutif blok M 366 RT 10 RW 04 Jatimulyo, Lowowaru Kota Malang ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/KQpoqpyI6B0bjhqMw2R9p9EDzmKuDvFVgHpum4ZM.jpg","current_weight":"50.00","weight_group_id":2,"age_group":2,"join_other_age_group":true,"event_age_group":"2","rank":"Kyu 2","dojo_origin":"Stiba ","city":"","bpjs_number":"000000000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":2,"event2":5,"event3":"13"},{"athlete_id":1203,"nik":"3573015109120004","nik_kenshi":"23.3.13.03.06.001","name":"Tiara Anisa Yuniarto","gender":"Female","birth_place":"Kota Malang ","blood_type":"-","birth_date":"2012-09-21","address":"Jln. Hamid Rusdi K-153\\nRT 001 RW  006 Kesatrian Blimbing Kota Malang ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/nZSEdxgRaM2c5NcCAE3d062B68uWeIiocinHmEao.jpg","current_weight":"50.00","weight_group_id":1,"age_group":2,"join_other_age_group":true,"event_age_group":"2","rank":"Kyu 2","dojo_origin":"Stiba ","city":"","bpjs_number":"0001537952185","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":5,"event2":"13","event3":""}],"matchTechniques":{"0_7":[],"10":[],"13":[255,116,180,99,103,335],"1_6":[255,337,186,7,335,183],"2_2":[255,337,186,7,335,183],"5":[52,180,116,103,54,99],"2":[],"0_2":[255,337,186,7,335,183],"3":[52,180,116,103,53,99]},"payment_method":"Tunai"}	pending
69	29	9100000.00	9100364.00	364	Tunai	KEMPO-R9POA	verified	\N	Ya	2026-05-27 14:27:12	2026-06-12 14:20:24	{"contingent_name":"Surabaya C","contingent_city":"Surabaya","leader_name":"Manager Surabaya C","leader_phone":"087701918534","leader_email":"surabaya.c@smart-perkemi.id","address":"Jl. Jambangan Baru no 3","officials":[{"official_id":29,"name":"Rendy Andhika Widyanto","role":"Manajer Tim","phone":"087701918534"},{"official_id":30,"name":"Stephanie Natania","role":"Official","phone":"082233999319"}],"athletes":[{"athlete_id":1299,"nik":"6473032511130001","nik_kenshi":"25.1.13.01.08.001","name":"As'ad Tegar Ballan Firmansyah","gender":"Male","birth_place":"Tarakan","blood_type":"A","birth_date":"2013-11-25","address":"Sidosermo 2 Blok A\\/4 Surabaya","phone":"082139130427","photo":null,"existing_photo_path":"athlete_photos\\/zPyE9DVYljOLUnhmUACvFX7s5V2dOQsfVgZzpNfS.jpg","current_weight":"45.00","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":4,"event2":1,"event3":""},{"athlete_id":1301,"nik":"3578262702140001","nik_kenshi":"23.3.13.01.20.003","name":"Muhammad Hanif Abrar Rivandra","gender":"Male","birth_place":"Surabaya","blood_type":"B","birth_date":"2014-02-27","address":"Jl. Semolowaru Selatan XI No.10 Surabaya","phone":"0896-7111-7111","photo":null,"existing_photo_path":"athlete_photos\\/R5cl0wfnIvEsnFtnqbs8vvTaDRLXJr36jJ3y7ADh.jpg","current_weight":"45.00","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"PLN Nusantara Power","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":4,"event2":"","event3":""},{"athlete_id":1302,"nik":"3578025101140003","nik_kenshi":"24.1.13.01.20.002","name":"Beby Naura Ozyllia Janeeta ","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2014-01-11","address":"Jln.sidosermo 4 GG 5 no 12","phone":"082227665580 \\/ 081938141499","photo":null,"existing_photo_path":"athlete_photos\\/8s7afuGrUF85i0uqZZckZ4vL8ld79NM2ILaeg2cH.jpg","current_weight":"29.90","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":3,"event2":2,"event3":""},{"athlete_id":"1345","nik":"3509202411140003","nik_kenshi":"21.3.18.01.09.002","name":"R. Muhammad Rifqi Ainurrafa","gender":"Male","birth_place":"Jember","blood_type":"B","birth_date":"2014-11-24","address":"Jalan Taman Indah VI no 23, Sepanjang, Taman, Sidoarjo","phone":"08123480480","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"1","rank":"Kyu 4","dojo_origin":"UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"3","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1304","nik":"3578024807120002","nik_kenshi":"21.1.13.01.20.004","name":"Farzana Delisha Wicaksono ","gender":"Female","birth_place":"Surabaya","blood_type":"O","birth_date":"2012-07-08","address":"Jl Wisma Menanggal 3 no 14 ","phone":"085755497473","photo":null,"existing_photo_path":null,"current_weight":"39.9","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"PLN Nusantara Power","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"12","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1306","nik":"3578226205120003","nik_kenshi":"18.3.13.01.20.004","name":"Kaysha Aretha Radwa Almeiraza","gender":"Female","birth_place":"Surabaya","blood_type":"B","birth_date":"2012-05-22","address":"Ketintang Madya No. 35 Surabaya","phone":"082142033926","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"PL Nusantara Power","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"12","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1308","nik":"3578224706130002","nik_kenshi":"23.1.13.01.20.004","name":"Dinda Cintani Nugroho","gender":"Female","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2013-09-07","address":"Jln. Ketintang Madya No.35 Surabaya Jawa Timur","phone":"087853096622","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"PLN Nusantara Power","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"12","event2":"9","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1311","nik":"3578186204120001","nik_kenshi":"22.3.13.01.09.004","name":"Naisya Ayatul Khusna ","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2012-04-22","address":"lidah wetan gang 8B no 56 Surabaya ","phone":"088217249098","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"Unesa","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"12","event2":"9","event3":"7","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1312","nik":"3515166504130001","nik_kenshi":"22.3.13.01.24.007","name":"Intan Renia Anggraini","gender":"Female","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2013-04-25","address":"Tambak Wedi Baru Utara 18B No 28","phone":"085105017208","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 4","dojo_origin":"Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"10","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1314","nik":"3318051906130003","nik_kenshi":"25.3.13.01.24.007","name":"Andrean dwi rekso jovani","gender":"Male","birth_place":"Pati","blood_type":"-","birth_date":"2013-06-19","address":"Kalimas baru2 buntu no264 Rt 13 Rw 09","phone":"082136264651","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 4","dojo_origin":"Perak","city":"","bpjs_number":"0002841231172","bpjs_status":"Aktif","bpjs_card":null,"event1":"10","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1315","nik":"6402162703120003","nik_kenshi":"20.1.15.04.03.003","name":"Ahmad Maulana Ibrahim ","gender":"Male","birth_place":"Jember","blood_type":"O","birth_date":"2012-03-27","address":"Sidomulyo 2-A\\/26, Sidotopo wetan - Surabaya ","phone":"085852004611","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"6","age_group":"3","rank":"Kyu 3","dojo_origin":"Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"14","event2":"22","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1316","nik":"3578122503250002","nik_kenshi":"18.1.13.01.24.002","name":"Farah azalia salshabila ilham","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2010-03-26","address":"jl. Teluk nibung timur 4 no 43","phone":"082230014230","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"3","rank":"Kyu 3","dojo_origin":"Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"24","event2":"31","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1317","nik":"3578124902060001","nik_kenshi":"13.3.13.01.24.004","name":"Bilqis Ammardivia G ","gender":"Female","birth_place":"Surabaya","blood_type":"B","birth_date":"2006-02-09","address":"Jl. Teluk Penanjung no 37","phone":"085746517955","photo":null,"existing_photo_path":null,"current_weight":"50","weight_group_id":"2","age_group":"4","rank":"Kyu 1","dojo_origin":"Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"43","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1349","nik":"3578122804090001","nik_kenshi":"16.2.13.01.24.002","name":"Bisma Ali Kumara","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2009-04-25","address":"Jl. Teluk Nibung Timur 4 no 48 ","phone":"085843233477","photo":null,"existing_photo_path":null,"current_weight":"60","weight_group_id":"4","age_group":"3","rank":"Kyu 3","dojo_origin":"Perak","city":"","bpjs_number":"2407301975","bpjs_status":"Aktif","bpjs_card":null,"event1":"20","event2":"31","event3":"","identity_document":null,"is_master_found":true,"show_fields":true}],"matchTechniques":{"4":[245,246,251,248,23,252],"0_1":[253,254,255,256,257,258],"3":[245,259,260,248,23,252],"2_2":[253,254,261,256,262,263],"12":[253,226,39,281,50,258],"7":[],"9":[226,233,282,283,284,285],"6_7":[253,286,255,256,257,258],"7_7":[253,286,255,256,257,258],"10":[245,246,251,39,23,252],"14":[],"10_14":[253,286,255,256,257,258],"22":[],"24":[],"11_24":[253,286,256,257,258,255],"43":[],"12_43":[7,147,253,255,257,258],"31":[289,303,290,282,291,292],"20":[]},"payment_method":"Tunai"}	verified
66	31	5500000.00	5500489.00	489	Tunai	KEMPO-JGNQP	pending	\N	Ya	2026-05-25 15:32:35	2026-06-12 12:12:19	{"contingent_name":"Kontingen Jember","contingent_city":"Jember","leader_name":"Navra Najma Alfurrohmah ","leader_phone":"082141812154","leader_email":"kempo.universitas.jember@gmail.com","address":"jln. udang windu no.31 kel. Mangli, kec. Kaliwates, kab. Jember","officials":[{"official_id":21,"name":"NAVRA NAJMA ALFURROHMAH","role":"Manajer Tim","phone":"082141812154"},{"official_id":22,"name":"Solider Rintang Perdana","role":"Official","phone":"082132401899"}],"athletes":[{"athlete_id":1288,"nik":"3509194407070003","nik_kenshi":"19.2.13.10.03.002","name":"FARADIBA QOTRUNADA FATQUROCHIM","gender":"Female","birth_place":"JEMBER","blood_type":"O","birth_date":"2007-04-07","address":"PERUM BUMI MANGLI PERMAI BLOK DB-06","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/aLM2iO1mcwgJZuxxqmuM44y8lKDIYbCObu8rL6ig.jpg","current_weight":"50.00","weight_group_id":2,"age_group":4,"rank":"Kyu 2","dojo_origin":"JEMBER","city":"","bpjs_number":"22050376361","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":42,"event2":"","event3":""},{"athlete_id":1289,"nik":"3510051404040013","nik_kenshi":"23.3.13.10.02.006","name":"Zulfa Deo Ananda Putra","gender":"Male","birth_place":"BANYUWANGI","blood_type":"","birth_date":"2004-04-14","address":"Dsn. Stoplas, Rt2 Rw3, Kec. Muncar, Kab. Banyuwangi","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/gKDABYHTFd0ORc3ksxq6eFaVmareehTPrHEAEi0a.jpg","current_weight":"50.00","weight_group_id":2,"age_group":4,"rank":"Kyu 2","dojo_origin":"JEMBER","city":"","bpjs_number":"20015574401","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":37,"event2":"","event3":""},{"athlete_id":1290,"nik":"3509195011100004","nik_kenshi":"24.1.13.10.03.001","name":"ALAYSA NADIA HAQQ","gender":"Female","birth_place":"JEMBER","blood_type":"","birth_date":"2010-11-10","address":"Jl. Udang Windu no.31","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/t6QrzZgWqcbqnhJApN3pLM8S9CSvBvA3U6rdLkU0.jpg","current_weight":"50.00","weight_group_id":2,"age_group":3,"rank":"Kyu 1","dojo_origin":"JEMBER","city":"","bpjs_number":"22050376445","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":"25","event2":28,"event3":""},{"athlete_id":1291,"nik":"3509193101040001","nik_kenshi":"10.1.13.10.02.007","name":"Daffa Rayya Muhammad Athallah","gender":"Male","birth_place":"JEMBER","blood_type":"","birth_date":"2004-01-31","address":"Jl. Tanjung Lingk. Krajan Jember","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/uMH6RLOkfeoaVqek5SwEt5gn64EEoHeJRE6TgosV.jpg","current_weight":"60.00","weight_group_id":4,"age_group":4,"rank":"Kyu 1","dojo_origin":"JEMBER","city":"","bpjs_number":"20015574401","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":36,"event2":39,"event3":""},{"athlete_id":1292,"nik":"3509190512080006","nik_kenshi":"22.2.13.10.03.005","name":"Pramadi wijoyo","gender":"Male","birth_place":"JEMBER","blood_type":"","birth_date":"2008-12-05","address":"JL.MUJAHIR SUKORAMBI JEMBER","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/d0fi0npNtm2W7t1yppFbHYWrj2R82HgwlkVcIc1p.jpg","current_weight":"50.00","weight_group_id":2,"age_group":3,"rank":"Kyu 2","dojo_origin":"JEMBER","city":"","bpjs_number":"24147411664","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":15,"event2":18,"event3":""},{"athlete_id":1293,"nik":"3509191311110002","nik_kenshi":"22.2.13.10.03.002","name":"Muhammad Affan Nur Ihsan","gender":"Male","birth_place":"JEMBER","blood_type":"","birth_date":"2011-11-13","address":"perumahan bumi Mangli permai blok DG12A, Kaliwates jember","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/LQW74Z9NhAq3IAlV34fLy4zqIbVtr7KnUCJZy8Fd.png","current_weight":"45.00","weight_group_id":1,"age_group":2,"rank":"Kyu 3","dojo_origin":"JEMBER","city":"","bpjs_number":"22050376361","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":"","event3":""}],"matchTechniques":{"0_42":[7,5,183,4,186,28],"1_37":[],"1_35":[7,5,183,4,147,28],"2_24":[5,4,9,6,3,28],"2_28":[],"3_36":[7,4,183,10,5,186],"3_39":[],"4_15":[7,5,183,4,186,28],"4_18":[],"5_6":[5,4,9,6,3,10],"6_47":[],"25":[],"2_25":[7,4,183,10,5,186]},"payment_method":"Tunai"}	pending
61	22	13200000.00	13200743.00	743	Tunai	KEMPO-BQYAL	verified	\N	Ya	2026-05-18 17:01:40	2026-06-12 14:20:24	{"contingent_name":"JOMBANG","contingent_city":"KABUPATEN JOMBANG","leader_name":"RIZKA ADHI PRABAWA","leader_phone":"081216801237","leader_email":"acapoeirista@yahoo.com","address":"JL Jayapura No 014 Ngoro Jombang","officials":[{"official_id":6,"name":"Asat Musthofa","role":"Official","phone":"085785811888"},{"official_id":7,"name":"Agus Wahab Suprianto","role":"Official","phone":"085706435473"},{"official_id":8,"name":"Naufar Wildan Gresika","role":"Official","phone":"081266579827"},{"official_id":33,"name":"Abdulloh Faqih Khoironi","role":"Official","phone":"089648357474"}],"athletes":[{"athlete_id":1204,"nik":"3517033007090002","nik_kenshi":"22.3.13.09.03.009","name":"Arga Kusuma R.S.M.Y","gender":"Male","birth_place":"Jombang","blood_type":"O","birth_date":"2009-07-30","address":"Dsn. Kertorejo, Ds. Kertorejo, Kec. Ngoro","phone":"085748155515","photo":null,"existing_photo_path":"athlete_photos\\/J7nvCviEBFdkpiNt7umv2IHXH1TeVTw5p7ywFEYY.jpg","current_weight":"64.00","weight_group_id":5,"age_group":3,"rank":"Kyu 2","dojo_origin":"Koramil Mojowarno ","city":"","bpjs_number":"212121454354354545","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":21,"event2":"16","event3":32},{"athlete_id":"1206","nik":"3517025305090001","nik_kenshi":"23.2.13.09.04.002","name":"AIRA SYIFA RISTA","gender":"Female","birth_place":"JOMBANG","blood_type":"-","birth_date":"2009-05-13","address":"DSN :BUMIARJO \\nRT\\/RW:002\\/006\\nDESA:GUDO\\nKECAMATAN:GUDO ","phone":"085785810504","photo":null,"existing_photo_path":null,"current_weight":"56","weight_group_id":"4","age_group":"3","rank":"Kyu 3","dojo_origin":"DOJO GUDO ","city":"","bpjs_number":"0000727907253","bpjs_status":"Aktif","bpjs_card":null,"event1":"33","event2":"34","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1279","nik":"3517042603130001","nik_kenshi":"24.3.13.09.09 015","name":"Titus Althea Marhaendra","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2013-03-26","address":"RT.002\\/RW.002 Dsn. Kayen Ds. Mojotengah kec. Bareng kab. Jombang ","phone":"085755122018","photo":null,"existing_photo_path":null,"current_weight":"43","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"Koramil Mojowarno ","city":"","bpjs_number":"3517042603130001","bpjs_status":"Aktif","bpjs_card":null,"event1":"6","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1271","nik":"3517045109120002","nik_kenshi":"24.3.13.09.09.011","name":"PUTRI SEFINA NILA SARI","gender":"Female","birth_place":"KEDIRI","blood_type":"-","birth_date":"2012-11-09","address":"DSN : jenisgelaran RT\\/RW: 001\\/001\\ndesa : jenisgelaran\\nkecamatan : bareng","phone":"0895428464612","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"2","rank":"Kyu 4","dojo_origin":"Mojowarno","city":"","bpjs_number":"3517045109120002","bpjs_status":"Aktif","bpjs_card":null,"event1":"7","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1281","nik":"3517110808130002","nik_kenshi":"25.2.13.09.10.006","name":"AKHMAD WAKHID IBRAHIM","gender":"Male","birth_place":"JOMBANG","blood_type":"-","birth_date":"2013-08-08","address":"Dsn. Nglele rt. 02 rw. 01 ds. Nglele kecamatan sumobito jombang","phone":"082336425598","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"1","rank":"Kyu 5","dojo_origin":"Al - fatih","city":"","bpjs_number":"351704510912","bpjs_status":"Aktif","bpjs_card":null,"event1":"1","event2":"4","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1283","nik":"3517021609130002","nik_kenshi":"25.2.13.09.10.005","name":"MUHAMMAD RAKA GARADHIKA SULFAN","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2013-09-16","address":"Dsn. Mojongapit, Kab. Jombang","phone":"08990764442","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"1","rank":"Kyu 4","dojo_origin":"Al-Fatih Jombang","city":"","bpjs_number":"3517045109","bpjs_status":"Aktif","bpjs_card":null,"event1":"1","event2":"3","event3":"4","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1276","nik":"3578307105140004","nik_kenshi":"24.3.13.09.09.008","name":"Meysa Ayu Riswanti","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2014-05-31","address":"Pondok Benowo indah blok fh no 4 Surabaya ","phone":"083847840828","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"1","rank":"Kyu 4","dojo_origin":"DOJO MOJOWARNO ","city":"","bpjs_number":"35170451091","bpjs_status":"Aktif","bpjs_card":null,"event1":"2","event2":"3","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1270","nik":"3517044512110002","nik_kenshi":"21.1.13.09.03.022","name":"Meysha Aulia Josephine Mahardika","gender":"Female","birth_place":"jombang","blood_type":"-","birth_date":"2011-05-12","address":"Dusun nglebak rt 7 rw 4 kecamatan bareng kab jombang","phone":"0895428464612","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 4","dojo_origin":"koramil mojowarno","city":"","bpjs_number":"351704510","bpjs_status":"Aktif","bpjs_card":null,"event1":"27","event2":"24","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1280","nik":"3517192911090002","nik_kenshi":"24.3.13.09.10.017","name":"Daniswara Gaozan","gender":"Male","birth_place":"Denpasar","blood_type":"AB","birth_date":"2009-11-29","address":"Rt.17 Rw.5 Dsn.Mayangan Ds.Mayangan Kec.Jogoroto Kab.jombang","phone":"087755819593","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 4","dojo_origin":"Dojo SMANSA","city":"","bpjs_number":"3517045109122","bpjs_status":"Aktif","bpjs_card":null,"event1":"14","event2":"34","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1275","nik":"3517096004090003","nik_kenshi":"24.3.13.09.07.005","name":"APRILLIA EKA NURAINI","gender":"Female","birth_place":"JOMBANG","blood_type":"AB","birth_date":"2009-04-20","address":"JL. KAPTEN TANDEAN NO. 151\\nRT\\/RW:004\\/005\\nDESA:PULO LOR\\nKECAMATAN:JOMBANG","phone":"081333098319","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 3","dojo_origin":"DOJO SMPN 1 JOMBANG","city":"","bpjs_number":"351704510912","bpjs_status":"Aktif","bpjs_card":null,"event1":"24","event2":"33","event3":"26","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1277","nik":"3517022506100002","nik_kenshi":"23.3.13.09.04.005","name":"Ganendra Waradana Prayuda","gender":"Male","birth_place":"jombang","blood_type":"-","birth_date":"2010-05-25","address":"dusun legundi desa gempolegundi RT.008 RW.003 kec.gudo kab.jombang","phone":"085645781048","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 3","dojo_origin":"dojo smpn 1 gudo","city":"","bpjs_number":"3517045109","bpjs_status":"Aktif","bpjs_card":null,"event1":"17","event2":"14","event3":"34","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1285","nik":"3517094110090004","nik_kenshi":"23.2.13.09.07.002","name":"AZZAHRA ADINDA PUTRI ","gender":"Female","birth_place":"10","blood_type":"-","birth_date":"2009-10-01","address":"JL.PATIMURA II NO.13\\/ A DUSUN SENGON 1\\nDESA: SENGON\\nKECAMATAN: JOMBANG ","phone":"083830504420","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 2","dojo_origin":"DOJO SMPN 1 JOMBANG ","city":"","bpjs_number":"3517045109","bpjs_status":"Aktif","bpjs_card":null,"event1":"25","event2":"33","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1269","nik":"3517024708090003","nik_kenshi":"23.2.13.09.04.003","name":"ARIFAH DWI ARDIYANTI","gender":"Female","birth_place":"JOMBANG,JAWA TIMUR","blood_type":"-","birth_date":"2009-08-07","address":"DSN PESANTREN, DS KREMBANGAN,KC GUDO, KBP JOMBANG","phone":"085648235223","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 3","dojo_origin":"DOJO SMPN 1 GUDO","city":"","bpjs_number":"0002809020508","bpjs_status":"Aktif","bpjs_card":null,"event1":"34","event2":"33","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1272","nik":"3517091704110002","nik_kenshi":"24.3.13.09.10.013","name":"Umar Alfaruq","gender":"Male","birth_place":"Jombang","blood_type":"A","birth_date":"2011-04-17","address":"Griya kencana Mulya G-4, RT 002\\/RW 013, Candimulyo, Jombang","phone":"085648781950","photo":null,"existing_photo_path":null,"current_weight":"44","weight_group_id":"1","age_group":"3","rank":"Kyu 2","dojo_origin":"Al-Fatih Jombang","city":"","bpjs_number":"3517045109","bpjs_status":"Aktif","bpjs_card":null,"event1":"15","event2":"16","event3":"32","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1278","nik":"3517095910090001","nik_kenshi":"21.3.13.09.07.001","name":"AISYAH ZASKIANDRA JASMINE","gender":"Female","birth_place":"JOMBANG","blood_type":"-","birth_date":"2009-10-19","address":"DSN : DENANYAR\\nRT\\/RW:14\\/07\\nDESA:DENANYAR \\nKECAMATAN:JOMBANG","phone":"085730826675","photo":null,"existing_photo_path":null,"current_weight":"68","weight_group_id":"6","age_group":"3","rank":"Kyu 1","dojo_origin":"DOJO GMK ","city":"","bpjs_number":"001288164925","bpjs_status":"Aktif","bpjs_card":null,"event1":"30","event2":"25","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1286","nik":"3517032404090001","nik_kenshi":"21.1.13.09.03.025","name":"Muhammad Ervin Abigail","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2009-04-24","address":"Dsn.Badang Kec. Ngoro Jombang ","phone":"085335995774","photo":null,"existing_photo_path":null,"current_weight":"58","weight_group_id":"4","age_group":"3","rank":"Kyu 1","dojo_origin":"SMANERO","city":"","bpjs_number":"0002971118722","bpjs_status":"Aktif","bpjs_card":null,"event1":"20","event2":"15","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1284","nik":"3517020207080002","nik_kenshi":"22.2.13.09.04.022","name":"RISQI EKA ERNADI","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2008-07-02","address":"Dsn Gempol kerep, des krembangan , kec gudo, kab Jombang","phone":"085755742818","photo":null,"existing_photo_path":null,"current_weight":"49","weight_group_id":"2","age_group":"3","rank":"Kyu 1","dojo_origin":"Dojo Gudo","city":"","bpjs_number":"25065699297","bpjs_status":"Aktif","bpjs_card":null,"event1":"18","event2":"32","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1282","nik":"3517020104090002","nik_kenshi":"23.2.13.09.07.005","name":"MUHAMMAD SAMUDERA GENETIKA SULFAN","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2009-04-01","address":"Dsn. Mojongapit, Kab. Jombang","phone":"08990764442","photo":null,"existing_photo_path":null,"current_weight":"53.8","weight_group_id":"3","age_group":"3","rank":"Kyu 1","dojo_origin":"SMPN 1 Jombang","city":"","bpjs_number":"6539892762","bpjs_status":"Aktif","bpjs_card":null,"event1":"19","event2":"31","event3":"32","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1273","nik":"3517194507100003","nik_kenshi":"23.2.13.09.07.009","name":"Ochie Dila Aurelia ","gender":"Female","birth_place":"Jombang","blood_type":"-","birth_date":"2010-05-07","address":"JL. Sentot Prawirodirjo GG IV","phone":"085708855774","photo":null,"existing_photo_path":null,"current_weight":"53","weight_group_id":"3","age_group":"3","rank":"Kyu 1","dojo_origin":"SMPN 1 Jombang","city":"","bpjs_number":"3517194507100003","bpjs_status":"Aktif","bpjs_card":null,"event1":"29","event2":"31","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1274","nik":"3172024909101013","nik_kenshi":"23.2.13.09.04.004","name":"arum shelvia fitri","gender":"Female","birth_place":"jakarta","blood_type":"-","birth_date":"2010-09-09","address":"dsn.kepuhrejo, rt04\\/rw02, ds. sukopinggir, kec. gudo","phone":"089512258640","photo":null,"existing_photo_path":null,"current_weight":"47","weight_group_id":"2","age_group":"3","rank":"Kyu 3","dojo_origin":"gudo","city":"","bpjs_number":"6325364537457","bpjs_status":"Aktif","bpjs_card":null,"event1":"28","event2":"26","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1268","nik":"3517026703080001","nik_kenshi":"22.2.13.09.04.027","name":"ZULVA AINUN ZASKIA ","gender":"Female","birth_place":"JOMBANG","blood_type":"-","birth_date":"2008-03-27","address":"DSN : SEKARU \\nRT\\/RW:002\\/001\\nDESA:SUKOPINGGIR \\nKECAMATAN:GUDO ","phone":"081958466795","photo":null,"existing_photo_path":null,"current_weight":"53","weight_group_id":"3","age_group":"4","rank":"Kyu 1","dojo_origin":"Dojo Gudo","city":"","bpjs_number":"542526336","bpjs_status":"Aktif","bpjs_card":null,"event1":"46","event2":"43","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1287","nik":"3517080701080005","nik_kenshi":"24.3.13.09.01.010","name":"Mokhamad Ilyas Rosyid ","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2008-07-01","address":"DSN BALONGBESUK \\nRT\\/RW:01\\/04\\nDESA:BALONGBESUK\\nKECAMATAN:DIWEK","phone":"085702418293","photo":null,"existing_photo_path":null,"current_weight":"63","weight_group_id":"5","age_group":"4","rank":"Kyu 4","dojo_origin":"DOJO SMADA ","city":"","bpjs_number":"0001043962086","bpjs_status":"Aktif","bpjs_card":null,"event1":"40","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true}],"matchTechniques":{"21":[],"16":[231,232,233,234,223,235],"32":[5,208,209,210,211,4],"34":[5,175,207,173,174,28],"33":[5,171,206,173,174,28],"6":[],"2_6":[5,8,3,10,9,4],"7":[],"3_7":[4,8,10,3,9,5],"1":[],"4_1":[28,14,27,32,8,4],"5_1":[5,10,8,6,9,4],"2":[],"6_2":[5,10,8,6,9,4],"27":[],"24":[],"7_24":[4,8,28,27,9,5],"14":[],"8_14":[5,10,8,6,9,4],"9_24":[4,8,6,9,3,5],"17":[],"10_14":[5,8,28,6,9,4],"3":[212,213,214,215,216,217],"4":[218,213,214,215,216,217],"15":[],"0_15":[219,220,221,222,223,224],"25":[],"11_25":[7,8,5,10,186,4],"31":[237,238,222,240,241,223],"26":[225,226,227,228,229,230],"13_15":[7,8,5,10,186,4],"30":[],"14_25":[7,8,5,10,186,4],"20":[],"15_15":[7,8,5,10,186,4],"18":[],"19":[],"29":[],"28":[],"46":[],"43":[],"20_43":[7,8,5,10,186,4],"40":[]},"payment_method":"Tunai"}	verified
62	18	7400000.00	7400576.00	576	Tunai	KEMPO-ADUIZ	verified	\N	Ya	2026-05-20 11:13:14	2026-06-12 14:20:24	{"contingent_name":"BANGKALAN A","contingent_city":"KABUPATEN BANGKALAN","leader_name":"ASTRIT RABECA YOLANDA","leader_phone":"08135367233","leader_email":"astritrabeca21@gmail.com","address":"jln. trunojoyo no 16, pejagan , kec : bangkalan, kab : bangkalan, ","officials":[{"official_id":13,"name":"MALIK","role":"Official","phone":"081332718533"},{"official_id":14,"name":"RACHMAD","role":"Official","phone":"081244283071"}],"athletes":[{"athlete_id":1213,"nik":"3526036106050002","nik_kenshi":"24.1.13.13.02.007","name":"ASTRIT RABECA YOLANDA","gender":"Female","birth_place":"BANGKALAN ","blood_type":"AB+","birth_date":"2005-06-21","address":"JL. NAGASARI NO 13 BURNEH ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/7xzRq8htjTs9e0BoODcStK5inHj4h9mloE2TIjec.jpg","current_weight":"48.00","weight_group_id":2,"age_group":4,"rank":"Kyu 1","dojo_origin":"GALIS BANGKALAN ","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":51,"event2":45,"event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1214,"nik":"3526032708070002","nik_kenshi":"24.1.13.13.02.005","name":"OCTA FIANDAR","gender":"Male","birth_place":"SURABAYA","blood_type":"O+","birth_date":"2007-10-27","address":"JL. GEMBIRA NO 37","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/TfYegSpyaK7xgdcnCkAMkc7OzKfSzPs4TE0pTvB9.jpg","current_weight":"59.00","weight_group_id":4,"age_group":4,"rank":"Kyu 1","dojo_origin":"GALIS BANGKALAN ","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":36,"event2":39,"event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1215,"nik":"3526031404090001","nik_kenshi":"25.2.13.13.03.005","name":"SYAIFUL ROHMAN ","gender":"Male","birth_place":"BANGKALAN","blood_type":"A","birth_date":"2009-04-14","address":"JL.KH.ACH.MUNIF NO 03","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/TfBjHgBzqS7OGu1X9PyKeg8nqrADxoyhHIrj9Qhl.jpg","current_weight":"44.00","weight_group_id":1,"age_group":3,"rank":"Kyu 4","dojo_origin":"GALIS BANGKALAN","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":17,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1216,"nik":"3526017110080002","nik_kenshi":"24.3.13.13.03.003","name":"DIANA OCTHA VELA","gender":"Female","birth_place":"BANGKALAN","blood_type":"B","birth_date":"2008-10-31","address":"JL.KH.MOH TOHA GG 2","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/ATQTw9IcvWVTJbTk4DIKNkb7kUqEEvomiCUZvFza.jpg","current_weight":"40.00","weight_group_id":1,"age_group":3,"rank":"Kyu 4","dojo_origin":"TRUNOJOYO","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":24,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1217,"nik":"3526016910080001","nik_kenshi":"24.3.13.13.03.010","name":"NAILA","gender":"Female","birth_place":"BANGKALAN","blood_type":"O","birth_date":"2008-10-29","address":"JL. RAYA BANCARAN ","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/1WBxmBBQJk3hdhtTJ06OTp46UIzFEQ7n34PEKU5l.jpg","current_weight":"44.00","weight_group_id":1,"age_group":3,"rank":"Kyu 3","dojo_origin":"TRUNOJOYO","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":27,"event2":"24","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1218,"nik":"3515130107110004","nik_kenshi":"25.3.13..13.02.001","name":"MOHAMAD RIZQI WIRA PUTRA","gender":"Male","birth_place":"SIDOARJO","blood_type":"O","birth_date":"2011-07-01","address":"PERUM GRAHA MENTARI BLOCK C 8","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/jlGtA1K5DkMH2NfCPZQjJrWd2HHbTq3H69r7fLOl.jpg","current_weight":"55.00","weight_group_id":3,"age_group":2,"rank":"Kyu 6","dojo_origin":"GALIS","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":"new","nik":"3526033004080001","nik_kenshi":"24.1.13.13.02.001","name":"ACH.FAIZ PUTRA PRATAMA JIANZAH ","gender":"Male","birth_place":"BANGKALAN ","blood_type":"B+","birth_date":"2008-04-30","address":"JL. PAHLAWAN NO 242 ","phone":"","photo":null,"existing_photo_path":null,"current_weight":"46","weight_group_id":"2","age_group":"4","rank":"Kyu 1","dojo_origin":"GALIS BANGKALAN ","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"37","event2":"51","event3":"","identity_document":null,"is_master_found":false,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"new","nik":"3526181809090003","nik_kenshi":"25.2.13.13.02.002","name":"WIRA ALFI QOLBI NURAN","gender":"Male","birth_place":"BANGKALAN ","blood_type":"B+","birth_date":"2009-09-18","address":"JL.ASRAMA KODIM","phone":"","photo":null,"existing_photo_path":null,"current_weight":"59","weight_group_id":"4","age_group":"3","rank":"Kyu 4","dojo_origin":"GALIS BANGKALAN","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"20","event2":"","event3":"","identity_document":null,"is_master_found":false,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"new","nik":"3526044701150001","nik_kenshi":"25.2.13.13.02.001","name":"NISRINA KHAYLA ZHAFIRAH","gender":"Female","birth_place":"BANGKALAN ","blood_type":"B","birth_date":"2015-01-07","address":"KARSON REGENCY BLOK D NO 09","phone":"","photo":null,"existing_photo_path":null,"current_weight":"49","weight_group_id":"2","age_group":"1","rank":"Kyu 6","dojo_origin":"GALIS BANGKALAN ","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"2","event2":"","event3":"","identity_document":null,"is_master_found":false,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"new","nik":"3573021001080003","nik_kenshi":"24.1.13.13.02.002","name":"DIMAS DZAKY ","gender":"Male","birth_place":"MALANG","blood_type":"O","birth_date":"2008-10-01","address":"JL. PONDOK HALIM 2 BLOK CI \\/ 27","phone":"","photo":null,"existing_photo_path":null,"current_weight":"70","weight_group_id":"6","age_group":"4","rank":"Kyu 3","dojo_origin":"GALIS BANGKALAN ","city":"","bpjs_number":"00000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"35","event2":"41","event3":"","identity_document":null,"is_master_found":false,"show_fields":true,"join_other_age_group":false,"event_age_group":""}],"matchTechniques":{"51":[181,182,189,223,106,287],"45":[],"36":[119,20,5,50,7,10],"39":[],"17":[],"24":[8,3,4,10,45,9],"27":[],"6":[3,8,23,36,47,4],"37":[],"40":[],"20":[],"2":[3,8,16,31,34,4],"35":[5,9,6,8,3,10],"41":[],"1_36":[253,6,183,257,255,7],"3_24":[8,3,4,10,76,9],"4_24":[4,5,10,3,6,8],"5_6":[3,8,16,31,34,4],"8_2":[3,8,16,15,34,4],"9_35":[5,9,6,8,3,10]},"payment_method":"Tunai"}	verified
70	28	9200000.00	9200420.00	420	Tunai	KEMPO-ZAZNM	verified	\N	Ya	2026-05-28 12:09:44	2026-06-12 14:20:24	{"contingent_name":"Surabaya B","contingent_city":"Surabaya","leader_name":"Manager Surabaya B","leader_phone":"081231375198","leader_email":"surabaya.b@smart-perkemi.id","address":"Jl. Jambangan Baru no 3","officials":[{"official_id":27,"name":"Yosia C Decky","role":"Manajer Tim","phone":"081231375198"},{"official_id":28,"name":"Agung Yongki F","role":"Official","phone":"085178433594"}],"athletes":[{"athlete_id":1303,"nik":"3316050809130003","nik_kenshi":"22.3.13.01.20.009","name":"Mikael Eko Grestrianto Aji","gender":"Male","birth_place":"Surabaya","blood_type":"B","birth_date":"2013-09-08","address":"Kutisari Townhouse 1, Jalan Kutisari Utara 3A No.10","phone":"0811310552","photo":null,"existing_photo_path":"athlete_photos\\/qEDuBRNhBdSoIBqWxIdSqci9mg1YLmP5VTFOMw8Q.jpg","current_weight":"40.00","weight_group_id":1,"age_group":1,"rank":"Kyu 3","dojo_origin":"Dojo PJB - PLN Nusantara Power","city":"","bpjs_number":"23066900616","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":1,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1305,"nik":"3578126404140002","nik_kenshi":"   \\t 22.3.13.01.24.012","name":"Alyssa Putri Djumaidah","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2014-04-24","address":"Jl. Teluk Nibung Timur 4 no 48 ","phone":"-","photo":null,"existing_photo_path":"athlete_photos\\/aICBj8NjCv3j8kYWbrBQXsyxo7E6KpLTxSoXUPmm.png","current_weight":"40.00","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"Dojo Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":5,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1307,"nik":"3578126404140001","nik_kenshi":"22.3.13.01.24.011","name":"Aisyah Putri Jamiah","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2014-04-24","address":"Jl. Teluk Nibung Timur 4 no 48 ","phone":"-","photo":null,"existing_photo_path":"athlete_photos\\/CJ7IXRLfezdrr9ZKZgiL8sc4R6Nx14kmsmyfg1aD.png","current_weight":"40.00","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"Dojo Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":5,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1309,"nik":"3578121705120002","nik_kenshi":"23.3.13.01.24.005","name":"Javier Zaidan Kahar","gender":"Male","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2012-05-17","address":"Jl. Teluk Bone Tengah No.9A","phone":"0821-4385-2756","photo":null,"existing_photo_path":"athlete_photos\\/l6VBwDRFC9Mtvnsy2W6PnT4BIgPdjJDTWJqLlcfE.jpg","current_weight":"65.00","weight_group_id":5,"age_group":2,"rank":"Kyu 3","dojo_origin":"Dojo Perak ","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":"1310","nik":"3578186302120002","nik_kenshi":"25.3.13.01.09.002","name":"Aura Salsabilla","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2012-02-23","address":"Lidah wetan RT 0003\\/RW 003","phone":"085717966264","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 5","dojo_origin":"Dojo UNESA","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"7","event2":"9","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1313","nik":"3578230101088891","nik_kenshi":"25.3.13.01.08.003","name":"Athalia Betaria Samosir","gender":"Female","birth_place":"Surabaya","blood_type":"O","birth_date":"2012-06-06","address":"Ketintang Madya II No. 36, Surabaya","phone":"082131433193","photo":null,"existing_photo_path":null,"current_weight":"40","weight_group_id":"1","age_group":"2","rank":"Kyu 4","dojo_origin":"Dojo Petra","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"9","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1318","nik":"3578120710100001","nik_kenshi":"24.3.13.01.08.002","name":"Reinhard Henokh Stardani Panambunan ","gender":"Male","birth_place":"Surabaya","blood_type":"A","birth_date":"2010-10-07","address":"Taman Rivera Regency blok N no-18 ","phone":"085331107048","photo":null,"existing_photo_path":null,"current_weight":"68","weight_group_id":"6","age_group":"3","rank":"Kyu 3","dojo_origin":"Dojo UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"22","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1319","nik":"3515181612090003","nik_kenshi":"24.3.13.01.08.010","name":"Delon Nathanael Tandio","gender":"Male","birth_place":"Surabaya","blood_type":"A","birth_date":"2009-12-16","address":"Jalan Durian 2 e474","phone":"082132027478","photo":null,"existing_photo_path":null,"current_weight":"75","weight_group_id":"7","age_group":"3","rank":"Kyu 3","dojo_origin":"Dojo Petra","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"23","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1320","nik":"3578231910090001","nik_kenshi":"21.1 13.01.20.003","name":"Fariz Fernando","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2009-05-19","address":"Jl.Kebonsari Tengah 47-B","phone":"0881027728257","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"3","rank":"Kyu 1","dojo_origin":"Dojo PLN-NP (PJB)","city":"","bpjs_number":"24073019127","bpjs_status":"Aktif","bpjs_card":null,"event1":"17","event2":"31","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1321","nik":"3578124909100003","nik_kenshi":"16.2.13.01.24.001","name":"Bilvina Aqila Saumifathiyah","gender":"Female","birth_place":"Surabaya","blood_type":"B","birth_date":"2010-09-09","address":"Jl. Teluk Penanjung No. 37","phone":"085806989699","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"3","rank":"Kyu 1","dojo_origin":"Dojo Perak","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"31","event2":"25","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1322","nik":"3404066702100003","nik_kenshi":" 25.3.13.01.08.001","name":"Abygael Kanaia Aditomo","gender":"Female","birth_place":"Surabaya","blood_type":"A","birth_date":"2010-02-27","address":"Griya Taman Asri Blok.DD-04, Tawangsari, Taman, Sidoarjo","phone":"081548326000","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"3","rank":"Kyu 4","dojo_origin":"Dojo Petra","city":"","bpjs_number":"0001542965196","bpjs_status":"Aktif","bpjs_card":null,"event1":"27","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1323","nik":"3578125906100003","nik_kenshi":"23.3.13.01.24.003","name":"QUENASHA GENDHIS GUPITA","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2010-06-19","address":"PRAPAT KURUNG TEGAL TEGAL NO.3B","phone":"082131628785","photo":null,"existing_photo_path":null,"current_weight":"55","weight_group_id":"3","age_group":"3","rank":"Kyu 3","dojo_origin":"Dojo Perak","city":"","bpjs_number":"24073019168","bpjs_status":"Aktif","bpjs_card":null,"event1":"29","event2":"26","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1324","nik":"3578154603100001","nik_kenshi":"22.3.13.01.24.008","name":"Jasmine Nur Erika","gender":"Female","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2010-03-06","address":"Jl. Tambak Wedi Baru Utara 18B\\/28","phone":"081231818808","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"3","rank":"Kyu 3","dojo_origin":"Dojo Perak","city":"","bpjs_number":"24073019150","bpjs_status":"Aktif","bpjs_card":null,"event1":"26","event2":"30","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1325","nik":"3578094210040001","nik_kenshi":"12.2.13.01.13.003","name":"Anantya Okazzahra Santoso","gender":"Female","birth_place":"Surabaya","blood_type":"O","birth_date":"2004-10-02","address":"Jl. Semolowaru Elok Blok G\\/8 Surabaya","phone":"0816904281","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"4","rank":"Kyu 1","dojo_origin":"Dojo ITS","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"48","event2":"43","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""}],"matchTechniques":{"0_1":[253,254,255,256,257,258],"5":[245,323,251,39,23,252],"3_6":[253,286,255,256,257,258],"9":[245,246,247,39,23,252],"7":[],"4_7":[253,254,14,256,257,258],"22":[],"23":[],"17":[],"31":[148,287,288,49,286,151],"25":[],"9_25":[7,147,253,255,257,258],"27":[],"29":[],"26":[289,163,290,282,291,292],"30":[],"48":[],"43":[],"13_43":[7,147,253,255,257,258]},"payment_method":"Tunai"}	verified
72	35	5500000.00	5500599.00	599	Tunai	KEMPO-CANK1	pending	\N	Ya	2026-06-03 05:15:09	2026-06-12 12:12:19	{"contingent_name":"Sidoarjo","contingent_city":"Sidoarjo","leader_name":"I Ketut Pramantara","leader_phone":"082141470867","leader_email":"perkemisidoarjo@gmail.com","address":"Sidoarjo","officials":[{"official_id":32,"name":"I Ketut Pramantara","role":"Official","phone":"082141470867"}],"athletes":[{"athlete_id":1350,"nik":"3175031504131005","nik_kenshi":" 25.3.13.08.05.003","name":"Nashiful Fatih Abinawa","gender":"Male","birth_place":"Jakarta","blood_type":"A","birth_date":"2013-04-15","address":"Pondok Nirwana Anggaswangi, Cluster Amaryllis Blok L no.5, Sidoarjo","phone":"0878-5448-8881","photo":null,"existing_photo_path":"athlete_photos\\/IFRVe04OGX697ICpNSjDPRMSVLeiNbgZQsMR09yV.jpg","current_weight":"64.00","weight_group_id":5,"age_group":2,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1351,"nik":"3515145111111002","nik_kenshi":"25.1.13.08.05.002","name":"Endyta Salsabilla","gender":"Female","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2011-11-11","address":"Perum Permata Sukodono Raya Blok C1 No33, Sidoarjo\\n","phone":"085198353732","photo":null,"existing_photo_path":"athlete_photos\\/MwAA8pnRgrYIbHbsy5wWFNAEtRZJ5Q4Oh9HCFeWI.jpg","current_weight":"45.00","weight_group_id":1,"age_group":3,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":27,"event2":31,"event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1353,"nik":"3578110703090001","nik_kenshi":"25.1.13.08.05.004","name":"Louis Bintang Dirgantara Lazaro","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2009-03-07","address":"Citra Fajar Golf AT 2000 \\/ B2030, Sidoarjo\\n","phone":" 088989083257","photo":null,"existing_photo_path":"athlete_photos\\/TvC1e6Zz7HxjtAq4WWo0WTfVCW6eJkBZDyAJ57cm.jpg","current_weight":"57.00","weight_group_id":4,"age_group":3,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":20,"event2":16,"event3":32,"join_other_age_group":false,"event_age_group":""},{"athlete_id":1355,"nik":"3515161905090002","nik_kenshi":"25.1.13.08.05.006","name":"Rafka Adrian Ichiro Sunyoto ","gender":"Male","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2009-05-19","address":"Punggul rt03\\/rw02 no.11 Gedangan Sidoarjo","phone":"081334677743","photo":null,"existing_photo_path":"athlete_photos\\/XGcyzW8wDncGQtCII6QcMeU4zJZQ1fbn7lQMicCH.jpg","current_weight":"49.00","weight_group_id":2,"age_group":3,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":17,"event2":32,"event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":1356,"nik":"3515141304100004","nik_kenshi":"25.1.13.08.05.005","name":"Muhammad Fattan Altaf","gender":"Male","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2010-04-13","address":"Perum Permata Sukodono Raya Blok C1 No33, Sidoarjo","phone":"081217228679","photo":null,"existing_photo_path":"athlete_photos\\/J5nTqub8YtlmqeyKPiFN45oy2XPUNpyNvIlBrl30.jpg","current_weight":"69.50","weight_group_id":6,"age_group":3,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":22,"event2":32,"event3":31,"join_other_age_group":false,"event_age_group":""},{"athlete_id":1357,"nik":"3578111505110001","nik_kenshi":"25.1.13.08.05.008","name":"William Tranggana Samudra Lazaro","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2011-05-15","address":"Citra Fajar Golf AT 2000 \\/ B2030, Sidoarjo","phone":"088989452277","photo":null,"existing_photo_path":"athlete_photos\\/YMpN9lt7ndIJeWBJURFjRPJ54FUwIreTBzH157om.jpg","current_weight":"53.00","weight_group_id":3,"age_group":3,"rank":"Kyu 5","dojo_origin":"Pagerwojo, Sidoarjo","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":19,"event2":16,"event3":32,"join_other_age_group":false,"event_age_group":""}],"matchTechniques":{"0_6":[335,336,337,338,339,340],"1_27":[],"31":[330,331,332,327,328,329],"2_20":[],"16":[330,331,332,341,328,329],"32":[344,342,343,345,346,347],"3_17":[],"4_22":[],"5_19":[]},"payment_method":"Tunai"}	pending
65	33	8900000.00	8900533.00	533	Tunai	KEMPO-IUIF2	verified	\N	Ya	2026-05-24 06:41:09	2026-06-12 14:20:24	{"contingent_name":"TUBAN","contingent_city":"KABUPATEN","leader_name":"APRILIA HANA PRATIWI","leader_phone":"081335338336","leader_email":"apriliahana10pr@gmail.com","address":"Ds. Prunggahan Wetan ","officials":[{"official_id":18,"name":"APRILIA HANA PRATIWI","role":"Manajer Tim","phone":"081335338336"},{"official_id":19,"name":"RANDI","role":"Official","phone":"082245138237"}],"athletes":[{"athlete_id":1242,"nik":"3523150706090002","nik_kenshi":"15.3.13.12.01.006","name":"BUSHIDO AJIE SAHPUTRA","gender":"Male","birth_place":"TUBAN","blood_type":"-","birth_date":"2010-06-07","address":"DESA PRUNGGAHAN WETAN KECAMATAN SEMANDING KABUPATEN TUBAN","phone":"","photo":null,"existing_photo_path":"athlete_photos\\/f7ABb5tIQEWjVpH4stDKo1Zod4RjLJyGM4YYVBL4.png","current_weight":"63.00","weight_group_id":5,"age_group":3,"rank":"Kyu 1","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":15,"event2":21,"event3":""},{"athlete_id":"1243","nik":"3523152409110002","nik_kenshi":"21.1.13.12.03.008","name":"LUTFI RIZKI WAHYU UTAMA","gender":"Male","birth_place":"TUBAN","blood_type":"-","birth_date":"2011-09-25","address":"DESA TEGALAGUNG RT 02 RW 03\\nSEMANDING - TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 2","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"6","event2":"8","event3":"13","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1244","nik":"3523152508110004","nik_kenshi":"21.1.13.12.03.015","name":"TAHFAQUL MANAN RAMADHANI","gender":"Male","birth_place":"TUBAN","blood_type":"-","birth_date":"2011-08-25","address":"DESA TEGALAGUNG RT 01 RW 03\\nSEMANDING-TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 2","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"8","event2":"10","event3":"13","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1245","nik":"3523156104110002","nik_kenshi":"21.1.13.12.03.013","name":"SABRINA NASIKHATUL HUSNA","gender":"Female","birth_place":"TUBAN","blood_type":"-","birth_date":"2011-04-21","address":"DESA TEGALAGUNG RT 01 RW 03\\nSEMANDING - TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 2","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"7","event2":"10","event3":"12","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1246","nik":"3523156412110001","nik_kenshi":"25.1.13.12.03.002","name":"KAYSHA CAHYANI SYAM SORAYA","gender":"Female","birth_place":"TUBAN","blood_type":"-","birth_date":"2013-12-24","address":"DUSUN GEMPOL\\nDESA GENAHARJO, SEMANDING - TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"9","event2":"12","event3":"10","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1247","nik":"3523155504110001","nik_kenshi":"25.1.13.12.03.004","name":"SHAFA HERMAWAN","gender":"Female","birth_place":"TUBAN","blood_type":"-","birth_date":"2013-04-15","address":"DESA PRUNGGAHAN WETAN RT 03 RW 01\\nKECAMATAN SEMANDING- TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"9","event2":"12","event3":"13","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1248","nik":"3523166005110002","nik_kenshi":"25.1.13.12.03.001","name":"AURORA FELICIA HERMA PUTRI","gender":"Female","birth_place":"TUBAN","blood_type":"-","birth_date":"2013-05-20","address":"JL. BASUKI RAHMAT GG. SERUT NO. 27\\n RONGGOMULYO, TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"7","event2":"12","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1250","nik":"3523156608140001","nik_kenshi":"25.1.13.12.03.003","name":"SALWA AGUSTIN ASSYIFA'","gender":"Female","birth_place":"TUBAN","blood_type":"-","birth_date":"2016-06-28","address":"DESA BEJAGUNG RT 01 RW 01 \\nSEMANDING-TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"1","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"2","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1249","nik":"3523152009120001","nik_kenshi":"24.3.13.12.03.006","name":"RAFAEL MILANO ARDITIA","gender":"Male","birth_place":"TUBAN","blood_type":"-","birth_date":"2013-09-20","address":"DESA PRUNGGAHAN WETAN RT 01 RW 02\\nSEMANDING, TUBAN","phone":"","photo":null,"existing_photo_path":null,"current_weight":"50","weight_group_id":"2","age_group":"2","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"6","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"new","nik":"5308181701060001","nik_kenshi":"","name":"WAHYUDIN SAPUTRA UMAR","gender":"Male","birth_place":"ENDE","blood_type":"","birth_date":"2006-01-17","address":"JALAN WOLOARE KOTA RATU KAB. ENDE","phone":"","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"4","rank":"Kyu 2","dojo_origin":"DOJO RONGGOLAWE","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"40","event2":"35","event3":"","identity_document":null,"is_master_found":false,"show_fields":true},{"athlete_id":"new","nik":"3517190109040004","nik_kenshi":"16.1.13.09.05.018","name":"MUHAMMAD RHELZA ARIVIRGA","gender":"Male","birth_place":"BANDUNG","blood_type":"","birth_date":"2004-09-01","address":"DESA SUMBERMULYO KEC. JOGOROTO KAB. JOMBANG","phone":"","photo":null,"existing_photo_path":null,"current_weight":"67","weight_group_id":"6","age_group":"4","rank":"Kyu 2","dojo_origin":"DOJO RONGGOLAWE","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"41","event2":"35","event3":"","identity_document":null,"is_master_found":false,"show_fields":true},{"athlete_id":"new","nik":"1371110607050009","nik_kenshi":"17.3.04.01.09.056","name":"EGA PRAMUDYA","gender":"Male","birth_place":"BALAI GADANG","blood_type":"","birth_date":"2005-07-06","address":"BALAI GADANG KECAMATAN KOTO TANGAH KOTA PADANG","phone":"","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"4","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"35","event2":"","event3":"","identity_document":null,"is_master_found":false,"show_fields":true},{"athlete_id":"1254","nik":"6408041701060008","nik_kenshi":"","name":"ANTONIUS DWIYANTO AGUN","gender":"Male","birth_place":"RENTUNG","blood_type":"-","birth_date":"2006-01-17","address":"PONG LALE KECAMATAN RUTENG KAB. MANGGARAI","phone":"","photo":null,"existing_photo_path":null,"current_weight":"72","weight_group_id":"6","age_group":"4","rank":"Kyu 3","dojo_origin":"DOJO RONGGOLAWE TUBAN","city":"","bpjs_number":"000000000000","bpjs_status":"Aktif","bpjs_card":null,"event1":"35","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true}],"matchTechniques":{"15":[8,186,5,6,10,4],"21":[],"6":[8,186,5,6,10,4],"8":[185,204,197,189,191,192],"13":[5,205,180,195,196,4],"10":[185,204,197,189,191,192],"7":[8,186,6,10,5,4],"12":[5,205,180,195,199,4],"9":[200,205,201,202,199,203],"2":[5,10,8,6,9,4],"39":[],"40":[],"8_6":[5,10,8,6,9,4],"35":[],"10_35":[8,193,6,10,5,4],"41":[],"9_35":[8,186,5,6,10,4],"11_35":[5,10,8,6,9,4],"12_35":[5,10,8,6,9,4],"0_15":[8,186,5,6,10,4],"1_6":[8,186,5,6,10,4],"3_7":[8,186,5,6,10,4],"6_7":[5,10,8,6,9,4],"7_2":[5,10,8,6,9,4]},"payment_method":"Tunai"}	verified
73	24	9400000.00	9400165.00	165	Tunai	KEMPO-XC6ZS	verified	\N	Ya	2026-06-04 05:57:09	2026-06-12 14:20:24	\N	verified
59	16	7500000.00	7500735.00	735	Tunai	KEMPO-WTAFE	verified	\N	Ya	2026-05-18 02:34:32	2026-06-12 14:20:24	{"contingent_name":"KOTA KEDIRI","contingent_city":"KOTA KEDIRI","leader_name":" Cecep Sunariya Ketua","leader_phone":"085790399330","leader_email":"cecepsunariya@gmail.com","address":"Jln. Veteran No 8 Mojoroto Kota Kediri","officials":[{"official_id":2,"name":"Cecep Sunariya","role":"Pelatih","phone":"085790399330"},{"official_id":3,"name":"Bagus Sapro'in","role":"Pelatih","phone":""}],"athletes":[{"athlete_id":1156,"nik":"3506111107770001","nik_kenshi":"25.3.13.02.02.004","name":"RADITYA KRESNA WAHYU WISNU SAPUTRA","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2011-07-11","address":"Dsn. Tanjung RT 004 RW 003 Tanjung","phone":"-","photo":null,"existing_photo_path":null,"current_weight":"44.00","weight_group_id":1,"age_group":3,"rank":"Kyu 4","dojo_origin":"JAYABAYA ","city":"","bpjs_number":"3506111107770001","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":17,"event2":"","event3":"","join_other_age_group":false,"event_age_group":""},{"athlete_id":"1175","nik":"3506061801110002","nik_kenshi":"23.1.13.02.02.002","name":"FAWWAZ AGRIYA PUTRA","gender":"Male","birth_place":"Kediri","blood_type":"-","birth_date":"2011-01-18","address":"JLN PAHLAWAN KUSUMA BANGSA NO 114","phone":"-","photo":null,"existing_photo_path":null,"current_weight":"63","weight_group_id":"5","age_group":"3","rank":"Kyu 3","dojo_origin":"Jayabaya","city":"","bpjs_number":"3506061801110002","bpjs_status":"Aktif","bpjs_card":null,"event1":"21","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1199","nik":"3571010709170006","nik_kenshi":"19.3.13.02.02.005","name":"Muhammad Wenpy Abdi Vannorin","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2009-04-06","address":"JLN KADEMANG MOJOROTO KOTA KEDIRI","phone":"@","photo":null,"existing_photo_path":null,"current_weight":"49","weight_group_id":"2","age_group":"4","rank":"Kyu 2","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3571010709170006","bpjs_status":"Aktif","bpjs_card":null,"event1":"37","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1200","nik":"3506140105130003","nik_kenshi":"19.1.13.02.02.002","name":"M Qaishar Mirza Athariz","gender":"Male","birth_place":"Kediri","blood_type":"-","birth_date":"2013-05-01","address":"JLN PAHLAWAN KUSUMA BANGSA NO 114","phone":"","photo":null,"existing_photo_path":null,"current_weight":"49","weight_group_id":"2","age_group":"3","rank":"Kyu 3","dojo_origin":"Jayabaya","city":"","bpjs_number":"3506140105130003","bpjs_status":"Aktif","bpjs_card":null,"event1":"18","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1240","nik":"3506140104110001","nik_kenshi":"17.3.13.02.02.001","name":"M. ARGA ADYASTA RESWARA","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2011-04-01","address":"MOJOROTO KOTA KEDIRI","phone":"-","photo":null,"existing_photo_path":null,"current_weight":"59","weight_group_id":"4","age_group":"3","rank":"Kyu 2","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3506140104110001","bpjs_status":"Aktif","bpjs_card":null,"event1":"20","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1241","nik":"3571025060600006","nik_kenshi":"18.1.13.02.02.004","name":"Yohanes Anselmo Mau","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2006-06-25","address":"MOJOROTO KOTA KEDIRI","phone":"!","photo":null,"existing_photo_path":null,"current_weight":"65","weight_group_id":"5","age_group":"4","rank":"Kyu 2","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3571025060600006","bpjs_status":"Aktif","bpjs_card":null,"event1":"40","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1188","nik":"3571035211100002","nik_kenshi":"25.3.13.02.02.001","name":"Agatha Natania Lituhayu","gender":"Female","birth_place":"KEDIRI","blood_type":"-","birth_date":"2010-11-12","address":"Jl. Pamenang Vii No: B-5 Katang Kediri","phone":"0","photo":null,"existing_photo_path":null,"current_weight":"50","weight_group_id":"2","age_group":"3","rank":"Kyu 4","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3571035211100002","bpjs_status":"Aktif","bpjs_card":null,"event1":"28","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1190","nik":"3571024204130001","nik_kenshi":"23.2.13.02.02.004","name":"Anisa Zhafira Ilma","gender":"Female","birth_place":"KEDIRI","blood_type":"-","birth_date":"2013-04-02","address":"Jln.balowerti V Kediri ","phone":"1","photo":null,"existing_photo_path":null,"current_weight":"45","weight_group_id":"1","age_group":"3","rank":"Kyu 4","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3571024204130001","bpjs_status":"Aktif","bpjs_card":null,"event1":"27","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1358","nik":"3506062404090001","nik_kenshi":"24.2.13.02.02.006","name":"Muhammad Faza Nabhani","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2009-04-24","address":"KOTA KEDIRI","phone":"3","photo":null,"existing_photo_path":null,"current_weight":"55","weight_group_id":"3","age_group":"4","rank":"Kyu 3","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3506062404090001","bpjs_status":"Aktif","bpjs_card":null,"event1":"38","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true,"join_other_age_group":false,"event_age_group":""},{"athlete_id":"1198","nik":"3506042204090002","nik_kenshi":"19.2.13.02.02.005","name":"M. Revaldo Alfino Pratama","gender":"Male","birth_place":"KEDIRI","blood_type":"-","birth_date":"2009-04-22","address":"JLN PATIMURA PAGORA KOTA KEDIRI","phone":"A","photo":null,"existing_photo_path":null,"current_weight":"70","weight_group_id":"6","age_group":"4","join_other_age_group":false,"event_age_group":"","rank":"Kyu 2","dojo_origin":"JAYABAYA","city":"","bpjs_number":"3506042204090002","bpjs_status":"Aktif","bpjs_card":null,"event1":"41","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true}],"matchTechniques":{"17":[],"20":[],"39":[],"41":[],"37":[],"18":[],"19":[],"40":[],"28":[],"27":[],"38":[],"21":[]},"payment_method":"Tunai"}	verified
67	34	8500000.00	8500250.00	250	Tunai	KEMPO-NYNDD	pending	\N	Ya	2026-05-25 16:10:10	2026-06-12 12:12:19	{"contingent_name":"Gresik","contingent_city":"Kabupaten ","leader_name":"Afrizal Hardiansyah","leader_phone":"087789552607","leader_email":"perkemigresik@gmail.com","address":"Perum Regency Mayjend Sungkono Blok F-12 Kedanyang Gresik","officials":[{"official_id":20,"name":"Andik Subakti","role":"Official","phone":"085731795333"}],"athletes":[{"athlete_id":1259,"nik":"3525142512140004","nik_kenshi":"25.2.13.06.01.001","name":"Ahmad Luthfi Raihan","gender":"Male","birth_place":"Jombang","blood_type":"-","birth_date":"2014-12-25","address":"Jl Kptn Darmosugondo 12d\\/74 Indro, Gresik, Kab. Gresik","phone":"089560388060","photo":null,"existing_photo_path":"athlete_photos\\/D38CJsPZFWz28f4LYzcJDZSLNEoe2WggHzQ0ptUI.jpg","current_weight":"35.00","weight_group_id":1,"age_group":1,"rank":"Kyu 6","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758172","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":1,"event2":"","event3":""},{"athlete_id":1257,"nik":"3521124511130001","nik_kenshi":"25.2.13.06.01.003","name":"Alisha Ramadhina Rheva","gender":"Female","birth_place":"Gresik","blood_type":"-","birth_date":"2013-11-05","address":"Jl Klangka Rt01 Rw06 Perum Garden Palace, Peganden, Manyar, Kab. Gresik","phone":"08161154446","photo":null,"existing_photo_path":"athlete_photos\\/pM2996mL6TSr8eN8F85fiIDdbhs3WBD2tSRYTxQY.jpg","current_weight":"30.00","weight_group_id":1,"age_group":1,"rank":"Kyu 4","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758180","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":2,"event2":"","event3":""},{"athlete_id":"1258","nik":"3510135011160003","nik_kenshi":"25.2.13.06.01.005","name":"Belvania Rahmadina Rabbani","gender":"Female","birth_place":"Lumajang","blood_type":"-","birth_date":"2016-11-10","address":"Jl Malik Ibrahim Gang Iv No.11a Gresik, Kab. Gresik","phone":"0811347812","photo":null,"existing_photo_path":null,"current_weight":"30","weight_group_id":"1","age_group":"1","rank":"Kyu 6","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758164","bpjs_status":"Aktif","bpjs_card":null,"event1":"2","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1255","nik":"3525140205150008","nik_kenshi":"23.3.13.06.01.003","name":"Muhammad Hanif Albaihaqi Handika","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2015-05-02","address":"Margoroto Rt04\\/rw01 Ngargosari Kebomas, Kab. Gresik","phone":"085731795333","photo":null,"existing_photo_path":null,"current_weight":"35","weight_group_id":"1","age_group":"1","rank":"Kyu 2","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758198","bpjs_status":"Aktif","bpjs_card":null,"event1":"4","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1256","nik":"3525161802140001","nik_kenshi":"24.1.13.06.01.002","name":"Muhammad Aidan Nurfatah","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2014-02-18","address":"Jl. Mh Tamrin No.12, Kab. Gresik","phone":"081231273381","photo":null,"existing_photo_path":null,"current_weight":"45.2","weight_group_id":"1","age_group":"1","rank":"Kyu 2","dojo_origin":"Semen Gresik","city":"","bpjs_number":"22145606749","bpjs_status":"Aktif","bpjs_card":null,"event1":"4","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1260","nik":"3525141904130002","nik_kenshi":"24.1.13.06.01.004","name":"Muhammad El Syirazi Ariansyah ","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2013-04-19","address":"Jl. Raya Brantas No.52 Rt : 01 Rw : 05, Randuagung, Kab. Gresik","phone":"081231406735","photo":null,"existing_photo_path":null,"current_weight":"48.6","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758123","bpjs_status":"Aktif","bpjs_card":null,"event1":"6","event2":"10","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1261","nik":"3525144504120003","nik_kenshi":"24.3.13.06.01.002","name":"Dewi Lembayung","gender":"Female","birth_place":"Gresik","blood_type":"-","birth_date":"2012-04-05","address":"Jl Veteran II \\/ 33 Sidomoro, Kab. Gresik","phone":"081332478777","photo":null,"existing_photo_path":null,"current_weight":"42","weight_group_id":"1","age_group":"2","rank":"Kyu 3","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758131","bpjs_status":"Aktif","bpjs_card":null,"event1":"7","event2":"10","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1262","nik":"3525160211110003","nik_kenshi":"25.2.13.06.01.009","name":"M. Qhalid Eka Wahyu Putra","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2011-11-02","address":"Jl Dewi Sekardadu 004\\/001 Gresik, Kab. Gresik","phone":"085735126787","photo":null,"existing_photo_path":null,"current_weight":"68","weight_group_id":"6","age_group":"3","rank":"Kyu 4","dojo_origin":"Semen Gresik","city":"","bpjs_number":"25164211465","bpjs_status":"Aktif","bpjs_card":null,"event1":"22","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1264","nik":"3525050309100002","nik_kenshi":"26.1.13.06.01.002","name":"Achmad Muflich Rojabi","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2010-09-03","address":" Jl Sumari Kec Duduk Sampeyan, Kabupaten Gresik","phone":"085784420136","photo":null,"existing_photo_path":null,"current_weight":"50","weight_group_id":"2","age_group":"3","rank":"Kyu 6","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758115","bpjs_status":"Aktif","bpjs_card":null,"event1":"18","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1263","nik":"3525142712110003","nik_kenshi":"19.1.13.06.01.021","name":"Muhammad Fernando Alamsyah ","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2011-12-27","address":"Perum Bukit Randuagung Indah, Gresik","phone":"085101823235","photo":null,"existing_photo_path":null,"current_weight":"74","weight_group_id":"7","age_group":"3","rank":"Kyu 3","dojo_origin":"Semen Gresik","city":"","bpjs_number":"25164211507","bpjs_status":"Aktif","bpjs_card":null,"event1":"23","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1265","nik":"3525145802120001","nik_kenshi":"26.2.13.06.01.001","name":"Hana Bhakitah Fadiyah","gender":"Female","birth_place":"Gresik","blood_type":"-","birth_date":"2011-02-18","address":"Jl Veteran No.206 Rt07 Rw01 Segoromadu, Gresik, Gresik","phone":"085645819227","photo":null,"existing_photo_path":null,"current_weight":"47","weight_group_id":"2","age_group":"3","rank":"Kyu 6","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758149","bpjs_status":"Aktif","bpjs_card":null,"event1":"28","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1266","nik":"3525165903100002","nik_kenshi":"26.2.13.06.01.002","name":"Siti Aisyah Suhebi","gender":"Female","birth_place":"Gresik","blood_type":"-","birth_date":"2010-03-19","address":"Jl Jaksa Agung Suprapto Gg 8 No.87, Gresik","phone":"085735137425","photo":null,"existing_photo_path":null,"current_weight":"55","weight_group_id":"3","age_group":"3","rank":"Kyu 6","dojo_origin":"Semen Gresik","city":"","bpjs_number":"26067758156","bpjs_status":"Aktif","bpjs_card":null,"event1":"29","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true},{"athlete_id":"1267","nik":"3525162712090003","nik_kenshi":"23.3.13.06.01.004","name":"Nur Ahmad Teguh","gender":"Male","birth_place":"Gresik","blood_type":"-","birth_date":"2009-12-27","address":"Jl Jaksa Agung Suprapto No.79 Gresik","phone":"08132628724","photo":null,"existing_photo_path":null,"current_weight":"70","weight_group_id":"6","age_group":"4","rank":"Kyu 2","dojo_origin":"Semen Gresik","city":"","bpjs_number":"25064110411","bpjs_status":"Aktif","bpjs_card":null,"event1":"41","event2":"","event3":"","identity_document":null,"is_master_found":true,"show_fields":true}],"matchTechniques":{"0_1":[270,271,256,10,3,4],"1_2":[270,271,8,10,3,4],"2":[],"2_2":[270,271,8,10,3,4],"4":[264,265,278,274,275,276],"1":[],"6":[],"5_6":[8,10,3,5,6,4],"10":[34,265,278,279,280,277],"7":[],"6_7":[8,10,3,5,6,4],"22":[],"18":[],"23":[],"28":[],"29":[],"41":[]},"payment_method":"Tunai"}	pending
71	27	10200000.00	10200821.00	821	Tunai	KEMPO-XFXIY	verified	\N	Ya	2026-05-28 14:59:04	2026-06-12 14:20:24	{"contingent_name":"Surabaya A","contingent_city":"Surabaya","leader_name":"Manager Surabaya A","leader_phone":"081938877507","leader_email":"surabaya.a@smart-perkemi.id","address":"Jl. Jambangan Baru no 3","officials":[{"official_id":25,"name":"Bambang Harmawan","role":"Manajer Tim","phone":"081938877507"},{"official_id":26,"name":"Muhammad Nurrahman Bathik","role":"Official","phone":"081252266544"},{"official_id":31,"name":"Esa Jati Wicaksono","role":"Official","phone":"081233404190"}],"athletes":[{"athlete_id":1326,"nik":"3578082201140001","nik_kenshi":"21.1.13.01.20.002","name":"FAKHRY NAUFAL PRAMUDYA","gender":"Male","birth_place":"Surabaya","blood_type":"O","birth_date":"2014-01-22","address":"Jl. Gubeng Kertajaya 8D\\/7 Surabaya","phone":"822-3126-2244","photo":null,"existing_photo_path":"athlete_photos\\/szEa50ajXpWjpGZ2PTmn5MrrLca0jfeBiqmX8SMa.png","current_weight":"40.00","weight_group_id":1,"age_group":1,"rank":"Kyu 3","dojo_origin":"Dojo UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":1,"event2":4,"event3":""},{"athlete_id":1327,"nik":"3578126708130002","nik_kenshi":"18.3.13.01.24.006","name":"BILIZZATI ASYIFA HUMAIRA SALAVIA","gender":"Female","birth_place":"Surabaya","blood_type":"B","birth_date":"2013-08-27","address":"Jl. Teluk Penanjung No.37","phone":"085806989737","photo":null,"existing_photo_path":"athlete_photos\\/jFvUJIHRE1fcLFrCHHwD7aWFYTyqrFWLWuTtRaJX.jpg","current_weight":"50.00","weight_group_id":2,"age_group":1,"rank":"Kyu 3","dojo_origin":"Dojo Perak ","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":2,"event2":3,"event3":""},{"athlete_id":1328,"nik":"3515082812130002","nik_kenshi":"23.2.13.01.20.002","name":"Muhammad Ilham","gender":"Male","birth_place":"Surabaya","blood_type":"O","birth_date":"2013-12-28","address":"Taman Indah V\\/38 ","phone":"081232080519","photo":null,"existing_photo_path":"athlete_photos\\/JWS4QhbetQXuQXsqlnU6fgIAIjO8Y1wWcLbiKw4S.jpg","current_weight":"40.00","weight_group_id":1,"age_group":1,"rank":"Kyu 3","dojo_origin":"Dojo UWK","city":"","bpjs_number":"24073019416","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":4,"event2":3,"event3":""},{"athlete_id":1329,"nik":"3578082108120002","nik_kenshi":"21.1.13.01.20.001","name":"ADHIYASTHA YAHYA MAULANA","gender":"Male","birth_place":"Surabaya","blood_type":"O","birth_date":"2012-08-21","address":"Jl. Gubeng Kertajaya 8D\\/17 Surabaya\\n","phone":" 081230386800","photo":null,"existing_photo_path":"athlete_photos\\/vjPV88qpcxq5J0XXYnzbVg1NfM6aYhgv4Ybbdnem.png","current_weight":"40.00","weight_group_id":1,"age_group":2,"rank":"Kyu 3","dojo_origin":"Dojo UWK","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":6,"event2":8,"event3":13},{"athlete_id":1330,"nik":"3578084109110004","nik_kenshi":"22.3.13.01.20.018","name":"Kirana Bellvania Ramadhani ","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2011-09-01","address":"JL. Gunung Anyar Jaya Safira No. 5 kav 21","phone":"081959736585","photo":null,"existing_photo_path":"athlete_photos\\/jLeG7nvSbA4ZwYIU2sPObIQA6hTyXLcJtcWSwio1.jpg","current_weight":"40.00","weight_group_id":1,"age_group":2,"rank":"Kyu 3","dojo_origin":"Dojo UWK","city":"","bpjs_number":"0626220600151","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":7,"event2":9,"event3":13},{"athlete_id":1332,"nik":"3578180410110002","nik_kenshi":"22.3.13.01.09.007","name":"Raung Laksono","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2011-10-04","address":"LIDAH WETAN GG 7\\/58 A\\/KECAMATAN LAKARSANTRI SBY ","phone":"082334412507","photo":null,"existing_photo_path":"athlete_photos\\/0VbYJIPfakk3TPfb6eUlQo5zl61iS3L5uRzyM1HL.jpg","current_weight":"40.00","weight_group_id":1,"age_group":2,"rank":"Kyu 3","dojo_origin":"Dojo UNESA","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":8,"event2":10,"event3":13},{"athlete_id":1348,"nik":"3173071401160007","nik_kenshi":"24.3.13.01.08.007","name":"Anabel Sarah Maheswari","gender":"Female","birth_place":"Jakarta","blood_type":"O","birth_date":"2011-10-19","address":"Jl. Semolowaru Tengah I No 34","phone":"085888388211","photo":null,"existing_photo_path":null,"current_weight":"40.00","weight_group_id":1,"age_group":2,"rank":"Kyu 3","dojo_origin":"Petra","city":"","bpjs_number":"0002287135326","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":9,"event2":10,"event3":13},{"athlete_id":1335,"nik":"3578220709100001","nik_kenshi":"18.3.13.01.20.005","name":"Fais Pratama Nugroho ","gender":"Male","birth_place":"Sidoarjo","blood_type":"-","birth_date":"2010-09-07","address":"Jln. Ketintang Madya No. 35","phone":"087864087070","photo":null,"existing_photo_path":"athlete_photos\\/WEvKmro6lDgFB0gREd4zOBB9ULB3zu2sGZ2Jhsyv.jpg","current_weight":"70.00","weight_group_id":6,"age_group":3,"rank":"Kyu 3","dojo_origin":"PLN Nusantara Power","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":14,"event2":"","event3":""},{"athlete_id":1337,"nik":"3578062702100001","nik_kenshi":"22.3.13.01.20.016","name":"GIOVANNI PUTRA HARMAWAN","gender":"Male","birth_place":"Surabaya","blood_type":"B","birth_date":"2010-02-27","address":"Bulu Jaya4\\/9","phone":"081358894606","photo":null,"existing_photo_path":"athlete_photos\\/YiPtSl9BRFpUidv48ppeGSNcActvmSsZo0ldZHXm.jpg","current_weight":"55.00","weight_group_id":3,"age_group":3,"rank":"Kyu 1","dojo_origin":"PLN Nusantara Power","city":"","bpjs_number":"24073019143","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":19,"event2":16,"event3":""},{"athlete_id":1338,"nik":"3578182212090004","nik_kenshi":"18.3.13.01.09.003","name":"Desta Rizky Syahputra","gender":"Male","birth_place":"Surabaya","blood_type":"AB","birth_date":"2009-12-22","address":"Jl. Wisma Lidah Kulon Blok C109","phone":"082331258090","photo":null,"existing_photo_path":"athlete_photos\\/kWKZbd6tl7oq2HhoJmxDfMt7AUd9dXj3D4k43EVn.jpg","current_weight":"65.00","weight_group_id":5,"age_group":3,"rank":"Kyu 1","dojo_origin":"Unesa","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":21,"event2":16,"event3":15},{"athlete_id":1341,"nik":"3518102111090001","nik_kenshi":"24.3.13.01.08.016","name":"DAVE AARON ELZABATH","gender":"Male","birth_place":"nganjuk","blood_type":"-","birth_date":"2009-11-21","address":"Jl. Ketintang baru selatan IV kav. 83","phone":"085784248283","photo":null,"existing_photo_path":"athlete_photos\\/ZNJADfqYwJEmuxeEJ1mf8IYy4HikmpwrYN7ubKqi.jpg","current_weight":"70.00","weight_group_id":6,"age_group":3,"rank":"Kyu 3","dojo_origin":"petra","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":22,"event2":"","event3":""},{"athlete_id":1343,"nik":"3578125902100001","nik_kenshi":"18.3.13.01.24.010","name":"Annisa Khansa Jamiah","gender":"Female","birth_place":"surabaya","blood_type":"-","birth_date":"2010-02-19","address":"Jl. Teluk Nibung Timur 4 no. 48","phone":"0881036839070","photo":null,"existing_photo_path":"athlete_photos\\/4WrduWGjQbCgkbFHFX8JNAK1EpiIWXj2Wkb8ByIF.jpg","current_weight":"45.00","weight_group_id":1,"age_group":3,"rank":"Kyu 3","dojo_origin":"perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":27,"event2":"26","event3":""},{"athlete_id":1336,"nik":"3578127103110001","nik_kenshi":"23.1.13.01.24.001","name":"Azzizah Lucita Zaviera","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2011-03-31","address":"JL. Teluk Tomini No 18","phone":"087861306300","photo":null,"existing_photo_path":"athlete_photos\\/MKRDt40b4DAdogJhRILFmGRXkPogaSITBLY5iaxO.png","current_weight":"50.00","weight_group_id":2,"age_group":3,"rank":"Kyu 3","dojo_origin":"Dojo Perak","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":28,"event2":"24","event3":"26"},{"athlete_id":1340,"nik":"3578023012090002","nik_kenshi":"25.3.13.01.08.002","name":"Alexander Troy Moeljono ","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2009-12-30","address":"Siwalankerto selatan I\\/24","phone":"081231793750","photo":null,"existing_photo_path":"athlete_photos\\/FiMLwUJehFoYMTItzmwc5mg5EIFLRdrKfI9EKvts.png","current_weight":"71.00","weight_group_id":7,"age_group":3,"rank":"Kyu 4","dojo_origin":"Dojo Petra","city":"","bpjs_number":"0000000000000","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":23,"event2":"","event3":""},{"athlete_id":1342,"nik":"3578120111040001","nik_kenshi":"12.1.13.01.24.021","name":"Ramadhani Arta Pradipta","gender":"Male","birth_place":"Surabaya","blood_type":"-","birth_date":"2004-11-04","address":"Teluk Aru Utara 65.A","phone":"085806619665","photo":null,"existing_photo_path":"athlete_photos\\/3NrfYyiGzJ0s14M5XLRzeWkgo2umzNT5cHOrsdQs.png","current_weight":"65.00","weight_group_id":5,"age_group":4,"rank":"Kyu 1","dojo_origin":"Dojo Perak","city":"","bpjs_number":"23108625742","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":40,"event2":36,"event3":51},{"athlete_id":1344,"nik":"3578034512070003","nik_kenshi":"23.2.13.01.20.003","name":"Laverda Javier Tonda","gender":"Female","birth_place":"Surabaya","blood_type":"-","birth_date":"2007-12-05","address":"Pandugo 6-A\\/10","phone":"085102890262","photo":null,"existing_photo_path":"athlete_photos\\/VeYZU74kqpy7OsqwGgihlXCJjoHX6ROSNGSeLLAm.jpg","current_weight":"65.00","weight_group_id":5,"age_group":4,"rank":"Kyu 1","dojo_origin":"Dojo PJB-PLN Nusantara Power","city":"","bpjs_number":"24073019234","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":51,"event2":48,"event3":""}],"matchTechniques":{"0_1":[253,254,255,256,257,258],"4":[293,294,282,295,289,285],"1_2":[253,254,255,256,257,258],"3":[293,294,282,295,289,285],"3_6":[253,286,255,256,257,258],"8":[226,296,282,283,297,285],"13":[253,226,39,281,50,258],"4_7":[253,286,255,256,257,258],"9":[226,296,282,283,297,285],"10":[226,296,282,283,297,285],"7_14":[253,286,255,256,257,258],"8_19":[],"16":[152,287,153,154,286,151],"9_21":[],"9_15":[6,147,255,253,286,258],"10_22":[],"11_27":[],"31":[289,163,290,282,291,292],"12_28":[],"12_24":[253,286,256,257,258,255],"13_23":[],"14_40":[],"14_36":[6,147,255,253,286,258],"51":[298,299,300,301,154,302],"15_48":[],"26":[289,303,290,282,291,292],"25":[],"24":[]},"payment_method":"Tunai"}	verified
63	25	4500000.00	4500191.00	191	Tunai	KEMPO-YHIOW	verified	\N	Ya	2026-05-20 14:39:30	2026-06-12 14:20:24	{"contingent_name":"BANGKALAN B","contingent_city":"KAB BANGKALAN ","leader_name":"ISNAINI RAHMAN ","leader_phone":"082229223414","leader_email":"isnainirahman93@gmail.com","address":"Graha trunojoyo f4 burneh bangkalan ","officials":[{"official_id":11,"name":"AHMAD SYAIFUDIN","role":"Official","phone":"087865609230"},{"official_id":10,"name":"ISNAINI RAHMAN","role":"Manajer Tim","phone":"082229223414"}],"athletes":[{"athlete_id":1210,"nik":"3526010603080001","nik_kenshi":"24.3.13.13.03.002","name":"ANDHIKA RIDHO MUTTAQIN ","gender":"Male","birth_place":"BANGKALAN","blood_type":"A","birth_date":"2008-03-06","address":"JL.RA KARTINI RT\\/RW 003\\/003 BANGKALAN ","phone":"085815652681","photo":null,"existing_photo_path":"athlete_photos\\/fS3zkvuf3PmMxVsWTj3tghupDptTGXVkQydEtwi8.jpg","current_weight":"55.00","weight_group_id":3,"age_group":3,"rank":"Kyu 4","dojo_origin":"BANGKALAN B","city":"","bpjs_number":"3526010603080001","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":16,"event2":19,"event3":""},{"athlete_id":1207,"nik":"7371126411090003","nik_kenshi":"25.2.13.13.03.002","name":"DESVITA EKA PUTRI","gender":"Female","birth_place":"MAKASSAR","blood_type":"A","birth_date":"2009-11-24","address":"PERUM GRAHA MENTARI BLOK C1\\/21","phone":"082131726661","photo":null,"existing_photo_path":"athlete_photos\\/2vL0wxSfydPUvKeeiTvHUXqmpNW32lq7YZXEscMl.jpg","current_weight":"70.00","weight_group_id":6,"age_group":3,"rank":"Kyu 4","dojo_origin":"BANGKALAN B","city":"","bpjs_number":"7371126411090003","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":26,"event2":30,"event3":""},{"athlete_id":1208,"nik":"3375034412090001","nik_kenshi":"25.2.13.13.03.001","name":"ZUHRUFFINE CALISTA PUTRI INDI ","gender":"Female","birth_place":"PEKALONGAN","blood_type":"A","birth_date":"2009-12-04","address":"JL KINIBALU RT\\/RW 001\\/001 MLAJAH BANGKALAN ","phone":"085850833250","photo":null,"existing_photo_path":"athlete_photos\\/3PZlMN4otzEzQ52vniC9L03MMSkHKlreaIOTdwTu.jpg","current_weight":"50.00","weight_group_id":2,"age_group":3,"rank":"Kyu 4","dojo_origin":"BANGKALAN B","city":"","bpjs_number":"3375034412090001","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":28,"event2":24,"event3":26},{"athlete_id":1209,"nik":"3526182303100001","nik_kenshi":"25.2.13.13.03.007","name":"MUHARROR MADANI ","gender":"Male","birth_place":"BANGKALAN","blood_type":"O","birth_date":"2010-03-23","address":"DSN SEDDANG  KEC GALIS KAB BANGKALAN ","phone":"082337180997","photo":null,"existing_photo_path":"athlete_photos\\/i08R5cHopJhjadIxDEY8ckps7jRI4BfM9GgbYAq0.jpg","current_weight":"63.00","weight_group_id":5,"age_group":3,"rank":"Kyu 4","dojo_origin":"BANGKALAN B","city":"","bpjs_number":"3526182303100001","bpjs_status":"Aktif","bpjs_card":null,"identity_document":null,"is_master_found":true,"show_fields":true,"event1":16,"event2":21,"event3":""}],"matchTechniques":{"16":[60,55,57,52,87,17,14,20,13,64,16],"19":[],"26":[34,40,91,72,41,85,73,17,14,20,13,64],"30":[],"28":[],"24":[147,10,27,8,20,4],"21":[]},"payment_method":"Tunai"}	verified
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	Super Admin	web	2026-05-16 15:47:25	2026-05-16 15:47:25
2	Admin	web	2026-05-16 15:47:25	2026-05-16 15:47:25
3	Pendaftaran	web	2026-05-16 15:47:26	2026-05-16 15:47:26
4	Pertandingan	web	2026-05-16 15:47:26	2026-05-16 15:47:26
5	Panitera	web	2026-05-16 15:47:26	2026-05-16 15:47:26
6	Perwasitan	web	2026-05-16 15:47:34	2026-05-16 15:47:34
7	Arbitrase	web	2026-05-16 15:47:34	2026-05-16 15:47:34
8	Contingent	web	2026-05-16 15:47:35	2026-05-16 15:47:35
9	Koordinator Lapangan	web	2026-05-16 15:47:35	2026-05-16 15:47:35
10	Court	web	2026-05-16 15:47:39	2026-05-16 15:47:39
11	Wasit	web	2026-05-16 15:47:39	2026-05-16 15:47:39
\.


--
-- Data for Name: rundowns; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rundowns (id, name, type, description, date, "order", created_at, updated_at) FROM stdin;
1	Upacara Pembukaan	seremonial	Opening ceremony dan parade kontingen	2026-06-14 00:00:00	1	2026-05-16 15:47:40	2026-05-16 15:47:40
2	Babak Penyisihan - Hari 1	pertandingan	Seluruh nomor pertandingan penyisihan hari pertama	2026-06-15 00:00:00	2	2026-05-16 15:47:40	2026-05-16 15:47:40
3	Babak Final & Perebutan Juara	pertandingan	Pertandingan perebutan medali emas, perak, dan perunggu	2026-06-16 00:00:00	3	2026-05-16 15:47:40	2026-05-16 15:47:40
\.


--
-- Data for Name: schedule_paniteras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.schedule_paniteras (id, rundown_id, session_time_id, court_id, user_id, role_type, slot_index, created_at, updated_at) FROM stdin;
75	2	2	1	101	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
76	2	2	1	146	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
77	2	2	1	148	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
78	2	2	1	150	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
79	2	2	1	154	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
80	2	2	3	105	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
81	2	2	3	161	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
82	2	2	3	147	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
83	2	2	3	145	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
84	2	2	3	167	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
85	2	2	4	165	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
86	2	2	4	159	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
87	2	2	4	160	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
88	2	2	4	151	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
89	2	2	4	152	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
90	2	2	2	101	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
91	2	2	2	157	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
92	2	2	2	158	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
93	2	2	2	153	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
94	2	2	2	156	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
95	3	1	1	105	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
96	3	1	1	157	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
97	3	1	1	146	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
98	3	1	1	155	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
99	3	1	1	149	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
100	3	1	3	101	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
101	3	1	3	159	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
102	3	1	3	160	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
103	3	1	3	161	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
104	3	1	3	153	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
105	3	1	2	165	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
106	3	1	2	147	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
107	3	1	2	150	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
108	3	1	2	154	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
109	3	1	2	152	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
110	3	1	4	165	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
111	3	1	4	148	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
112	3	1	4	145	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
113	3	1	4	151	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
114	3	1	4	167	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
115	2	1	1	101	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
116	2	1	1	159	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
117	2	1	1	147	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
118	2	1	1	150	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
119	2	1	1	153	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
120	2	1	3	165	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
121	2	1	3	157	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
122	2	1	3	161	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
123	2	1	3	146	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
124	2	1	3	148	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
125	2	1	2	105	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
126	2	1	2	158	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
127	2	1	2	152	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
128	2	1	2	156	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
129	2	1	2	167	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
130	2	1	4	101	koordinator	1	2026-06-11 11:29:36	2026-06-11 11:29:36
131	2	1	4	160	panitera	1	2026-06-11 11:29:36	2026-06-11 11:29:36
132	2	1	4	151	panitera	2	2026-06-11 11:29:36	2026-06-11 11:29:36
133	2	1	4	154	panitera	3	2026-06-11 11:29:36	2026-06-11 11:29:36
134	2	1	4	155	panitera	4	2026-06-11 11:29:36	2026-06-11 11:29:36
\.


--
-- Data for Name: schedule_referees; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.schedule_referees (id, rundown_id, session_time_id, court_id, referee_id, judge_index, created_at, updated_at) FROM stdin;
319	2	2	1	25	1	2026-06-11 11:30:42	2026-06-11 11:30:42
320	2	2	1	18	2	2026-06-11 11:30:42	2026-06-11 11:30:42
321	2	2	1	23	3	2026-06-11 11:30:42	2026-06-11 11:30:42
322	2	2	1	32	4	2026-06-11 11:30:42	2026-06-11 11:30:42
323	2	2	1	42	5	2026-06-11 11:30:42	2026-06-11 11:30:42
324	2	1	2	16	1	2026-06-11 17:42:08	2026-06-11 17:42:08
325	2	1	2	22	2	2026-06-11 17:42:08	2026-06-11 17:42:08
326	2	1	2	32	3	2026-06-11 17:42:08	2026-06-11 17:42:08
327	2	1	2	35	4	2026-06-11 17:42:08	2026-06-11 17:42:08
328	2	1	2	41	5	2026-06-11 17:42:08	2026-06-11 17:42:08
256	2	2	\N	54	0	2026-06-11 07:34:22	2026-06-11 07:34:22
262	2	2	3	24	1	2026-06-11 07:34:22	2026-06-11 07:34:22
113	3	2	\N	35	0	2026-05-30 16:44:05	2026-05-30 16:44:05
263	2	2	3	53	2	2026-06-11 07:34:22	2026-06-11 07:34:22
264	2	2	3	28	3	2026-06-11 07:34:22	2026-06-11 07:34:22
265	2	2	3	33	4	2026-06-11 07:34:22	2026-06-11 07:34:22
266	2	2	3	46	5	2026-06-11 07:34:22	2026-06-11 07:34:22
267	2	2	2	21	1	2026-06-11 07:34:22	2026-06-11 07:34:22
268	2	2	2	20	2	2026-06-11 07:34:22	2026-06-11 07:34:22
269	2	2	2	16	3	2026-06-11 07:34:22	2026-06-11 07:34:22
270	2	2	2	49	4	2026-06-11 07:34:22	2026-06-11 07:34:22
271	2	2	2	39	5	2026-06-11 07:34:22	2026-06-11 07:34:22
272	2	2	4	22	1	2026-06-11 07:34:22	2026-06-11 07:34:22
273	2	2	4	30	2	2026-06-11 07:34:22	2026-06-11 07:34:22
274	2	2	4	34	3	2026-06-11 07:34:22	2026-06-11 07:34:22
275	2	2	4	27	4	2026-06-11 07:34:22	2026-06-11 07:34:22
276	2	2	4	41	5	2026-06-11 07:34:22	2026-06-11 07:34:22
277	3	1	\N	7	0	2026-06-11 07:34:22	2026-06-11 07:34:22
278	3	1	1	23	1	2026-06-11 07:34:22	2026-06-11 07:34:22
279	3	1	1	15	2	2026-06-11 07:34:22	2026-06-11 07:34:22
280	3	1	1	16	3	2026-06-11 07:34:22	2026-06-11 07:34:22
281	3	1	1	31	4	2026-06-11 07:34:22	2026-06-11 07:34:22
282	3	1	1	33	5	2026-06-11 07:34:22	2026-06-11 07:34:22
283	3	1	3	22	1	2026-06-11 07:34:22	2026-06-11 07:34:22
284	3	1	3	55	2	2026-06-11 07:34:22	2026-06-11 07:34:22
285	3	1	3	24	3	2026-06-11 07:34:22	2026-06-11 07:34:22
286	3	1	3	41	4	2026-06-11 07:34:22	2026-06-11 07:34:22
287	3	1	3	52	5	2026-06-11 07:34:22	2026-06-11 07:34:22
288	3	1	2	25	1	2026-06-11 07:34:22	2026-06-11 07:34:22
289	3	1	2	32	2	2026-06-11 07:34:22	2026-06-11 07:34:22
290	3	1	2	42	3	2026-06-11 07:34:22	2026-06-11 07:34:22
291	3	1	2	45	4	2026-06-11 07:34:22	2026-06-11 07:34:22
292	3	1	2	46	5	2026-06-11 07:34:22	2026-06-11 07:34:22
293	3	1	4	21	1	2026-06-11 07:34:22	2026-06-11 07:34:22
294	3	1	4	14	2	2026-06-11 07:34:22	2026-06-11 07:34:22
295	3	1	4	53	3	2026-06-11 07:34:22	2026-06-11 07:34:22
296	3	1	4	30	4	2026-06-11 07:34:22	2026-06-11 07:34:22
297	3	1	4	40	5	2026-06-11 07:34:22	2026-06-11 07:34:22
298	2	1	\N	54	0	2026-06-11 07:34:22	2026-06-11 07:34:22
299	2	1	1	18	1	2026-06-11 07:34:22	2026-06-11 07:34:22
300	2	1	1	14	2	2026-06-11 07:34:22	2026-06-11 07:34:22
301	2	1	1	24	3	2026-06-11 07:34:22	2026-06-11 07:34:22
302	2	1	1	27	4	2026-06-11 07:34:22	2026-06-11 07:34:22
303	2	1	1	43	5	2026-06-11 07:34:22	2026-06-11 07:34:22
304	2	1	3	25	1	2026-06-11 07:34:22	2026-06-11 07:34:22
305	2	1	3	30	2	2026-06-11 07:34:22	2026-06-11 07:34:22
206	3	2	1	55	1	2026-06-09 15:34:42	2026-06-09 15:34:42
207	3	2	1	20	2	2026-06-09 15:34:42	2026-06-09 15:34:42
208	3	2	1	16	3	2026-06-09 15:34:42	2026-06-09 15:34:42
209	3	2	1	31	4	2026-06-09 15:34:42	2026-06-09 15:34:42
210	3	2	1	52	5	2026-06-09 15:34:42	2026-06-09 15:34:42
211	3	2	3	15	1	2026-06-09 15:34:42	2026-06-09 15:34:42
212	3	2	3	25	2	2026-06-09 15:34:42	2026-06-09 15:34:42
213	3	2	3	31	3	2026-06-09 15:34:42	2026-06-09 15:34:42
214	3	2	3	34	4	2026-06-09 15:34:42	2026-06-09 15:34:42
215	3	2	3	45	5	2026-06-09 15:34:42	2026-06-09 15:34:42
216	3	2	2	55	1	2026-06-09 15:34:42	2026-06-09 15:34:42
217	3	2	2	16	2	2026-06-09 15:34:42	2026-06-09 15:34:42
218	3	2	2	34	3	2026-06-09 15:34:42	2026-06-09 15:34:42
219	3	2	2	32	4	2026-06-09 15:34:42	2026-06-09 15:34:42
220	3	2	2	43	5	2026-06-09 15:34:42	2026-06-09 15:34:42
306	2	1	3	42	3	2026-06-11 07:34:22	2026-06-11 07:34:22
307	2	1	3	45	4	2026-06-11 07:34:22	2026-06-11 07:34:22
308	2	1	3	46	5	2026-06-11 07:34:22	2026-06-11 07:34:22
314	2	1	4	23	1	2026-06-11 07:34:22	2026-06-11 07:34:22
315	2	1	4	20	2	2026-06-11 07:34:22	2026-06-11 07:34:22
316	2	1	4	34	3	2026-06-11 07:34:22	2026-06-11 07:34:22
317	2	1	4	37	4	2026-06-11 07:34:22	2026-06-11 07:34:22
318	2	1	4	39	5	2026-06-11 07:34:22	2026-06-11 07:34:22
\.


--
-- Data for Name: session_times; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.session_times (id, name, start_time, end_time, created_at, updated_at) FROM stdin;
1	Sesi Pagi	07:30:00	12:00:00	2026-05-16 15:47:40	2026-05-16 15:47:40
2	Sesi Sore	13:00:00	17:00:00	2026-05-16 15:47:40	2026-06-09 13:13:30
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
cwJawmhsNiC7yxCXlmtUR0mERqMah0gxYLWZauF1	\N	72.14.201.176	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36	eyJfdG9rZW4iOiJwZTNtMnFMQTNWMUlZb0ZLWGRld2I1dUhTYzJyRFNacTF3WnBNZGJ2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781274572
1QeCCZMEudrkSFsFonfRXskLLENfeHGcZD0xM09H	\N	193.186.4.182	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36	eyJfdG9rZW4iOiIyZEkwVjJiM2NWelEyMlRCZTVHaE5NT0x4R3c3VlhhTE1PYlczc0xlIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781274862
C8XIuKstIlfUjEukkq6QSnbG86BwFHbHEIxTdH9n	\N	69.63.184.19	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJhTXE1R1hqaFVwdHVvbFhGSDlPTVVEQzZOdU52aHZDcnZQUUFBNElPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275932
PqPey41b41F9rsG705Tm6YlDFWqImgRsQYvYTWk5	\N	69.63.184.37	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJwNllkQlZnaXdkNjU3VWlFbzJxNnlRYUx6WGFIdVJBYmNnekl2SXJCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275934
9ONyYgd4bwCuZwmrAY3D2RWE4u5oa8hGlskAh5HP	\N	34.178.133.116	Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)	eyJfdG9rZW4iOiI1dkw3cUVGQlNiT2NpVnJBblpEWDJwWG5YTTJtc2gzQmt0elM3NmtoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93d3cuc21hcnQtcGVya2VtaS5pZCIsInJvdXRlIjoid2VsY29tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781269879
PyKH284m7nEbWQJ1PU3pO4xrlsrMaIOULvHFu0VA	\N	69.63.184.2	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJTU3U1elkwbVJLUmwzVnFDWU1pMmtvWEgyVHg1NDBneDVJU2s0aW5hIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275945
yUBVjlmsZJYU0LDSgkYpNe9WP9k5jb5pfLNzAljK	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJnQ3NwNk1mV1VZWERYQ0Z2S0hKcE9YaTZLWmFKZ2ViTm1HVnVxOElrIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
h3l4e9gqF4j5Ollr8VMYukWVHmfGqLGBm8eyOcbc	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJWVWltdzVpYXRSS2lUdXdDN3hqNlhjbkc5SE5QNnZwRE52cFdSSUtOIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvMzI/cm91bmQ9RmluYWwifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
8YUVcPVTJHme4KksNw9YwXeBttAk6ANuCGVwsP1h	\N	54.209.60.63	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiI0cG43Z0hZdG4wa0hxZHk1T3ZHUGU4ZXp1UXltYjNPaW81d09GMjh3IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9wYW5pdGVyYVwvc2NvcmluZ1wvZW1idS1yZXN1bHQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
PDRor27pqPvWKwBnYyjXfMIHvj8Vi6c0emJ1iEV6	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJMYUJ6UFZBc1NnYXVCbUhoMmxCbG1vMEtBZGhYTVdyaVlNa0U0THFZIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctdXNlcnMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781271202
3UDgmismlfejkj9Y7fRoDsVZqtEexSzoQfMYsRqT	\N	54.209.60.63	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJqb29pNVhTd2kydE1IbEhBSGw1SGFHMDVhd1ViM0NJaUU2dUtMZ2NLIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvMTQ/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781271202
WMoFAEHMnnXPtjuUJtsfBzJc2h9ujP8GXUQcPlPX	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJWRDVDSUZpVTlRV3Nnb0tNTlVoc01USWJPTGFJSDdxR0dZWFJaa3hWIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvND9wb29sX2lkPTUmcm91bmQ9UGVueWlzaWhhbiJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
qUd8bifVv1aota9WfE7x9cwbgCTydj1lhLrTBxen	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiIyYXdnMm9Dek44RjN1MlBYSGszT1JuUEVmUzdqRXFnNUxvMnBLekRWIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctdXNlcnMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781271202
eG9rKftZwGRvF1cFRplw6peAMyKoW94g6jYyd5et	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJjSVc0WVlBekZuWUZlTmdyZG13M05yVHpDM3NUcDc5a3lua3Vlb0d2IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvMzI/cG9vbF9pZD0mcm91bmQ9RmluYWwifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
T9RLb4HETJmCFrZSfJ9uC7Zxxwv1RVG8oC3JR2LQ	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJsQVZoZXk3THl3ek9NZWxiQTExc1ZsZGRoeVc4Y25URGNYeWJ3bWdJIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvcmFuZG9yaVwvMTc/cG9vbF9pZD0mcm91bmQ9RnVsbCUyMEJyYWNrZXQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273623
FqrKpj3QzrdIaQsF9PhecCrVLiM4PKy58biZicCK	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJHa0ZDUFlNQTBBOWF0d2I5a3N4WVVsMUt5aWltWkdkM0k1UWxuWTNPIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9wYW5pdGVyYVwvc2NvcmluZ1wvZW1idS1yZXN1bHQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9hZG1pblwvcGFuaXRlcmFcL3Njb3JpbmdcL2VtYnUtcmVzdWx0Iiwicm91dGUiOiJhZG1pbi5wYW5pdGVyYS5zY29yaW5nLmVtYnUucmVzdWx0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781273621
Sbk4RROZV7Wv1vhQDgDU1nbmMzaJ0vat5cm4D73g	\N	31.13.103.9	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJWdElNYXE0WWdaWEpXMW9icTdXM3lsbWJCSVBPM0tMS1RJQWxIQ1dnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275932
42NFPGN7lY9a8hmwvV0SFte7qSQLpgksB22q9i6J	\N	69.63.184.9	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJPMmlvaDV0VjNybjZBbmxGUE9KU21NR1labTlSaUNoUnQ2NnNRblg2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275935
rbkQxh2ECBIE080edNu0EFaRDH6C0JOvE8f3ERiQ	\N	54.209.60.63	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJOaERHOERNWTR0azZ2bUtHTU5oeU44VzdvYnRhbGRqTGZXa0dudlJOIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctcGFuaXRlcmEifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
20JiKI99wm3K0DZyCWnT9GgeGxOcJLmSe87lAsSl	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJoQ1RQeWdMSzJuYnB3dGhzR3NCZW0xRk14cE1hcE8xdUNJdnlySWllIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
oy93X2o92M1K7kpU6RsjQF0njp8M6PzIhxehvu1K	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJib254bFhYTHpSWVg5RnNLWUVuVVV5MjBwRW12czNNUzEyQ1VvblNkIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273623
btZwVSOyH6sOi3vQxBWgIyaXeb5CaT0ZNl5rvVBT	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJvOTFIQ1gwNGxtZThPRmtTTzhWMXNmbWQ4bWtGRVJHcUNiZkp2eXVpIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctZ2VuZXJhdGUtcmVmZXJlZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
jmbdymosnor4kYnOk9eozdNapLeBBFUv87EaXbfP	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiI0SG9xaHNvVVZudnV0VlhmNXRjeTdNdVJRb2FCODY1U1ZhOVBsNGdHIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctYWdlLWdyb3VwcyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
SET7ubaL0XmvYyBb9dSDjg34uuu5FmGGzUrnQGnW	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJIZjMyczNtSm5kNGpHMGFLUlZMbHM1WHZscjduWFhUZ1loeUo1YkNBIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctZ2VuZXJhdGUtcmVmZXJlZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
YmyXFcHRU9hib6lMalWXSOD3bguWuT5Gj1G6nEpx	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJJZk1iWEVLaTc1MXJXdkpCUGs0cngzM2o1dmdiTTVtbk5WY254T0Z5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9hZG1pblwvYXJiaXRyYXNlXC9zY29yaW5nXC9tb25pdG9yLXRpbWVyXC9jb3VydFwvMiIsInJvdXRlIjoiYWRtaW4uYXJiaXRyYXNlLnNjb3JpbmcubW9uaXRvci10aW1lci5jb3VydCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781273621
fh32A15ilBt41QZg7tGIEWaZnXKawnu3S1dcDVqv	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJNUVFhbDI4WlhaQmdTcU9vYzZVOVhZVkV6b2Y5RWlkV08yMFdPWXRMIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9wYW5pdGVyYVwvc2NvcmluZ1wvZW1idS1yZXN1bHQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
vt56Rxnm8Y9Xzvy4LK17ypsuWTpYBCfhZwkF7RQ6	\N	54.209.60.63	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJBMU9DaFRzRXlkY1F6OGZ6elJlNk9FY2FhU1diSzA4SnJsMGJsYlBrIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9wYW5pdGVyYVwvc2NvcmluZ1wvZW1idS1yZXN1bHQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
mAloFWU7laO1eccEOMoPTVSEVc6kLICy3ZDhopbd	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJpSEM2NFUzNzZPM1pvazdBWTluN0dHbGxqdlBITmhmMW9yRTV0cGRNIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273623
sJlaIAbuGQlsmY15Sg9GKi0d1GSgCX4i4gJWLMXo	94	203.78.112.194	Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.2 Mobile/15E148 Safari/604.1	eyJfdG9rZW4iOiJmZXNXYzRNUm4zN0JzVm9GSUhPUHlzQWdsWWphMjdkSmVFNDc5WUxwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9jb250aW5nZW50XC9yZWdpc3RyYXRpb24taGlzdG9yeVwvNjkiLCJyb3V0ZSI6ImNvbnRpbmdlbnQucmVnaXN0cmF0aW9uLWhpc3Rvcnkuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo5NH0=	1781270070
dnugJvP1nbjTTlN0DYuR36CFtKa7OXAhH4UkKMrF	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJZUkZXVnVqSEwyT2lyTU5xVGlFSWxwaHBMMTYyQVFIaGdjS0M2R1RkIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctZ2VuZXJhdGUtcmVmZXJlZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
tn0DBeLWF5UP47TKWP1hWWP73Nha1kTdTuTdNP7P	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJIUjFWcXQ2M1FnM1RGWXN3ZE01bDNmTXd4STFqSVAxR3VqUTI3bWVTIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
KWbTcCfmBSP2cAHpaTLyqyhJyojPjE0DB3BKMft1	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJCUWtRbWx5UXkyZEc4Ujg1ZDVjVTZqVURjaXB3TmwzOW5VS2tTMU9LIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9tYXN0ZXJcL25ldy1yZWZlcmVlcyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
6Kc7RVXIWFZ4dBXaFqnGQmnwEzCQ2HPIkZeQ9fKr	\N	173.252.82.11	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJJVmZFYUxSMnV0WTlBYUpCdndtRnEwTDJRTllCMlFmQmhEREhqemlpIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275933
xXZITWx145GDuelSlEyF7CvYYJ7wZxuS72hmwP5E	\N	69.63.184.32	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJoSlpibllRWUVZeTRNVkx5b283aWNySk9zMzk5Q0pMQk1SY2dLSmswIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275942
ojPiwXAAGgCrgBSWcowoeZy9icuHhM0pybdZXfb1	42	114.10.47.104	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJZUnc4Vml2THBpNjlzSnhQWkR0TklOREZkRkFrVWFlbHkyYzNqdHg3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9hZG1pblwvbmV3LWRhc2hib2FyZCIsInJvdXRlIjoiYWRtaW4ubmV3LWRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo0Mn0=	1781276258
R6KSziTqRY2b12rnsSyTOEPudWGYXAgmGofalAqM	\N	69.63.184.27	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJkMFNxYzBqYXpFYnU5Q3hlV09XZG8zdDd2cXE3TTBpTUtQelFSS1J5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275934
MPfc5UKNcL8MxMjeSiaGBnh8MJA6JJiLBdL0vM9g	\N	173.252.87.28	facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)	eyJfdG9rZW4iOiJYdWtrRlNaOVFaWkxFMG9Ja3Y1Mnk5bVlCWTdleWtnZmdDaElwaVBsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkIiwicm91dGUiOiJ3ZWxjb21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1781275944
fCxUoXNT1Kf4VcQHEFaaR62qInbGyy09gGcIlda1	\N	3.254.70.26	Mozilla/5.0 (compatible; NetcraftSurveyAgent/1.0; +info@netcraft.com)	eyJfdG9rZW4iOiJCckE3ZmdMT1dTcnpqbzQzbTZMSmtJcGdMRjR0N2hwVmRZbXJkZ1VXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93d3cuc21hcnQtcGVya2VtaS5pZCIsInJvdXRlIjoid2VsY29tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781268765
YmeeP4RKU4mLra8qYOiOB8b00Lx35ov0gYLEA5G2	2	114.10.47.104	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJGWGZYeUhiS3hoN01BS0lZY1FKTk1ET1N3MDhpZEFDTlVKanVUTU5JIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9hZG1pblwvYXBpXC9zY29yaW5nXC9wYW5nZ2lsLWRyYXdpbmctc3RhdGU/ZmlsdGVyQWdlR3JvdXA9JmZpbHRlckNvbnRpbmdlbnQ9JmZpbHRlckNvdXJ0PSZmaWx0ZXJHZW5kZXI9JmZpbHRlck1hdGNoTnVtYmVyPSZmaWx0ZXJQb29sPSZmaWx0ZXJSb3VuZD0mZmlsdGVyUnVuZG93bj0mZmlsdGVyU2Vzc2lvbj0mZmlsdGVyVHlwZT0mcGFnZT0xJnNlYXJjaD0iLCJyb3V0ZSI6ImFkbWluLmFwaS5zY29yaW5nLnBhbmdnaWwtZHJhd2luZy1zdGF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==	1781274980
sWuwVoCAX6ZXWzWq8HWV950j0n2sLVfsgLBC9Fl9	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJDRmU4SkVOeFo3cldTNTBKeFNIeVFhUFBXSDFRZnBjNEh0eTk1NjBhIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
JZYePWFQt4Tif9igAxK6QhBMwWWS7cy8xoKT6vMJ	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJzRUl5N2luZWI5QW5WUldHRTJJQmc2NnZZVDRYbEZKU2VsVW15SmxuIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctZ2VuZXJhdGUtcmVmZXJlZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
16zdbwknwUroUoq9bsdjQmrfCtTbi6rTxKhJSE7s	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJ1azBnOGZmVVlrVU9tYXNtMmR6eVc0a0xpMjR5WjFGRmtqYkJhNFpuIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
U9tsSgigJBHC9bYrORQwgRtDYXgDpWYSii0e8woN	\N	184.72.115.35	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiIxMndYdXdQcWZCanRKUEFnczNGQk1FRTl3VWRwelZuZkVsQ29ZaGdjIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctZ2VuZXJhdGUtcmVmZXJlZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1781271202
JKiOedzAHwKbgdM6c3h0lkvCf71UF6rP7yv5RLyO	\N	54.175.74.27	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJLWTZlNlBBUGFXR2RsRXg5c0N5T0xTVzlITDRJSVE5RTVnVWRRN0pIIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
uPW8vwWENgi3PxDdBy8ufQ0fIILEqDDNcnd3FeK1	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJoY0M2a1hLeTZRcGM3VGtrUUI2R0NQQmRoTkxwWlk0MzdrOW9CR3B6IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvMTE/cG9vbF9pZD0mcm91bmQ9RmluYWwifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
wHG8CD1x2XHbeHkctyGSw2bBPWcUJfc8hjmJiWvR	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiJzYzNKYlJxYmdzbWpKMHJxcjM2SE5iMFAwRHNaZHlhcDlraHFpaW9CIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvNDM/cG9vbF9pZD01JnJvdW5kPVBlbnlpc2loYW4ifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273622
HKYIlzyjDe5VmBLkHRynA1JyCS0ZCB1gzr1KPbn9	\N	184.72.121.156	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25	eyJfdG9rZW4iOiIwSFlRY2lxN21pam5ZYURMRUFJMlk4UDFYWjdCaXI1N1M4MVJIakRlIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NtYXJ0LXBlcmtlbWkuaWRcL2FkbWluXC9uZXctc2NvcmluZ1wvZW1idVwvMTE/cm91bmQ9RmluYWwifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zbWFydC1wZXJrZW1pLmlkXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1781273623
\.


--
-- Data for Name: techniques; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.techniques (id, name, "order", created_at, updated_at, kyu_level_id, description) FROM stdin;
1	Kihon Tanen	1	2026-05-16 15:47:40	2026-05-16 15:47:40	\N	\N
2	Kihon Sotai	2	2026-05-16 15:47:40	2026-05-16 15:47:40	\N	\N
3	Ten Ichi Ken Dai Ichi	3	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
4	Ten Ichi Ken Dai Ni	4	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
5	Ten Ichi Ken Dai San	5	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
6	Ten Ichi Ken Dai Yon	6	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
7	Ten Ichi Ken Dai Go	7	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
8	Giwa Ken Dai Ichi	8	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
9	Giwa Ken Dai Ni	9	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
10	Ryu Ken Dai Ichi	10	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
11	Ryu Ken Dai Ni	11	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
12	Manji Ken	12	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
13	Uchi uke zuki	13	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
14	Shita uke geri	14	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
15	Uwa uke zuki	15	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
16	Ryusui geri	16	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
17	Uwa uke geri	17	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
18	Ude juji tate gassho gatame	18	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
19	Kote nuki ura ken chudan zuki	19	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
20	Gyaku gote mae yubi gatame	20	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
21	Katate yori nuki	21	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
22	Ryote yori nuki	22	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
23	Ryusui geri (mae)	23	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
24	Uwa uke geri (omote, ura)	24	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
25	Shita uke geri	25	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
26	Shita uke jun geri	26	2026-05-16 15:47:40	2026-05-16 15:47:40	1	\N
27	Tenchi Ken Dai Ikkei	27	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
28	Ryuo Ken Dai Ikkei	28	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
29	Ryusui geri (ushiro)	29	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
30	Ryote yori nuki	30	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
31	Uwa uke zuki (omote)	31	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
32	Gyaku gote ~ mae yubi gatame	32	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
33	Ryusui geri (ushiro ryusui)	33	2026-05-16 15:47:40	2026-05-16 15:47:40	2	\N
34	Tenshin geri	34	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
35	Uchi uke zuki	35	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
36	Uwa uke zuki	36	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
37	Uwa uke geri	37	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
38	Shita uke geri	38	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
39	Kote nuki	39	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
40	Katate yori nuki	40	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
41	Katate maki nuki	41	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
42	Uchi age zuki (ura, omote)	42	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
43	Uchi age geri (ura, omote)	43	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
44	Soto uke zuki (ura, omote)	44	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
45	Soto uke geri (ura, omote)	45	2026-05-16 15:47:41	2026-05-16 15:47:41	2	\N
46	Tsuki ten ichi ren hanko	46	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
47	Han tenshin geri ren hanko	47	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
48	Katate okuri gote, okuri yoko tembin ~ ura gatame	48	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
49	Kiri gote (katate)	49	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
50	Johaku dori (ryote)	50	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
51	Gyaku gote (mae yubi gatame)	51	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
52	Soto oshi uke zuki	52	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
53	Kusshin zuki	53	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
54	Kusshin geri	54	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
55	Uchi uke geri	55	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
56	Ryusui geri	56	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
57	Uchi age zuki	57	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
58	Uchi uke geri (ura, omote)	58	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
59	Soto oshi uke zuki	59	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
60	Juji uke geri	60	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
61	Tsuki ten ichi	61	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
62	Gyaku gote ~ mae yubi gatame	62	2026-05-16 15:47:41	2026-05-16 15:47:41	3	\N
63	Tsuki nuki (soto, uchi)	63	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
64	Kiri nuki (soto, uchi)	64	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
65	Juji nuki (katate)	65	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
66	Oshi nuki (katate)	66	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
67	Soto uke zuki ren han ko	67	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
68	Kiri nuki (uchi)	68	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
69	Shita uke geri	69	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
70	Kiri kaeshi nuki (morote)	70	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
71	Shita uke jun geri	71	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
72	Kote nuki ren han ko	72	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
73	Uchi age geri ren han ko	73	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
74	Ryote yori nuki	74	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
75	Ryusui geri (mae)	75	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
76	Okuri gote (katate) ~ okuri gatame	76	2026-05-16 15:47:41	2026-05-16 15:47:41	4	\N
77	Tsuki nuki (ryote)	77	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
78	Maki nuki (ryote)	78	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
79	Juji nuki (ryote)	79	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
80	Gassho nuki	80	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
81	Johaku nuki	81	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
82	Nidan nuki	82	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
83	Hiji nuki mae tembin	83	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
84	Katate okuri dori yubi dori ~ ura gatame	84	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
85	Okuri gote (maki tembin)	85	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
86	Johaku dori & johaku maki	86	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
87	Sei juji gote (katate)	87	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
88	Gyaku gote (ura gaeshi ~ ura gatame)	88	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
89	Chidori gaeshi ren han ko	89	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
90	Johaku nuki (katate)	90	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
91	Soto uke geri ren han ko	91	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
92	Hiki nuki (morote)	92	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
93	Uwa uke geri (ura)	93	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
94	Sode nuki	94	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
95	Uchi age zuki ren han ko	95	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
96	Tsuki nuki (morote)	96	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
97	Kusshin zuki geri	97	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
98	Nidan nuki	98	2026-05-16 15:47:41	2026-05-16 15:47:41	5	\N
99	Tsubame gaeshi, ren hanko	99	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
100	Soto oshi uke zuki, ren hanko	100	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
101	Eri juji	101	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
102	Sode maki	102	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
103	Tsuki ten san ren hanko	103	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
104	Maki gote (morote) ~ tembin gatame (ura)	104	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
105	Harai uke geri & tsuki ten san	105	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
106	Gyaku geri hiza uke name gaeshi & tsuki ten ichi	106	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
107	Morote kiri gote (attack: ude ushiro neji age)	107	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
108	Tsubame gaeshi ren han ko	108	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
109	Juji nuki (ryote)	109	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
110	Gyaku tenshin geri ren han ko	110	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
111	Johaku nuki (ryote)	111	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
112	Soto oshi uke zuki ren han ko	112	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
113	Maki nuki (morote)	113	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
114	Uchi oshi uke geri ren han ko	114	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
115	Wa nuki (morote)	115	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
116	Harai uke geri ren han ko	116	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
117	Okuri gote (ryote) ~ okuri gatame	117	2026-05-16 15:47:41	2026-05-16 15:47:41	6	\N
118	Morote oshi nuki (attack: gyaku tembin)	118	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
119	Chidori gaeshi kari ashi	119	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
120	Kote maki gaeshi (continue: kannuki gatame)	120	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
121	Tai ten 1 & keri ten san -- (defense) 3 ren ko: jodan, chudan zuki, mawashi geri	121	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
122	Tai ten ichi ke keri ten san	122	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
123	Jun geri chi san ke tsuki ten ni	123	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
124	Ryu nage ~ ryu gatame	124	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
125	Morote gyaku gote (Penyerang: ippon se nage)	125	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
126	Shita uke geri kote nage ~ tembin gatame (ura)	126	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
127	Uwa uke nage ~ kannuki gatame	127	2026-05-16 15:47:41	2026-05-16 15:47:41	7	\N
128	Kusshin geri tenkai ren geri	128	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
129	Mikazuki gaeshi kari ashi	129	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
130	Morote kiri kaeshi nage (Penyerang: Ude ushiro neji age)	130	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
131	Ryote katate nage ~ kannuki gatame	131	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
132	Keri ten ichi sukui nage	132	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
133	Sode maki gaeshi ~ kannuki gatame (Penyerang: Pegang lengan baju & ashi barai)	133	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
134	Gedan gaeshi ke tobi ren geri	134	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
135	Chudan gaeshi ke uchi uke zuki	135	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
136	Kubi jime shuho juji nage	136	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
137	Maki komi gote	137	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
138	Oshi uke maki nage	138	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
139	Hangetsu gaeshi sukui kubi nage ke fukko chi ni	139	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
140	Kote nage melawan sashi komi mawashi geri & jo chu ni ren zuki	140	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
141	Keri ten san ke tora daoshi	141	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
142	Katate nage kiri gaeshi	142	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
143	Furisute omote nage	143	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
144	Uwa uke tembin nage	144	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
145	Kaishin zuki ke osae kannuki nage soto	145	2026-05-16 15:47:41	2026-05-16 15:47:41	\N	\N
146	soto uke zuki + johaku dori	146	2026-05-16 16:01:28	2026-05-16 16:01:28	\N	\N
147	Byakuren Dai Chi	147	2026-05-16 16:25:32	2026-05-16 16:25:32	\N	\N
148	juji uke geri + Katate Johaku Dori	148	2026-05-16 16:26:36	2026-05-16 16:26:36	\N	\N
149	tsubame gaaeshi + Juji gote	149	2026-05-16 16:27:08	2026-05-16 16:27:08	\N	\N
150	tsubame gaeshi + Juji gote	150	2026-05-16 16:27:26	2026-05-16 16:27:26	\N	\N
151	harai uke geri + Gyaku tembin	151	2026-05-16 16:27:55	2026-05-16 16:27:55	\N	\N
152	juji uke geri + kote maki gaeshi	152	2026-05-16 16:39:30	2026-05-16 16:39:30	\N	\N
153	tsubame gaeshi + katate oshi gote	153	2026-05-16 16:39:58	2026-05-16 16:39:58	\N	\N
154	katate johaku dori	154	2026-05-16 16:40:27	2026-05-16 16:40:27	\N	\N
155	gyaku tembin	155	2026-05-16 16:40:52	2026-05-16 16:40:52	\N	\N
156	juji uke geri ren han ko	156	2026-05-16 16:49:31	2026-05-16 16:49:31	\N	\N
157	+ johaku doi	157	2026-05-17 05:00:05	2026-05-17 05:00:05	\N	\N
158	soto  + johaku	158	2026-05-17 05:00:30	2026-05-17 05:00:30	\N	\N
159	+ Gyaku gote	159	2026-05-18 04:53:02	2026-05-18 04:53:02	\N	\N
160	Kusshin zuki + Gyaku gote	160	2026-05-18 05:01:38	2026-05-18 05:01:38	\N	\N
161	Kote nuki + Johaku dori	161	2026-05-18 05:03:47	2026-05-18 05:03:47	\N	\N
162	Kote nuki ren han ko + Johaku dori (katate)	162	2026-05-18 09:04:11	2026-05-18 09:04:11	\N	\N
163	Juji uke geri + Juji gote (katate)	163	2026-05-18 09:04:34	2026-05-18 09:04:34	\N	\N
164	Sita uke geri + Kiri gote (katate)	164	2026-05-18 09:04:58	2026-05-18 09:04:58	\N	\N
165	Tsuki ten san ren han ko + Okuri gote	165	2026-05-18 09:06:06	2026-05-18 09:06:06	\N	\N
166	Tsuki ten ichi + Ryusui geri (mae)	166	2026-05-18 09:06:31	2026-05-18 09:06:31	\N	\N
167	Harai Uke Geri RHK dan Kote Maki	167	2026-05-18 17:09:00	2026-05-18 17:09:00	\N	\N
168	Tsuki Ten Ichi Dan Okuri Yoko Tembin	168	2026-05-18 17:11:12	2026-05-18 17:11:12	\N	\N
169	Keri Ten San dan Gyaku Gote UGN	169	2026-05-18 17:12:31	2026-05-18 17:12:31	\N	\N
170	Tsubame Gaeshi dan Oshi Gote UGN	170	2026-05-18 17:13:08	2026-05-18 17:13:08	\N	\N
171	Juji uke geri RHK + Gyaku Gote UGN	171	2026-05-18 17:33:37	2026-05-18 17:33:37	\N	\N
172	Shita Uke Geri RHK + Okuri Yoko Tembin	172	2026-05-18 17:34:16	2026-05-18 17:34:16	\N	\N
173	Maki Nuki RHK + Soto Oshi Uke Tsuki	173	2026-05-18 17:34:45	2026-05-18 17:34:45	\N	\N
174	Tsuki Ten Ichi + Juji Gote	174	2026-05-18 17:35:05	2026-05-18 17:35:05	\N	\N
175	Juji Uke Geri RHK + Gyaku Gote UGN	175	2026-05-18 17:36:45	2026-05-18 17:36:45	\N	\N
176	1. harai uke geri+gyaku gote  2. ⁠furi ten ni ten’o ken 3. ⁠katate juji gote 4. ⁠tsubame gaeshi ren han ko 5. ⁠gyaku geri hizauke nami gaeshi 6. ⁠maki gote (morote) + tembin gatame (ura)	176	2026-05-19 07:26:57	2026-05-19 07:26:57	\N	\N
177	1. chidori gaeshi kani ashi 2. ⁠gyaku gote maeyubi gatame  3. ⁠tenchi ken daeshang  4. ⁠johaku dori (ryote) 5. ⁠tenchi ken dai ikkei 6. ⁠ryou ken dai ikkei	177	2026-05-19 07:32:19	2026-05-19 07:32:19	\N	\N
178	1. tenchi ken dai nikei 2. ⁠tenchi ken dai sankei  3. ⁠ryuo ken dai ikkei  4. ⁠tenchi ken dai ikkei 5. ⁠soto uchi uke zuki 6. ⁠giwa ken da ikkei	178	2026-05-19 07:49:14	2026-05-19 07:49:14	\N	\N
179	1. tenchi ken dai ikkei 2. ⁠giwa ken dai ikkei 3. ryusui geri (ushiro) 4. ⁠uwa uke zuki (omote	179	2026-05-19 08:01:13	2026-05-19 08:01:13	\N	\N
180	SODE DORI	180	2026-05-20 04:51:00	2026-05-20 04:51:00	\N	\N
181	HARAI UKE GERI + GYAKU GOTE	181	2026-05-20 10:11:30	2026-05-20 10:11:30	\N	\N
182	FURI TENI	182	2026-05-21 14:34:28	2026-05-21 14:34:28	\N	\N
183	ten ichi ken dai roku	183	2026-05-24 03:51:26	2026-05-24 03:51:26	\N	\N
184	Ryu no kata	184	2026-05-24 03:52:00	2026-05-24 03:52:00	\N	\N
185	TSUKI TEN ICHI-GYAKU KOTE URA GAESHI NAGE	185	2026-05-24 04:46:25	2026-05-24 04:46:25	\N	\N
186	BYAKUREN DAICHI	186	2026-05-24 04:47:22	2026-05-24 04:47:22	\N	\N
187	KERI TEN SAN - FURI TEN NI	187	2026-05-24 04:50:29	2026-05-24 04:50:29	\N	\N
188	TSUBAME GAESHI - SPDE DORI	188	2026-05-24 04:51:09	2026-05-24 04:51:09	\N	\N
189	KATATE JUJI KOTE	189	2026-05-24 04:51:33	2026-05-24 04:51:33	\N	\N
190	- MOROTE MAKI KOTE	190	2026-05-24 04:52:21	2026-05-24 04:52:21	\N	\N
191	SOTO OSHI UKE ZUKI - MOROTE MAKI KOTE	191	2026-05-24 04:53:17	2026-05-24 04:53:17	\N	\N
192	JUJI UKE GERI - CHIDORI GAESHI	192	2026-05-24 04:53:55	2026-05-24 04:53:55	\N	\N
193	BYAKUREN ICHI	193	2026-05-24 04:55:49	2026-05-24 04:55:49	\N	\N
194	GYOKU KOTE URA GAESHI NAGE	194	2026-05-24 05:00:25	2026-05-24 05:00:25	\N	\N
195	SOTO KIRI NUKI - KATATE JUJI KOTE	195	2026-05-24 05:00:59	2026-05-24 05:00:59	\N	\N
196	JUJI UKE GERI - MOROTE MAKI KOTE	196	2026-05-24 05:01:24	2026-05-24 05:01:24	\N	\N
197	TSUBAME GAESHI - SODE DORI	197	2026-05-24 05:13:11	2026-05-24 05:13:11	\N	\N
198	JUJI UKE GERI - KATATE JUJI KOTE	198	2026-05-24 05:27:12	2026-05-24 05:27:12	\N	\N
199	JUJI UKE GERI - KATATE KIRI KOTE	199	2026-05-24 05:28:00	2026-05-24 05:28:00	\N	\N
200	TSUKI TEN ICHI - UCHI OSHI UKE GERI	200	2026-05-24 05:35:43	2026-05-24 05:35:43	\N	\N
201	KERI TEN SAN - KATATE JUJI KOTE	201	2026-05-24 05:36:30	2026-05-24 05:36:30	\N	\N
202	KATATE OKURI KOTE YUBI DORI	202	2026-05-24 05:37:05	2026-05-24 05:37:05	\N	\N
203	TSUBAME GAESHI - SOTO OSHI UKE ZUKI	203	2026-05-24 05:39:08	2026-05-24 05:39:08	\N	\N
204	KERITENSAN - FURITEN NI	204	2026-05-24 06:46:36	2026-05-24 06:46:36	\N	\N
205	GYAKU KOTE URA GAESHI NAGE	205	2026-05-24 06:49:40	2026-05-24 06:49:40	\N	\N
206	Shita Uke Geri RHK + Okuri maki Tembin	206	2026-05-25 15:10:34	2026-05-25 15:10:34	\N	\N
207	Shita Uke Geri RHK + Okuri  makiTembin	207	2026-05-25 15:11:22	2026-05-25 15:11:22	\N	\N
208	harai uke geri + Kote maki Gaeshi Kannuki Gatame	208	2026-05-25 15:18:22	2026-05-25 15:18:22	\N	\N
209	tsuken ten ichi + Okuri maki tembin	209	2026-05-25 15:18:49	2026-05-25 15:18:49	\N	\N
210	keri ten san + gyaku gote ura gaeshi nage	210	2026-05-25 15:19:20	2026-05-25 15:19:20	\N	\N
211	tsubame gaeshi + oshi gote kannuki Gatame	211	2026-05-25 15:20:37	2026-05-25 15:20:37	\N	\N
212	soto uke Geri RHK + Gyaku Gote Ura Gatame	212	2026-05-25 16:02:15	2026-05-25 16:02:15	\N	\N
213	Maki Nuki RHK + Katate Yori Nuki	213	2026-05-25 16:02:33	2026-05-25 16:02:33	\N	\N
214	Ryusui Geri Ushiro + Kote Nuki	214	2026-05-25 16:02:53	2026-05-25 16:02:53	\N	\N
215	Tenshin Geri RHK + Tsuki Ten ichi	215	2026-05-25 16:03:17	2026-05-25 16:03:17	\N	\N
216	Juji Uke Geri RHK + Okuri Maki Tembin	216	2026-05-25 16:03:41	2026-05-25 16:03:41	\N	\N
217	Shita Uke Geri RHK	217	2026-05-25 16:04:03	2026-05-25 16:04:03	\N	\N
218	Soto uke Geri RHK + Gyaku Gote mae Yubi Gatame	218	2026-05-25 16:05:33	2026-05-25 16:05:33	\N	\N
219	Juji Uke Geri RHK + Juji Gote	219	2026-05-25 16:08:54	2026-05-25 16:08:54	\N	\N
220	Maki Nuki + Gyaku Gote Ura Gaeshi Nage	220	2026-05-25 16:09:54	2026-05-25 16:09:54	\N	\N
221	kushhin zuki + ryote Johaku dori	221	2026-05-25 16:10:17	2026-05-25 16:10:17	\N	\N
222	tsuki ten san + hiki otoshi	222	2026-05-25 16:10:38	2026-05-25 16:10:38	\N	\N
223	tsubame gaeshi	223	2026-05-25 16:10:56	2026-05-25 16:10:56	\N	\N
224	keri ten san + morote maki gote ura gatame	224	2026-05-25 16:11:19	2026-05-25 16:11:19	\N	\N
225	kusshin Zuki RHK + Johaku dori	225	2026-05-25 16:21:06	2026-05-25 16:21:06	\N	\N
226	Juji uke geri + Gyaku Gote Ura Gaeshi Nage	226	2026-05-25 16:21:26	2026-05-25 16:21:26	\N	\N
227	Shita uke geri RHK + Okuri Maki tembin	227	2026-05-25 16:21:48	2026-05-25 16:21:48	\N	\N
228	Tenshin Geri RHK + Kiri Gote	228	2026-05-25 16:22:06	2026-05-25 16:22:06	\N	\N
229	maki Nuki RHK + Soto Oshi Uki Tsuki	229	2026-05-25 16:22:28	2026-05-25 16:22:28	\N	\N
230	Tsuki Ten Ichi	230	2026-05-25 16:22:40	2026-05-25 16:22:40	\N	\N
231	Juji uke geri RHK + Juji Gote	231	2026-05-25 16:29:59	2026-05-25 16:29:59	\N	\N
232	maki nuki + Gyaku gote Ura Gaeshi Nage	232	2026-05-25 16:30:21	2026-05-25 16:30:21	\N	\N
233	kusshin zuki + Ryote Johaku dori	233	2026-05-25 16:30:41	2026-05-25 16:30:41	\N	\N
234	tsuki ten san + Hiki Otoshi	234	2026-05-25 16:30:58	2026-05-25 16:30:58	\N	\N
235	Keri ten san + morote maki gote ura gatame	235	2026-05-25 16:31:59	2026-05-25 16:31:59	\N	\N
236	byakuren ken(migi)	236	2026-05-25 16:33:56	2026-05-25 16:33:56	\N	\N
237	harai uke geri RHK + Kote Maki Gaeshi Kannuki Gatame	237	2026-05-25 16:47:32	2026-05-25 16:47:32	\N	\N
238	gedan gaeshi + Okuri Maki tembin	238	2026-05-25 16:47:47	2026-05-25 16:47:47	\N	\N
239	tsuki ten san + oshi gote kannuki gatame	239	2026-05-25 16:48:11	2026-05-25 16:48:11	\N	\N
240	keri ten san + oshi gote kannuki gatame	240	2026-05-25 16:49:15	2026-05-25 16:49:15	\N	\N
241	shita uke geri + gyaku gote ura gaeshi nage	241	2026-05-25 16:49:38	2026-05-25 16:49:38	\N	\N
242	soto uke zuki	242	2026-05-27 13:35:39	2026-05-27 13:35:39	\N	\N
243	shita uke geri + Uwa Uke Zuki	243	2026-05-27 13:36:20	2026-05-27 13:36:20	\N	\N
244	Uchi age tsuki + Katate okuri gote	244	2026-05-27 13:39:00	2026-05-27 13:39:00	\N	\N
245	Uchi Uke Tsuki	245	2026-05-27 13:44:24	2026-05-27 13:44:24	\N	\N
246	Soto Uke Tsuki	246	2026-05-27 13:44:38	2026-05-27 13:44:38	\N	\N
247	Shita Uke Geri + Uwa Uke Tsuki	247	2026-05-27 13:45:10	2026-05-27 13:45:10	\N	\N
248	Kote Nuki	248	2026-05-27 13:45:29	2026-05-27 13:45:29	\N	\N
249	Ryu sui geri (mae)	249	2026-05-27 13:46:07	2026-05-27 13:46:07	\N	\N
250	Uchi age tsuki + Katate Okuri Gote	250	2026-05-27 13:46:36	2026-05-27 13:46:36	\N	\N
251	Shita Uke Geri +Uwa Uke Tsuki	251	2026-05-27 14:14:55	2026-05-27 14:14:55	\N	\N
252	Uchi Age Tsuki +Katate Okuri Gote	252	2026-05-27 14:15:54	2026-05-27 14:15:54	\N	\N
253	Ten Chi Ken Dai Ni	253	2026-05-27 14:16:53	2026-05-27 14:16:53	\N	\N
254	Gyaku Gote	254	2026-05-27 14:17:04	2026-05-27 14:17:04	\N	\N
255	Ten Chi Ken Dai san	255	2026-05-27 14:17:30	2026-05-27 14:17:30	\N	\N
256	Giwa ken dai chi	256	2026-05-27 14:17:42	2026-05-27 14:17:42	\N	\N
257	Ten chi ken dai chi	257	2026-05-27 14:17:54	2026-05-27 14:17:54	\N	\N
258	Ryu o ken dai chi	258	2026-05-27 14:18:03	2026-05-27 14:18:03	\N	\N
259	Soto uke tsuki	259	2026-05-27 14:21:24	2026-05-27 14:21:24	\N	\N
260	shita uke geri + uwa uke tsuki	260	2026-05-27 14:22:24	2026-05-27 14:22:24	\N	\N
261	ten chi ken dai san	261	2026-05-27 14:24:49	2026-05-27 14:24:49	\N	\N
262	ten chi ken dai chi	262	2026-05-27 14:25:09	2026-05-27 14:25:09	\N	\N
263	ryu o ken dai chi	263	2026-05-27 14:26:10	2026-05-27 14:26:10	\N	\N
264	Tenshin Geri	264	2026-05-27 15:27:53	2026-05-27 15:27:53	\N	\N
265	Sita Uke Geri - Juji Uke Geri	265	2026-05-27 15:28:10	2026-05-27 15:28:10	\N	\N
266	Kushin Zuki Geri - Kiri Gote	266	2026-05-27 15:28:50	2026-05-27 15:28:50	\N	\N
267	Johaku Nuki - Ryusui Geri	267	2026-05-27 15:29:21	2026-05-27 15:29:21	\N	\N
268	Uchi Uke Chuki - Katate Okuri Gote	268	2026-05-27 15:29:36	2026-05-27 15:29:36	\N	\N
269	Kushin Geri - Johaku Dori	269	2026-05-27 15:29:49	2026-05-27 15:29:49	\N	\N
270	KIHON	270	2026-05-27 21:55:46	2026-05-27 21:55:46	\N	\N
271	SAN REN	271	2026-05-27 21:57:08	2026-05-27 21:57:08	\N	\N
272	Kusshin Zuki Geri - Ryote Okuri Gote	272	2026-05-27 23:08:25	2026-05-27 23:08:25	\N	\N
273	Kusshin Zuki Geri - Ryote Kiri Gote	273	2026-05-27 23:10:30	2026-05-27 23:10:30	\N	\N
274	Ryote Johaku Nuki - Ryusui Geri	274	2026-05-27 23:11:27	2026-05-27 23:11:27	\N	\N
275	Uchi Uke Zuki - Katate Okuri Gote	275	2026-05-27 23:12:30	2026-05-27 23:12:30	\N	\N
276	Kusshi Geri - Ryote Johaku Dori	276	2026-05-27 23:13:47	2026-05-27 23:13:47	\N	\N
277	KUSSHIN GERI - RYOTE JOHAKU DORI	277	2026-05-27 23:14:19	2026-05-27 23:14:19	\N	\N
278	KUSSHIN ZUKI GERI - KIRI GOTE	278	2026-05-27 23:25:10	2026-05-27 23:25:10	\N	\N
279	RYOTE JOHAKU NUKI - RYUSUI GERI	279	2026-05-27 23:33:03	2026-05-27 23:33:03	\N	\N
280	UCHI UKE ZUKI - KATATE OKURI GOTE	280	2026-05-27 23:34:23	2026-05-27 23:34:23	\N	\N
281	katate juji gote	281	2026-05-28 04:18:24	2026-05-28 04:18:24	\N	\N
282	katate okuri gote	282	2026-05-28 04:54:41	2026-05-28 04:54:41	\N	\N
283	kon ten chi + katate juji gote	283	2026-05-28 04:55:54	2026-05-28 04:55:54	\N	\N
284	uchi uke zuki + katate kiri gote	284	2026-05-28 04:56:30	2026-05-28 04:56:30	\N	\N
285	uchi oshi uke geri	285	2026-05-28 04:57:05	2026-05-28 04:57:05	\N	\N
286	gyaku gote ura gaeshi nage	286	2026-05-28 04:58:51	2026-05-28 04:58:51	\N	\N
287	morote maki gote	287	2026-05-28 12:28:45	2026-05-28 12:28:45	\N	\N
288	tsubame gaeshi + katate juji gote	288	2026-05-28 12:29:02	2026-05-28 12:29:02	\N	\N
289	uchi uke tsuki + Katate Juji nuki	289	2026-05-28 12:33:41	2026-05-28 12:33:41	\N	\N
290	soto uke tsuki + katate yori nuki	290	2026-05-28 12:34:19	2026-05-28 12:34:19	\N	\N
291	ryusui geri (mae) + Gyaku gote ura gaeshi nage	291	2026-05-28 12:34:46	2026-05-28 12:34:46	\N	\N
292	shita uke geri + katate kiri gote	292	2026-05-28 12:34:57	2026-05-28 12:34:57	\N	\N
293	juji uke geri + gyaku gote	293	2026-05-28 14:15:41	2026-05-28 14:15:41	\N	\N
294	kusshin tsuki + ryote johaku nuki	294	2026-05-28 14:16:05	2026-05-28 14:16:05	\N	\N
295	kon ten chi + ryote maki nuki	295	2026-05-28 14:16:44	2026-05-28 14:16:44	\N	\N
296	kusshin tsuki + ryote johaku dori	296	2026-05-28 14:34:38	2026-05-28 14:34:38	\N	\N
297	uchi uke tsuki + katate kiri gote	297	2026-05-28 14:35:15	2026-05-28 14:35:15	\N	\N
298	kote maki gaeshi	298	2026-05-28 14:56:37	2026-05-28 14:56:37	\N	\N
299	tsubame gaeshi + gyaku gote ura gaeshi nage	299	2026-05-28 14:56:51	2026-05-28 14:56:51	\N	\N
300	harai uke geri + katate oshi gote	300	2026-05-28 14:57:02	2026-05-28 14:57:02	\N	\N
301	gedan gaeshi + morote maki gote	301	2026-05-28 14:57:12	2026-05-28 14:57:12	\N	\N
302	shita uke geri + sode maki tembin	302	2026-05-28 14:57:30	2026-05-28 14:57:30	\N	\N
303	juji uke geri + katate juji gote	303	2026-05-31 11:20:54	2026-05-31 11:20:54	\N	\N
304	JUJI UKE GERI - GYAKU GOTE URA GAESHI NAGE	304	2026-06-05 00:55:21	2026-06-05 00:55:21	\N	\N
305	JUJI UKE GERI - GYAKU GITE URA GAESHI NAGE	305	2026-06-05 00:55:57	2026-06-05 00:55:57	\N	\N
306	soto uke zuki-katate juji gote	306	2026-06-05 08:10:19	2026-06-05 08:10:19	\N	\N
307	han tenshin geri- gyaku gote ura gaeshi nage	307	2026-06-05 08:11:04	2026-06-05 08:11:04	\N	\N
308	uchi uke zuki- katate johaku dori	308	2026-06-05 08:11:38	2026-06-05 08:11:38	\N	\N
309	juji uke geri-kiri gote (katate)	309	2026-06-05 08:13:00	2026-06-05 08:13:00	\N	\N
310	shita uke geri- ryusui geri	310	2026-06-05 08:13:27	2026-06-05 08:13:27	\N	\N
311	kusshin zuki-okuri gote (katate)	311	2026-06-05 08:14:02	2026-06-05 08:14:02	\N	\N
312	han tenshin geri-gyaku gote ura gaeshi nage	312	2026-06-05 08:16:08	2026-06-05 08:16:08	\N	\N
313	uchi uke zuki-katate johaku dori	313	2026-06-05 08:16:28	2026-06-05 08:16:28	\N	\N
314	shita uke geri-ryusui geri	314	2026-06-05 08:17:41	2026-06-05 08:17:41	\N	\N
315	kusshin zuki-okuri gote(katate)	315	2026-06-05 08:21:43	2026-06-05 08:21:43	\N	\N
316	gedan gaeshi renhanko-kote maki gaeshi	316	2026-06-05 08:40:51	2026-06-05 08:40:51	\N	\N
317	shita uke geri-gyaku gote ura gaeshi nage	317	2026-06-05 08:42:50	2026-06-05 08:42:50	\N	\N
318	tsuki ten san-maki gote (morote)	318	2026-06-05 08:43:13	2026-06-05 08:43:13	\N	\N
319	harai uke geri-gyaku tembin	319	2026-06-05 08:44:03	2026-06-05 08:44:03	\N	\N
320	juji uke geri-furi ten ni	320	2026-06-05 08:44:28	2026-06-05 08:44:28	\N	\N
321	tsubame gaeshi renhanko-oshi gote (katate)	321	2026-06-05 08:46:18	2026-06-05 08:46:18	\N	\N
322	tsuki ten san-maki gote(morote)	322	2026-06-05 08:49:54	2026-06-05 08:49:54	\N	\N
323	soto uke tsuki + gyaku gote	323	2026-06-07 05:39:20	2026-06-07 05:39:20	\N	\N
324	Tenchiken Daini - Gyaku Gote	324	2026-06-08 13:10:12	2026-06-08 13:10:12	\N	\N
325	Chuki Ten Ichi - Maki Nuki	325	2026-06-08 13:10:28	2026-06-08 13:10:28	\N	\N
326	Uwa Uke Geri	326	2026-06-08 13:10:38	2026-06-08 13:10:38	\N	\N
327	TENSHIN GERI - KIRI NUKI	327	2026-06-08 13:10:56	2026-06-08 13:10:56	\N	\N
328	YORI NUKI	328	2026-06-08 13:11:13	2026-06-08 13:11:13	\N	\N
329	SHITA UKE GERI - OKURI MAKI TEMBIN	329	2026-06-08 13:11:34	2026-06-08 13:11:34	\N	\N
330	TENCHIKEN DAINI - GYAKU GOTE	330	2026-06-08 13:11:56	2026-06-08 13:11:56	\N	\N
331	CHUKI TEN ICHI - MAKI NUKI	331	2026-06-08 13:12:06	2026-06-08 13:12:06	\N	\N
332	UWA UKE GERI	332	2026-06-08 13:12:12	2026-06-08 13:12:12	\N	\N
333	TENCHI KEN DAICHI	333	2026-06-08 13:13:40	2026-06-08 13:13:40	\N	\N
334	RYU O KEN DAICIHI	334	2026-06-08 13:13:51	2026-06-08 13:13:51	\N	\N
335	GIWA KEN DAICHI	335	2026-06-08 13:14:12	2026-06-08 13:14:12	\N	\N
336	TENCHIKEN DAICHI	336	2026-06-08 13:14:26	2026-06-08 13:14:26	\N	\N
337	RYU O KEN DAICHI	337	2026-06-08 13:14:39	2026-06-08 13:14:39	\N	\N
338	TECHIKEN DAINI	338	2026-06-08 13:17:27	2026-06-08 13:17:27	\N	\N
339	UCHI UKE CHUKI	339	2026-06-08 13:17:37	2026-06-08 13:17:37	\N	\N
340	SHITA UKE GERI	340	2026-06-08 13:17:44	2026-06-08 13:17:44	\N	\N
341	TENSHI GERI - KIRI NUKI	341	2026-06-08 13:18:59	2026-06-08 13:18:59	\N	\N
342	MAKI NUKI	342	2026-06-08 13:20:24	2026-06-08 13:20:24	\N	\N
343	UWA UKE GERI - RYUSUI GERI	343	2026-06-08 13:20:38	2026-06-08 13:20:38	\N	\N
344	RYU O KEN DAICHI - TENSHIN GERI - GYAKU GOTE	344	2026-06-08 13:21:09	2026-06-08 13:21:09	\N	\N
345	UCHI UKE CHUKI - YORI NUKI	345	2026-06-08 13:21:49	2026-06-08 13:21:49	\N	\N
346	TENCHIKEN DAINI	346	2026-06-08 13:21:56	2026-06-08 13:21:56	\N	\N
347	SOTO UKE CHUKI	347	2026-06-08 13:22:03	2026-06-08 13:22:03	\N	\N
348	SHITA UKE GERI - JUJI GOTE	0	2026-06-10 12:48:57	2026-06-10 12:48:57	\N	\N
\.


--
-- Data for Name: tournament_results; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tournament_results (id, match_number_id, draft_type, rank, registration_id, athlete_names, contingent_name, penyisihan_score, final_score, accumulated_score, bracket_section, generated_by, confirmed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, court_id, remember_token, created_at, updated_at, judge_index) FROM stdin;
1	Super Admin	super-admin@smart-perkemi.id	\N	$2y$12$e.cV49fucB7GK83yDalxmuZ8kK0xSIORnRTO5AvVLujchQ.q8HTn2	\N	\N	2026-05-16 15:47:25	2026-05-16 15:47:25	\N
40	Court	court@smart-perkemi.id	\N	$2y$12$vSs7eevLyGB1txnjHgnH0.kMOJbSGwyDl.j4KIK5Zt5F.dAFcdiZO	\N	\N	2026-05-16 15:47:39	2026-05-16 15:47:39	\N
43	Petugas Court 2	court2@gmail.com	2026-05-16 15:47:43	$2y$12$XGet0Xx/tFDgF56EED7IP.CESCco3CzEmxiUQdh.caYu9Sj7WCod2	2	\N	2026-05-16 15:47:43	2026-05-16 15:47:43	\N
44	Petugas Court 3	court3@gmail.com	2026-05-16 15:47:44	$2y$12$ZjV7ggvHUSPK5iE9GbfQeeWgG6tdBPXGoZlW5ruWPZ5iAYjFsMHAG	3	\N	2026-05-16 15:47:44	2026-05-16 15:47:44	\N
46	Surabaya B	surabayab@smart-perkemi.id	\N	$2y$12$s7EOeFga2720oqcmVfpicOo2MZAkrIaxWKswcX8ieVh787mUZ2XX.	\N	\N	2026-05-16 15:47:45	2026-05-16 15:47:45	\N
47	Surabaya C	surabayac@smart-perkemi.id	\N	$2y$12$myrkVa5ICuKzqj.qmocQGud1wkrI3QJsMdFUoc6tQLtVmIIVREKue	\N	\N	2026-05-16 15:47:45	2026-05-16 15:47:45	\N
48	Surabaya D	surabayad@smart-perkemi.id	\N	$2y$12$6/iKUAc5elQlcksqr/xCfer75oTa1MoL8.qTDAISDcOC8RVR4AGV2	\N	\N	2026-05-16 15:47:46	2026-05-16 15:47:46	\N
4	Pertandingan User	pertandingan@smart-perkemi.id	\N	$2y$12$EQPZsX9ORLwJhQrG77OWs...g4kPXLyHWmudoHtZLyjRHdMXpqXNK	\N	\N	2026-05-16 15:47:26	2026-05-16 15:47:50	\N
26	Perwasitan User	perwasitan@smart-perkemi.id	\N	$2y$12$FS34enr9POBtzG.oOs1/OepRJqancZPKeSamixCCBEzHPnh45RLUK	\N	\N	2026-05-16 15:47:34	2026-05-16 15:47:51	\N
27	Arbitrase User	arbitrase@smart-perkemi.id	\N	$2y$12$cYDwb28deedubyn.P2GF7u8HMAMYxOtnRWvZnjj.ev6/p4rhu2Hoi	\N	\N	2026-05-16 15:47:34	2026-05-16 15:47:51	\N
41	Wasit User	wasit@smart-perkemi.id	\N	$2y$12$XWVxQqLElkn5qMN8RoxMC.u7TVfN.mGT2Syw.wntFNZjLLKyta0Q6	\N	\N	2026-05-16 15:47:39	2026-05-16 15:47:52	\N
2	Admin	admin@smart-perkemi.id	\N	$2y$12$D3USD/dKn5BPp4uHwj8e6.gdfZ99O4QUdhmous8AItoRIR4AqqGSq	\N	oWh8mVvnxZ1ApXMOaEIUJbBRDTLMCsy5KSjQCmmUz8mRjQEFdtGj2WjNDfeN	2026-05-16 15:47:25	2026-05-16 15:47:25	\N
42	Petugas Court 1	court1@gmail.com	2026-05-16 15:47:43	$2y$12$2rJ9EmZA7M4sGeeWZd4NuO5g/fVKmIPYmd5KSg8Phb/hAR35TQCVS	1	zn2Hx91kSPdXGexQ6ru8472MJhEkMebKjzC21i7Bn2iO1qdodIo8UijyhBFw	2026-05-16 15:47:43	2026-05-16 15:47:43	\N
49	Bangkalan A	bangkalana@smart-perkemi.id	\N	$2y$12$gIU/OxDv8EFBXWUGz8YcVOeDETr0tUG0RpmeHUZd8EfJpPGqKNYWq	\N	\N	2026-05-16 15:47:46	2026-05-16 15:47:46	\N
50	Bangkalan B	bangkalanb@smart-perkemi.id	\N	$2y$12$n9m7teDVtan1oMFWt4.8peKo9jz0Yg5y8rfR/ST/KSODYfbpPLTQe	\N	\N	2026-05-16 15:47:47	2026-05-16 15:47:47	\N
51	Kota Malang 1	malang1@smart-perkemi.id	\N	$2y$12$2c447fw/wDU1fNcoSSZk5uxyazQzbfZUalqDWdALG.PtF58TB4ddW	\N	\N	2026-05-16 15:47:47	2026-05-16 15:47:47	\N
52	Kota Malang 3	malang3@smart-perkemi.id	\N	$2y$12$PA872Cx1RLYYkX5Avt5E6e3KtDsn3B/WI2M.e04eKFHBWciVN/brG	\N	\N	2026-05-16 15:47:47	2026-05-16 15:47:47	\N
53	Kota Kediri	kediri@smart-perkemi.id	\N	$2y$12$i0sd15TQQrKzjKwyg5XPvOIHOOEVOn5EgUOZZh5787JUrkAsCZgMW	\N	\N	2026-05-16 15:47:48	2026-05-16 15:47:48	\N
54	Jombang	jombang@smart-perkemi.id	\N	$2y$12$yIEVrlrEOWPD0ZmIpkuniuBlvRoe7Z.zxSbB2hIh1UyKFzXCum2JK	\N	\N	2026-05-16 15:47:48	2026-05-16 15:47:48	\N
55	Banyuwangi	banyuwangi@smart-perkemi.id	\N	$2y$12$KLHxi/2YIQ7gz26ANUUlZeoB2corYcTIIuff/K/angIJ3jctqyMzO	\N	\N	2026-05-16 15:47:48	2026-05-16 15:47:48	\N
56	Sidoarjo	sidoarjo@smart-perkemi.id	\N	$2y$12$E1IntVlFzioA1FxbWsfs/.kS0X.qVX1YvCk.I0d/mlXGEU.4zyBbS	\N	\N	2026-05-16 15:47:49	2026-05-16 15:47:49	\N
57	Jember	jember@smart-perkemi.id	\N	$2y$12$2Pu9zCsg6Ki38exD225WjOV2Mh4MYodD/a9FdIabsiaUXf6OBfE56	\N	\N	2026-05-16 15:47:49	2026-05-16 15:47:49	\N
58	Gresik	gresik@smart-perkemi.id	\N	$2y$12$oFmBOKgil0aiox/Ncb4H/eqc4izq8W0IwPIGcl5fnwO1pTBsvlcgG	\N	\N	2026-05-16 15:47:49	2026-05-16 15:47:49	\N
59	Pasuruan	pasuruan@smart-perkemi.id	\N	$2y$12$KE6vi5NXxMEo9ayWMb6/xeUzGcy9XiNRCFwUvZ/sCq2QNBXWtYPvi	\N	\N	2026-05-16 15:47:50	2026-05-16 15:47:50	\N
3	Pendaftaran User	pendaftaran@smart-perkemi.id	\N	$2y$12$anGvIdDeCsCecWybzwmbpeh4qVRdslicIc9j3JUestDV96TdhWcHm	\N	\N	2026-05-16 15:47:26	2026-05-16 15:47:50	\N
63	Tablet Court 1 - Wasit 3	tabletcourt1wasit3@gmail.com	2026-05-16 15:47:53	$2y$12$WK6acBGe4.xD3DsB6p26A.E1EvZYlVdfmJleFf3.zr33YlJW1tTc.	1	\N	2026-05-16 15:47:53	2026-05-16 15:47:53	3
66	Tablet Court 2 - Wasit Utama	tabletcourt2wasitutama@gmail.com	2026-05-16 15:47:54	$2y$12$eyfw9BOfSVKP0zKgWGygHuRTj.HaLJLqT1Ws3IoHAlPK1AOztIkYu	2	\N	2026-05-16 15:47:54	2026-05-16 15:47:54	1
67	Tablet Court 2 - Wasit 2	tabletcourt2wasit2@gmail.com	2026-05-16 15:47:55	$2y$12$r2XeBn6ex9Oif6MsTTv3tOksD7bgFghoCy2rZbnXCXe71mfahmR.i	2	\N	2026-05-16 15:47:55	2026-05-16 15:47:55	2
68	Tablet Court 2 - Wasit 3	tabletcourt2wasit3@gmail.com	2026-05-16 15:47:55	$2y$12$3MFi1KAYZ2FH0zP/tDRzfeYoxCbp5cyBuyCJcdSZo1jC4a8iki296	2	\N	2026-05-16 15:47:55	2026-05-16 15:47:55	3
69	Tablet Court 2 - Wasit 4	tabletcourt2wasit4@gmail.com	2026-05-16 15:47:55	$2y$12$NNHm2cHvQPLNruSxAaU1gePVy6QXy.HpdKAugh2q/XvweioX68652	2	\N	2026-05-16 15:47:55	2026-05-16 15:47:55	4
70	Tablet Court 2 - Wasit 5	tabletcourt2wasit5@gmail.com	2026-05-16 15:47:56	$2y$12$mUL1UJbwUVSJc5OlzmlT/uFWn6cAzkbYl3hUI3MdHK4Atyjb1ZXzK	2	\N	2026-05-16 15:47:56	2026-05-16 15:47:56	5
73	Tablet Court 3 - Wasit 3	tabletcourt3wasit3@gmail.com	2026-05-16 15:47:57	$2y$12$RY5rXYRs..QFVtk8rupH5uWO1QKLCzlw3QIC84kpjVXePLBOZw0Rq	3	\N	2026-05-16 15:47:57	2026-05-16 15:47:57	3
74	Tablet Court 3 - Wasit 4	tabletcourt3wasit4@gmail.com	2026-05-16 15:47:57	$2y$12$R2N5B2FnXNKTx6WvjxC4C.PlYLW6Znna7IdNIXo.YmQXJN5XJLkum	3	\N	2026-05-16 15:47:57	2026-05-16 15:47:57	4
45	Surabaya A	surabayaa@smart-perkemi.id	\N	$2y$12$o1kv8gjFrLQNunXmRXyliOV.njRrd/m66v1KeKBZ0eupI0sl3iq.m	\N	XzsobHqZThpNUZnHTWQlU0ROhE8bdPoq33wHh2qj4aj2YK6xBZPzamD2XvY1	2026-05-16 15:47:44	2026-05-16 15:47:44	\N
65	Tablet Court 1 - Wasit 5	tabletcourt1wasit5@gmail.com	2026-05-16 15:47:54	$2y$12$hByVE4jqiSf//xMY.uZS6eF1/Gualm8K.0SFGzrj/UkGgBTWIA2aq	1	gNHJzaNgA451qh1uQ9qcCjaVwfBVTksSYuWj92qVeKYHeUqgcj90edqzzPQq	2026-05-16 15:47:54	2026-05-16 15:47:54	5
82	CECEP SUNARIYA/OFFICIAL	cecepsunariya@gmail.com	\N	$2y$12$CBEFrOpcpqZ4ec0JFPEG6O.dfQW5Aht9Zr7Ka3FvRDAsx7NvyDFze	\N	\N	2026-05-17 07:39:20	2026-05-17 07:39:20	\N
84	ASTRIT RABECA YOLANDA 	astritrabeca21@gmail.com	\N	$2y$12$OkOus0MvomiBuF0KPdVTwOhl9wV4aUz0TW0qVSRyt8IeXUcEh138a	\N	\N	2026-05-18 05:36:12	2026-05-18 05:36:12	\N
85	Rahman	isnainirahman71@gmail.com	\N	$2y$12$B4aHbpJ1t62Awu9K9xFC/OBEp4fSl76w9BVyU3xi8VkXoDz6l9.ju	\N	\N	2026-05-18 07:33:08	2026-05-18 07:33:08	\N
88	Rahman	isnainirahman73@gmail.com	\N	$2y$12$5zCRvFb1OskBUDeFtX9C2ONbTqjC8Idq7gQ6NLH5ZDsGh7BvUvC5.	\N	\N	2026-05-18 15:20:36	2026-05-18 15:20:36	\N
62	Tablet Court 1 - Wasit 2	tabletcourt1wasit2@gmail.com	2026-05-16 15:47:53	$2y$12$Vu5VAjnsWQhkDddc.hLKleRtnMkQmCFw.y841k2IeNOpT75OGn/K2	1	HlSGobfdDAEfSsrClKZWaFWeeUMRMRqF5PCvrKHqdT7jmaenWEJf2H8C1ITL	2026-05-16 15:47:53	2026-05-16 15:47:53	2
90	Isnaini Rahman/ Manager	isnainirahman93@gmail.com	\N	$2y$12$RQMcDswJqsBPV.5I//0PjOEGe6SxvJLZUnxY2merZ95BpAJThXvFW	\N	\N	2026-05-19 08:24:10	2026-05-19 08:24:10	\N
92	Surabaya A	surabaya.a@smart-perkemi.id	\N	$2y$12$es6AceLDikwf1WJQMqDJBOKWmjz4z7YzGl/nIWWoN83rPDwxRj70O	\N	\N	2026-05-20 04:31:54	2026-05-20 04:34:03	\N
93	Surabaya B	surabaya.b@smart-perkemi.id	\N	$2y$12$ubMwPMWtdZTSNUyRLTm.ielqRVC9pPN8nBWyqTshNyKr4Zw87Q9CC	\N	\N	2026-05-20 04:35:04	2026-05-20 04:37:41	\N
94	Surabaya C	surabaya.c@smart-perkemi.id	\N	$2y$12$v/T5ECwTbQvbZdhcjCtkQuh7x0gVTlLy3xbEd.ZX18d61XjM0iaWO	\N	\N	2026-05-20 04:38:23	2026-05-20 04:38:49	\N
75	Tablet Court 3 - Wasit 5	tabletcourt3wasit5@gmail.com	2026-05-16 15:47:57	$2y$12$47JBryreLt7ajNysmHhtM.KCRN8aL/tAHxCD9Azy.6Ip5cQo96TFm	3	CLqZCHm4hAc0jyAjz1xNgbZhMXWN08XOSzZJ6sZgEboBnOxXTdh01zQc9Uxc	2026-05-16 15:47:57	2026-05-16 15:47:57	5
83	Yulia Ainur Rahma/Official	lia555yul@gmail.com	\N	$2y$12$rFOZ6IlA6vv/srDYST9Am.emn0fOIOdZYv5yDQfib48tX04G0uU6W	\N	Xj1OAyIStQsNYtXCt8ZibT9jhuuqAZ6xZ1BFJoJWpD9hDkzsn1QDChOoXnM9	2026-05-18 04:38:18	2026-05-18 04:38:18	\N
87	RIZKA ADHI PRABAWA	acapoeirista@yahoo.com	\N	$2y$12$i3DD39tQXrBM55LvFER0TumnAd0nSvRlVppmL6bF/avoYSic64wUq	\N	mfKw3lOMB8fmnLis5co2v6vd6tNboB4QxlVWSn1eln9mrEmP4ZPR1N1Fi0E1	2026-05-18 15:04:45	2026-05-18 15:04:45	\N
86	S. Simbolon 	leonardocastello26@gmail.com	\N	$2y$12$ODO9SY6AkU4n/15NxBZcfuVpyW0jckTfQbqZ2zwgHOiFmUE.iASI.	\N	bomQGoDRBvnGlZVOtmxvPzsPPmba4ZbNwKa6DbvJUWyMsEaCAzSRKHE9tgdm	2026-05-18 12:11:11	2026-05-18 12:11:11	\N
61	Tablet Court 1 - Wasit Utama	tabletcourt1wasitutama@gmail.com	2026-05-16 15:47:52	$2y$12$z/SHLzaN9FKRzdmRPnSpfulqmq6a5ql4f/QKWuSgijJhwF0V3uXHC	1	udxsHxsOEi5tsL3VpqvXP9mw3A6c8tqJBcP7lQ2UeItuNqur1V8wwTRS7gGs	2026-05-16 15:47:52	2026-05-16 15:47:52	1
64	Tablet Court 1 - Wasit 4	tabletcourt1wasit4@gmail.com	2026-05-16 15:47:53	$2y$12$hqrmnRBB4G9jivwphsu6O.CsRoA4I60oAs..c0wjTLbzWuKtqmJH6	1	kf8oFzwZW2ppOM6UqQCKCQvhTAcSUEJ0QiUqD0vsqruJXITNDzBB4ZTsNtPE	2026-05-16 15:47:53	2026-05-16 15:47:53	4
72	Tablet Court 3 - Wasit 2	tabletcourt3wasit2@gmail.com	2026-05-16 15:47:56	$2y$12$xt1U6e03WS47.AFL62mgiOuQYLM/6JHrT8Cc6n0a2/FgUogH68Obe	3	16viCjrcidyrBgskLWKtFtKQAuCCBC4QfkUJLbLqiSOPT0GMMXsDFqPvXLiU	2026-05-16 15:47:56	2026-05-16 15:47:56	2
71	Tablet Court 3 - Wasit Utama	tabletcourt3wasitutama@gmail.com	2026-05-16 15:47:56	$2y$12$RG3at9HYcYjQgDAH05x9n.f08mffe19KVT8kHEqqbkODYKolTLpM2	3	YAH9B3KZgUnAgiJQ165uSiSUIfgMSmxKFtohWnfzANh9qnG0krcoO1vft62O	2026-05-16 15:47:56	2026-05-16 15:47:56	1
133	IWAN WIJAYA	IWANWIJAYA@smart-perkemi.id	\N	$2y$12$0y52FDz2rVNL.n.dkAzUnOfJ/gYayfZzUOqnzNiauyp9j0d/yN1Yi	\N	\N	2026-05-24 03:09:28	2026-05-24 03:09:28	\N
96	Navra Najma Alfurrohmah/Manager	kempo.universitas.jember@gmail.com	\N	$2y$12$OdxV8IfEO7AIHW0d3xe3T.PhFY6ZAU4qKDT.c2VxI5F3d16CslX1.	\N	3rbrdktv4PbkcAY3MZWxILlyaD0CfLLKIl33Abnir79cZ4jlRwf8rY71idTp	2026-05-20 14:47:02	2026-05-20 14:47:02	\N
98	M.DJAMHURI DJAUHARI	M.DJAMHURIDJAUHARI@smart-perkemi.id	\N	$2y$12$5952az6gmfEMnQRnLY5OiOj9I1rQErALlvY8azB3eHA4lHwkUwp0C	\N	\N	2026-05-24 01:35:01	2026-05-24 01:35:01	\N
99	BERNARD LAISINA	BERNARDLAISINA@smart-perkemi.id	\N	$2y$12$AJhq6UjxDb4vVEeECl5/a.MEspgonFkOGnk6vKCCwWrCvrrzISaHe	\N	\N	2026-05-24 01:35:53	2026-05-24 01:35:53	\N
100	WAWAN SETYAWAN	WAWANSETYAWAN@smart-perkemi.id	\N	$2y$12$hxA5b0skhJcpAFOt9D3ko.0j4Fs.tiAIVs/RUWqCjPa3Iqu/V5FDe	\N	\N	2026-05-24 01:36:56	2026-05-24 01:36:56	\N
101	DAUD TATENGKENG	DAUDTATENGKENG@smart-perkemi.id	\N	$2y$12$rrouhJlN2XC07zTxzTxNNumYKEwO5Vhev2SSl3/xPboU98ihJS3vi	\N	\N	2026-05-24 01:38:45	2026-05-24 01:38:45	\N
105	SUGIONO	SUGIONO@smart-perkemi.id	\N	$2y$12$c7HTL5qK7jT1tYZx4oVHwOAYqrGVecYWFEZkyWkLyxPVrlzv9Ghby	\N	\N	2026-05-24 02:11:55	2026-05-24 02:11:55	\N
106	DR IHYAN AMRI	DRIHYANAMRI@smart-perkemi.id	\N	$2y$12$q3J1g6lFcBYcGErDLzP8guoGTcYKJlTZHV6qZi8719vIJWPyWqZI2	\N	\N	2026-05-24 02:12:40	2026-05-24 02:12:40	\N
107	JOKO LISTYONO	JOKOLISTYONO@smart-perkemi.id	\N	$2y$12$SjBzAVPkWQM2LPnfthsD3u3NwSP4c.MYfgBlUwCXwXsArSk1nSpgW	\N	\N	2026-05-24 02:13:36	2026-05-24 02:13:36	\N
109	ANTONIUS EKO PURNOMO	ANTONIUSEKOPURNOMO@smart-perkemi.id	\N	$2y$12$o3zn8d45T50kAnB111cj..9OMLQgl0e7.dHKUpLE3/jq.iVZjMDBi	\N	\N	2026-05-24 02:15:03	2026-05-24 02:15:03	\N
111	ESAU MOSES CHRISTIAAN	ESAUMOSESCHRISTIAAN@smart-perkemi.id	\N	$2y$12$I726o7o/AeuIGeEeFT0mF.rtRAErhQrtj7HaFT7M0YNyzZJqVTS.2	\N	\N	2026-05-24 02:16:15	2026-05-24 02:16:15	\N
112	SARIP MAULANA BATHIK	SARIPMAULANABATHIK@smart-perkemi.id	\N	$2y$12$6sDyaGWPLT4L06y0VfuzuuJnqKpvzzG8LCwGRxaRCsLlaCiN9VN92	\N	\N	2026-05-24 02:16:47	2026-05-24 02:16:47	\N
113	ARYA SETYANTO WICAKSONO	ARYASETYANTOWICAKSONO@smart-perkemi.id	\N	$2y$12$MQHMOpRk7MHHLoah6UwVuuqLYVklEarcOWB/UQzRT3jlr6bz6pZVe	\N	\N	2026-05-24 02:17:22	2026-05-24 02:17:22	\N
114	LULUK CAHYONO	LULUKCAHYONO@smart-perkemi.id	\N	$2y$12$koUXiCWAApvnP.cyTq2KUecD5eKP7vg0oBw479iD6En4cGAsVZtxO	\N	\N	2026-05-24 02:18:01	2026-05-24 02:18:01	\N
115	ACHMAD YUSUF	ACHMADYUSUF@smart-perkemi.id	\N	$2y$12$FLXysyWuESrjgebI7Fk4U.glex6KyOquDA4Wv4mB/YorYUNaocOPa	\N	\N	2026-05-24 02:18:49	2026-05-24 02:18:49	\N
116	HARI WINARKO	HARIWINARKO@smart-perkemi.id	\N	$2y$12$gSuhsw.x9zr2hpqzWNljre0bxPNWpM1jqwTUAbU39gCxRA55dq/y.	\N	\N	2026-05-24 02:34:35	2026-05-24 02:34:35	\N
118	RICKY JOYOMULYONO	RICKYJOYOMULYONO@smart-perkemi.id	\N	$2y$12$I3dEwKJdAfcr2Bz14sELQeXX5V8si.ZVfimrNsesK3IQJxcgq/FyK	\N	\N	2026-05-24 02:36:26	2026-05-24 02:36:26	\N
119	SUTARNO	SUTARNO@smart-perkemi.id	\N	$2y$12$hRQ15.1QXeMlXDI8DFmX9eENzmxWVA751eS47CW9mtp/vLjiRUAIW	\N	\N	2026-05-24 02:36:55	2026-05-24 02:36:55	\N
120	ANIK YUSSETYOWATI	ANIKYUSSETYOWATI@smart-perkemi.id	\N	$2y$12$mlMlFgUCWmRcTf66tA/V6uV7sokeBXtzoYeka2c/qa4krlziS1t3m	\N	\N	2026-05-24 02:37:32	2026-05-24 02:37:32	\N
121	RAHAYU APRILIYANTI	RAHAYUAPRILIYANTI@smart-perkemi.id	\N	$2y$12$vlaE.22dt7qgBoCK6RiX/eijhRENjPeas8baKsbLl/aCU.nv1vBq.	\N	\N	2026-05-24 02:38:03	2026-05-24 02:38:03	\N
122	THALIB ALKATIRI	THALIBALKATIRI@smart-perkemi.id	\N	$2y$12$WWd05j0bpgGXazgginJqD.r7Vh/PYrgwR42V6ChP2zrgReAVFsbjm	\N	\N	2026-05-24 02:38:35	2026-05-24 02:38:35	\N
123	AUDRY PRADITYA LAISINA	AUDRYPRADITYALAISINA@smart-perkemi.id	\N	$2y$12$GoZb99bXr0EWQIK2mfJ1ee/vvicE.kFmf9AuvG69ToAHeeR4MRCoi	\N	\N	2026-05-24 02:39:12	2026-05-24 02:39:12	\N
124	TRI SARASWATI	TRISARASWATI@smart-perkemi.id	\N	$2y$12$/4NurBkXLw2Lgmche5GPw..ciLWhqCJQ088iFzOxuA1nLIY8aJXHq	\N	\N	2026-05-24 02:39:47	2026-05-24 02:39:47	\N
125	TOHA RIFAI	TOHARIFAI@smart-perkemi.id	\N	$2y$12$Z/AlRpwj5GRKKTfg5EsEzubzMdSG10bKaLb5YzM5P8nfA30Ok5lmK	\N	\N	2026-05-24 02:40:21	2026-05-24 02:40:21	\N
126	BAYU CATUR PAMUNGKAS	BAYUCATURPAMUNGKAS@smart-perkemi.id	\N	$2y$12$Jk3w3anwSJp7xWvJYzey2OnoNbNRFxw4ab6RQyXL71fzaCd9xpWNC	\N	\N	2026-05-24 02:40:56	2026-05-24 02:40:56	\N
128	GERALD MOKOGINTA	GERALDMOKOGINTA@smart-perkemi.id	\N	$2y$12$O88P97XgjS0P3X0z3D/AOuJoAtMfEo0TO/yTwz/c9f3rTU2zH1srC	\N	\N	2026-05-24 02:44:04	2026-05-24 02:44:04	\N
129	MUHAMMAD SAIFDUDIN	MUHAMMADSAIFDUDIN@smart-perkemi.id	\N	$2y$12$1PYDrUdR1b4OlKzeDItpoOHl5KoY0qh3i1UPkkMV30ENA4/ARenkG	\N	\N	2026-05-24 02:44:43	2026-05-24 02:44:43	\N
130	RIZQI FEBRIAN PRAMUDITA	RIZQIFEBRIANPRAMUDITA@smart-perkemi.id	\N	$2y$12$6vtqHuwgjDILfc3NkNJ2FuAIbv9w9nKOk5gQFHft1VYA4zUqdTnXC	\N	\N	2026-05-24 03:07:47	2026-05-24 03:07:47	\N
131	VITA DWI CAHYANINGRUM	VITADWICAHYANINGRUM@smart-perkemi.id	\N	$2y$12$wa5ZgdPWqHSsnfKBxUPGfujHT5Z973VfkxXf6yiNh7TGA0evCUGnC	\N	\N	2026-05-24 03:08:30	2026-05-24 03:08:30	\N
132	ISMAIL WAHYUONO	ISMAILWAHYUONO@smart-perkemi.id	\N	$2y$12$EDmPBmFrNcc04dLPOl7TQejwnOVL1Yi7/QKH5BPmmcMYfatfsSxDi	\N	\N	2026-05-24 03:09:01	2026-05-24 03:09:01	\N
134	PANGESTI NURMARDIAH	PANGESTINURMARDIAH@smart-perkemi.id	\N	$2y$12$NRqEajk5ev9Dv6o89.84heTNsed136P0aP3zLBPcBZBfAPbfH0SMC	\N	\N	2026-05-24 03:09:57	2026-05-24 03:09:57	\N
135	AISYAH AMINI	AISYAHAMINI@smart-perkemi.id	\N	$2y$12$Uo8K4i.JJAYsUIXBcjS0e.nb4LTIA2GY89RJVxz1zTv.0Umz16QPi	\N	\N	2026-05-24 03:10:33	2026-05-24 03:10:33	\N
97	Aprilia Hana Pratiwi	apriliahana10pr@gmail.com	\N	$2y$12$aebLOn8CXydyTBh1/6OAw.1.yV8gTbXpUkV6vQa.N9qkaZNaCdOEu	\N	tTyoQaenBbPuDbtwaNkrlbI64Vz66CJLKTtQCsnMVpTPGLVFyuKmK3Bc9PA6	2026-05-22 11:19:37	2026-05-22 11:19:37	\N
136	MUHAMAD ALI YUNUS	MUHAMADALIYUNUS@smart-perkemi.id	\N	$2y$12$lYltGG5JJA2ioffCDFYgDOJxKzFkN4ugP75.dLio2tu7pBmE5/kNC	\N	\N	2026-05-24 03:18:41	2026-05-24 03:18:41	\N
137	I KETUT PRAMANTARA	IKETUTPRAMANTARA@smart-perkemi.id	\N	$2y$12$NbwNjSNrh.zmC5SYQcfs5ucZcB9Cb1..FP3ern4SS/VSDUc1O3oR2	\N	\N	2026-05-24 03:19:11	2026-05-24 03:19:11	\N
140	HENDRA WICAKSONO	HENDRAWICAKSONO@smart-perkemi.id	\N	$2y$12$kmzACUKQnF7xxVYjCZTD0e6uKvaRQ.PYw76g0OsAQxD8ZPYk.xWtm	\N	\N	2026-05-24 03:23:18	2026-05-24 03:23:18	\N
141	Afrizal Hardiansyah	perkemigresik@gmail.com	\N	$2y$12$SsnJj4c2cMZluWUJEWzB8uV84QWo2arjVllKSZ7ZBGjdaDPiBETPq	\N	\N	2026-05-24 22:32:15	2026-05-24 22:32:15	\N
157	Agus Ifan  Riyadi	Ifan@smart-perkemi.id	\N	$2y$12$FF5y/kD4h0SRs8oDZ4LMtubT9AP8B2xo2SmfI0TTCuglvizqyg/7.	\N	\N	2026-06-07 05:20:28	2026-06-07 05:21:03	\N
158	Maulana Syaif Ridho Lil Firdaus	safril@smart-perkemi.id	\N	$2y$12$4UjeSG/AlsJtDJY.dwHo1upOUIlqyAITDHFhgKcXxk.ZJGWZkHZiC	\N	\N	2026-06-07 05:22:24	2026-06-07 05:22:24	\N
159	surya sajid	suryasajid@smart-perkemi.id	\N	$2y$12$TJIdvNeOQ9pwkfPgdlL9aeWzm/Pv/oEWfosNBu8cjJda1RU2gzxXi	\N	\N	2026-06-07 05:23:12	2026-06-07 05:23:12	\N
160	ayumi trinarita wardani	ayumi@smart-perkemi.id	\N	$2y$12$WdNc5u2dVoFO2vfj8fQziuGayPBt2H.f5ODO3p4xBzpqXb/N8SxAW	\N	\N	2026-06-07 05:25:24	2026-06-07 05:25:24	\N
161	revan catur cakti putra jatayu	revan@smart-perkemi.id	\N	$2y$12$QjPL0sJvetaGXQ0NvJvVh.rUng.riJxdUbiNiBAupFw7f9KqjAUki	\N	\N	2026-06-07 05:27:32	2026-06-07 05:27:32	\N
89	Nur Imama	nurimama948@gmail.com	\N	$2y$12$lO67GPhE.B7L85y078ExbOzax/q4spaDyO6XgYXOPkXm8PWEDPQKa	\N	kuPs5hKCImdiAyrXouzdJ7xffTETeYELWF6F6zRqoUjx8DxInUWS96iHy3KZ	2026-05-19 02:57:16	2026-05-20 12:26:59	\N
163	DIDY INDRA YUNIARTO	DIDYINDRAYUNIARTO@smart-perkemi.id	\N	$2y$12$69WQudRQLKD2S7suifF/L.ABg8adrzNGFJGlQnOWzFCH67xHPIQ1i	\N	\N	2026-06-08 15:35:37	2026-06-08 15:35:37	\N
164	MUJIB RIDHWAN	MUJIBRIDHWAN@smart-perkemi.id	\N	$2y$12$d8hW4SuCcmoWjpHsCOkM2eWxHf0EP6BKUH3e8rswElKFeEZXIrzwG	\N	\N	2026-06-08 15:42:52	2026-06-08 15:42:52	\N
95	Surabaya D	surabaya.d@smart-perkemi.id	\N	$2y$12$CtTPA/IE/jbl4tZa7.boqOsEqXWN8pAyy0T9ZPdi.0YFx6i2/IM96	\N	TZwQoxnAIIOXCzXob9wjx3godPY3viZCguZwdCnsFbmXoHSvLft4CHIN3Lkq	2026-05-20 04:39:13	2026-05-20 04:39:13	\N
143	I Ketut Pramantara	perkemisidoarjo@gmail.com	\N	$2y$12$RANIXRRnyGsIvAL6o0kVYu7Hsg6Bo2DgLPF/hQSDLLct7RhYbkhhe	\N	\N	2026-06-03 04:54:08	2026-06-03 04:54:08	\N
165	BAMBANG ANJAR SOEPENO	BAMBANGANJARSOEPENO@smart-perkemi.id	\N	$2y$12$O7sz6d.tYmak5FoR.NqrFu/Cu0VHIJhGk3tETfkpzeT7Lv9U5ktHG	\N	\N	2026-06-08 15:43:40	2026-06-08 15:43:40	\N
166	G.T.TOTO IRIANTO NOYA	GTTOTOIRIANTONOYA@smart-perkemi.id	\N	$2y$12$y7LfI18MLMjmTLa.egz9OeJr/N7Gh3myphbOhMcmHnkNQviBhNVrK	\N	\N	2026-06-09 03:25:43	2026-06-09 03:25:43	\N
146	Raya Silmina	raya@smart-perkemi.id	\N	$2y$12$7jFdf3RcYiOztdk6b7L7y.54IxJdi5Qq32Q/DSI4wwG4aziX3ktHe	\N	\N	2026-06-07 05:14:47	2026-06-07 05:14:47	\N
148	Inggar Cahya Wahyu Putri 	inggar@smart-perkemi.id	\N	$2y$12$xtoZ7KWqd.OJs/bK6aZm3eK1LdpmJW6ygKRtOZciBqtKgBMo68Wqi	\N	\N	2026-06-07 05:16:06	2026-06-07 05:16:06	\N
147	sheila wardatul jannah	sheila@smart-perkemi.id	\N	$2y$12$VHc4JPbm2Sn59pg9UC5d3OtcL5gXVzDKPpmGgOp9s8sWhWwH60hki	\N	\N	2026-06-07 05:15:48	2026-06-07 05:16:22	\N
145	nadifa amira	nadifa@smart-perkemi.id	\N	$2y$12$jSjWLvWsZZuB6.YZv61VBepSzSCeSZtHbaDrnCuOy3qtT4N/NGDn6	\N	\N	2026-06-07 05:14:46	2026-06-07 05:16:37	\N
150	Alvita Debby Marcella	marcell@smart-perkemi.id	\N	$2y$12$WB9a786c/DhwfBM1BAmPseH7LCI8.Hv5d2tkmxE.1aebjEsLS.9bq	\N	\N	2026-06-07 05:16:56	2026-06-07 05:16:56	\N
151	sophie nabila aekidya	sophie@smart-perkemi.id	\N	$2y$12$ACc61P71ViH8MdUgGLWgNut/usTd2veQ900es2ooCp.LyeoBMOZuS	\N	\N	2026-06-07 05:17:21	2026-06-07 05:17:21	\N
153	Velina	velina@smart-perkemi.id	\N	$2y$12$KZbyP3tanDzR2GHO.jaX3eUM861tbgU20h2UceWiIckBXJJILt4X.	\N	\N	2026-06-07 05:18:34	2026-06-07 05:18:34	\N
154	dyah nur islami putri nisyam	putrinisyam@smart-perkemi.id	\N	$2y$12$/Nxu7v5hCMTlJVY/MC7ZLekLpuQYjLTG0AAYSzpRjjVJbfkM/ZVQy	\N	\N	2026-06-07 05:18:44	2026-06-07 05:18:44	\N
155	alyssa rahma pradipta	alyssa@smart-perkemi.id	\N	$2y$12$5rCxus4JJ/tBstILZQpvu.zf.ApjXt/UdesPZ9Q/lRsY7EJdq7hbS	\N	\N	2026-06-07 05:18:48	2026-06-07 05:19:29	\N
152	nadia septia wardani	septia@smart-perkemi.id	\N	$2y$12$gluoGYwverxZZsrZq6y2Ie2Ntf6sO37MOnPdrQImg3d55DY1X.6mq	\N	\N	2026-06-07 05:18:06	2026-06-07 05:19:46	\N
149	sagita suryani anwar	sagita@smart-perkemi.id	\N	$2y$12$UjgGV2oGkILtzJtKo/LDzOpkDO.BAKuzle8oDDApOOYQlCO9FwKvG	\N	\N	2026-06-07 05:16:53	2026-06-07 05:19:57	\N
156	saddam bintang hermawan	saddam@smart-perkemi.id	\N	$2y$12$fESdis5K2NndvUFmyYuZtOQeLMYUjdZtWTvN5g8kH6GrMEH1yiAwu	\N	\N	2026-06-07 05:20:24	2026-06-07 05:20:24	\N
167	aisyah nurillah	aisyahnurillah@smart-perkemi.id	\N	$2y$12$HVTSPH4ONJm.3/VyfdFCoOFx1olaYykNZI5cxwbD6AG4cIR6QD3Pm	\N	\N	2026-06-09 11:15:57	2026-06-09 11:15:57	\N
91	RANGGA	dummy.rangga@gmail.com	\N	$2y$12$JiWQP9J.y7sX8L8yyGtJFex0Auyy1/vDqs1v41tjPJL5lw/7VrBNm	\N	YMKRuBPBb8rYD2kgf6IpwuDhy9qFY8Cfu6y2uP5nvIlutx7KIO80QbevYzaw	2026-05-20 00:22:23	2026-05-20 00:22:23	\N
168	Petugas Court 4	court4@gmail.com	\N	$2y$12$T0VpCrFmcOv1noZn4eJ9hOQOkjII9Msc9fNnSXJbDy2yAG3XtvfOC	\N	\N	2026-06-11 07:13:03	2026-06-11 10:57:08	\N
169	Tablet Court 4 - Wasit Utama	tabletcourt0wasitutama@gmail.com	\N	$2y$12$HJFjygxw7dBXdoXfsD78NeOr4Cs3WWvRA/WOKyH3PY2heNIxsWN5y	\N	\N	2026-06-11 10:57:09	2026-06-11 10:57:09	\N
170	Tablet Court 4 - Wasit 2	tabletcourt0wasit2@gmail.com	\N	$2y$12$8EyIyqz0EN6l8aiP7E4Luejd7Cuee0WO4.0I2pStpoSL3A539opnm	\N	\N	2026-06-11 10:57:09	2026-06-11 10:57:09	\N
171	Tablet Court 4 - Wasit 3	tabletcourt0wasit3@gmail.com	\N	$2y$12$GR7FYSfjBaFCQ7IWoz1W2uVzOP9ueaFSjkdOBBU2NcMCg9uUr.jqu	\N	\N	2026-06-11 10:57:09	2026-06-11 10:57:09	\N
172	Tablet Court 4 - Wasit 4	tabletcourt0wasit4@gmail.com	\N	$2y$12$GBrwSRy1s7dS7L6icbm7qu21Hazu3C8IRa5KiyPiuDvr1Vfp3OsK.	\N	\N	2026-06-11 10:57:10	2026-06-11 10:57:10	\N
173	Tablet Court 4 - Wasit 5	tabletcourt0wasit5@gmail.com	\N	$2y$12$IeF5eg9keIpuENJHCcXFWuyZ9oVcRHwsRJRVvbuZzZysZlIrgBRca	\N	\N	2026-06-11 10:57:10	2026-06-11 10:57:10	\N
\.


--
-- Data for Name: weight_groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.weight_groups (id, name, "order", created_at, updated_at) FROM stdin;
1	45Kg	1	2026-05-16 15:47:44	2026-05-16 15:47:44
2	50Kg	2	2026-05-16 15:47:44	2026-05-16 15:47:44
3	55Kg	3	2026-05-16 15:47:44	2026-05-16 15:47:44
4	60Kg	4	2026-05-16 15:47:44	2026-05-16 15:47:44
5	65Kg	5	2026-05-16 15:47:44	2026-05-16 15:47:44
6	70Kg	6	2026-05-16 15:47:44	2026-05-16 15:47:44
7	75Kg	7	2026-05-16 15:47:44	2026-05-16 15:47:44
8	>75Kg	8	2026-05-16 15:47:44	2026-05-16 15:47:44
\.


--
-- Name: active_court_referees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.active_court_referees_id_seq', 30, true);


--
-- Name: age_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.age_groups_id_seq', 4, true);


--
-- Name: athlete_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.athlete_category_id_seq', 1, false);


--
-- Name: athlete_contingent_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.athlete_contingent_histories_id_seq', 50, true);


--
-- Name: athlete_contingent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.athlete_contingent_id_seq', 1336, true);


--
-- Name: athlete_match_number_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.athlete_match_number_id_seq', 2679, true);


--
-- Name: athletes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.athletes_id_seq', 1362, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 96, true);


--
-- Name: contingents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contingents_id_seq', 40, true);


--
-- Name: courts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.courts_id_seq', 4, true);


--
-- Name: drawing_match_numbers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.drawing_match_numbers_id_seq', 17147, true);


--
-- Name: embu_champions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.embu_champions_id_seq', 1, false);


--
-- Name: embu_scores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.embu_scores_id_seq', 100, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: galleries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.galleries_id_seq', 12, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: kyu_levels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.kyu_levels_id_seq', 7, true);


--
-- Name: match_number_merge_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.match_number_merge_details_id_seq', 20, true);


--
-- Name: match_number_merges_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.match_number_merges_id_seq', 5, true);


--
-- Name: match_numbers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.match_numbers_id_seq', 53, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 81, true);


--
-- Name: officials_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.officials_id_seq', 35, true);


--
-- Name: payment_methods_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.payment_methods_id_seq', 4, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);


--
-- Name: pools_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pools_id_seq', 8, true);


--
-- Name: posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.posts_id_seq', 6, true);


--
-- Name: randori_judge_scores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.randori_judge_scores_id_seq', 1, false);


--
-- Name: randori_match_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.randori_match_results_id_seq', 20, true);


--
-- Name: referee_observations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.referee_observations_id_seq', 2, true);


--
-- Name: referee_score_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.referee_score_details_id_seq', 121, true);


--
-- Name: referees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.referees_id_seq', 55, true);


--
-- Name: registration_athlete_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registration_athlete_id_seq', 2009, true);


--
-- Name: registration_official_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registration_official_id_seq', 237, true);


--
-- Name: registrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registrations_id_seq', 88, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 11, true);


--
-- Name: rundowns_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rundowns_id_seq', 3, true);


--
-- Name: schedule_paniteras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.schedule_paniteras_id_seq', 134, true);


--
-- Name: schedule_referees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.schedule_referees_id_seq', 328, true);


--
-- Name: session_times_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.session_times_id_seq', 2, true);


--
-- Name: techniques_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.techniques_id_seq', 348, true);


--
-- Name: tournament_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tournament_results_id_seq', 6, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 173, true);


--
-- Name: weight_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.weight_groups_id_seq', 8, true);


--
-- Name: active_court_referees active_court_referees_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.active_court_referees
    ADD CONSTRAINT active_court_referees_pkey PRIMARY KEY (id);


--
-- Name: age_groups age_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.age_groups
    ADD CONSTRAINT age_groups_pkey PRIMARY KEY (id);


--
-- Name: athlete_category athlete_category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_category
    ADD CONSTRAINT athlete_category_pkey PRIMARY KEY (id);


--
-- Name: athlete_contingent athlete_contingent_athlete_id_contingent_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent
    ADD CONSTRAINT athlete_contingent_athlete_id_contingent_id_unique UNIQUE (athlete_id, contingent_id);


--
-- Name: athlete_contingent_histories athlete_contingent_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent_histories
    ADD CONSTRAINT athlete_contingent_histories_pkey PRIMARY KEY (id);


--
-- Name: athlete_contingent athlete_contingent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent
    ADD CONSTRAINT athlete_contingent_pkey PRIMARY KEY (id);


--
-- Name: athlete_match_number athlete_match_number_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_match_number
    ADD CONSTRAINT athlete_match_number_pkey PRIMARY KEY (id);


--
-- Name: athletes athletes_nik_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athletes
    ADD CONSTRAINT athletes_nik_unique UNIQUE (nik);


--
-- Name: athletes athletes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athletes
    ADD CONSTRAINT athletes_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: contingents contingents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contingents
    ADD CONSTRAINT contingents_pkey PRIMARY KEY (id);


--
-- Name: courts courts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courts
    ADD CONSTRAINT courts_pkey PRIMARY KEY (id);


--
-- Name: drawing_match_numbers drawing_match_numbers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_pkey PRIMARY KEY (id);


--
-- Name: embu_champions embu_champions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_champions
    ADD CONSTRAINT embu_champions_pkey PRIMARY KEY (id);


--
-- Name: embu_scores embu_scores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_scores
    ADD CONSTRAINT embu_scores_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: galleries galleries_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.galleries
    ADD CONSTRAINT galleries_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: kyu_levels kyu_levels_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kyu_levels
    ADD CONSTRAINT kyu_levels_pkey PRIMARY KEY (id);


--
-- Name: match_number_merge_details match_number_merge_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merge_details
    ADD CONSTRAINT match_number_merge_details_pkey PRIMARY KEY (id);


--
-- Name: match_number_merges match_number_merges_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merges
    ADD CONSTRAINT match_number_merges_pkey PRIMARY KEY (id);


--
-- Name: match_numbers match_numbers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_numbers
    ADD CONSTRAINT match_numbers_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: officials officials_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.officials
    ADD CONSTRAINT officials_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payment_methods payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: pools pools_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pools
    ADD CONSTRAINT pools_pkey PRIMARY KEY (id);


--
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- Name: posts posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_slug_unique UNIQUE (slug);


--
-- Name: randori_judge_scores randori_judge_scores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_judge_scores
    ADD CONSTRAINT randori_judge_scores_pkey PRIMARY KEY (id);


--
-- Name: randori_match_results randori_match_results_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_match_results
    ADD CONSTRAINT randori_match_results_pkey PRIMARY KEY (id);


--
-- Name: referee_observations referee_observations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_observations
    ADD CONSTRAINT referee_observations_pkey PRIMARY KEY (id);


--
-- Name: referee_score_details referee_score_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_score_details
    ADD CONSTRAINT referee_score_details_pkey PRIMARY KEY (id);


--
-- Name: referees referees_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referees
    ADD CONSTRAINT referees_pkey PRIMARY KEY (id);


--
-- Name: registration_athlete registration_athlete_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_athlete
    ADD CONSTRAINT registration_athlete_pkey PRIMARY KEY (id);


--
-- Name: registration_official registration_official_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_official
    ADD CONSTRAINT registration_official_pkey PRIMARY KEY (id);


--
-- Name: registrations registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations
    ADD CONSTRAINT registrations_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: rundowns rundowns_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rundowns
    ADD CONSTRAINT rundowns_pkey PRIMARY KEY (id);


--
-- Name: schedule_paniteras schedule_paniteras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT schedule_paniteras_pkey PRIMARY KEY (id);


--
-- Name: schedule_referees schedule_referees_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT schedule_referees_pkey PRIMARY KEY (id);


--
-- Name: session_times session_times_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session_times
    ADD CONSTRAINT session_times_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: techniques techniques_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.techniques
    ADD CONSTRAINT techniques_pkey PRIMARY KEY (id);


--
-- Name: tournament_results tournament_results_match_rank_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tournament_results
    ADD CONSTRAINT tournament_results_match_rank_unique UNIQUE (match_number_id, rank);


--
-- Name: tournament_results tournament_results_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tournament_results
    ADD CONSTRAINT tournament_results_pkey PRIMARY KEY (id);


--
-- Name: schedule_referees unique_judge_per_court; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT unique_judge_per_court UNIQUE (rundown_id, session_time_id, court_id, judge_index);


--
-- Name: randori_judge_scores unique_judge_score_node; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_judge_scores
    ADD CONSTRAINT unique_judge_score_node UNIQUE (match_number_id, bracket_node, judge_index);


--
-- Name: schedule_paniteras unique_schedule_panitera_slot; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT unique_schedule_panitera_slot UNIQUE (rundown_id, session_time_id, court_id, role_type, slot_index);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: weight_groups weight_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.weight_groups
    ADD CONSTRAINT weight_groups_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: referee_score_details_scorable_type_scorable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX referee_score_details_scorable_type_scorable_id_index ON public.referee_score_details USING btree (scorable_type, scorable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: active_court_referees active_court_referees_court_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.active_court_referees
    ADD CONSTRAINT active_court_referees_court_id_foreign FOREIGN KEY (court_id) REFERENCES public.courts(id) ON DELETE CASCADE;


--
-- Name: active_court_referees active_court_referees_referee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.active_court_referees
    ADD CONSTRAINT active_court_referees_referee_id_foreign FOREIGN KEY (referee_id) REFERENCES public.referees(id) ON DELETE CASCADE;


--
-- Name: athlete_category athlete_category_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_category
    ADD CONSTRAINT athlete_category_athlete_id_foreign FOREIGN KEY (athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: athlete_category athlete_category_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_category
    ADD CONSTRAINT athlete_category_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: athlete_category athlete_category_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_category
    ADD CONSTRAINT athlete_category_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: athlete_contingent athlete_contingent_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent
    ADD CONSTRAINT athlete_contingent_athlete_id_foreign FOREIGN KEY (athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: athlete_contingent athlete_contingent_contingent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent
    ADD CONSTRAINT athlete_contingent_contingent_id_foreign FOREIGN KEY (contingent_id) REFERENCES public.contingents(id) ON DELETE CASCADE;


--
-- Name: athlete_contingent_histories athlete_contingent_histories_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent_histories
    ADD CONSTRAINT athlete_contingent_histories_athlete_id_foreign FOREIGN KEY (athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: athlete_contingent_histories athlete_contingent_histories_contingent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_contingent_histories
    ADD CONSTRAINT athlete_contingent_histories_contingent_id_foreign FOREIGN KEY (contingent_id) REFERENCES public.contingents(id) ON DELETE CASCADE;


--
-- Name: athlete_match_number athlete_match_number_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_match_number
    ADD CONSTRAINT athlete_match_number_athlete_id_foreign FOREIGN KEY (athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: athlete_match_number athlete_match_number_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_match_number
    ADD CONSTRAINT athlete_match_number_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: athlete_match_number athlete_match_number_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.athlete_match_number
    ADD CONSTRAINT athlete_match_number_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: contingents contingents_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contingents
    ADD CONSTRAINT contingents_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: contingents contingents_verified_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contingents
    ADD CONSTRAINT contingents_verified_by_foreign FOREIGN KEY (verified_by) REFERENCES public.users(id);


--
-- Name: courts courts_active_drawing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courts
    ADD CONSTRAINT courts_active_drawing_id_foreign FOREIGN KEY (active_drawing_id) REFERENCES public.drawing_match_numbers(id) ON DELETE SET NULL;


--
-- Name: courts courts_active_match_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courts
    ADD CONSTRAINT courts_active_match_id_foreign FOREIGN KEY (active_match_id) REFERENCES public.match_numbers(id) ON DELETE SET NULL;


--
-- Name: courts courts_active_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courts
    ADD CONSTRAINT courts_active_registration_id_foreign FOREIGN KEY (active_registration_id) REFERENCES public.registrations(id) ON DELETE SET NULL;


--
-- Name: drawing_match_numbers drawing_match_numbers_court_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_court_id_foreign FOREIGN KEY (court_id) REFERENCES public.courts(id) ON DELETE SET NULL;


--
-- Name: drawing_match_numbers drawing_match_numbers_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: drawing_match_numbers drawing_match_numbers_pool_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_pool_id_foreign FOREIGN KEY (pool_id) REFERENCES public.pools(id) ON DELETE SET NULL;


--
-- Name: drawing_match_numbers drawing_match_numbers_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: drawing_match_numbers drawing_match_numbers_rundown_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_rundown_id_foreign FOREIGN KEY (rundown_id) REFERENCES public.rundowns(id) ON DELETE SET NULL;


--
-- Name: drawing_match_numbers drawing_match_numbers_session_time_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drawing_match_numbers
    ADD CONSTRAINT drawing_match_numbers_session_time_id_foreign FOREIGN KEY (session_time_id) REFERENCES public.session_times(id) ON DELETE SET NULL;


--
-- Name: embu_champions embu_champions_drawing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_champions
    ADD CONSTRAINT embu_champions_drawing_id_foreign FOREIGN KEY (drawing_id) REFERENCES public.drawing_match_numbers(id) ON DELETE SET NULL;


--
-- Name: embu_champions embu_champions_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_champions
    ADD CONSTRAINT embu_champions_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: embu_champions embu_champions_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_champions
    ADD CONSTRAINT embu_champions_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: embu_scores embu_scores_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_scores
    ADD CONSTRAINT embu_scores_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: embu_scores embu_scores_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.embu_scores
    ADD CONSTRAINT embu_scores_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: match_number_merge_details match_number_merge_details_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merge_details
    ADD CONSTRAINT match_number_merge_details_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: match_number_merge_details match_number_merge_details_match_number_merge_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merge_details
    ADD CONSTRAINT match_number_merge_details_match_number_merge_id_foreign FOREIGN KEY (match_number_merge_id) REFERENCES public.match_number_merges(id) ON DELETE CASCADE;


--
-- Name: match_number_merges match_number_merges_age_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_number_merges
    ADD CONSTRAINT match_number_merges_age_group_id_foreign FOREIGN KEY (age_group_id) REFERENCES public.age_groups(id) ON DELETE CASCADE;


--
-- Name: match_numbers match_numbers_active_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_numbers
    ADD CONSTRAINT match_numbers_active_registration_id_foreign FOREIGN KEY (active_registration_id) REFERENCES public.registrations(id) ON DELETE SET NULL;


--
-- Name: match_numbers match_numbers_age_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.match_numbers
    ADD CONSTRAINT match_numbers_age_group_id_foreign FOREIGN KEY (age_group_id) REFERENCES public.age_groups(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: officials officials_contingent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.officials
    ADD CONSTRAINT officials_contingent_id_foreign FOREIGN KEY (contingent_id) REFERENCES public.contingents(id) ON DELETE CASCADE;


--
-- Name: randori_judge_scores randori_judge_scores_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_judge_scores
    ADD CONSTRAINT randori_judge_scores_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: randori_match_results randori_match_results_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_match_results
    ADD CONSTRAINT randori_match_results_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: randori_match_results randori_match_results_winner_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.randori_match_results
    ADD CONSTRAINT randori_match_results_winner_athlete_id_foreign FOREIGN KEY (winner_athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: referee_observations referee_observations_contingent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_observations
    ADD CONSTRAINT referee_observations_contingent_id_foreign FOREIGN KEY (contingent_id) REFERENCES public.contingents(id) ON DELETE CASCADE;


--
-- Name: referee_observations referee_observations_referee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_observations
    ADD CONSTRAINT referee_observations_referee_id_foreign FOREIGN KEY (referee_id) REFERENCES public.referees(id) ON DELETE CASCADE;


--
-- Name: referee_score_details referee_score_details_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_score_details
    ADD CONSTRAINT referee_score_details_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: referee_score_details referee_score_details_referee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referee_score_details
    ADD CONSTRAINT referee_score_details_referee_id_foreign FOREIGN KEY (referee_id) REFERENCES public.referees(id) ON DELETE CASCADE;


--
-- Name: referees referees_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.referees
    ADD CONSTRAINT referees_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: registration_athlete registration_athlete_athlete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_athlete
    ADD CONSTRAINT registration_athlete_athlete_id_foreign FOREIGN KEY (athlete_id) REFERENCES public.athletes(id) ON DELETE CASCADE;


--
-- Name: registration_athlete registration_athlete_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_athlete
    ADD CONSTRAINT registration_athlete_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: registration_athlete registration_athlete_weight_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_athlete
    ADD CONSTRAINT registration_athlete_weight_group_id_foreign FOREIGN KEY (weight_group_id) REFERENCES public.weight_groups(id) ON DELETE SET NULL;


--
-- Name: registration_official registration_official_official_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_official
    ADD CONSTRAINT registration_official_official_id_foreign FOREIGN KEY (official_id) REFERENCES public.officials(id) ON DELETE CASCADE;


--
-- Name: registration_official registration_official_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_official
    ADD CONSTRAINT registration_official_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE CASCADE;


--
-- Name: registrations registrations_contingent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations
    ADD CONSTRAINT registrations_contingent_id_foreign FOREIGN KEY (contingent_id) REFERENCES public.contingents(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: schedule_paniteras schedule_paniteras_court_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT schedule_paniteras_court_id_foreign FOREIGN KEY (court_id) REFERENCES public.courts(id) ON DELETE CASCADE;


--
-- Name: schedule_paniteras schedule_paniteras_rundown_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT schedule_paniteras_rundown_id_foreign FOREIGN KEY (rundown_id) REFERENCES public.rundowns(id) ON DELETE CASCADE;


--
-- Name: schedule_paniteras schedule_paniteras_session_time_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT schedule_paniteras_session_time_id_foreign FOREIGN KEY (session_time_id) REFERENCES public.session_times(id) ON DELETE CASCADE;


--
-- Name: schedule_paniteras schedule_paniteras_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_paniteras
    ADD CONSTRAINT schedule_paniteras_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: schedule_referees schedule_referees_court_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT schedule_referees_court_id_foreign FOREIGN KEY (court_id) REFERENCES public.courts(id) ON DELETE SET NULL;


--
-- Name: schedule_referees schedule_referees_referee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT schedule_referees_referee_id_foreign FOREIGN KEY (referee_id) REFERENCES public.referees(id) ON DELETE CASCADE;


--
-- Name: schedule_referees schedule_referees_rundown_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT schedule_referees_rundown_id_foreign FOREIGN KEY (rundown_id) REFERENCES public.rundowns(id) ON DELETE CASCADE;


--
-- Name: schedule_referees schedule_referees_session_time_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schedule_referees
    ADD CONSTRAINT schedule_referees_session_time_id_foreign FOREIGN KEY (session_time_id) REFERENCES public.session_times(id) ON DELETE CASCADE;


--
-- Name: techniques techniques_kyu_level_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.techniques
    ADD CONSTRAINT techniques_kyu_level_id_foreign FOREIGN KEY (kyu_level_id) REFERENCES public.kyu_levels(id) ON DELETE SET NULL;


--
-- Name: tournament_results tournament_results_match_number_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tournament_results
    ADD CONSTRAINT tournament_results_match_number_id_foreign FOREIGN KEY (match_number_id) REFERENCES public.match_numbers(id) ON DELETE CASCADE;


--
-- Name: tournament_results tournament_results_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tournament_results
    ADD CONSTRAINT tournament_results_registration_id_foreign FOREIGN KEY (registration_id) REFERENCES public.registrations(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict FLAqSxPdVZkgQxw32uRvrFKZ9S4FiDQXXQAILRgn6qGKaP3xqhTAqj8cueiGtTY

