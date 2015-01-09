<?php

class KioskController extends BaseController{

    //This function will show the kiosk page where we ask for a 4 digit code to show the signature/package pickup page
    function showKiosk(){
        return View::make('kiosk.showKiosk');
    }

    //This function will show the package pickup page. We will take the 4 digit code and lookup the pickup in the db
    //Next we will take the pickup and show the details of the pickup and ask for a signature from the user.
    function showPackagePickup(){
        //Pincode should be a number between 1000-9999
        $pincode = intval(Input::get("PINcode"));
        if($pincode == null){
            return Redirect::route("kiosk.showKiosk")->with("error",array("please enter a pincode to view a pickup"));
        }
        //Use the pincode to find the appropriate pickup request
        $pickup = Pickup::where("RetreiveCode","=",$pincode)->where("Completed","=",false)->get();
        $pickup = $pickup[0];
        if($pickup == null) {
            return Redirect::route('kiosk.showKiosk')->with('error', array('The specified pickup code does not exist'));
        }
        //Next we proceed to pull the items from the pickup
        //Lets take the pickup and find the pickup's items.
        $pickupItems = $pickup->PickupItems()->lists("ItemID");
        //Grab the items for each pickupItem
        $items = Item::WhereIn("id",$pickupItems)->get();
        $student = User::find($pickup->PersonID);
        View::share("pickup",$pickup);
        View::share("items",$items);
        View::share("student",$student);
        $studentFullName = $student->FirstName." ".$student->LastName;
        $png = imagecreatetruecolor(550, 200);

        imagesavealpha($png, true);

        $trans_colour = imagecolorallocatealpha($png, 0, 0, 0, 127);
        imagefill($png, 0, 0, $trans_colour);

        $black = imagecolorallocate($png, 0, 0, 0);
        imageline($png, 5, 5, 545, 5, $black);
        imageline($png, 5, 5, 5, 195, $black);
        imageline($png, 5, 195, 545, 195, $black);
        imageline($png, 545, 5, 545, 195, $black);
        imageline($png, 20, 175, 530, 175, $black);
        imagestring($png, 5, 20, 158, "x", $black);
        imagestring($png, 5, 20, 177, $studentFullName, $black);

        ob_start ();

        imagepng($png);
        $image_data = ob_get_contents ();

        ob_end_clean ();

        $png = base64_encode($image_data);
        View::share('sigpad',$png);
        return View::make('kiosk.pickupPackage');
    }

    function completePackagePickup(){
        //Take the data posted from the pickup page and update the database.
        $sigData = Input::get("signatureData");
        $authCode = intval(Input::get("AuthorizeCode"));
        $pickupid = intval(Input::get("pickupid"));
        //Find the pickup in the database
        $pickup = Pickup::where("id",'=',$pickupid)->where("AuthorizeCode","=",$authCode)->limit(1)->get();
        if($pickup == null){
            return Redirect::route("kiosk.showKiosk")->with("error",array("Authorization mismatch. Please contact the administrator if you continue to have this issue."));
        }
        $pickup = $pickup[0];
        //We have a valid pickup. Update the sig data and pass it on to the admin, admin still has to approve.
        $pickup->SignatureData = $sigData;
        $pickup->save();
        return Redirect::route("kiosk.showKiosk")->with("success",array("Thank you for signing the pickup agreement"));
    }

}

