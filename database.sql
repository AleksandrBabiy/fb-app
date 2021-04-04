DROP TABLE IF EXISTS accounts;
CREATE TABLE accounts
(
   id serial NOT NULL, 
   username character varying NOT NULL, 
   title character varying NOT NULL, 
   email character varying(100) NOT NULL, 
   phone character varying(30) NOT NULL, 
   facebook_account character varying(30) DEFAULT NULL, 
   CONSTRAINT id PRIMARY KEY (id)
) 
WITH (
  OIDS = FALSE
)
;
DELETE FROM accounts;
INSERT INTO accounts (id, username, title, email, phone, facebook_account) VALUES
(1, 'User1', 'This is the title', 'info@netaffinity.com', '+353 (0) 12939906', 'NetAffinity'),
(2, 'User1', 'This is the title 2', 'info@netaffinity.com', '+353 (0) 12345678', 'cocacola'),
(3, 'User1', 'This is the title 3', 'info@netaffinity.com', '+353 (0) 98756432', 'Fics1954');