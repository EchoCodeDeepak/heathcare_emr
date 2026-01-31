<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicalRecord;
use App\Models\DataAccessPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Apply permission middleware to specific methods
        $this->middleware('permission:view-medical-records')->only(['index', 'show']);
        $this->middleware('permission:create-medical-records')->only(['create', 'store', 'storePatient']);
        $this->middleware('permission:edit-medical-records')->only(['edit', 'update']);
        $this->middleware('permission:delete-medical-records')->only(['destroy']);
        $this->middleware('permission:export-data')->only(['exportPatients']);
    }

    /**
     * Display a listing of medical records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user has permission to view medical records
        if (!$user->hasPermission('view-medical-records')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view medical records.');
        }

        // Query builder for records
        $query = PatientMedicalRecord::with(['patient', 'doctor']);

        // Role-based filtering with permission checks
        if ($user->isAdmin()) {
            // Admin sees all records if they have view-medical-records permission
            // No additional filtering needed
        } elseif ($user->isDoctor()) {
            // Doctor sees records they created or have access to
            $query->where(function ($q) use ($user) {
                $q->where('doctor_id', $user->id)
                    ->orWhereHas('accessPermissions', function ($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id)->where('can_view', true);
                    });
            });
        } elseif ($user->isNurse()) {
            // Nurse sees records based on permissions
            if ($user->hasPermission('view-all-medical-records')) {
                // Nurses with special permission can see all
            } else {
                // Regular nurses see non-private records or records they have access to
                $query->where(function ($q) use ($user) {
                    $q->where('visibility_level', '!=', 'private')
                        ->orWhereHas('accessPermissions', function ($subQuery) use ($user) {
                            $subQuery->where('user_id', $user->id)->where('can_view', true);
                        });
                });
            }
        } elseif ($user->isLabTechnician()) {
            // Lab techs see records based on permissions
            if ($user->hasPermission('view-all-medical-records')) {
                // Lab techs with special permission can see all
            } else {
                // Regular lab techs see records they need for lab work
                $query->where(function ($q) use ($user) {
                    $q->whereNotNull('lab_results')
                        ->orWhereHas('accessPermissions', function ($subQuery) use ($user) {
                            $subQuery->where('user_id', $user->id)->where('can_view', true);
                        });
                });
            }
        } elseif ($user->isPatient()) {
            // Patient sees only their own records
            $query->where('patient_id', $user->id);
        } else {
            // Other users (if any) - check specific permissions
            if (!$user->hasPermission('view-all-medical-records')) {
                // Default to no access
                $query->where('id', 0); // Will return empty results
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                    ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                        $doctorQuery->where('name', 'like', "%$search%");
                    })
                    ->orWhere('diagnosis', 'like', "%$search%")
                    ->orWhere('medical_history', 'like', "%$search%");
            });
        }

        // Filter by visibility
        if ($request->has('visibility') && $request->visibility != '') {
            $query->where('visibility_level', $request->visibility);
        }

        // Filter by doctor
        if ($request->has('doctor_id') && $request->doctor_id != '') {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by patient (for doctors and admins)
        if (
            $request->has('patient_id') && $request->patient_id != '' &&
            ($user->isAdmin() || $user->hasPermission('view-all-medical-records'))
        ) {
            $query->where('patient_id', $request->patient_id);
        }

        // Sort functionality
        $sort_by = $request->get('sort_by', 'created_at');
        $sort_order = $request->get('sort_order', 'desc');
        $query->orderBy($sort_by, $sort_order);

        // Paginate results
        $records = $query->paginate(10)->withQueryString();

        // Get doctors for filter dropdown (if user has permission)
        $doctors = [];
        if ($user->isAdmin() || $user->hasPermission('view-all-medical-records')) {
            $doctors = User::whereHas('role', function ($q) {
                $q->where('name', 'doctor');
            })->get();
        }

        // Get patients for filter dropdown (if user has permission)
        $patients = [];
        if ($user->isAdmin() || $user->hasPermission('view-all-medical-records')) {
            $patients = User::whereHas('role', function ($q) {
                $q->where('name', 'patient');
            })->get(['id', 'name', 'email']);
        }

        return view('medical-records.index', compact('records', 'doctors', 'patients'));
    }

    /**
     * Show the form for creating a new medical record.
     */
    public function create()
    {
        $user = Auth::user();

        // Allow doctors or users with the explicit permission to create records
        if (!($user->hasPermission('create-medical-records') || $user->isDoctor())) {
            return redirect()->route('medical-records.index')
                ->with('error', 'You do not have permission to create medical records.');
        }

        $patients = User::whereHas('role', function ($query) {
            $query->where('name', 'patient');
        })->get();

        return view('medical-records.create', compact('patients'));
    }

    /**
     * Store a newly created medical record.
     */
    // public function store(Request $request)
    // {
    //     $user = Auth::user();

    //     // Allow doctors or users with the explicit permission to create records
    //     if (!($user->hasPermission('create-medical-records') || $user->isDoctor())) {
    //         return redirect()->route('medical-records.index')
    //             ->with('error', 'You do not have permission to create medical records.');
    //     }

    //     try {
    //         $validated = $request->validate([
    //             'patient_id' => 'required|exists:users,id',
    //             'medical_history' => 'nullable|string',
    //             'diagnosis' => 'nullable|string',
    //             'prescription' => 'nullable|string',
    //             'lab_results' => 'nullable|string',
    //             'blood_pressure' => 'nullable|string',
    //             'temperature' => 'nullable|numeric',
    //             'pulse_rate' => 'nullable|integer',
    //             'weight' => 'nullable|numeric',
    //             'height' => 'nullable|numeric',
    //             'allergies' => 'nullable|string',
    //             'notes' => 'nullable|string',
    //             'visibility_level' => 'required|in:private,restricted,public',
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         Log::warning('MedicalRecordController@store validation failed', [
    //             'user_id' => $user->id ?? null,
    //             'request' => $request->all(),
    //             'errors' => $e->errors(),
    //         ]);

    //         throw $e;
    //     }

    //     $validated['doctor_id'] = $user->id;

    //     // Create the record
    //     $record = PatientMedicalRecord::create($validated);

    //     // Log the creation (optional)
    //     // activity()
    //     //     ->causedBy($user)
    //     //     ->performedOn($record)
    //     //     ->log('created medical record');

    //     return redirect()->route('medical-records.show', $record->id)
    //         ->with('success', 'Medical record created successfully.');
    // }
    public function store(Request $request)
    {
        $user = Auth::user();

        // Allow doctors or users with the explicit permission to create records
        if (!($user->hasPermission('create-medical-records') || $user->isDoctor())) {
            return redirect()->route('medical-records.index')
                ->with('error', 'You do not have permission to create medical records.');
        }

        // Convert empty strings to null
        $request->merge([
            'medical_history' => $request->medical_history ?: null,
            'diagnosis' => $request->diagnosis ?: null,
            'prescription' => $request->prescription ?: null,
            'lab_results' => $request->lab_results ?: null,
            'blood_pressure' => $request->blood_pressure ?: null,
            'temperature' => $request->temperature ?: null,
            'pulse_rate' => $request->pulse_rate ?: null,
            'weight' => $request->weight ?: null,
            'height' => $request->height ?: null,
            'allergies' => $request->allergies ?: null,
            'notes' => $request->notes ?: null,
        ]);

        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:users,id',
                'medical_history' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'prescription' => 'nullable|string',
                'lab_results' => 'nullable|string',
                'blood_pressure' => 'nullable|string',
                'temperature' => 'nullable|numeric',
                'pulse_rate' => 'nullable|integer',
                'weight' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'allergies' => 'nullable|string',
                'notes' => 'nullable|string',
                'visibility_level' => 'required|in:private,restricted,public',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('MedicalRecordController@store validation failed', [
                'user_id' => $user->id ?? null,
                'request' => $request->all(),
                'errors' => $e->errors(),
            ]);

            // If AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            throw $e;
        }

        $validated['doctor_id'] = $user->id;

        // Create the record
        $record = PatientMedicalRecord::create($validated);

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'record' => $record,
                'message' => 'Medical record created successfully.'
            ]);
        }

        return redirect()->route('medical-records.show', $record->id)
            ->with('success', 'Medical record created successfully.');
    }
    /**
     * Display the specified medical record.
     */
    public function show($id)
    {
        $record = PatientMedicalRecord::with(['patient', 'doctor', 'accessPermissions.user'])->findOrFail($id);
        $user = Auth::user();

        // Check access permissions
        $canAccess = $this->canAccessRecord($record, $user);

        if (!$canAccess) {
            return redirect()->route('medical-records.index')
                ->with('error', 'Access denied to medical record.');
        }

        return view('medical-records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified medical record.
     */
    public function edit($id)
    {
        $record = PatientMedicalRecord::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to edit medical records
        if (!$user->hasPermission('edit-medical-records')) {
            return redirect()->route('medical-records.show', $id)
                ->with('error', 'You do not have permission to edit medical records.');
        }

        // Admin can only view, not edit (unless they have special permission)
        if ($user->isAdmin() && !$user->hasPermission('edit-all-medical-records')) {
            return redirect()->route('medical-records.show', $id)
                ->with('info', 'Admins can only view records, not edit them.');
        }

        // Check if user can edit this specific record
        if (!$this->canEditRecord($record, $user)) {
            return redirect()->route('medical-records.show', $id)
                ->with('error', 'You do not have permission to edit this record.');
        }

        // For doctors and users with edit-all permission: show patient dropdown
        // For others: just show the patient info
        if ($user->isDoctor() || $user->hasPermission('edit-all-medical-records')) {
            $patients = User::whereHas('role', function ($query) {
                $query->where('name', 'patient');
            })->get();
        } else {
            $patients = collect([$record->patient]);
        }

        return view('medical-records.edit', compact('record', 'patients'));
    }

    /**
     * Update the specified medical record.
     */
    public function update(Request $request, $id)
    {
        $record = PatientMedicalRecord::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to edit medical records
        if (!$user->hasPermission('edit-medical-records')) {
            return redirect()->route('medical-records.show', $id)
                ->with('error', 'You do not have permission to edit medical records.');
        }

        // Admin cannot update records unless they have special permission
        if ($user->isAdmin() && !$user->hasPermission('edit-all-medical-records')) {
            return redirect()->route('medical-records.show', $id)
                ->with('error', 'Admins cannot update medical records.');
        }

        // Check if user can edit this specific record
        if (!$this->canEditRecord($record, $user)) {
            return redirect()->route('medical-records.show', $id)
                ->with('error', 'You do not have permission to update this record.');
        }

        $validated = $request->validate([
            'medical_history' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'lab_results' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'visibility_level' => 'required|in:private,restricted,public',
        ]);

        // Only doctors and users with edit-all permission can change patient
        if (($user->isDoctor() || $user->hasPermission('edit-all-medical-records')) &&
            $request->has('patient_id')
        ) {
            $validated['patient_id'] = $request->validate(['patient_id' => 'exists:users,id'])['patient_id'];
        }

        // Log changes before update
        $changes = [];
        foreach ($validated as $field => $value) {
            if ($record->$field != $value) {
                $changes[$field] = [
                    'from' => $record->$field,
                    'to' => $value
                ];
            }
        }

        $record->update($validated);

        // Log the update if there were changes
        // if (!empty($changes)) {
        //     activity()
        //         ->causedBy($user)
        //         ->performedOn($record)
        //         ->withProperties(['changes' => $changes])
        //         ->log('updated medical record');
        // }

        return redirect()->route('medical-records.show', $id)
            ->with('success', 'Medical record updated successfully.');
    }

    /**
     * Remove the specified medical record.
     */
    public function destroy($id)
    {
        $record = PatientMedicalRecord::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to delete medical records
        if (!$user->hasPermission('delete-medical-records')) {
            return redirect()->route('medical-records.index')
                ->with('error', 'You do not have permission to delete medical records.');
        }

        // Check if user can delete this specific record
        if (!$this->canDeleteRecord($record, $user)) {
            return redirect()->route('medical-records.index')
                ->with('error', 'You do not have permission to delete this record.');
        }

        // Only the doctor who created it (or admin with delete-all permission) can delete
        if ((!$user->isDoctor() || $record->doctor_id != $user->id) &&
            !($user->isAdmin() && $user->hasPermission('delete-all-medical-records'))
        ) {
            return redirect()->route('medical-records.index')
                ->with('error', 'You do not have permission to delete this record.');
        }

        // Log deletion
        // activity()
        //     ->causedBy($user)
        //     ->performedOn($record)
        //     ->withProperties(['record_data' => $record->toArray()])
        //     ->log('deleted medical record');

        // Delete associated permissions first
        DataAccessPermission::where('record_id', $id)->delete();

        // Delete the record
        $record->delete();

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record deleted successfully.');
    }

    /**
     * Store a new patient (AJAX).
     */
    public function storePatient(Request $request)
    {
        $user = Auth::user();

        if (!($user->hasPermission('create-medical-records') || $user->isDoctor())) {
            return response()->json(['error' => 'You do not have permission to create patients.'], 403);
        }

        // Convert empty strings to null and handle numeric fields
        $request->merge([
            'phone' => $request->phone ?: null,
            'date_of_birth' => $request->date_of_birth ?: null,
            'gender' => $request->gender ?: null,
            'blood_group' => $request->blood_group ?: null,
            'address' => $request->address ?: null,
            'medical_history' => $request->medical_history ?: null,
            'diagnosis' => $request->diagnosis ?: null,
            'prescription' => $request->prescription ?: null,
            'lab_results' => $request->lab_results ?: null,
            'blood_pressure' => $request->blood_pressure ?: null,
            'temperature' => $request->temperature ?: null,
            'pulse_rate' => $request->pulse_rate ?: null,
            'weight' => $request->weight ?: null,
            'height' => $request->height ?: null,
            'allergies' => $request->allergies ?: null,
            'notes' => $request->notes ?: null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'address' => 'nullable|string',

            // Medical record fields - make nullable
            'medical_history' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'lab_results' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'visibility_level' => 'required|in:private,restricted,public',
        ]);

        // Generate a random password
        $password = Str::random(8);

        // Get patient role
        $patientRole = \App\Models\Role::where('name', 'patient')->first();
        if (!$patientRole) {
            return response()->json(['error' => 'Patient role not found'], 500);
        }

        // Create the patient user
        $patient = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($password),
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'address' => $validated['address'],
            'role_id' => $patientRole->id,
        ]);

        // Create the initial medical record
        $medicalRecord = PatientMedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_id' => Auth::id(),
            'medical_history' => $validated['medical_history'],
            'diagnosis' => $validated['diagnosis'],
            'prescription' => $validated['prescription'],
            'lab_results' => $validated['lab_results'],
            'blood_pressure' => $validated['blood_pressure'],
            'temperature' => $validated['temperature'],
            'pulse_rate' => $validated['pulse_rate'],
            'weight' => $validated['weight'],
            'height' => $validated['height'],
            'allergies' => $validated['allergies'],
            'notes' => $validated['notes'],
            'visibility_level' => $validated['visibility_level'],
        ]);

        return response()->json([
            'success' => true,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->phone,
            ],
            'medical_record' => $medicalRecord,
            'message' => 'Patient and initial medical record created successfully'
        ]);
    }
    /**
     * Export patients data.
     */
    public function exportPatients($format)
    {
        $user = Auth::user();

        // Check if user has permission to export data
        if (!$user->hasPermission('export-data')) {
            return redirect()->back()->with('error', 'You do not have permission to export data.');
        }

        // Additional check: only doctors and admins can export
        if (!$user->isDoctor() && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Only doctors and administrators can export patient data.');
        }

        $patients = User::whereHas('role', function ($query) {
            $query->where('name', 'patient');
        })->get();

        $fileName = 'patients_' . date('Y_m_d_H_i_s');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('medical-records.exports.patients-pdf', compact('patients'));
            return $pdf->download($fileName . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PatientsExport($patients), $fileName . '.xlsx');
        } elseif ($format === 'csv') {
            return Excel::download(new PatientsExport($patients), $fileName . '.csv');
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

    /**
     * Update permissions for a medical record.
     */
    public function updatePermissions(Request $request, $id)
    {
        $record = PatientMedicalRecord::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to manage permissions
        if (!$user->hasPermission('manage-permissions')) {
            return response()->json(['error' => 'Unauthorized - No permission to manage permissions'], 403);
        }

        // Additional check: user must be admin, doctor who created the record, or the patient
        if (
            !$user->isAdmin() &&
            $record->doctor_id !== $user->id &&
            $record->patient_id !== $user->id
        ) {
            return response()->json(['error' => 'Unauthorized - Not authorized for this record'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'can_view' => 'nullable|boolean',
            'can_edit' => 'nullable|boolean',
        ]);

        // Check if the target user exists
        $targetUser = User::find($request->user_id);
        if (!$targetUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent users from giving themselves permissions they don't have
        if (!$user->isAdmin() && !$user->isDoctor() && $request->can_edit) {
            return response()->json([
                'error' => 'Only admin and doctors can grant edit permissions'
            ], 403);
        }

        $permission = DataAccessPermission::updateOrCreate(
            [
                'record_id' => $id,
                'user_id' => $request->user_id
            ],
            [
                'can_view' => $request->can_view ?? false,
                'can_edit' => $request->can_edit ?? false
            ]
        );

        // Log permission change
        // activity()
        //     ->causedBy($user)
        //     ->performedOn($record)
        //     ->withProperties([
        //         'target_user_id' => $request->user_id,
        //         'can_view' => $request->can_view ?? false,
        //         'can_edit' => $request->can_edit ?? false
        //     ])
        //     ->log('updated record permissions');

        return response()->json([
            'success' => true,
            'permission' => $permission,
            'target_user' => $targetUser->name,
            'message' => 'Permissions updated successfully.'
        ]);
    }

    /**
     * Check if user can access a record.
     */
    private function canAccessRecord($record, $user)
    {
        // Admin can access all records if they have view permission
        if ($user->isAdmin() && $user->hasPermission('view-medical-records')) {
            return true;
        }

        // Patient can access their own records if they have view permission
        if ($record->patient_id == $user->id && $user->hasPermission('view-medical-records')) {
            return true;
        }

        // Doctor can access records they created if they have view permission
        if ($record->doctor_id == $user->id && $user->hasPermission('view-medical-records')) {
            return true;
        }

        // Check explicit permissions
        if ($record->canUserView($user->id)) {
            return true;
        }

        // Check visibility level
        if ($record->visibility_level == 'public' && $user->hasPermission('view-medical-records')) {
            return true;
        }

        if (
            $record->visibility_level == 'restricted' &&
            ($user->isDoctor() || $user->isNurse() || $user->isLabTechnician()) &&
            $user->hasPermission('view-medical-records')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can edit a record.
     */
    private function canEditRecord($record, $user)
    {
        // User must have edit-medical-records permission
        if (!$user->hasPermission('edit-medical-records')) {
            return false;
        }

        // Admin with special permission can edit all
        if ($user->isAdmin() && $user->hasPermission('edit-all-medical-records')) {
            return true;
        }

        // Doctor can edit records they created
        if ($user->isDoctor() && $record->doctor_id == $user->id) {
            return true;
        }

        // Check explicit edit permissions
        if ($record->canUserEdit($user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete a record.
     */
    private function canDeleteRecord($record, $user)
    {
        // User must have delete-medical-records permission
        if (!$user->hasPermission('delete-medical-records')) {
            return false;
        }

        // Admin with special permission can delete all
        if ($user->isAdmin() && $user->hasPermission('delete-all-medical-records')) {
            return true;
        }

        // Doctor can delete records they created
        if ($user->isDoctor() && $record->doctor_id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Get users who can be granted permissions (AJAX).
     */
    public function getAvailableUsers(Request $request, $id)
    {
        $record = PatientMedicalRecord::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to manage permissions for this record
        if (
            !$user->hasPermission('manage-permissions') ||
            (!$user->isAdmin() && $record->doctor_id !== $user->id && $record->patient_id !== $user->id)
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get users who are not already the doctor or patient of this record
        $availableUsers = User::where('id', '!=', $record->doctor_id)
            ->where('id', '!=', $record->patient_id)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', ['doctor', 'nurse', 'lab-technician']);
            })
            ->get(['id', 'name', 'email', 'role_id']);

        return response()->json([
            'success' => true,
            'users' => $availableUsers
        ]);
    }
}
