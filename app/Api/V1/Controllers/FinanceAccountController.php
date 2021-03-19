<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\FinanceAccountRequest;
use App\Models\FinanceAccount;



class FinanceAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard()->user();
        $finance_account = FinanceAccount::where('user_id','=',$user->id)->get();

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'get all your finance account',
                'data' => $finance_account
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinanceAccountRequest $request)
    {
    
        $request_body = $request->only(['name']);
        $finance_account = new FinanceAccount($request_body);
        $finance_account->save();

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'store your finance account',
                'data' => [
                    'id' => $finance_account->id
                ]
                ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $finance_account = FinanceAccount::where('id','=',$id)->first();
        if(!$finance_account){
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'your finance account not found',
                ], 404);
        }

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'show your finance account',
                'data' => $finance_account
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FinanceAccountRequest $request, $id)
    {
        $request_body = $request->only(['name']);

        $finance_account = FinanceAccount::where('id','=',$id)->first();
        if(!$finance_account){
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'your finance account not found',
                ], 404);
        }

        $finance_account->name = $request_body->name;

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'update your finance account',
                'data' => $finance_account
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $finance_account = FinanceAccount::where('id','=',$id)->first();

        if(!$finance_account){
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'your finance account not found',
                ], 404);
        }

        $finance_account->delete();
        return response()
            ->json([
                'status' => 'ok',
                'message' => 'delete your finance account',
                'data' => $finance_account
            ], 200);
    }
}
