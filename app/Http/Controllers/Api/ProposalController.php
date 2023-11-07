<?php


namespace App\Http\Controllers\Api;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve a list of proposals
        $proposals = Proposal::all();
        return response()->json($proposals);
    }

    public function show($id)
    {
        // Retrieve a specific proposal by its ID
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        return response()->json($proposal);
    }

    public function store(Request $request)
    {
        // Create a new proposal
        $data = $request->validate([
            'img' => 'required',
            'budget' => 'required|numeric',
            'comment' => 'required',
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
        ],[
            'img.required' => 'برجاء ارفاق صورة',
            'budget.required' => 'برجاء كتابه الميزانية',
            'comment.required'=> 'هذا الحقل مطلوب',
            'user_id' => 'required|integer',
            'job_id' => 'required|integer',
        ]);

        $proposal = Proposal::create($data);

        return response()->json($proposal, 201);
    }

    public function update(Request $request, $id)
    {
        // Update an existing proposal
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        $data = $request->validate([
            'img' => 'required',
            'budget' => 'required|numeric',
            'comment' => 'required',
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
        ]);

        $proposal->update($data);

        return response()->json($proposal);
    }

    public function destroy($id)
    {
        // Delete a proposal
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted']);
    }
}
