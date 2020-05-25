<?php

namespace App\Http\Middleware;

use App\MeetingAttendee;
use Closure;

class MeetingSignIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $meeting_id = $request->input("meeting_id");
        $time = time() + 28800;
        $BeginTime = strtotime(MeetingAttendee::where("id", $meeting_id)->value("BeginTime"));
        if ($time >= $BeginTime && $time < ($BeginTime + 1800)) {
            return $next($request);
        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "当前不能签到"
            ]);
        }
    }
}
