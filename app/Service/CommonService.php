<?php


namespace App\Service;

use App\Model\History;
use Carbon\Carbon;
use SellySample\Selly;
class CommonService
{
    public static function  sellyClient()
    {
        $sellyService = new Selly();
        $sellyService->client();
    }
    public static function roleMember()
    {
        $isActive = false;
        $startDate = Carbon::create(2020, 12,1)->startOfMonth();
        $endDate = Carbon::create(2020, 12,1)->addMonths(2)->endOfMonth();;
        $range = [$startDate, $endDate];
        $user = \Auth::user();
        $totalRechargeMoney = History::from('histories as hi')
            ->select(\DB::raw('sum(amount) as sum'))
            ->where('user_id', \Auth::user()->id)
            ->where('action', 'BUY_KEY')
            ->whereBetween('created_at', $range)
            ->get();
        $totalMoney = ($totalRechargeMoney->first()->sum);
        $role = config('const.role_member.member_status.silver');
        if ($isActive) {
            if ($totalMoney >= 500) {
                $role = config('const.role_member.member_status.diamond');
            } elseif ($totalMoney >= 200 && $totalMoney < 500) {
                $role = config('const.role_member.member_status.platinum');
            } elseif ($totalMoney >= 100 && $totalMoney < 200) {
                $role = config('const.role_member.member_status.gold');
            }
        } else {
            $totalMoney = 0;
        }
        $role_status = array_search($role, config('const.role_member.member_status'), true );
        $discount = config('const.role_member.discount')[$role_status];
        $user->role_member = $role;
        $user->save();

        \Log::info('userId: ' . \Auth::user()->id . ' role: ' . $role . ' discount: ' . $discount);


        return [
            'role' => $role,
            'totalMoney' => $totalMoney,
            'discount' => $discount
        ];
    }

    /** Random string
     * @param int $length
     * @return string
     */
    public function randomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        for ($i = 0; $i < $length; $i++) {
            $randString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randString;
    }
}
