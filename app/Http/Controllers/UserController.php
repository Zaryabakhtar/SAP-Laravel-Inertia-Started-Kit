<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Answers;
use Illuminate\Http\Request;
use App\Events\ChartDataUpdated;
use App\Events\ChartsDataChanged;
use App\Events\ZipcodeUpdated;
use App\Models\Zipcodes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
{
    $answers = Answers::with('user')->get()->toArray();
    return Inertia::render('User/Index', compact('answers'));
}



    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otp = $request->input('otp');

        // Perform OTP verification logic here
        // Example: compare the provided OTP with the one stored in the session or database
        if ($otp == '123456') {
            // OTP verified successfully

            // Update the session or user record with the verified status
            Session::put('isVerified', true);

            $answers  = Session::get('answers');
            $userData = Session::get('userData');
            // Save user to database if exists update
            $user = User::where('email', $userData['email'])->first();
            if($user){
                $user->update($userData);
            }else{
                $user = User::create($userData);
            }
            
            // Save answers to database
            $question_1 = $answers[0];
            $question_2 = $answers[1];
            $question_3 = $answers[2];

            // Save answers to database if the user answers already exists update answers
            $answer = $user->answers()->first();
            if($answer){
                $answer->update([
                    'question_1' => $question_1,
                    'question_2' => $question_2,
                    'question_3' => $question_3,
                    'answer' => $question_1 . $question_2 . $question_3
                ]);
            }else{
                $user->answers()->create([
                    'user_id'   => $user->id,
                    'question_1' => $question_1,
                    'question_2' => $question_2,
                    'question_3' => $question_3,
                    'answer' => $question_1 . $question_2 . $question_3
                ]);
            }

            $getChartsData = $this->getChartData($request , true);
            event(new ChartDataUpdated($getChartsData));
            event(new ZipcodeUpdated($question_1 . $question_2 . $question_3));
            // Return a success response
            return redirect()->route('user.index')->with('success', 'OTP verified successfully');
        }

        // OTP verification failed
        // Throw a validation exception to display the error message
        throw ValidationException::withMessages([
            'otp' => ['Invalid OTP. Please try again.'],
        ]);
    }

    public function showZipCodes(Request $result){
        // Get all the zip codes from us table
        $zipCodes = DB::table('us')->where('state_abbr' , 'AL')->get();
        $answers = Answers::get()->pluck('answer')->toArray();
        $matched = Zipcodes::where('state_abbr' , 'AL')->whereIn('unique_code' , $answers)->get()->pluck('zipcode')->toArray();

        return Inertia::render('User/Zipcodes' , compact('zipCodes' , 'matched' , 'answers'));
    }

    public function getZipCodes(Request $request , $returnArray = false){
        // Get all the zip codes from us table
        $zipCodes = DB::table('us')->where('state_abbr' , 'AL')->get();
        $answers = Answers::get()->pluck('answer')->toArray();
        $matched = Zipcodes::where('state_abbr' , 'AL')->whereIn('unique_code' , $answers)->get()->pluck('zipcode')->toArray();

        if($returnArray){
            return compact('zipCodes' , 'matched' , 'answers');
        }else{
            return response()->json(compact('zipCodes' , 'matched' , 'answers'));
        }
        
    }

    public function showCharts(){
        return Inertia::render('User/Charts');
    }

    public static function getChartData(Request $request , $returnArray = false){
        $totalResponses = DB::table('answers')->count();
        $questions = [
            'question_1' => ['a', 'b', 'c', 'd'],
            'question_2' => ['1', '2', '3', '4', '5'],
            'question_3' => ['u', 'v', 'w', 'x', 'y', 'z']
        ];
        $result = [];

        foreach ($questions as $question => $options) {
            $questionResult = [];

            foreach ($options as $option) {
                $optionCount = DB::table('answers')->where($question, $option)->count();

                if ($totalResponses > 0) {
                    $percentage = ($optionCount / $totalResponses) * 100;
                } else {
                    $percentage = 0;
                }

                $questionResult[$option] = $percentage;
            }

            $result[$question] = $questionResult;
        }
        if($returnArray){
            return $result;
        }else{
            return response()->json($result);
        }
        
    }
}

