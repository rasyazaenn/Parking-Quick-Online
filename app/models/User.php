<?php
class User extends Model {

    public function login($username, $password) {
        $username = $this->db->escape($username);
        $password = MD5($password);

        $result = $this->db->query(
            "SELECT * FROM users WHERE username='$username' AND password='$password'"
        );

        return $result->num_rows;
    }
}
