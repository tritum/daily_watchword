CREATE TABLE tx_dailywatchword_domain_model_watchword (
    uid int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid int(11) DEFAULT '0' NOT NULL,

    date VARCHAR(10) NOT NULL,
    weekday VARCHAR(10) NOT NULL,
    sunday_message VARCHAR(128) NULL,
    watchwordVerse VARCHAR(128) NOT NULL,
    watchwordText VARCHAR(1024) NOT NULL,
    teachVerse VARCHAR(128) NOT NULL,
    teachText VARCHAR(1024) NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);
