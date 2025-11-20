<?php

use App\Http\Controllers\Admin\AboutUsSectionController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\BecomeInstructorSectionController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandSectionController;
use App\Http\Controllers\Admin\CertificateBuilderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseLanguageController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\CourseSubCategoryController;
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DatabaseClearController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\FeaturedInstructorController;
use App\Http\Controllers\Admin\FooterColumnOneController;
use App\Http\Controllers\Admin\FooterColumnTwoController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\InstructorReqeustController;
use App\Http\Controllers\Admin\InstructorRequestController;
use App\Http\Controllers\Admin\LatestCourseSectionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\PayoutGatewayController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TopBarController;
use App\Http\Controllers\Admin\VideoSectionController;
use App\Http\Controllers\Admin\IssuedCertificateController;
use App\Http\Controllers\Admin\CourseApplicationAdminController;
use App\Http\Controllers\Admin\CourseSessionController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\InstructorsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WithdrawRequestController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ApplicationFacultyController;
use App\Http\Controllers\Admin\ApplicationSpecialtyController;
use App\Http\Controllers\Admin\ApplicationCountryController;
use App\Http\Controllers\Admin\ApplicationCityController;
use App\Http\Controllers\Admin\ApplicationDegreeController;
use App\Http\Controllers\Admin\ApplicationOrgTypeController;
use App\Http\Controllers\Admin\ApplicationCourseLanguageController;
use App\Http\Controllers\Admin\ApplicationCourseController;
use App\Http\Controllers\Admin\CourseContentV2Controller;
use App\Http\Controllers\Frontend\HeroController;
use App\Models\BecomeInstructorSection;
use App\Models\FeaturedInstructor;
use App\Models\Footer;
use App\Models\FooterColumnOne;
use App\Models\LatestCourseSection;
use App\Models\TopBar;
use Illuminate\Support\Facades\Route;

// Редирект с /admin на /admin/{locale}
Route::get("admin", function () {
    $defaultLocale = config("app.locale", "en");
    return redirect("/admin/{$defaultLocale}");
})->name("admin.redirect");

Route::group(
    [
        "middleware" => [
            "guest:admin",
            \App\Http\Middleware\AdminLocaleMiddleware::class,
        ],
        //"middleware" => "guest:admin",
        "prefix" => "admin/{locale}",
        "as" => "admin.",
        "where" => ["locale" => "en|ru|kk"],
    ],
    function () {
        Route::get("login", [
            AuthenticatedSessionController::class,
            "create",
        ])->name("login");

        Route::post("login", [
            AuthenticatedSessionController::class,
            "store",
        ])->name("login.store");

        Route::get("forgot-password", [
            PasswordResetLinkController::class,
            "create",
        ])->name("password.request");

        Route::post("forgot-password", [
            PasswordResetLinkController::class,
            "store",
        ])->name("password.email");

        Route::get("reset-password/{token}", [
            NewPasswordController::class,
            "create",
        ])->name("password.reset");

        Route::post("reset-password", [
            NewPasswordController::class,
            "store",
        ])->name("password.store");
    },
);

/** Application routes - placed BEFORE auth.admin middleware to avoid conflicts */
Route::group(
    [
        "prefix" => "admin/{locale}",
        "as" => "admin.",
        "where" => ["locale" => "en|ru|kk"],
    ],
    function () {
        Route::prefix("admin-training-applications")->group(function () {
            Route::get("/", [AdminApplicationController::class, "index"])->name(
                "applications.index",
            );
            Route::get("/test-view", [
                AdminApplicationController::class,
                "testView",
            ])->name("applications.test.view");
            Route::post("/status/{id}", [
                AdminApplicationController::class,
                "updateStatus",
            ])
                ->name("applications.status.update")
                ->whereNumber("id");
            Route::get("/view/{id}", [
                AdminApplicationController::class,
                "show",
            ])
                ->name("applications.show")
                ->where("id", "[0-9]+")
                ->whereNumber("id");
            Route::get("/test/{id}", [
                AdminApplicationController::class,
                "test",
            ])
                ->name("applications.test")
                ->whereNumber("id");
            Route::delete("/delete/{id}", [
                AdminApplicationController::class,
                "destroy",
            ])
                ->name("applications.destroy")
                ->whereNumber("id");
        });

        // ПРОСТОЙ ТЕСТОВЫЙ МАРШРУТ
        Route::get("/simple-test", function () {
            return "SIMPLE TEST WORKS!";
        })->name("simple.test");
    },
);

Route::group(
    [
        "middleware" => "auth.admin",
        "prefix" => "admin/{locale}",
        "as" => "admin.",
        "where" => ["locale" => "en|ru|kk"],
    ],
    function () {
        Route::get(
            "verify-email",
            EmailVerificationPromptController::class,
        )->name("verification.notice");

        Route::get("verify-email/{id}/{hash}", VerifyEmailController::class)
            ->middleware(["signed", "throttle:6,1"])
            ->name("verification.verify");

        Route::post("email/verification-notification", [
            EmailVerificationNotificationController::class,
            "store",
        ])
            ->middleware("throttle:6,1")
            ->name("verification.send");

        Route::get("confirm-password", [
            ConfirmablePasswordController::class,
            "show",
        ])->name("password.confirm");

        Route::post("confirm-password", [
            ConfirmablePasswordController::class,
            "store",
        ]);

        Route::put("password", [PasswordController::class, "update"])->name(
            "password.update",
        );

        Route::post("logout", [
            AuthenticatedSessionController::class,
            "destroy",
        ])->name("logout");

        Route::get("dashboard", [DashboardController::class, "index"])->name(
            "dashboard",
        );

        /** Profile Update Routes */

        Route::get("profile", [ProfileUpdateController::class, "index"])->name(
            "profile.index",
        );
        Route::post("profile", [
            ProfileUpdateController::class,
            "profileUpdate",
        ])->name("profile.update");
        Route::post("update-password", [
            ProfileUpdateController::class,
            "updatePassword",
        ])->name("password.update");

        /** Instructor Request Routes */
        Route::get("instructor-doc-download/{user}", [
            InstructorRequestController::class,
            "download",
        ])->name("instructor-doc-download");
        Route::resource(
            "instructor-requests",
            InstructorRequestController::class,
        );

        /** Course Languages Routes */
        Route::resource(
            "course-languages",
            CourseLanguageController::class,
        )->parameters([
            "course-languages" => "course_language",
        ]);

        /** Course Levels Routes */
        Route::resource("course-levels", CourseLevelController::class);

        /** Course Categories Routes */
        // Use default implicit binding name to avoid signature mismatch
        Route::resource(
            "course-categories",
            CourseCategoryController::class,
        )->where(["course_category" => "[1-9][0-9]*"]);
        Route::get("/{course_category}/sub-categories", [
            CourseSubCategoryController::class,
            "index",
        ])
            ->where(["course_category" => "[1-9][0-9]*"])
            ->name("course-sub-categories.index");
        Route::get("/{course_category}/sub-categories/create", [
            CourseSubCategoryController::class,
            "create",
        ])
            ->where(["course_category" => "[1-9][0-9]*"])
            ->name("course-sub-categories.create");
        Route::post("/{course_category}/sub-categories", [
            CourseSubCategoryController::class,
            "store",
        ])
            ->where(["course_category" => "[1-9][0-9]*"])
            ->name("course-sub-categories.store");
        Route::get(
            "/{course_category}/sub-categories/{course_sub_category}/edit",
            [CourseSubCategoryController::class, "edit"],
        )
            ->where([
                "course_category" => "[1-9][0-9]*",
                "course_sub_category" => "[1-9][0-9]*",
            ])
            ->name("course-sub-categories.edit");

        Route::put("/{course_category}/sub-categories/{course_sub_category}", [
            CourseSubCategoryController::class,
            "update",
        ])
            ->where([
                "course_category" => "[1-9][0-9]*",
                "course_sub_category" => "[1-9][0-9]*",
            ])
            ->name("course-sub-categories.update");
        Route::delete(
            "/{course_category}/sub-categories/{course_sub_category}",
            [CourseSubCategoryController::class, "destroy"],
        )
            ->where([
                "course_category" => "[1-9][0-9]*",
                "course_sub_category" => "[1-9][0-9]*",
            ])
            ->name("course-sub-categories.destroy");

        /** Crouse Module Routes */
        Route::get("courses", [CourseController::class, "index"])->name(
            "courses.index",
        );
        Route::put("courses/{course}/update-approval", [
            CourseController::class,
            "updateApproval",
        ])->name("courses.update-approval");

        Route::get("courses/create", [CourseController::class, "create"])->name(
            "courses.create",
        );
        Route::post("courses/create", [
            CourseController::class,
            "storeBasicInfo",
        ])->name("courses.sore-basic-info");

        Route::get("courses/{id}/edit", [
            CourseController::class,
            "edit",
        ])->name("courses.edit");
        Route::post("courses/update", [
            CourseController::class,
            "update",
        ])->name("courses.update");
        Route::delete("courses/{id}", [
            CourseController::class,
            "destroy",
        ])->name("courses.destroy");

        // Course Contents standalone
        Route::get("course-contents", [
            CourseContentController::class,
            "index",
        ])->name("course-contents.index");
        Route::get("course-contents/{course}/manage", [
            CourseContentController::class,
            "manage",
        ])
            ->name("course-contents.manage")
            ->whereNumber("course");
        // Alias for Course Contents v2
        Route::get("course-contents-v2", [
            CourseContentV2Controller::class,
            "index",
        ])->name("course-contents-v2.index");
        Route::get("course-contents-v2/{course}/manage", [
            CourseContentV2Controller::class,
            "manage",
        ])->name("course-contents-v2.manage");
        Route::get("course-contents-v2/{course}/create-chapter", [
            CourseContentV2Controller::class,
            "createChapterModal",
        ])->name("course-contents-v2.chapters.create");
        Route::post("course-contents-v2/{course}/create-chapter", [
            CourseContentV2Controller::class,
            "storeChapter",
        ])->name("course-contents-v2.chapters.store");
        Route::get("course-contents-v2/{chapter}/edit-chapter", [
            CourseContentV2Controller::class,
            "editChapterModal",
        ])->name("course-contents-v2.chapters.edit");
        Route::post("course-contents-v2/{chapter}/edit-chapter", [
            CourseContentV2Controller::class,
            "updateChapterModal",
        ])->name("course-contents-v2.chapters.update");
        Route::delete("course-contents-v2/{chapter}/chapter", [
            CourseContentV2Controller::class,
            "destroyChapter",
        ])->name("course-contents-v2.chapters.destroy");

        Route::get("course-contents-v2/create-lesson", [
            CourseContentV2Controller::class,
            "createLesson",
        ])->name("course-contents-v2.lessons.create");
        Route::post("course-contents-v2/create-lesson", [
            CourseContentV2Controller::class,
            "storeLesson",
        ])->name("course-contents-v2.lessons.store");
        Route::get("course-contents-v2/edit-lesson", [
            CourseContentV2Controller::class,
            "editLesson",
        ])->name("course-contents-v2.lessons.edit");
        Route::post("course-contents-v2/{id}/update-lesson", [
            CourseContentV2Controller::class,
            "updateLesson",
        ])->name("course-contents-v2.lessons.update");
        Route::delete("course-contents-v2/{id}/lesson", [
            CourseContentV2Controller::class,
            "destroyLesson",
        ])->name("course-contents-v2.lessons.destroy");

        Route::post("course-contents-v2/chapter/{chapter}/sort-lesson", [
            CourseContentV2Controller::class,
            "sortLesson",
        ])->name("course-contents-v2.chapters.sort-lesson");
        Route::get("course-contents-v2/{course}/sort-chapter", [
            CourseContentV2Controller::class,
            "sortChapter",
        ])->name("course-contents-v2.sort-chapters");
        Route::post("course-contents-v2/{course}/sort-chapter", [
            CourseContentV2Controller::class,
            "updateSortChapter",
        ])->name("course-contents-v2.sort-chapters.update");

        Route::get("course-content/{course}/create-chapter", [
            CourseContentController::class,
            "createChapterModal",
        ])
            ->whereNumber("course")
            ->name("course-content.create-chapter");
        Route::post("course-content/{course}/create-chapter", [
            CourseContentController::class,
            "storeChapter",
        ])
            ->whereNumber("course")
            ->name("course-content.store-chapter");
        Route::get("course-content/{chapter}/edit-chapter", [
            CourseContentController::class,
            "editChapterModal",
        ])
            ->whereNumber("chapter")
            ->name("course-content.edit-chapter");
        Route::post("course-content/{chapter}/edit-chapter", [
            CourseContentController::class,
            "updateChapterModal",
        ])
            ->whereNumber("chapter")
            ->name("course-content.update-chapter");
        Route::delete("course-content/{chapter}/chapter", [
            CourseContentController::class,
            "destroyChapter",
        ])
            ->whereNumber("chapter")
            ->name("course-content.destory-chapter");

        Route::get("course-content/create-lesson", [
            CourseContentController::class,
            "createLesson",
        ])->name("course-content.create-lesson");
        Route::post("course-content/create-lesson", [
            CourseContentController::class,
            "storeLesson",
        ])->name("course-content.store-lesson");

        Route::get("course-content/edit-lesson", [
            CourseContentController::class,
            "editLesson",
        ])->name("course-content.edit-lesson");
        Route::post("course-content/{id}/update-lesson", [
            CourseContentController::class,
            "updateLesson",
        ])
            ->whereNumber("id")
            ->name("course-content.update-lesson");
        Route::delete("course-content/{id}/lesson", [
            CourseContentController::class,
            "destroyLesson",
        ])
            ->whereNumber("id")
            ->name("course-content.destroy-lesson");

        /** Faculties & Specialties (Application) */
        Route::resource(
            "application-faculties",
            ApplicationFacultyController::class,
        )->parameters([
            "application-faculties" => "application_faculty",
        ]);
        Route::resource(
            "application-specialties",
            ApplicationSpecialtyController::class,
        )->parameters([
            "application-specialties" => "application_specialty",
        ]);
        Route::resource(
            "application-countries",
            ApplicationCountryController::class,
        )->parameters([
            "application-countries" => "application_country",
        ]);
        Route::resource(
            "application-cities",
            ApplicationCityController::class,
        )->parameters([
            "application-cities" => "application_city",
        ]);
        Route::resource(
            "application-degrees",
            ApplicationDegreeController::class,
        )->parameters([
            "application-degrees" => "application_degree",
        ]);
        Route::resource(
            "application-org-types",
            ApplicationOrgTypeController::class,
        )->parameters([
            "application-org-types" => "application_org_type",
        ]);
        Route::resource(
            "application-course-languages",
            ApplicationCourseLanguageController::class,
        )->parameters([
            "application-course-languages" => "application_course_language",
        ]);
        // Application Courses settings
        Route::get("application-courses", [
            ApplicationCourseController::class,
            "index",
        ])->name("application-courses.index");
        Route::get("application-courses/{course}/edit", [
            ApplicationCourseController::class,
            "edit",
        ])
            ->name("application-courses.edit")
            ->whereNumber("course");
        Route::put("application-courses/{course}", [
            ApplicationCourseController::class,
            "update",
        ])
            ->name("application-courses.update")
            ->whereNumber("course");

        Route::post("course-chapter/{chapter}/sort-lesson", [
            CourseContentController::class,
            "sortLesson",
        ])
            ->whereNumber("chapter")
            ->name("course-chapter.sort-lesson");

        Route::get("course-content/{course}/sort-chapter", [
            CourseContentController::class,
            "sortChapter",
        ])
            ->whereNumber("course")
            ->name("course-content.sort-chpater");
        Route::post("course-content/{course}/sort-chapter", [
            CourseContentController::class,
            "updateSortChapter",
        ])
            ->whereNumber("course")
            ->name("course-content.update-sort-chpater");

        /** Order Routes */
        Route::get("orders", [OrderController::class, "index"])->name(
            "orders.index",
        );
        Route::get("orders/{order}", [OrderController::class, "show"])->name(
            "orders.show",
        );

        /** Payment setting routes */
        Route::get("payment-setting", [
            PaymentSettingController::class,
            "index",
        ])->name("payment-setting.index");
        Route::post("paypal-setting", [
            PaymentSettingController::class,
            "paypalSetting",
        ])->name("paypal-setting.update");
        Route::post("stripe-setting", [
            PaymentSettingController::class,
            "stripeSetting",
        ])->name("stripe-setting.update");
        Route::post("razorpay-setting", [
            PaymentSettingController::class,
            "razorpaySetting",
        ])->name("razorpay-setting.update");

        /** Site Settings Route */
        Route::get("settings", [SettingController::class, "index"])->name(
            "settings.index",
        );
        Route::post("general-settings", [
            SettingController::class,
            "updateGeneralSettings",
        ])->name("general-settings.update");

        Route::get("commission-settings", [
            SettingController::class,
            "commissionSettingIndex",
        ])->name("commission-settings.index");
        Route::post("commission-settings", [
            SettingController::class,
            "updateCommissionSetting",
        ])->name("commission-settings.update");

        Route::get("smtp-settings", [
            SettingController::class,
            "smtpSetting",
        ])->name("smtp-settings.index");
        Route::post("smtp-settings", [
            SettingController::class,
            "updateSmtpSetting",
        ])->name("smtp-settings.update");

        Route::get("logo-settings", [
            SettingController::class,
            "logoSettingIndex",
        ])->name("logo-settings.index");
        Route::post("logo-settings", [
            SettingController::class,
            "updateLogoSetting",
        ])->name("logo-settings.update");
        /** Payout Gateway Routes */
        Route::resource("payout-gateway", PayoutGatewayController::class);

        /** Withdrawal routes */
        Route::get("withdraw-requests", [
            WithdrawRequestController::class,
            "index",
        ])->name("withdraw-request.index");
        Route::get("withdraw-requests/{withdraw}/details", [
            WithdrawRequestController::class,
            "show",
        ])->name("withdraw-request.show");
        Route::post("withdraw-requests/{withdraw}/status", [
            WithdrawRequestController::class,
            "updateStatus",
        ])->name("withdraw-request.status.update");

        /** Certificate Builder Routes */
        Route::get("certificate-builder", [
            CertificateBuilderController::class,
            "index",
        ])->name("certificate-builder.index");
        Route::post("certificate-builder", [
            CertificateBuilderController::class,
            "update",
        ])->name("certificate-builder.update");
        Route::post("certificate-item", [
            CertificateBuilderController::class,
            "itemUpdate",
        ])->name("certificate-item.update");

        /** Issued certificates list */
        Route::get("issued-certificates", [
            IssuedCertificateController::class,
            "index",
        ])->name("issued-certificates.index");
        Route::post("issued-certificates", [
            IssuedCertificateController::class,
            "store",
        ])->name("issued-certificates.store");
        Route::get("issued-certificates/{issued}", [
            IssuedCertificateController::class,
            "show",
        ])
            ->whereNumber("issued")
            ->name("issued-certificates.show");
        Route::post("issued-certificates/{issued}/generate", [
            IssuedCertificateController::class,
            "generate",
        ])
            ->whereNumber("issued")
            ->name("issued-certificates.generate");
        /** Students management */
        Route::get("students", [StudentsController::class, "index"])->name(
            "students.index",
        );
        Route::get("students/create", [
            StudentsController::class,
            "create",
        ])->name("students.create");
        Route::post("students", [StudentsController::class, "store"])->name(
            "students.store",
        );
        Route::get("students/{user}", [StudentsController::class, "show"])
            ->whereNumber("user")
            ->name("students.show");
        Route::get("students/{user}/edit", [StudentsController::class, "edit"])
            ->whereNumber("user")
            ->name("students.edit");
        Route::put("students/{user}", [StudentsController::class, "update"])
            ->whereNumber("user")
            ->name("students.update");
        Route::delete("students/{user}", [StudentsController::class, "destroy"])
            ->whereNumber("user")
            ->name("students.destroy");
        Route::post("students/{user}/issue-certificate", [
            StudentsController::class,
            "issueCertificate",
        ])
            ->whereNumber("user")
            ->name("students.issue-certificate");

        /** Instructors management */
        Route::get("instructors", [
            InstructorsController::class,
            "index",
        ])->name("instructors.index");
        Route::get("instructors/create", [
            InstructorsController::class,
            "create",
        ])->name("instructors.create");
        Route::post("instructors", [
            InstructorsController::class,
            "store",
        ])->name("instructors.store");
        Route::get("instructors/{instructor}/edit", [
            InstructorsController::class,
            "edit",
        ])
            ->whereNumber("instructor")
            ->name("instructors.edit");
        Route::put("instructors/{instructor}", [
            InstructorsController::class,
            "update",
        ])
            ->whereNumber("instructor")
            ->name("instructors.update");

        /** Users management */
        Route::get("users", [UsersController::class, "index"])->name(
            "users.index",
        );
        Route::get("users/{user}/edit", [UsersController::class, "edit"])
            ->whereNumber("user")
            ->name("users.edit");
        Route::put("users/{user}", [UsersController::class, "update"])
            ->whereNumber("user")
            ->name("users.update");

        /** Course Applications */
        Route::get("course-applications", [
            CourseApplicationAdminController::class,
            "index",
        ])->name("course-applications.index");
        Route::get("course-applications/{application}", [
            CourseApplicationAdminController::class,
            "show",
        ])->name("course-applications.show");
        Route::post("course-applications/{application}/status", [
            CourseApplicationAdminController::class,
            "updateStatus",
        ])->name("course-applications.status");

        /** Course Sessions (scheduling) */
        Route::get("course-sessions", [
            CourseSessionController::class,
            "index",
        ])->name("course-sessions.index");
        Route::get("course-sessions/create", [
            CourseSessionController::class,
            "create",
        ])->name("course-sessions.create");
        Route::post("course-sessions", [
            CourseSessionController::class,
            "store",
        ])->name("course-sessions.store");
        Route::get("course-sessions/{course_session}/edit", [
            CourseSessionController::class,
            "edit",
        ])->name("course-sessions.edit");
        Route::put("course-sessions/{course_session}", [
            CourseSessionController::class,
            "update",
        ])->name("course-sessions.update");
        Route::delete("course-sessions/{course_session}", [
            CourseSessionController::class,
            "destroy",
        ])->name("course-sessions.destroy");

        /** Simple Course Routes (упрощенное управление курсами) */
        Route::get("simple-courses", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "index",
        ])->name("simple-courses.index");
        Route::get("simple-courses/create", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "create",
        ])->name("simple-courses.create");
        Route::post("simple-courses", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "store",
        ])->name("simple-courses.store");
        Route::get("simple-courses/{id}/edit", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "edit",
        ])->name("simple-courses.edit");
        Route::put("simple-courses/{id}", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "update",
        ])->name("simple-courses.update");
        Route::delete("simple-courses/{id}", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "destroy",
        ])->name("simple-courses.destroy");
        Route::patch("simple-courses/{id}/toggle-approval", [
            \App\Http\Controllers\Admin\SimpleCourseController::class,
            "toggleApproval",
        ])->name("simple-courses.toggle-approval");

        /** Hero Routes */
        Route::resource("hero", HeroController::class);
        /** Feature Routes */
        Route::resource("feature", FeatureController::class);

        /** Feature Routes */
        Route::resource("about-section", AboutUsSectionController::class);

        /** Latest Courses Routes */
        Route::resource(
            "latest-courses-section",
            LatestCourseSectionController::class,
        );

        /** Become Instructor Section Routes */
        Route::resource(
            "become-instructor-section",
            BecomeInstructorSectionController::class,
        );

        /** Video Section Routes */
        Route::resource("video-section", VideoSectionController::class);

        /** Video Section Routes */
        Route::resource("brand-section", BrandSectionController::class);

        /** Featured Instructor Section Routes */
        Route::get("get-instructor-courses/{id}", [
            FeaturedInstructorController::class,
            "getInstructorCourses",
        ])->name("get-instructor-courses");
        Route::resource(
            "featured-instructor-section",
            FeaturedInstructorController::class,
        );

        /** Video Section Routes */
        Route::resource("testimonial-section", TestimonialController::class)
            ->parameters([
                "testimonial-section" => "testimonial",
            ])
            ->scoped([
                "testimonial" => "id",
            ]);

        /** Counter Routes */
        Route::resource("counter-section", CounterController::class);

        /** Contact Routes */
        Route::resource("contact", ContactController::class);

        /** Contact Setting Routes */
        Route::resource("contact-setting", ContactSettingController::class);

        /** Review Routes */
        Route::resource("reviews", ReviewController::class);

        /** Top bar routes */
        Route::resource("top-bar", TopBarController::class);

        /** Footer routes */
        Route::resource("footer", FooterController::class);

        /** Social links routes */
        Route::resource("social-links", SocialLinkController::class);

        /** footer column one routes */
        Route::resource("footer-column-one", FooterColumnOneController::class);

        /** footer column one routes */
        Route::resource("footer-column-two", FooterColumnTwoController::class);

        /** footer column one routes */
        Route::resource("custom-page", CustomPageController::class);

        /** Services routes */
        Route::resource("services", ServiceController::class);

        /** Documents routes */
        Route::resource("documents", DocumentController::class)->parameters([
            "documents" => "document",
        ]);

        /** blog category routes */
        Route::resource(
            "blog-categories",
            BlogCategoryController::class,
        )->parameters(["blog-categories" => "blog_category"]);

        /** blog routes */
        Route::resource("blogs", BlogController::class)->parameters([
            "blogs" => "blog",
        ]);

        /** Upload image from TinyMCE editor */
        Route::post("upload-editor-image", [
            BlogController::class,
            "uploadEditorImage",
        ])->name("upload-editor-image");

        /** About Us routes */
        Route::resource("about-us", AboutUsController::class)->parameters([
            "about-us" => "about_us",
        ]);

        /** Database Clear Routes */
        Route::get("database-clear", [
            DatabaseClearController::class,
            "index",
        ])->name("database-clear.index");
        Route::delete("database-clear", [
            DatabaseClearController::class,
            "destroy",
        ])->name("database-clear.destroy");

        /** lfm Routes */
        Route::group(
            [
                "prefix" => "laravel-filemanager",
                "middleware" => ["web", "auth:admin"],
            ],
            function () {
                \UniSharp\LaravelFilemanager\Lfm::routes();
            },
        );
    },
);
