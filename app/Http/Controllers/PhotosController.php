<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use App\Tweets;
use App\users;

class PhotosController extends Controller
{
     public function index()
    {
    	
    }

    public function create()
    {
        return view('photos.create');
    }

    public function store(Request $request){
    	$this->validate($request,[
    		'photo' => 'image|max:1999'
    	]);

   	// Get filename with extension
   	$wholeFilename = $request->file('photo')->getClientOriginalName();
    	
    // Get just the filename
   	$filename = pathinfo($wholeFilename, PATHINFO_FILENAME);

   	// Get extension
   	$extension = $request->file('photo')->getClientOriginalExtension();

   	// Create new filename
   	$newfilename = $filename.'_'.time().'.'.$extension;

   	// Upload image
   	$path = $request->file('photo')->storeAs('public/Photos/', $newfilename);

   	// Create
   	$photo = new Photo;
   	$photo->user_id = auth()->user()->id;
   	$photo->tweet_text = $request->input('tweet_text');
   	$photo->photo = $newfilename;

   	$photo->save();

   	// Create
   	$tweet = new Tweets;
   	$tweet->user_id = auth()->user()->id;
    $tweet->tweet_text = $request->input('tweet_text');
    $tweet->photo_id = $photo->id;
   	$tweet->like_cnt = 0;
   	$tweet->reply_cnt = 0;
   	$tweet->photo = $newfilename;
   	$tweet->time_posted = now();
   	$tweet->save();

   	return redirect('/home')->with('success', 'Photo Uploaded');

    }

}