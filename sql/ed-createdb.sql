CREATE TABLE IF NOT EXISTS ed_emaillist (
  ed_email_id INT unsigned NOT NULL AUTO_INCREMENT,
  ed_email_guid VARCHAR(255) NOT NULL,
  ed_email_name VARCHAR(255) NOT NULL,
  ed_email_mail VARCHAR(255) NOT NULL,
  ed_email_company VARCHAR(255) NOT NULL,
  ed_email_downloaddate datetime NOT NULL default '0000-00-00 00:00:00',
  ed_email_downloadcount INT unsigned NOT NULL,
  ed_email_downloadstatus VARCHAR(25) NOT NULL default 'Pending',
  ed_email_downloadid VARCHAR(255) NOT NULL,
  ed_email_form_guid VARCHAR(255) NOT NULL,
  PRIMARY KEY  (ed_email_id)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

-- SQLQUERY ---

CREATE TABLE IF NOT EXISTS ed_downloadform (
  ed_form_id INT unsigned NOT NULL AUTO_INCREMENT,
  ed_form_guid VARCHAR(255) NOT NULL,
  ed_form_title VARCHAR(255) NOT NULL,
  ed_form_description VARCHAR(255) NOT NULL,
  ed_form_downloadurl VARCHAR(255) NOT NULL,
  ed_form_downloadabspath VARCHAR(255) NOT NULL,
  ed_form_downloadcount INT unsigned NOT NULL,
  ed_form_expirationtype VARCHAR(25) NOT NULL default 'Never',
  ed_form_expirationdate date NOT NULL default '9999-12-31',
  ed_form_status VARCHAR(25) NOT NULL default 'Published',
  ed_form_downloadid VARCHAR(255) NOT NULL,
  PRIMARY KEY  (ed_form_id)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

-- SQLQUERY ---

CREATE TABLE IF NOT EXISTS ed_pluginconfig (
  ed_c_id INT unsigned NOT NULL AUTO_INCREMENT,
  ed_c_fromname VARCHAR(255) NOT NULL,
  ed_c_fromemail VARCHAR(255) NOT NULL,
  ed_c_mailtype VARCHAR(255) NOT NULL,
  ed_c_adminmailoption VARCHAR(255) NOT NULL,
  ed_c_adminemail VARCHAR(255) NOT NULL,
  ed_c_adminmailsubject VARCHAR(255) NOT NULL,
  ed_c_adminmailcontant TEXT NULL,
  ed_c_usermailoption VARCHAR(255) NOT NULL,
  ed_c_usermailsubject VARCHAR(255) NOT NULL,
  ed_c_usermailcontant TEXT NULL, 
  ed_c_downloadstart VARCHAR(255) NOT NULL,
  ed_c_downloadpgtxt TEXT NULL,
  ed_c_pipedrivekey TEXT NULL,
  ed_c_pipedrivedomain TEXT NULL,
  ed_c_cronurl VARCHAR(255) NOT NULL,
  ed_c_cronmailcontent VARCHAR(255) NOT NULL,
  ed_c_expiredlinkcontant TEXT NULL,
  ed_c_invalidlinkcontant TEXT NULL,
  PRIMARY KEY  (ed_c_id)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;