<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepresentiveReportController extends Controller
{
    public function index(Request $request)
    {
        $representiveId = $request->input('representive_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $status = $request->input('status', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
    
        $users = User::where('is_representive', 1)->get();
        $filteredUsers = $users;
    
        if ($representiveId) {
            $filteredUsers = $filteredUsers->where('id', $representiveId);
        }
    
        $reports = [];
        $totalCommissionAll = 0;
    
        foreach ($filteredUsers as $user) {
            $contractsQuery = Contract::with('client')->where('user_id', $user->id);
    
            if ($status !== 'all') {
                $contractsQuery->where('status', $status);
            } else {
                $contractsQuery->whereIn('status', ['active', 'completed']);
            }
    
            if ($year && $month) {
                $contractsQuery->whereYear('created_at', $year)
                               ->whereMonth('created_at', $month);
            }
    
            if ($dateFrom) {
                $contractsQuery->where('start_date', '>=', $dateFrom);
            }
    
            if ($dateTo) {
                $contractsQuery->where('start_date', '<=', $dateTo);
            }
    
            $contracts = $contractsQuery->get();
    
            $clients = $contracts->pluck('client')->unique('id');
            $clientIds = $clients->pluck('id');
    
            $clientsCount = $clients->count();
            $contractsCount = $contracts->count();
            $totalContractValue = $contracts->sum('total_amount');
            $totalCommission = $totalContractValue * ($user->commission / 100);
            $totalCommissionAll += $totalCommission;
    
            $contactsCount = Contact::whereIn('client_id', $clientIds)->count();
    
            $clientDetails = [];
    
            foreach ($clients as $client) {
                $clientContracts = $contracts->where('client_id', $client->id);
                $clientContractValue = $clientContracts->sum('total_amount');
                $clientCommission = $clientContractValue * ($user->commission / 100);
                $clientContactsCount = Contact::where('client_id', $client->id)->count();
    
                $clientDetails[] = [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company_name' => $client->company_name,
                    'contract_value' => $clientContractValue,
                    'commission' => $clientCommission,
                    'contracts_count' => $clientContracts->count(),
                    'contacts_count' => $clientContactsCount
                ];
            }
    
            $reports[] = [
                'user' => $user,
                'clients_count' => $clientsCount,
                'contacts_count' => $contactsCount,
                'contracts_count' => $contractsCount,
                'total_contract_value' => $totalContractValue,
                'total_commission' => $totalCommission,
                'client_details' => $clientDetails
            ];
        }
    
        return view('reports.representives.index', compact(
            'reports', 'users', 'representiveId', 'year', 'month', 'status', 'dateFrom', 'dateTo', 'totalCommissionAll'
        ));
    }
    
}
