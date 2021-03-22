<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionIncome;
use App\Models\TransactionExpense;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthly($year, $month)
    {
        $transaction_income = TransactionIncome::getByUserId()
            ->getByMonthAndYear($year, $month)->get();
        $transaction_expense = TransactionExpense::getByUserId()
            ->getByMonthAndYear($year, $month)->get();

        if(count($transaction_expense) == 0 && count($transaction_income) == 0){
            throw new NotFoundHttpException(trans('report.not-found'));
        }

        $data = $this->report($transaction_income, $transaction_expense, $year, $month);
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('report.get-all'),
                'data' => $data,
            ], 200);
    }

    public function daily($year, $month, $day){
        
        $transaction_income = TransactionIncome::getByUserId()
            ->getByDate($year, $month, $day)->get();
        $transaction_expense = TransactionExpense::getByUserId()
            ->getByDate($year, $month, $day)->get();

        if(count($transaction_expense) == 0 && count($transaction_income) == 0){
            throw new NotFoundHttpException(trans('report.not-found'));
        }

        $data = $this->report($transaction_income, $transaction_expense, $year, $month, $day);
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('report.get-all'),
                'data' => $data,
            ], 200);
    }

    private function report($transaction_income, $transaction_expense, $year, $month, $day = null){
        $user = Auth::Guard()->user();

        $income_amount = $transaction_income->sum('amount');
        $expense_amount = $transaction_expense->sum('amount');
        $income_count = $transaction_income->count();
        $expense_count = $transaction_expense->count();

        $transaction_income_category = $transaction_income->groupBy('category_name');
        $transaction_expense_category = $transaction_expense->groupBy('category_name');
        $transaction_income_category = $transaction_income_category->map(function ($item, $key) {
            return $item->sum('amount');
        });
        $transaction_expense_category = $transaction_expense_category->map(function ($item, $key) {
            return $item->sum('amount');
        });

        $income_amount_max = $transaction_income_category->max();
        $expense_amount_max = $transaction_expense_category->max();
        $income_category_name = array_search($income_amount_max, $transaction_income_category->all());
        $expense_category_name = array_search($expense_amount_max, $transaction_expense_category->all());
        $money = $income_amount - $expense_amount;
        $burn = $transaction_expense_category->sum();
        $earn = $transaction_income_category->sum();

        $report = ($day != null) ? [
            "type" => "daily",
            "year" => $year,
            "month" => $month,
            "day" => $day,
        ]: [
            "type" => "monthly",
            "year" => $year,
            "month" => $month,
        ];

        return [
            "name" => $user->name,
            "email" => $user->email,
            "money" => $money,
            "earn" => $earn,
            "burn" => $burn,
            "report"=> $report,
            "income" => [
                "amount" =>  $income_amount,
                "total" => $income_count,
                "top_money_earn" => [
                    "category" => $income_category_name,
                    "amount" => $income_amount_max
                ],
                "category_details" => $transaction_income_category,
                "transaction_details" => $transaction_income,
            ],
            "expense" => [
                "amount" =>  $expense_amount,
                "total" => $expense_count,
                "top_money_burn" => [
                    "category" => $expense_category_name,
                    "amount" => $expense_amount_max
                ],
                "category_details" => $transaction_expense_category,
                "transaction_details" => $transaction_expense,
            ]
        ];
    }


}
