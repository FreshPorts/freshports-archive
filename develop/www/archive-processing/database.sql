--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- TOC entry 6 (OID 27036139)
-- Name: plpgsql_call_handler (); Type: FUNCTION; Schema: public; Owner: pgsql
--

CREATE FUNCTION plpgsql_call_handler () RETURNS language_handler
    AS '$libdir/plpgsql', 'plpgsql_call_handler'
    LANGUAGE c;


--
-- TOC entry 5 (OID 27036140)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: public; Owner: 
--

CREATE TRUSTED PROCEDURAL LANGUAGE plpgsql HANDLER plpgsql_call_handler;


--
-- TOC entry 7 (OID 27036141)
-- Name: messageidfound (text); Type: FUNCTION; Schema: public; Owner: dan
--

CREATE FUNCTION messageidfound (text) RETURNS integer
    AS '
DECLARE
	MessageID	ALIAS FOR $1;
	CommitLogID	text;

BEGIN
	SELECT id
	  INTO CommitLogID
	  FROM commit_log
	 WHERE message_id = MessageID;

	return CommitLogID;
END;'
    LANGUAGE plpgsql;


--
-- TOC entry 2 (OID 27036142)
-- Name: commit_log; Type: TABLE; Schema: public; Owner: dan
--

CREATE TABLE commit_log (
    id integer NOT NULL,
    message_id text NOT NULL
);


--
-- Data for TOC entry 8 (OID 27036142)
-- Name: commit_log; Type: TABLE DATA; Schema: public; Owner: dan
--

COPY commit_log (id, message_id) FROM stdin;
1	200308262026.h7QKQMFw011958@repoman.freebsd.org
2	200308262055.h7QKtg57013416@repoman.freebsd.org
3	200308262114.h7QLEGRj015063@repoman.freebsd.org
\.


--
-- TOC entry 4 (OID 27036181)
-- Name: message_id; Type: INDEX; Schema: public; Owner: dan
--

CREATE UNIQUE INDEX message_id ON commit_log USING btree (message_id);


--
-- TOC entry 3 (OID 27036144)
-- Name: commit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: dan
--

ALTER TABLE ONLY commit_log
    ADD CONSTRAINT commit_log_pkey PRIMARY KEY (id);


