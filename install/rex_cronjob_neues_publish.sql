SET NAMES utf8mb4;

INSERT INTO `rex_cronjob` (`name`, `description`, `type`, `parameters`, `interval`, `nexttime`, `environment`, `execution_moment`, `execution_start`, `status`, `createdate`, `createuser`, `updatedate`, `updateuser`) VALUES
('[neues] Geplante Beiträge veröffentlichen',	'Veröffentlicht alle Beiträge (status = 1), deren Status geplant (status = 0) ist und deren Veröffentlichungszeitpunkt erreicht wurde (publishdate < now()).',	'rex_cronjob_neues_publish',	'[]',	'{\"minutes\":\"all\",\"hours\":\"all\",\"days\":\"all\",\"weekdays\":\"all\",\"months\":\"all\"}',	NOW(),	'|frontend|backend|script|',	0,	NOW(),	1,	NOW(),	'neues',	NOW(),	'neues');
