CREATE TABLE shortener
(
  hash character varying(80) NOT NULL,
  url character varying(255) NOT NULL,
  CONSTRAINT shortener_pkey PRIMARY KEY (hash)
);
