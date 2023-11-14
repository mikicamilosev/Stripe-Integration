<?php

include_once __DIR__ . '/AS.php';

$action = $_POST['action'];

switch ($action) {
    case 'checkLogin':
        app('login')->userLogin($_POST['username'], $_POST['password']);
        break;

    case "registerUser":
        app('register')->register($_POST['user']);
        break;

    case "resetPassword":
        app('register')->resetPassword($_POST['new_password'], $_POST['key']);
        break;

    case "forgotPassword":
        app('register')->forgotPassword($_POST['email']);
        break;

    case "postComment":
        app('comment')->insertComment(ASSession::get("user_id"), $_POST['comment']);
        break;

    case "updatePassword":
        app('user')->updatePassword(
            ASSession::get("user_id"),
            [
                "old_password" => $_POST['old_password'],
                "new_password" => $_POST['new_password'],
                "new_password_confirmation" => $_POST['new_password_confirmation']
            ]
        );
        break;

    case "updateDetails":
        app('user')->updateDetails(ASSession::get("user_id"), $_POST['details']);
        break;

    case "changeRole":
        onlyAdmin();

        $result = app('user')->changeRole($_POST['userId'], $_POST['role']);
        respond(['role' => ucfirst($result)]);
        break;

    case "deleteUser":
        onlyAdmin();

        $userId = (int)$_POST['userId'];
        $users = app('user');

        if (!$users->isAdmin($userId)) {
            $users->deleteUser($userId);
            respond(['status' => 'success']);
        }
        respond(['error' => 'Forbidden.'], 403);
        break;

    case "getUserDetails":
        onlyAdmin();

        respond(
            app('user')->getAll($_POST['userId'])
        );
        break;

    case "addRole":
        onlyAdmin();

        app('role')->add($_POST['role']);
        break;

    case "deleteRole":
        onlyAdmin();

        app('role')->delete($_POST['roleId']);
        break;


    case "addUser":
        onlyAdmin();

        respond(
            app('user')->add($_POST['user'])
        );
        break;

    case "updateUser":
        onlyAdmin();

        app('user')->updateUser($_POST['user']['user_id'], $_POST['user']);
        break;

    case "banUser":
        onlyAdmin();

        app('user')->updateInfo($_POST['userId'], ['banned' => 'Y']);
        respond(['status' => 'success']);
        break;

    case "unbanUser":
        onlyAdmin();

        app('user')->updateInfo($_POST['userId'], ['banned' => 'N']);
        respond(['status' => 'success']);
        break;

    case "getUser":
        onlyAdmin();

        respond(
            app('user')->getAll($_POST['userId'])
        );
        break;

    default:
        break;
}

function onlyAdmin()
{
    if (!(app('login')->isLoggedIn() && app('current_user')->is_admin)) {
        respond(['error' => 'Forbidden.'], 403);
    }
}
