<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\{Sach, TacGia, TheLoai, NhaXuatBan, NguoiDung, DonHang, GioHang, MaGiamGia};

class ApiTestController extends Controller
{
    public function index()
    {
        // Lấy tất cả routes
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        })->filter(function ($route) {
            // Lọc bỏ các route không cần thiết
            return !str_contains($route['uri'], '_ignition') 
                && !str_contains($route['uri'], 'sanctum');
        })->values();

        // Thống kê database
        $stats = [
            'sach' => Sach::count(),
            'tac_gia' => TacGia::count(),
            'the_loai' => TheLoai::count(),
            'nha_xuat_ban' => NhaXuatBan::count(),
            'nguoi_dung' => NguoiDung::count(),
            'don_hang' => DonHang::count(),
            'gio_hang' => GioHang::count(),
            'ma_giam_gia' => MaGiamGia::count(),
        ];

        // Sample data cho test
        $sampleBook = Sach::first();
        $sampleCategory = TheLoai::first();
        $sampleAuthor = TacGia::first();

        return view('admin.api-test', compact('routes', 'stats', 'sampleBook', 'sampleCategory', 'sampleAuthor'));
    }

    public function testEndpoint(Request $request)
    {
        $method = strtoupper($request->input('method', 'GET'));
        $url = $request->input('url');
        $data = $request->input('data', []);

        try {
            $startTime = microtime(true);
            
            // Tạo request nội bộ
            $internalRequest = Request::create($url, $method, is_array($data) ? $data : []);
            $internalRequest->headers->set('Accept', 'application/json');
            
            // Copy session và cookies
            $internalRequest->setLaravelSession($request->session());
            
            $response = app()->handle($internalRequest);
            
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);

            $content = $response->getContent();
            $isJson = $this->isJson($content);

            return response()->json([
                'success' => true,
                'status_code' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'is_json' => $isJson,
                'response' => $isJson ? json_decode($content, true) : substr($content, 0, 5000),
                'headers' => $response->headers->all(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
