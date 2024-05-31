<?php

// Include the TwitterOAuth library
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$consumer_key = '0DdTaREgtHXrOpJmmtEtJ9uX1';
$consumer_secret = 'RwvFoC7Kkp0LGQm41j5KYxDcEloiQoaRNibKAIuzFqfv4ZkwMH';
$access_token = '357357515-Rch01zACJJMJydHvaeVqkxPvgo2TNuRIvEyQDUBW';
$access_token_secret = 'l4tSdzKIOLC66Ir1tXGQrMpnIqUw3McvYVGwq5cGZurHW';



// Function to post a tweet with an image from a MySQL database blob
function postTweetWithImageFromDatabase($consumer_key, $consumer_secret, $access_token, $access_token_secret, $tweet_text, $symbol) {
    // Create a TwitterOAuth object
    $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $twitter->setApiVersion('2');

    // Establish a connection to your MySQL database
    $db_host = 'daytradeinsight.com';
    $db_user = 'daytrade_insight';
    $db_pass = 'y#-mXT580?zb';
    $db_name = 'daytrade_insight';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        return 'Error connecting to the database: ' . $conn->connect_error;
    }

    // Query the database to retrieve the image blob (modify the query as needed)
    $sql = 'SELECT plot FROM plots WHERE symbol like "%'.$symbol.'%"'; // Modify the query as needed
    $result = $conn->query($sql);

    if ($result === false) {
        $conn->close();
        return 'Error executing the database query: ' . $conn->error;
    }

    // Fetch the image blob data as binary
    $row = $result->fetch_assoc();
    $image_blob = $row['plot'];

    // Check if the image blob data is valid
    if ($image_blob === null || !is_string($image_blob) || empty($image_blob)) {
        $conn->close();
        return 'Error: Invalid image data retrieved from the database';
    }

    // Create a temporary file for the image
    $temp_image_path = tempnam(sys_get_temp_dir(), 'tweet_image');
    file_put_contents($temp_image_path, $image_blob);

    // Check if the temporary file was created successfully
    if (!file_exists($temp_image_path)) {
        $conn->close();
        return 'Error creating the temporary image file';
    }

    // Upload the image to Twitter
    $media = $twitter->upload('media/upload', ['media' => $temp_image_path]);

    // Check if the media was uploaded successfully
    if (!isset($media->media_id_string)) {
        $conn->close();
        return 'Error uploading media: ' . $twitter->getLastHttpCode();
    }

    // Tweet with the uploaded image
    $tweet_result = $twitter->post('statuses/update', [
        'status' => $tweet_text,
        'media_ids' => $media->media_id_string // Attach the uploaded media to the tweet
    ]);

    // Close the database connection
    $conn->close();

    // Check if the tweet was successful
    if ($twitter->getLastHttpCode() == 200) {
        return 'Tweet with image from database posted successfully!';
    } else {
        return 'Error posting tweet with image from database: ' . $tweet_result->errors[0]->message;
    }
}

function createTwitterKeyword($input_text, $max_tokens = 50, $temperature = 0.7) {
    $api_key = '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'; // Replace with your OpenAI API key
    $api_endpoint = 'https://api.openai.com/v1/engines/gpt-3.5-turbo/completions';

    $data = [
        'prompt' => 'Write stock name for symbol ' . $input_text,
        'max_tokens' => $max_tokens,
        'temperature' => $temperature
    ];

    $headers = [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json',
    ];

    $ch = curl_init($api_endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return 'Error making the API request';
    }

    $result = json_decode($response, true);

    if (isset($result['choices'][0]['text'])) {
        return trim($result['choices'][0]['text']);
    } else {
        return 'Error: ' . $result['error']['message'];
    }
}

$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");
var_dump($content);

/*
// Example usage of the function
$symbol = "AMBUJACEM";
$input_text = $symbol;
$tweet_text = createTwitterKeyword($input_text);
$result = postTweetWithImageFromDatabase($consumer_key, $consumer_secret, $access_token, $access_token_secret, $tweet_text, $symbol);
echo $result;
*/
?>
