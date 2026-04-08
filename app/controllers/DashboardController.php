<?php
class DashboardController extends Controller {

    public function index() {
        session_start();

        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'index.php?url=auth');
            exit;
        }

        $transaksi = $this->model('Transaksi');
        $data['rows'] = $transaksi->getAll();

        $this->view('dashboard', $data);
    }
}
