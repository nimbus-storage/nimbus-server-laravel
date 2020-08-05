<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;

class NimbusController extends Controller
{
    
    protected $storagePath = '/app/data/';

    public function hello (Request $request) {
        return $this->ok(["message" => "Hello, Nimbus"]);
    }

    protected function getUserFile($filename){
        $file = new \SplFileInfo( storage_path() . $this->storagePath . $filename );
        
        if ( ! $file->isReadable() ){
            return false;
        }

        return file_get_contents($file);
    }

    protected function setUserFile($filename, $content){
        $file = new \SplFileInfo( storage_path()  . $this->storagePath . $filename );
        $path = $file->getPathInfo();

        if (! $path->isDir()) {
            mkdir($path->getPathname(), 0777, true);
        }
    
        return file_put_contents($file, $content);
    }

    protected function getLoggedUser(){
        if (Session::has('user')) {
            return Session::get('user');
        }

        return null;
    }

    public function login (Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
        $passwordFile = "{$email}/_password.txt";

        if($storedPassword = $this->getUserFile($passwordFile)){

            if(! password_verify($password, $storedPassword)) {
                return $this->nok(["message" => "Wrong password"], 401);
            } else {
                Session::put('user', $email);
                return $this->ok(["message" => "Welcome back."]);
            }
        }

        $result = $this->setUserFile($passwordFile, $encryptedPassword);
        return $this->ok(["message" => "Welcome.", "res" => $result]);
    }

    public function logout (Request $request) {
        Session::forget('user');

        return $this->ok(["message" => "Bye."]);
    }

    public function list (Request $request) {
        $user = $this->getLoggedUser();
        
        if (! $user) {
            return $this->nok(["message" => "Please login first"], 401);
        }
    }

    public function get (Request $request) {
        $user = $this->getLoggedUser();
        
        if (! $user) {
            return $this->nok(["message" => "Please login first"], 401);
        }

        $email = $request->input('email');
        $file = $request->input('file');

        if ($user != $email) {
            return $this->nok(["message" => "Not allowed"], 401);
        }

        $data = $this->getUserFile("{$email}/{$file}/content.txt");
        return $this->ok(["message" => "Here is your file.", "file" => $data]);
    }

    public function store (Request $request) {
        $user = $this->getLoggedUser();
        
        if (! $user) {
            return $this->nok(["message" => "Please login first"], 401);
        }

        $email = $request->input('email');
        $file = $request->input('file');
        $content = $request->input('content');

        $size = $this->setUserFile("{$email}/{$file}/content.txt", $content);

        return $this->ok(["message" => "Stored.", "size" => $size]);


    }
}
