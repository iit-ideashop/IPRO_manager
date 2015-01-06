<?php

class KioskController extends BaseController{

    //This function will show the kiosk page where we ask for a 4 digit code to show the signature/package pickup page
    function showKiosk(){
        return View::make('kiosk.showKiosk');
    }

    //This function will show the package pickup page. We will take the 4 digit code and lookup the pickup in the db
    //Next we will take the pickup and show the details of the pickup and ask for a signature from the user.
    function showPackagePickup(){
        $pincode = Input::get("PINcode");
        return Redirect::route('kiosk.showKiosk')->with('error',array('The specified pickup code does not exist'));

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
        imagestring($png, 5, 20, 177, "Bartlomiej Dworak", $black);

        ob_start ();

        imagepng($png);
        $image_data = ob_get_contents ();

        ob_end_clean ();

        $png = base64_encode($image_data);
        View::share('sigpad',$png);
        return View::make('kiosk.pickupPackage');
    }


}

