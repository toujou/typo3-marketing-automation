#
# Table structure for table 'tx_marketingautomation_persona'
#
CREATE TABLE tx_marketingautomation_persona (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    sorting int(11) unsigned DEFAULT '0' NOT NULL,

    title varchar(255) DEFAULT '' NOT NULL,
    description text,

    PRIMARY KEY (uid)
);
