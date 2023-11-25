<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    function withdrawal(Request $request)
    {
        //send which atm id and how much money to pay
        $request->validate([
            'atm_id' => 'required|integer',
            'amount' => 'required|integer',
        ]);

        //get user 
        $userWallet = $request->user()->wallet()->first();
        //check user have wallet
        if (empty($userWallet)) {
            // createWallet($request->user()->id);
            $userWallet = \App\Models\Wallet::create([
                'user_id' => $request->user()->id,
                'balance' => 0,
            ]);
            return response(['message' => 'User wallet created your Balance is 0'], 403);
        }

        if ($userWallet->status == 0) {
            return response(['message' => 'Your wallet is blocked'], 403);
        }
        if ($userWallet->balance < $request->amount) {
            return response(['message' => 'Your wallet balance is not enough'], 403);
        }

        $atm = \App\Models\Atm::find($request->atm_id)->with('banknotesAtms')->with('banknotesAtms.banknote')->first();

        if (!$atm) {
            return response(['message' => 'Atm not found'], 404);
        }

        //get atm banknotes.banknote value and sort by value desc
        $banknotes = $atm->banknotesAtms->sortByDesc('banknote.value');

        //get atm banknotes sum
        $banknotesSum = $banknotes->sum('banknote.value');

        //check atm banknotes sum is enough
        if ($banknotesSum < $request->amount) {
            return response(['message' => 'Atm banknotes sum is not enough'], 403);
        }


        $amount = $request->amount;
        $imposibble = true;
        $givedBanknotes = [];
        foreach ($banknotes as $bankNote) {
            $count = floor($amount / $bankNote->banknote->value);
            if ($count > 0 && $bankNote->quantity >= $count) {
                $givedBanknotes[$bankNote->banknote->value . $bankNote->banknote->prefix] = $count;
                $amount %= $bankNote->banknote->value;
                $imposibble = false;
            }
        }
        if ($imposibble)
            return response(['message' => 'Impossible to give this amount of money we dont have enough banknotes'], 403);

        $transaction = \App\Models\Transaction::create([
            'atm_id' => $request->atm_id,
            'user_id' => $request->user()->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 1,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'banknotes' => json_encode($givedBanknotes),
        ]);

        //update atm banknotes quantity
        foreach ($givedBanknotes as $key => $value) {
            $banknoteAtm = $atm->banknotesAtms->where('banknote.value', $key)->first();
            $banknoteAtm->quantity -= $value;
            $banknoteAtm->save();
        }

        //update user wallet balance
        $userWallet->balance -= $request->amount;
        $userWallet->save();
        //add prefix to banknotes fromt


        return response(['message' => 'withdrawal successful', 'Atm Gived To you' => $givedBanknotes], 201);
    }

    //transaction history
    function getHistory()
    {
        $transactions = \App\Models\Transaction::where('user_id', auth()->user()->id)->get();

        return response(['transactions' => $transactions], 201);
    }
    //delete transaction
    function deleteHistory($transaction_id)
    {
        $transaction = \App\Models\Transaction::find($transaction_id);

        if (!$transaction) {
            return response(['message' => 'Transaction not found'], 404);
        }

        if (auth()->user()->role != 'admin') {
            return response(['message' => 'You can not delete this transaction'], 403);
        }

        $transaction->delete();

        return response(['message' => 'Transaction deleted', "transaction" => $transaction], 201);
    }
}
