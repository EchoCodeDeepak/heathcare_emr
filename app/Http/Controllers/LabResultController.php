<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabResultController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of lab results.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Get medical records that have lab results
        $query = PatientMedicalRecord::with(['patient', 'doctor'])
            ->whereNotNull('lab_results')
            ->where('lab_results', '!=', '');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })
                    ->orWhere('lab_results', 'like', '%' . $search . '%');
            });
        }

        // Apply patient filter
        if ($request->has('patient_id') && !empty($request->patient_id)) {
            $query->where('patient_id', $request->patient_id);
        }

        $labResults = $query->orderBy('updated_at', 'desc')
            ->paginate($request->get('per_page', 10));

        // Get patients for filter dropdown
        $patients = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'patient');
        })->orderBy('name')->get();

        return view('lab-results.index', compact('labResults', 'patients'));
    }

    /**
     * Show the form for creating a new lab result.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        // Get patients for dropdown
        $patients = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'patient');
        })->orderBy('name')->get();

        return view('lab-results.create', compact('patients'));
    }

    /**
     * Store a newly created lab result.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'lab_results' => 'required|string',
        ]);

        // Check if patient already has a medical record
        $record = PatientMedicalRecord::where('patient_id', $request->patient_id)->first();

        if ($record) {
            // Update existing record
            $record->update([
                'lab_results' => $request->lab_results,
                'doctor_id' => Auth::id(), // Lab technician as doctor
            ]);
        } else {
            // Create new record
            PatientMedicalRecord::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => Auth::id(),
                'lab_results' => $request->lab_results,
                'visibility_level' => 'private',
            ]);
        }

        return redirect()->route('lab-results.index')
            ->with('success', 'Lab result added successfully.');
    }

    /**
     * Display the specified lab result.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $record = PatientMedicalRecord::with(['patient', 'doctor'])
            ->whereNotNull('lab_results')
            ->findOrFail($id);

        return view('lab-results.show', compact('record'));
    }

    /**
     * Show the form for editing the specified lab result.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $record = PatientMedicalRecord::with('patient')
            ->whereNotNull('lab_results')
            ->findOrFail($id);

        return view('lab-results.edit', compact('record'));
    }

    /**
     * Update the specified lab result.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'lab_results' => 'required|string',
        ]);

        $record = PatientMedicalRecord::whereNotNull('lab_results')
            ->findOrFail($id);

        $record->update([
            'lab_results' => $request->lab_results,
        ]);

        return redirect()->route('lab-results.index')
            ->with('success', 'Lab result updated successfully.');
    }

    /**
     * Remove the specified lab result.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $record = PatientMedicalRecord::whereNotNull('lab_results')
            ->findOrFail($id);

        // Clear lab results instead of deleting the whole record
        $record->update(['lab_results' => null]);

        return redirect()->route('lab-results.index')
            ->with('success', 'Lab result removed successfully.');
    }
}
