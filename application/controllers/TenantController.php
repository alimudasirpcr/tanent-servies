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
            "email" => "email@account",    // Replace with the account email
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




        // Define the path to your Bash script
        $script_path = '/home/sareehap/public_html/setup_tenant.sh'; // Update the correct path

    
        // Execute the script with the tenant name and capture the output
        $command = escapeshellcmd("bash $script_path $tenant_name");
        $output = shell_exec($command);
    
        // Check for any error in execution and return it
        if ($output === null) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Error executing the script.']));
        }
    
        // Respond with the output of the script
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['message' => 'Script executed.', 'output' => $output]));
    }
    
}
