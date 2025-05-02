<?php
// Get the callback data
$callbackData = file_get_contents('php://input');
$data = json_decode($callbackData);

// Log the callback data
$logFile = 'callback_log.txt';
$logData = date('Y-m-d H:i:s') . " - " . $callbackData . "\n";
file_put_contents($logFile, $logData, FILE_APPEND);

// Process the callback
if(isset($data->Body->stkCallback->ResultCode) && $data->Body->stkCallback->ResultCode == 0) {
    // Payment was successful
    $merchantRequestID = $data->Body->stkCallback->MerchantRequestID;
    $checkoutRequestID = $data->Body->stkCallback->CheckoutRequestID;
    $resultDesc = $data->Body->stkCallback->ResultDesc;
    $amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
    $mpesaReceiptNumber = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
    $transactionDate = $data->Body->stkCallback->CallbackMetadata->Item[3]->Value;
    $phoneNumber = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;
    
    // Log successful transaction
    $successLog = date('Y-m-d H:i:s') . " - SUCCESS - Amount: $amount, Receipt: $mpesaReceiptNumber, Phone: $phoneNumber\n";
    file_put_contents('successful_transactions.txt', $successLog, FILE_APPEND);
    
    // You can add your database operations here to save the transaction details
    
    // Send response to M-Pesa
    header('Content-Type: application/json');
    echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Success']);
} else {
    // Payment failed
    $resultDesc = $data->Body->stkCallback->ResultDesc;
    
    // Log failed transaction
    $failLog = date('Y-m-d H:i:s') . " - FAILED - Reason: $resultDesc\n";
    file_put_contents('failed_transactions.txt', $failLog, FILE_APPEND);
    
    // Send response to M-Pesa
    header('Content-Type: application/json');
    echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Failed']);
}
?>