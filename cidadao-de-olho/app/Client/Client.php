<?Php 
namespace App\Client;

class Client
{
    public $curl;

	public function __construct()
	{
        $curl = curl_init();
		   $this->curl = $curl;
	} 
	
	public function requestGet($url)
	{
        try {
			curl_setopt_array($this->curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                   "authorization: <some_token>",
                   "cache-control: no-cache",
                ),
             ) );
             $response = curl_exec($this->curl);
		} catch (\Exception $e) {
          return [];
		}
      curl_close($this->curl);
      return $this->response_handler($response);
   }
   
   public function requestGetInde($url)
	{
         $curl = curl_init();
			curl_setopt_array($curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                   "authorization: <some_token>",
                   "cache-control: no-cache",
                ),
             ) );
             $response = curl_exec($curl);
             curl_close($curl);
      return $this->response_handler($response);
	}


	public function response_handler($response)
	{
		if ($response) {
			return json_decode($response);
		}
		
		return [];
	}
}