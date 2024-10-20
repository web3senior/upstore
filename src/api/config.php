<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# Basic
const LOG = __DIR__ . '/app.log';
define('NAME', 'Universal Link');
define('AUTHOR', 'Aratta Labs');
define('URL', 'http://localhost/upstore/api/');

const LIBS = 'libs/';
const JSON = 'src/data/';
define('CSS_VERSION', date('l jS \of F Y h:i:s A'));
const PATH_ADMIN = 'panel/';
const PATH_USER = 'user';
const UPLOAD_IMAGE_PATH = 'upload/images/';
const UPLOAD_DOC = 'upload/doc/';

# Database
const DB_TYPE = 'mysql';
const DB_HOST = 'localhost';
const DB_NAME = 'upstore';
const DB_CHARSET = 'utf8mb4';
const DB_USER = 'root';
const DB_PASS = '';
const DB_TABLE_PRE = 'aratta_';

# Security
const HASH_GENERAL_KEY = 'amirRahimi';
const HASH_PASSWORD_KEY = 'programmingismyjob';
const EMAIL = 'web3senior@gmail.com';
const ENABLE_UPLOADS = true;
const MERCHANT_ID = 'e93f6a8e-6b09-4a91-83ef-c1095fb2242d';

# Auth0
const AUTH_DOMAIN = "lampstack.us.auth0.com";
const AUTH_CLIENT_ID = "OPpwdNnmhKK7ZUwI7Pn6iyjklHaBZ9JM";
const AUTH_CLIENT_SECRET = "biQzEyudNtC3TSOUeT_XBx4Mzrhy2NkWTCl93oEXBHsKKiYKnB8WKwq50OJ61ClS";

# Google
const GOOGLE_MAP_API = 'AIzaSyDe1wJlZbkhsipyGqY8luYul7z2KnR8Soo';
const GOOGLE_RECAPTCHA_SITE_KEY = '6Ld1RC8lAAAAABB5VgvbebgX5H6vuBOvAQUdn0Oo';
const GOOGLE_RECAPTCHA_SECREAT_KEY = '6Ld1RC8lAAAAAPau9Uo58Et0HFoXhWMZSJsgfWQJ';
const FCM_SERVER_KEY = '';
const GOOGLE_ANALYTICS = '';
const GOOGLE_CLIENT_ID = '896010653760-nr605l0jstsqq1prhrdirshu9tqd07ub.apps.googleusercontent.com';
const GOOGLE_SECRET_ID = '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';

# Facebook
const FB_APP_ID = '636082127320110';
const FB_APP_SECRET = '1574af9a636f13e6129b940de476d67d';

# HCaptcha
const HCAPTCHA_SECRET = '0xa3dFEF5b2b1485538248d6C99a6752C40a572D9E';
const HCAPTCHA_RESPONSE = 'a55f7aa4-426b-4b60-bcf4-223c44ae4d82';

# Region
date_default_timezone_set('Asia/Tehran');

# Session
$sec = (8 * 60 * 60) * 5;
ini_set('session.gc_maxlifetime', $sec);
session_set_cookie_params($sec);

# UI
define('ICON_DELETE', '<i class="ms-Icon ms-Icon--Delete text-danger" aria-hidden="true"></i>');
define('ICON_EDIT', '<i class="ms-Icon ms-Icon--SingleColumnEdit text-warning" aria-hidden="true"></i>');
define('ICON_ACTIVE', '<i class="ms-Icon ms-Icon--ToggleRight badge badge-success" aria-hidden="true"></i>');
define('ICON_DEACTIVE', '<i class="ms-Icon ms-Icon--ToggleLeft badge badge-warning" aria-hidden="true"></i>');
define('ICON_OPEN', '<i class="ms-Icon ms-Icon--OpenInNewWindow" aria-hidden="true"></i>');
define('ICON_PREVIEW', '<i class="ms-Icon ms-Icon--RedEye" aria-hidden="true"></i>');
define('ICON_CLOSE', '<span class="material-icons text-danger">clear</span>');
define('ICON_DONE', '<span class="material-icons text-success">done</span>');
define('ICON_COPY', '<span class="material-icons">content_copy</span>');
define('ICON_LINK', '<span class="material-icons">link</span>');
define('ICON_SMS', '<i class="ms-Icon ms-Icon--Message" aria-hidden="true"></i>');
define('ICON_UPLOAD', '<i class="ms-Icon ms-Icon--CloudUpload" aria-hidden="true"></i>');
define('ICON_USER', '<i class="ms-Icon ms-Icon--UserFollowed" aria-hidden="true"></i>');