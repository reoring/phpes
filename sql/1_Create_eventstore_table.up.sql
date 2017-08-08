DROP SEQUENCE IF EXISTS event_id_seq;
CREATE SEQUENCE event_id_seq;

CREATE TABLE eventstore (
    event_id bigint NOT NULL DEFAULT nextval('event_id_seq'),
    stream_name varchar(512) NOT NULL,
    stream_version integer NOT NULL,
    event_type varchar(512) NOT NULL,
    data TEXT NOT NULL,
    PRIMARY KEY (event_id)
);
