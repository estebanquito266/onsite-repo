<?php

namespace App\Http\Controllers\googleApi;

if(! defined('STDIN')) define('STDIN', fopen("php://stdin", "r"));

use App\Http\Controllers\Controller;


use App\Models\Onsite\SolicitudBoucher;
use App\Services\Onsite\GoogleCalendar;
use App\Services\Onsite\SolicitudBoucherService;
use Exception;
use Google\Client;
use Google\Service\Calendar\Calendar;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;

class GoogleCalendarController extends Controller
{


    public function __construct()
    {
    }

    public function connect()
    {

        // Get the API client and construct the service object.
        /* $google_client = $this->getClient(); */

        $google_client = new Google_Client();
        $google_client->setApplicationName('Google Calendar API PHP Quickstart');
        $google_client->setScopes('https://www.googleapis.com/auth/calendar.events.readonly');
        /* $google_client->setClientId('1073175698188-9q9dkvkd5npt4afce2ev8jmj9dlpp8rv.apps.googleusercontent.com');
        $google_client->setClientSecret('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'); */

        
        $google_client->setAuthConfig('client_secret.json');

        $google_client->setAccessType('offline');
        $google_client->setPrompt('select_account consent');

        $authUrl = $google_client->createAuthUrl();
        return redirect($authUrl);

        /* $service = new Calendar($google_client); 
      
        // Print the next 10 events on the user's calendar.
        try {

            $calendarId = 'primary';
            $optParams = array(
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            );
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = $results->getItems();

            
            if (empty($events)) {
                print "No upcoming events found.\n";
            } else {
                print "Upcoming events:\n";
                foreach ($events as $event) {
                    $start = $event->start->dateTime;
                    if (empty($start)) {
                        $start = $event->start->date;
                    }
                    printf("%s (%s)\n", $event->getSummary(), $start);
                }
            }
        } catch (Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' . $e->getMessage();
        } */

        /* $client = new Google_Client(); */
        /* $client = GoogleCalendar::getClient();    */

        /* $client->getClientId();
        $authUrl = $client->createAuthUrl(); */

        /* return redirect($authUrl); */
        /* return $authUrl; */
    }

    public function store()
    {
        $client = new Google_Client();
        $authCode = request('code');

       
        // Load previously authorized credentials from a file.
        $credentialsPath = storage_path('keys/client_secret_generated.json');
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        return redirect('/')->with('message', 'Credentials saved');
    }

    public function getClient()
    {
            $client = new Google_Client();
            /*     $client->setApplicationName(config('app.name'));
            
                $client->setScopes(Google_Service_Directory::CALENDAR_READONLY); */
                $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes('https://www.googleapis.com/auth/calendar.events.readonly');
               //$client->setAuthConfig(storage_path('keys/client_secret.json'));
        $client->setAuthConfig('client_secret.json');

               $client->setAccessType('offline');
               return $client;
    }


    public function getResources()
    {
            // Get the authorized client object and fetch the resources.
            
            $client = GoogleCalendar::oauth();

            return GoogleCalendar::getResource($client);
    
     }
    
    


    function xxgetClient()
    {
        $client = new Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes('https://www.googleapis.com/auth/calendar.events.readonly');
        /* $client->setAuthConfig('credentials.json'); */
        $client->setAuthConfig('client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                /* $authCode = trim(fgets(STDIN)); */
                //$code='4%2F0AdQt8qjzwv9rBCJsPROCZYtCp0wB06kLCZ5MfT9u_RnCEPuHq2WC_-sEy6on0ZSJ1MqEkA';
                
                
                $authCode = trim(fgets(STDIN));
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

   

    public function storeBoucher(Request $request)
    {
    }



    /**
     * Display the specified resource.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *   $
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idSolicitudBoucher)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function getBouchersPorObra($idObra)
    {
    }

    public function unsetSessionVariable()
    {
    }

    public function getAllBouchersPorObra($idObra)
    {
    }
}
