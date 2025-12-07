<?php
    // Set the header to indicate the response is JSON
    header('Content-Type: application/json');

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $doctorId = $data['doctor_id'] ?? null;
        $doctorName = $data['doctor_name'] ?? 'Unknown Doctor';
        $action = $data['action'] ?? null;

        if ($doctorId && $action === 'add') {
            // Success Simulation
            $response = [
                'status' => 'success',
                'message' => "successfully added '{$doctorName}' to the list.",
                'received_id' => $doctorId
            ];
            echo json_encode($response);
        } else {
            http_response_code(400); // Bad Request
            $response = [
                'status' => 'error', 
                'message' => 'Invalid or incomplete data received. Doctor ID missing.'
            ];
            echo json_encode($response);
        }
    } else {
        http_response_code(405); // Method Not Allowed
        $response = [
            'status' => 'error', 
            'message' => 'Only POST method is allowed for this endpoint.'
        ];
        echo json_encode($response);
    }
?>