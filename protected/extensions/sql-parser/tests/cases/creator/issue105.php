<?php

require_once dirname(__FILE__) . '/../../../src/PHPSQLParser.php';
require_once dirname(__FILE__) . '/../../../src/PHPSQLCreator.php';
require_once dirname(__FILE__) . '/../../test-more.php';

$sql = "SELECT users0.user_name AS 'CIS UserName'
	,calls.description AS 'Description'
	,contacts2.first_name AS 'Contacts First Name'
	,contacts2.last_name AS 'Contacts Last Name'
	,calls_cstm.date_logged_c AS 'Date'
	,calls_cstm.contact_type_c AS 'Contact Type'
	,dbo.fn_GetAccountName(calls.parent_id) AS 'Account Name'
FROM calls
LEFT JOIN calls_cstm ON calls.id = calls_cstm.id_c
LEFT JOIN users users0 ON calls.assigned_user_id = users0.id
LEFT JOIN contacts contacts2 ON calls.contact_id = contacts2.id
WHERE calls.deleted = 0
	AND (
		DATEADD(SECOND, 0, calls_cstm.date_logged_c) BETWEEN '2013-01-01'
			AND '2013-12-31'
		)
ORDER BY dbo.fn_GetAccountName(calls.parent_id) ASC LIMIT 0
	,15";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'issue105.sql', false);
ok($created === $expected, 'function within order-by');

?>