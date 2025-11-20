<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ApplicationController extends Controller
{
    /**
     * Отображает список всех заявок
     */
    public function index(): View
    {
        $applications = Application::with([
            'faculty.translations',
            'specialty.translations', 
            'course.translations',
            'courseLanguage.translations',
            'orgType.translations',
            'country.translations',
            'city.translations',
            'degree.translations',
            'user'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(25);

        return view('admin.application.index', compact('applications'));
    }

    /**
     * Отображает детальную информацию о заявке
     */
    public function show($locale, $id): View
    {
        \Log::info('ApplicationController@show called with ID: ' . $id);
        \Log::info('Request URL: ' . request()->url());
        \Log::info('Request method: ' . request()->method());
        
        try {
            \Log::info('Searching for application with ID: ' . $id);
            $application = Application::findOrFail($id);
            \Log::info('Application found: ' . $application->id);
            
            \Log::info('Loading application relations...');
            $application->load([
                'faculty.translations',
                'specialty.translations',
                'course.translations',
                'courseLanguage.translations',
                'orgType.translations',
                'country.translations',
                'city.translations',
                'degree.translations',
                'user'
            ]);
            
            \Log::info('Application loaded with relations');
            \Log::info('About to return view: admin.application.show');
            
            return view('admin.application.show', compact('application'));
        } catch (\Exception $e) {
            \Log::error('Error in ApplicationController@show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Тестовый метод для отладки
     */
    public function test($locale, $id)
    {
        try {
            $application = Application::findOrFail($id);
            return response()->json([
                'success' => true,
                'application' => $application->toArray(),
                'message' => 'Application found successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Простой тест view
     */
    public function testView()
    {
        return view('admin.application.show', [
            'application' => Application::first()
        ]);
    }

    /**
     * Обновляет статус заявки
     */
    public function updateStatus(Request $request, $locale, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $application = Application::findOrFail($id);
        $application->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Статус заявки успешно обновлен');
    }

    /**
     * Удаляет заявку
     */
    public function destroy($locale, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);
        $application->delete();
        
        return redirect()->route('admin.applications.index', ['locale' => app()->getLocale()])
            ->with('success', 'Заявка успешно удалена');
    }
}
