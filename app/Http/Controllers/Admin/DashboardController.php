<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Order;
use App\Models\Application\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    function index() : View{
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->sum('total_amount');
        $thisWeekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount'); 
        $thisMonthOrders = Order::whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)->sum('total_amount');
        $thisYearOrders = Order::whereYear('created_at', Carbon::now()->year)->sum('total_amount');
        $totalOrders = Order::count();
        $pendingCourses = Course::where('is_approved', 'pending')->count();
        $rejectedCourses = Course::where('is_approved', 'rejected')->count();
        $totalCourses = Course::where('is_approved', 'approved')->count();

        // Статистика заявок
        $pendingApplications = Application::where('status', 'pending')->count();
        $approvedApplications = Application::where('status', 'approved')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();
        $totalApplications = Application::count();

        $recentCourses = Course::orderBy('created_at', 'desc')->take(5)->get();
        $recentBlogs = Blog::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
        $recentApplications = Application::orderBy('created_at', 'desc')->take(5)->get();

        $monthlyOrderSums = [];
        $monthlyOrderCounts = [];

        for($month = 1; $month <= 12; $month++) {
            $monthlyOrderSums[] = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

            $monthlyOrderCounts[] = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        }


        return view('admin.dashboard', compact(
            'todaysOrder', 'thisWeekOrders', 'thisMonthOrders', 'thisYearOrders', 'totalOrders',
            'pendingCourses', 'rejectedCourses', 'totalCourses', 
            'pendingApplications', 'approvedApplications', 'rejectedApplications', 'totalApplications',
            'monthlyOrderSums', 'monthlyOrderCounts',
            'recentCourses', 'recentBlogs', 'recentOrders', 'recentApplications'
        ));
    }
}
