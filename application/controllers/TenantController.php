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



 // Check for cURL errors
        if ($response === false) {
            log_message('error', "cURL Error: " . curl_error($ch));
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to connect to CWP API.']));
        }
        
        curl_close($ch);

        // Parse the API response
        $responseData = json_decode($response, true);

        if ($responseData === null) {
            log_message('error', "Invalid JSON response: " . $response);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Invalid response from CWP API.']));
        }

        if (!isset($responseData['status']) || $responseData['status'] !== "OK") {
            $error_message = $responseData['message'] ?? 'Unknown error';
            log_message('error', "CWP API Error: " . $error_message);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => $error_message]));
        }

        log_message('info', "CWP account created successfully: " . $tenant_name);

        $ftpServer = "localhost";
$ftpUser = 'root'; // FTP username
$ftpPass = "M0hd@427504+24v1";   // FTP password

$connId = ftp_connect($ftpServer);

if ($connId && ftp_login($connId, $ftpUser, $ftpPass)) {
    $sourceDir = "/home/main_folder";
    $destDir = "/home/".$tenant_name;

    // Upload all files
    function ftp_upload_directory($ftp, $srcDir, $destDir) {
        $files = scandir($srcDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;

            $srcFile = $srcDir . '/' . $file;
            $destFile = $destDir . '/' . $file;

            if (is_dir($srcFile)) {
                ftp_mkdir($ftp, $destFile);
                ftp_upload_directory($ftp, $srcFile, $destFile);
            } else {
                ftp_put($ftp, $destFile, $srcFile, FTP_BINARY);
            }
        }
    }

    ftp_upload_directory($connId, $sourceDir, $destDir);
    ftp_close($connId);

    echo "Files copied successfully via FTP.";
} else {
    echo "FTP connection failed.";
}


// Respond with success
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'message' => 'Account created and script executed successfully.',
                'script_output' => $script_output
            ]));
    }
    
}
