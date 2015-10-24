<?php
if (!defined('QA_VERSION')) { 
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
	require_once QA_INCLUDE_DIR.'app/emails.php';
}

$LIMIT = (int)qa_opt('q2a-only-once-question-day');	// 閾値：日数
// for local test START
//$LIMIT = 16;
// for local test END
if (!is_numeric($LIMIT) or $LIMIT == '0') {
	return;
}

$posts = getXdaysAgoOnlyOnceQuestionPosts($LIMIT);
foreach($posts as $post){
	$user = getUserInfo($post['userid']);
	$handle = $user[0]['handle'];
	$email = $user[0]['email'];
$title = "plugin07 title";
	$bodyTemplate = qa_opt('q2a-only-once-question-body');
	$body = strtr($bodyTemplate, 
		array(
			'^username' => $handle,
			'^sitename' => qa_opt('site_title')
		)
	);
//$body = "plugin07 body.";
	sendEmail($title, $body, $handle, $email);
}

function sendEmail($title, $body, $toname, $toemail){

	$params['fromemail'] = qa_opt('from_email');
	$params['fromname'] = qa_opt('site_title');
	$params['subject'] = '【' . qa_opt('site_title') . '】' . $title;
	$params['body'] = $body;
	$params['toname'] = $toname;
	$params['toemail'] = $toemail;
	$params['html'] = false;
/*************
print "sendEmail\n";
foreach($params as $key => $val) {
print "key[".$key."] val[".$val."]\n";
}
*************/
	qa_send_email($params);

	//$params['toemail'] = 'yuichi.shiga@gmail.com';
	$params['toemail'] = 'ryuta_takeyama@nexyzbb.ne.jp';
	qa_send_email($params);
}
function getUserInfo($userid) {
    $sql = 'select email,handle from qa_users where userid=' . $userid;
    $result = qa_db_query_sub($sql);
    return qa_db_read_all_assoc($result);
}