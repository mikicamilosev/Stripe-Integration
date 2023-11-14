<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

class ASRole
{
    public const ROLE_USER = 1;
    public const ROLE_EDITOR = 2;
    public const ROLE_ADMIN = 3;

    /**
     * @var ASDatabase Instance of ASDatabase class
     */
    private $db = null;

    /**
     * @var ASValidator Instance of ASValidator class
     */
    private $validator;

    /**
     * Class constructor
     * @param ASDatabase $db
     * @param ASValidator $validator
     */
    public function __construct(ASDatabase $db, ASValidator $validator)
    {
        $this->db = $db;
        $this->validator = $validator;
    }

    /**
     * Get role id of role that have provided role name.
     *
     * @param $name string Role name
     * @return int Role id if role with provided role name exist, null otherwise.
     */
    public function getId(string $name): ?int
    {
        $result = $this->db->select(
            "SELECT `role_id` FROM `as_user_roles` WHERE `role` = :r",
            ['r' => $name]
        );

        return $result !== []
            ? (int) $result[0]['role_id']
            : null;
    }

    /**
     * Get role name of role with provided id.
     *
     * @param $id int Role id
     * @return string Role Name if role with provided role id exist, null otherwise.
     */
    public function name(int $id): ?string
    {
        $result = $this->db->select(
            "SELECT `role` FROM `as_user_roles` WHERE `role_id` = :id",
            ['id' => $id]
        );

        return $result !== []
            ? $result[0]['role']
            : null;
    }

    /**
     * Add new role into db.
     *
     * @param $name string Role name
     */
    public function add(string $name): void
    {
        if ($this->validator->roleExist($name)) {
            ASResponse::validationError(["name" => trans('role_taken')]);
        }

        $name = strtolower(strip_tags($name));

        $this->db->insert("as_user_roles", ["role" => $name]);

        ASResponse::success([
            "role_name" => $name,
            "role_id" => $this->db->lastInsertId()
        ]);
    }

    /**
     * Delete role with provided id.
     *
     * @param $id int Role id
     */
    public function delete(int $id): void
    {
        //default user roles can't be deleted
        if (in_array($id, [self::ROLE_USER, self::ROLE_EDITOR, self::ROLE_ADMIN])) {
            exit();
        }

        $this->db->delete("as_user_roles", "role_id = :id", ["id" => $id]);

        // Since provided role is deleted, we will
        // update all users who had this role to use
        // default "User" role now.
        $this->db->update(
            "as_users",
            ['user_role' => self::ROLE_USER],
            "user_role = :r",
            ["r" => $id]
        );

        ASResponse::success();
    }
}
