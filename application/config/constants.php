<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

// region DEVICE MANAGEMENT

// Teach-in status
define('DEVICE_STATUS_TAUGHT_IN', 1);
define('DEVICE_STATUS_PENDING_TEACH_IN', 0);

// Device state
define('DEVICE_STATE_CONTROLLER', 'controller');
define('DEVICE_STATE_CONTROLLED', 'controlled');
define('DEVICE_STATE_INPUT', 'input');

// endregion DEVICE MANAGEMENT

//region ACTION MANAGEMENT

// Action type
define('ACTION_TYPE_SCHEDULE', 0);
define('ACTION_TYPE_EVENT', 1);

// Action status
define('ACTION_ENABLE', 1);
define('ACTION_DISABLE', 0);

// Exception type
define('EXCEPTION_TYPE_DAY', 'day');
define('EXCEPTION_TYPE_DURATION', 'duration');

// Schedule days
define('ACTION_SCHEDULE_MONDAY', 1);
define('ACTION_SCHEDULE_TUESDAY', 2);
define('ACTION_SCHEDULE_WEDNESDAY', 3);
define('ACTION_SCHEDULE_THURSDAY', 4);
define('ACTION_SCHEDULE_FRIDAY', 5);
define('ACTION_SCHEDULE_SARTUDAY', 6);
define('ACTION_SCHEDULE_SUNDAY', 7);
define('ACTION_SCHEDULE_ALL_DAYS', 8);

//endregion ACTION MANAGEMENT

// region MODE CONTROL

// Mode control status
define('MODE_CONTROL_ENABLE', 1);
define('MODE_CONTROL_DISABLE', 0);

// endregion MODE CONTROL

// region CALLBACK CONSTANT

define('CALLBACK_ADD_EDIT_MODE_CONTROL', 'edit_control');

// endregion CALLBACK CONSTANT

// region USER ACCOUNT

define('USER_STATUS_ACTIVE', 1);
define('USER_STATUS_INACTIVE', 0);

define('USER_GROUP_ROOT_ADMIN', 1);
define('USER_GROUP_BUILDINGS_OWNER', 2);
define('USER_GROUP_ROOMS_ADMIN', 3);

define('USER_SESSION_NAME', 'user_session');

define('USER_IS_DELETE_TRUE', 1);
define('USER_IS_DELETE_FALSE', 0);

// endregion USER ACCOUNT

/* End of file constants.php */
/* Location: ./application/config/constants.php */