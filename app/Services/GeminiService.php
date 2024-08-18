use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

Route::get('/gemini/chatbot', function () {
$apiKey = env('GEMINI_API_KEY');
$apiSecret = env('GEMINI_API_SECRET');
$apiUrl = env('GEMINI_API_URL');

$endpoint = '/account';
$payload = [
'request' => $endpoint,
'nonce' => strval(time())
];

$encodedPayload = base64_encode(json_encode($payload));
$signature = hash_hmac('sha384', $encodedPayload, $apiSecret);

$headers = [
'X-GEMINI-APIKEY' => $apiKey,
'X-GEMINI-PAYLOAD' => $encodedPayload,
'X-GEMINI-SIGNATURE' => $signature,
'Content-Type' => 'text/plain'
];

$client = new Client();

try {
$response = $client->post($apiUrl . $endpoint, [
'headers' => $headers,
'body' => json_encode($payload)
]);

$accountInfo = json_decode($response->getBody()->getContents(), true);
return response()->json($accountInfo);
} catch (\Exception $e) {
Log::error('Error fetching account info from Gemini: ' . $e->getMessage());
return response()->json(['error' => 'Unable to fetch account info'], 500);
}
});