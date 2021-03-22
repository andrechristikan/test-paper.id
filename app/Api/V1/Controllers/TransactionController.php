<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionIncome;
use App\Models\TransactionExpense;
use App\Models\FinanceAccount;
use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Api\V1\Requests\TransactionStoreRequest;
use App\Api\V1\Requests\TransactionUpdateRequest;
use Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = ( $request->query('page') && is_numeric($request->query('page')) ) ? (int) $request->query('page') : 1;
        $per_page = ( $request->query('per_page') && is_numeric($request->query('per_page')) ) ? (int) $request->query('per_page') : 10;
        $off_set= ($page - 1) * $per_page;

        $search = $request->query('search');
        $sort = $request->query('sort');
        $filters = $request->query('filters');
        $deleted = $request->query('deleted');

        if($sort && str_contains($sort, '@')){
            $sort = explode('@', $sort);

            if($sort[1] != 'asc' && $sort[1] != 'desc'){
                $sort[1] = 'asc';
            }
        }

        $transaction_income = TransactionIncome::getByUserId();
        $transaction_expense = TransactionExpense::getByUserId();
        
        if($deleted == 'with_trashed'){
            $transaction_income->withTrashed();
            $transaction_expense->withTrashed();
        }else if($deleted == 'only_trashed'){
            $transaction_income->onlyTrashed();
            $transaction_expense->onlyTrashed();
        }

        if($search){
            $transaction_income->search($search);
            $transaction_expense->search($search);
        }

        if($filters){
            $transaction_filter = $this->filter($transaction_income, $transaction_expense, $filters);
            $transaction_income = $transaction_filter[0];
            $transaction_expense = $transaction_filter[1];
        }

        $transaction = $transaction_expense->unionAll($transaction_income);

        if(is_array($sort) && count($sort) > 1){
            ($sort[1] == 'desc') 
                ? 
                $transaction->orderByDesc($sort[0]) 
                :
                $transaction->orderBy($sort[0]);
        }

        $transaction = $transaction->offset($off_set)
            ->limit($per_page)->get();
            
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('transaction.get-all'),
                'data' => $transaction,
            ], 200);
    }

    private function filter($transaction_income, $transaction_expense, $filters){
        $filters = explode(';', $filters);
        foreach($filters as $key => $value){
            $filter = explode('@', $value);
            switch($filter[0]){
                case 'finance_account':
                    $transaction_income->filterFinanceAccount($filter[1]);
                    $transaction_expense->filterFinanceAccount($filter[1]);
                    break;
                case 'category_type':
                    $category = array_keys(array_filter($filters, function($var){
                        return str_contains($var, 'category@');
                    }));

                    if($category && count($category) > 0){
                        $category = explode('@', $filters[$category[0]]);
                        if($filter[1] == 'expense'){
                            $transaction_expense->filterExpenseCategory($category[1]);
                            $transaction_income->filterId();
                        }else if($filter[1] == 'income'){
                            $transaction_income->filterIncomeCategory($category[1]);
                            $transaction_expense->filterId();
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return [
            $transaction_income,
            $transaction_expense
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionStoreRequest $request)
    {
        $request_body = $request->only([
            'finance_account_id',
            'category_id',
            'category_type',
            'amount'
        ]);

        $finance_account = FinanceAccount::find($request_body['finance_account_id']);
        if(!$finance_account){
            throw new NotFoundHttpException(trans('transaction.finance-account-not-found'));
        }

        if($request_body['category_type'] == 'expense'){
            $category = ExpenseCategory::find($request_body['category_id']);
        }else{
            $category = IncomeCategory::find($request_body['category_id']);
        }

        if(!$category){
            throw new NotFoundHttpException(trans('transaction.category-not-found'));
        }

        if($request_body['category_type'] == 'expense'){
            $transaction = new TransactionExpense([
                "finance_account_id" => $request_body['finance_account_id'],
                "expense_category_id" => $request_body['category_id'],
                "amount" => $request_body['amount']
            ]);
        }else{
            $transaction = new TransactionIncome([
                "finance_account_id" => $request_body['finance_account_id'],
                "income_category_id" => $request_body['category_id'],
                "amount" => $request_body['amount']
            ]);
        }

        if(!$transaction->save()){
            throw new HttpException(trans('http.internal-server-error'));
        }

        return response()
            ->json([
                'status_code' => 201,
                'message' => trans('transaction.store'),
                'data' => [
                    "id" => $transaction->id
                ]
            ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category_type, $id)
    {
        if($category_type == 'expense'){
            $transaction = TransactionExpense::getByUserId()->find($id);
        }else{
            $transaction = TransactionIncome::getByUserId()->find($id);
        }

        if(!$transaction){
            throw new NotFoundHttpException(trans('http.not-found'));
        }
        
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('transaction.show'),
                'data' => $transaction
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionUpdateRequest $request, $id)
    {
        $request_body = $request->only([
            'category_id',
            'category_type',
            'amount'
        ]);

        if($request_body['category_type'] == 'expense'){
            $transaction = TransactionExpense::getByUserId()->find($id);
        }else{
            $transaction = TransactionIncome::getByUserId()->find($id);
        }

        if(!$transaction){
            throw new NotFoundHttpException(trans('http.not-found'));
        }

        if($request_body['category_type'] == 'expense'){
            $transaction = TransactionExpense::find($id);
            $transaction->expense_category_id =  $request_body['category_id'];
            $transaction->amount =  $request_body['amount'];
        }else{
            $transaction = TransactionIncome::find($id);
            $transaction->income_category_id =  $request_body['category_id'];
            $transaction->amount =  $request_body['amount'];
        }

        if(!$transaction->save()){
            throw new HttpException(trans('http.internal-server-error'));
        }

        
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('transaction.update'),
                'data' => [
                    "id" => $transaction->id
                ]
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_type, $id)
    {
        if($category_type == 'expense'){
            $transaction = TransactionExpense::getByUserId()->find($id);
        }else{
            $transaction = TransactionIncome::getByUserId()->find($id);
        }

        if(!$transaction){
            throw new NotFoundHttpException(trans('http.not-found'));
        }

        if($category_type == 'expense'){
            $transaction = TransactionExpense::find($id);
        }else{
            $transaction = TransactionIncome::find($id);
        }
        
        if(!$transaction->delete()){
            throw new HttpException(trans('http.internal-server-error'));
        }

        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('transaction.delete'),
            ], 200);
    }
}
