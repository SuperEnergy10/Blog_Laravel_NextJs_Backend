<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    //API Slider

    public function ApiAllSlider(){
        $slider = Slider::latest()->get();
        return $slider;
    }


    // end Api Slider

    public function AllSlider(){
        $slider = Slider::latest()->get();

        return view('backend.slider.all_slider', compact('slider'));
    }


    public function AddSlider(){

        return view('backend.slider.add_slider');
    }


    public function StoreSlider(Request $request){
        if($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(1124, 750)->save(public_path('upload/sliders/'.$name_gen));
            $save_url = 'upload/slider/'.$name_gen;
    
            Slider::create([
                'heading' => $request->heading,
                'description' => $request->description,
                'link' => $request->link,
                'image' => $save_url,
            ]);
        }
    
        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.slider')->with($notification);
    }
    


    public function EditSlider($id){
        $slider = Slider::find($id);

        return view('backend.slider.edit_slider', compact('slider'));
    }


    public function UpdateSlider(Request $request){
        $slider_id = $request->id;
        $slider = Slider::find($slider_id);
    
        $data = [
            'heading' => $request->heading,
            'description' => $request->description,
            'link' => $request->link,
        ];
    
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    
            $img = $manager->read($image);
            $img->resize(1124, 750)->save(public_path('upload/sliders/'.$name_gen));
            $save_url = 'upload/sliders/'.$name_gen;
    
            // Xóa ảnh cũ nếu tồn tại
            if (file_exists(public_path($slider->image))) {
                @unlink(public_path($slider->image));
            }
    
            $data['image'] = $save_url;
            $message = 'Slider Updated with Image Successfully';
        } else {
            $message = 'Slider Updated without Changing Image';
        }
    
        $slider->update($data);
    
        $notification = array(
            'message' => $message,
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.slider')->with($notification);
    }

    

    public function DeleteSlider($id){
        $item = Slider::find($id);
    
        if (!$item) {
            $notification = array(
                'message' => 'Slider Not Found',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    
        // Xóa ảnh nếu tồn tại
        $img_path = public_path($item->image);
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    
        // Xóa slider
        $item->delete();
    
        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    

}
