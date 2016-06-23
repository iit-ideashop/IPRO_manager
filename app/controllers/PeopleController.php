<?php
use Illuminate\Encryption\Encrypter;

class PeopleController extends BaseController {

        public function Create(){
            
            
            
            
        }

	protected function CryptoTest()
	{
                $data = Input::get('data');
                $client_id = Input::get("client_id");
                $encrypter = new Encrypter("e807f1fcf82d132f9bb018ca6738a19f");
                
                $enc_data = $encrypter->encrypt("Hello");
                
                $dec_data = $encrypter->decrypt($data);
               
                return Response::json(array('client_id'=>$client_id,'dec_data'=>$dec_data),200);
                
	}

}
