<?php
require 'MysqlAdapter.php';
require 'database_config.php';
class User extends MysqlAdapter
{
    private $table = "users";

    public function __construct(){
        global $config;

        parent::__construct($config);
    }

    /**
     * List all users
     * @return array|false array of users every user is an associative array || false on failure
     */
    public function getUsers()
    {
        $this->select($this->table);
        return $this->fetchAll();
    }

    /**
     * Get a user by id
     * @param int $id user id
     * @return array|false user as an associative array || false on failure
     */
    public function getUser($id)
    {
        $this->select($this->table, 'id = ' . $id);
        return $this->fetch();
    }

    /**
     * Add a new user
     * @param array $data user data
     * @return int user id
     */
    public function addUser($data){
        return $this->insert($this->table, $data);
    }

    /**
     * Update a user
     * @param int $id user id
     * @param array $data user data
     * @return int number of affected rows
     */
    public function updateUser($id, $data){
         return $this->update($this->table, $data, 'id = ' . $id);
     }

     /**
      * Delete a user
      * @param int $id user id
      * @return int number of affected rows
      */
     public function deleteUser($id){
         return $this->delete($this->table, 'id = ' . $id);
     }

     /**
      * Search for a user
      * @param string $search search string
      * @return array|false array of users every user is an associative array || false on failure
      */
     public function searchUsers($search){
         $this->select($this->table, 'name LIKE "%' . $search . '%" OR email LIKE "%' . $search . '%"');
         return $this->fetchAll();
     }
}