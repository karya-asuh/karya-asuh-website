<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PantiController extends Controller
{
    public function showAll(){
        $pantis = DB::select("
            SELECT users.*, panti_details.location 
            FROM users
            LEFT JOIN panti_details ON users.user_id = panti_details.panti_id
            WHERE users.role = 'panti'
        ");

        return response()->json($pantis);
    }

    public function showSearch(Request $request)
    {
        $query = $request->input('query'); // Get the search query

        $pantis = User::join('panti_details', 'users.user_id', '=', 'panti_details.panti_id')
        ->where('users.role', 'panti') // Filter by role
        ->select('users.*', 'panti_details.location');// Start with the 'panti' role

        if ($query) {
            $pantis = $pantis->where('name', 'like', '%' . $query . '%'); // Search by name
        }

        $pantis = $pantis->get(); // Fetch the filtered pantis

        return view('page.panti', ['pantis' => $pantis]);
    }

    public function withdrawFund(Request $request){
        $pantiId = Auth::id();
        $payoutFund = $request->input('payout_fund');
        $detail = $request->input('detail', null); // Default to null if no detail is provided

        Withdraw::create([
            'panti_id' => $pantiId,
            'payout_fund' => $payoutFund,
            'detail' => $detail,
        ]);

        return redirect('/profile')->with('success', 'Withdraw successfully!');
    }

    public function withdrawAll(){
        $withdraws = DB::table('withdraws')
        ->join('users', 'withdraws.panti_id', '=', 'users.user_id')
        ->select('withdraws.*', 'users.name') // Add the columns you want to retrieve
        ->get();

        return response()->json($withdraws);
    }

    public function acceptWithdraw(Request $request, $id){
        DB::statement("
            UPDATE withdraws 
            SET status = 'Withdrawn',
            admin_id = :admin_id
            WHERE withdraw_id = :id
        ", ['id'=>$id,
            'admin_id'=>Auth::id()]);

        return redirect('/')->with('success', 'Withdrawn successfully!');
    }
}