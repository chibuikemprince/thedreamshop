<?php
function scheduleEmail($emailData) {
    $url = "https://api.elasticemail.com/v4/emails";
    $apiKey = "D3681CF702924CD8CE0CCB27F43323AD38EFE0A2A84A5DE11CCE5078E358B559BB2A454FEB08A338A1B5640C950202D9";


    $headers = [
        "Content-Type: application/json",
        "X-ElasticEmail-ApiKey: " . $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    echo $response;
    
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "Error scheduling email: " . $error;
    } else {
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status_code == 200) {
            $response_data = json_decode($response, true);
            return "Email scheduled successfully. Message ID: " . $response_data["MessageID"];
        } else {
            return "Error scheduling email. Status code: " . $status_code;
        }
    }
}

// Example usage
$emailData = [
    "Recipients" => [
        [
            "Email" => "youngprince042@gmail.com",
            "Fields" => [
                "city" => "New York",
                "age" => "34"
            ]
        ]
    ],
    "Content" => [
        "Body" => [
            [
                "ContentType" => "HTML",
                "Content" => "string",
                "Charset" => "string"
            ]
        ],
        "Merge" => [
            "city" => "New York",
            "age" => "34"
        ],
        "Attachments" => [
            [
                "BinaryContent" => "string",
                "Name" => "string",
                "ContentType" => "string",
                "Size" => "100"
            ]
        ],
        "Headers" => [
            "city" => "New York",
            "age" => "34"
        ],
        "Postback" => "string",
        "EnvelopeFrom" => "John Doe <email@domain.com>",
        "From" => "John Doe <email@domain.com>",
        "ReplyTo" => "John Doe <email@domain.com>",
        "Subject" => "Hello!",
        "TemplateName" => "Template01",
        "AttachFiles" => [
            "preuploaded.jpg"
        ],
        "Utm" => [
            "Source" => "string",
            "Medium" => "string",
            "Campaign" => "string",
            "Content" => "string"
        ]
    ],
    "Options" => [
        "TimeOffset" => null,
        "PoolName" => "My Custom Pool",
        "ChannelName" => "Channel01",
        "Encoding" => "UserProvided",
        "TrackOpens" => "true",
        "TrackClicks" => "true"
    ]
];

$result = scheduleEmail($emailData);
echo $result;