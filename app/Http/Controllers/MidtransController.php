<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$clientKey = config('services.midtrans.clientKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        $transactions = Transactions::with('ticket')->get();
        return view('admin.transaction.index_transaction', compact('transactions'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);
        $total = $ticket->price * $request->quantity;

        // Generate unique order ID
        $orderId = 'TIX-' . time();

        // Create transaction record
        $transaction = Transactions::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'UNPAID',
            'order_id' => $orderId,
            'total_price' => $total,
            'ticket_id' => $ticket->id,
            'quantity' => $request->quantity,
        ]);

        // Prepare Midtrans parameters
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
            'item_details' => [
                [
                    'id' => $ticket->id,
                    'price' => (int) $ticket->price,
                    'quantity' => $request->quantity,
                    'name' => $ticket->name_ticket,
                ]
            ],
        ];

        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Update transaction with snap token
            $transaction->snap_token = $snapToken;
            $transaction->save();

            // Return response with token
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'redirect_url' => route('payment.finish')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function finish(Request $request)
    {
        return view('payment.finish');
    }

    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $transactionStatus = $notification->transaction_status;
            $transactionId = $notification->transaction_id;
            $fraudStatus = $notification->fraud_status;
            $transactionTime = $notification->transaction_time;
            $paymentType = $notification->payment_type;

            $transaction = Transactions::where('order_id', $orderId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Update transaction details
            $transaction->transaction_id = $transactionId;
            $transaction->payment_type = $paymentType;
            $transaction->transaction_time = $transactionTime;
            $transaction->transaction_status = $transactionStatus;

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->status = 'CHALLENGE';
                } else if ($fraudStatus == 'accept') {
                    $transaction->status = 'SUCCESS';
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->status = 'SUCCESS';
            } else if ($transactionStatus == 'pending') {
                $transaction->status = 'PENDING';
            } else if ($transactionStatus == 'deny') {
                $transaction->status = 'FAILED';
            } else if ($transactionStatus == 'expire') {
                $transaction->status = 'EXPIRED';
            } else if ($transactionStatus == 'cancel') {
                $transaction->status = 'CANCELLED';
            }

            $transaction->save();

            return response()->json(['message' => 'Notification processed successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing notification: ' . $e->getMessage()], 500);
        }
    }

    public function status($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return response()->json($status);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error checking status: ' . $e->getMessage()], 500);
        }
    }
}
