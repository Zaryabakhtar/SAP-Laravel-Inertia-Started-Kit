<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Mail\OtpEmail;
use App\Models\Answers;
use Illuminate\Http\Request;
use App\Events\ZipcodeUpdated;
use App\Events\ChartDataUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Questionnaire/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // Get the Questionnaire Answers from the request
        $answers = $request->input('answers');
        $userData = $request->input('userData');
        // Save the answers to session
        session()->put('answers', $answers);
        session()->put('userData', $userData);
        // Redirect to the next page

        // Generate a random OTP (e.g., 6-digit code)
        $otp = rand(100000, 999999);

        // Store the OTP in the session
        Session::put('otp', $otp);

        $userEmail = $request->userData['email'];
        
        // Send the OTP to the user via email
        $sendMail = Mail::to($userEmail)->send(new OtpEmail($otp));
        
        
        return redirect()->route('verify-otp')->with('success', 'OTP sent successfully');      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get the answers from the database where id = $id
        $answers = Answers::find($id);

        // Check if the answers exist
        if (!$answers) {
            return redirect()->route('user.index')->with('error', 'Answers not found');
        }

        // Update the answers with the new values
        $answers->question_1 = $request->input('question_1');
        $answers->question_2 = $request->input('question_2');
        $answers->question_3 = $request->input('question_3');

        // Save the updated answers
        $answers->save();

        $getChartsData = UserController::getChartData($request , true);
        event(new ChartDataUpdated($getChartsData));
        event(new ZipcodeUpdated($answers->question_1 . $answers->question_2 . $answers->question_3));

        return redirect()->route('user.index')->with('success', 'Answers updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete the answers from the database where id = $id
        Answers::where('id', $id)->delete();

        $request = new Request();
        $getChartsData = UserController::getChartData($request , true);
        event(new ChartDataUpdated($getChartsData));

        return redirect()->route('user.index')->with('success', 'Answers deleted successfully');
    }
}
