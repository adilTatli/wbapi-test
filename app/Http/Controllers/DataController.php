<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    private $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
    private $host = '89.108.115.241';
    private $port = '6969';

    public function fetchData()
    {
        $this->fetchSales();
        $this->fetchOrders();
        $this->fetchStocks();
        $this->fetchIncomes();

        return redirect()->back()->with('status', 'Data fetched successfully!');
    }

    private function fetchSales()
    {
        $response = Http::get("http://{$this->host}:{$this->port}/api/sales", [
            'dateFrom' => '2023-01-01',
            'dateTo' => '2023-12-31',
            'key' => $this->apiKey,
            'limit' => 500
        ]);

        $sales = $response->json();

        if (isset($sales['data'])) {
            foreach ($sales['data'] as $sale) {
                Sale::updateOrCreate(
                    ['g_number' => $sale['g_number']],
                    $sale
                );
            }
        }
    }

    private function fetchOrders()
    {
        $response = Http::get("http://{$this->host}:{$this->port}/api/orders", [
            'dateFrom' => '2023-01-01',
            'dateTo' => '2023-12-31',
            'key' => $this->apiKey,
            'limit' => 500
        ]);

        $orders = $response->json();

        if (isset($orders['data'])) {
            foreach ($orders['data'] as $order) {
                Order::updateOrCreate(
                    ['g_number' => $order['g_number']],
                    $order
                );
            }
        }
    }

    private function fetchStocks()
    {
        $response = Http::get("http://{$this->host}:{$this->port}/api/stocks", [
            'dateFrom' => now()->format('Y-m-d'),
            'key' => $this->apiKey,
            'limit' => 500
        ]);

        $stocks = $response->json();

        if (isset($stocks['data'])) {
            foreach ($stocks['data'] as $stock) {
                Stock::updateOrCreate(
                    ['date' => $stock['date']],
                    $stock
                );
            }
        }
    }

    private function fetchIncomes()
    {
        $response = Http::get("http://{$this->host}:{$this->port}/api/incomes", [
            'dateFrom' => '2023-01-01',
            'dateTo' => '2023-12-31',
            'key' => $this->apiKey,
            'limit' => 500
        ]);

        $incomes = $response->json();

        if (isset($incomes['data'])) {
            foreach ($incomes['data'] as $income) {
                Income::updateOrCreate(
                    ['income_id' => $income['income_id']],
                    $income
                );
            }
        }
    }
}
