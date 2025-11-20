<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseScheduleController extends Controller
{
    /**
     * Display course schedule page
     */
    public function index(Request $request)
    {
        $categoryId = $request->get("category");

        // Получаем все категории для фильтра
        $categories = CourseCategory::where("status", 1)
            ->orderBy("name")
            ->get();

        // Получаем активные сессии курсов
        $query = CourseSession::with([
            "course.translations",
            "course.category",
            "translations",
        ])
            ->where("is_active", 1)
            ->whereHas("course", function ($q) {
                $q->where("status", "active")->where("is_approved", 1);
            });

        // Фильтрация по категории
        if ($categoryId) {
            $query->whereHas("course", function ($q) use ($categoryId) {
                $q->where("category_id", $categoryId);
            });
        }

        // Сортировка: сначала по порядку, затем по дате начала
        $sessions = $query
            ->orderBy("order", "asc")
            ->orderBy("start_date", "asc")
            ->get();

        // Группируем по датам (месяцам)
        $groupedSessions = $sessions->groupBy(function ($session) {
            return $session->start_date->format("Y-m");
        });

        return view(
            "frontend.pages.course-schedule",
            compact("groupedSessions", "categories", "categoryId"),
        );
    }
}
