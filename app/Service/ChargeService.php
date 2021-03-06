<?php

namespace App\Service;

use App\Model\History;
use App\User;
use App\Payment;
use Auth;
use DB;
use Exception;
use Log;

class ChargeService
{
    public function get_lex_holding_key(){
        $lex_holding_key = Payment::where('payment_type', 'Lex Holding')->get()->first()->client_secret;
        return $lex_holding_key;
    }

    public function chargeViaLexHolding($order, $url)
    {
        $webhookURL = "https://divinesofts.net/api/charge";
        $returnURL = "https://divinesofts.net/balance";
        $url = "https://lexholdingsgroup.com/create";
        $lex_holding_key = $this->get_lex_holding_key();
        try {
            $data = array(
                "secret" => $lex_holding_key,
                "email" => Auth::user()->email,
                "amount" => $order->input("amount"),
                "webhookURL" => $webhookURL,
                "returnURL" => $returnURL
            );
            $data_string = json_encode($data);

            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_POST => 1,
                // CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS => $data_string
            );
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            Log::info("chargeViaLexHolding_result:" . json_encode($result));
            curl_close($ch);
            if ($result === FALSE) {
                return redirect()->back();
            } else {
                $resArr = json_decode($result);
                return redirect()->to("https://lexholdingsgroup.com/checkout/" . $resArr->response);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }

    public function insertTransaction($order)
    {
        Log::info("order transaction: " . json_encode($order));
        $order = (object) $order;
        // dd($order);
        $transactionStatus = $order->status;
        $secret = $order->secret;
        $lex_holding_key = $this->get_lex_holding_key();
        DB::beginTransaction();
        try {
            if ($transactionStatus == "paid" && $secret == $lex_holding_key) {
                #add to user
                $user_recharge = User::where("email", $order->email)->get()->first();
                if(!$user_recharge ) {
                    Log::error("not found user");
                    return false;
                }
                $pre_recharge = $user_recharge->credit;
                $user_recharge->credit = $user_recharge->credit + $order->income;
                $user_recharge->save();

                #insert history
                $history = new History();
                $history->user_id = $user_recharge->id;
                $history->amount = $order->income;
                $history->nl_token = $order->id;
                $history->revenue = 0;
                $history->action = config("const.action.recharge_via_lexhodings");
                $history->content = "Charge via lexholdings." . $order->email . " charge " . $order->amount .  " with info " . $order->metadata .
                ".Balance from " . $pre_recharge . " to " . $user_recharge->credit;
                $history->need_to_verify = false;
                $history->save();
                Log::info("insert transaction user: " . json_encode($history));
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("insert transaction failed " . $e->getMessage());
            return false;
        }
        return true;
    }
}
