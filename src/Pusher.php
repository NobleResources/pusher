<?php


namespace VanLonden\Pusher;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use VanLonden\Pusher\Models\FuturePush;
use VanLonden\Pusher\Models\PastPush;
use VanLonden\Pusher\Models\PastPushError;

class Pusher
{
    /**
     * @var string
     */
    const END_POINT_URI = 'https://fcm.googleapis.com/fcm/send';

    /**
     * @var string
     */
    private $serverKey;
    /**
     * @var int
     */
    private $batchSize;
    /**
     * @var Client
     */
    private $http;

    /**
     * Pusher constructor.
     *
     * @param Client $http
     * @param string $serverKey
     * @param int    $batchSize
     */
    public function __construct(Client $http, $serverKey, $batchSize)
    {
        $this->http = $http;
        $this->serverKey = $serverKey;
        $this->batchSize = $batchSize;
    }

    /**
     * Sends a FuturePush and deletes the record in the database
     *
     * @param FuturePush $futurePush
     * @param bool       $doDelete
     */
    public function sendFuture(FuturePush $futurePush, $doDelete = true)
    {
        $this->send($futurePush->title, $futurePush->message, $futurePush->users);
        if ($doDelete) $futurePush->delete();
    }

    /**
     * Sends a push message
     *
     * @param string $title
     * @param string $message
     * @param Collection $users
     */
    public function send($title, $message, $users)
    {
        if ($users->count() == 0) {
            PastPush::create(['title' => $title, 'message' => $message]);
        }

        foreach ($users->chunk($this->batchSize) as $userBatch) {
            $registrationIds = $this->getRegistrationIds($userBatch);
            $response = $this->sendNotifications($title, $message, $registrationIds);
            $this->logPushNotification($title, $message, $response, $userBatch);
        }
    }

    /**
     * @param $title
     * @param $message
     * @param $registrationIds
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendNotifications($title, $message, $registrationIds)
    {
        return $this->http->post(self::END_POINT_URI, [
            'json'    => [
                'registration_ids' => $registrationIds,
                'notification'     => [
                    'title' => $title,
                    'body'  => $message
                ]
            ],
            'headers' => [
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type'  => 'application/json'
            ]
        ]);
    }

    /**
     * @param $users
     *
     * @return mixed
     */
    protected function getRegistrationIds($users)
    {
        return $users->pluck('registration_id')->toArray();
    }

    /**
     * @param $title
     * @param $message
     * @param $response
     * @param $users
     */
    protected function logPushNotification($title, $message, $response, $users)
    {
        $responseBody = json_decode($response->getBody()->getContents());

        $pushNotification = PastPush::create([
            'title'        => $title,
            'message'      => $message,
            'status_code'  => $response->getStatusCode(),
            'multicast_id' => $responseBody->multicast_id,
            'success'      => $responseBody->success,
            'failure'      => $responseBody->failure,
        ]);

        foreach ($responseBody->results as $index => $result) {
            $this->handleResult($users, $index, $pushNotification, $result);
        }
    }

    /**
     * @param Collection $users
     * @param integer    $index
     * @param PastPush   $push
     * @param \stdClass  $result
     */
    protected function handleResult($users, $index, $push, $result)
    {
        $user = $users[$index];

        $pushError = new PastPushError(['user_id' => $user->user_id]);

        if (isset($result->registration_id)) {
            $this->updateRegistrationId($user, $result->registration_id);
        }

        if (isset($result->error)) {
            if ($result->error === 'InvalidRegistration' || $result->error === 'NotRegistered') {
                $user->registration_id = '';
                $user->save();
            }

            $push->errors()->save($pushError);
        }
    }

    /**
     * @param $user
     * @param $registrationId
     */
    protected function updateRegistrationId($user, $registrationId)
    {
        $user->registration_id = $registrationId;
        $user->save();
    }

}