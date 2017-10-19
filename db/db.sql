create database clothing;

----------------------------------------------------------------------------------------------------

CREATE TABLE goods (
    g_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    g_cat int(10) unsigned NOT NULL default 0,
    g_title varchar(255) NOT NULL,
    g_desc text NOT NULL,
    g_price decimal not null,
    g_image varchar(255) NOT NULL,
    g_ctime datetime NOT NULL,
    g_mtime datetime NOT NULL,
    g_count tinyint NOT NULL,
    g_total_price decimal NOT NULL default 0,
    g_state tinyint NOT NULL default 0,
    g_views int(10) unsigned NOT NULL default 0,
    PRIMARY KEY(g_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE categories (
    cat_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    cat_parent int(10) unsigned NOT NULL default 0,
    cat_desc varchar(255) NOT NULL,
    PRIMARY KEY(cat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE users (
    u_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    u_type int(10) unsigned NOT NULL,
    u_login varchar(255) NOT NULL,
    u_pswd char(32) NOT NULL,
    u_phone char(15) NOT NULL,
    u_location varchar(255),
    PRIMARY KEY(u_id),
    UNIQUE(u_phone),
    UNIQUE(u_login),
    UNIQUE(u_olx_profile)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE dim_grid (
    dg_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    dg_good int(10) unsigned NOT NULL,
    dg_data TEXT NOT NULL,
    dg_state tinyint NOT NULL default 0,
    PRIMARY KEY(dg_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE sales (
    s_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    s_good int(10) unsigned NOT NULL,
    s_earn float NOT NULL,
    s_date datetime NOT NULL,
    s_user int(10) unsigned NOT NULL,
    s_dim int unsigned not null default 0,
    PRIMARY KEY(s_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE clients (
    c_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    c_name varchar(255) NOT NULL,
    c_phone char(15) NOT NULL,
    c_address varchar(255),
    PRIMARY KEY(c_id),
    UNIQUE(c_phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE orders (
    o_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    o_desc text NOT NULL,
    o_pay tinyint NOT NULL default 0,
    o_delivery tinyint NOT NULL default 0,
    o_name varchar(255) NOT NULL,
    o_address text NOT NULL,
    o_phone varchar(20) NOT NULL,
    o_mail varchar(255) NOT NULL,
    o_message text NOT NULL,
    o_date datetime NOT NULL,
    o_state tinyint NOT NULL default 0,
    o_total_price smallint  NOT NULL,
    PRIMARY KEY(o_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE visitors (
    v_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    v_ip char(15) NOT NULL,
    v_date datetime NOT NULL,
    PRIMARY KEY(v_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE questions (
    q_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    q_ip char(15) NOT NULL,
    q_date datetime NOT NULL,
    q_contact varchar(255) NOT NULL,
    q_text text NOT NULL,
    q_good int(10) unsigned NOT NULL,
    q_state tinyint NOT NULL default 0,
    PRIMARY KEY(q_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------

CREATE TABLE feedbacks (
    f_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    f_ip char(15) NOT NULL,
    f_date datetime NOT NULL,
    f_contact varchar(255) NOT NULL,
    f_text text NOT NULL,
    f_good int(10) unsigned NOT NULL,
    f_state tinyint NOT NULL default 0,

    PRIMARY KEY(f_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------------------------------------------------------------------------------------------------
