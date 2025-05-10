<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;

class ElectionController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();

        return view('welcome', ['candidates' => $candidates]);
    }

    public function store(Request $request)
    {
        try {
            // Validate inputs
            $request->validate([
                'governor_id'      => 'required|exists:candidates,id',
                'vice_governor_id' => 'required|exists:candidates,id',
            ], [
                'governor_id.required'      => 'Please select a Governor.',
                'governor_id.exists'        => 'That Governor is not valid.',
                'vice_governor_id.required' => 'Please select a Vice-Governor.',
                'vice_governor_id.exists'   => 'That Vice-Governor is not valid.',
            ]);

            // Check if the user has already voted
            $cookie = $request->cookie('voted_token');
            if ($cookie && Vote::where('device_cookie', $cookie)->exists()) {
                return back()->with('error', 'You have already voted.');
            }

            // Generate token if no cookie exists
            $token = $cookie ?? Str::uuid();

            // Store the vote in the database
            Vote::create([
                'governor_id'      => $request->governor_id,
                'vice_governor_id' => $request->vice_governor_id,
                'device_cookie'    => $token,
            ]);

            // Store the selected candidates in a cookie (JSON encoded)
            $selected_candidates = json_encode([
                'governor' => $request->governor_id,
                'vice_governor' => $request->vice_governor_id,
            ]);

            // Redirect with success message and set cookies
            return redirect()->back()
                ->withCookie(Cookie::make('voted_token', $token, 60 * 24 * 365))
                ->withCookie(Cookie::make('selected_candidates', $selected_candidates, 60 * 24 * 365))
                ->with('success', 'Thank you for voting!');
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return back()
                ->withErrors($e->errors()) // Pass validation errors
                ->withInput()              // Retain input
                ->with('error', 'Please select one Governor and one Vice-Governor.');
        }
    }
}
