<?php
namespace App\Http\Controllers;

use App\Models\Creation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class ProductController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function showAll()
    {
        // Fetch all products from the database
        $creations = Creation::get(); // This assumes you have a 'products' table in the database

        // dd($creations);
        // Pass the products to the view
        return response()->json($creations);
    }

    public function showSearch(Request $request){
        $query = $request->input('query'); // Get the search query

        $creations = Creation::query();

        // Filter the products based on the search query
        if ($query) {
            $creations = $creations->where('name', 'like', '%' . $query . '%') // Search by product name
                                ->orWhere('desc', 'like', '%' . $query . '%'); // Optionally, search by description
        }

        $creations = $creations->get(); // Fetch the filtered creations

        return view('page.catalog', ['creations' => $creations]);
    }

    public function showOwnProducts(){
        $creations = DB::select("
            SELECT *
            FROM creations
            WHERE panti_id = :id
        ",['id' => Auth::id()]);

        return response()->json($creations);
    }

    public function showSuccessCreation(){
        $userId = Auth::id();

        $creations = DB::select('
            SELECT DISTINCT creations.*
            FROM creations
            LEFT JOIN transactions ON creations.creation_id = transactions.creation_id
            WHERE transactions.status = "Success"
            AND transactions.donor_id = :userId
        ', ['userId' => $userId]);

        return response()->json($creations);
    }

    public function show($id)
    {

        $creation = DB::select("
            SELECT creations.*, 
            users.name AS panti_name, 
            users.user_image AS panti_image,
            panti_details.location AS panti_location
            FROM creations
            LEFT JOIN users ON creations.panti_id = users.user_id
            LEFT JOIN panti_details ON creations.panti_id = panti_details.panti_id
            WHERE creations.creation_id = :id
        ", ['id' => $id]);

        // dd($creation);

        // Explicitly pass the $product variable
        return view('page.product-detail', ['creation' => $creation]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'desc' => 'nullable|string',
            'min_price' => 'required|integer|min:0',
            'creation_file' => 'required|file|max:2048', // Adjust size as needed
        ]);

        // Generate UUID and set the file path
        $uuid = (string) Str::uuid();
        $fileName = $uuid . '.' . $request->file('creation_file')->getClientOriginalExtension(); // Keep original file extension

        // Move the uploaded file to the desired location
        $request->file('creation_file')->move(storage_path('product'), $fileName);

        DB::table('creations')->insert([
            'panti_id' => Auth::id(),
            'name' => $request->name,
            'desc' => $request->desc,
            'min_price' => $request->min_price,
            'creation_file' => $fileName,
        ]);

        return redirect()->route('home')->with('success', 'Karya berhasil ditambahkan!');
    }

    public function generateSnapToken(Request $request, $id)
    {
        // Validate the custom price
        $request->validate([
            'custom_price' => 'required|numeric|min:' . Creation::where('creation_id', $id)->value('min_price'),
        ]);

        // Find the creation using the correct creation_id
        $creation = Creation::where('creation_id', $id)->firstOrFail();
        $customPrice = $request->input('custom_price');

        $transactionId = 'TR-'.(string) Str::uuid(); 

        DB::table('transactions')->insert([
            'transaction_id' => $transactionId,
            'donor_id' => Auth::id(),
            'creation_id' => $id,
            'price' => $customPrice,
        ]);

        Log::info("transaction id: ".$transactionId);

        // Prepare the transaction data
        $transactionDetails = [
            'order_id' => $transactionId, // unique order ID
            'gross_amount' => $customPrice,  // custom price
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        // Create transaction array
        $transaction = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Get Snap Token from Midtrans API
            $snapToken = Snap::getSnapToken($transaction);

            // Return Snap Token as JSON response
            return response()->json([
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error generating Snap Token: ' . $e->getMessage()], 500);
        }
    }

    public function handleMidtransNotification(Request $request)
    {
        try {
            // Get notification from Midtrans
            $notif = new Notification();

            // Get the transaction status
            $transactionStatus = $notif->transaction_status;

            // Get the order_id and related transaction details
            $orderId = $notif->order_id;

            if ($transactionStatus == 'settlement') {
                // Payment is successfully settled
                Log::info("Payment settled, order_id: " . $orderId);
                DB::table('transactions')
                    ->where('transaction_id', $orderId)
                    ->update(['status' => 'Success']);
            } else {
                // Handle the case when the payment expires
                Log::info("Payment Failed, order_id: " . $orderId);
                DB::table('transactions')
                    ->where('transaction_id', $orderId)
                    ->update(['status' => 'Failed']);
                Log::info("Unhandled transaction status, order_id: " . $orderId);
            }

            // Respond with a 200 OK to acknowledge the receipt of the notification
            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            // Handle any errors and log them
            Log::error('Error processing Midtrans notification: ' . $e->getMessage());
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()], 500);
        }
    }
}