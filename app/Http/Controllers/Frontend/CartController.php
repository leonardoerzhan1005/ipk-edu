<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //

    function index(string $locale) : View
    {
        $cart = Cart::with(['course'])->where(['user_id' => user()->id])->paginate();
        return view('frontend.pages.cart', compact('cart'));
    }

    function addToCart(string $locale, int $id) : Response
    {

      if(!Auth::guard('web')->check()){
          return response(['message' => 'Please Login First!'], 401);
      }
      if(user()->enrollments()->where(['course_id' => $id])->exists()){
        return response(['message' => 'Already Enrolled!'], 200);
      }

      // Temporarily bypass cart and enroll immediately. Remove any existing cart item for this course
      Cart::where(['course_id' => $id, 'user_id' => Auth::guard('web')->user()->id])->delete();

      if(user()?->role == 'instructor') {
        return response(['message' => 'Please use a user account for add to cart!'], 401);

      }


      $course = Course::findOrFail($id);
      Enrollment::firstOrCreate([
          'user_id' => Auth::guard('web')->user()->id,
          'course_id' => $course->id,
          'instructor_id' => $course->instructor_id,
      ], [
          'have_access' => true,
      ]);

      $cartCount = cartCount();

      return response(['message' => 'Enrolled Successfully!', 'cart_count' => $cartCount], 200);

    }

    function removeFromCart(string $locale, int $id) : RedirectResponse
    {
        $cart = Cart::where(['id' => $id, 'user_id' => user()->id])->firstOrFail();
        $cart->delete();
        notyf()->success('Removed Successfully!');
        return redirect()->back();
    }
}
