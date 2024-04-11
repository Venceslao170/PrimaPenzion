-- Active: 1693549141692@@127.0.0.1@3306@penzion
CREATE DATABASE penzion;

CREATE TABLE stranka(
	id VARCHAR(255) PRIMARY KEY,
	titulek VARCHAR (255) NOT NULL,
	menu VARCHAR (255),
	obrazek VARCHAR (255) NOT NULL,
	obsah TEXT NOT NULL,
	poradi INT UNSIGNED DEFAULT 0
);

DESC stranka;

INSERT INTO stranka SET id="kocka", titulek= "mnau", menu="cici", obrazek="primapenzion-main.jpg", obsah="mnau mnau mnau", poradi= 0;

