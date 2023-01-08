<?php 

class phpBitly{
    private $access_token = null;
    private $bitly_api= "https://api-ssl.bitly.com/v4";
    private $bilty_oauth_api = "";
    private $bitly_oauth_access_token = "";
    public function __construct($access_token= null)
    {
        $this->access_token = $access_token;
    }
    
    private function get_uri($action)
    {
        return "{$this->bitly_api}/{$action}";
    }
    
    private function get_headers()
    {
        return [
                "Authorization: Bearer {$this->access_token}",
                'Content-Type: application/json'
            ];
    }

    public function shorten($url,$params= [])
    {
        $params = array_replace([
            "long_url" => $url,
            "domain" => "bit.ly",
            "group_guid" => null
        ],$params);
        $headers = $this->get_headers();
        $url = "{$this->bitly_api}/shorten";
        return $this->post_curl($url, $params, $headers);
    }
    
    public function bitlink($url,$params= [])
    {
        $params = array_replace([
            "long_url" => $url,
            "domain" => "bit.ly",
            "group_guid" => null
        ],$params);
        $headers = $this->get_headers();
        $url = "{$this->bitly_api}/bitlinks";
        return $this->post_curl($url, $params, $headers);
    }

    public function delete_bitlink($bitlink){
        $headers = $this->get_headers();
        $url = "{$this->bitly_api}/bitlinks/{$bitlink}";

        return $this->post_curl($url, [], $headers, "delete");
    }

    public function get_bitlink($bitlink)
    {
        $headers = $this->get_headers();
        $url = "{$this->bitly_api}/bitlinks/{$bitlink}";

        return $this->post_curl($url, [], $headers, 'get'); 
    }

    public function update_bitlink($bitlink, $params= [])
    {
        $headers = $this->get_headers();
        $url = "{$this->bitly_api}/bitlinks/{$bitlink}";

        return $this->post_curl($url, $params, $headers, 'patch'); 
    }

    private function post_curl($url, $params=[], $headers= [], $request = "post")
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        if($request == 'post')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else if($request == 'get'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }    
        else if($request == 'delete')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        }
        else if($request == 'patch')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        }

        if($params)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }


}
$access_token= "AAAAAA";

$bitly = new phpBitly($access_token);
// $short= $bitly->shorten("https://google.com");
// var_dump($short);

// $bitlink = $bitly->bitlink("https://reddit.com",["title" => "this is bing"]);
// var_dump($bitlink);

// $delete_bitlink = $bitly->delete_bitlink("binged.it/3CsMVbp");
// var_dump($delete_bitlink); 


// $bitlink = $bitly->update_bitlink("bit.ly/3WVwLje",["long_url" => "https://old.reddit.com", "title" => "this is old reddit"]);
// var_dump($bitlink);

$bitlink = $bitly->get_bitlink('bit.ly/3VSFilB');
var_dump($bitlink);