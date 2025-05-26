<?php

/**
 * Mercado Pago Integration Backend
 * 
 * This file handles the server-side integration with Mercado Pago API
 * for processing payments in the Ecclesia system.
 */

// Load environment variables
require_once __DIR__ . '/../../vendor/autoload.php';

// Configure Mercado Pago SDK
MercadoPago\SDK::setAccessToken(getenv('MERCADO_PAGO_ACCESS_TOKEN'));

/**
 * Create a payment preference
 * 
 * @param array $data Payment data
 * @return array Preference data
 */
function createPreference($data)
{
    // Create a preference object
    $preference = new MercadoPago\Preference();
    
    // Create an item object
    $item = new MercadoPago\Item();
    $item->title = $data['description'];
    $item->quantity = 1;
    $item->unit_price = $data['price'];
    
    // Add item to preference
    $preference->items = array($item);
    
    // Set payer information
    $payer = new MercadoPago\Payer();
    $payer->name = $data['name'];
    $payer->email = $data['email'];
    $payer->phone = array(
        "area_code" => "",
        "number" => $data['phone']
    );
    
    $preference->payer = $payer;
    
    // Set back URLs
    $preference->back_urls = array(
        "success" => $data['success_url'],
        "failure" => $data['failure_url'],
        "pending" => $data['pending_url']
    );
    
    $preference->auto_return = "approved";
    
    // Set additional info
    $preference->external_reference = $data['external_reference'];
    $preference->notification_url = $data['notification_url'];
    
    // Save preference
    $preference->save();
    
    return [
        'id' => $preference->id,
        'init_point' => $preference->init_point,
        'sandbox_init_point' => $preference->sandbox_init_point
    ];
}

/**
 * Process webhook notifications from Mercado Pago
 * 
 * @param string $topic Notification topic
 * @param string $id Resource ID
 * @return array Response data
 */
function processWebhook($topic, $id)
{
    if ($topic === 'payment') {
        $payment = MercadoPago\Payment::find_by_id($id);
        
        // Process payment status
        switch ($payment->status) {
            case 'approved':
                // Payment approved - activate subscription
                activateSubscription($payment->external_reference, $payment->id);
                break;
                
            case 'pending':
                // Payment pending - mark subscription as pending
                markSubscriptionPending($payment->external_reference, $payment->id);
                break;
                
            case 'rejected':
                // Payment rejected - mark subscription as failed
                markSubscriptionFailed($payment->external_reference, $payment->id);
                break;
        }
        
        return [
            'status' => 'success',
            'message' => 'Webhook processed successfully'
        ];
    }
    
    return [
        'status' => 'error',
        'message' => 'Invalid topic'
    ];
}

/**
 * Activate a subscription after successful payment
 * 
 * @param string $reference External reference
 * @param string $paymentId Payment ID
 * @return bool Success status
 */
function activateSubscription($reference, $paymentId)
{
    // In a real implementation, this would update the database
    // to activate the subscription for the specified reference
    
    // For demonstration purposes, we'll just log the action
    error_log("Subscription activated: {$reference}, Payment ID: {$paymentId}");
    
    return true;
}

/**
 * Mark a subscription as pending
 * 
 * @param string $reference External reference
 * @param string $paymentId Payment ID
 * @return bool Success status
 */
function markSubscriptionPending($reference, $paymentId)
{
    // In a real implementation, this would update the database
    // to mark the subscription as pending
    
    // For demonstration purposes, we'll just log the action
    error_log("Subscription pending: {$reference}, Payment ID: {$paymentId}");
    
    return true;
}

/**
 * Mark a subscription as failed
 * 
 * @param string $reference External reference
 * @param string $paymentId Payment ID
 * @return bool Success status
 */
function markSubscriptionFailed($reference, $paymentId)
{
    // In a real implementation, this would update the database
    // to mark the subscription as failed
    
    // For demonstration purposes, we'll just log the action
    error_log("Subscription failed: {$reference}, Payment ID: {$paymentId}");
    
    return true;
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Handle different endpoints
    $endpoint = $_GET['endpoint'] ?? '';
    
    switch ($endpoint) {
        case 'create-preference':
            // Create payment preference
            $result = createPreference($data);
            header('Content-Type: application/json');
            echo json_encode($result);
            break;
            
        case 'webhook':
            // Process webhook notification
            $topic = $_GET['topic'] ?? '';
            $id = $_GET['id'] ?? '';
            
            $result = processWebhook($topic, $id);
            header('Content-Type: application/json');
            echo json_encode($result);
            break;
            
        default:
            // Invalid endpoint
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Invalid endpoint']);
            break;
    }
} else {
    // Only POST requests are allowed
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method not allowed']);
}
