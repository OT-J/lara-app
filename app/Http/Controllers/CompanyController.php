<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::with('employees')->get();
        return response( $companies,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' =>'required|string',
            'email' => 'required|string|unique:users,email',
            'description'=>'string'
        ]);

        Company::create([
            'name'=>$fields['name'],
            'mail'=>$fields['email'],
            'description'=>$fields['description']
        ]);
        return response()->json(['message'=>"New company added successfully",'data'=>$this->index()],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $companies = Company::with('employees')->where('id',$company->id)->get();
        return response( $companies,200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $fields = $request->validate([
            'name' =>'required|string',
            'mail' => 'required|string|unique:users,email',
            'description'=>'string'
        ]);
        $company->update($fields);
        return response()->json(['message'=>"Company updated successfully",'data'=>$this->index()],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        // Code to delete a company from the database if it has no employees
        if ($company->employees()->count() === 0) {
            $company->delete();
            
            return response()->json(['message'=>"Company deleted successfully",'data'=>$this->index()],201);
        } else {
            return response()->json(['message'=>"Company cannot be deleted as it has employees.",'data'=>$company],200);

        }
    }
}
