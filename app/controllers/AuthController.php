<?php
class AuthController extends Controller {

    public function index() {
        $this->view('login');
    }

    public function login() {
        session_start();

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->model('User');

        if ($user->login($username, $password)) {
            $_SESSION['login'] = true;
            header('Location: ' . BASEURL . 'index.php');
        } else {
            echo "Login gagal";
        }
    }
}
