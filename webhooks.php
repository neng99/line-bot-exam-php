<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Voa878ns7CMlnrk2ZgUPumnU0HqSCDQSf87dxOXlZM6HihqrqoHtqBDvg8jrbuQrW0zPzRyrjMYRAJJcWeRYrVkfIczewyrmPPfcyn4ujeyaWc8O/QhtiRJzjhxBwlMT61xVUw/EZx0psXQsrcpzggdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		/*
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
		*/
		// Get Image Sent
		if($events['events'][0]['message']['type'] == 'image'){
			$message_id= $events['events'][0]['message']['id'];
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '72c258be4626bcfdae2e89c4ea3397a8']);
			$response = $bot->getMessageContent($event['message']['id']);
				if ($response->isSucceeded()) {
					$dataBinary = $response->getRawBody();
				//chdir("imagesssss");
					$fileFullSavePath = 'Test.jpg';
					file_put_contents($fileFullSavePath,$dataBinary);
					$text = 'Success' ;
					}else{ // Get text sent
			$text = 'Oz';
			//getcwd();
				}
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
