<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TenantController extends CI_Controller {
    
    public function create_tenant() {
        // Get tenant name from the request
        $tenant_name = $this->input->post('tenant_name');
    
        if (empty($tenant_name)) {
            // Respond with error if no tenant name is provided
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Tenant name is required.']));
        }
        

        $apiKey = "SSoqIsZN80vvtaBD9cNVcydcsq7v3g7s5eChLeTCkSrAx5KXkUSdbcaw5IaI3U4N8C72tV"; // Replace with your actual API key
        $apiUrl = "https://ecom-multivendor.omancloud.net:2304/v1/account"; // Replace with your CWP API URL
        
        // Account details for the new account
        $data = array(
            "key" => $apiKey,
            "action" => "add",
            "domain" => $tenant_name.".oo.om",       // Replace with the account domain
            "user" => $tenant_name,              // Replace with the account username
            "pass" => "PASSWORD",          // Replace with a secure password
            "email" =>  $tenant_name."@account",    // Replace with the account email
            "package" => "1",   // Replace with the package name
            "inode" => "0",
            "limit_nproc" => "40",
            "limit_nofile" => "0",
          "debug" =>1,
            "server_ips" => "65.109.95.216" // Replace with your server IP
        );

         $postdata = http_build_query($data);
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        
        // Execute the API request
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if ($responseData === null) {
                echo "Invalid JSON response. Raw response: " . htmlspecialchars($response);
            } elseif (isset($responseData['status']) && $responseData['status'] === "OK") {
                echo "Account created successfully!";
            } else {
                echo "Failed to create account. Response: " . ($responseData['message'] ?? 'Unknown error');
            }
        }
        
        // Close the cURL session
        curl_close($ch);


       
    }

    public function create_dns() {
        // Get tenant name from the request
        $tenant_name = $this->input->post('tenant_name');
    
        if (empty($tenant_name)) {
            // Respond with error if no tenant name is provided
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Tenant name is required.']));
        }
        



        $zone_id = '39721e473aaf9a10f3bf65d48895d816'; // Replace with your actual Zone ID
        $api_token = 'TLle8QedjnBVjB7B5_9UrW5j6Xid76WpWN7k5ttx'; // Replace with your API Token
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode(array(
              'type' => 'CNAME',
              'name' => $tenant_name.'.oo.om',
              'content' => 'ecom-multivendor.omancloud.net', // Replace with the target for the CNAME record
              'ttl' => 3600, // Time to live (optional, default is 1 for automatic TTL)
              'proxied' => false // Set to true if you want to enable Cloudflare proxy
          )),
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $api_token",
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

       
    }

    public function get_dns_zones(){
        $curl = curl_init();
        $api_token = 'TLle8QedjnBVjB7B5_9UrW5j6Xid76WpWN7k5ttx'; // Replace with your API Token
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.cloudflare.com/client/v4/zones',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $api_token",
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    
}
