<?php
/*
	Plugin Name: only once question mail
	Plugin URI: 
	Plugin Description: send advice mail to users that are only once question X days ago
	Plugin Version: 0.3
	Plugin Date: 2015-10-20
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 
*/
/*
## cron setting  

0 8 * * * {qa-root-path}/qa-plugin/q2a-only-once-question-email/send-email.php 

*/
if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module('module', 'q2a-only-once-question-admin.php', 'q2a_only_once_question_admin', 'only once question mail');

function getXdaysAgoOnlyOnceQuestionPosts($days) {
	$days_from = $days;
	$days_to = $days + 1;
	$sql = "select * from";
	$sql .= " (select userid,count(postid) as postcount,datediff(current_date,created) as dfday";
	$sql .= " from qa_posts where type='Q' group by userid) t0";
	$sql .= " where postcount=1 and userid is not null";
	$sql .= " and dfday >= " . $days_from . " and dfday < " . $days_to;
	$result = qa_db_query_sub($sql); 
	return qa_db_read_all_assoc($result);
}


