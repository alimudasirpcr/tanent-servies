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
    
        // Define the path to your Bash script
        $script_path = '/path/to/your/script.sh';
    
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
